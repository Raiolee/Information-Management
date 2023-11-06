<?php

require("connection.php");

if (isset($_GET["product_id"]))
{
  $product_id = $_GET["product_id"];

  $selectQuery = "select * from product where product_id = '$product_id';";
  $doSelectquery = mysqli_query($connection, $selectQuery);
}

?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomePage</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- Header -->
  <?php include('includes/header.php'); ?>
</head>

<body>

<div class="d-flex justify-content-center">
    <div class="container">
      <div class="row gap-3">
          <div class="row">
          <?php
            while ($row = mysqli_fetch_array($doSelectquery))
              {
          ?>

        <div class="col-md-6">
          <div class="card my-3" style="width: 25rem;">
            <img src=<?php echo $row ['image_details']; ?> class="card-img-top mt-3 rounded" alt="...">
          </div>
        </div>

        <div class="col-md-6">
          <h1><?php echo $row ['product_name']; ?></h1>
          <p class="lead"><?php echo $row ['product_desc']; ?></p>
          
          <div class="row">
            <div class="col-md-6">
              <h4>Product Price: </h4>

              <div class="col-md-6">
              <p class="lead"><?php echo $row ['product_price']; ?> Pesos</p>
              </div>

          </div>
          </div>

          <div class="row">
          <a href="#"><button type="button" class="btn btn-primary btn-lg" style=" --bs-btn-font-size: 1rem;">Add to Cart</button></a>
          </div>

          </div>
        </div>

        <?php
           }
        ?>
          </div>

      </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
</body>

<footer>
<?php include('footer.html'); ?>
</footer>
</html>
