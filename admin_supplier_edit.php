<?php
session_start();
require_once 'config/connection.php';

// get user's full name
$getname = mysqli_query($connection, "SELECT CONCAT(user_fname, ' ', user_lname) AS full_name FROM users;");
$row = mysqli_fetch_assoc($getname);
$full_name = $row['full_name'];

// get user's image path
$getimage = mysqli_query($connection, "SELECT user_path FROM users WHERE user_id = " . $_SESSION['user']);
$userRow = mysqli_fetch_assoc($getimage);
$userimage = $userRow['user_path'];

// Check if 'id' is set in $_GET
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // get user details based on the provided 'id'
    $query = mysqli_query($connection, "SELECT * FROM users WHERE user_id=" . $user_id);
    $getquery = mysqli_fetch_array($query, MYSQLI_ASSOC);

    // Collection of inputs
    if (isset($_POST['update'])) {
        $user_email = mysqli_real_escape_string($connection, $_POST['email']);
        $user_pass = mysqli_real_escape_string($connection, $_POST['pass']);
        $user_first = mysqli_real_escape_string($connection, $_POST['fname']);
        $user_last = mysqli_real_escape_string($connection, $_POST['name']);
        $user_mobile = mysqli_real_escape_string($connection, $_POST['number']);
        $user_gender = mysqli_real_escape_string($connection, $_POST['usergender']);
        $user_dob = $_POST['bday'];
        $user_address = mysqli_real_escape_string($connection, $_POST['address']);

        // Update query
        $query2 = "UPDATE users SET user_email='$user_email', user_password='$user_pass', user_fname='$user_first', user_lname='$user_last', user_mobile='$user_mobile', user_gender='$user_gender', user_dob='$user_dob', user_address='$user_address' WHERE user_id=" . $user_id;

        // Perform the update and error handling
        if (mysqli_query($connection, $query2)) {
            echo 'Successfully Updated';
        } else {
            echo 'Error Updating! Check Server';
        }
    }

    // Delete query
    if (isset($_GET['action']) && $_GET['action'] == 'Delete') {
        
        $del = "DELETE FROM users WHERE user_id=" . $user_id;

        if (mysqli_query($connection, $del)) {
            ?>
            <script>alert('Successfully Deleted ');</script>
            <?php
            header("Location: admin_supplier_config.php");
            exit();
        } else {
            ?>
            <script>alert('Error while Deleting! ...');</script>
        <?php
        }
    }
} else { // Error Handling
    echo "User ID not provided.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Left Side Header</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="m-0">

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
                        <li class="m-4"><a href="dashboard_admin.php"
                                class="text-decoration-none text-light">Dashboard</a></li>
                        <li class="m-4"><a href="admin_config.php" class="text-decoration-none text-light">Admins</a>
                        </li>
                        <li class="m-4"><a href="admin_supplier_config.php"
                                class="text-decoration-none text-light">Suppliers</a></li>
                        <li class="m-4"><a href="admin_customer_config.php"
                                class="text-decoration-none text-light">Customers</a>
                        </li>
                        <li class="m-4"><a href="admin_products_config.php"
                                class="text-decoration-none text-light">Products</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Navigation bar -->
            <div class="col-10 d-flex flex-column">
                <?php include('includes/header.php'); ?>
                <!-- Supplier Edit Form -->
                <div class="container mt-3">
                    <h3>Edit Details</h3>
                    <form action="admin_supplier_edit.php?id=<?php echo $user_id; ?>" method="post"
                        enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo $getquery['user_email']; ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="pass"
                                    value="<?php echo $getquery['user_password']; ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="fname" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="fname" name="fname"
                                    value="<?php echo $getquery['user_fname']; ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="lname" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lname" name="name"
                                    value="<?php echo $getquery['user_lname']; ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="number" class="form-label">Mobile Number</label>
                                <input type="text" class="form-control" id="number" name="number"
                                    value="<?php echo $getquery['user_mobile']; ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Gender</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="usergender" value="Male" <?php if ($getquery['user_gender'] == 'Male')
                                        echo 'checked=checked"'; ?> id="genderMale">
                                    <label class="form-check-label" for="genderMale">Male</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="usergender" value="Female" <?php if ($getquery['user_gender'] == 'Female')
                                        echo 'checked=checked"'; ?>
                                        id="genderFemale">
                                    <label class="form-check-label" for="genderFemale">Female</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="usergender"
                                        value="Other" <?php if ($getquery['user_gender'] == 'Other')
                                            echo 'checked=checked"'; ?> id="genderOther">
                                    <label class="form-check-label" for="genderOther">Other</label>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="bday" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="bday" name="bday"
                                    value="<?php echo $getquery['user_dob']; ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address"
                                    rows="3"><?php echo $getquery['user_address']; ?></textarea>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="imageInput" class="form-label">Choose an image:</label>
                                <input type="file" class="form-control" id="imageInput" name="images"
                                    width="100" height="100">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="userStatus" class="form-label">Supplier Status</label>
                                <div class="form-check">
                                    <label for="supplierstatus" class="form-label">Active</label>
                                    <input class="form-check-input" type="radio" name="user_status" value="Active" <?php if ($getquery['user_status'] == 'Active')
                                        echo 'checked="checked"'; ?>>
                                </div>
                                <div class="form-check">
                                    <label for="userStatus" class="form-label">Inactive</label>
                                    <input class="form-check-input" type="radio" name="user_status" value="Inactive"
                                        <?php if ($getquery['user_status'] == 'Inactive')
                                            echo 'checked="checked"'; ?>>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $user_id; ?>">
                        <button type="submit" class="btn btn-primary" name="update">Submit</button>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
