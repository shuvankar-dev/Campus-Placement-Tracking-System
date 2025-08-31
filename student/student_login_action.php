<?php
session_start();
include("../config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // First, get the student record by email
    $query = "SELECT s.*, d.department_name 
              FROM student s 
              LEFT JOIN department d ON s.dept_id = d.d_id 
              WHERE s.semail='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verify the password - check both hashed and plain text for compatibility
        if (password_verify($password, $row['spassword']) || $row['spassword'] === $password) {
            $_SESSION['std_info'] = $row;
            header("location:student_dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid Email or Password'); window.location='../student/login.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid Email or Password'); window.location='../student/login.php';</script>";
    }
}
?>
