<?php
session_start();
include('core/dbConfig.php');

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit;
}

$message = '';
if (isset($_GET['id'])) {
    $applicationId = $_GET['id'];

    $stmt = $pdo->prepare("
        SELECT ja.*, d.first_name, d.last_name 
        FROM job_applications ja
        JOIN doctors d ON ja.doctor_id = d.doctor_id
        WHERE ja.application_id = :id
    ");
    $stmt->execute(['id' => $applicationId]);
    $applicant = $stmt->fetch();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $position = $_POST['position'];
        $status = $_POST['status'];
        $coverLetter = $_POST['cover_letter'];

        $updateDoctor = $pdo->prepare("
            UPDATE doctors 
            SET first_name = :first_name, last_name = :last_name 
            WHERE doctor_id = :doctor_id
        ");
        $updateDoctor->execute([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'doctor_id' => $applicant['doctor_id']
        ]);

        $updateApplication = $pdo->prepare("
            UPDATE job_applications 
            SET position = :position, status = :status, cover_letter = :cover_letter 
            WHERE application_id = :id
        ");
        if ($updateApplication->execute([
            'position' => $position,
            'status' => $status,
            'cover_letter' => $coverLetter,
            'id' => $applicationId
        ])) {
            $message = "<div class='success-message'><p>Application updated successfully!</p></div>";
        } else {
            $message = "<div class='failed-message'><p>Failed to update application.</p></div>";
        }

         // Log the action in the audit log
    $userId = $_SESSION['user_id'];  // Make sure 'user_id' is stored in the session during login
    $addedBy = $_SESSION['username'];  // Assuming 'username' is stored in the session
    $action = "Edited Applicant";
    $details = "Edited details of applicant: " . $applicant['first_name'] . " " . $applicant['last_name'];

    $logStmt = $pdo->prepare("INSERT INTO audit_log (action_type, user_id, added_by, details) VALUES (:action_type, :user_id, :added_by, :details)");
    $logStmt->execute([
        'action_type' => $action,
        'user_id' => $userId,
        'added_by' => $addedBy,
        'details' => $details
    ]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Applicant</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="edit_container">
        <h1>Edit Applicant</h1>
        <?php if (!empty($message)): ?>
            <?= $message ?>
        <?php endif; ?>
        <form method="post">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($applicant['first_name']) ?>" required><br>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($applicant['last_name']) ?>" required><br>

            <label for="position">Position:</label>
            <input type="text" name="position" value="<?= htmlspecialchars($applicant['position']) ?>" required><br>

            <label for="status">Status:</label>
            <select name="status">
                <option value="Pending" <?= $applicant['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Accepted" <?= $applicant['status'] == 'Accepted' ? 'selected' : '' ?>>Accepted</option>
                <option value="Rejected" <?= $applicant['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
            </select><br>

            <label for="cover_letter">Cover Letter:</label>
            <textarea name="cover_letter"><?= htmlspecialchars($applicant['cover_letter']) ?></textarea><br>

            <button type="submit">Update</button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <a href="index.php">Back to Applicants List</a>
        </div>
    </div>
</body>
</html>
