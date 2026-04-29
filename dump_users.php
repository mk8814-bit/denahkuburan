<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$blocks = \App\Models\Grave::pluck('block_name')->unique();
echo "Blocks: " . json_encode($blocks) . "\n";
