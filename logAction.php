<?php
session_start();
include 'core/dbConfig.php';

function logAction($pdo, $actionType, $tableName, $recordId, $details = null) {
    try {
        // Get user details
        $userId = $_SESSION['user_id'];
        $addedBy = $_SESSION['username'];
        
        // Prepare and execute the insert statement
        $stmt = $pdo->prepare("INSERT INTO audit_log (user_id, action_type, table_name, record_id, added_by, details)
                               VALUES (:user_id, :action_type, :table_name, :record_id, :added_by, :details)");
        $stmt->execute([
            ':user_id' => $userId,      
            ':action_type' => $actionType,
            ':table_name' => $tableName,
            ':record_id' => $recordId,
            ':added_by' => $addedBy,    
            ':details' => $details
        ]);
    } catch (PDOException $e) {
        error_log("Error logging action: " . $e->getMessage());
    }
}
?>
