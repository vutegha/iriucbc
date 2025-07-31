-- IRI Laravel Database Creation Script
-- Run this in phpMyAdmin or MySQL command line

-- Create database
CREATE DATABASE IF NOT EXISTS `iriadmin` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Select the database
USE `iriadmin`;

-- Show confirmation
SELECT 'Database iriadmin created successfully!' AS message;
