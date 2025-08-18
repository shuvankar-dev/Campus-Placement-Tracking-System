<?php
session_start();
include("../config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use correct table name -> student (not students)
    $query = "SELECT * FROM student WHERE semail='$email' AND spassword='$password'";
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
