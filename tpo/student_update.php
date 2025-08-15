<?php
session_start();
if (!isset($_SESSION['tpo_id'])) {
    header("Location: index.php");
    exit();
}

include('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $std_id = $_POST['std_id'];
    $dept_id = $_POST['dept_id'];
    $sname = $_POST['sname'];
    $semail = $_POST['semail'];
    $sdob = $_POST['sdob'];
    $sphone = $_POST['sphone'];
    $sgender = $_POST['sgender'];
    $scgpa = $_POST['scgpa'];
    $saddress = $_POST['saddress'];
    $new_password = $_POST['new_password'];

    // Validate CGPA
    if ($scgpa < 0 || $scgpa > 10) {
        $_SESSION['error'] = "CGPA must be between 0 and 10.";
        header("Location: student_edit.php");
        exit();
    }

    // Check if email already exists for other students
    $check_email_sql = "SELECT std_id FROM student WHERE semail = ? AND std_id != ?";
    $check_stmt = $conn->prepare($check_email_sql);
    $check_stmt->bind_param("si", $semail, $std_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $_SESSION['error'] = "Email already exists. Please use a different email.";
        header("Location: student_edit.php");
        exit();
    }

    // Validate date of birth (student should be at least 16 years old)
    $today = new DateTime();
    $dob = new DateTime($sdob);
    $age = $today->diff($dob)->y;
    
    if ($age < 16) {
        $_SESSION['error'] = "Student must be at least 16 years old.";
        header("Location: student_edit.php");
        exit();
    }

    // Update student in database
    if (!empty($new_password)) {
        // Update with new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE student SET dept_id = ?, sname = ?, semail = ?, spassword = ?, sdob = ?, sphone = ?, sgender = ?, scgpa = ?, saddress = ? WHERE std_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssssssi", $dept_id, $sname, $semail, $hashed_password, $sdob, $sphone, $sgender, $scgpa, $saddress, $std_id);
    } else {
        // Update without changing password
        $sql = "UPDATE student SET dept_id = ?, sname = ?, semail = ?, sdob = ?, sphone = ?, sgender = ?, scgpa = ?, saddress = ? WHERE std_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssssss", $dept_id, $sname, $semail, $sdob, $sphone, $sgender, $scgpa, $saddress, $std_id);
    }

    if ($stmt->execute()) {
        $_SESSION['message'] = "Student updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating student: " . $conn->error;
    }

    $stmt->close();
    $check_stmt->close();
}

header("Location: students.php");
exit();
?>
