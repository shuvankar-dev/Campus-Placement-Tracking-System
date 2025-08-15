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
    <title>Department Details - TPO Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .dept-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
        }
        .dept-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .dept-header {
            padding: 20px;
            color: white;
            border-radius: 10px 10px 0 0;
        }
        .upload-card {
            border-radius: 15px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        .upload-card:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-color: #adb5bd;
        }
        .header-icon {
            background-color: rgba(255,255,255,0.2);
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 15px;
        }
    </style>
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
            <li class="nav-item mb-2"><a href="#" class="nav-link text-white active fw-bold">Departments</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link text-white">Students</a></li>
            <li class="nav-item mb-2"><a href="jobs.php" class="nav-link text-white">Job Posts</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link text-white">Applications</a></li>
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
            <h3>Department Details</h3>
            <div>
                <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                    <i class="fa-solid fa-plus me-2"></i>Add New Department
                </button>
                <a href="tpo_dashboard.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Back to Dashboard</a>
            </div>
        </div>

        <!-- Add Department Modal -->
        <div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDepartmentModalLabel">Add New Department</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="add_department.php" method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="department_name" class="form-label">Department Name</label>
                                <input type="text" class="form-control" id="department_name" name="department_name" required placeholder="Enter department name">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fa-solid fa-plus me-2"></i>Add Department
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Departments Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa-solid fa-building me-2"></i>Departments List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Department Name</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch departments from database
                            $sql = "SELECT d_id, department_name FROM department ORDER BY department_name";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0):
                                while ($row = $result->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?php echo $row['d_id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['department_name']); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-success me-1" data-bs-toggle="modal" data-bs-target="#uploadModal<?php echo $row['d_id']; ?>">
                                            <i class="fa-solid fa-upload me-1"></i>Upload Students
                                        </button>
                                        <button class="btn btn-sm btn-info me-1">
                                            <i class="fa-solid fa-eye me-1"></i>View Details
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteDepartment(<?php echo $row['d_id']; ?>, '<?php echo htmlspecialchars($row['department_name'], ENT_QUOTES); ?>')">
                                            <i class="fa-solid fa-trash me-1"></i>Delete
                                        </button>
                                    </td>
                                </tr>

                                <!-- Upload Modal for each department -->
                                <div class="modal fade" id="uploadModal<?php echo $row['d_id']; ?>" tabindex="-1" aria-labelledby="uploadModalLabel<?php echo $row['d_id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="uploadModalLabel<?php echo $row['d_id']; ?>">
                                                    Upload Students - <?php echo htmlspecialchars($row['department_name']); ?>
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="uploadForm-<?php echo $row['d_id']; ?>">
                                                    <div class="mb-3">
                                                        <label for="formFile-<?php echo $row['d_id']; ?>" class="form-label">Select File</label>
                                                        <input class="form-control" type="file" id="formFile-<?php echo $row['d_id']; ?>" accept=".csv,.xlsx,.xls">
                                                        <div class="form-text">Upload CSV or Excel file containing student data</div>
                                                    </div>
                                                    <div class="form-check mb-3">
                                                        <input class="form-check-input" type="checkbox" id="check-<?php echo $row['d_id']; ?>">
                                                        <label class="form-check-label" for="check-<?php echo $row['d_id']; ?>">
                                                            I confirm that the data is correct
                                                        </label>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="button" class="btn btn-success" id="uploadBtn-<?php echo $row['d_id']; ?>" disabled>
                                                    <i class="fa-solid fa-cloud-arrow-up me-1"></i>Upload
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No departments found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Hidden form for department deletion -->
        <form id="deleteForm" action="delete_department.php" method="POST" style="display: none;">
            <input type="hidden" id="deleteDepartmentId" name="department_id">
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Enable/disable upload buttons based on checkbox status
    document.addEventListener('DOMContentLoaded', function() {
        // Get all checkboxes with IDs starting with "check-"
        const checkboxes = document.querySelectorAll('[id^="check-"]');
        
        checkboxes.forEach(function(checkbox) {
            const deptId = checkbox.id.replace('check-', '');
            const uploadBtn = document.getElementById(`uploadBtn-${deptId}`);
            
            if (uploadBtn) {
                checkbox.addEventListener('change', function() {
                    uploadBtn.disabled = !this.checked;
                });
            }
        });
    });

    // Function to delete department with confirmation
    function deleteDepartment(departmentId, departmentName) {
        if (confirm(`Are you sure you want to delete the department "${departmentName}"? This action cannot be undone.`)) {
            document.getElementById('deleteDepartmentId').value = departmentId;
            document.getElementById('deleteForm').submit();
        }
    }
</script>
</body>
</html>
