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
            <a href="tpo_dashboard.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Back to Dashboard</a>
        </div>

        <!-- Department Cards -->
        <div class="row">
            <?php
            // Array of departments with colors
            $departments = [
                ['name' => 'Computer Science and Engineering', 'color' => 'primary', 'icon' => 'fa-solid fa-laptop-code'],
                ['name' => 'Electrical Engineering', 'color' => 'info', 'icon' => 'fa-solid fa-database'],
                ['name' => 'Electronics and Communication', 'color' => 'secondary', 'icon' => 'fa-solid fa-microchip'],
                ['name' => 'Mechanical Engineering', 'color' => 'warning', 'icon' => 'fa-solid fa-cogs'],
                ['name' => 'Civil Engineering', 'color' => 'success', 'icon' => 'fa-solid fa-building']
            ];
            
            foreach ($departments as $dept):
            ?>
            <div class="col-md-6 mb-4">
                <div class="card dept-card shadow-sm">
                    <div class="dept-header bg-<?php echo $dept['color']; ?>">
                        <div class="d-flex align-items-center">
                            <div class="header-icon">
                                <i class="<?php echo $dept['icon']; ?> fa-lg"></i>
                            </div>
                            <h4 class="mb-0"><?php echo $dept['name']; ?></h4>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card upload-card p-3 shadow-sm">
                                    <h5 class="mb-3"><i class="fa-solid fa-upload me-2"></i>Upload Student List</h5>
                                    <form id="uploadForm-<?php echo strtolower(str_replace(' ', '-', $dept['name'])); ?>">
                                        <div class="mb-3">
                                            <label for="formFile-<?php echo strtolower(str_replace(' ', '-', $dept['name'])); ?>" class="form-label">Select File</label>
                                            <input class="form-control" type="file" id="formFile-<?php echo strtolower(str_replace(' ', '-', $dept['name'])); ?>">
                                            <div class="form-text">Upload CSV or Excel file</div>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="check-<?php echo strtolower(str_replace(' ', '-', $dept['name'])); ?>">
                                            <label class="form-check-label" for="check-<?php echo strtolower(str_replace(' ', '-', $dept['name'])); ?>">
                                                Confirm Upload
                                            </label>
                                        </div>
                                        <button class="btn btn-<?php echo $dept['color']; ?> w-100" id="uploadBtn-<?php echo strtolower(str_replace(' ', '-', $dept['name'])); ?>" disabled>
                                            <i class="fa-solid fa-cloud-arrow-up me-2"></i>Upload
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Enable/disable upload buttons based on checkbox status
    document.addEventListener('DOMContentLoaded', function() {
        <?php foreach ($departments as $dept): ?>
        const deptId = "<?php echo strtolower(str_replace(' ', '-', $dept['name'])); ?>";
        const checkbox = document.getElementById(`check-${deptId}`);
        const uploadBtn = document.getElementById(`uploadBtn-${deptId}`);
        
        checkbox.addEventListener('change', function() {
            uploadBtn.disabled = !this.checked;
        });
        <?php endforeach; ?>
    });
</script>
</body>
</html>
