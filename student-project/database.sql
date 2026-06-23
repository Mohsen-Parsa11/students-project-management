-- ============================================
-- Student Project Management System (SPMS)
-- Database: spms_db
-- Import this file in phpMyAdmin or run via MySQL CLI
-- ============================================

CREATE DATABASE IF NOT EXISTS spms_db2;
USE spms_db2;

-- ---------------------------
-- users table
-- ---------------------------
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------
-- projects table
-- ---------------------------
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    category VARCHAR(100),
    file VARCHAR(255),
    status ENUM('Pending', 'Approved', 'Rejected') NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ---------------------------
-- Admin account
-- ---------------------------
-- This student project uses the same database schema as the admin project.
-- Create the admin account from the admin-project/setup-admin.php script,
-- or register a normal student through the app, then run:
-- UPDATE users SET role = 'admin' WHERE email = 'your@email.com';
