<?php
session_start();
require 'config/connection.php';

error_reporting(E_ERROR | E_PARSE);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $db_host = 'HOST_NAME';
  $db_user = 'USER_NAME';
  $db_password = 'PASSWORD';
  $db_name = 'DB_NAME';

  $connection = mysqli_connect(HOST_NAME, USER_NAME, PASSWORD, DB_NAME);

  if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
  }

  $username = $_POST['username'];
  $password = $_POST['password'];

  $query = "SELECT * FROM accounts WHERE username = ?";
  $stmt = $connection->prepare($query);
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && mysqli_num_rows($result) == 1) {
    $row = $result->fetch_assoc();
    $storedHashedPassword = $row['password'];

    if (password_verify($password, $storedHashedPassword)) {
      $_SESSION['username'] = $username;
      $_SESSION['password'] = $storedHashedPassword;
      header("Location: homepage.php");
      exit;
    } else {
      $error = "Invalid username or password.";
    }
  } else {
    $error = "Invalid username or password.";
  }

  mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendo Central ng Pilipinas</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <?php include('includes/header2.html'); ?>
</head>

<body>

  <div class="container">
    <div class="d-flex justify-content-center align-items-center">
      <form action="homepage.php" method="POST" class="w-25">
        <div class="mt-5">
          <label for="exampleFormControlInput1" class="form-label">Username</label>
          <input type="text" name="username" class="form-control" id="exampleFormControlInput1">
        </div>
        <div class="mb-3">
          <label for="exampleFormControlTextarea1" class="form-label">Password</label>
          <input type="password" name="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary" method="POST">Submit</button>
      </form>
    </div>

    <div class="text-center mt-3">
      <p>Do not have an account yet? <a href="registration.php">Register here</a>.</p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
  </script>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  <?php include('includes/footer.html'); ?>
</body>

</html>