<?php
session_start();
require('../config.php');

if(!isset($_POST['job_id'])) {
    header("Location: jobs.php");
    exit();
}

$job_id = $_POST['job_id']; 
$src = "SELECT * FROM job WHERE id = $job_id";
$rs = mysqli_query($conn, $src) or die(mysqli_error($conn));
$job = mysqli_fetch_assoc($rs);

// Handle form submission for updating
if(isset($_POST['update_job'])) {
    $job_title = $_POST['job_title'];
    $company_name = $_POST['company_name'];
    $campus_date = $_POST['campus_date'];
    $last_date_apply = $_POST['last_date_apply'];
    $job_description = $_POST['job_description'];
    $company_url = $_POST['company_url'];
    $job_id = $_POST['job_id'];
    
    // Validate dates
    if ($last_date_apply >= $campus_date) {
        $_SESSION['message'] = "Last date to apply must be before the campus date.";
        header("Location: jobs.php");
        exit();
    }
    
    $update_sql = "UPDATE job SET 
                  job_title = '$job_title',
                  company_name = '$company_name',
                  campus_date = '$campus_date',
                  last_date_apply = '$last_date_apply',
                  job_description = '$job_description',
                  company_url = '$company_url'
                  WHERE id = $job_id";
                  
    if(mysqli_query($conn, $update_sql)) {
        $_SESSION['message'] = "Job updated successfully!";
        header("Location: jobs.php");
        exit();
    } else {
        $_SESSION['message'] = "Error updating job: " . mysqli_error($conn);
        header("Location: jobs.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job - TPO Dashboard</title>
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
                    <li><h6 class="dropdown-header"><?php echo isset($_SESSION['tpo_email']) ? $_SESSION['tpo_email'] : 'TPO User'; ?></h6></li>
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Edit Job</h3>
                <a href="jobs.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Back to Jobs</a>
            </div>

            <!-- Edit Job Modal (Auto-opens) -->
            <div class="modal fade" id="editJobModal" tabindex="-1" aria-labelledby="editJobModalLabel" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editJobModalLabel">Edit Job</h5>
                            <a href="jobs.php" class="btn-close"></a>
                        </div>
                        <div class="modal-body">
                            <form id="editJobForm" action="job_edit.php" method="POST">
                                <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                                <input type="hidden" name="update_job" value="1">
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="job_title" class="form-label">Job Title</label>
                                        <input type="text" class="form-control" id="job_title" name="job_title" value="<?php echo htmlspecialchars($job['job_title']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="company_name" class="form-label">Company Name</label>
                                        <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo htmlspecialchars($job['company_name']); ?>" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="campus_date" class="form-label">Campus Date</label>
                                        <input type="date" class="form-control" id="campus_date" name="campus_date" value="<?php echo $job['campus_date']; ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="last_date_apply" class="form-label">Last Date to Apply</label>
                                        <input type="date" class="form-control" id="last_date_apply" name="last_date_apply" value="<?php echo $job['last_date_apply']; ?>" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="company_url" class="form-label">Company Website</label>
                                    <input type="url" class="form-control" id="company_url" name="company_url" value="<?php echo htmlspecialchars($job['company_url']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="job_description" class="form-label">Job Description</label>
                                    <textarea class="form-control" id="job_description" name="job_description" rows="4" required><?php echo htmlspecialchars($job['job_description']); ?></textarea>
                                </div>
                                
                                <div class="modal-footer px-0 pb-0">
                                    <a href="jobs.php" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update Job</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-open the modal when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = new bootstrap.Modal(document.getElementById('editJobModal'));
            editModal.show();
        });
    </script>
</body>
</html>