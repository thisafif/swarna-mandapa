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
        $this->baseUrl   = rtrim(trim(env('DOKU_BASE_URL', 'https://api-sandbox.doku.com')), '/');
        $this->clientId  = preg_replace('/\s+/', '', env('DOKU_CLIENT_ID', ''));
        $this->secretKey = preg_replace('/\s+/', '', env('DOKU_SECRET_KEY', ''));
    }

    // -------------------------------------------------------------------------
    // PUBLIC: Inisiasi pembayaran → redirect ke DOKU Checkout
    // -------------------------------------------------------------------------

    public function createPayment(Request $request)
    {
        // Validasi — method & channel hanya untuk UX di sisi kita, tidak dikirim ke DOKU
        $request->validate([
            'payment_method'  => 'required|in:CREDIT_CARD,VIRTUAL_ACCOUNT,EWALLET',
            'payment_channel' => 'nullable|string',
        ]);

        // Ambil booking
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

        // Auto-cancel jika sudah expired
        if ($booking->expires_at && now()->gt($booking->expires_at)) {
            $booking->update(['status' => 'CANCELLED']);
            return redirect()->route('booking.status', ['code' => $booking->booking_code])
                ->withErrors(['payment' => 'Waktu pembayaran telah habis. Booking dibatalkan.']);
        }

        // Hitung total
        $base       = (float) $booking->total_price;
        $tax        = (int) round($base * 0.11);
        $fee        = (int) round($base * 0.10);
        $grandTotal = (int) ($base + $tax + $fee);

        // Generate order ID
        $orderId = 'INV' . time() . rand(100, 999);
        $booking->update(['payment_order_id' => $orderId]);

        try {
            // DOKU Checkout adalah hosted page — semua payment method ditampilkan di sana
            // Kita tidak bisa pre-select channel lewat payload (akan error PAYMENT CHANNEL IS INACTIVE)
            // Pilihan user di UI kita hanya untuk UX saja
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

        if (! $this->validateNotificationSignature($request, $rawBody)) {
            Log::warning('[DOKU] callback: invalid signature', [
                'headers' => $request->headers->all(),
                'body'    => $rawBody,
            ]);
            return response()->json(['responseCode' => '4010000', 'responseMessage' => 'Unauthorized'], 401);
        }

        $payload           = json_decode($rawBody, true) ?? [];
        $orderId           = data_get($payload, 'order.invoice_number');
        $transactionStatus = strtoupper(data_get($payload, 'transaction.status', ''));

        if (! $orderId) {
            return response()->json(['responseCode' => '4000000', 'responseMessage' => 'Bad Request'], 400);
        }

        $booking = Booking::where('payment_order_id', $orderId)->first();
        if (! $booking) {
            Log::warning('[DOKU] callback: booking not found', ['order_id' => $orderId]);
            return response()->json(['responseCode' => '4040000', 'responseMessage' => 'Not Found'], 404);
        }

        $newStatus = match ($transactionStatus) {
            'SUCCESS'           => 'CONFIRMED',
            'EXPIRED', 'FAILED' => 'CANCELLED',
            default             => $booking->status,
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

        return response()->json(['responseCode' => '2000000', 'responseMessage' => 'OK'], 200);
    }

    // -------------------------------------------------------------------------
    // PUBLIC: Return page setelah user selesai di halaman DOKU
    // -------------------------------------------------------------------------

    public function returnPage(Request $request)
    {
        $code = session('booking_code');

        if ($code) {
            $booking = Booking::where('booking_code', $code)->first();
            if ($booking) {
                // If still pending, actively pull status from DOKU (helps local testing where webhooks fail)
                if ($booking->status === 'PENDING' && $booking->payment_order_id) {
                    $this->pullDokuStatus($booking);
                }
                return redirect()->route('booking.status', ['code' => $code]);
            }
        }

        return redirect()->route('booking.form');
    }

    // -------------------------------------------------------------------------
    // PRIVATE: Pull status from DOKU (GET)
    // -------------------------------------------------------------------------
    private function pullDokuStatus(Booking $booking): void
    {
        $path      = '/orders/v1/status/' . $booking->payment_order_id;
        $timestamp = now()->utc()->format('Y-m-d\TH:i:s\Z');
        $requestId = (string) Str::uuid();
        
        $digest = base64_encode(hash('sha256', '', true));

        $componentToSign =
            "Client-Id:{$this->clientId}\n" .
            "Request-Id:{$requestId}\n" .
            "Request-Timestamp:{$timestamp}\n" .
            "Request-Target:{$path}\n" .
            "Digest:{$digest}";

        $signature = 'HMACSHA256=' . base64_encode(hash_hmac('sha256', $componentToSign, $this->secretKey, true));

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Client-Id'         => $this->clientId,
                    'Request-Id'        => $requestId,
                    'Request-Timestamp' => $timestamp,
                    'Signature'         => $signature,
                    'Digest'            => 'SHA-256=' . $digest,
                ])
                ->get($this->baseUrl . $path);

            if ($response->successful()) {
                $status = strtoupper(data_get($response->json(), 'transaction.status', ''));
                if ($status === 'SUCCESS') {
                    $booking->update(['status' => 'CONFIRMED', 'paid_at' => now()]);
                } elseif (in_array($status, ['FAILED', 'EXPIRED'])) {
                    $booking->update(['status' => 'CANCELLED']);
                }
            }
        } catch (\Exception $e) {
            Log::error('[DOKU] pull status failed: ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // PRIVATE: Buat checkout session ke DOKU
    // Catatan: DOKU Checkout adalah hosted page, semua method tampil di sana.
    // Kita hanya kirim order + customer — DOKU yang handle pilihan payment method.
    // -------------------------------------------------------------------------

    private function createCheckoutSession(Booking $booking, string $orderId, int $grandTotal): string
    {
        $path      = '/checkout/v1/payment';
        $timestamp = now()->utc()->format('Y-m-d\TH:i:s\Z');
        $requestId = (string) Str::uuid();
        $phone     = $this->normalizePhone($booking->phone);

        // Payload minimal — jangan tambah payment_method_types atau virtual_account_info
        // DOKU akan tampilkan semua channel aktif di hosted page-nya
        $payload = [
            'order' => [
                'invoice_number' => $orderId,
                'amount'         => $grandTotal,
                'currency'       => 'IDR',
                'callback_url'   => route('payment.return'), // Redirect user back to merchant after payment
            ],
            'payment' => [
                'payment_due_date' => 60, // menit
            ],
            'customer' => [
                'name'  => trim($booking->first_name . ' ' . $booking->last_name),
                'email' => $booking->email,
                'phone' => $phone,
            ],
        ];

        $jsonBody = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $digest   = base64_encode(hash('sha256', $jsonBody, true));

        // ✅ Digest di component-to-sign TANPA prefix "SHA-256="
        $componentToSign =
            "Client-Id:{$this->clientId}\n" .
            "Request-Id:{$requestId}\n" .
            "Request-Timestamp:{$timestamp}\n" .
            "Request-Target:{$path}\n" .
            "Digest:{$digest}";

        $signature = 'HMACSHA256=' . base64_encode(
            hash_hmac('sha256', $componentToSign, $this->secretKey, true)
        );

        Log::info('[DOKU] Request', [
            'order_id'       => $orderId,
            'request_id'     => $requestId,
            'timestamp'      => $timestamp,
            'component_sign' => $componentToSign,
            'payload'        => $payload,
        ]);

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

        Log::info('[DOKU] Response', ['status_code' => $statusCode, 'body' => $result]);

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
        $path      = $request->getPathInfo();

        $digest = base64_encode(hash('sha256', $rawBody, true));

        $componentToSign =
            "Client-Id:{$clientId}\n" .
            "Request-Id:{$requestId}\n" .
            "Request-Timestamp:{$timestamp}\n" .
            "Request-Target:{$path}\n" .
            "Digest:{$digest}";

        $expectedSignature = 'HMACSHA256=' . base64_encode(
            hash_hmac('sha256', $componentToSign, $this->secretKey, true)
        );

        $signatureValid = hash_equals($expectedSignature, $signature);
        $clientIdValid  = hash_equals($this->clientId, $clientId);

        if (! $signatureValid || ! $clientIdValid) {
            Log::warning('[DOKU] Signature mismatch', [
                'expected'  => $expectedSignature,
                'received'  => $signature,
                'client_ok' => $clientIdValid,
                'path'      => $path,
            ]);
        }

        return $signatureValid && $clientIdValid;
    }

    // -------------------------------------------------------------------------
    // PRIVATE: Normalisasi nomor HP ke +62xxx
    // -------------------------------------------------------------------------

    private function normalizePhone(string $raw): string
    {
        $phone = preg_replace('/[^0-9+]/', '', trim($raw));

        if (str_starts_with($phone, '+62')) return $phone;
        if (str_starts_with($phone, '62'))  return '+' . $phone;
        if (str_starts_with($phone, '0'))   return '+62' . substr($phone, 1);

        return '+62' . $phone;
    }
}