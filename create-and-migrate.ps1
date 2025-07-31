# Complete Database Setup and Migration Script (PowerShell)
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "IRI Laravel - Complete Database Setup" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Set PATH
$env:PATH = "C:\xampp\mysql\bin;C:\xampp\php;$env:PATH"

try {
    Write-Host "Step 1: Testing MySQL connection..." -ForegroundColor Yellow
    $testResult = & mysql -u root -e "SELECT 'Connected' as status;" 2>&1
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ MySQL is running!" -ForegroundColor Green
        
        Write-Host ""
        Write-Host "Step 2: Creating database 'iriadmin'..." -ForegroundColor Yellow
        & mysql -u root -e "CREATE DATABASE IF NOT EXISTS iriadmin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
        
        if ($LASTEXITCODE -eq 0) {
            Write-Host "✅ Database 'iriadmin' created!" -ForegroundColor Green
            
            Write-Host ""
            Write-Host "Step 3: Running Laravel migrations..." -ForegroundColor Yellow
            Write-Host "This may take a moment..." -ForegroundColor Gray
            
            $migrationOutput = & C:\xampp\php\php.exe artisan migrate --force 2>&1
            
            if ($LASTEXITCODE -eq 0) {
                Write-Host "✅ Migrations completed successfully!" -ForegroundColor Green
                Write-Host $migrationOutput -ForegroundColor Gray
                
                Write-Host ""
                Write-Host "Step 4: Verifying setup..." -ForegroundColor Yellow
                $tables = & mysql -u root -e "USE iriadmin; SHOW TABLES;" 2>&1
                
                if ($LASTEXITCODE -eq 0) {
                    Write-Host "✅ Tables created:" -ForegroundColor Green
                    Write-Host $tables -ForegroundColor Gray
                }
                
                Write-Host ""
                Write-Host "========================================" -ForegroundColor Green
                Write-Host "✅ SETUP COMPLETED SUCCESSFULLY!" -ForegroundColor Green
                Write-Host "========================================" -ForegroundColor Green
                Write-Host "Database: iriadmin" -ForegroundColor Cyan
                Write-Host "Your Laravel application is ready!" -ForegroundColor Green
                Write-Host ""
                Write-Host "Start the server with: .\serve-php82.bat" -ForegroundColor Yellow
                
            } else {
                Write-Host "❌ Migration failed!" -ForegroundColor Red
                Write-Host $migrationOutput -ForegroundColor Red
            }
        } else {
            Write-Host "❌ Failed to create database" -ForegroundColor Red
        }
    } else {
        Write-Host "❌ MySQL not accessible" -ForegroundColor Red
        Write-Host "Please start XAMPP MySQL service" -ForegroundColor Yellow
    }
} catch {
    Write-Host "❌ Error: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""
Read-Host "Press Enter to continue"
