<?php
session_start();
if (!isset($_SESSION['tpo_id'])) {
    header("Location: index.php");
    exit();
}

include('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];
    
    // First get student name for confirmation message
    $get_name_sql = "SELECT sname FROM student WHERE std_id = ?";
    $name_stmt = $conn->prepare($get_name_sql);
    $name_stmt->bind_param("i", $student_id);
    $name_stmt->execute();
    $name_result = $name_stmt->get_result();
    $student_name = $name_result->fetch_assoc()['sname'] ?? 'Unknown';
    $name_stmt->close();
    
    // Delete student from database
    $sql = "DELETE FROM student WHERE std_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = "Student '$student_name' has been deleted successfully!";
        } else {
            $_SESSION['error'] = "Student not found or already deleted.";
        }
    } else {
        $_SESSION['error'] = "Error deleting student: " . $conn->error;
    }

    $stmt->close();
}

header("Location: students.php");
exit();
?>
