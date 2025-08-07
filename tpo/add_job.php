<?php
session_start();
if (!isset($_SESSION['tpo_id'])) {
    header("Location: index.php");
    exit();
}

include('../config.php'); // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $company_name = $_POST['company_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $company_link = $_POST['company_link'];
    
    // Validate data
    if (empty($company_name) || empty($start_date) || empty($end_date) || empty($company_link)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: jobs.php");
        exit();
    }
    
    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO jobs (company_name, start_date, end_date, company_link) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $company_name, $start_date, $end_date, $company_link);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Job added successfully!";
    } else {
        $_SESSION['error'] = "Error adding job: " . $conn->error;
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
