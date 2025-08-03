<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TPO Registration</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center" style="height:100vh;">
    <div class="card shadow p-4" style="width:400px;">
        <h3 class="text-center mb-4 text-success">TPO Registration</h3>
    <?php
        if (isset($_GET['success'])) {
            echo "<p class='text-success'>Registered Successfully!";
        } elseif (isset($_GET['error'])) {
            echo "<p class='text-danger'>Error: " . htmlspecialchars($_GET['error']) . "</p>";
        }
        ?>

        <form action="../action/tpo_register_action.php" method="POST">
            <div class="mb-3">
                <label class="form-label"> Name</label>
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control" name="first_name" placeholder="First name" aria-label="First name" required>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="last_name" placeholder="Last name" aria-label="Last name" required>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" placeholder="Enter email" required name="email">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" placeholder="Create password" required name="password">
            </div>
            <button type="submit" class="btn btn-success w-100">Register</button>
        </form>

        <p class="text-center mt-3">
            Already registered? 
            <a href="index.php" class="text-decoration-none">Login Here</a>
        </p>
    </div>
</div>
    
</body>
</html>