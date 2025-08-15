<?php
session_start();
if (!isset($_SESSION['tpo_id'])) {
    header("Location: index.php");
    exit();
}

include('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dept_id = $_POST['dept_id'];
    $sname = $_POST['sname'];
    $semail = $_POST['semail'];
    $spassword = password_hash($_POST['spassword'], PASSWORD_DEFAULT);
    $sdob = $_POST['sdob'];
    $sphone = $_POST['sphone'];
    $sgender = $_POST['sgender'];
    $scgpa = $_POST['scgpa'];
    $saddress = $_POST['saddress'];

    // Validate CGPA
    if ($scgpa < 0 || $scgpa > 10) {
        $_SESSION['error'] = "CGPA must be between 0 and 10.";
        header("Location: students.php");
        exit();
    }

    // Check if email already exists
    $check_email_sql = "SELECT std_id FROM student WHERE semail = ?";
    $check_stmt = $conn->prepare($check_email_sql);
    $check_stmt->bind_param("s", $semail);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $_SESSION['error'] = "Email already exists. Please use a different email.";
        header("Location: students.php");
        exit();
    }

    // Validate date of birth (student should be at least 16 years old)
    $today = new DateTime();
    $dob = new DateTime($sdob);
    $age = $today->diff($dob)->y;
    
    if ($age < 16) {
        $_SESSION['error'] = "Student must be at least 16 years old.";
        header("Location: students.php");
        exit();
    }

    // Insert student into database
    $sql = "INSERT INTO student (dept_id, sname, semail, spassword, sdob, sphone, sgender, scgpa, saddress) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $dept_id, $sname, $semail, $spassword, $sdob, $sphone, $sgender, $scgpa, $saddress);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Student added successfully!";
    } else {
        $_SESSION['error'] = "Error adding student: " . $conn->error;
    }

    $stmt->close();
    $check_stmt->close();
}

header("Location: students.php");
exit();
?>
