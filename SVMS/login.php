<?php
session_start();
require_once 'config/connection.php';

error_reporting(E_ERROR | E_PARSE);

if ((!isset($_SESSION['user']) != "") && (isset($_SESSION['type']) == "Admin")) {
  header("Location: admin_dashboard.php");
  exit;
} elseif ((isset($_SESSION["user"]) != "") && (isset($_SESSION["type"]) == "Supplier")) {
  header("Location: supplier_dashboard.php");
  exit;
}

$error = false;
if (isset($_POST['sign-in'])) {
  $username = test_input($_POST['username']);
  $password = test_input($_POST['password']);
}

if (empty($email)) {
  $error = true;
  $emailError = "Please enter your email address.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $error = true;
  $emailError = "Please enter a valid email addres.";
}

if (empty($password)) {
  $error = true;
  $passwordError = "Please enter your password.";
}

if (!$error) {
  $password = password_hash($password, PASSWORD_DEFAULT);

  $result = mysqli_query($connection, "SELECT user_id, user_email, user_pass, user_type FROM users WHERE user_email = '$email'");
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

  $count = mysqli_num_rows($result); // if username/password is correct it returns must be 1 row

  if ($count == 1 && $row["user_pass"] == $password && $row['user_type'] == 'admin') {
    $_SESSION['user'] = $row['user_id'];
    $_SESSION['type'] = $row['user_type'];
    header('Location: admin_dashboard.php');
  } elseif ($count == 1 && $row["user_pass"] == $password && $row['user_type'] == 'supplier') {
    $_SESSION['user'] = $row['user_id'];
    $_SESSION['type'] = $row['user_type'];
    header('Location: supplier_dashboard.php');
  } else {
    $errorMessage = "Incorrect Credentials, Try again";
  }
}

function test_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
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

  <div id="login-form" class="container">
    <div class="d-flex justify-content-center align-items-center">
      <h3>Sign in.</h3>

      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off" method="POST"
        class="w-25">
        <?php
        if (isset($errorMessage)) {
          ?>
          <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="bi flex-shrink-0 me-2 bi-exclamation-triangle" role="img" aria-label="Warning:"></i>
            <div>
              <?php echo $errorMessage; ?>
            </div>
          </div>

          <?php
        } ?>

        <div class="mt-5">
          <label for="exampleFormControlInput1" class="form-label"> Email </label>
          <input type="email" name="email" class="form-control" id="exampleFormControlInput1" value="<?php echo $email; ?>">
        </div>
        <span class="text-danger">
          <?php echo $emailError ?>
        </span>
        <div class="mb-3">
          <label for="exampleFormControlTextarea1" class="form-label">Password</label>
          <input type="password" name="password" class="form-control">
        </div>
        <span class="text-danger">
          <?php echo $passError ?>
        </span>
        <button type="submit" class="btn btn-primary" name="login" method="POST">Submit</button>
      </form>
    </div>

    <div class="text-center mt-3">
      <p>Do not have an account yet? <a href="registration.php">Register here</a>.</p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
  </script>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <?php include('includes/footer.html'); ?>
</body>

</html>
<?php mysqli_close($connection); ?>