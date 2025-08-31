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
    <!-- Mobile hamburger menu button -->
    <button class="btn btn-outline-primary d-md-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
        <i class="fa-solid fa-bars"></i>
    </button>
    
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
                <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Wrapper -->
<div class="d-flex" id="wrapper">

    <!-- Desktop Sidebar -->
    <div class="p-3 d-none d-md-block" style="width: 250px; min-height: 100vh; background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
        <div class="text-center mb-4">
            <div class="bg-white text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                <i class="fa-solid fa-user-tie fa-2x"></i>
            </div>
            <h5 class="text-white mb-0">TPO Panel</h5>
            <small class="text-white-50">Training & Placement</small>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a href="tpo_dashboard.php" class="nav-link text-white">
                    <i class="fa-solid fa-tachometer-alt me-3"></i>Dashboard
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="departments.php" class="nav-link text-white active fw-bold">
                    <i class="fa-solid fa-building me-3"></i>Departments
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="students.php" class="nav-link text-white">
                    <i class="fa-solid fa-graduation-cap me-3"></i>Students
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="jobs.php" class="nav-link text-white">
                    <i class="fa-solid fa-briefcase me-3"></i>Job Posts
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="applications.php" class="nav-link text-white">
                    <i class="fa-solid fa-file-text me-3"></i>Applications
                </a>
            </li>
        </ul>
    </div>

    <!-- Mobile Sidebar (Offcanvas) -->
    <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
        <div class="offcanvas-header">
            <div class="text-center w-100">
                <div class="bg-white text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                    <i class="fa-solid fa-user-tie fa-lg"></i>
                </div>
                <h6 class="text-white mb-0">TPO Panel</h6>
                <small class="text-white-50">Training & Placement</small>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="tpo_dashboard.php" class="nav-link text-white">
                        <i class="fa-solid fa-tachometer-alt me-3"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="departments.php" class="nav-link text-white active fw-bold">
                        <i class="fa-solid fa-building me-3"></i>Departments
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="students.php" class="nav-link text-white">
                        <i class="fa-solid fa-graduation-cap me-3"></i>Students
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="jobs.php" class="nav-link text-white">
                        <i class="fa-solid fa-briefcase me-3"></i>Job Posts
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="applications.php" class="nav-link text-white">
                        <i class="fa-solid fa-file-text me-3"></i>Applications
                    </a>
                </li>
            </ul>
        </div>
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
                                        <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editDepartmentModal<?php echo $row['d_id']; ?>">
                                            <i class="fa-solid fa-edit me-1"></i>Update
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteDepartment(<?php echo $row['d_id']; ?>, '<?php echo htmlspecialchars($row['department_name'], ENT_QUOTES); ?>')">
                                            <i class="fa-solid fa-trash me-1"></i>Delete
                                        </button>
                                    </td>
                                </tr>

                                <!-- Edit Department Modal for each department -->
                                <div class="modal fade" id="editDepartmentModal<?php echo $row['d_id']; ?>" tabindex="-1" aria-labelledby="editDepartmentModalLabel<?php echo $row['d_id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editDepartmentModalLabel<?php echo $row['d_id']; ?>">
                                                    Edit Department
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="update_department.php" method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="department_id" value="<?php echo $row['d_id']; ?>">
                                                    <div class="mb-3">
                                                        <label for="edit_department_name_<?php echo $row['d_id']; ?>" class="form-label">Department Name</label>
                                                        <input type="text" class="form-control" id="edit_department_name_<?php echo $row['d_id']; ?>" name="department_name" value="<?php echo htmlspecialchars($row['department_name']); ?>" required placeholder="Enter department name">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-warning">
                                                        <i class="fa-solid fa-save me-2"></i>Update Department
                                                    </button>
                                                </div>
                                            </form>
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
