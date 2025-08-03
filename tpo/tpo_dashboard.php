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
    <link rel="stylesheet" href="../assets/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card p-4 shadow" >
        <h3>Welcome, <?php echo $_SESSION['tpo_first_name'] . " " . $_SESSION['tpo_last_name']; ?> </h3>
        <p>You are now logged in as TPO.</p>
        <div class="mb-3">
            <label for="formFile" class="form-label">Upload File</label>
            <input class="form-control" type="file" id="formFile">
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="checkDefault">
            <label class="form-check-label" for="checkDefault">
                    Confirm Upload
            </label>
        </div>
        <button class="btn btn-primary mt-3" id="uploadBtn" disabled>Upload</button>

    </div>
</div>
<script src="../assets/app.js"></script>
</body>
</html>
