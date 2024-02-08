<?php
session_start();
require("config/connection.php");
// Checks if the user is a customer
if ($_SESSION['type'] != "Customer") {
  unset($_SESSION['user']);
  session_unset();
  session_destroy();
  header("Location: index.php");
  exit;
}

// Query to get all the products
$selectQuery = "SELECT * FROM product;";
$doSelectquery = mysqli_query($connection, $selectQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product List</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

  <!-- Style for homepage short description -->
  <style>
    .custom-line-clamp {
      display: -webkit-box;
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis;
      -webkit-line-clamp: 3;
    }
  </style>
  <!-- Header -->
  <?php include('includes/homepageheader.php'); ?>
</head>

<body>

  <!-- cards/products -->
  <div class="d-flex justify-content-center">
    <div class="container">
      <div class="row gap-3">
        <?php
        while ($row = mysqli_fetch_assoc($doSelectquery)) {
          $imagePath = isset($row['product_path']) ? $row['product_path'] : 'placeholder.jpg';
          $productDesc = isset($row['product_description']) ? $row['product_description'] : '';
          ?>
          <div class="card my-3" style="width: 18rem;">
            <img src="<?php echo $imagePath; ?>" class="card-img-top mt-3 rounded" alt="Product Image">
            <div class="card-body">
              <h5 class="card-title">
                <?php echo $row['product_name']; ?>
              </h5>
              <p class="card-text custom-line-clamp">
                <?php echo $productDesc; ?>
              </p>
              <p class="card-text">Price: $
                <?php echo $row['product_price']; ?>
              </p>
              <a href="product_detail.php?product_id=<?php echo $row['product_id']; ?>">
                <button class="btn btn-outline-success">View Details</button>
              </a>
            </div>
          </div>
          <?php
        }
        ?>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  <?php include('includes/footer.html'); ?>
</body>

</html>