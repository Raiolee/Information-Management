<?php
session_start();
$page_title = "Register";
require 'config/connection.php';
error_reporting(E_ERROR | E_PARSE);
$connection = mysqli_connect(HOST_NAME, USER_NAME, PASSWORD, DB_NAME);

$display_all = "select * from accounts";
$display_specific = "select distinct program from sms";

$query = mysqli_query($connection, $display_all);

$errors = array();

if (isset($_POST['register'])) {
  $email = $_POST['email'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $confirm_pass = $_POST["confirm_pass"];

  if (empty($email) || empty($username) || empty($password) || empty($confirm_pass)) {
    $errors['userType'] = "Fill in all the fields";
  }

  if ($password !== $confirm_pass) {
    $errors['password_match'] = "Passwords do not match";
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Invalid email format";
  }

  if (count($errors) == 0) {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $insertQuery = "INSERT INTO accounts (email, username, password) VALUES (?, ?, ?)";
    $stmt = $connection->prepare($insertQuery);
    $stmt->bind_param('sss', $email, $username, $hashedPassword);
    if ($stmt->execute()) {
      header('location: login.php');
      exit;
    } else {
      $errors['db_error'] = "An error occurred. Please try again later.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register as a Vendor</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <?php include('includes/header2.html'); ?>
</head>

<body>
  <div class="container">
    <div class="d-flex justify-content-center align-items-center my-4 ">
      <form id="registration.php" class="w-25" method="POST">
        <div class="mb-3">
          <label for="exampleFormControlInput1" class="form-label">Email address</label>
          <input type="email" name="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
          <?php if (isset($errors['email'])) : ?>
            <div class="text-danger"><?php echo $errors['email']; ?></div>
          <?php endif; ?>
        </div>
        <div class="mb-3">
          <label for="exampleFormControlTextarea1" class="form-label">Username</label>
          <input type="text" name="username" class="form-control">
        </div>
        <div class="mb-3">
          <label for="exampleFormControlTextarea1" class="form-label">Password</label>
          <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
          <label for="exampleFormControlTextarea1" class="form-label">Confirm Password</label>
          <input type="password" name="confirm_pass" class="form-control">
        </div>
        <button type="submit" name="register" class="btn btn-primary">Submit</button>

        <?php if (count($errors) > 0) : ?>
          <div>
            <?php foreach ($errors as $error) : ?>
              <li>
                <?php echo $error; ?>
              </li>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </form>
    </div>
    <div class="text-center mt-3">
      <p>Already have an account? <a href="login.php">Log in here</a>.</p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  <?php include('includes/footer.html'); ?>
</body>

</html>