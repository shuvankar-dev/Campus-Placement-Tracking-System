<?php
session_start();
if (!isset($_SESSION['tpo_id'])) {
    header("Location: index.php");
    exit();
}

include('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['app_id']) && isset($_POST['status'])) {
    $app_id = $_POST['app_id'];
    $status = $_POST['status'];
    
    // Validate status
    $allowed_statuses = ['Pending', 'Approved', 'Rejected'];
    if (!in_array($status, $allowed_statuses)) {
        $_SESSION['error'] = "Invalid status.";
        header("Location: applications.php");
        exit();
    }
    
    // Update application status
    $sql = "UPDATE applications SET status = ? WHERE app_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $app_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = "Application status updated to '$status' successfully!";
        } else {
            $_SESSION['error'] = "Application not found or no changes made.";
        }
    } else {
        $_SESSION['error'] = "Error updating application status: " . $conn->error;
    }
    
    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid request.";
}

header("Location: applications.php");
exit();
?>
