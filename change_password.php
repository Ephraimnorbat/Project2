<?php
session_start();

// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=authdb', 'admin', 'admin123');

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Handle password change form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Retrieve user's current password
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    // Verify current password
    if ($user && password_verify($current_password, $user['password'])) {
        // Update password
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$new_password, $user_id]);
        echo "Password updated successfully";
    } else {
        echo "Incorrect current password";
    }
}
?>
