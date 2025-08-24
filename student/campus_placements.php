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
    <title>Campus Placements - Student Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .hover-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
            transition: all 0.3s ease;
        }
        .job-card {
            transition: all 0.3s ease;
            border-left: 4px solid #0d6efd;
        }
        .deadline-urgent {
            border-left-color: #dc3545 !important;
        }
        .deadline-warning {
            border-left-color: #ffc107 !important;
        }
        .deadline-safe {
            border-left-color: #198754 !important;
        }
        .company-logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
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
    
     <!-- Sidebar -->
    <div class="text-bg-primary p-3" style="width: 250px; min-height: 100vh;">
        <h4 class="mb-4">Student Panel</h4>
        <ul class="nav flex-column">
            <li class="nav-item mb-2"><a href="student_dashboard.php" class="nav-link text-white"><i class="fa-solid fa-home me-2"></i>Dashboard</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link text-white active fw-bold"><i class="fa-solid fa-briefcase me-2"></i>Campus Placements</a></li>
            <li class="nav-item mb-2"><a href="my_applications.php" class="nav-link text-white"><i class="fa-solid fa-file-lines me-2"></i>My Applications</a></li>
            <li class="nav-item mb-2"><a href="approved_applications.php" class="nav-link text-white"><i class="fa-solid fa-check-circle me-2"></i>Approved Applications</a></li>
            <li class="nav-item mb-2"><a href="upcoming_deadlines.php" class="nav-link text-white"><i class="fa-solid fa-clock me-2"></i>Upcoming Deadlines</a></li>
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
                <h2 class="mb-1"><i class="fa-solid fa-briefcase me-3 text-primary"></i>Campus Placements</h2>
                <p class="text-muted mb-0">Explore exciting career opportunities and apply for your dream job!</p>
            </div>
            <a href="student_dashboard.php" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white hover-card">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-briefcase fa-2x mb-2"></i>
                        <h4 class="mb-1">
                            <?php
                            $total_jobs = $conn->query("SELECT COUNT(*) as count FROM job")->fetch_assoc()['count'];
                            echo $total_jobs;
                            ?>
                        </h4>
                        <small>Total Opportunities</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white hover-card">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-calendar-check fa-2x mb-2"></i>
                        <h4 class="mb-1">
                            <?php
                            $active_jobs = $conn->query("SELECT COUNT(*) as count FROM job WHERE last_date_apply >= CURDATE()")->fetch_assoc()['count'];
                            echo $active_jobs;
                            ?>
                        </h4>
                        <small>Active Applications</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white hover-card">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-file-lines fa-2x mb-2"></i>
                        <h4 class="mb-1">
                            <?php
                            $my_applications = $conn->query("SELECT COUNT(*) as count FROM applications WHERE std_id = $student_id")->fetch_assoc()['count'];
                            echo $my_applications;
                            ?>
                        </h4>
                        <small>My Applications</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white hover-card">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-clock fa-2x mb-2"></i>
                        <h4 class="mb-1">
                            <?php
                            $urgent_deadlines = $conn->query("SELECT COUNT(*) as count FROM job WHERE last_date_apply BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)")->fetch_assoc()['count'];
                            echo $urgent_deadlines;
                            ?>
                        </h4>
                        <small>Urgent Deadlines</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Search -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Search Jobs</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-search"></i></span>
                            <input type="text" class="form-control" id="searchJobs" placeholder="Search by job title or company name...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Filter by Deadline</label>
                        <select class="form-select" id="deadlineFilter">
                            <option value="">All Deadlines</option>
                            <option value="urgent" <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'urgent') ? 'selected' : ''; ?>>Urgent (Within 7 days)</option>
                            <option value="soon">Soon (Within 30 days)</option>
                            <option value="later">Later (More than 30 days)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sort by</label>
                        <select class="form-select" id="sortBy">
                            <option value="latest">Latest Posted</option>
                            <option value="deadline">Deadline (Urgent First)</option>
                            <option value="company">Company Name</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job Listings -->
        <div class="row" id="jobListings">
            <?php
            $jobs_sql = "SELECT * FROM job WHERE last_date_apply >= CURDATE() ORDER BY campus_date ASC";
            $jobs_result = $conn->query($jobs_sql);

            if ($jobs_result && $jobs_result->num_rows > 0):
                while ($job = $jobs_result->fetch_assoc()):
                    // Calculate days remaining
                    $deadline = new DateTime($job['last_date_apply']);
                    $today = new DateTime();
                    $days_remaining = $today->diff($deadline)->days;
                    
                    // Set deadline class
                    $deadline_class = 'deadline-safe';
                    if ($days_remaining <= 3) {
                        $deadline_class = 'deadline-urgent';
                    } elseif ($days_remaining <= 7) {
                        $deadline_class = 'deadline-warning';
                    }

                    // Check if student has already applied
                    $application_check = $conn->query("SELECT id FROM applications WHERE std_id = $student_id AND job_id = " . $job['id']);
                    $already_applied = $application_check && $application_check->num_rows > 0;

                    // Get company initials for logo
                    $company_initials = '';
                    $words = explode(' ', $job['company_name']);
                    foreach ($words as $word) {
                        if (!empty($word)) {
                            $company_initials .= strtoupper($word[0]);
                        }
                    }
                    $company_initials = substr($company_initials, 0, 2);
            ?>
                <div class="col-lg-6 mb-4 job-item" data-company="<?php echo strtolower($job['company_name']); ?>" data-title="<?php echo strtolower($job['job_title']); ?>" data-days="<?php echo $days_remaining; ?>">
                    <div class="card job-card hover-card <?php echo $deadline_class; ?>">
                        <div class="card-body">
                            <div class="d-flex align-items-start mb-3">
                                <div class="company-logo me-3">
                                    <?php echo $company_initials; ?>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1 fw-bold"><?php echo htmlspecialchars($job['job_title']); ?></h5>
                                    <p class="text-muted mb-2">
                                        <i class="fa-solid fa-building me-1"></i>
                                        <?php echo htmlspecialchars($job['company_name']); ?>
                                    </p>
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        <span class="badge bg-light text-dark">
                                            <i class="fa-solid fa-calendar me-1"></i>
                                            Campus: <?php echo date('d M Y', strtotime($job['campus_date'])); ?>
                                        </span>
                                        <span class="badge bg-<?php echo $days_remaining <= 3 ? 'danger' : ($days_remaining <= 7 ? 'warning' : 'success'); ?>">
                                            <i class="fa-solid fa-clock me-1"></i>
                                            <?php echo $days_remaining; ?> days left
                                        </span>
                                        <?php if ($already_applied): ?>
                                            <span class="badge bg-info">
                                                <i class="fa-solid fa-check me-1"></i>Applied
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <p class="text-muted mb-0">
                                    <?php echo strlen($job['job_description']) > 150 ? substr(htmlspecialchars($job['job_description']), 0, 150) . '...' : htmlspecialchars($job['job_description']); ?>
                                </p>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    <i class="fa-solid fa-stopwatch me-1"></i>
                                    Apply by: <?php echo date('d M Y', strtotime($job['last_date_apply'])); ?>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#jobModal<?php echo $job['id']; ?>">
                                        <i class="fa-solid fa-eye me-1"></i>View Details
                                    </button>
                                    <?php if (!$already_applied && $days_remaining > 0): ?>
                                        <button class="btn btn-sm btn-primary" onclick="applyForJob(<?php echo $job['id']; ?>)">
                                            <i class="fa-solid fa-paper-plane me-1"></i>Apply Now
                                        </button>
                                    <?php elseif ($already_applied): ?>
                                        <button class="btn btn-sm btn-success" disabled>
                                            <i class="fa-solid fa-check me-1"></i>Applied
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-secondary" disabled>
                                            <i class="fa-solid fa-times me-1"></i>Deadline Passed
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job Details Modal -->
                <div class="modal fade" id="jobModal<?php echo $job['id']; ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">
                                    <i class="fa-solid fa-briefcase me-2"></i>
                                    <?php echo htmlspecialchars($job['job_title']); ?>
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="company-logo me-3">
                                                <?php echo $company_initials; ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-1"><?php echo htmlspecialchars($job['company_name']); ?></h6>
                                                <?php if (!empty($job['company_url'])): ?>
                                                    <a href="<?php echo htmlspecialchars($job['company_url']); ?>" target="_blank" class="text-decoration-none">
                                                        <small><i class="fa-solid fa-external-link me-1"></i>Visit Website</small>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <small class="text-muted">Campus Date</small>
                                                <div class="fw-semibold"><?php echo date('d F Y', strtotime($job['campus_date'])); ?></div>
                                            </div>
                                            <div class="col-12">
                                                <small class="text-muted">Application Deadline</small>
                                                <div class="fw-semibold text-<?php echo $days_remaining <= 3 ? 'danger' : ($days_remaining <= 7 ? 'warning' : 'success'); ?>">
                                                    <?php echo date('d F Y', strtotime($job['last_date_apply'])); ?>
                                                    <small>(<?php echo $days_remaining; ?> days left)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="fw-bold mb-3">Job Description</h6>
                                    <div class="p-3 bg-light rounded">
                                        <?php echo nl2br(htmlspecialchars($job['job_description'])); ?>
                                    </div>
                                </div>

                                <?php if ($already_applied): ?>
                                    <div class="alert alert-info">
                                        <i class="fa-solid fa-info-circle me-2"></i>
                                        You have already applied for this position.
                                    </div>
                                <?php elseif ($days_remaining <= 0): ?>
                                    <div class="alert alert-danger">
                                        <i class="fa-solid fa-exclamation-triangle me-2"></i>
                                        The application deadline has passed.
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <?php if (!$already_applied && $days_remaining > 0): ?>
                                    <button type="button" class="btn btn-primary" onclick="applyForJob(<?php echo $job['id']; ?>)">
                                        <i class="fa-solid fa-paper-plane me-1"></i>Apply for this Job
                                    </button>
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
                        <div class="card-body text-center py-5">
                            <i class="fa-solid fa-briefcase fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Active Job Openings</h5>
                            <p class="text-muted">There are currently no active job opportunities available. Please check back later!</p>
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
document.getElementById('searchJobs').addEventListener('input', filterJobs);
document.getElementById('deadlineFilter').addEventListener('change', filterJobs);
document.getElementById('sortBy').addEventListener('change', sortJobs);

