<?php
require_once 'config/connection.php';
$connection = mysqli_connect(HOST_NAME, USER_NAME, PASSWORD, DB_NAME);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['quantity'] as $id => $quantity) {
        $id = intval($id);
        $quantity = intval($quantity);

        // Adjust the SQL query to update quantity for each product
        $sql_update = "UPDATE cart SET quantity = $quantity WHERE id = $id";
        $result = mysqli_query($connection, $sql_update);

        // Check if the update was successful for each product
        if ($result !== true) {
            echo "Error updating quantity: " . mysqli_error($connection);
            exit(); // Exit on the first error
        }
    }

    // Redirect after updating all products
    header('location: cart.php');
    exit();
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
        table, th, tr {
            text-align: center;
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
        <h2>Shopping Cart</h2>
        <form method="POST" action="cart_update.php">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="10%">Picture</th>
                        <th width="20%">Product Name</th>
                        <th width="10%">Quantity</th>
                        <th width="20%">Price Details</th>
                        <th width="15%">Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM cart";
                    $result = mysqli_query($connection, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <tr>
                            <td><?php echo $row['product_path']; ?></td>
                            <td><?php echo $row['product_name']; ?></td>
                            <td><input type="number" id="quantity_<?php echo $row['id']; ?>" name="quantity[<?php echo $row['id']; ?>]" value="<?php echo $row['quantity']; ?>" required></td>
                            <td>₱<?php echo $row['price']; ?></td>
                            <td>₱<?php echo number_format($row['price'] * $row['quantity'], 2); ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
</body>

</html>
