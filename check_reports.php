<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Report;
use App\Models\Category;

echo "\n";
echo "=============================================\n";
echo "  CEK LAPORAN DI DATABASE\n";
echo "=============================================\n\n";

// 1. Total reports
$total = Report::count();
echo "Total Reports: {$total}\n\n";

if ($total === 0) {
    echo "[INFO] Belum ada laporan di database.\n";
    echo "\nBuat laporan baru dari:\n";
    echo "  1. Flutter app (BUILD_DI_C.bat + RUN_KE_HP.bat)\n";
    echo "  2. Postman (POST /api/v1/reports)\n";
    exit;
}

// 2. Latest reports
echo "=============================================\n";
echo "  LAPORAN TERBARU (Last 5)\n";
echo "=============================================\n\n";

$reports = Report::with(['user', 'category'])
    ->latest()
    ->take(5)
    ->get();

foreach ($reports as $r) {
    echo "─────────────────────────────────────────\n";
    echo "ID       : {$r->id}\n";
    echo "Title    : {$r->title}\n";
    echo "Category : " . ($r->category ? $r->category->name : 'N/A') . "\n";
    echo "User     : " . ($r->user ? $r->user->name : 'N/A') . "\n";
    echo "Location : {$r->address}\n";
    echo "Status   : {$r->status}\n";
    
    // Images detail
    echo "Images   : ";
    if ($r->images && is_array($r->images) && count($r->images) > 0) {
        echo count($r->images) . " file(s)\n";
        foreach ($r->images as $img) {
            echo "           - {$img}\n";
        }
    } else {
        echo "(no images)\n";
    }
    
    echo "Created  : " . $r->created_at->format('Y-m-d H:i:s') . "\n";
}

echo "\n";
echo "=============================================\n";
echo "  DETAIL IMAGES FIELD\n";
echo "=============================================\n\n";

$withImages = Report::whereNotNull('images')
    ->whereRaw("JSON_LENGTH(images) > 0")
    ->latest()
    ->take(3)
    ->get(['id', 'title', 'images']);

if ($withImages->isEmpty()) {
    echo "[INFO] Tidak ada laporan dengan images\n";
    echo "\nImages field masih kosong [] karena:\n";
    echo "  1. Laporan dibuat sebelum perbaikan upload\n";
    echo "  2. Upload gagal (check logs)\n";
    echo "  3. Base64 format tidak benar\n";
} else {
    foreach ($withImages as $r) {
        echo "─────────────────────────────────────────\n";
        echo "Report ID    : {$r->id}\n";
        echo "Title        : {$r->title}\n";
        echo "Images Type  : " . gettype($r->images) . "\n";
        echo "Images Count : " . (is_array($r->images) ? count($r->images) : 0) . "\n";
        echo "Images JSON  :\n";
        echo json_encode($r->images, JSON_PRETTY_PRINT) . "\n";
    }
}

echo "\n";
echo "=============================================\n";
echo "  FILES DI STORAGE\n";
echo "=============================================\n\n";

$reportsDir = storage_path('app/public/reports');

if (is_dir($reportsDir)) {
    $files = glob($reportsDir . '/*');
    
    if (empty($files)) {
        echo "[INFO] Tidak ada file di storage/app/public/reports/\n";
        echo "\nUpload belum dilakukan atau gagal.\n";
    } else {
        echo "Directory: storage/app/public/reports/\n";
        echo "Total files: " . count($files) . "\n\n";
        
        $files = array_slice($files, 0, 10); // Show max 10 files
        
        foreach ($files as $file) {
            $filename = basename($file);
            $size = filesize($file);
            $sizeKb = round($size / 1024, 2);
            $modified = date('Y-m-d H:i:s', filemtime($file));
            
            echo "  - {$filename}\n";
            echo "    Size: {$sizeKb} KB\n";
            echo "    Modified: {$modified}\n";
        }
    }
} else {
    echo "[INFO] Directory 'storage/app/public/reports' belum ada\n";
    echo "(akan dibuat otomatis saat pertama upload)\n";
}

echo "\n";
echo "=============================================\n";
echo "  STATISTIK\n";
echo "=============================================\n\n";

$totalReports = Report::count();
$withImages = Report::whereNotNull('images')
    ->whereRaw("JSON_LENGTH(images) > 0")
    ->count();
$noImages = $totalReports - $withImages;

echo "Total Reports      : {$totalReports}\n";
echo "With Images        : {$withImages}\n";
echo "Without Images     : {$noImages}\n\n";

echo "By Status:\n";
foreach (['pending', 'process', 'done', 'rejected'] as $status) {
    $count = Report::where('status', $status)->count();
    echo "  " . ucfirst($status) . ": {$count}\n";
}

echo "\nBy Category:\n";
$categories = Category::withCount('reports')->get();
foreach ($categories as $cat) {
    echo "  {$cat->name}: {$cat->reports_count}\n";
}

echo "\n";
echo "=============================================\n";
echo "  SUMMARY\n";
echo "=============================================\n\n";

if ($withImages > 0) {
    echo "✓ ADA {$withImages} laporan dengan images!\n";
    echo "✓ Upload images BERHASIL\n";
} else {
    echo "✗ SEMUA laporan belum ada images\n";
    echo "✗ Upload images BELUM BERHASIL\n";
    echo "\nSolusi:\n";
    echo "  1. Test upload dari Flutter app\n";
    echo "  2. Check logs: storage/logs/laravel.log\n";
    echo "  3. Verify base64 format\n";
}

echo "\n";
echo "=============================================\n";
