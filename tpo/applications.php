<?php
session_start();
if (!isset($_SESSION['tpo_id'])) {
    header("Location: index.php");
    exit();
}
include('../config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application Management - TPO Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-light">

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4">
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
                <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Wrapper -->
<div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="bg-primary text-white p-3" style="width: 250px; min-height: 100vh;">
        <h4 class="mb-4">TPO Panel</h4>
        <ul class="nav flex-column">
            <li class="nav-item mb-2"><a href="tpo_dashboard.php" class="nav-link text-white">Dashboard</a></li>
            <li class="nav-item mb-2"><a href="departments.php" class="nav-link text-white">Departments</a></li>
            <li class="nav-item mb-2"><a href="students.php" class="nav-link text-white">Students</a></li>
            <li class="nav-item mb-2"><a href="jobs.php" class="nav-link text-white">Job Posts</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link text-white active fw-bold">Applications</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link text-white"><i class="fa-solid fa-gear me-2"></i>Settings</a></li>
        </ul>
    </div>

    <!-- Page Content -->
    <div class="container-fluid p-4">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Application Management</h3>
            <a href="tpo_dashboard.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Back to Dashboard</a>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Total Applications</h6>
                                <h3 class="mb-0">
                                    <?php
                                    $total_apps_sql = "SELECT COUNT(*) as total FROM applications";
                                    $total_apps_result = $conn->query($total_apps_sql);
                                    $total_apps = $total_apps_result ? $total_apps_result->fetch_assoc()['total'] : 0;
                                    echo $total_apps;
                                    ?>
                                </h3>
                            </div>
                            <div><i class="fa-solid fa-file-lines fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Approved</h6>
                                <h3 class="mb-0">
                                    <?php
                                    $approved_sql = "SELECT COUNT(*) as approved FROM applications WHERE status = 'Approved'";
                                    $approved_result = $conn->query($approved_sql);
                                    $approved = $approved_result ? $approved_result->fetch_assoc()['approved'] : 0;
                                    echo $approved;
                                    ?>
                                </h3>
                            </div>
                            <div><i class="fa-solid fa-check-circle fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Pending</h6>
                                <h3 class="mb-0">
                                    <?php
                                    $pending_sql = "SELECT COUNT(*) as pending FROM applications WHERE status = 'Pending'";
                                    $pending_result = $conn->query($pending_sql);
                                    $pending = $pending_result ? $pending_result->fetch_assoc()['pending'] : 0;
                                    echo $pending;
                                    ?>
                                </h3>
                            </div>
                            <div><i class="fa-solid fa-clock fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Rejected</h6>
                                <h3 class="mb-0">
                                    <?php
                                    $rejected_sql = "SELECT COUNT(*) as rejected FROM applications WHERE status = 'Rejected'";
                                    $rejected_result = $conn->query($rejected_sql);
                                    $rejected = $rejected_result ? $rejected_result->fetch_assoc()['rejected'] : 0;
                                    echo $rejected;
                                    ?>
                                </h3>
                            </div>
                            <div><i class="fa-solid fa-times-circle fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-4" id="applicationTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-applications-tab" data-bs-toggle="tab" data-bs-target="#all-applications" type="button" role="tab">
                    <i class="fa-solid fa-list me-2"></i>All Applications
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="companies-tab" data-bs-toggle="tab" data-bs-target="#companies" type="button" role="tab">
                    <i class="fa-solid fa-building me-2"></i>All Companies
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab">
                    <i class="fa-solid fa-calendar-days me-2"></i>Upcoming Details
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="applicationTabsContent">
            
            <!-- All Applications Tab -->
            <div class="tab-pane fade show active" id="all-applications" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fa-solid fa-file-lines me-2"></i>All Job Applications</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Student Name</th>
                                        <th>Company</th>
                                        <th>Job Title</th>
                                        <th>Applied Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $applications_sql = "SELECT a.*, s.sname, s.semail, j.job_title, j.company_name 
                                                        FROM applications a 
                                                        LEFT JOIN student s ON a.std_id = s.std_id 
                                                        LEFT JOIN job j ON a.job_id = j.id 
                                                        ORDER BY a.applied_date DESC";
                                    $applications_result = $conn->query($applications_sql);

                                    if ($applications_result && $applications_result->num_rows > 0):
                                        while ($app = $applications_result->fetch_assoc()):
                                    ?>
                                        <tr>
                                            <td><?php echo $app['id']; ?></td>
                                            <td><?php echo htmlspecialchars($app['sname'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($app['company_name'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($app['job_title'] ?? 'N/A'); ?></td>
                                            <td><?php echo date('d M Y', strtotime($app['applied_date'])); ?></td>
                                            <td>
                                                <?php
                                                $status = $app['status'];
                                                $badge_class = '';
                                                switch($status) {
                                                    case 'Approved': $badge_class = 'bg-success'; break;
                                                    case 'Rejected': $badge_class = 'bg-danger'; break;
                                                    case 'Pending': $badge_class = 'bg-warning'; break;
                                                    default: $badge_class = 'bg-secondary';
                                                }
                                                ?>
                                                <span class="badge <?php echo $badge_class; ?>"><?php echo $status; ?></span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info me-1" data-bs-toggle="modal" data-bs-target="#viewAppModal<?php echo $app['id']; ?>" title="View Details">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-success" onclick="updateStatus(<?php echo $app['id']; ?>, 'Approved')" title="Approve">
                                                        <i class="fa-solid fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="updateStatus(<?php echo $app['id']; ?>, 'Rejected')" title="Reject">
                                                        <i class="fa-solid fa-times"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- View Application Modal -->
                                        <div class="modal fade" id="viewAppModal<?php echo $app['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Application Details</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Application ID:</strong> <?php echo $app['id']; ?>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Student Name:</strong> <?php echo htmlspecialchars($app['sname'] ?? 'N/A'); ?>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Student Email:</strong> <?php echo htmlspecialchars($app['semail'] ?? 'N/A'); ?>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Company:</strong> <?php echo htmlspecialchars($app['company_name'] ?? 'N/A'); ?>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Job Title:</strong> <?php echo htmlspecialchars($app['job_title'] ?? 'N/A'); ?>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Applied Date:</strong> <?php echo date('d F Y', strtotime($app['applied_date'])); ?>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Status:</strong> 
                                                                <span class="badge <?php echo $badge_class; ?>"><?php echo $status; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                        endwhile;
                                    else:
                                    ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No applications found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- All Companies Tab -->
            <div class="tab-pane fade" id="companies" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fa-solid fa-building me-2"></i>All Companies</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            $companies_sql = "SELECT DISTINCT j.company_name, j.company_url, 
                                             COUNT(a.id) as total_applications,
                                             SUM(CASE WHEN a.status = 'Approved' THEN 1 ELSE 0 END) as approved_count
                                             FROM job j 
                                             LEFT JOIN applications a ON j.id = a.job_id 
                                             GROUP BY j.company_name, j.company_url 
                                             ORDER BY j.company_name";
                            $companies_result = $conn->query($companies_sql);

                            if ($companies_result && $companies_result->num_rows > 0):
                                while ($company = $companies_result->fetch_assoc()):
                            ?>
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($company['company_name']); ?></h5>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    Total Applications: <strong><?php echo $company['total_applications']; ?></strong><br>
                                                    Approved: <strong><?php echo $company['approved_count']; ?></strong>
                                                </small>
                                            </p>
                                            <?php if (!empty($company['company_url'])): ?>
                                                <a href="<?php echo htmlspecialchars($company['company_url']); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fa-solid fa-external-link-alt me-1"></i>Visit Website
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                endwhile;
                            else:
                            ?>
                                <div class="col-12">
                                    <div class="text-center text-muted">No companies found.</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Details Tab -->
            <div class="tab-pane fade" id="upcoming" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fa-solid fa-calendar-days me-2"></i>Upcoming Campus Placements</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-warning">
                                    <tr>
                                        <th>Company</th>
                                        <th>Job Title</th>
                                        <th>Campus Date</th>
                                        <th>Last Date to Apply</th>
                                        <th>Days Remaining</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $upcoming_sql = "SELECT * FROM job 
                                                    WHERE campus_date >= CURDATE() 
                                                    ORDER BY campus_date ASC";
                                    $upcoming_result = $conn->query($upcoming_sql);

                                    if ($upcoming_result && $upcoming_result->num_rows > 0):
                                        while ($upcoming = $upcoming_result->fetch_assoc()):
                                            $campus_date = new DateTime($upcoming['campus_date']);
                                            $today = new DateTime();
                                            $days_remaining = $today->diff($campus_date)->days;
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($upcoming['company_name']); ?></td>
                                            <td><?php echo htmlspecialchars($upcoming['job_title']); ?></td>
                                            <td><?php echo date('d M Y', strtotime($upcoming['campus_date'])); ?></td>
                                            <td><?php echo date('d M Y', strtotime($upcoming['last_date_apply'])); ?></td>
                                            <td>
                                                <?php if ($days_remaining == 0): ?>
                                                    <span class="badge bg-danger">Today</span>
                                                <?php elseif ($days_remaining <= 7): ?>
                                                    <span class="badge bg-warning"><?php echo $days_remaining; ?> days</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success"><?php echo $days_remaining; ?> days</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#upcomingModal<?php echo $upcoming['id']; ?>">
                                                    <i class="fa-solid fa-eye"></i> Details
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Upcoming Details Modal -->
                                        <div class="modal fade" id="upcomingModal<?php echo $upcoming['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"><?php echo htmlspecialchars($upcoming['company_name']); ?> - <?php echo htmlspecialchars($upcoming['job_title']); ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Company:</strong> <?php echo htmlspecialchars($upcoming['company_name']); ?>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Job Title:</strong> <?php echo htmlspecialchars($upcoming['job_title']); ?>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Campus Date:</strong> <?php echo date('d F Y', strtotime($upcoming['campus_date'])); ?>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Last Date to Apply:</strong> <?php echo date('d F Y', strtotime($upcoming['last_date_apply'])); ?>
                                                            </div>
                                                            <div class="col-12 mb-3">
                                                                <strong>Job Description:</strong>
                                                                <div class="mt-2 p-3 bg-light rounded">
                                                                    <?php echo nl2br(htmlspecialchars($upcoming['job_description'])); ?>
                                                                </div>
                                                            </div>
                                                            <?php if (!empty($upcoming['company_url'])): ?>
                                                            <div class="col-12">
                                                                <a href="<?php echo htmlspecialchars($upcoming['company_url']); ?>" target="_blank" class="btn btn-primary">
                                                                    <i class="fa-solid fa-external-link-alt me-2"></i>Visit Company Website
                                                                </a>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                        endwhile;
                                    else:
                                    ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No upcoming campus placements found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden forms for status updates -->
        <form id="statusUpdateForm" action="update_application_status.php" method="POST" style="display: none;">
            <input type="hidden" id="updateAppId" name="id">
            <input type="hidden" id="updateStatus" name="status">
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Function to update application status
    function updateStatus(appId, status) {
        const actionText = status === 'Approved' ? 'approve' : 'reject';
        if (confirm(`Are you sure you want to ${actionText} this application?`)) {
            document.getElementById('updateAppId').value = appId;
            document.getElementById('updateStatus').value = status;
            document.getElementById('statusUpdateForm').submit();
        }
    }
</script>
</body>
</html>
