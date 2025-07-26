<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TPO Login</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>
<body class="bg-light">
    
<div class="container d-flex justify-content-center align-items-center" style="height:100vh;">
    <div class="card shadow p-4" style="width:350px;">
        <h3 class="text-center mb-4 text-success">TPO Login</h3>
        
        <form>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" placeholder="Enter TPO email">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" placeholder="Enter password">
            </div>
            <button type="submit" class="btn btn-success w-100">Login</button>
        </form>

        <p class="text-center mt-3">
            <a href="register.php" class="text-decoration-none">New TPO? Register Here</a>
        </p>
    </div>
</div>
</body>
</html>