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
            <!-- Mobile Menu Toggle -->
            <button class="btn btn-outline-primary d-md-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
                <i class="fa-solid fa-bars"></i>
            </button>
            
            <a class="navbar-brand" href="#">TPO Dashboard</a>

            <div class="ms-auto d-flex align-items-center">
                <span class="me-3 fw-semibold text-capitalize">
                    <?php echo $_SESSION['tpo_first_name'] . " " . $_SESSION['tpo_last_name']; ?>
                </span>

                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><h6 class="dropdown-header"><?php echo $_SESSION['tpo_email']; ?></h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../tpo/logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>


    <div class="d-flex" id="wrapper">
    
     <!-- Desktop Sidebar -->
    <div class="p-3 d-none d-md-block" style="width: 250px; min-height: 100vh; background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
        <div class="text-center mb-4">
            <div class="bg-white text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                <i class="fa-solid fa-user-tie fa-2x"></i>
            </div>
            <h5 class="text-white mb-0">TPO Panel</h5>
            <small class="text-white-50">Training & Placement</small>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a href="tpo_dashboard.php" class="nav-link text-white active fw-bold">
                    <i class="fa-solid fa-tachometer-alt me-3"></i>Dashboard
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="departments.php" class="nav-link text-white">
                    <i class="fa-solid fa-building me-3"></i>Departments
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="students.php" class="nav-link text-white">
                    <i class="fa-solid fa-graduation-cap me-3"></i>Students
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="jobs.php" class="nav-link text-white">
                    <i class="fa-solid fa-briefcase me-3"></i>Job Posts
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="applications.php" class="nav-link text-white">
                    <i class="fa-solid fa-file-text me-3"></i>Applications
                </a>
            </li>
        </ul>
    </div>

    <!-- Mobile Sidebar (Offcanvas) -->
    <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
        <div class="offcanvas-header">
            <div class="text-center w-100">
                <div class="bg-white text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                    <i class="fa-solid fa-user-tie fa-lg"></i>
                </div>
                <h6 class="text-white mb-0">TPO Panel</h6>
                <small class="text-white-50">Training & Placement</small>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="tpo_dashboard.php" class="nav-link text-white active fw-bold">
                        <i class="fa-solid fa-tachometer-alt me-3"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="departments.php" class="nav-link text-white">
                        <i class="fa-solid fa-building me-3"></i>Departments
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="students.php" class="nav-link text-white">
                        <i class="fa-solid fa-graduation-cap me-3"></i>Students
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="jobs.php" class="nav-link text-white">
                        <i class="fa-solid fa-briefcase me-3"></i>Job Posts
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="applications.php" class="nav-link text-white">
                        <i class="fa-solid fa-file-text me-3"></i>Applications
                    </a>
                </li>
            </ul>
        </div>
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
                    <div class="card-body" style="height: 100px;">Campus</div>
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
