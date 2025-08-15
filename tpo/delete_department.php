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
    // Get and validate the department ID
    $department_id = isset($_POST['department_id']) ? intval($_POST['department_id']) : 0;
    
    // Validate input
    if ($department_id <= 0) {
        $_SESSION['message'] = 'Invalid department ID.';
        header('Location: departments.php');
        exit();
    }
    
    // Check if department exists
    $check_sql = "SELECT department_name FROM department WHERE d_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $department_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows == 0) {
        $_SESSION['message'] = 'Department not found.';
        header('Location: departments.php');
        exit();
    }
    
    $department_data = $check_result->fetch_assoc();
    $department_name = $department_data['department_name'];
    
    // TODO: You might want to check if there are students associated with this department
    // and prevent deletion if there are, or handle cascade deletion
    
    // Delete the department
    $delete_sql = "DELETE FROM department WHERE d_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $department_id);
    
    if ($delete_stmt->execute()) {
        if ($delete_stmt->affected_rows > 0) {
            $_SESSION['message'] = "Department '$department_name' deleted successfully!";
        } else {
            $_SESSION['message'] = 'No department was deleted. It may have already been removed.';
        }
    } else {
        $_SESSION['message'] = 'Error deleting department: ' . $conn->error;
    }
    
    $delete_stmt->close();
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
