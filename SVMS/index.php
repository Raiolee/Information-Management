<?php
session_start();
require_once 'config/connection.php';

// Redirect if session is set
if(isset($_SESSION['user'])) {
    if($_SESSION['type'] == "Admin") {
        header("Location: dashboard_admin.php");
    } elseif($_SESSION['type'] == "Supplier") {
        header("Location: dashboard_supplier.php");
    } elseif($_SESSION["type"] == "Customer") {
      header("homepage.php");
    }
    exit;
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Initialize error variables
$error = false;


if(isset($_POST['login'])) {
    $email = test_input($_POST['email']);
    $pass = test_input($_POST['pass']);

    // Prevent SQL injection
    if(empty($email)) {
        $error = true;
        $emailError = "Please enter your email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $emailError = "Please enter a valid email address.";
    }

    if(empty($pass)) {
        $error = true;
        $passwordError = "Please enter your password.";
    }

    // If there's no error, continue to login
    if(!$error) {
        $password = $pass;

        $res = mysqli_query($connection, "SELECT user_id, user_email, user_password, user_type FROM users WHERE user_email='$email'");
        $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

        $count = mysqli_num_rows($res);

        if($count == 1 && $row['user_password'] == $password) {
            $_SESSION['user'] = $row['user_id'];
            $_SESSION['type'] = $row['user_type'];

            if($row['user_type'] == 'Admin') {
                header("Location: dashboard_admin.php");
            } elseif($row['user_type'] == 'Supplier') {
                header("Location: dashboard_supplier.php");
            }
            exit;
        } else {
            $errorMSG = "Incorrect Credentials, Try again...";
        }
    }
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
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off" method="POST"
        class="w-25">
        <?php
        if (isset($errorMSG)) {
          ?>
          <div class="alert alert-danger d-flex align-items-center mt-3" role="alert">
            <i class="bi flex-shrink-0 me-2 bi-exclamation-triangle" role="img" aria-label="Warning:"></i>
            <div>
              <?php echo isset($errorMSG) ? $errorMSG: ''; ?>
            </div>
          </div>

          <?php
        } ?>
        <div class="mt-3">
          <h3>Sign in.</h3>
        </div>
        <div class="mb-3">
          <label for="exampleFormControlInput1" class="form-label"> Email </label>
          <input type="email" name="email" class="form-control" id="exampleFormControlInput1"
            value="<?php echo isset($email) ? $email: ''; ?>">
        </div>
        <span class="text-danger">
          <?php echo isset($emailError) ? $emailError: ''; ?>
        </span>
        <div class="mb-3">
          <label for="exampleFormControlTextarea1" class="form-label">Password</label>
          <input type="password" name="pass" class="form-control">
        </div>
        <span class="text-danger">
          <?php echo isset($passwordError) ? $passwordError: ''; ?>
        </span>
        <div class="d-flex mt-3">
          <button type="submit" class="btn btn-primary" name="login">Submit</button>
        </div>
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
