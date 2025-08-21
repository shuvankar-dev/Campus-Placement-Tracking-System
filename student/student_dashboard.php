<?php
session_start();
if (!isset($_SESSION['std_info'])) {
    header("location:login.php");
    exit();
}

// Student data from session
$student_id = $_SESSION['std_info']['std_id'];
$student_name = $_SESSION['std_info']['sname'];
$student_email = $_SESSION['std_info']['semail'];
$student_cgpa = $_SESSION['std_info']['scgpa'];

// Handle department name - fetch from database if not in session
if (isset($_SESSION['std_info']['department_name'])) {
    $student_department = $_SESSION['std_info']['department_name'];
} else {
    // Fetch department name from database using dept_id
    include('../config.php');
    $dept_id = $_SESSION['std_info']['dept_id'] ?? null;
    if ($dept_id) {
        $dept_query = "SELECT department_name FROM department WHERE d_id = $dept_id";
        $dept_result = $conn->query($dept_query);
        $student_department = $dept_result && $dept_result->num_rows > 0 
            ? $dept_result->fetch_assoc()['department_name'] 
            : 'Not Assigned';
    } else {
        $student_department = 'Not Assigned';
    }
}

?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .hover-bg-light:hover {
            background-color: #f8f9fa !important;
            transition: background-color 0.3s ease;
        }
        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        .stats-card {
            transition: all 0.3s ease;
        }
        .stats-card:hover {
            transform: scale(1.02);
        }
        .welcome-section {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            position: relative;
            overflow: hidden;
        }
        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="white" opacity="0.05"/><circle cx="80" cy="40" r="1" fill="white" opacity="0.05"/><circle cx="40" cy="80" r="1" fill="white" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
        }
        .btn-action {
            transition: all 0.3s ease;
            border-radius: 10px;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body class="bg-light">
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light text-bg-light p-3 shadow-sm px-4">
        <a class="navbar-brand" href="#">Student Dashboard</a>

        <div class="ms-auto d-flex align-items-center">
            <span class="me-3 fw-semibold text-capitalize">
                <?php echo htmlspecialchars($student_name); ?>
            </span>

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="../assets/images/user.png" alt="Profile" width="40" height="40" class="rounded-circle">
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li><h6 class="dropdown-header"><?php echo htmlspecialchars($student_email); ?></h6></li>
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
            <li class="nav-item mb-2"><a href="#" class="nav-link text-white">Dashboard</a></li>
            <li class="nav-item mb-2"><a href="departments.php" class="nav-link text-white">Departments</a></li>
            <li class="nav-item mb-2"><a href="campus_placements.php" class="nav-link text-white">Campus Placements</a></li>
            <li class="nav-item mb-2"><a href="my_applications.php" class="nav-link text-white">My Applications</a></li>
            <li class="nav-item mb-2"><a href="profile.php" class="nav-link text-white"><i class="fa-solid fa-user me-2"></i>Profile</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link text-white"><i class="fa-solid fa-gear me-2"></i>Settings</a></li>
        </ul>
    </div>

    <div class="container-fluid p-4">
        <!-- Enhanced Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg position-relative overflow-hidden" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                    <div class="card-body text-white p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-white bg-opacity-20 rounded-circle p-3 me-4">
                                        <i class="fa-solid fa-user-graduate fa-2x text-white"></i>
                                    </div>
                                    <div>
                                        <h2 class="mb-1 fw-bold">Welcome back, <?php echo htmlspecialchars($student_name); ?>! 👋</h2>
                                        <p class="mb-0 fs-5 opacity-90">Ready to explore new opportunities today?</p>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white bg-opacity-20 rounded p-2 me-3">
                                                <i class="fa-solid fa-building-columns text-white"></i>
                                            </div>
                                            <div>
                                                <small class="opacity-75">Department</small>
                                                <div class="fw-semibold"><?php echo htmlspecialchars($student_department); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white bg-opacity-20 rounded p-2 me-3">
                                                <i class="fa-solid fa-chart-line text-white"></i>
                                            </div>
                                            <div>
                                                <small class="opacity-75">CGPA</small>
                                                <div class="fw-semibold"><?php echo htmlspecialchars($student_cgpa); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="bg-white bg-opacity-10 rounded-3 p-4">
                                    <i class="fa-solid fa-rocket fa-3x mb-3 text-white opacity-75"></i>
                                    <h5 class="fw-bold">Your Success Journey</h5>
                                    <p class="small opacity-90 mb-0">Track applications, explore opportunities, and achieve your career goals!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Decorative elements -->
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="fa-solid fa-graduation-cap" style="font-size: 8rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards Row -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-warning p-3 text-white mb-3 stats-card">
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
                <div class="card bg-info p-3 text-white mb-3 stats-card">
                    <div class="card-body" style="height: 100px;">
                        <h6>My Applications</h6>
                        <h3 class="mb-0">
                            <?php
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
                <div class="card text-bg-secondary p-3 text-white mb-3 stats-card">
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
            <div class="col-md-3">
                <div class="card bg-danger p-3 text-white mb-3 stats-card">
                    <div class="card-body" style="height: 100px;">
                        <h6>Upcoming Deadlines</h6>
                        <h3 class="mb-0">
                            <?php
                            $deadline_count = $conn->query("SELECT COUNT(*) as count FROM job WHERE last_date_apply >= CURDATE()")->fetch_assoc()['count'];
                            echo $deadline_count;
                            ?>
                        </h3>
                        <small>Application Deadlines</small>
                    </div>
                    <div class="card-footer text-bg-light p-3">
                        <a href="campus_placements.php" class="text-dark text-decoration-none">View Details →</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Applications Section - Moved up for prominence -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fa-solid fa-file-lines me-2"></i>Recent Applications</h5>
                        <a href="my_applications.php" class="btn btn-sm btn-light">View All</a>
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
                            echo '<div class="row">';
                            while ($app = $recent_apps->fetch_assoc()):
                                $status_class = match($app['status']) {
                                    'Approved' => 'success',
                                    'Rejected' => 'danger',
                                    'Pending' => 'warning',
                                    default => 'secondary'
                                };
                                $status_icon = match($app['status']) {
                                    'Approved' => 'fa-check-circle',
                                    'Rejected' => 'fa-times-circle',
                                    'Pending' => 'fa-clock',
                                    default => 'fa-question-circle'
                                };
                        ?>
                            <div class="col-md-6 mb-3">
                                <div class="card border-start border-4 border-<?php echo $status_class; ?> hover-bg-light h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold"><?php echo htmlspecialchars($app['job_title'] ?? 'N/A'); ?></h6>
                                                <p class="text-muted mb-2">
                                                    <i class="fa-solid fa-building me-1"></i>
                                                    <?php echo htmlspecialchars($app['company_name'] ?? 'N/A'); ?>
                                                </p>
                                                <small class="text-muted">
                                                    <i class="fa-solid fa-calendar me-1"></i>
                                                    Applied on <?php echo date('d M Y', strtotime($app['applied_date'])); ?>
                                                </small>
                                            </div>
                                            <span class="badge bg-<?php echo $status_class; ?> fs-6 px-3 py-2">
                                                <i class="fa-solid <?php echo $status_icon; ?> me-1"></i>
                                                <?php echo $app['status']; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; 
                            echo '</div>';
                        else: ?>
                            <div class="text-center py-5">
                                <i class="fa-solid fa-file-lines fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">No applications yet</h6>
                                <p class="text-muted mb-3">Start your career journey by applying to available positions!</p>
                                <a href="campus_placements.php" class="btn btn-primary">
                                    <i class="fa-solid fa-search me-2"></i>Browse Jobs
                                </a>
                            </div>
                        <?php endif; ?>
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
                            <div class="col-md-4 mb-3"><a href="campus_placements.php" class="btn btn-warning btn-lg w-100 btn-action"><i class="fa-solid fa-briefcase me-2"></i>Browse Jobs</a></div>
                            <div class="col-md-4 mb-3"><a href="my_applications.php" class="btn btn-info btn-lg w-100 btn-action"><i class="fa-solid fa-file-lines me-2"></i>View Applications</a></div>
                            <div class="col-md-4 mb-3"><a href="profile.php" class="btn btn-secondary btn-lg w-100 btn-action"><i class="fa-solid fa-user-edit me-2"></i>Update Profile</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
