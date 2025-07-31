# IRI Laravel Database Setup (PowerShell)
Write-Host "================================" -ForegroundColor Cyan
Write-Host "IRI Laravel Database Setup" -ForegroundColor Cyan  
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""

# Add XAMPP to PATH
$env:PATH = "C:\xampp\mysql\bin;C:\xampp\php;$env:PATH"

Write-Host "Step 1: Testing MySQL connection..." -ForegroundColor Yellow

try {
    # Test MySQL connection (assuming no password for root)
    $result = & mysql -u root -e "SELECT 'Connected' as status;" 2>&1
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ MySQL is running!" -ForegroundColor Green
        
        Write-Host ""
        Write-Host "Step 2: Creating database 'iriadmin'..." -ForegroundColor Yellow
        
        # Create database
        & mysql -u root -e "CREATE DATABASE IF NOT EXISTS iriadmin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
        
        if ($LASTEXITCODE -eq 0) {
            Write-Host "✅ Database 'iriadmin' created successfully!" -ForegroundColor Green
            
            Write-Host ""
            Write-Host "Step 3: Running Laravel migrations..." -ForegroundColor Yellow
            
            # Run migrations
            $migrationResult = & C:\xampp\php\php.exe artisan migrate --force 2>&1
            
            if ($LASTEXITCODE -eq 0) {
                Write-Host "✅ Migrations completed successfully!" -ForegroundColor Green
                Write-Host ""
                Write-Host "================================" -ForegroundColor Green
                Write-Host "✅ Database setup completed!" -ForegroundColor Green
                Write-Host "================================" -ForegroundColor Green
                Write-Host "Database: iriadmin" -ForegroundColor Cyan
                Write-Host "Host: 127.0.0.1:3306" -ForegroundColor Cyan
                Write-Host "Username: root" -ForegroundColor Cyan
                Write-Host "Password: (empty)" -ForegroundColor Cyan
                Write-Host ""
                Write-Host "You can now start your Laravel application:" -ForegroundColor Yellow
                Write-Host ".\serve-php82.bat" -ForegroundColor White
            } else {
                Write-Host "❌ Migration failed:" -ForegroundColor Red
                Write-Host $migrationResult -ForegroundColor Red
            }
        } else {
            Write-Host "❌ Failed to create database" -ForegroundColor Red
        }
    } else {
        Write-Host "❌ MySQL is not running or not accessible" -ForegroundColor Red
        Write-Host "Please start XAMPP and make sure MySQL is running" -ForegroundColor Yellow
        Write-Host "Error details: $result" -ForegroundColor Red
    }
} catch {
    Write-Host "❌ Error connecting to MySQL: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "Please make sure XAMPP is installed and MySQL is running" -ForegroundColor Yellow
}

Write-Host ""
Read-Host "Press Enter to continue"
