@echo off
REM Fix newsletters table migration conflict
echo ========================================
echo Fixing Newsletters Table Migration
echo ========================================
echo.

REM Set PATH
set "PATH=C:\xampp\php;C:\xampp\mysql\bin;%PATH%"

echo Step 1: Checking current newsletters table structure...
mysql -u root -e "USE iriadmin; DESCRIBE newsletters;"
echo.

echo Step 2: Adding missing columns to newsletters table...
echo Adding 'nom' column...
mysql -u root -e "USE iriadmin; ALTER TABLE newsletters ADD COLUMN IF NOT EXISTS nom VARCHAR(255) NULL AFTER email;" 2>nul

echo Adding 'token' column...
mysql -u root -e "USE iriadmin; ALTER TABLE newsletters ADD COLUMN IF NOT EXISTS token VARCHAR(255) NOT NULL DEFAULT '' AFTER nom;" 2>nul

echo Adding 'actif' column...
mysql -u root -e "USE iriadmin; ALTER TABLE newsletters ADD COLUMN IF NOT EXISTS actif TINYINT(1) NOT NULL DEFAULT 1 AFTER token;" 2>nul

echo Adding 'confirme_a' column...
mysql -u root -e "USE iriadmin; ALTER TABLE newsletters ADD COLUMN IF NOT EXISTS confirme_a TIMESTAMP NULL AFTER actif;" 2>nul

echo.
echo Step 3: Adding unique constraint to token column...
mysql -u root -e "USE iriadmin; ALTER TABLE newsletters ADD UNIQUE KEY newsletters_token_unique (token);" 2>nul

echo.
echo Step 4: Adding index for email and actif...
mysql -u root -e "USE iriadmin; ALTER TABLE newsletters ADD INDEX newsletters_email_actif_index (email, actif);" 2>nul

echo.
echo Step 5: Verifying updated table structure...
mysql -u root -e "USE iriadmin; DESCRIBE newsletters;"

echo.
echo Step 6: Marking migration as completed...
mysql -u root -e "USE iriadmin; INSERT IGNORE INTO migrations (migration, batch) VALUES ('2025_07_17_085934_create_newsletters_table', 6);"

echo.
echo Step 7: Running remaining migrations...
php artisan migrate --force --no-interaction

echo.
echo ========================================
echo ✅ NEWSLETTERS TABLE FIXED!
echo ========================================
echo.
echo The newsletters table now has all required columns:
echo - id, email, nom, token, actif, confirme_a, created_at, updated_at
echo.
echo Migration should now proceed without conflicts.
echo.
pause
