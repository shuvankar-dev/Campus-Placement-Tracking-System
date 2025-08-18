<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Login</title>
</head>
<body>
  <h2>Student Login</h2>

  <!-- Show error message if login fails -->
  <?php
  if (isset($_SESSION['login_error'])) {
      echo "<p style='color:red;'>" . $_SESSION['login_error'] . "</p>";
      unset($_SESSION['login_error']);
  }
  ?>

  <form method="POST" action="student_login_action.php">
    <label for="email">Email</label><br>
    <input type="email" name="email" required><br><br>

    <label for="password">Password</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>
</body>
</html>
