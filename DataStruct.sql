CREATE DATABASE rome_project_db;
USE rome_project_db;
CREATE TABLE users (
    id BIGINT PRIMARY KEY UNIQUE AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(255) DEFAULT NULL,
    role ENUM('Developer', 'Influencer', 'Player') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_ip VARBINARY(16) NOT NULL DEFAULT (INET6_ATON('0.0.0.0')),
    is_enabled BOOLEAN DEFAULT 1,
    
    INDEX idx_id (id),
    INDEX idx_username (username)
);

CREATE TABLE user_settings (
    user_id BIGINT PRIMARY KEY,
    receive_daily_stats BOOLEAN DEFAULT 0,
    profile_image VARCHAR(255) DEFAULT NULL,
    web_language VARCHAR(10) DEFAULT 'en',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE registration_tokens (
	token VARCHAR (64) PRIMARY KEY UNIQUE,
    role ENUM('Developer', 'Influencer', 'Player') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    used BOOLEAN DEFAULT 0
);

CREATE TABLE action_history (
    action_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    action VARCHAR(255) NOT NULL,
    severity ENUM('low', 'medium', 'high') DEFAULT 'low',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
);