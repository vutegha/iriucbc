<?php
// Script to create password_reset_tokens table

$host = 'localhost';
$dbname = 'iriadmin';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "CREATE TABLE IF NOT EXISTS password_reset_tokens (
        email VARCHAR(255) PRIMARY KEY,
        token VARCHAR(255) NOT NULL,
        created_at TIMESTAMP NULL DEFAULT NULL
    )";
    
    $pdo->exec($sql);
    echo "Table 'password_reset_tokens' created successfully!\n";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
