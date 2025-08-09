<?php
session_start();
require('../config.php');

if(!isset($_POST['job_id'])) {
    header("Location: jobs.php");
    exit();
}

$job_id = $_POST['job_id']; 
$src = "SELECT * FROM jobs WHERE id = $job_id";
$rs = mysqli_query($conn, $src) or die(mysqli_error($conn));
$job = mysqli_fetch_assoc($rs);

// Handle form submission for updating
if(isset($_POST['update_job'])) {
    $company_name = $_POST['company_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $company_link = $_POST['company_link'];
    $job_id = $_POST['job_id'];
    
    $update_sql = "UPDATE jobs SET 
                  company_name = '$company_name',
                  start_date = '$start_date',
                  end_date = '$end_date',
                  company_link = '$company_link'
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
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editJobModalLabel">Edit Job</h5>
                            <a href="jobs.php" class="btn-close"></a>
                        </div>
                        <div class="modal-body">
                            <form id="editJobForm" action="job_edit.php" method="POST">
                                <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                                <input type="hidden" name="update_job" value="1">
                                
                                <div class="mb-3">
                                    <label for="company_name" class="form-label">Company Name</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo htmlspecialchars($job['company_name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $job['start_date']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $job['end_date']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="company_link" class="form-label">Company Link</label>
                                    <input type="url" class="form-control" id="company_link" name="company_link" value="<?php echo $job['company_link']; ?>" required>
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