<?php
session_start();

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Clear session cookie for complete security
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect to main campus placement homepage
header("Location: ../../index.php");
exit();
?>
