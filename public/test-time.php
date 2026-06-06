<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$timestamp = now()->utc()->format('Y-m-d\TH:i:s\Z');
var_dump("Laravel UTC time: " . $timestamp);
var_dump("PHP gmdate: " . gmdate('Y-m-d\TH:i:s\Z'));
