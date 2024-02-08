<?php
require 'config/connection.php';

$searchQuery = null; // Initialize the variable to avoid undefined variable warning

//Error Handling
if (isset($_POST["search"])) {
    $search = $_POST["searchBar"];
    $searchQuery = mysqli_query($connection, "SELECT * FROM product WHERE product_name LIKE '%$search%' OR product_description LIKE '%$search%';");

    // Check if the query was successful
    if (!$searchQuery) {
        die("Query failed: " . mysqli_error($connection));
    }
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
    <?php include('includes/homepageheader.php'); ?>
</head>

<body>
    <!-- cards/products -->
    <div class="d-flex justify-content-center">
        <div class="container">
            <div class="row gap-3">

                <?php
                // Check if $searchQuery is not null before fetching data
                while ($row = ($searchQuery ? mysqli_fetch_array($searchQuery) : null)) {
                ?>
                    <div class="card my-3" style="width: 18rem;">
                        <img src=<?php echo $row['product_path'] ?> class="card-img-top mt-3 rounded" alt="...">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['product_name'] ?></h5>
                            <p class="card-text"><?php echo $row['product_description'] ?></p>
                            <a href="product_detail.php?product_id=<?php echo $row['product_id']; ?>" class="btn btn-primary">Product Details</a>
                        </div>
                    </div>

                <?php
                }
                ?>

            </div>
        </div>

    </div>
  <?php include('includes/footer.html'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
  
</body>

</html>