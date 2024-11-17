<?php
session_start();
include('core/dbConfig.php');

if (isset($_GET['id'])) {
    $applicationId = $_GET['id'];

    $pdo->beginTransaction();

    try {
        // Fetch the doctor_id associated with the application
        $stmt = $pdo->prepare("SELECT doctor_id FROM job_applications WHERE application_id = :id");
        $stmt->execute(['id' => $applicationId]);
        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($doctor) {
            $doctorId = $doctor['doctor_id'];

            // Delete from the job_applications table
            $stmt1 = $pdo->prepare("DELETE FROM job_applications WHERE application_id = :id");
            $stmt1->execute(['id' => $applicationId]);

            // Delete from the doctors table using the fetched doctor_id
            $stmt2 = $pdo->prepare("DELETE FROM doctors WHERE doctor_id = :doctor_id");
            $stmt2->execute(['doctor_id' => $doctorId]);
        }

        // Commit the transaction
        $pdo->commit();
        header('Location: index.php?delete_status=success');
    } catch (Exception $e) {
        // Rollback the transaction on error
        $pdo->rollBack();
        header('Location: index.php?delete_status=failure');
    }

    exit();
}
?>
