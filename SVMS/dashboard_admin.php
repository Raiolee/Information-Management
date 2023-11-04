<?php
session_start();
require_once 'config/connection.php';

$getname = mysqli_query($connection, "SELECT CONCAT(user_first, ' ', user_last) AS full_name FROM users;");
$row = mysqli_fetch_assoc($getname);
$full_name = $row['full_name'];

$getimage = mysqli_query($connection,"SELECT user_path FROM users");
$userRow = mysqli_fetch_assoc($getimage);
$userimage = $userRow['user_path'];

if ($_SESSION['type'] != "Admin") {

    unset($_SESSION['user']);
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

$usertotal = mysqli_query($connection, "SELECT * FROM users;");
$usercount = mysqli_num_rows($usertotal);

$admintotal = mysqli_query($connection, "SELECT * FROM users WHERE user_type = 'Admin';");
$admincount = mysqli_num_rows($admintotal);

$suppliertotal = mysqli_query($connection, "SELECT * FROM users WHERE user_type = 'Supplier';");
$suppliercount = mysqli_num_rows($suppliertotal);

$customertotal = mysqli_query($connection, "SELECT * FROM customer ;");
$customercount = mysqli_num_rows($customertotal);
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
                    <img class="img-fluid" src="<?php echo $userimage ?>" alt="">
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
                        <li class="m-4"><a href="supplier_details.php" class="text-decoration-none text-light">Supplier
                                Details</a></li>
                    </ul>
                </div>
            </div>

            <!-- Navigation bar -->
            <div class="col-10 d-flex flex-column">
                <?php include('includes/header3.html'); ?>
                <div class="row mt-3">
                    <div class="col-3">
                        <div class="card text-center">
                            <div class="card-header">Total Admin and Supplier</div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <?php echo $usercount ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card text-center">
                            <div class="card-header">Total Admins</div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <?php echo $admincount ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card text-center">
                            <div class="card-header">Total Supplier</div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <?php echo $suppliercount ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card text-center">
                            <div class="card-header">Total Customers</div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <?php echo $customercount ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>