<?php
session_start();
require_once 'config/connection.php';
// Checks if the user is a supplier
if ($_SESSION['type'] != "Supplier") {
    unset($_SESSION['user']);
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

// Gets user's image path
$getimage = mysqli_query($connection, "SELECT user_path FROM users WHERE user_id = " . $_SESSION['user']);
$userRow = mysqli_fetch_assoc($getimage);
$userimage = $userRow['user_path'];

// Gets user's details
$getname = mysqli_query($connection, "SELECT user_fname, user_lname, user_mobile, user_gender, user_address, user_dob FROM users WHERE user_id = " . $_SESSION['user']);
$row = mysqli_fetch_assoc($getname);
$user_fname = $row['user_fname'];
$user_lname = $row['user_lname'];
$user_mobile = $row['user_mobile'];
$user_gender = $row['user_gender'];
$user_address = $row['user_address'];
$user_dob = $row['user_dob'];

// Collection of inputs
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_fname = mysqli_real_escape_string($connection, $_POST['new_fname']);
    $new_lname = mysqli_real_escape_string($connection, $_POST['new_lname']);
    $new_mobile = mysqli_real_escape_string($connection, $_POST['new_mobile']);
    $new_gender = mysqli_real_escape_string($connection, $_POST['new_gender']);
    $new_address = mysqli_real_escape_string($connection, $_POST['new_address']);
    $new_dob = mysqli_real_escape_string($connection, $_POST['new_dob']);

    // Perform update query
    $updateQuery = "UPDATE users SET user_fname = '$new_fname', user_lname = '$new_lname', user_mobile = '$new_mobile', user_gender = '$new_gender', user_address = '$new_address', user_dob = '$new_dob' WHERE user_id = " . $_SESSION['user'];

    if (mysqli_query($connection, $updateQuery)) {
        header("Location: supplier_contact_details.php");
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($connection);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Supplier Contact</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="m-0">
    <!-- Supplier Details Edit form -->
    <div class="container mt-3">
        <h3>Edit Supplier Contact</h3>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="new_fname" class="form-label">Supplier First Name</label>
                    <input type="text" class="form-control" id="new_fname" name="new_fname" value="<?php echo $user_fname; ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="new_lname" class="form-label">Supplier Last Name</label>
                    <input type="text" class="form-control" id="new_lname" name="new_lname" value="<?php echo $user_lname; ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="new_mobile" class="form-label">Supplier Mobile</label>
                    <input type="text" class="form-control" id="new_mobile" name="new_mobile" value="<?php echo $user_mobile; ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="new_gender" class="form-label">Supplier Gender</label>
                    <input type="text" class="form-control" id="new_gender" name="new_gender" value="<?php echo $user_gender; ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="new_address" class="form-label">Supplier Address</label>
                    <input type="text" class="form-control" id="new_address" name="new_address" value="<?php echo $user_address; ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="new_dob" class="form-label">Supplier Date of Birth</label>
                    <input type="text" class="form-control" id="new_dob" name="new_dob" value="<?php echo $user_dob; ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
