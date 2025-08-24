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
    <title>Job Listings - TPO Dashboard</title>
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
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
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
            <li class="nav-item mb-2"><a href="#" class="nav-link text-white">Departments</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link text-white">Students</a></li>
            <li class="nav-item mb-2"><a href="jobs.php" class="nav-link text-white active fw-bold">Job Posts</a></li>
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
            <h3>Job Listings</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addJobModal"><i class="fa-solid fa-plus me-2"></i>Add new job</button>
        </div>

        <!-- Add Job Modal -->
        <div class="modal fade" id="addJobModal" tabindex="-1" aria-labelledby="addJobModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addJobModalLabel">Add New Job</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addJobForm" action="add_job.php" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="job_title" class="form-label">Job Title</label>
                                    <input type="text" class="form-control" id="job_title" name="job_title" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="company_name" class="form-label">Company Name</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="campus_date" class="form-label">Campus Date</label>
                                    <input type="date" class="form-control" id="campus_date" name="campus_date" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_date_apply" class="form-label">Last Date to Apply</label>
                                    <input type="date" class="form-control" id="last_date_apply" name="last_date_apply" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="company_url" class="form-label">Company Website</label>
                                <input type="url" class="form-control" id="company_url" name="company_url" placeholder="https://example.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="job_description" class="form-label">Job Description</label>
                                <textarea class="form-control" id="job_description" name="job_description" rows="4" required placeholder="Enter detailed job description..."></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" form="addJobForm" class="btn btn-primary">Save Job</button>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-hover bg-white shadow-sm">
            <thead class="table-primary text-white">
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 20%;">Job Title</th>
                    <th style="width: 15%;">Company Name</th>
                    <th style="width: 12%;">Campus Date</th>
                    <th style="width: 12%;">Last Date Apply</th>
                    <th style="width: 8%;">Website</th>
                    <th style="width: 8%;">Details</th>
                    <th style="width: 20%;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM job ORDER BY id DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['job_title']) ?></td>
                        <td><?= htmlspecialchars($row['company_name']) ?></td>
                        <td><?= date('d-m-Y', strtotime($row['campus_date'])) ?></td>
                        <td><?= date('d-m-Y', strtotime($row['last_date_apply'])) ?></td>
                        <td class="text-center">
                            <a href="<?= htmlspecialchars($row['company_url']) ?>" target="_blank" class="btn btn-sm btn-outline-primary" title="Visit Company Website">
                                <i class="fa-solid fa-external-link-alt"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#jobDetailsModal<?= $row['id'] ?>" title="View Job Description">
                                <i class="fa-solid fa-info-circle"></i>
                            </button>
                        </td>
                        <td class="text-center"> 
                            <form name="upd-<?php echo $row['id'] ?>" action="job_edit.php" method="post" class="d-inline">
                                <input type="hidden" name="job_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-warning me-1" title="Edit Job">
                                    <i class="fa-solid fa-edit"></i>
                                </button>
                            </form>
                            
                            <form name="del-<?php echo $row['id'] ?>" action="job_delete.php" method="get" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this job?');">
                                <input type="hidden" name="job_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete Job">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Job Details Modal -->
                    <div class="modal fade" id="jobDetailsModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="jobDetailsModalLabel<?= $row['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="jobDetailsModalLabel<?= $row['id'] ?>">
                                        <?= htmlspecialchars($row['job_title']) ?> - <?= htmlspecialchars($row['company_name']) ?>
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Campus Date:</strong> <?= date('d F Y', strtotime($row['campus_date'])) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Last Date to Apply:</strong> <?= date('d F Y', strtotime($row['last_date_apply'])) ?>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Company Website:</strong> 
                                        <a href="<?= htmlspecialchars($row['company_url']) ?>" target="_blank" class="ms-2">
                                            <?= htmlspecialchars($row['company_url']) ?> <i class="fa-solid fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Job Description:</strong>
                                        <div class="mt-2 p-3 bg-light rounded">
                                            <?= nl2br(htmlspecialchars($row['job_description'])) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    endwhile;
                else:
                ?>
                    <tr><td colspan="8" class="text-center">No job posts found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/app.js"></script>
</body>
</html>
