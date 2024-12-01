<?php
session_start();
include 'core/dbConfig.php'; 

// Redirect to index.php if already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$login_error = "";
$registration_success = "";

if (isset($_SESSION['registration_success'])) {
    $registration_success = "Registration successful! You can now log in.";
    unset($_SESSION['registration_success']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['logged_in'] = true;

        header("Location: index.php");
        exit;
    } else {
        $login_error = "Invalid credentials!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Job Application System - Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <nav>
        <h1>Digital Solutions Company Management</h1>
    </nav>
    
    <div class="form-container">
    <h2>Login</h2>
    <?php if ($login_error): ?>
        <p style="color: red; text-align: center;"><?= htmlspecialchars($login_error); ?></p>
    <?php endif; ?>
    <?php if ($registration_success): ?>
        <p style="color: green; text-align: center;"><?= htmlspecialchars($registration_success); ?></p>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit" style="margin: 0 0 1em 0">Login</button>
        <hr>
        <p style="margin: .5em 0 0 0; text-align:center;">Don’t have an account? <a href="registration.php">Register here</a></p>
        <p style="margin: .5em 0 0 0; text-align:center;">Forgot credentials? Contact IT support</p>
    </form>
</div>


    <?php if ($login_error): ?>
        <p style="color: red; margin: 1rem 0 0 40rem"><?= htmlspecialchars($login_error); ?></p>
    <?php endif; ?>
    <?php if ($registration_success): ?>
        <p style="color: red; margin: 1rem 0 0 40rem"><?= htmlspecialchars($registration_success); ?></p>
    <?php endif; ?>
</body>

</html>
