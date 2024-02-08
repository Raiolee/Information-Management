<?php
require 'config/connection.php';

session_start();
$customer_id = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitOrder'])) {
    $selectCartQuery = "SELECT * FROM cart";
    $resultCart = mysqli_query($connection, $selectCartQuery);

    // Disable foreign key checks
    mysqli_query($connection, "SET FOREIGN_KEY_CHECKS=0");

    $totalAmount = 0; // Initialize totalAmount

    // Check if the cart is empty
    if (mysqli_num_rows($resultCart) > 0) {
        while ($row = mysqli_fetch_assoc($resultCart)) {
            $supplier_id = 1;

            $insertOrderQuery = "INSERT INTO Checkout_Order (product_name, quantity, prices, total_amount, customer_id, supplier_id, full_name, address) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
            $stmtInsertOrder = mysqli_prepare($connection, $insertOrderQuery);

            $price = $row['price'];
            $total_amount = $price * $row['quantity'];
            $quantity = $row['quantity'];

            $full_name = $_POST['fullName'];
            $address = $_POST['address'];

            mysqli_stmt_bind_param($stmtInsertOrder, "ssssssss", $row['product_name'], $quantity, $price, $total_amount, $customer_id, $supplier_id, $full_name, $address);

            if (mysqli_stmt_execute($stmtInsertOrder)) {
                $totalAmount += $total_amount; // Accumulate totalAmount
            } else {
                echo "Error adding product to Checkout_Order: " . mysqli_error($connection);
            }
            
            // Close the prepared statement
            mysqli_stmt_close($stmtInsertOrder);
        }

        // Enable foreign key checks
        mysqli_query($connection, "SET FOREIGN_KEY_CHECKS=1");

        // Clear the cart after checkout
        $truncateCartQuery = "TRUNCATE TABLE cart";
        $truncateCart = mysqli_query($connection, $truncateCartQuery);

        if ($truncateCart) {
            echo "Order submitted successfully. Cart cleared.";
        } else {
            echo "Error clearing cart: " . mysqli_error($connection);
        }
    } else {
        echo "The cart is empty.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <?php include('includes/homepageheader.php'); ?>
    <style>
        table,
        th,
        tr {
            text-align: left;
        }

        h2 {
            text-align: center;
            background-color: #efefef;
            padding: 2%;
        }

        table th {
            background-color: #efefef;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Checkout</h2>
        <form method="post" action="">
            <table class="table">
                <thead>
                    <tr>
                        <th width="20%"></th>
                        <th width="20%">Product Name</th>
                        <th width="20%">Quantity</th>
                        <th width="20%">Price Details</th>
                        <th width="20%">Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $selectCartItemsQuery = "SELECT * FROM cart";
                    $resultCartItems = mysqli_query($connection, $selectCartItemsQuery);
                    while ($row = mysqli_fetch_assoc($resultCartItems)) {
                    ?>
                        <tr>
                            <td><?php echo $row['product_name']; ?></td>
                            <td><?php echo $row['product_name']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td>₱<?php echo $row['price']; ?></td>
                            <td>₱<?php echo number_format($row['price'] * $row['quantity'], 2); ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <table class="table">
                <thead>
                    <tr>
                        <th width="20%"></th>
                        <th width="20%"></th>
                        <th width="20%"></th>
                        <th width="20%"></th>
                        <th width="20%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Free Shipping Fee: ₱0.00</td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td></td>
                        <td></td>
                        <td><h7 id="totalAmount">Total Amount: ₱<?= number_format($totalAmount, 2) ?></h7></td>
                    </tr>
                </tbody>
            </table>
            
        
        <div class="container">
            <h2>Shipping Information</h2>
            <form method="post" action="cart_processShipping.php">
                <div class="mb-3">
                    <label for="fullName" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="fullName" name="fullName" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="1" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="paymentMethod" class="form-label">Payment Method</label>
                    <select class="form-select" id="paymentMethod" name="paymentMethod" required>
                        <option value="cashOnDelivery">Cash on Delivery</option>
                        <option value="gcash">Gcash</option>
                        <option value="paypal">Paypal</option>
                    </select>
                </div>
        <div class="text-center mt-3">
            <button type="submit" class="btn btn-outline-success mx-2" name="submitOrder">Submit Order</button>
        </div>
        </form>
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
    <?php include('includes/footer.html'); ?>
</body>
</html>

