<?php
$servername = "db2";
$username = "testuser";
$password = "testpass";
$dbname = "testdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";
$conn->query($sql);

// Insert default user (username: admin, password: admin123)
$hashed_password = password_hash("admin123", PASSWORD_DEFAULT);
$conn->query("INSERT IGNORE INTO users (username, password) VALUES ('admin', '$2y$10$K4EFGEnkHHv68vhCGY4CdelxjrL9zjQuuDi8q/YZOPIJuMjX4BGA.')");

echo "Database initialized. Default login: admin / admin123";
$conn->close();
