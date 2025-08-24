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
                <a href="uplodeSTD.php" class="btn btn-success me-2">
                    <i class="fa-solid fa-upload me-2"></i>Add New Students (CSV Upload)
                </a>
                <a href="tpo_dashboard.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Back to Dashboard</a>
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
