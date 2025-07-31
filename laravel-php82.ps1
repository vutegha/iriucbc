# Laravel Project PHP 8.2 Configuration
# This script sets up XAMPP PHP 8.2 for this Laravel project

# Function to use XAMPP PHP for Laravel artisan commands
function Invoke-LaravelArtisan {
    param([Parameter(ValueFromRemainingArguments=$true)]$Args)
    & "C:\xampp\php\php.exe" artisan @Args
}

# Function to use XAMPP PHP for Composer commands
function Invoke-ProjectComposer {
    param([Parameter(ValueFromRemainingArguments=$true)]$Args)
    & "C:\xampp\php\php.exe" composer.phar @Args
}

# Create aliases
Set-Alias -Name "artisan" -Value "Invoke-LaravelArtisan"
Set-Alias -Name "pcomposer" -Value "Invoke-ProjectComposer"

Write-Host "Laravel PHP 8.2 environment loaded!" -ForegroundColor Green
Write-Host "Use 'artisan' for Laravel commands and 'pcomposer' for Composer commands" -ForegroundColor Yellow
