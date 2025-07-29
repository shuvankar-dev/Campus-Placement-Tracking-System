<?php
session_start();
if (!isset($_SESSION['tpo_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TPO Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card p-4 shadow">
        <h3>Welcome, <?php echo $_SESSION['tpo_first_name'] . " " . $_SESSION['tpo_last_name']; ?> 🎉</h3>
        <p>You are now logged in as TPO.</p>
        <a href="tpo_logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>

</body>
</html>
