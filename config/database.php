<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cms_sederhana');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if ($conn->query($sql) === TRUE) {
    $conn->select_db(DB_NAME);
} else {
    die("Error creating database: " . $conn->error);
}

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($sql)) {
    die("Error creating users table: " . $conn->error);
}

// Add last_login column if it doesn't exist
$result = $conn->query("SHOW COLUMNS FROM users LIKE 'last_login'");
if ($result->num_rows == 0) {
    $sql = "ALTER TABLE users ADD COLUMN last_login TIMESTAMP NULL AFTER password";
    if (!$conn->query($sql)) {
        die("Error adding last_login column: " . $conn->error);
    }
}

// Add email column if it doesn't exist
$result = $conn->query("SHOW COLUMNS FROM users LIKE 'email'");
if ($result->num_rows == 0) {
    $sql = "ALTER TABLE users ADD COLUMN email VARCHAR(100) NOT NULL UNIQUE AFTER username";
    if (!$conn->query($sql)) {
        die("Error adding email column: " . $conn->error);
    }
}

// Create posts table
$sql = "CREATE TABLE IF NOT EXISTS posts (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (!$conn->query($sql)) {
    die("Error creating posts table: " . $conn->error);
}

// Insert default admin user if not exists
$default_username = 'admin';
$default_email = 'admin@example.com';
$default_password = password_hash('admin123', PASSWORD_DEFAULT);

$sql = "INSERT IGNORE INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $default_username, $default_email, $default_password);
$stmt->execute();
$stmt->close();
?> 