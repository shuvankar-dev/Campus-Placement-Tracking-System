<?php
session_start();
if (!isset($_SESSION['tpo_id'])) {
    header("Location: index.php");
    exit();
}

// Set the email in the current session
if (!isset($_SESSION['tpo_email']) && isset($_SESSION['tpo_id'])) {
    // Connect to the database to get the email
    include('../config.php');
    $tpo_id = $_SESSION['tpo_id'];
    $sql = "SELECT email FROM tpo_users WHERE id='$tpo_id'";
    $result = $conn->query($sql);   
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['tpo_email'] = $row['email'];
    }
}
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TPO Dashboard</title>
    <!-- <link rel="stylesheet" href="../assets/style.css"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-light">
    <!-- Top Navbar -->
        <nav class="navbar navbar-expand-1g navbar-light text-bg-light p-3 shadow-sm px-4">
            <a class="navbar-brand" href="#">TPO Dashboard</a>

            <div class="ms-auto d-flex align-items-center">
                <span class="me-3 fw-semibold text-capitalize">
                    <?php echo $_SESSION['tpo_first_name'] . " " . $_SESSION['tpo_last_name']; ?>
                </span>

                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../assets/images/user.png" alt="Profile" width="40" height="40" class="rounded-circle">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><h6 class="dropdown-header"><?php echo $_SESSION['tpo_email']; ?></h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item text-danger" href="../tpo/logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>


    <div class="d-flex" id="wrapper">
    
     <!-- Sidebar -->
    <div class="text-bg-primary p-3" style="width: 250px; min-height: 100vh;">
            <h4 class="mb-4">TPO Panel</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link text-white">Dashboard</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="departments.php" class="nav-link text-white">Departments</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="students.php" class="nav-link text-white">Students</a>
                </li>
            <li class="nav-item mb-2">
                <a href="jobs.php" class="nav-link text-white">Job Posts</a>
            </li>
            <li class="nav-item mb-2"><a href="applications.php" class="nav-link text-white">Applications</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link text-white"><i class="fa-solid fa-gear me-2"></i>Settings</a></li>
        </ul>
    </div>

    <div class="container-fluid p-4">
        <!-- Cards Row -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary p-3 text-white mb-3 ">
                    <div class="card-body" style="height: 100px;">Departments</div>
                    <div class="card-footer text-bg-light p-3">
                        <a href="departments.php" class="text-dark text-decoration-none">
                            View Details →
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning p-3 text-white mb-3">
                    <div class="card-body" style="height: 100px;">Jobs</div>
                    <div class="card-footer text-bg-light p-3">
                        <a href="jobs.php" class="text-dark text-decoration-none">View Details →</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success p-3 text-white mb-3">
                    <div class="card-body" style="height: 100px;"> Students</div>
                    <div class="card-footer text-bg-light p-3">
                        <a href="students.php" class="text-dark text-decoration-none">View Details →</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-secondary p-3 text-white mb-3">
                    <div class="card-body" style="height: 100px;">Applications</div>
                    <div class="card-footer text-bg-light p-3">
                        <a href="applications.php" class="text-dark text-decoration-none">View Details →</a>
                    </div>
                </div>
            </div>
        </div>

        
    </div>
</div>
<script src="../assets/app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
