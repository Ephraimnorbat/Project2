<?php
session_start();

// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=authdb', 'admin', 'admin123');

// Handle registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Insert user into database
    $stmt = $pdo->prepare("INSERT INTO users (username, password, name, email) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $password, $name, $email]);

    // Redirect to login page after successful registration
    header("Location: login.html");
    exit();
}
?>
