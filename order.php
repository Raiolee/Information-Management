<?php
require_once 'config/connection.php';
error_reporting(E_ERROR | E_PARSE);
$errors = array();

// Check if the Cancel button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelOrder'])) {
    // Perform the truncate operation
    $truncateCart = mysqli_query($connection, "TRUNCATE TABLE Checkout_Order");

    if ($truncateCart) {
        echo "Order canceled successfully. Cart cleared.";
        // Add any additional logic after canceling the order if needed
    } else {
        echo "Error clearing cart: " . mysqli_error($connection);
    }
}

$id = isset($_GET['id']) ? $_GET['id'] : null;

// Validate and sanitize the ID from the URL
$id = is_numeric($id) ? (int)$id : 0;

$query = "SELECT * FROM cart WHERE id = '$id'";
$result = mysqli_query($connection, $query);
$row = mysqli_fetch_assoc($result);

// Calculate the total amount based on the products in the cart
$totalAmount = 0;
$query = "SELECT * FROM Checkout_Order";
$result = mysqli_query($connection, $query);

while ($orderRow = mysqli_fetch_assoc($result)) {
    $productId = $orderRow['order_id'];
    $quantityName = 'quantity_' . $productId;

    // Validate and sanitize the quantity value from the user input
    $quantity = isset($_POST[$quantityName]) ? (int)$_POST[$quantityName] : 0;

    // Ensure that the quantity is a non-negative integer
    $quantity = max(0, $quantity);

    $totalAmount += $orderRow['prices'] * $quantity;
}
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
        table,
        th,
        tr {
            text-align: center;
        }

        h2 {
            text-align: center;
            padding: 4%;
        }
    </style>
</head>

<body>
    <div class="container-md">
        <h2>Shipping</h2>
        <?php
        $query = "SELECT * FROM Checkout_Order";
        $result = mysqli_query($connection, $query);
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <p>Order Number: <?php echo ($row['order_id']); ?></p>
            <p>Customer Name: <?php echo ($row['full_name']); ?></p>
            <p>Address: <?php echo htmlspecialchars($row['address']); ?></p>
            <p>Order Date: <?php echo htmlspecialchars($row['order_date']); ?></p>
        <?php
        }
        ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM Checkout_Order";
                $result = mysqli_query($connection, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>₱<?php echo $row['prices']; ?></td>
                        <td>₱<?php echo number_format($row['prices'] * $row['quantity'], 2); ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>

        <!-- Cancel button and total amount display -->
        <form method="post" action="">
            <div class="d-flex justify-content-between mt-3">
                <button type="submit" class="btn btn-danger" name="cancelOrder">Cancel</button>
                <h5 id="totalAmount">Total Amount: ₱<?= number_format($totalAmount, 2) ?></h5>
            </div>
        </form>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            function updateTotalAmount() {
                $.ajax({
                    type: 'POST',
                    url: 'order_updatetota.php',
                    success: function (response) {
                        $('#totalAmount').html('Total Amount: ₱' + response);
                    }
                });
            }
            updateTotalAmount();
            setInterval(updateTotalAmount, 5000);
        });
    </script>
    <?php // Include additional scripts if needed ?>
</body>

</html>
