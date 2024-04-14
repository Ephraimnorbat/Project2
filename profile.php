<?php
session_start();

// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=authdb', 'admin', 'admin123');

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Validate CSRF token
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed");
    }

    // Handle profile update form submission
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');

    // Update user profile
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->execute([$name, $email, $user_id]);

    echo "Profile updated successfully";
}

// Generate and store CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/profile.css">

</head>
<body>
<div class="profile-container">
    <h2>User Profile</h2>

    <div id="profileForm" class="form-container">
    <?php if ($user): ?>
        <p>Welcome, <?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>!</p>
        <p>Username: <?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></p>
        <p>Email: <?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></p>

        <!-- Profile update form with CSRF token -->
        <h3>Edit Profile</h3>
        <form action="profile.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            Name: <input type="text" name="name" value="<?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>" required><br>
            Email: <input type="email" name="email" value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>" required><br>
            <input type="submit" value="Update Profile">
        </form>
    <?php else: ?>
        <p>User not found</p>
    <?php endif; ?>
    </div>
    </div>
</body>
</html>
