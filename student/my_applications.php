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
    <title>My Applications - Student Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .application-card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }
        .application-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }
        .status-approved {
            border-left: 5px solid #198754;
            background: linear-gradient(135deg, #d4edda 0%, #f8fff9 100%);
        }
        .status-rejected {
            border-left: 5px solid #dc3545;
            background: linear-gradient(135deg, #f8d7da 0%, #fff5f5 100%);
        }
        .status-pending {
            border-left: 5px solid #ffc107;
            background: linear-gradient(135deg, #fff3cd 0%, #fffdf5 100%);
        }
        .company-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .timeline-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        .filter-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 12px;
        }
        .empty-state {
            padding: 60px 20px;
            text-align: center;
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
    <div class="text-bg-primary p-3" style="width: 250px; min-height: 100vh;">
        <h4 class="mb-4">Student Panel</h4>
        <ul class="nav flex-column">
            <li class="nav-item mb-2"><a href="student_dashboard.php" class="nav-link text-white">Dashboard</a></li>
            <li class="nav-item mb-2"><a href="departments.php" class="nav-link text-white">Departments</a></li>
            <li class="nav-item mb-2"><a href="campus_placements.php" class="nav-link text-white">Campus Placements</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link text-white active fw-bold">My Applications</a></li>
            <li class="nav-item mb-2"><a href="approved_applications.php" class="nav-link text-white">Approved Applications</a></li>
            <li class="nav-item mb-2"><a href="upcoming_deadlines.php" class="nav-link text-white">Upcoming Deadlines</a></li>
            <li class="nav-item mb-2"><a href="profile.php" class="nav-link text-white"><i class="fa-solid fa-user me-2"></i>Profile</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link text-white"><i class="fa-solid fa-gear me-2"></i>Settings</a></li>
        </ul>
    </div>

    <div class="container-fluid p-4">
        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-check-circle me-2"></i>
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-exclamation-triangle me-2"></i>
                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1"><i class="fa-solid fa-file-lines me-3 text-primary"></i>My Applications</h2>
                <p class="text-muted mb-0">Track your job applications and their current status</p>
            </div>
            <div>
                <a href="campus_placements.php" class="btn btn-primary me-2">
                    <i class="fa-solid fa-plus me-2"></i>Apply for More Jobs
                </a>
                <a href="student_dashboard.php" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Application Statistics -->
        <div class="row mb-4">
            <?php
            // Get application statistics
            $total_apps = $conn->query("SELECT COUNT(*) as count FROM applications WHERE std_id = $student_id")->fetch_assoc()['count'];
            $pending_apps = $conn->query("SELECT COUNT(*) as count FROM applications WHERE std_id = $student_id AND status = 'Pending'")->fetch_assoc()['count'];
            $approved_apps = $conn->query("SELECT COUNT(*) as count FROM applications WHERE std_id = $student_id AND status = 'Approved'")->fetch_assoc()['count'];
            $rejected_apps = $conn->query("SELECT COUNT(*) as count FROM applications WHERE std_id = $student_id AND status = 'Rejected'")->fetch_assoc()['count'];
            ?>
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-file-lines fa-2x mb-2 opacity-75"></i>
                        <h3 class="mb-1"><?php echo $total_apps; ?></h3>
                        <small>Total Applications</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-clock fa-2x mb-2"></i>
                        <h3 class="mb-1"><?php echo $pending_apps; ?></h3>
                        <small>Pending Review</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-check-circle fa-2x mb-2"></i>
                        <h3 class="mb-1"><?php echo $approved_apps; ?></h3>
                        <small>Approved</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-times-circle fa-2x mb-2"></i>
                        <h3 class="mb-1"><?php echo $rejected_apps; ?></h3>
                        <small>Not Selected</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Search -->
        <div class="card filter-card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Search Applications</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa-solid fa-search text-muted"></i></span>
                            <input type="text" class="form-control" id="searchApplications" placeholder="Search by company or job title...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Filter by Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="Pending" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="Approved" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                            <option value="Rejected" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Sort by</label>
                        <select class="form-select" id="sortBy">
                            <option value="latest">Latest Applied</option>
                            <option value="oldest">Oldest Applied</option>
                            <option value="company">Company Name</option>
                            <option value="status">Status</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Applications List -->
        <div class="row" id="applicationsList">
            <?php
            $applications_sql = "SELECT a.*, j.job_title, j.company_name, j.company_url, j.campus_date, j.last_date_apply 
                                 FROM applications a 
                                 LEFT JOIN job j ON a.job_id = j.id 
                                 WHERE a.std_id = $student_id 
                                 ORDER BY a.applied_date DESC";
            $applications_result = $conn->query($applications_sql);

            if ($applications_result && $applications_result->num_rows > 0):
                while ($app = $applications_result->fetch_assoc()):
                    // Get company initials
                    $company_initials = '';
                    $words = explode(' ', $app['company_name'] ?? 'Unknown');
                    foreach ($words as $word) {
                        if (!empty($word)) {
                            $company_initials .= strtoupper($word[0]);
                        }
                    }
                    $company_initials = substr($company_initials, 0, 2);

                    // Status styling
                    $status_class = match($app['status']) {
                        'Approved' => 'status-approved',
                        'Rejected' => 'status-rejected',
                        'Pending' => 'status-pending',
                        default => 'status-pending'
                    };

                    $status_icon = match($app['status']) {
                        'Approved' => 'fa-check-circle text-success',
                        'Rejected' => 'fa-times-circle text-danger',
                        'Pending' => 'fa-clock text-warning',
                        default => 'fa-question-circle text-secondary'
                    };

                    $status_bg = match($app['status']) {
                        'Approved' => 'bg-success',
                        'Rejected' => 'bg-danger',
                        'Pending' => 'bg-warning text-dark',
                        default => 'bg-secondary'
                    };

                    // Calculate days since application
                    $applied_date = new DateTime($app['applied_date']);
                    $today = new DateTime();
                    $days_ago = $today->diff($applied_date)->days;

                    // Check if campus date has passed
                    $campus_date = $app['campus_date'] ? new DateTime($app['campus_date']) : null;
                    $campus_passed = $campus_date && $campus_date < $today;
            ?>
                <div class="col-lg-6 mb-4 application-item" 
                     data-company="<?php echo strtolower($app['company_name'] ?? ''); ?>" 
                     data-title="<?php echo strtolower($app['job_title'] ?? ''); ?>"
                     data-status="<?php echo $app['status']; ?>"
                     data-date="<?php echo strtotime($app['applied_date']); ?>">
                    <div class="card application-card <?php echo $status_class; ?> h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-start mb-3">
                                <div class="company-avatar me-3">
                                    <?php echo $company_initials; ?>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1 fw-bold"><?php echo htmlspecialchars($app['job_title'] ?? 'Unknown Position'); ?></h5>
                                    <p class="text-muted mb-2">
                                        <i class="fa-solid fa-building me-1"></i>
                                        <?php echo htmlspecialchars($app['company_name'] ?? 'Unknown Company'); ?>
                                    </p>
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="status-badge <?php echo $status_bg; ?>">
                                            <i class="fa-solid <?php echo str_replace('text-success text-danger text-warning text-secondary', '', $status_icon); ?>"></i>
                                            <?php echo $app['status']; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">Applied <?php echo $days_ago; ?> days ago</small>
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Applied On</small>
                                    <span class="fw-semibold"><?php echo date('d M Y', strtotime($app['applied_date'])); ?></span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Campus Date</small>
                                    <span class="fw-semibold <?php echo $campus_passed ? 'text-muted' : ''; ?>">
                                        <?php echo $app['campus_date'] ? date('d M Y', strtotime($app['campus_date'])) : 'TBD'; ?>
                                        <?php if ($campus_passed): ?>
                                            <i class="fa-solid fa-clock text-muted ms-1" title="Date has passed"></i>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Application Timeline -->
                            <div class="mb-3">
                                <div class="d-flex align-items-center">
                                    <span class="timeline-dot bg-primary"></span>
                                    <small class="text-muted">Application submitted</small>
                                    <span class="ms-auto">
                                        <i class="fa-solid fa-check text-primary"></i>
                                    </span>
                                </div>
                                <div class="d-flex align-items-center mt-1">
                                    <span class="timeline-dot <?php echo $app['status'] != 'Pending' ? 'bg-warning' : 'bg-light'; ?>"></span>
                                    <small class="text-muted">Under review</small>
                                    <span class="ms-auto">
                                        <?php if ($app['status'] != 'Pending'): ?>
                                            <i class="fa-solid fa-check text-warning"></i>
                                        <?php else: ?>
                                            <i class="fa-solid fa-clock text-muted"></i>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="d-flex align-items-center mt-1">
                                    <span class="timeline-dot <?php echo $app['status'] == 'Approved' ? 'bg-success' : ($app['status'] == 'Rejected' ? 'bg-danger' : 'bg-light'); ?>"></span>
                                    <small class="text-muted">Final decision</small>
                                    <span class="ms-auto">
                                        <?php if ($app['status'] == 'Approved'): ?>
                                            <i class="fa-solid fa-check text-success"></i>
                                        <?php elseif ($app['status'] == 'Rejected'): ?>
                                            <i class="fa-solid fa-times text-danger"></i>
                                        <?php else: ?>
                                            <i class="fa-solid fa-hourglass text-muted"></i>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <?php if (!empty($app['company_url'])): ?>
                                        <a href="<?php echo htmlspecialchars($app['company_url']); ?>" target="_blank" class="text-decoration-none me-3">
                                            <small><i class="fa-solid fa-external-link me-1"></i>Company Website</small>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#appModal<?php echo $app['id']; ?>">
                                    <i class="fa-solid fa-eye me-1"></i>View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Application Details Modal -->
                <div class="modal fade" id="appModal<?php echo $app['id']; ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">
                                    <i class="fa-solid fa-file-lines me-2"></i>
                                    Application Details
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="company-avatar me-3">
                                                <?php echo $company_initials; ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-1"><?php echo htmlspecialchars($app['job_title'] ?? 'Unknown Position'); ?></h6>
                                                <p class="mb-0 text-muted"><?php echo htmlspecialchars($app['company_name'] ?? 'Unknown Company'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <span class="status-badge <?php echo $status_bg; ?> fs-6">
                                            <i class="fa-solid <?php echo str_replace('text-success text-danger text-warning text-secondary', '', $status_icon); ?>"></i>
                                            <?php echo $app['status']; ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <small class="text-muted">Application ID</small>
                                        <div class="fw-semibold">#<?php echo str_pad($app['id'], 6, '0', STR_PAD_LEFT); ?></div>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted">Applied Date</small>
                                        <div class="fw-semibold"><?php echo date('d F Y', strtotime($app['applied_date'])); ?></div>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted">Campus Date</small>
                                        <div class="fw-semibold">
                                            <?php echo $app['campus_date'] ? date('d F Y', strtotime($app['campus_date'])) : 'To be announced'; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="fw-bold mb-3">Application Status Timeline</h6>
                                    <div class="timeline">
                                        <div class="d-flex align-items-center mb-3 p-3 bg-light rounded">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <i class="fa-solid fa-paper-plane"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <strong>Application Submitted</strong>
                                                <div class="text-muted small">
                                                    <?php echo date('d F Y \a\t g:i A', strtotime($app['applied_date'])); ?>
                                                </div>
                                            </div>
                                            <i class="fa-solid fa-check text-success fa-lg"></i>
                                        </div>

                                        <?php if ($app['status'] != 'Pending'): ?>
                                        <div class="d-flex align-items-center mb-3 p-3 bg-light rounded">
                                            <div class="bg-<?php echo $app['status'] == 'Approved' ? 'success' : 'danger'; ?> text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <i class="fa-solid fa-<?php echo $app['status'] == 'Approved' ? 'check' : 'times'; ?>"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <strong>Application <?php echo $app['status']; ?></strong>
                                                <div class="text-muted small">
                                                    Decision made by the company
                                                </div>
                                            </div>
                                            <i class="fa-solid fa-check text-<?php echo $app['status'] == 'Approved' ? 'success' : 'danger'; ?> fa-lg"></i>
                                        </div>
                                        <?php endif; ?>

                                        <?php if ($app['status'] == 'Pending'): ?>
                                        <div class="d-flex align-items-center mb-3 p-3 border border-dashed rounded">
                                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <i class="fa-solid fa-hourglass-half"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <strong>Awaiting Review</strong>
                                                <div class="text-muted small">
                                                    Your application is being reviewed by the company
                                                </div>
                                            </div>
                                            <i class="fa-solid fa-clock text-muted fa-lg"></i>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if ($app['status'] == 'Approved'): ?>
                                    <div class="alert alert-success">
                                        <i class="fa-solid fa-party-horn me-2"></i>
                                        <strong>Congratulations!</strong> Your application has been approved. You will be contacted soon with further instructions.
                                    </div>
                                <?php elseif ($app['status'] == 'Rejected'): ?>
                                    <div class="alert alert-info">
                                        <i class="fa-solid fa-info-circle me-2"></i>
                                        <strong>Keep trying!</strong> Don't give up - there are many more opportunities waiting for you.
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <?php if (!empty($app['company_url'])): ?>
                                    <a href="<?php echo htmlspecialchars($app['company_url']); ?>" target="_blank" class="btn btn-primary">
                                        <i class="fa-solid fa-external-link me-1"></i>Visit Company
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                endwhile;
            else:
            ?>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body empty-state">
                            <i class="fa-solid fa-file-lines fa-4x text-muted mb-4"></i>
                            <h4 class="text-muted mb-3">No Applications Yet</h4>
                            <p class="text-muted mb-4">You haven't applied for any jobs yet. Start exploring opportunities and apply for your dream job!</p>
                            <a href="campus_placements.php" class="btn btn-primary btn-lg">
                                <i class="fa-solid fa-search me-2"></i>Browse Job Opportunities
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Search and Filter Functionality
document.getElementById('searchApplications').addEventListener('input', filterApplications);
document.getElementById('statusFilter').addEventListener('change', filterApplications);
document.getElementById('sortBy').addEventListener('change', sortApplications);

// Auto-filter on page load if status parameter is present
document.addEventListener('DOMContentLoaded', function() {
    filterApplications();
});

function filterApplications() {
    const searchTerm = document.getElementById('searchApplications').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const applicationItems = document.querySelectorAll('.application-item');

    applicationItems.forEach(item => {
        const company = item.dataset.company;
        const title = item.dataset.title;
        const status = item.dataset.status;
        
        let showApplication = true;

        // Search filter
        if (searchTerm && !company.includes(searchTerm) && !title.includes(searchTerm)) {
            showApplication = false;
        }

        // Status filter
        if (statusFilter && status !== statusFilter) {
            showApplication = false;
        }

        item.style.display = showApplication ? 'block' : 'none';
    });
}

function sortApplications() {
    const sortBy = document.getElementById('sortBy').value;
    const container = document.getElementById('applicationsList');
    const items = Array.from(container.children);

    items.sort((a, b) => {
        switch(sortBy) {
            case 'oldest':
                return parseInt(a.dataset.date) - parseInt(b.dataset.date);
            case 'company':
                return a.dataset.company.localeCompare(b.dataset.company);
            case 'status':
                const statusOrder = {'Pending': 1, 'Approved': 2, 'Rejected': 3};
                return statusOrder[a.dataset.status] - statusOrder[b.dataset.status];
            case 'latest':
            default:
                return parseInt(b.dataset.date) - parseInt(a.dataset.date);
        }
    });

    items.forEach(item => container.appendChild(item));
}

// Auto-refresh page every 30 seconds to check for status updates
setInterval(() => {
    // Only refresh if user hasn't interacted recently
    if (document.hidden === false) {
        // You can implement a silent AJAX check here instead of full page refresh
        console.log('Checking for application updates...');
    }
}, 30000);
</script>
</body>
</html>
