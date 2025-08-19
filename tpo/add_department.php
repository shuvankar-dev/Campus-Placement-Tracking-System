<?php
session_start();

// Check if user is logged in as TPO
if (!isset($_SESSION['tpo_id'])) {
    $_SESSION['message'] = 'Please login to continue';
    header('Location: ../index.php');
    exit();
}

// Include database configuration
require_once '../config.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize the department name
    $department_name = trim($_POST['department_name']);
    
    // Validate input
    if (empty($department_name)) {
        $_SESSION['error'] = 'Department name is required.';
        header('Location: departments.php');
        exit();
    }
    
    // Check if department already exists
    $check_sql = "SELECT d_id FROM department WHERE department_name = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $department_name);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $_SESSION['error'] = 'Department already exists.';
        header('Location: departments.php');
        exit();
    }
    
    // Insert new department
    $insert_sql = "INSERT INTO department (department_name) VALUES (?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("s", $department_name);
    
    if ($insert_stmt->execute()) {
        $_SESSION['message'] = 'Department added successfully!';
    } else {
        $_SESSION['error'] = 'Error adding department: ' . $connection->error;
    }
    
    $insert_stmt->close();
    $check_stmt->close();
    $conn->close();
    
    // Redirect back to departments page
    header('Location: departments.php');
    exit();
} else {
    // If not a POST request, redirect to departments page
    header('Location: departments.php');
    exit();
}
?>
