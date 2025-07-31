# Database Check Script for IRI Laravel Project
Write-Host "================================" -ForegroundColor Cyan
Write-Host "Database Status Check" -ForegroundColor Cyan
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""

# Add XAMPP to PATH
$env:PATH = "C:\xampp\mysql\bin;C:\xampp\php;$env:PATH"

# Check MySQL connection
Write-Host "Checking MySQL connection..." -ForegroundColor Yellow

try {
    # Test MySQL connection
    $testConnection = & mysql -u root -e "SELECT 'Connected' as status;" 2>&1
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ MySQL is running!" -ForegroundColor Green
        Write-Host ""
        
        # Check if database exists
        Write-Host "Checking if database 'iriadmin' exists..." -ForegroundColor Yellow
        
        $dbCheck = & mysql -u root -e "SHOW DATABASES LIKE 'iriadmin';" 2>&1
        
        if ($dbCheck -match "iriadmin") {
            Write-Host "✅ Database 'iriadmin' exists!" -ForegroundColor Green
            Write-Host ""
            
            # Check tables
            Write-Host "Checking tables in database..." -ForegroundColor Yellow
            $tables = & mysql -u root -e "USE iriadmin; SHOW TABLES;" 2>&1
            
            if ($LASTEXITCODE -eq 0 -and $tables.Length -gt 0) {
                Write-Host "✅ Database has tables:" -ForegroundColor Green
                Write-Host $tables -ForegroundColor Gray
                Write-Host ""
                
                # Check specific table mentioned in error
                $servicesCheck = & mysql -u root -e "USE iriadmin; DESCRIBE services;" 2>&1
                if ($LASTEXITCODE -eq 0) {
                    Write-Host "✅ 'services' table exists and is accessible" -ForegroundColor Green
                } else {
                    Write-Host "❌ 'services' table not found or not accessible" -ForegroundColor Red
                    Write-Host "Run migrations: .\laravel.bat migrate" -ForegroundColor Yellow
                }
            } else {
                Write-Host "⚠️  Database exists but appears empty" -ForegroundColor Yellow
                Write-Host "Run migrations: .\laravel.bat migrate" -ForegroundColor Yellow
            }
        } else {
            Write-Host "❌ Database 'iriadmin' does NOT exist" -ForegroundColor Red
            Write-Host ""
            Write-Host "Available databases:" -ForegroundColor Yellow
            $databases = & mysql -u root -e "SHOW DATABASES;" 2>&1
            Write-Host $databases -ForegroundColor Gray
            Write-Host ""
            Write-Host "To create the database, run: .\setup-database.bat" -ForegroundColor Yellow
        }
        
    } else {
        Write-Host "❌ MySQL is not running or not accessible" -ForegroundColor Red
        Write-Host "Error: $testConnection" -ForegroundColor Red
        Write-Host ""
        Write-Host "Solutions:" -ForegroundColor Yellow
        Write-Host "1. Start XAMPP Control Panel" -ForegroundColor White
        Write-Host "2. Click 'Start' next to MySQL" -ForegroundColor White
        Write-Host "3. Wait for MySQL to start (should show green 'Running')" -ForegroundColor White
        Write-Host "4. Run this script again" -ForegroundColor White
    }
    
} catch {
    Write-Host "❌ Error checking database: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "Please make sure XAMPP is installed and MySQL is configured" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "================================" -ForegroundColor Cyan
Read-Host "Press Enter to continue"
