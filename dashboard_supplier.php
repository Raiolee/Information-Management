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

// Gets user's full name
$getname = mysqli_query($connection, "SELECT CONCAT(user_fname, ' ', user_lname) AS full_name FROM users WHERE user_id = " . $_SESSION['user']);
$row = mysqli_fetch_assoc($getname);
$full_name = $row['full_name'];

$isOk = false;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="m-0">

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-2 vh-100 bg-dark text-white">
                <h2 class="m-2">Supplier</h2>
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
                        <li class="m-4"><a href="dashboard_supplier.php"
                                class="text-decoration-none text-light">Dashboard</a></li>
                        <li class="m-4"><a href="supplier_products.php" class="text-decoration-none text-light">Products</a>
                        </li>
                        <li class="m-4"><a href="supplier_contact_details.php" class="text-decoration-none text-light">Contact Details</a></li>
                    </ul>
                </div>
            </div>

            <!-- Navigation bar -->
            <div class="col-10 d-flex flex-column">
                <?php include('includes/header.php'); ?>
                <div class="row mt-3">
            <!-- Total Customers Gathered -->
                 <div class="col-3 mx-auto">
                    <div class="card text-center">
                     <div class="card-header">Total Customers Gathered</div>
                         <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                             <?php
                                 $totalCustomersQuery = mysqli_query($connection, "SELECT COUNT(DISTINCT customer_id) AS total_customers FROM deliver WHERE supplier_id = " . $_SESSION['user']);
                                 $totalCustomersRow = mysqli_fetch_assoc($totalCustomersQuery);
                                    echo $totalCustomersRow['total_customers'];
                                 ?>
                        </li>
                        </ul>
                    </div>
                    </div>
            <!-- Total Amount Earned -->
                <div class="col-3 mx-auto">
                    <div class="card text-center">
                        <div class="card-header">Total Amount Earned</div>
                             <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <?php
                                         $totalAmountQuery = mysqli_query($connection, "SELECT SUM(product.product_price * cart.quantity) AS total_earned
                                        FROM cart JOIN product ON cart.product_id = product.product_id WHERE product.supplier_user_id = " . $_SESSION['user']); $totalAmountRow = mysqli_fetch_assoc($totalAmountQuery);
                                                echo '₱' . number_format($totalAmountRow['total_earned'], 2);
                                     ?>
                                 </li>
                            </ul>
                        </div>
                    </div>
                <!-- Most Popular Product -->
                    <div class="col-3 mx-auto">
                         <div class="card text-center">
                             <div class="card-header">Most Popular Product</div>
                                 <ul class="list-group list-group-flush">
                                     <li class="list-group-item">
                                        <?php
                                            $mostPopularProductQuery = mysqli_query($connection, "SELECT product.product_name, COUNT(cart.product_id) AS frequency FROM cart JOIN product ON cart.product_id = product.product_id WHERE product.supplier_user_id = " . $_SESSION['user'] . " GROUP BY cart.product_id ORDER BY frequency DESC LIMIT 1");
                                            $mostPopularProductRow = mysqli_fetch_assoc($mostPopularProductQuery);
                                                     if ($mostPopularProductRow) {
                                                        echo $mostPopularProductRow['product_name'];
                                                     } else {
                                                            echo "No data";
                                                    }
                                         ?>
                                        </li>
                                      </ul>
                                    </div>
                                </div>    
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