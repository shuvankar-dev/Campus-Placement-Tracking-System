<?php
// Include database configuration
require_once '../config.php';

// Start session if not already started
session_start();

// Check if user is logged in as TPO
if (!isset($_SESSION['tpo_id'])) {
    $_SESSION['message'] = 'Please login to continue';
    header('Location: ../index.php');
    exit();
}

// Check if job_id is provided
if (!isset($_GET['job_id']) || empty($_GET['job_id'])) {
    $_SESSION['message'] = 'Invalid job ID';
    header('Location: jobs.php');
    exit();
}

// Get job ID from URL parameter
$job_id = mysqli_real_escape_string($conn, $_GET['job_id']);

// Delete job from database
$query = "DELETE FROM job WHERE id = '$job_id'";

if (mysqli_query($conn, $query)) {
    // Deletion successful
    $_SESSION['message'] = 'Job deleted successfully';
} else {
    // Deletion failed
    $_SESSION['message'] = 'Failed to delete job: ' . mysqli_error($conn);
}

// Redirect back to jobs page
header('Location: jobs.php');
exit();
?>
