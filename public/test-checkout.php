<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$clientId = preg_replace('/\s+/', '', env('DOKU_CLIENT_ID', ''));
$secretKey = preg_replace('/\s+/', '', env('DOKU_SECRET_KEY', ''));
$baseUrl = env('DOKU_BASE_URL', 'https://api-sandbox.doku.com');

$orderId = 'INV' . time() . rand(100, 999);
$path = '/checkout/v1/payment';
$timestamp = now()->utc()->format('Y-m-d\TH:i:s\Z');
$requestId = (string) Illuminate\Support\Str::uuid();

$payload = [
    'order' => [
        'invoice_number' => $orderId,
        'amount'         => 10000,
        'currency'       => 'IDR',
        'callback_url'   => 'http://localhost/callback'
    ],
    'payment' => ['payment_due_date' => 60],
    'customer' => ['name' => 'Test', 'email' => 'test@test.com', 'phone' => '08123456789']
];

$jsonBody = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
$digest   = base64_encode(hash('sha256', $jsonBody, true));

$componentToSign =
    "Client-Id:{$clientId}\n" .
    "Request-Id:{$requestId}\n" .
    "Request-Timestamp:{$timestamp}\n" .
    "Request-Target:{$path}\n" .
    "Digest:{$digest}";

$signature = 'HMACSHA256=' . base64_encode(
    hash_hmac('sha256', $componentToSign, $secretKey, true)
);

$response = Illuminate\Support\Facades\Http::timeout(15)
    ->withHeaders([
        'Client-Id'         => $clientId,
        'Request-Id'        => $requestId,
        'Request-Timestamp' => $timestamp,
        'Signature'         => $signature,
    ])
    ->post($baseUrl . $path, $payload);

var_dump($response->status());
