<?php
session_start();
if (!isset($_SESSION['std_info'])) {
    header("location:login.php");
    exit();
}

include('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['job_id'])) {
    $student_id = $_SESSION['std_info']['std_id'];
    $job_id = intval($_POST['job_id']);
    
    // Check if the job exists and is still active
    $job_check = $conn->query("SELECT * FROM job WHERE id = $job_id AND last_date_apply >= CURDATE()");
    
    if ($job_check && $job_check->num_rows > 0) {
        // Check if student has already applied
        $existing_application = $conn->query("SELECT id FROM applications WHERE std_id = $student_id AND job_id = $job_id");
        
        if ($existing_application && $existing_application->num_rows > 0) {
            $_SESSION['error_message'] = "You have already applied for this job!";
        } else {
            // Insert new application
            $apply_sql = "INSERT INTO applications (job_id, std_id, applied_date, status) VALUES ($job_id, $student_id, NOW(), 'Pending')";
            
            if ($conn->query($apply_sql)) {
                $_SESSION['success_message'] = "Application submitted successfully! You will be notified about the status update.";
            } else {
                $_SESSION['error_message'] = "Failed to submit application. Please try again.";
            }
        }
    } else {
        $_SESSION['error_message'] = "This job is no longer available or the deadline has passed.";
    }
} else {
    $_SESSION['error_message'] = "Invalid request.";
}

// Redirect back to campus placements page
header("location:campus_placements.php");
exit();
?>
