<?php
require_once 'config/connection.php';
$connection = mysqli_connect(HOST_NAME, USER_NAME, PASSWORD, DB_NAME);

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

error_reporting(E_ERROR | E_PARSE);

$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $totalAmount = 0;
    $query = "SELECT * FROM cart";
    $result = mysqli_query($connection, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $productId = $row['id'];
        $quantityName = 'quantity_' . $productId;
        $totalAmount += $row['price'] * $_POST[$quantityName];
    }
}

$id = $_GET['id'];
$query = "SELECT * FROM cart WHERE id = '$id'";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <?php include('includes/homepageheader.php'); ?>
    <style>
        table, th, tr{
            text-align:left;
        }
        h2{
            text-align: center;
            background-color: #efefef;
            padding: 2%;
        }
        table th{
            background-color: #efefef;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Shopping Cart</h2>
        <table class="table">
            <thead>
                <tr>
                    <th width="15%">Picture</th>
                    <th width="20%">Product Name</th>
                    <th width="15%">Quantity</th>
                    <th width="15%">Price Details</th>
                    <th width="15%">Total Price</th>
                    <th width="15%">Operation</th>
                </tr>
            </thead>
            <tbody>
                <form method="post" action="update_quantities.php">
                    <?php
                    $query = "SELECT * FROM cart";
                    $result = mysqli_query($connection, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <tr>
                            <td><img src="<?php echo $row['product_path']; ?>" class="card-img-top mt-3 rounded" alt="Product Image" style="width: 100px; height: auto;"></td>
                            <td><?php echo $row['product_name']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td>₱<?php echo $row['price']; ?></td>
                            <td>₱<?php echo number_format($row['price'] * $row['quantity'], 2); ?></td>
                            <td>
                                <div class="action_btn">
                                    <a href="cart_remove.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-danger">Remove</a>
                                </div>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </form>
            </tbody>
        </table>
        <div class="text-center mt-3">
            <h5 id="totalAmount">Total Amount: ₱<?= number_format($totalAmount, 2) ?></h5>
        </div>
        <div class="text-center mt-3">
            <a href="cart_checkout.php" class="btn btn-outline-success mx-2">Checkout</a>
            <a href="cart_update.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-success mx-2">Edit</a>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script>
            $(document).ready(function () {
                function updateTotalAmount() {
                    $.ajax({
                        type: 'POST',
                        url: 'cart_updatetotal.php',
                        success: function (response) {
                            $('#totalAmount').html('Total Amount: ₱' + response);
                        }
                    });
                }
                updateTotalAmount();
                setInterval(updateTotalAmount, 5000);
            });
        </script>
    </div>    
    <?php include('includes/footer.html'); ?>
</body>
</html>