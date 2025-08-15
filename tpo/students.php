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
    <title>Student Management - TPO Dashboard</title>
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
            <li class="nav-item mb-2"><a href="#" class="nav-link text-white active fw-bold">Students</a></li>
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
            <h3>Student Management</h3>
            <div>
                <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                    <i class="fa-solid fa-user-plus me-2"></i>Add New Student
                </button>
                <a href="tpo_dashboard.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Back to Dashboard</a>
            </div>
        </div>

        <!-- Add Student Modal -->
        <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStudentModalLabel">Add New Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="add_student.php" method="POST">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="sname" class="form-label">Student Name</label>
                                    <input type="text" class="form-control" id="sname" name="sname" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="semail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="semail" name="semail" required>
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
                                            echo "<option value='{$dept['d_id']}'>{$dept['department_name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sphone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="sphone" name="sphone" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="sdob" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="sdob" name="sdob" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sgender" class="form-label">Gender</label>
                                    <select class="form-control" id="sgender" name="sgender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="scgpa" class="form-label">CGPA</label>
                                    <input type="number" step="0.01" min="0" max="10" class="form-control" id="scgpa" name="scgpa" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="spassword" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="spassword" name="spassword" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="saddress" class="form-label">Address</label>
                                <textarea class="form-control" id="saddress" name="saddress" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fa-solid fa-user-plus me-2"></i>Add Student
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Students Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa-solid fa-users me-2"></i>Students List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Phone</th>
                                <th>CGPA</th>
                                <th>Gender</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch students with department information
                            $sql = "SELECT s.*, d.department_name 
                                   FROM student s 
                                   LEFT JOIN department d ON s.dept_id = d.d_id 
                                   ORDER BY s.sname";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0):
                                while ($row = $result->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?php echo $row['std_id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['sname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['semail']); ?></td>
                                    <td><?php echo htmlspecialchars($row['department_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($row['sphone']); ?></td>
                                    <td><span class="badge bg-info"><?php echo $row['scgpa']; ?></span></td>
                                    <td><?php echo htmlspecialchars($row['sgender']); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-info me-1" data-bs-toggle="modal" data-bs-target="#viewStudentModal<?php echo $row['std_id']; ?>" title="View Details">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning me-1" onclick="editStudent(<?php echo $row['std_id']; ?>)" title="Edit Student">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteStudent(<?php echo $row['std_id']; ?>, '<?php echo htmlspecialchars($row['sname'], ENT_QUOTES); ?>')" title="Delete Student">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- View Student Details Modal -->
                                <div class="modal fade" id="viewStudentModal<?php echo $row['std_id']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Student Details - <?php echo htmlspecialchars($row['sname']); ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <strong>Student ID:</strong> <?php echo $row['std_id']; ?>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <strong>Name:</strong> <?php echo htmlspecialchars($row['sname']); ?>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <strong>Email:</strong> <?php echo htmlspecialchars($row['semail']); ?>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <strong>Phone:</strong> <?php echo htmlspecialchars($row['sphone']); ?>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <strong>Department:</strong> <?php echo htmlspecialchars($row['department_name'] ?? 'N/A'); ?>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <strong>CGPA:</strong> <span class="badge bg-info"><?php echo $row['scgpa']; ?></span>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <strong>Gender:</strong> <?php echo htmlspecialchars($row['sgender']); ?>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <strong>Date of Birth:</strong> <?php echo date('d F Y', strtotime($row['sdob'])); ?>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <strong>Address:</strong>
                                                        <div class="mt-2 p-3 bg-light rounded">
                                                            <?php echo nl2br(htmlspecialchars($row['saddress'])); ?>
                                                        </div>
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
                                    <td colspan="8" class="text-center text-muted">No students found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Hidden form for student deletion -->
        <form id="deleteStudentForm" action="delete_student.php" method="POST" style="display: none;">
            <input type="hidden" id="deleteStudentId" name="student_id">
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Function to edit student
    function editStudent(studentId) {
        // Create a form to send student ID to edit page
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'student_edit.php';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'student_id';
        input.value = studentId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }

    // Function to delete student with confirmation
    function deleteStudent(studentId, studentName) {
        if (confirm(`Are you sure you want to delete student "${studentName}"? This action cannot be undone.`)) {
            document.getElementById('deleteStudentId').value = studentId;
            document.getElementById('deleteStudentForm').submit();
        }
    }
</script>
</body>
</html>
