<?php
session_start();
require_once 'config/connection.php';

if ($_SESSION['type'] != "Admin") {
    unset($_SESSION['user']);
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

// get image path
$getimage = mysqli_query($connection, "SELECT user_path FROM users WHERE user_id = " . $_SESSION['user']);
$userRow = mysqli_fetch_assoc($getimage);
$userimage = $userRow['user_path'];

// get full name 
$getname = mysqli_query($connection, "SELECT CONCAT(user_fname, ' ', user_lname) AS full_name FROM users WHERE user_id = " . $_SESSION['user']);
$row = mysqli_fetch_assoc($getname);
$full_name = $row['full_name'];


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Config</title>
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
                        <li class="m-4"><a href="dashboard_admin.php"
                                class="text-decoration-none text-light">Dashboard</a>
                        </li>
                        <li class="m-4"><a href="admin_config.php" class="text-decoration-none text-light">Admins</a>
                        </li>
                        <li class="m-4"><a href="admin_supplier_config.php"
                                class="text-decoration-none text-light">Suppliers</a>
                        </li>
                        <li class="m-4"><a href="admin_customer_config.php"
                                class="text-decoration-none text-light">Customers</a>
                        </li>
                        <li class="m-4"><a href="admin_products_config.php"
                                class="text-decoration-none text-light">Products</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Header -->
            <div class="col-10">
                <?php include('includes/header.php') ?>

                <!-- Main Contents -->

                <!-- Product table -->
                <div class="container mt-4 border-top p-3">
                    <h3>List of Products</h3>
                    <div class="col-10">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Supplier_ID</th>
                                    <th>Product Name</th>
                                    <th>Product Quantity</th>
                                    <th>Product Price</th>
                                    <th>Product Description </th>
                                    <th>Picture</th>
                                    <th colspan="2">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $userId = $_SESSION['user'];
                                $productsQuery = "SELECT * FROM product";
                                $productsResult = mysqli_query($connection, $productsQuery);

                                if ($productsResult) {
                                    while ($productsRow = mysqli_fetch_assoc($productsResult)) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $productsRow['product_id']; ?>
                                            </td>
                                            <td>
                                                <?php echo $productsRow['supplier_user_id']; ?>
                                            </td>
                                            <td>
                                                <?php echo $productsRow['product_name']; ?>
                                            </td>
                                            <td>
                                                <?php echo $productsRow['product_quantity']; ?>
                                            </td>
                                            <td>
                                                <?php echo $productsRow['product_price']; ?>
                                            </td>
                                            <td>
                                                <?php echo $productsRow['product_description']; ?>
                                            </td>
                                            <td><img src="<?php echo $productsRow['product_path']; ?>" width="55" height="50">
                                            </td>
                                            <td><a
                                                    href="supplier_edit.php?action=Delete&id=<?php echo $productsRow['product_id']; ?>">Delete</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error alerts -->
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