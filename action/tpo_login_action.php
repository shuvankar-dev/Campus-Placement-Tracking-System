<?php
session_start();
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM tpo_users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['tpo_id'] = $row['tpo_id'];
            $_SESSION['tpo_first_name'] = $row['first_name'];
            $_SESSION['tpo_last_name'] = $row['last_name'];
            $_SESSION['tpo_email'] = $row['email'];

            header("Location: ../tpo/tpo_dashboard.php");
            exit();
        } else {
            header("Location: ../tpo/index.php?error=Invalid Password");
            exit();
        }
    } else {
        header("Location: ../tpo/index.php?error=No Account Found");
        exit();
    }
}
?>
