<?php
session_start();
include('core/dbConfig.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$message = '';
$stmt = $pdo->query("SELECT * FROM departments");
$departments = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];
    $dateOfBirth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $departmentId = $_POST['department_id'];

    $stmt = $pdo->prepare("INSERT INTO doctors (first_name, last_name, email, phone_number, date_of_birth, gender, address, department_id) 
                           VALUES (:first_name, :last_name, :email, :phone_number, :date_of_birth, :gender, :address, :department_id)");

    if ($stmt->execute([
        'first_name' => $firstName, 
        'last_name' => $lastName, 
        'email' => $email, 
        'phone_number' => $phoneNumber, 
        'date_of_birth' => $dateOfBirth, 
        'gender' => $gender, 
        'address' => $address, 
        'department_id' => $departmentId
    ])) {
        $doctorId = $pdo->lastInsertId(); 

        $position = $_POST['position'];
        $coverLetter = $_POST['cover_letter'];

        $stmt = $pdo->prepare("INSERT INTO job_applications (doctor_id, position, cover_letter) 
                               VALUES (:doctor_id, :position, :cover_letter)");

        if ($stmt->execute(['doctor_id' => $doctorId, 'position' => $position, 'cover_letter' => $coverLetter])) {
            $message = "<div class='success-message'><p>Application successful!</p></div>";

          
            $action = "Added Applicant";
            $addedBy = $_SESSION['username'];  
            $userId = $_SESSION['user_id']; 
            $details = "Added a new applicant: $firstName $lastName";

            $logStmt = $pdo->prepare("INSERT INTO audit_log (action_type, user_id, added_by, details) VALUES (:action_type, :user_id, :added_by, :details)");
            $logStmt->execute([
        'action_type' => $action,
        'user_id' => $userId,
        'added_by' => $addedBy,
        'details' => $details
    ]);
        } else {
            $message = "<div class='failed-message'><p>Failed to submit the application.</p></div>";
        }
    } else {
        $message = "<div class='container'><p>Failed to add doctor details.</p></div>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Doctor Applicant</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="add_container">
        <h1>Add New Doctor Applicant</h1>
        <?php if (!empty($message)): ?>
            <?= $message ?>
        <?php endif; ?>
        <form method="post">
            <!-- Doctor Personal Details -->
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" required><br>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" required><br>

            <label for="email">Email:</label>
            <input type="email" name="email" required><br>

            <label for="phone_number">Phone Number:</label>
            <input type="text" name="phone_number"><br>

            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" name="date_of_birth"><br>

            <label for="gender">Gender:</label>
            <select name="gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select><br>

            <label for="address">Address:</label>
            <textarea name="address"></textarea><br>

            <label for="department_id">Department:</label>
            <select name="department_id">
                <?php foreach ($departments as $department): ?>
                    <option value="<?= $department['department_id'] ?>"><?= $department['department_name'] ?></option>
                <?php endforeach; ?>
            </select><br>

            <!-- Job Application Details -->
            <label for="position">Position:</label>
            <input type="text" name="position" required><br>

            <label for="cover_letter">Cover Letter:</label>
            <textarea name="cover_letter"></textarea><br>

            <button type="submit">Submit</button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <a href="index.php">Back to Applicants List</a>
        </div>
    </div>
</body>
</html>
