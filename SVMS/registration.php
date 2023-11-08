<?php
session_start();
$page_title = "Register";
require_once 'config/connection.php';

$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate and sanitize user inputs...
  $email = mysqli_real_escape_string($connection, $_POST["email"]);
  $password = mysqli_real_escape_string($connection, $_POST["password"]);
  $fname = mysqli_real_escape_string($connection, $_POST["fname"]);
  $lname = mysqli_real_escape_string($connection, $_POST["lname"]);
  $mobilenumber = mysqli_real_escape_string($connection, $_POST["mobilenumber"]);
  $gender = mysqli_real_escape_string($connection, $_POST["gender"]);
  $bday = $_POST['dob'];
  $address = mysqli_real_escape_string($connection, $_POST["address"]);
  $type = mysqli_real_escape_string($connection, $_POST["usertype"]);
  $path = mysqli_real_escape_string($connection, $_POST["userpath"]);
  $status = mysqli_real_escape_string($connection, $_POST["userstatus"]);

  if (count($errors) == 0) {
    // Insert user data into the database
    $insertQuery = "INSERT INTO users (user_email, user_pass, user_fname, user_lname, user_mobile, user_gender, user_dob, user_address, user_type, user_path, user_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    $stmt = $connection->prepare($insertQuery);
    $stmt->bind_param('sssssssssss', $email, $hashedPassword, $fname, $lname, $mobilenumber, $gender, $bday, $address, $type, $path, $status);

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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
    <?php include('includes/header2.html'); ?>
</head>

<body>
  <div class="container">
    <div class="d-flex justify-content-center align-items-center my-4">
      <form id="registration" class="w-25" method="POST">
      <div class="mb-3">
      <h3>Registration Form</h3>
      </div>
        <div class="mb-3">
          <label for="fname" class="form-label">
            First Name
          </label>
          <input type="text" name="fname" class="form-control">
        </div>
        <div class="mb-3">
          <label for="lname" class="form-label">
            Last Name
          </label>
          <input type="text" name="lname" class="form-control">
        </div>
        <div class="mb-3">
          <label for="mobilenumber" class="form-label">
            Mobile Number
          </label>
          <input type="text" name="mobilenumber" class="form-control">
        </div>
        <div class="mb-3">
          <label for="gender" class="form-check-label">Gender</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" value="Male" id="genderMale">
            <label class="form-check-label" for="gender">
              Male
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" value="Female" id="genderFemale">
            <label class="form-check-label" for="gender">
              Female
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" value="Other" id="genderOther">
            <label class="form-check-label" for="gender">
              Other
            </label>
          </div>
        </div>
        <div class="mb-3">
          <label for="datepicker" class="form-label">Date of Birth</label>
          <input type="date" name="bday" class="form-control datepicker" id="datepicker">
        </div>
        <div class="mb-3">
          <label for="address" class="form-label">
            Address
          </label>
          <input type="text" name="address" class="form-control">
        </div>
        <div class="mb-3">
          <label for="imageInput" class="form-label">Choose an image:</label>
          <input type="file" class="form-control" id="imageInput" name="image">
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">
            Email
          </label>
          <input type="email" name="email" class="form-control" placeholder="name@example.com">
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">
            Password
          </label>
          <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
          <label for="confirmpass" class="form-label">
            Confirm Password
          </label>
          <input type="password" name="confirmpassword" class="form-control">
        </div>
        <div class="mb-3">
          <label for="usertype" class="form-check-label">Register as:</label>
          <div class="form-check">
          <input class="form-check-input" type="radio" name="type" value="Customer" id="typeCustomer">
            <label class="form-check-label" for="usertype">
              Customer
            </label>
          </div>
          <div class="form-check">
          <input class="form-check-input" type="radio" name="type" value="Supplier" id="typeSupplier">
            <label class="form-check-label" for="usertype">
              Supplier
            </label>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php include('includes/footer.html') ?>
</body>

</html>
