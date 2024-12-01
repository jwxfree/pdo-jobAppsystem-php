<?php
session_start();
include('core/dbConfig.php');

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $applicationId = $_GET['id'];
    
    // Fetch the doctor's first and last name and doctor_id for logging purposes
    $stmt = $pdo->prepare("SELECT d.first_name, d.last_name, d.doctor_id 
                           FROM job_applications ja 
                           JOIN doctors d ON ja.doctor_id = d.doctor_id 
                           WHERE ja.application_id = :application_id");
    $stmt->execute(['application_id' => $applicationId]);
    $applicant = $stmt->fetch();
    
    if ($applicant) {
        $doctorId = $applicant['doctor_id'];

        // Begin a transaction to ensure atomicity
        $pdo->beginTransaction();

        try {
            // Delete the job application
            $deleteApplicationStmt = $pdo->prepare("DELETE FROM job_applications WHERE application_id = :application_id");
            $deleteApplicationStmt->execute(['application_id' => $applicationId]);

            // Delete the doctor record
            $deleteDoctorStmt = $pdo->prepare("DELETE FROM doctors WHERE doctor_id = :doctor_id");
            $deleteDoctorStmt->execute(['doctor_id' => $doctorId]);

            // Log the deletion to the audit log
            $actionType = "Deleted Applicant";
            $addedBy = $_SESSION['username']; 
            $userId = $_SESSION['user_id']; // Fetch the user ID from the session
            $details = "Deleted applicant: " . $applicant['first_name'] . " " . $applicant['last_name'];

            $logStmt = $pdo->prepare("INSERT INTO audit_log (action_type, user_id, added_by, details) 
                                      VALUES (:action_type, :user_id, :added_by, :details)");
            $logStmt->execute([
                'action_type' => $actionType,
                'user_id' => $userId,
                'added_by' => $addedBy,
                'details' => $details
            ]);

            // Commit the transaction
            $pdo->commit();

            // Redirect with success message
            header('Location: index.php?delete_status=success');
            exit;
        } catch (Exception $e) {
            // Roll back the transaction in case of error
            $pdo->rollBack();
            header('Location: index.php?delete_status=failure');
            exit;
        }
    } else {
        header('Location: index.php?delete_status=failure');
        exit;
    }
}
?>
