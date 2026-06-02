<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmedMail;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
    // PUBLIC: Inisiasi pembayaran
    // -------------------------------------------------------------------------

    public function createPayment(Request $request)
    {
        $request->validate([
            'payment_method'  => 'required|in:CREDIT_CARD,VIRTUAL_ACCOUNT,EWALLET',
            'payment_channel' => 'nullable|string',
        ]);

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

        if ($booking->expires_at && now()->gt($booking->expires_at)) {
            $booking->update(['status' => 'CANCELLED']);
            return redirect()->route('booking.status', ['code' => $booking->booking_code])
                ->withErrors(['payment' => 'Waktu pembayaran telah habis. Booking dibatalkan.']);
        }

        $grandTotal    = (int) $booking->total_price;
        $orderId       = 'INV' . time() . rand(100, 999);
        $diffInMinutes = (int) now()->diffInMinutes($booking->expires_at, false);
        $dueMinutes    = $diffInMinutes > 0 ? $diffInMinutes : 1;

        $booking->update(['payment_order_id' => $orderId]);

        try {
            $checkoutUrl = $this->createCheckoutSession($booking, $orderId, $grandTotal, $dueMinutes);
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
    // PUBLIC: Return page — dipanggil saat user klik "GO TO MERCHANT" dari DOKU
    // Langsung cek status ke DOKU API, jangan tunggu callback
    // -------------------------------------------------------------------------

    public function returnPage(Request $request)
{
    // DOKU redirect ke sini setelah user klik "GO TO MERCHANT"
    // HARUS verify ke DOKU API bahwa payment benar-benar SUCCESS
    
    $orderId = $request->query('order_id') ?? session('payment_order_id');
    $code    = $request->query('booking_code') ?? session('booking_code');
    
    if (! $orderId || ! $code) {
        return redirect()->route('booking.form');
    }
    
    $booking = Booking::where('booking_code', $code)->first();
    if (! $booking) {
        return redirect()->route('booking.form');
    }
    
    // Kalau sudah CONFIRMED, langsung ke status
    if ($booking->status === 'CONFIRMED') {
        $this->sendConfirmationEmail($booking);
        return redirect()->route('booking.status', ['code' => $booking->booking_code]);
    }
    
    // PENDING - verify ke DOKU API bahwa payment benar-benar sukses
    if ($booking->status === 'PENDING') {
        // Check payment status ke DOKU
        $isPaymentSuccess = $this->checkPaymentStatus($orderId);
        
        if ($isPaymentSuccess) {
            // Payment confirmed - update status
            $booking->update([
                'status'  => 'CONFIRMED',
                'paid_at' => now(),
            ]);
            $booking->refresh();
            $this->sendConfirmationEmail($booking);
            return redirect()->route('booking.status', ['code' => $booking->booking_code]);
        } else {
            // Payment BELUM berhasil - redirect ke pending page
            return redirect()->route('booking.pending', ['code' => $booking->booking_code]);
        }
    }
    
    return redirect()->route('booking.invoice');
}
 

    // -------------------------------------------------------------------------
    // PUBLIC: Callback dari DOKU (server-to-server)
    // -------------------------------------------------------------------------

    public function callback(Request $request)
    {
        $rawBody = $request->getContent();

        if (! $this->validateNotificationSignature($request, $rawBody)) {
            Log::warning('[DOKU] callback: invalid signature');
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

        if ($transactionStatus === 'SUCCESS') {
            $booking->refresh();
            $this->sendConfirmationEmail($booking);
        }

        Log::info('[DOKU] callback processed', [
            'order_id'   => $orderId,
            'tx_status'  => $transactionStatus,
            'new_status' => $newStatus,
        ]);

        return response()->json(['responseCode' => '2000000', 'responseMessage' => 'OK'], 200);
    }

    // -------------------------------------------------------------------------
    // PRIVATE: Cek status pembayaran langsung ke DOKU
    // -------------------------------------------------------------------------

    private function checkPaymentStatus(string $orderId): bool
    {
        try {
            $path      = '/orders/v1/status/' . $orderId;
            $timestamp = now()->utc()->format('Y-m-d\TH:i:s\Z');
            $requestId = (string) Str::uuid();

            // GET request — tidak ada body/digest
            $componentToSign =
                "Client-Id:{$this->clientId}\n" .
                "Request-Id:{$requestId}\n" .
                "Request-Timestamp:{$timestamp}\n" .
                "Request-Target:{$path}";

            $signature = 'HMACSHA256=' . base64_encode(
                hash_hmac('sha256', $componentToSign, $this->secretKey, true)
            );

            $response = Http::timeout(15)
                ->withHeaders([
                    'Client-Id'         => $this->clientId,
                    'Request-Id'        => $requestId,
                    'Request-Timestamp' => $timestamp,
                    'Signature'         => $signature,
                ])
                ->get($this->baseUrl . $path);

            $result = $response->json() ?? [];

            Log::info('[DOKU] checkPaymentStatus', [
                'order_id' => $orderId,
                'status'   => $response->status(),
                'body'     => $result,
            ]);

            $txStatus = strtoupper(
                data_get($result, 'transaction.status')
                ?? data_get($result, 'status')
                ?? ''
            );

            return in_array($txStatus, ['SUCCESS', 'PAID', 'SETTLEMENT']);

        } catch (\Exception $e) {
            Log::warning('[DOKU] checkPaymentStatus error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    // -------------------------------------------------------------------------
    // PRIVATE: Buat checkout session ke DOKU
    // -------------------------------------------------------------------------

    private function createCheckoutSession(
        Booking $booking,
        string $orderId,
        int $grandTotal,
        int $dueMinutes
    ): string {
        $path      = '/checkout/v1/payment';
        $timestamp = now()->utc()->format('Y-m-d\TH:i:s\Z');
        $requestId = (string) Str::uuid();
        $phone     = $this->normalizePhone($booking->phone);

        $payload = [
            'order' => [
                'invoice_number' => $orderId,
                'amount'         => $grandTotal,
                'currency'       => 'IDR',
                'callback_url'   => url('/payment/return?order_id='.$orderId.'&booking_code='.$booking->booking_code),
            ],
            'payment' => [
                'payment_due_date' => $dueMinutes,
            ],
            'customer' => [
                'name'  => trim($booking->first_name . ' ' . $booking->last_name),
                'email' => $booking->email,
                'phone' => $phone,
            ],
        ];

        $jsonBody = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $digest   = base64_encode(hash('sha256', $jsonBody, true));

        $componentToSign =
            "Client-Id:{$this->clientId}\n" .
            "Request-Id:{$requestId}\n" .
            "Request-Timestamp:{$timestamp}\n" .
            "Request-Target:{$path}\n" .
            "Digest:{$digest}";

        $signature = 'HMACSHA256=' . base64_encode(
            hash_hmac('sha256', $componentToSign, $this->secretKey, true)
        );

        Log::info('[DOKU] createCheckout request', [
            'order_id'   => $orderId,
            'request_id' => $requestId,
            'timestamp'  => $timestamp,
        ]);

        $response = Http::timeout(30)
            ->withHeaders([
                'Content-Type'      => 'application/json',
                'Client-Id'         => $this->clientId,
                'Request-Id'        => $requestId,
                'Request-Timestamp' => $timestamp,
                'Signature'         => $signature,
            ])
            ->withBody($jsonBody, 'application/json')
            ->post($this->baseUrl . $path);

        $statusCode = $response->status();
        $result     = $response->json() ?? [];

        Log::info('[DOKU] createCheckout response', ['status_code' => $statusCode, 'body' => $result]);

        $url = data_get($result, 'response.payment.url')
            ?? data_get($result, 'payment.url')
            ?? data_get($result, 'paymentUrl')
            ?? null;

        if ($statusCode !== 200 || ! $url) {
            $errMsg = data_get($result, 'responseMessage')
                ?? data_get($result, 'error.message')
                ?? data_get($result, 'message')
                ?? 'Gagal membuat sesi pembayaran.';
            if (is_array($errMsg)) $errMsg = implode(', ', $errMsg);
            throw new \Exception("DOKU Error [{$statusCode}]: {$errMsg}");
        }

        return $url;
    }

    // -------------------------------------------------------------------------
    // PRIVATE: Kirim email konfirmasi (hanya sekali)
    // -------------------------------------------------------------------------

    private function sendConfirmationEmail(Booking $booking): void
    {
        if ($booking->email_sent_at) return;

        try {
            Mail::to($booking->email)->send(new BookingConfirmedMail($booking));
            $booking->update(['email_sent_at' => now()]);
            Log::info('[Mail] Konfirmasi terkirim', ['booking_code' => $booking->booking_code]);
        } catch (\Exception $e) {
            Log::warning('[Mail] Gagal kirim email', ['error' => $e->getMessage()]);
        }
    }

    // -------------------------------------------------------------------------
    // PRIVATE: Validasi signature callback DOKU
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

        $expected = 'HMACSHA256=' . base64_encode(
            hash_hmac('sha256', $componentToSign, $this->secretKey, true)
        );

        return hash_equals($expected, $signature) && hash_equals($this->clientId, $clientId);
    }

    // -------------------------------------------------------------------------
    // PRIVATE: Normalisasi nomor HP
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