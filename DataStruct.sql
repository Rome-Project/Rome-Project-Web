CREATE DATABASE rome_project_db;
USE rome_project_db;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_ip VARBINARY(16) NOT NULL DEFAULT (INET6_ATON('0.0.0.0')),
    is_active BOOLEAN DEFAULT 0,
    
    INDEX idx_last_login (last_login),
    INDEX idx_last_ip (last_ip)
);