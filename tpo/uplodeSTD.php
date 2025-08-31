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
    <title>Upload Students - TPO Dashboard</title>
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
            <li class="nav-item mb-2"><a href="departments.php" class="nav-link text-white">Departments</a></li>
            <li class="nav-item mb-2"><a href="students.php" class="nav-link text-white active fw-bold">Students</a></li>
            <li class="nav-item mb-2"><a href="jobs.php" class="nav-link text-white">Job Posts</a></li>
            <li class="nav-item mb-2"><a href="applications.php" class="nav-link text-white">Applications</a></li>
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

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Bulk Student Upload</h3>
            <a href="students.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Back to Students</a>
        </div>

        <!-- Upload Form -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fa-solid fa-upload me-2"></i>Upload Students CSV File</h5>
                    </div>
                    <div class="card-body">
                        <form action="tpoaddSTD.php" method="post" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label for="dept_id" class="form-label">
                                    <i class="fa-solid fa-building me-2"></i>Select Department
                                </label>
                                <select class="form-control" id="dept_id" name="dept_id" required>
                                    <option value="">Choose Department</option>
                                    <?php
                                    $dept_sql = "SELECT d_id, department_name FROM department ORDER BY department_name";
                                    $dept_result = $conn->query($dept_sql);
                                    while ($dept = $dept_result->fetch_assoc()) {
                                        echo "<option value='{$dept['d_id']}'>{$dept['department_name']}</option>";
                                    }
                                    ?>
                                </select>
                                <div class="form-text">All students in the CSV file will be assigned to this department.</div>
                            </div>

                            <div class="mb-4">
                                <label for="csv_file" class="form-label">
                                    <i class="fa-solid fa-file-csv me-2"></i>Choose CSV File
                                </label>
                                <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
                                <div class="form-text">Please upload a CSV file with student data.</div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" name="submit" class="btn btn-success btn-lg">
                                    <i class="fa-solid fa-upload me-2"></i>Upload Students
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- CSV Format Instructions -->
                <div class="card mt-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fa-solid fa-info-circle me-2"></i>CSV Format Instructions</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Your CSV file should have the following columns in this exact order:</strong></p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Column 1</th>
                                        <th>Column 2</th>
                                        <th>Column 3</th>
                                        <th>Column 4</th>
                                        <th>Column 5</th>
                                        <th>Column 6</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>sname</td>
                                        <td>semail</td>
                                        <td>sdob</td>
                                        <td>sphone</td>
                                        <td>sgender</td>
                                        <td>scgpa</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>john@college.edu</td>
                                        <td>2000-05-15</td>
                                        <td>1234567890</td>
                                        <td>Male</td>
                                        <td>8.5</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-warning mt-3">
                            <strong>Important Notes:</strong>
                            <ul class="mb-0">
                                <li>Do not include column headers in your CSV file</li>
                                <li>Date format should be YYYY-MM-DD (e.g., 2000-05-15)</li>
                                <li>Gender should be: Male, Female, or Other</li>
                                <li>CGPA should be between 0 and 10</li>
                                <li>Email addresses must be unique</li>
                                <li><strong>Passwords will be auto-generated (8-digit numbers)</strong></li>
                                <li>Students will receive their login credentials via email</li>
                                <li>All fields are required</li>
                            </ul>
                        </div>

                        <div class="mt-3">
                            <a href="#" class="btn btn-outline-info btn-sm" onclick="downloadSample()">
                                <i class="fa-solid fa-download me-2"></i>Download Sample CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function downloadSample() {
        // Create sample CSV content (without password - auto-generated)
        const csvContent = 'John Doe,john.doe@college.edu,2000-05-15,1234567890,Male,8.5\nJane Smith,jane.smith@college.edu,2001-08-22,0987654321,Female,9.2\nMike Johnson,mike.johnson@college.edu,2000-12-10,5551234567,Male,7.8';
        
        // Create and download file
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'sample_students.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }
</script>
</body>
</html>
