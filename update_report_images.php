<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$report = App\Models\Report::first();

if ($report) {
    $report->images = [
        'storage/reports/1785228597_6a686d35cd1b5.jpeg',
        'storage/reports/1785236836_6a688d6479691.jpeg'
    ];
    $report->save();
    
    echo "✅ Report ID {$report->id} updated!\n";
    echo "Images: " . json_encode($report->images) . "\n";
} else {
    echo "❌ No reports found\n";
}