// Auto-filter on page load if filter parameter is present
document.addEventListener('DOMContentLoaded', function() {
    filterJobs();
});

function filterJobs() {
    const searchTerm = document.getElementById('searchJobs').value.toLowerCase();
    const deadlineFilter = document.getElementById('deadlineFilter').value;
    const jobItems = document.querySelectorAll('.job-item');

    jobItems.forEach(item => {
        const company = item.dataset.company;
        const title = item.dataset.title;
        const days = parseInt(item.dataset.days);
        
        let showJob = true;

        // Search filter
        if (searchTerm && !company.includes(searchTerm) && !title.includes(searchTerm)) {
            showJob = false;
        }

        // Deadline filter
        if (deadlineFilter) {
            switch(deadlineFilter) {
                case 'urgent':
                    if (days > 7) showJob = false;
                    break;
                case 'soon':
                    if (days > 30) showJob = false;
                    break;
                case 'later':
                    if (days <= 30) showJob = false;
                    break;
            }
        }

        item.style.display = showJob ? 'block' : 'none';
    });
}

function sortJobs() {
    const sortBy = document.getElementById('sortBy').value;
    const container = document.getElementById('jobListings');
    const items = Array.from(container.children);

    items.sort((a, b) => {
        switch(sortBy) {
            case 'deadline':
                return parseInt(a.dataset.days) - parseInt(b.dataset.days);
            case 'company':
                return a.dataset.company.localeCompare(b.dataset.company);
            case 'latest':
            default:
                return 0; // Keep original order
        }
    });

    items.forEach(item => container.appendChild(item));
}

function applyForJob(jobId) {
    if (confirm('Are you sure you want to apply for this job?')) {
        // Create a form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'apply_job.php';
        
        const jobInput = document.createElement('input');
        jobInput.type = 'hidden';
        jobInput.name = 'job_id';
        jobInput.value = jobId;
        
        form.appendChild(jobInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
</body>
</html>
