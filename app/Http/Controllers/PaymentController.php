<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    private string $baseUrl;
    private string $clientId;
    private string $secretKey;

    public function __construct()
    {
        // Strip ALL whitespace (spaces, tabs, newlines, BOM) — env values sering kotor
        $this->baseUrl   = rtrim(trim(env('DOKU_BASE_URL', 'https://api-sandbox.doku.com')), '/');
        $this->clientId  = preg_replace('/\s+/', '', env('DOKU_CLIENT_ID', ''));
        $this->secretKey = preg_replace('/\s+/', '', env('DOKU_SECRET_KEY', ''));
    }

    // -------------------------------------------------------------------------
    // PUBLIC: Inisiasi pembayaran (redirect ke DOKU Checkout)
    // -------------------------------------------------------------------------

    public function createPayment(Request $request)
    {
        // 1. Ambil booking dari session
        $code    = session('booking_code');
        $booking = $code ? Booking::where('booking_code', $code)->first() : null;

        if (! $booking) {
            return redirect()->route('booking.invoice')
                ->withErrors(['payment' => 'Booking tidak ditemukan.']);
        }

        if ($booking->status !== 'PENDING') {
            return redirect()->route('booking.status', ['code' => $booking->booking_code])
                ->withErrors(['payment' => 'Status booking tidak valid untuk pembayaran.']);
        }

        // 2. Hitung total
        $base       = (float) $booking->total_price;
        $tax        = (int) round($base * 0.11);
        $fee        = (int) round($base * 0.10);
        $grandTotal = (int) ($base + $tax + $fee);

        // 3. Generate order ID unik & simpan ke booking
        $orderId = 'INV' . time() . rand(100, 999);
        $booking->update(['payment_order_id' => $orderId]);

        // 4. Hit DOKU dan redirect
        try {
            $checkoutUrl = $this->createCheckoutSession($booking, $orderId, $grandTotal);
            return redirect($checkoutUrl);
        } catch (\Exception $e) {
            Log::error('[DOKU] createPayment failed', [
                'booking_code' => $booking->booking_code,
                'error'        => $e->getMessage(),
            ]);
            return redirect()->route('booking.invoice')
                ->withErrors(['payment' => $e->getMessage()]);
        }
    }

    // -------------------------------------------------------------------------
    // PUBLIC: Callback / Notification dari DOKU (server-to-server)
    // -------------------------------------------------------------------------

    public function callback(Request $request)
    {
        $rawBody = $request->getContent();

        // Validasi signature — tolak kalau tidak valid
        if (! $this->validateNotificationSignature($request, $rawBody)) {
            Log::warning('[DOKU] callback: invalid signature', [
                'headers' => $request->headers->all(),
                'body'    => $rawBody,
            ]);
            return response()->json([
                'responseCode'    => '4010000',
                'responseMessage' => 'Unauthorized',
            ], 401);
        }

        // Parse payload
        $payload = json_decode($rawBody, true) ?? [];
        $orderId           = data_get($payload, 'order.invoice_number');
        $transactionStatus = strtoupper(data_get($payload, 'transaction.status', ''));

        if (! $orderId) {
            return response()->json([
                'responseCode'    => '4000000',
                'responseMessage' => 'Bad Request: missing invoice_number',
            ], 400);
        }

        $booking = Booking::where('payment_order_id', $orderId)->first();
        if (! $booking) {
            Log::warning('[DOKU] callback: booking not found', ['order_id' => $orderId]);
            return response()->json([
                'responseCode'    => '4040000',
                'responseMessage' => 'Not Found',
            ], 404);
        }

        // Tentukan status baru
        $newStatus = match ($transactionStatus) {
            'SUCCESS' => 'CONFIRMED',
            'EXPIRED', 'FAILED' => 'CANCELLED',
            default   => $booking->status, // Jangan ubah kalau status tidak dikenal
        };

        $booking->update([
            'status'     => $newStatus,
            'paid_at'    => $transactionStatus === 'SUCCESS' ? now() : $booking->paid_at,
            'payment_id' => data_get($payload, 'transaction.id', $booking->payment_id),
        ]);

        Log::info('[DOKU] callback processed', [
            'order_id'   => $orderId,
            'tx_status'  => $transactionStatus,
            'new_status' => $newStatus,
        ]);

        // DOKU butuh response 200 dengan format ini
        return response()->json([
            'responseCode'    => '2000000',
            'responseMessage' => 'OK',
        ], 200);
    }

    // -------------------------------------------------------------------------
    // PUBLIC: Return page setelah user selesai di halaman DOKU
    // -------------------------------------------------------------------------

    public function returnPage(Request $request)
    {
        $code = session('booking_code');

        if ($code && Booking::where('booking_code', $code)->exists()) {
            return redirect()->route('booking.status', ['code' => $code]);
        }

        return redirect()->route('booking.form');
    }

    // -------------------------------------------------------------------------
    // PRIVATE: Buat checkout session ke DOKU
    // -------------------------------------------------------------------------

    private function createCheckoutSession(Booking $booking, string $orderId, int $grandTotal): string
    {
        $path      = '/checkout/v1/payment';
        // Format timestamp dengan milisecond dan +00:00 timezone
        $timestamp = now()->setTimezone('UTC')->format('Y-m-d\TH:i:s.vO');
        $requestId = (string) Str::uuid();

        // Normalisasi nomor HP ke format internasional +62xxx
        $phone = $this->normalizePhone($booking->phone);

        // Build payload — hanya field wajib dulu (lebih minimal = lebih aman saat debug)
        $payload = [
            'order' => [
                'invoice_number' => $orderId,
                'amount'         => $grandTotal,
                'currency'       => 'IDR',
            ],
            'customer' => [
                'name'  => trim($booking->first_name . ' ' . $booking->last_name),
                'email' => $booking->email,
                'phone' => $phone,
            ],
            'payment' => [
                'payment_due_date' => 60, // menit, booking expired dalam 60 menit
            ],
        ];

        // Encode JSON — WAJIB: no escaped slashes, no escaped unicode
        $jsonBody = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        // Digest = SHA-256 dari raw body, di-base64
        $digest = base64_encode(hash('sha256', $jsonBody, true));

        // Component to sign — urutan HARUS persis ini, case-sensitive
        // TIDAK ada trailing newline di baris terakhir
        $componentToSign =
            "Client-Id:{$this->clientId}\n" .
            "Request-Id:{$requestId}\n" .
            "Request-Timestamp:{$timestamp}\n" .
            "Request-Target:{$path}\n" .
            "Digest:SHA-256={$digest}";

        // Tanda tangan HMAC-SHA256
        $signature = 'HMACSHA256=' . base64_encode(
            hash_hmac('sha256', $componentToSign, $this->secretKey, true)
        );

        // Verify signature locally untuk debug
        $localVerify = hash_hmac('sha256', $componentToSign, $this->secretKey);
        $expectedB64 = base64_encode(hex2bin($localVerify));

        // Debug log
        Log::info('[DOKU] Signature Debug', [
            'component_raw_bytes' => strlen($componentToSign),
            'secret_key_length'   => strlen($this->secretKey),
            'secret_key_trimmed'  => trim($this->secretKey),
            'signature_generated' => $signature,
        ]);

        // Kirim ke DOKU
        $response = Http::timeout(30)
            ->withHeaders([
                'Content-Type'      => 'application/json',
                'Client-Id'         => $this->clientId,
                'Request-Id'        => $requestId,
                'Request-Timestamp' => $timestamp,
                'Signature'         => $signature,
                'Digest'            => 'SHA-256=' . $digest,
            ])
            ->withBody($jsonBody, 'application/json')
            ->post($this->baseUrl . $path);

        $statusCode = $response->status();
        $result     = $response->json() ?? [];

        Log::info('[DOKU] Response', [
            'status_code' => $statusCode,
            'body'        => $result,
        ]);

        // Ambil URL dari response — DOKU bisa return di beberapa key
        $url = data_get($result, 'response.payment.url')
            ?? data_get($result, 'payment.url')
            ?? data_get($result, 'paymentUrl')
            ?? null;

        if ($statusCode !== 200 || ! $url) {
            $errMsg = data_get($result, 'responseMessage')
                ?? data_get($result, 'error.message')
                ?? data_get($result, 'message')
                ?? 'Gagal membuat sesi pembayaran.';

            if (is_array($errMsg)) {
                $errMsg = implode(', ', $errMsg);
            }

            throw new \Exception("DOKU Error [{$statusCode}]: {$errMsg}");
        }

        return $url;
    }

    // -------------------------------------------------------------------------
    // PRIVATE: Validasi signature dari notifikasi DOKU
    // -------------------------------------------------------------------------

    private function validateNotificationSignature(Request $request, string $rawBody): bool
    {
        $clientId  = $request->header('Client-Id', '');
        $requestId = $request->header('Request-Id', '');
        $timestamp = $request->header('Request-Timestamp', '');
        $signature = $request->header('Signature', '');

        // Path harus persis seperti yang didaftarkan di DOKU dashboard
        // Kalau pakai prefix /api atau /public, sesuaikan di sini
        $path = $request->getPathInfo();

        // Digest dari raw body
        $digest = base64_encode(hash('sha256', $rawBody, true));

        // Rebuild component to sign dengan format yang sama
        $componentToSign =
            "Client-Id:{$clientId}\n" .
            "Request-Id:{$requestId}\n" .
            "Request-Timestamp:{$timestamp}\n" .
            "Request-Target:{$path}\n" .
            "Digest:{$digest}";

        $expectedSignature = 'HMACSHA256=' . base64_encode(
            hash_hmac('sha256', $componentToSign, $this->secretKey, true)
        );

        // hash_equals mencegah timing attack
        $signatureValid = hash_equals($expectedSignature, $signature);
        $clientIdValid  = hash_equals($this->clientId, $clientId);

        if (! $signatureValid || ! $clientIdValid) {
            Log::warning('[DOKU] Signature mismatch', [
                'expected'  => $expectedSignature,
                'received'  => $signature,
                'client_ok' => $clientIdValid,
                'path'      => $path,
                'digest'    => $digest,
            ]);
        }

        return $signatureValid && $clientIdValid;
    }

    // -------------------------------------------------------------------------
    // PRIVATE: Normalisasi nomor HP ke +62xxx
    // -------------------------------------------------------------------------

    private function normalizePhone(string $raw): string
    {
        // Bersihkan semua karakter selain digit dan +
        $phone = preg_replace('/[^0-9+]/', '', trim($raw));

        if (str_starts_with($phone, '+62')) {
            return $phone; // Sudah benar
        }

        if (str_starts_with($phone, '62')) {
            return '+' . $phone; // 62812... → +62812...
        }

        if (str_starts_with($phone, '0')) {
            return '+62' . substr($phone, 1); // 0812... → +62812...
        }

        // Asumsi sudah tanpa kode negara, misal 812...
        return '+62' . $phone;
    }
}