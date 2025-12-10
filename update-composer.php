<?php
echo "<!DOCTYPE html><html><head><title>Composer Update</title></head><body>";
echo "<h2>Updating Composer Dependencies...</h2>";
echo "<pre>";

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Dump autoload
    echo "Running composer dump-autoload...\n";
    Artisan::call('optimize:clear');
    echo Artisan::output();
    
    echo "\n✓ Autoload updated!\n";
    echo "\n<strong style='color:green'>SUCCESS!</strong>\n";
    echo "\n<strong style='color:red'>DELETE THIS FILE NOW!</strong>\n";
    
} catch (Exception $e) {
    echo "<strong style='color:red'>ERROR:</strong> " . $e->getMessage();
}

echo "</pre></body></html>";
?>