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
                <a href="departments.php" class="nav-link text-white">
                    <i class="fa-solid fa-building me-3"></i>Departments
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="students.php" class="nav-link text-white active fw-bold">
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
                    <a href="departments.php" class="nav-link text-white">
                        <i class="fa-solid fa-building me-3"></i>Departments
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="students.php" class="nav-link text-white active fw-bold">
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
                <!-- Filter and Search Section -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="departmentFilter" class="form-label">
                            <i class="fa-solid fa-building me-2"></i>Filter by Department
                        </label>
                        <select class="form-select" id="departmentFilter" onchange="filterStudents()">
                            <option value="">All Departments</option>
                            <?php
                            // Fetch departments for filter dropdown
                            $dept_sql = "SELECT d_id, department_name FROM department ORDER BY department_name";
                            $dept_result = $conn->query($dept_sql);
                            while ($dept = $dept_result->fetch_assoc()) {
                                $selected = (isset($_GET['dept_id']) && $_GET['dept_id'] == $dept['d_id']) ? 'selected' : '';
                                echo "<option value='{$dept['d_id']}' $selected>{$dept['department_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="searchInput" class="form-label">
                            <i class="fa-solid fa-search me-2"></i>Search Students
                        </label>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search by name, email, or phone..." onkeyup="filterStudents()" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                            <i class="fa-solid fa-refresh me-2"></i>Clear Filters
                        </button>
                    </div>
                </div>

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
                        <tbody id="studentsTableBody">
                            <?php
                            // Build SQL query with filters
                            $where_conditions = [];
                            $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
                            $dept_filter = isset($_GET['dept_id']) ? (int)$_GET['dept_id'] : 0;
                            
                            if ($dept_filter > 0) {
                                $where_conditions[] = "s.dept_id = $dept_filter";
                            }
                            
                            if (!empty($search_term)) {
                                $search_escaped = mysqli_real_escape_string($conn, $search_term);
                                $where_conditions[] = "(s.sname LIKE '%$search_escaped%' OR s.semail LIKE '%$search_escaped%' OR s.sphone LIKE '%$search_escaped%' OR d.department_name LIKE '%$search_escaped%')";
                            }
                            
                            $where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";
                            
                            // Fetch students with department information
                            $sql = "SELECT s.*, d.department_name 
                                   FROM student s 
                                   LEFT JOIN department d ON s.dept_id = d.d_id 
                                   $where_clause
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
                
                <!-- Results Summary -->
                <div class="mt-3 text-muted">
                    <small>
                        <i class="fa-solid fa-info-circle me-1"></i>
                        Showing <?php echo $result ? $result->num_rows : 0; ?> student(s)
                        <?php if (!empty($search_term)): ?>
                            matching "<?php echo htmlspecialchars($search_term); ?>"
                        <?php endif; ?>
                        <?php if ($dept_filter > 0): ?>
                            <?php
                            $dept_name_sql = "SELECT department_name FROM department WHERE d_id = $dept_filter";
                            $dept_name_result = $conn->query($dept_name_sql);
                            $dept_name = $dept_name_result->fetch_assoc()['department_name'];
                            ?>
                            in <?php echo htmlspecialchars($dept_name); ?>
                        <?php endif; ?>
                    </small>
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

    // Function to filter students
    function filterStudents() {
        const deptFilter = document.getElementById('departmentFilter').value;
        const searchInput = document.getElementById('searchInput').value;
        
        // Build URL with filters
        let url = 'students.php?';
        const params = [];
        
        if (deptFilter) {
            params.push('dept_id=' + encodeURIComponent(deptFilter));
        }
        
        if (searchInput.trim()) {
            params.push('search=' + encodeURIComponent(searchInput.trim()));
        }
        
        if (params.length > 0) {
            url += params.join('&');
        } else {
            url = 'students.php';
        }
        
        // Redirect to filtered page
        window.location.href = url;
    }

    // Function to clear all filters
    function clearFilters() {
        window.location.href = 'students.php';
    }

    // Auto-filter on Enter key press in search box
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            filterStudents();
        }
    });

    // Real-time search with debounce
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            filterStudents();
        }, 500); // Wait 500ms after user stops typing
    });
</script>
</body>
</html>
