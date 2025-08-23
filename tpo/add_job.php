<?php
session_start();
if (!isset($_SESSION['tpo_id'])) {
    header("Location: index.php");
    exit();
}

include('../config.php'); // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $job_title = trim($_POST['job_title']);
    $company_name = trim($_POST['company_name']);
    $campus_date = trim($_POST['campus_date']);
    $last_date_apply = trim($_POST['last_date_apply']);
    $job_description = trim($_POST['job_description']);
    $company_url = trim($_POST['company_url']);
    
    // Validate data
    if (empty($job_title) || empty($company_name) || empty($campus_date) || empty($last_date_apply) || empty($job_description) || empty($company_url)) {
        $_SESSION['message'] = "All fields are required.";
        header("Location: jobs.php");
        exit();
    }
    
    // Validate dates
    $campus_dt = new DateTime($campus_date);
    $last_apply_dt = new DateTime($last_date_apply);

    // TODO: Need to fix this Validation Logic
    // Suggestion : 
    // 1. No Need to Close Modal
    // 2. Campus Date can not less than today!
    
    // if ($last_apply_dt > $campus_dt) {
    //     $_SESSION['message'] = "Last date to apply must be before the campus date.";
    //     header("Location: jobs.php");
    //     exit();
    // }

    
    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO job (job_title, company_name, campus_date, last_date_apply, job_description, company_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $job_title, $company_name, $campus_date, $last_date_apply, $job_description, $company_url);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Job added successfully!";
    } else {
        $_SESSION['message'] = "Error adding job: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
    
    // Redirect back to jobs page
    header("Location: jobs.php");
    exit();
} else {
    // If not a POST request, redirect to jobs page
    header("Location: jobs.php");
    exit();
}
?>
