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
// Gets product id
$productId = $_GET['id'];
// Gets product details
$query = mysqli_query($connection, "SELECT * FROM product WHERE product_id = $productId");
$productDetails = mysqli_fetch_assoc($query);

if (!$productDetails) {
    // Product not found / redirect to supplier_products
    header("Location: supplier_products.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle update logic here
    $product_name = mysqli_real_escape_string($connection, $_POST['product_name']);
    $product_quantity = mysqli_real_escape_string($connection, $_POST['product_quantity']);
    $product_price = mysqli_real_escape_string($connection, $_POST['product_price']);
    $product_description = mysqli_real_escape_string($connection, $_POST['product_description']);

    // Update product details in the database using prepared statement
    $updateQuery = "UPDATE product 
                    SET product_name = ?, 
                        product_quantity = ?, 
                        product_price = ?, 
                        product_description = ? 
                    WHERE product_id = ?";

    $stmt = mysqli_prepare($connection, $updateQuery);
    mysqli_stmt_bind_param($stmt, "ssssi", $product_name, $product_quantity, $product_price, $product_description, $productId);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: supplier_products.php");
        exit;
    } else {
        // 
        echo 'Error Updating! Check Server';
    }
}

// Delete product logic
if (isset($_GET['action']) && $_GET['action'] == 'Delete' && isset($_GET['id'])) {
    $deleteQuery = "DELETE FROM product WHERE product_id = $productId";

    if (mysqli_query($connection, $deleteQuery)) {
        ?>
        <script>alert('Successfully Deleted');</script>
        <?php
        header("Location: supplier_products.php");
        exit;
    } else {
        ?>
        <script>alert('Error while Deleting!');</script>
    <?php
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="m-0">
<!-- Edit form -->
    <div class="container mt-3">
        <h3>Edit Product Details</h3>
        <form action="supplier_edit.php?id=<?php echo $productId; ?>" method="post">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="product_name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo $productDetails['product_name']; ?>">
                </div>
              <div class="col-md-3 mb-3">
                    <label for="product_quantity" class="form-label">Product Quantity</label>
                    <input type="text" class="form-control" id="product_quantity" name="product_quantity" required>
                </div>
              <div class="col-md-3 mb-3">
                    <label for="product_price" class="form-label">Product Price</label>
                    <input type="text" class="form-control" id="product_price" name="product_price" required>
                </div>
            <div class="col-md-3 mb-3">
                    <label for="product_description" class="form-label">Product Description</label>
                    <input type="text" class="form-control" id="product_description" name="product_description" required>
                </div>
            <div class="col-md-3 mb-3">
                    <label for="imageInput" class="form-label">Upload an Image:</label>
                    <input type="file" class="form-control" id="imageInput" name="images" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" name="update">Submit</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
