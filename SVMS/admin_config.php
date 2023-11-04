<!-- Connection Query -->
<?php
session_start();
require_once 'config/connection.php';

if ($_SESSION['type'] != "Admin") {

  unset($_SESSION['user']);
  session_unset();
  session_destroy();
  header("Location: login.php");
  exit;
}

$getimage = mysqli_query($connection,"SELECT user_path FROM users");
$userRow = mysqli_fetch_assoc($getimage);
$userimage = $userRow['user_path'];
?>

<!-- get full name query -->
<?php
$getname = mysqli_query($connection, "SELECT CONCAT(user_first, ' ', user_last) AS full_name FROM users;");
$row = mysqli_fetch_assoc($getname);
$full_name = $row['full_name'];
 

// Add admin
if (isset($_POST['update'])) {

  $target = "images/";
  $targetFile = $target . basename($_FILES["images"]["name"]);
  $isOk = false;

  if (!is_dir($target) || !is_writable($target)) {
    die("Error: Upload directory is not accessible.");
  }

  if (file_exists($targetFile)) {
    die("File Exists");
  }

  if (move_uploaded_file($_FILES["images"]["tmp_name"], $targetFile)) {
    $isOk = true;
  }

  $user_email = mysqli_real_escape_string($connection, $_POST['email']);
  $user_pass = mysqli_real_escape_string($connection, $_POST['pass']);
  $user_fname = mysqli_real_escape_string($connection, $_POST['fname']);
  $user_lname = mysqli_real_escape_string($connection, $_POST['lname']);
  $user_mobile = mysqli_real_escape_string($connection, $_POST['number']);
  $user_gender = mysqli_real_escape_string($connection, $_POST['gender']);
  $user_dob = $_POST['bday'];
  $user_address = mysqli_real_escape_string($connection, $_POST['address']);
  $user_type = "Admin";
  $user_role = "Active";

  if ($isOk) {
    $sql = "INSERT INTO users (user_email, user_pass, user_first, user_last, user_mobile, user_gender, user_dob, user_address, user_path, user_type, user_role)VALUES('$user_email','$user_pass','$user_fname','$user_lname','$user_mobile','$user_gender','$user_dob','$user_address','{$targetFile}','$user_type','$user_role')";

  }

  

  if (mysqli_query($connection, $sql)) {

    ?>
    <script>alert('successfully registered ');</script>
    <?php
  } else {
    ?>
    <script>alert('error while Adding! Check email/Server...');</script>
    <?php
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Config</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-2 vh-100 bg-dark text-white">
        <h2 class="m-2">Admin</h2>
        <div class="rounded-circle overflow-hidden mb-3" style="width: 100px; height: 100px; margin: auto;">
          <img class="img-fluid" src="<?php echo $userimage; ?>" alt="">
        </div>
        <div class="text-center">
          <h6>
            <?php echo $full_name; ?>
          </h6>
        </div>
        <div class="ml-2">
          <h5 class="mt-3">General</h5>
          <ul class="list-unstyled">
            <li class="m-4"><a href="dashboard_admin.php" class="text-decoration-none text-light">Dashboard</a></li>
            <li class="m-4"><a href="admin_config.php" class="text-decoration-none text-light">Admins</a></li>
            <li class="m-4"><a href="supplier_details.php" class="text-decoration-none text-light">Supplier
                Details</a></li>
          </ul>
        </div>
      </div>

      <!-- Header -->
      <div class="col-10">
        <?php include('includes/header3.html'); ?>
        <!-- Main Contents -->
        <!-- Add New Admin Form -->
        <div class="container mt-3">
          <h3>Add New Admin</h3>
          <form action="admin_config.php" method="post" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-3 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="col-md-3 mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="pass" required>
              </div>
              <div class="col-md-3 mb-3">
                <label for="fname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="fname" name="fname" required>
              </div>
              <div class="col-md-3 mb-3">
                <label for="lname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lname" name="lname" required>
              </div>
              <div class="col-md-3 mb-3">
                <label for="number" class="form-label">Mobile Number</label>
                <input type="text" class="form-control" id="number" name="number" required>
              </div>
              <div class="col-md-3 mb-3">
                <label class="form-label">Gender</label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="gender" value="Male" id="genderMale">
                  <label class="form-check-label" for="genderMale">Male</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="gender" value="Female" id="genderFemale">
                  <label class="form-check-label" for="genderFemale">Female</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="gender" value="Rather not say" id="genderOther"
                    checked>
                  <label class="form-check-label" for="genderOther">Rather not say</label>
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <label for="bday" class="form-label">Date of Birth</label>
                <input type="date" class="form-control" id="bday" name="bday" required>
              </div>
              <div class="col-md-3 mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
              </div>
              <div class="col-md-3 mb-3">
                <label for="imageInput" class="form-label">Choose an image:</label>
                <input type="file" class="form-control" id="imageInput" name="images" required>
              </div>
            </div>
            <button type="submit" class="btn btn-primary" name="update">Add Admin</button>
          </form>
        </div>

        <!-- Admin table -->
        <div class="container mt-4 border-top p-3">
          <h3>List of Admins</h3>
          <div class="col-10">
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Email</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Mobile Number</th>
                  <th>User Status</th>
                  <th>Picture</th>
                  <th colspan="2">Action</th>
                </tr>
              </thead>

              <tbody>
                <?php $userdata = array();
                $res = mysqli_query($connection, 'SELECT * FROM users');
                while ($userRow = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
                  $projects[] = $userRow;
                }
                foreach ($projects as $userRow) {
                  ?>
                  <form method="get" action="admin_edit.php">
                    <tr>
                      <td>
                        <?php echo $userRow['user_id']; ?>
                      </td>
                      <td>
                        <?php echo $userRow['user_email']; ?>
                      </td>
                      <td>
                        <?php echo $userRow['user_first']; ?>
                      </td>
                      <td>
                        <?php echo $userRow['user_last']; ?>
                      </td>
                      <td>
                        <?php echo $userRow['user_mobile']; ?>
                      </td>
                      <td>
                        <?php echo $userRow['user_role']; ?>
                      </td>
                      <td><img src="<?php echo $userRow['user_path']; ?>" width="55" height="50"></td>
                      <td><input type="submit" class="btn btn-secondary" name="action" value="Edit"></td>
                      <td><input type="submit" class="btn btn-secondary" name="action" value="Delete"></td>
                      <td><input type="hidden" name="id" value="<?php echo $userRow['user_id']; ?>"/></td>
                    </tr>
                  </form>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php if (isset($successMessage)): ?>
    <div class="alert alert-success position-fixed top-0 start-50 translate-middle-x">
      <?php echo $successMessage; ?>
    </div>

    <script>
      setTimeout(function () {
        document.querySelector('.alert').style.display = 'none';
      }, 3000); // Disappear after 3 seconds (3000 milliseconds)
    </script>
  <?php endif; ?>

  <!-- Display Error Message -->
  <?php if (isset($errorMessage)): ?>
    <div class="alert alert-danger position-fixed top-0 start-50 translate-middle-x">
      <?php echo $errorMessage; ?>
    </div>

    <script>
      setTimeout(function () {
        document.querySelector('.alert').style.display = 'none';
      }, 3000); // Disappear after 3 seconds (3000 milliseconds)
    </script>
  <?php endif; ?>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>