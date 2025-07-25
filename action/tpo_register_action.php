<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO tpo_users (first_name, last_name, email, password) 
            VALUES ('$first_name', '$last_name', '$email', '$password')";
    
    if ($conn->query($sql)) {
        header("Location: ../tpo/register.php?success=1");
        exit();
    } else {
        header("Location: ../tpo/register.php?error=" . urlencode($conn->error));
        exit();
    }
}
?>
