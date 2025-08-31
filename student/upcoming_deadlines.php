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

include('../config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Deadlines - Student Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .deadline-card {
            transition: all 0.3s ease;
            border-left: 5px solid #dc3545;
            background: linear-gradient(135deg, #fff5f5 0%, #fefefe 100%);
        }
        .deadline-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.15) !important;
        }
        .deadline-card.urgent {
            border-left-color: #dc3545;
            background: linear-gradient(135deg, #fff5f5 0%, #ffeaea 100%);
        }
        .deadline-card.warning {
            border-left-color: #ffc107;
            background: linear-gradient(135deg, #fffbf0 0%, #fff8e1 100%);
        }
        .deadline-card.normal {
            border-left-color: #198754;
            background: linear-gradient(135deg, #f0fff4 0%, #e8f5e8 100%);
        }
        .company-logo {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #6c757d, #495057);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1rem;
        }
        .urgent-badge {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            animation: pulse 2s infinite;
        }
        .warning-badge {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: #212529;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .normal-badge {
            background: linear-gradient(135deg, #198754, #157347);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
        .countdown-timer {
            font-size: 1.2rem;
            font-weight: bold;
            text-align: center;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .countdown-urgent { background: #fff5f5; color: #dc3545; border: 1px solid #f5c6cb; }
        .countdown-warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .countdown-normal { background: #d1f2eb; color: #0f5132; border: 1px solid #badbcc; }
        .timeline-container {
            position: relative;
            margin: 20px 0;
        }
        .timeline-line {
            position: absolute;
            left: 30px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, #dc3545, #ffc107, #198754);
        }
        .timeline-item {
            position: relative;
            padding-left: 80px;
            margin-bottom: 30px;
        }
        .timeline-dot {
            position: absolute;
            left: 20px;
            top: 20px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .timeline-dot.urgent { background: #dc3545; }
        .timeline-dot.warning { background: #ffc107; }
        .timeline-dot.normal { background: #198754; }
        .filter-tabs {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 5px;
            margin-bottom: 30px;
        }
        .filter-tab {
            border: none;
            background: transparent;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .filter-tab.active { background: #dc3545; color: white; }
        .filter-tab:hover { background: #f8f9fa; }
        .filter-tab.active:hover { background: #dc3545; }
        .stats-card {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border-radius: 15px;
            transition: transform 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .empty-state {
            text-align: center;
            padding: 80px 20px;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light text-bg-light p-3 shadow-sm px-4">
        <!-- Mobile hamburger menu button -->
        <button class="btn btn-outline-primary d-md-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
            <i class="fa-solid fa-bars"></i>
        </button>
        
        <a class="navbar-brand" href="#">Student Dashboard</a>

        <div class="ms-auto d-flex align-items-center">
            <span class="me-3 fw-semibold text-capitalize">
                <?php echo htmlspecialchars($student_name); ?>
            </span>

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-user"></i>
                    </div>
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
    
        <!-- Desktop Sidebar -->
        <div class="p-3 d-none d-md-block" style="width: 250px; min-height: 100vh; background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
            <h4 class="mb-4 text-white">Student Panel</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="student_dashboard.php" class="nav-link text-white"><i class="fa-solid fa-home me-2"></i>Dashboard</a></li>
                <li class="nav-item mb-2"><a href="campus_placements.php" class="nav-link text-white"><i class="fa-solid fa-briefcase me-2"></i>Campus Placements</a></li>
                <li class="nav-item mb-2"><a href="my_applications.php" class="nav-link text-white"><i class="fa-solid fa-file-lines me-2"></i>My Applications</a></li>
                <li class="nav-item mb-2"><a href="approved_applications.php" class="nav-link text-white"><i class="fa-solid fa-check-circle me-2"></i>Approved Applications</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link text-white active fw-bold"><i class="fa-solid fa-clock me-2"></i>Upcoming Deadlines</a></li>
            </ul>
        </div>

        <!-- Mobile Sidebar (Offcanvas) -->
        <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
            <div class="offcanvas-header text-white">
                <h5 class="offcanvas-title fw-bold" id="sidebarOffcanvasLabel">
                    <i class="fa-solid fa-graduation-cap me-2"></i>Student Panel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="nav flex-column">
                    <li class="nav-item mb-2"><a href="student_dashboard.php" class="nav-link text-white"><i class="fa-solid fa-home me-2"></i>Dashboard</a></li>
                    <li class="nav-item mb-2"><a href="campus_placements.php" class="nav-link text-white"><i class="fa-solid fa-briefcase me-2"></i>Campus Placements</a></li>
                    <li class="nav-item mb-2"><a href="my_applications.php" class="nav-link text-white"><i class="fa-solid fa-file-lines me-2"></i>My Applications</a></li>
                    <li class="nav-item mb-2"><a href="approved_applications.php" class="nav-link text-white"><i class="fa-solid fa-check-circle me-2"></i>Approved Applications</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link text-white active fw-bold"><i class="fa-solid fa-clock me-2"></i>Upcoming Deadlines</a></li>
                </ul>
            </div>
        </div>

        <div class="container-fluid p-4">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">
                        <i class="fa-solid fa-clock me-3 text-danger"></i>
                        Upcoming Application Deadlines
                    </h2>
                    <p class="text-muted mb-0">
                        Jobs posted by TPO with deadlines after today (<?php echo date('d M Y'); ?>)
                    </p>
                </div>
                <div>
                    <a href="campus_placements.php" class="btn btn-outline-primary me-2">
                        <i class="fa-solid fa-briefcase me-2"></i>Browse Jobs
                    </a>
                    <a href="student_dashboard.php" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>

            <?php
            // Get current date
            $today = date('Y-m-d');
            
            // Fetch only upcoming application deadlines from jobs posted by TPO that are still open
            $upcoming_sql = "SELECT j.*, 
                           DATEDIFF(j.last_date_apply, '$today') as days_remaining,
                           (SELECT COUNT(*) FROM applications a WHERE a.job_id = j.id AND a.std_id = $student_id) as applied
                           FROM job j 
                           WHERE j.last_date_apply > '$today' 
                           ORDER BY j.last_date_apply ASC";
            $upcoming_result = $conn->query($upcoming_sql);

            // Count deadlines by urgency (only open application deadlines)
            $urgent_count = 0;
            $warning_count = 0;
            $normal_count = 0;
            $total_jobs = 0;

            if ($upcoming_result && $upcoming_result->num_rows > 0) {
                $total_jobs = $upcoming_result->num_rows;
                $upcoming_result->data_seek(0);
                while ($job = $upcoming_result->fetch_assoc()) {
                    $days = $job['days_remaining'];
                    if ($days <= 2) $urgent_count++;
                    elseif ($days <= 7) $warning_count++;
                    else $normal_count++;
                }
            }
            ?>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card p-4">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-briefcase fa-2x me-3"></i>
                            <div>
                                <h3 class="mb-0"><?php echo $total_jobs; ?></h3>
                                <small class="opacity-75">Open Positions</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card p-4">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-exclamation-triangle fa-2x me-3"></i>
                            <div>
                                <h3 class="mb-0"><?php echo $urgent_count; ?></h3>
                                <small class="opacity-75">Urgent (≤2 days)</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card p-4" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-clock fa-2x me-3"></i>
                            <div>
                                <h3 class="mb-0"><?php echo $warning_count; ?></h3>
                                <small class="opacity-75">This Week (≤7 days)</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card p-4" style="background: linear-gradient(135deg, #198754 0%, #157347 100%);">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-calendar fa-2x me-3"></i>
                            <div>
                                <h3 class="mb-0"><?php echo $normal_count; ?></h3>
                                <small class="opacity-75">Later (>7 days)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="filter-tabs">
                <button class="filter-tab active" onclick="filterDeadlines('all')">
                    <i class="fa-solid fa-list me-2"></i>All Jobs
                </button>
                <button class="filter-tab" onclick="filterDeadlines('not-applied')">
                    <i class="fa-solid fa-file-text me-2"></i>Not Applied Yet
                </button>
                <button class="filter-tab" onclick="filterDeadlines('applied')">
                    <i class="fa-solid fa-check me-2"></i>Already Applied
                </button>
                <button class="filter-tab" onclick="filterDeadlines('urgent')">
                    <i class="fa-solid fa-exclamation me-2"></i>Urgent Only
                </button>
            </div>

            <!-- Timeline View -->
            <div class="timeline-container">
                <div class="timeline-line"></div>
                
                <?php
                if ($upcoming_result && $upcoming_result->num_rows > 0):
                    $upcoming_result->data_seek(0);
                    while ($job = $upcoming_result->fetch_assoc()):
                        $days = $job['days_remaining'];
                        $urgency = $days <= 2 ? 'urgent' : ($days <= 7 ? 'warning' : 'normal');
                        $applied_status = $job['applied'] > 0 ? 'applied' : 'not-applied';
                        
                        // Get company initials
                        $company_initials = '';
                        $words = explode(' ', $job['company_name'] ?? 'Unknown');
                        foreach ($words as $word) {
                            if (!empty($word)) {
                                $company_initials .= strtoupper($word[0]);
                            }
                        }
                        $company_initials = substr($company_initials, 0, 2);
                ?>
                    <div class="timeline-item deadline-item" data-type="application" data-urgency="<?php echo $urgency; ?>" data-applied="<?php echo $applied_status; ?>">
                        <div class="timeline-dot <?php echo $urgency; ?>"></div>
                        
                        <div class="card deadline-card <?php echo $urgency; ?>">
                            <div class="card-body">
                                <!-- Countdown Timer -->
                                <div class="countdown-timer countdown-<?php echo $urgency; ?>">
                                    <?php if ($days == 0): ?>
                                        <i class="fa-solid fa-exclamation-triangle me-2"></i>Last Day to Apply!
                                    <?php elseif ($days == 1): ?>
                                        <i class="fa-solid fa-clock me-2"></i>Apply by Tomorrow
                                    <?php else: ?>
                                        <i class="fa-solid fa-calendar-days me-2"></i><?php echo $days; ?> days left to apply
                                    <?php endif; ?>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <!-- Job/Company Info -->
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="company-logo me-3">
                                                <?php echo $company_initials; ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="mb-1 fw-bold">
                                                    <?php echo htmlspecialchars($job['job_title'] ?? 'Unknown Position'); ?>
                                                </h5>
                                                <p class="text-muted mb-2">
                                                    <i class="fa-solid fa-building me-1"></i>
                                                    <?php echo htmlspecialchars($job['company_name'] ?? 'Unknown Company'); ?>
                                                </p>
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="<?php echo $urgency; ?>-badge">
                                                        <i class="fa-solid fa-file-text me-1"></i>
                                                        Application Deadline
                                                    </span>
                                                    <?php if ($job['applied'] > 0): ?>
                                                        <small class="text-success">
                                                            <i class="fa-solid fa-check-circle me-1"></i>Already Applied
                                                        </small>
                                                    <?php else: ?>
                                                        <small class="text-warning">
                                                            <i class="fa-solid fa-clock me-1"></i>Not Applied Yet
                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Job Description -->
                                        <?php if (!empty($job['job_description'])): ?>
                                            <div class="mb-3">
                                                <p class="text-muted mb-0">
                                                    <?php echo substr(htmlspecialchars($job['job_description']), 0, 150); ?>
                                                    <?php echo strlen($job['job_description']) > 150 ? '...' : ''; ?>
                                                </p>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Additional Job Info -->
                                        <div class="row text-sm">
                                            <?php if (!empty($job['campus_date'])): ?>
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <i class="fa-solid fa-calendar me-1"></i>
                                                        Campus Date: <?php echo date('d M Y', strtotime($job['campus_date'])); ?>
                                                    </small>
                                                </div>
                                            <?php endif; ?>
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <i class="fa-solid fa-clock me-1"></i>
                                                    Posted by TPO
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <!-- Date Info -->
                                        <div class="text-center">
                                            <div class="mb-3">
                                                <strong class="text-muted small">APPLICATION DEADLINE</strong>
                                                <div class="h5 mb-1 text-<?php echo $urgency == 'urgent' ? 'danger' : ($urgency == 'warning' ? 'warning' : 'success'); ?>">
                                                    <?php echo date('d M Y', strtotime($job['last_date_apply'])); ?>
                                                </div>
                                                <small class="text-muted">
                                                    <?php echo date('l', strtotime($job['last_date_apply'])); ?>
                                                </small>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="d-grid gap-2">
                                                <?php if ($job['applied'] == 0): ?>
                                                    <a href="campus_placements.php?job_id=<?php echo $job['id']; ?>" class="btn btn-<?php echo $urgency == 'urgent' ? 'danger' : 'primary'; ?> btn-sm">
                                                        <i class="fa-solid fa-paper-plane me-1"></i>Apply Now
                                                    </a>
                                                <?php else: ?>
                                                    <a href="my_applications.php?status=all" class="btn btn-success btn-sm">
                                                        <i class="fa-solid fa-eye me-1"></i>View Application
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal<?php echo $job['id']; ?>">
                                                    <i class="fa-solid fa-info-circle me-1"></i>Job Details
                                                </button>
                                                
                                                <?php if (!empty($job['company_url'])): ?>
                                                    <a href="<?php echo htmlspecialchars($job['company_url']); ?>" target="_blank" class="btn btn-outline-info btn-sm">
                                                        <i class="fa-solid fa-external-link me-1"></i>Company
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Modal -->
                    <div class="modal fade" id="detailModal<?php echo $job['id']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-<?php echo $urgency == 'urgent' ? 'danger' : ($urgency == 'warning' ? 'warning' : 'success'); ?> text-white">
                                    <h5 class="modal-title">
                                        <i class="fa-solid fa-file-text me-2"></i>
                                        Job Application Details
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h5><?php echo htmlspecialchars($job['job_title']); ?></h5>
                                            <p class="text-muted"><i class="fa-solid fa-building me-2"></i><?php echo htmlspecialchars($job['company_name']); ?></p>
                                            
                                            <?php if (!empty($job['job_description'])): ?>
                                                <div class="mb-4">
                                                    <h6>Job Description:</h6>
                                                    <div class="p-3 bg-light rounded">
                                                        <?php echo nl2br(htmlspecialchars($job['job_description'])); ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6 class="mb-0">Important Dates</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <strong>Application Deadline:</strong>
                                                        <div class="text-danger"><?php echo date('d F Y', strtotime($job['last_date_apply'])); ?></div>
                                                        <small class="text-muted"><?php echo $days; ?> days remaining</small>
                                                    </div>
                                                    
                                                    <?php if ($job['campus_date']): ?>
                                                        <div>
                                                            <strong>Campus Date:</strong>
                                                            <div><?php echo date('d F Y', strtotime($job['campus_date'])); ?></div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <?php if ($job['applied'] == 0): ?>
                                        <a href="campus_placements.php?job_id=<?php echo $job['id']; ?>" class="btn btn-primary">
                                            <i class="fa-solid fa-paper-plane me-1"></i>Apply Now
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>

                <?php else: ?>
                    <!-- Empty State -->
                    <div class="empty-state">
                        <i class="fa-solid fa-calendar-check fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted mb-3">No Open Application Deadlines</h4>
                        <p class="text-muted mb-4">
                            All current job application deadlines have passed or there are no jobs posted yet.<br>
                            Check back regularly for new opportunities posted by TPO.
                        </p>
                        <div>
                            <a href="campus_placements.php" class="btn btn-primary btn-lg me-3">
                                <i class="fa-solid fa-search me-2"></i>Browse All Jobs
                            </a>
                            <a href="my_applications.php" class="btn btn-outline-primary btn-lg">
                                <i class="fa-solid fa-list me-2"></i>View My Applications
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filter deadlines by type
        function filterDeadlines(filter) {
            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');

            // Show/hide deadline items
            const items = document.querySelectorAll('.deadline-item');
            items.forEach(item => {
                const applied = item.dataset.applied;
                const urgency = item.dataset.urgency;
                
                let show = false;
                switch(filter) {
                    case 'all':
                        show = true;
                        break;
                    case 'not-applied':
                        show = applied === 'not-applied';
                        break;
                    case 'applied':
                        show = applied === 'applied';
                        break;
                    case 'urgent':
                        show = urgency === 'urgent';
                        break;
                }
                
                item.style.display = show ? 'block' : 'none';
            });
        }

        // Live countdown updates
        function updateCountdowns() {
            const countdowns = document.querySelectorAll('.countdown-timer');
            countdowns.forEach(countdown => {
                // This would be implemented with real-time updates
                // For now, the PHP handles the countdown calculation
            });
        }

        // Auto-refresh every minute to update countdowns
        setInterval(updateCountdowns, 60000);

        // Notification for urgent deadlines
        document.addEventListener('DOMContentLoaded', function() {
            const urgentCount = <?php echo $urgent_count; ?>;
            if (urgentCount > 0) {
                // You can add browser notifications here
                console.log(`You have ${urgentCount} urgent deadline(s)!`);
            }
        });
    </script>
</body>
</html>
