<?php
session_start();
if (!isset($_SESSION['tpo_id'])) {
    header("Location: index.php");
    exit();
}

include('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $application_id = (int)$_POST['id'];
    $status = trim($_POST['status']);
    
    // Validate status
    if (!in_array($status, ['Approved', 'Rejected', 'Pending'])) {
        $_SESSION['message'] = "Invalid status provided.";
        header("Location: applications.php");
        exit();
    }
    
    // Validate application ID
    if ($application_id <= 0) {
        $_SESSION['message'] = "Invalid application ID.";
        header("Location: applications.php");
        exit();
    }
    
    // Update application status
    $stmt = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $application_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = "Application status updated to " . $status . " successfully!";
        } else {
            $_SESSION['message'] = "No application found with the given ID.";
        }
    } else {
        $_SESSION['message'] = "Error updating application status: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    $_SESSION['message'] = "Invalid request method.";
}

// Redirect back to applications page
header("Location: applications.php");
exit();
?>
