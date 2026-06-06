<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$clientId = preg_replace('/\s+/', '', env('DOKU_CLIENT_ID', ''));
$secretKey = preg_replace('/\s+/', '', env('DOKU_SECRET_KEY', ''));
$baseUrl = env('DOKU_BASE_URL', 'https://api-sandbox.doku.com');

$orderId = 'INV12345';
$path = '/orders/v1/status/' . $orderId;
$timestamp = now()->utc()->format('Y-m-d\TH:i:s\Z');
$requestId = (string) Illuminate\Support\Str::uuid();

$componentToSign =
    "Client-Id:{$clientId}\n" .
    "Request-Id:{$requestId}\n" .
    "Request-Timestamp:{$timestamp}\n" .
    "Request-Target:{$path}";

$signature = 'HMACSHA256=' . base64_encode(
    hash_hmac('sha256', $componentToSign, $secretKey, true)
);

try {
    $response = Illuminate\Support\Facades\Http::timeout(15)
        ->withHeaders([
            'Client-Id'         => $clientId,
            'Request-Id'        => $requestId,
            'Request-Timestamp' => $timestamp,
            'Signature'         => $signature,
        ])
        ->get($baseUrl . $path);

    var_dump($response->status());
    var_dump($response->json());
} catch (\Exception $e) {
    var_dump('Exception: ' . $e->getMessage());
}
