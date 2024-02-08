<?php
require 'config/connection.php';

session_start(); 
// Gets the customer_id
$customer_id = $_SESSION['user'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {

  // Use prepared statement to prevent SQL injection
  $selectQuery = "SELECT * FROM product WHERE product_id = ?";
  $stmtSelect = mysqli_prepare($connection, $selectQuery);
  mysqli_stmt_bind_param($stmtSelect, "s", $_GET['product_id']);
  mysqli_stmt_execute($stmtSelect);
  $result = mysqli_stmt_get_result($stmtSelect);

  if ($row = mysqli_fetch_assoc($result)) {
    // Insert into the cart table
    $insertQuery = "INSERT INTO cart (customer_id, product_name, quantity, price, product_path) 
                        VALUES (?, ?, 1, ?, ?)";

    $stmtInsert = mysqli_prepare($connection, $insertQuery);

    // Assuming total amount is the product price for simplicity
    $total_amount = $row['product_price'];

    // Disable foreign key checks
    mysqli_query($connection, "SET FOREIGN_KEY_CHECKS=0");

    mysqli_stmt_bind_param($stmtInsert, "ssss", $customer_id, $row['product_name'], $total_amount, $row['product_path']);

    if (mysqli_stmt_execute($stmtInsert)) {
      echo "Product added to cart successfully.";
    } else {
      echo "Error adding product to cart: " . mysqli_error($connection);
    }

    // Enable foreign key checks
    mysqli_query($connection, "SET FOREIGN_KEY_CHECKS=1");

    // Close the prepared statement
    mysqli_stmt_close($stmtInsert);
  } else {
    echo "Product not found.";
  }
}

if (isset($_GET["product_id"])) {
  $product_id = $_GET["product_id"];

  // Use prepared statement to prevent SQL injection
  $selectQuery = "SELECT * FROM product WHERE product_id = ?";
  $stmt = mysqli_prepare($connection, $selectQuery);
  mysqli_stmt_bind_param($stmt, "s", $product_id);
  mysqli_stmt_execute($stmt);
  $doSelectquery = mysqli_stmt_get_result($stmt);
}

// Check if the product_id is set before accessing it
if (isset($_GET["product_id"])) {
  $product_id = $_GET["product_id"];

  $selectSupplier = "SELECT p.product_id, p.product_name, p.product_quantity, p.product_price, p.product_description, p.product_path, u.user_id, u.user_fname, u.user_lname FROM product p JOIN users u ON p.supplier_user_id = u.user_id WHERE p.product_id = ?";
  $stmtSupplier = mysqli_prepare($connection, $selectSupplier);
  mysqli_stmt_bind_param($stmtSupplier, "s", $product_id);
  mysqli_stmt_execute($stmtSupplier);
  $getSupplier = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtSupplier));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Details</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

  <!-- Header -->
  <?php include('includes/homepageheader.php'); ?>
</head>

<body>
<!-- Main Content -->
<!-- Product Details -->
  <div class="container my-5">
    <?php
    while ($row = mysqli_fetch_array($doSelectquery)) {
      ?>
      <div class="row">
        <div class="col-md-6">
          <div class="card my-3">
            <img src="<?php echo $row['product_path']; ?>" class="card-img-top mt-3 rounded" alt="...">
          </div>
        </div>

        <div class="col-md-6">
          <blockquote class="blockquote">
            <h1>
              <?php echo $row['product_name']; ?>
            </h1>
            <div class="d-flex align-items-start">
              <label for="product-description" class="me-2">Product Description:</label>
              <p class="lead" id="product-description">
                <?php echo $row['product_description']; ?>
              </p>
            </div>
          </blockquote>

          <div class="row my-4">
            <blockquote class="blockquote">
              <label for="supplier-name">Supplier:</label>
              <p class="d-flex">
                <?php echo $getSupplier['user_fname'] . ", " . $getSupplier['user_lname']; ?>
              </p>
            </blockquote>
          </div>

          <div class="row my-4">
            <div class="col-md-6">
              <h4>Product Price: </h4>
              <p class="lead">₱
                <?php echo $row['product_price']; ?>
              </p>
            </div>
          </div>

          <div class="row">
            <form method="POST" action="">
              <input type="hidden" name="add_to_cart" value="1">
              <input type="hidden" name="product_name" value="<?php echo $row['product_name']; ?>">
              <button type="submit" class="btn btn-primary btn-lg" style="--bs-btn-font-size: 1rem;">
                Add to Cart
              </button>
            </form>
          </div>
        </div>
      </div>
      <?php
    }
    ?>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
</body>

<footer>
  <?php include('includes/footer.html'); ?>
</footer>

</html>