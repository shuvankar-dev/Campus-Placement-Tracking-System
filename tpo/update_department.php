<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['tpo_id'])) {
    header("Location: index.php");
    exit();
}

include('../config.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $department_id = $_POST['department_id'];
    $department_name = trim($_POST['department_name']);
    
    // Validate input
    if (empty($department_name)) {
        $_SESSION['error'] = "Department name cannot be empty.";
        header("Location: departments.php");
        exit();
    }
    
    // Check if department name already exists (excluding current department)
    $check_sql = "SELECT d_id FROM department WHERE department_name = ? AND d_id != ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("si", $department_name, $department_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $_SESSION['error'] = "Department name already exists. Please choose a different name.";
        header("Location: departments.php");
        exit();
    }
    
    // Update department
    $update_sql = "UPDATE department SET department_name = ? WHERE d_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $department_name, $department_id);
    
    if ($update_stmt->execute()) {
        $_SESSION['message'] = "Department updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating department: " . $conn->error;
    }
    
    $update_stmt->close();
    $check_stmt->close();
} else {
    $_SESSION['error'] = "Invalid request method.";
}

// Redirect back to departments page
header("Location: departments.php");
exit();
?>
