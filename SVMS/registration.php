<?php
session_start();
$page_title = "Register";
require_once 'config/connection.php';
error_reporting(E_ERROR | E_PARSE);

$errors = array();
$target = "images/";
$targetFile = $target . basename($_FILES["image"]["name"]);
$isOk = false;

if (file_exists($targetFile)) {
  die("File Exists");
}
if (move_uploaded_file($_FILES["image"]["temp_name"], $targetFile)) {
  $isOk = true;
}

if (isset($_POST['register'])) {
  $fname = mysqli_real_escape_string($conn, $_POST['user_fname']);
  $lname = mysqli_real_escape_string($conn, $_POST['user_lname']);
  $usermobile = mysqli_real_escape_string($conn, $_POST['user_mobile']);
  $usergender = mysqli_real_escape_string($conn, $_POST['user_gender']);
  $userbday = $_POST['user_dob'];
  $useraddress = mysqli_real_escape_string($conn, $_POST['u_address']);
  $useremail = mysqli_real_escape_string($conn, $_POST['user_email']);
  $userpassword = mysqli_real_escape_string($conn, $_POST['user_pass']);
  $usertype = mysqli_real_escape_string($conn, $_POST['u_type']);
  
  if (empty($fname) || empty($lname) || empty($usermobile) || empty($usermobile) || empty($userbday) || empty($useraddress) || empty($fname) || empty($email) || empty($username) || empty($password) || empty($confirm_pass) || empty($usertype)) {
    $errors['userType'] = "Fill in all the fields";
  }

  if ($password !== $confirm_pass) {
    $errors['password_match'] = "Passwords do not match";
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Invalid email format";
  }

  if ((count($errors) == 0) && ($isOk)) {
    $insertQuery = "INSERT INTO users (user_email, user_pass, user_fname, user_lname, user_mobile, user_gender, user_dob, user_address, user_path, user_type, user_role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($insertQuery);
    $stmt->bind_param('ssssss', $email, $username, $hashedPassword);
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
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

  <?php include('includes/header2.html'); ?>
</head>

<body>
  <div class="container">
    <div class="d-flex justify-content-center align-items-center my-4 ">
      <form id="registration.php" class="w-25" method="POST">
        <div class="mb-3">
          <label for="exampleFormControlTextarea1" class="form-label">First Name</label>
          <input type="text" name="fname" class="form-control">
        </div>
        <div class="mb-3">
          <label for="exampleFormControlTextarea1" class="form-label">Last Name</label>
          <input type="text" name="lname" class="form-control">
        </div>
        <div class="mb-3">
          <label for="exampleFormControlTextarea1" class="form-label">Mobile Number</label>
          <input type="text" name="usermobile" class="form-control">
        </div>
        <div class="mb-3">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="usergender" value="Male">
            <label class="form-check-label" for="inlineCheckbox1">Male</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox2" name="usergender" value="Female">
            <label class="form-check-label" for="inlineCheckbox2">Female</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox3" name="usergender"
              value="Rather not say">
            <label class="form-check-label" for="inlineCheckbox3">Rather not say</label>
          </div>
          <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Date of Birth</label>
            <input type="text" name="userbday" class="form-control" id="datepicker">
          </div>
        </div>
        <div class="mb-3">
          <label for="exampleFormControlTextarea1" class="form-label">Address</label>
          <input type="text" name="Address" class="form-control">
        </div>
        <div class="mb-3">
          <label for="imageInput" class="form-label">Choose an image:</label>
          <input type="file" class="form-control" id="imageInput" name="image">
        </div>
        <div class="mb-3">
          <label for="exampleFormControlInput1" class="form-label">Email address</label>
          <input type="email" name="useremail" class="form-control" id="exampleFormControlInput1"
            placeholder="name@example.com">
          <?php if (isset($errors['email'])): ?>
            <div class="text-danger">
              <?php echo $errors['email']; ?>
            </div>
          <?php endif; ?>
        </div>
        <div class="mb-3">
          <label for="exampleFormControlTextarea1" class="form-label">Username</label>
          <input type="text" name="username" class="form-control">
        </div>
        <div class="mb-3">
          <label for="exampleFormControlTextarea1" class="form-label">Password</label>
          <input type="password" name="userpassword" class="form-control">
        </div>
        <div class="mb-3">
          <label for="exampleFormControlTextarea1" class="form-label">Confirm Password</label>
          <input type="password" name="confirm_pass" class="form-control">
        </div>
        <div class="mb-3">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="usertype" value="Customer">
            <label class="form-check-label" for="inlineCheckbox1">Customer</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox2" name="usertype" value="Supplier">
            <label class="form-check-label" for="inlineCheckbox2">Supplier</label>
          </div>
        </div>
        <button type="submit" name="register" class="btn btn-primary">Submit</button>

        <?php if (count($errors) > 0): ?>
          <div>
            <?php foreach ($errors as $error): ?>
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


  <script>
    $(document).ready(function () {
      $('#datepicker').datepicker({
        format: 'yyyy-mm-dd', // Set date format
        autoclose: true,      // Close the datepicker when a date is selected
        todayHighlight: true  // Highlight today's date
      });
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  <?php include('includes/footer.html'); ?>
</body>

</html>