<?php
session_start();
include 'core/dbConfig.php';

$username = $password = $confirm_password = "";
$registration_error = "";

// Process form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $registration_error = "Passwords do not match.";
    } else {
        // Check if the username already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $registration_error = "Username already exists. Please choose another.";
        } else {
          
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $_SESSION['registration_success'] = true;
                header("Location: index.php");
                exit();
            } else {
                $registration_error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Job Application System - Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Register</h2>
    <form method="POST" action="registration.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <button type="submit" style="margin: 1em 0">Register</button>
        <button type="button" style="margin: 1em 0" onclick="window.location.href='login.php'">Back</button>
    </form>
    
    <?php if ($registration_error): ?>
        <p style="color: red;"><?= htmlspecialchars($registration_error); ?></p>
    <?php endif; ?>
</body>
</html>
