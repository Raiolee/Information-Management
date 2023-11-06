<?php
require("config/connection.php");

$selectQuery = "Select * from product;";
$doSelectquery = mysqli_query($connection, $selectQuery);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
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
  <?php include('includes/header.html'); ?>
</head>

<body>

  <!-- cards/products -->
  <div class="d-flex justify-content-center">
    <div class="container">
      <div class="row gap-3">
        
      <?php
         while ($row = mysqli_fetch_array($doSelectquery))
         {
      ?>
        <div class="card my-3" style="width: 18rem;">
          <img src=<?php echo $row ['image_details'] ?> class="card-img-top mt-3 rounded" alt="...">
          <div class="card-body">
            <h5 class="card-title"><?php echo $row ['product_name'] ?></h5>
            <p class="card-text custom-line-clamp"><?php echo $row ['product_desc'] ?></p>
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
  <?php include('includes/footer.html'); ?>
</body>

</html>
