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
    <title>Approved Applications - Student Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .success-card {
            background: linear-gradient(135deg, #d4edda 0%, #f8fff9 100%);
            border-left: 5px solid #198754;
            transition: all 0.3s ease;
        }
        .success-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(25, 135, 84, 0.15) !important;
        }
        .company-logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #198754, #20c997);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .congratulations-banner {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            border-radius: 15px;
            position: relative;
            overflow: hidden;
        }
        .congratulations-banner::before {
            content: '🎉';
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 3rem;
            opacity: 0.3;
        }
        .next-steps-card {
            background: linear-gradient(135deg, #fff3cd 0%, #fffdf5 100%);
            border-left: 4px solid #ffc107;
        }
        .timeline-item {
            border-left: 2px solid #198754;
            padding-left: 20px;
            margin-bottom: 20px;
            position: relative;
        }
        .timeline-item::before {
            content: '';
            width: 12px;
            height: 12px;
            background: #198754;
            border-radius: 50%;
            position: absolute;
            left: -7px;
            top: 5px;
        }
        .campus-date-card {
            background: linear-gradient(135deg, #e7f3ff 0%, #f8fcff 100%);
            border-left: 4px solid #0d6efd;
        }
        .contact-info {
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #dee2e6;
        }
        .celebration-icon {
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
        .status-badge {
            background: linear-gradient(135deg, #198754, #20c997);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
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
    <div class="p-3 d-none d-md-block" style="width: 250px; min-height: 100vh; background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
        <h4 class="mb-4 text-white">Student Panel</h4>
        <ul class="nav flex-column">
            <li class="nav-item mb-2"><a href="student_dashboard.php" class="nav-link text-white"><i class="fa-solid fa-home me-2"></i>Dashboard</a></li>
            <li class="nav-item mb-2"><a href="campus_placements.php" class="nav-link text-white"><i class="fa-solid fa-briefcase me-2"></i>Campus Placements</a></li>
            <li class="nav-item mb-2"><a href="my_applications.php" class="nav-link text-white"><i class="fa-solid fa-file-lines me-2"></i>My Applications</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link text-white active fw-bold"><i class="fa-solid fa-check-circle me-2"></i>Approved Applications</a></li>
            <li class="nav-item mb-2"><a href="upcoming_deadlines.php" class="nav-link text-white"><i class="fa-solid fa-clock me-2"></i>Upcoming Deadlines</a></li>
        </ul>
    </div>

    <!-- Mobile Sidebar (Offcanvas) -->
    <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
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
                <li class="nav-item mb-2"><a href="#" class="nav-link text-white active fw-bold"><i class="fa-solid fa-check-circle me-2"></i>Approved Applications</a></li>
                <li class="nav-item mb-2"><a href="upcoming_deadlines.php" class="nav-link text-white"><i class="fa-solid fa-clock me-2"></i>Upcoming Deadlines</a></li>
            </ul>
        </div>
    </div>

    <div class="container-fluid p-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">
                    <i class="fa-solid fa-trophy me-3 text-success celebration-icon"></i>
                    Approved Applications
                </h2>
                <p class="text-muted mb-0">Congratulations! Here are your successful applications</p>
            </div>
            <div>
                <a href="my_applications.php" class="btn btn-outline-primary me-2">
                    <i class="fa-solid fa-list me-2"></i>All Applications
                </a>
                <a href="student_dashboard.php" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>

        <?php
        // Fetch approved applications
        $approved_sql = "SELECT a.*, j.job_title, j.company_name, j.company_url, j.campus_date, j.last_date_apply, j.job_description 
                         FROM applications a 
                         LEFT JOIN job j ON a.job_id = j.id 
                         WHERE a.std_id = $student_id AND a.status = 'Approved'
                         ORDER BY a.applied_date DESC";
        $approved_result = $conn->query($approved_sql);

        if ($approved_result && $approved_result->num_rows > 0):
        ?>
            <!-- Congratulations Banner -->
            <div class="congratulations-banner text-white p-4 mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2 fw-bold">🎉 Congratulations, <?php echo htmlspecialchars($student_name); ?>!</h3>
                        <p class="mb-0 fs-5 opacity-90">
                            You have <?php echo $approved_result->num_rows; ?> approved application<?php echo $approved_result->num_rows > 1 ? 's' : ''; ?>! 
                            Your hard work has paid off. Get ready for the next exciting chapter of your career!
                        </p>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="fa-solid fa-graduation-cap fa-4x opacity-75"></i>
                    </div>
                </div>
            </div>

            <!-- Approved Applications -->
            <div class="row">
                <?php
                // Reset result pointer
                $approved_result->data_seek(0);
                while ($app = $approved_result->fetch_assoc()):
                    // Get company initials
                    $company_initials = '';
                    $words = explode(' ', $app['company_name'] ?? 'Unknown');
                    foreach ($words as $word) {
                        if (!empty($word)) {
                            $company_initials .= strtoupper($word[0]);
                        }
                    }
                    $company_initials = substr($company_initials, 0, 2);

                    // Calculate days since approval
                    $applied_date = new DateTime($app['applied_date']);
                    $today = new DateTime();
                    $days_ago = $today->diff($applied_date)->days;

                    // Check campus date status
                    $campus_date = $app['campus_date'] ? new DateTime($app['campus_date']) : null;
                    $campus_upcoming = $campus_date && $campus_date > $today;
                    $campus_today = $campus_date && $campus_date->format('Y-m-d') == $today->format('Y-m-d');
                    $campus_passed = $campus_date && $campus_date < $today;
                ?>
                    <div class="col-lg-6 mb-4">
                        <div class="card success-card h-100">
                            <div class="card-body">
                                <!-- Company Header -->
                                <div class="d-flex align-items-start mb-3">
                                    <div class="company-logo me-3">
                                        <?php echo $company_initials; ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1 fw-bold text-success">
                                            <i class="fa-solid fa-check-circle me-2"></i>
                                            <?php echo htmlspecialchars($app['job_title'] ?? 'Unknown Position'); ?>
                                        </h5>
                                        <p class="text-muted mb-2">
                                            <i class="fa-solid fa-building me-1"></i>
                                            <?php echo htmlspecialchars($app['company_name'] ?? 'Unknown Company'); ?>
                                        </p>
                                        <span class="status-badge">
                                            <i class="fa-solid fa-trophy"></i>
                                            Selected!
                                        </span>
                                    </div>
                                </div>

                                <!-- Timeline -->
                                <div class="mb-3">
                                    <h6 class="fw-bold mb-3">Your Success Journey</h6>
                                    <div class="timeline-item">
                                        <strong>Application Submitted</strong>
                                        <div class="text-muted small">
                                            <?php echo date('d F Y', strtotime($app['applied_date'])); ?>
                                        </div>
                                    </div>
                                    <div class="timeline-item">
                                        <strong>Application Approved! 🎉</strong>
                                        <div class="text-success small fw-semibold">
                                            Congratulations! You've been selected.
                                        </div>
                                    </div>
                                </div>

                                <!-- Campus Date Info -->
                                <?php if ($campus_date): ?>
                                    <div class="campus-date-card p-3 mb-3">
                                        <h6 class="fw-bold mb-2">
                                            <i class="fa-solid fa-calendar-check me-2 text-primary"></i>
                                            Campus Placement Date
                                        </h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong class="fs-5"><?php echo date('d F Y', strtotime($app['campus_date'])); ?></strong>
                                                <div class="text-muted small">
                                                    <?php echo date('l', strtotime($app['campus_date'])); ?>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <?php if ($campus_today): ?>
                                                    <span class="badge bg-danger fs-6">
                                                        <i class="fa-solid fa-exclamation me-1"></i>Today!
                                                    </span>
                                                <?php elseif ($campus_upcoming): ?>
                                                    <?php 
                                                    $days_remaining = $today->diff($campus_date)->days;
                                                    $badge_class = $days_remaining <= 3 ? 'bg-warning' : 'bg-primary';
                                                    ?>
                                                    <span class="badge <?php echo $badge_class; ?> fs-6">
                                                        <i class="fa-solid fa-clock me-1"></i>
                                                        <?php echo $days_remaining; ?> days to go
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary fs-6">
                                                        <i class="fa-solid fa-check me-1"></i>Completed
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Next Steps -->
                                <div class="next-steps-card p-3 mb-3">
                                    <h6 class="fw-bold mb-2">
                                        <i class="fa-solid fa-list-check me-2 text-warning"></i>
                                        Next Steps
                                    </h6>
                                    <ul class="mb-0 small">
                                        <?php if ($campus_upcoming): ?>
                                            <li>Prepare all required documents</li>
                                            <li>Arrive at campus on time for the placement</li>
                                            <li>Bring multiple copies of your resume</li>
                                        <?php elseif ($campus_today): ?>
                                            <li class="text-danger fw-bold">Report to campus today!</li>
                                            <li>Bring all required documents</li>
                                        <?php else: ?>
                                            <li>Wait for further communication from the company</li>
                                            <li>Check your email regularly for updates</li>
                                        <?php endif; ?>
                                        <li>Stay in touch with the placement office</li>
                                    </ul>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <?php if (!empty($app['company_url'])): ?>
                                            <a href="<?php echo htmlspecialchars($app['company_url']); ?>" target="_blank" class="text-decoration-none me-3">
                                                <small><i class="fa-solid fa-external-link me-1"></i>Company Website</small>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal<?php echo $app['id']; ?>">
                                        <i class="fa-solid fa-eye me-1"></i>View Full Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Modal -->
                    <div class="modal fade" id="detailModal<?php echo $app['id']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title">
                                        <i class="fa-solid fa-trophy me-2"></i>
                                        Application Approved - Full Details
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Congratulations Header -->
                                    <div class="alert alert-success border-0 mb-4">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-party-horn fa-2x me-3"></i>
                                            <div>
                                                <h5 class="alert-heading mb-1">Congratulations! 🎉</h5>
                                                <p class="mb-0">Your application for <strong><?php echo htmlspecialchars($app['job_title']); ?></strong> at <strong><?php echo htmlspecialchars($app['company_name']); ?></strong> has been approved!</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- Left Column - Job Details -->
                                        <div class="col-md-8">
                                            <div class="card mb-4">
                                                <div class="card-header bg-light">
                                                    <h6 class="mb-0"><i class="fa-solid fa-briefcase me-2"></i>Job Information</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <strong>Position:</strong>
                                                            <div><?php echo htmlspecialchars($app['job_title']); ?></div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Company:</strong>
                                                            <div><?php echo htmlspecialchars($app['company_name']); ?></div>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php if (!empty($app['job_description'])): ?>
                                                        <div class="mb-3">
                                                            <strong>Job Description:</strong>
                                                            <div class="mt-2 p-3 bg-light rounded">
                                                                <?php echo nl2br(htmlspecialchars($app['job_description'])); ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if (!empty($app['company_url'])): ?>
                                                        <div>
                                                            <strong>Company Website:</strong>
                                                            <div>
                                                                <a href="<?php echo htmlspecialchars($app['company_url']); ?>" target="_blank" class="text-decoration-none">
                                                                    <?php echo htmlspecialchars($app['company_url']); ?> 
                                                                    <i class="fa-solid fa-external-link ms-1"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right Column - Important Dates & Contact -->
                                        <div class="col-md-4">
                                            <!-- Important Dates -->
                                            <div class="card mb-4">
                                                <div class="card-header bg-primary text-white">
                                                    <h6 class="mb-0"><i class="fa-solid fa-calendar me-2"></i>Important Dates</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <strong class="text-muted small">APPLICATION DATE</strong>
                                                        <div class="fw-semibold"><?php echo date('d F Y', strtotime($app['applied_date'])); ?></div>
                                                    </div>
                                                    
                                                    <?php if ($app['campus_date']): ?>
                                                        <div class="mb-3">
                                                            <strong class="text-muted small">CAMPUS PLACEMENT DATE</strong>
                                                            <div class="fw-semibold text-primary">
                                                                <?php echo date('d F Y', strtotime($app['campus_date'])); ?>
                                                            </div>
                                                            <small class="text-muted"><?php echo date('l, g:i A', strtotime($app['campus_date'])); ?></small>
                                                        </div>
                                                    <?php endif; ?>

                                                    <div>
                                                        <strong class="text-muted small">APPLICATION DEADLINE WAS</strong>
                                                        <div class="fw-semibold"><?php echo date('d F Y', strtotime($app['last_date_apply'])); ?></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Contact Information -->
                                            <div class="contact-info p-3">
                                                <h6 class="fw-bold mb-3">
                                                    <i class="fa-solid fa-phone me-2 text-primary"></i>
                                                    Need Help?
                                                </h6>
                                                <div class="small">
                                                    <div class="mb-2">
                                                        <strong>Placement Office:</strong><br>
                                                        <i class="fa-solid fa-envelope me-1"></i> placement@college.edu<br>
                                                        <i class="fa-solid fa-phone me-1"></i> +91 12345 67890
                                                    </div>
                                                    <div class="text-muted">
                                                        Contact us for any queries regarding your placement.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Preparation Checklist -->
                                    <div class="card">
                                        <div class="card-header bg-warning text-dark">
                                            <h6 class="mb-0"><i class="fa-solid fa-list-check me-2"></i>Preparation Checklist</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="fw-bold mb-3">Documents to Prepare:</h6>
                                                    <ul class="list-unstyled">
                                                        <li><i class="fa-solid fa-file-pdf text-danger me-2"></i>Updated Resume (5 copies)</li>
                                                        <li><i class="fa-solid fa-id-card text-primary me-2"></i>Academic Transcripts</li>
                                                        <li><i class="fa-solid fa-certificate text-success me-2"></i>Certificates & Awards</li>
                                                        <li><i class="fa-solid fa-image text-info me-2"></i>Passport Size Photos</li>
                                                        <li><i class="fa-solid fa-id-badge text-warning me-2"></i>Identity Proof</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="fw-bold mb-3">What to Expect:</h6>
                                                    <ul class="list-unstyled">
                                                        <li><i class="fa-solid fa-comments text-primary me-2"></i>Technical Interview</li>
                                                        <li><i class="fa-solid fa-user-tie text-success me-2"></i>HR Round</li>
                                                        <li><i class="fa-solid fa-handshake text-info me-2"></i>Final Selection</li>
                                                        <li><i class="fa-solid fa-file-signature text-warning me-2"></i>Offer Letter Process</li>
                                                        <li><i class="fa-solid fa-rocket text-danger me-2"></i>Onboarding Details</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-success" onclick="window.print()">
                                        <i class="fa-solid fa-print me-1"></i>Print Details
                                    </button>
                                    <?php if (!empty($app['company_url'])): ?>
                                        <a href="<?php echo htmlspecialchars($app['company_url']); ?>" target="_blank" class="btn btn-primary">
                                            <i class="fa-solid fa-external-link me-1"></i>Visit Company
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

        <?php else: ?>
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fa-solid fa-trophy fa-4x text-muted mb-4"></i>
                <h4 class="text-muted mb-3">No Approved Applications Yet</h4>
                <p class="text-muted mb-4">
                    Keep applying and working hard! Your success is just around the corner.<br>
                    Continue exploring opportunities and showcase your skills.
                </p>
                <div>
                    <a href="campus_placements.php" class="btn btn-primary btn-lg me-3">
                        <i class="fa-solid fa-search me-2"></i>Browse Job Opportunities
                    </a>
                    <a href="my_applications.php" class="btn btn-outline-primary btn-lg">
                        <i class="fa-solid fa-list me-2"></i>View All Applications
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto-refresh to check for new approvals every 2 minutes
setInterval(() => {
    if (document.hidden === false) {
        // Check for updates (you can implement AJAX here)
        console.log('Checking for new approvals...');
    }
}, 120000);

// Celebration animation on page load
document.addEventListener('DOMContentLoaded', function() {
    const celebrationIcons = document.querySelectorAll('.celebration-icon');
    celebrationIcons.forEach(icon => {
        setTimeout(() => {
            icon.style.animation = 'bounce 2s infinite';
        }, 500);
    });
});
</script>
</body>
</html>
