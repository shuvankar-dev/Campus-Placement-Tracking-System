<?php
session_start();
include("../config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Join with department table to get department name
    $query = "SELECT s.*, d.department_name 
              FROM student s 
              LEFT JOIN department d ON s.dept_id = d.d_id 
              WHERE s.semail='$email' AND s.spassword='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['std_info'] = $row;
       
        header("location:student_dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid Email or Password'); window.location='../student/login.php';</script>";
    }
}
?>
