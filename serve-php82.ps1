# Laravel Development Server with PHP 8.2
# This script temporarily adds XAMPP PHP to PATH and starts the Laravel server

Write-Host "Setting up PHP 8.2 environment..." -ForegroundColor Green

# Store original PATH
$originalPath = $env:PATH

# Add XAMPP PHP to the beginning of PATH
$env:PATH = "C:\xampp\php;$env:PATH"

Write-Host "Checking PHP version:" -ForegroundColor Yellow
& php --version

Write-Host ""
Write-Host "Starting Laravel development server..." -ForegroundColor Green
Write-Host "Server will be available at: http://127.0.0.1:8000" -ForegroundColor Cyan
Write-Host "Press Ctrl+C to stop the server" -ForegroundColor Yellow
Write-Host ""

try {
    & php artisan serve --host=127.0.0.1 --port=8000
}
finally {
    # Restore original PATH
    $env:PATH = $originalPath
    Write-Host "PATH restored." -ForegroundColor Green
}
