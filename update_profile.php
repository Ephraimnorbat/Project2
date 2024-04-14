<?php
session_start();

// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=authdb', 'admin', 'admin123');

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Handle profile update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Update user profile
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->execute([$name, $email, $user_id]);

    echo "Profile updated successfully";
}
?>
