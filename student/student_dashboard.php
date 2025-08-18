<?php
session_start();
if (!isset($_SESSION['std_info'])) {
    header("location:login.php");
    exit();
}

// Set student details in session if not already set
// if (!isset($_SESSION['student_email']) && isset($_SESSION['std_id'])) {
//     include('../config.php');
//     $student_id = $_SESSION['std_id'];
//     $sql = "SELECT s.*, d.department_name FROM student s 
//             LEFT JOIN department d ON s.dept_id = d.d_id 
//             WHERE s.std_id='$student_id'";
//     $result = $conn->query($sql);   
//     if ($result && $result->num_rows > 0) {
//         $row = $result->fetch_assoc();
//         $_SESSION['student_email'] = $row['semail'];
//         $_SESSION['student_name'] = $row['sname'];
//         $_SESSION['student_department'] = $row['department_name'];
//         $_SESSION['student_cgpa'] = $row['scgpa'];
//     }
// }
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-light">
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light text-bg-light p-3 shadow-sm px-4">
        <a class="navbar-brand" href="#">Student Dashboard</a>

        <div class="ms-auto d-flex align-items-center">
            <span class="me-3 fw-semibold text-capitalize">
                <?php echo $_SESSION['std_info']['sname'] ?? 'Student'; ?>
            </span>

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="../assets/images/user.png" alt="Profile" width="40" height="40" class="rounded-circle">
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li><h6 class="dropdown-header"><?php echo $_SESSION['student_email'] ?? 'student@example.com'; ?></h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><a class="dropdown-item text-danger" href="action/logout_action.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex" id="wrapper">
    
     <!-- Sidebar -->
    <div class="text-bg-success p-3" style="width: 250px; min-height: 100vh;">
        <h4 class="mb-4">Student Panel</h4>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a href="#" class="nav-link text-white">Dashboard</a>
            </li>
            <li class="nav-item mb-2">
                <a href="departments.php" class="nav-link text-white">Departments</a>
            </li>
            <li class="nav-item mb-2">
                <a href="campus_placements.php" class="nav-link text-white">Campus Placements</a>
            </li>
            <li class="nav-item mb-2">
                <a href="my_applications.php" class="nav-link text-white">My Applications</a>
            </li>
            <li class="nav-item mb-2">
                <a href="profile.php" class="nav-link text-white"><i class="fa-solid fa-user me-2"></i>Profile</a>
            </li>
            <li class="nav-item mb-2">
                <a href="#" class="nav-link text-white"><i class="fa-solid fa-gear me-2"></i>Settings</a>
            </li>
        </ul>
    </div>

    <div class="container-fluid p-4">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h4>Welcome, <?php echo $_SESSION['student_name'] ?? 'Student'; ?>!</h4>
                        <p class="mb-0">
                            <i class="fa-solid fa-graduation-cap me-2"></i>
                            Department: <?php echo $_SESSION['student_department'] ?? 'N/A'; ?> | 
                            CGPA: <?php echo $_SESSION['student_cgpa'] ?? 'N/A'; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards Row -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary p-3 text-white mb-3">
                    <div class="card-body" style="height: 100px;">
                        <h6>Departments</h6>
                        <h3 class="mb-0">
                            <?php
                            include('../config.php');
                            $dept_count = $conn->query("SELECT COUNT(*) as count FROM department")->fetch_assoc()['count'];
                            echo $dept_count;
                            ?>
                        </h3>
                        <small>Available Departments</small>
                    </div>
                    <div class="card-footer text-bg-light p-3">
                        <a href="departments.php" class="text-dark text-decoration-none">
                            View Details →
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning p-3 text-white mb-3">
                    <div class="card-body" style="height: 100px;">
                        <h6>Campus Placements</h6>
                        <h3 class="mb-0">
                            <?php
                            $job_count = $conn->query("SELECT COUNT(*) as count FROM job WHERE campus_date >= CURDATE()")->fetch_assoc()['count'];
                            echo $job_count;
                            ?>
                        </h3>
                        <small>Available Opportunities</small>
                    </div>
                    <div class="card-footer text-bg-light p-3">
                        <a href="campus_placements.php" class="text-dark text-decoration-none">View Details →</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info p-3 text-white mb-3">
                    <div class="card-body" style="height: 100px;">
                        <h6>My Applications</h6>
                        <h3 class="mb-0">
                            <?php
                            $student_id = $_SESSION['student_id'];
                            $app_count = $conn->query("SELECT COUNT(*) as count FROM applications WHERE std_id = $student_id")->fetch_assoc()['count'];
                            echo $app_count;
                            ?>
                        </h3>
                        <small>Applied Jobs</small>
                    </div>
                    <div class="card-footer text-bg-light p-3">
                        <a href="my_applications.php" class="text-dark text-decoration-none">View Details →</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-secondary p-3 text-white mb-3">
                    <div class="card-body" style="height: 100px;">
                        <h6>Approved Applications</h6>
                        <h3 class="mb-0">
                            <?php
                            $approved_count = $conn->query("SELECT COUNT(*) as count FROM applications WHERE std_id = $student_id AND status = 'Approved'")->fetch_assoc()['count'];
                            echo $approved_count;
                            ?>
                        </h3>
                        <small>Selected Applications</small>
                    </div>
                    <div class="card-footer text-bg-light p-3">
                        <a href="my_applications.php" class="text-dark text-decoration-none">View Details →</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fa-solid fa-bolt me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <a href="campus_placements.php" class="btn btn-warning btn-lg w-100">
                                    <i class="fa-solid fa-briefcase me-2"></i>Browse Jobs
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="my_applications.php" class="btn btn-info btn-lg w-100">
                                    <i class="fa-solid fa-file-lines me-2"></i>View Applications
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="profile.php" class="btn btn-secondary btn-lg w-100">
                                    <i class="fa-solid fa-user-edit me-2"></i>Update Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fa-solid fa-clock me-2"></i>Recent Applications</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $recent_apps_sql = "SELECT a.*, j.job_title, j.company_name 
                                           FROM applications a 
                                           LEFT JOIN job j ON a.job_id = j.id 
                                           WHERE a.std_id = $student_id 
                                           ORDER BY a.applied_date DESC LIMIT 5";
                        $recent_apps = $conn->query($recent_apps_sql);

                        if ($recent_apps && $recent_apps->num_rows > 0):
                            while ($app = $recent_apps->fetch_assoc()):
                                $status_class = '';
                                switch($app['status']) {
                                    case 'Approved': $status_class = 'success'; break;
                                    case 'Rejected': $status_class = 'danger'; break;
                                    case 'Pending': $status_class = 'warning'; break;
                                    default: $status_class = 'secondary';
                                }
                        ?>
                            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                <div>
                                    <strong><?php echo htmlspecialchars($app['job_title'] ?? 'N/A'); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($app['company_name'] ?? 'N/A'); ?></small>
                                </div>
                                <span class="badge bg-<?php echo $status_class; ?>"><?php echo $app['status']; ?></span>
                            </div>
                        <?php
                            endwhile;
                        else:
                        ?>
                            <p class="text-muted">No applications yet. <a href="campus_placements.php">Browse jobs</a> to get started!</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fa-solid fa-calendar-days me-2"></i>Upcoming Deadlines</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $upcoming_sql = "SELECT * FROM job 
                                        WHERE last_date_apply >= CURDATE() 
                                        ORDER BY last_date_apply ASC LIMIT 5";
                        $upcoming = $conn->query($upcoming_sql);

                        if ($upcoming && $upcoming->num_rows > 0):
                            while ($job = $upcoming->fetch_assoc()):
                                $deadline = new DateTime($job['last_date_apply']);
                                $today = new DateTime();
                                $days_left = $today->diff($deadline)->days;
                        ?>
                            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                <div>
                                    <strong><?php echo htmlspecialchars($job['job_title']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($job['company_name']); ?></small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted"><?php echo date('d M', strtotime($job['last_date_apply'])); ?></small><br>
                                    <span class="badge bg-<?php echo $days_left <= 3 ? 'danger' : ($days_left <= 7 ? 'warning' : 'success'); ?>">
                                        <?php echo $days_left; ?> days
                                    </span>
                                </div>
                            </div>
                        <?php
                            endwhile;
                        else:
                        ?>
                            <p class="text-muted">No upcoming deadlines.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>