<?php
require("connection.php");

if (isset($_POST["search"]))
{
    $search = $_POST["searchBar"];

    $searchQuery = "select * from product where product_name like '%$search%' or product_descShort like '%$search%';";
    $doSearchQuery = mysqli_query($connection, $searchQuery);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HomePage</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <!-- Header -->
  <?php include('inlcudes/header.html'); ?>
</head>



<body>
  <!-- cards/products -->
  <div class="d-flex justify-content-center">
    <div class="container">
      <div class="row gap-3">
        
      <?php
         while ($row = mysqli_fetch_array($doSearchQuery))
         {
      ?>
        <div class="card my-3" style="width: 18rem;">
          <img src=<?php echo $row ['image_details'] ?> class="card-img-top mt-3 rounded" alt="...">
          <div class="card-body">
            <h5 class="card-title"><?php echo $row ['product_name'] ?></h5>
            <p class="card-text"><?php echo $row ['product_descShort'] ?></p>
            <a href="product_detail.php?product_id=<?php echo $row['product_id']; ?>" class="btn btn-primary">Go somewhere</a>
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
  <?php include('footer.html'); ?>
</body>

</html>
