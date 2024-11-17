<?php
session_start();
include('core/dbConfig.php');
$deleteMessage = '';
if (isset($_GET['delete_status'])) {
    if ($_GET['delete_status'] === 'success') {
        $deleteMessage = '<div class="success-message">Applicant deleted successfully!</div>';
    } elseif ($_GET['delete_status'] === 'failure') {
        $deleteMessage = '<div class="failed-message">Failed to delete applicant. Please try again.</div>';
    }
}

$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
}

$query = "SELECT ja.application_id, d.first_name, d.last_name, ja.position, ja.status, d.doctor_id, d.email, d.phone_number, d.date_of_birth, d.gender, d.address, d.department_id
          FROM job_applications ja
          JOIN doctors d ON ja.doctor_id = d.doctor_id
          WHERE d.first_name LIKE :searchTerm OR d.last_name LIKE :searchTerm";
$stmt = $pdo->prepare($query);
$stmt->execute(['searchTerm' => "%$searchTerm%"]);
$applicants = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Applicants</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="index_container">
        <h1>Job Applicants</h1>
        <?php if ($deleteMessage): ?>
            <?= $deleteMessage ?>
        <?php endif; ?>

        <div class="search-container">
            <form method="get">
                <input type="text" name="search" placeholder="Search by name" value="<?= htmlspecialchars($searchTerm) ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Doctor ID</th>
                    <th>Name</th>
                    <th>Department ID</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Address</th>
                    <th>Position</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applicants as $applicant): ?>
                    <tr>
                        <td><?= htmlspecialchars($applicant['doctor_id']) ?></td>
                        <td><?= htmlspecialchars($applicant['first_name']) . ' ' . htmlspecialchars($applicant['last_name']) ?></td>
                        <td><?= htmlspecialchars($applicant['department_id']) ?></td>
                        <td><?= htmlspecialchars($applicant['email']) ?></td>
                        <td><?= htmlspecialchars($applicant['phone_number']) ?></td>
                        <td><?= htmlspecialchars($applicant['date_of_birth']) ?></td>
                        <td><?= htmlspecialchars($applicant['gender']) ?></td>
                        <td><?= htmlspecialchars($applicant['address']) ?></td>
                        <td><?= htmlspecialchars($applicant['position']) ?></td>
                        <td><?= htmlspecialchars($applicant['status']) ?></td>
                        <td class="table-actions">
                            <a href="editApplicant.php?id=<?= $applicant['application_id'] ?>">Edit</a>
                            <a href="delApplicant.php?id=<?= $applicant['application_id'] ?>" onclick="return confirm('Are you sure you want to delete this applicant?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="text-align: center; margin-top: 20px;">
            <a href="addApplicant.php">Add New Applicant</a>
        </div>
    </div>
</body>
</html>
