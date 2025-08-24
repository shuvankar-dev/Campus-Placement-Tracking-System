<?php
session_start();
if (!isset($_SESSION['tpo_id'])) {
    header("Location: index.php");
    exit();
}

include('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];
    
    // First get student details for the edit form
    $sql = "SELECT * FROM student WHERE std_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        $_SESSION['error'] = "Student not found.";
        header("Location: students.php");
        exit();
    }
    
    $student = $result->fetch_assoc();
    $stmt->close();
} else {
    header("Location: students.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student - TPO Dashboard</title>
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

<!-- Main Content -->
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa-solid fa-user-edit me-2"></i>Edit Student</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="student_update.php" method="POST">
                        <input type="hidden" name="std_id" value="<?php echo $student['std_id']; ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="sname" class="form-label">Student Name</label>
                                <input type="text" class="form-control" id="sname" name="sname" value="<?php echo htmlspecialchars($student['sname']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="semail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="semail" name="semail" value="<?php echo htmlspecialchars($student['semail']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="dept_id" class="form-label">Department</label>
                                <select class="form-control" id="dept_id" name="dept_id" required>
                                    <option value="">Select Department</option>
                                    <?php
                                    $dept_sql = "SELECT d_id, department_name FROM department ORDER BY department_name";
                                    $dept_result = $conn->query($dept_sql);
                                    while ($dept = $dept_result->fetch_assoc()) {
                                        $selected = ($dept['d_id'] == $student['dept_id']) ? 'selected' : '';
                                        echo "<option value='{$dept['d_id']}' $selected>{$dept['department_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sphone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="sphone" name="sphone" value="<?php echo htmlspecialchars($student['sphone']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="sdob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="sdob" name="sdob" value="<?php echo $student['sdob']; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sgender" class="form-label">Gender</label>
                                <select class="form-control" id="sgender" name="sgender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" <?php echo ($student['sgender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo ($student['sgender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                    <option value="Other" <?php echo ($student['sgender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="scgpa" class="form-label">CGPA</label>
                                <input type="number" step="0.01" min="0" max="10" class="form-control" id="scgpa" name="scgpa" value="<?php echo $student['scgpa']; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="new_password" class="form-label">New Password (leave blank to keep current)</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="saddress" class="form-label">Address</label>
                            <textarea class="form-control" id="saddress" name="saddress" rows="3" required><?php echo htmlspecialchars($student['saddress']); ?></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="students.php" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left me-2"></i>Back to Students
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-save me-2"></i>Update Student
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
