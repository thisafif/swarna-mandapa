<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$bookings = App\Models\Booking::whereIn('status', ['PENDING', 'CONFIRMED'])->get(['check_in', 'check_out', 'status']);
echo 'count: ' . $bookings->count() . PHP_EOL;
foreach ($bookings as $b) {
    echo $b->check_in->toDateString() . ' -> ' . $b->check_out->toDateString() . ' (' . $b->status . ')' . PHP_EOL;
}
