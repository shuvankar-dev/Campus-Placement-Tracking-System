<?php
session_start();

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Clear session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect to TPO login page
header("Location: index.php");
exit();
?>
