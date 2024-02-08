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

// Gets the user image
$getimage = mysqli_query($connection, "SELECT user_path FROM users WHERE user_id = " . $_SESSION['user']);
$userRow = mysqli_fetch_assoc($getimage);
$userimage = $userRow['user_path'];

// Gets the user full name
$getname = mysqli_query($connection, "SELECT CONCAT(user_fname, ' ', user_lname) AS full_name FROM users WHERE user_id = " . $_SESSION['user']);
$row = mysqli_fetch_assoc($getname);
$full_name = $row['full_name'];

$isOk = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form fields
    if (empty($_POST['product_name']) || empty($_POST['product_quantity']) || empty($_POST['product_price']) || empty($_POST['product_description']) || empty($_FILES['images'])) {
        echo "All fields are required.";
    } else {
        // Continue with form processing

        $product_name = mysqli_real_escape_string($connection, $_POST['product_name']);
        $product_quantity = mysqli_real_escape_string($connection, $_POST['product_quantity']);
        $product_price = mysqli_real_escape_string($connection, $_POST['product_price']);
        $product_description = mysqli_real_escape_string($connection, $_POST['product_description']);

        // File Upload
        if ($_FILES["images"]["error"] == UPLOAD_ERR_OK) {
            $targetDirectory = "images/";
            $targetFile = $targetDirectory . basename($_FILES["images"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            $check = getimagesize($_FILES["images"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            if (file_exists($targetFile)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }

            if ($_FILES["images"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            $allowedFormats = array("jpg", "jpeg", "png", "gif");
            if (!in_array($imageFileType, $allowedFormats)) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            } else {
                if (!file_exists($targetDirectory)) {
                    mkdir($targetDirectory, 0777, true);
                }

                if (move_uploaded_file($_FILES["images"]["tmp_name"], $targetFile)) {
                    // Get the user ID of the current session
                    $userId = $_SESSION['user'];

                    // Insert product with the user ID in the product table
                    $sql = "INSERT INTO product (product_name, product_quantity, product_price, product_path, supplier_user_id, product_description) 
                            VALUES (?, ?, ?, ?, ?, ?)";

                    $stmt = mysqli_prepare($connection, $sql);
                    mysqli_stmt_bind_param($stmt, "ssssss", $product_name, $product_quantity, $product_price, $targetFile, $userId, $product_description);
                    if (mysqli_stmt_execute($stmt)) {
                        ?>
                        <script>alert('Product added successfully.');</script>
                        <?php
                    } else {
                        ?>
                        <script>alert('Error adding product: <?php echo mysqli_error($connection); ?>');</script>
                        <?php
                    }
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        } else {
            echo "File upload error.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Config</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-2 vh-100 bg-dark text-white">
                <h2 class="m-2">Supplier</h2>
                <div class="rounded-circle overflow-hidden mb-3" style="width: 100px; height: 100px; margin: auto;">
                    <img class="img-fluid" src="<?php echo $userimage; ?>" alt="">
                </div>
                <div class="text-center">
                    <h6>
                        <?php echo $full_name; ?>
                    </h6>
                </div>
                <div class="ml-2">
                    <h5 class="mt-3">General</h5>
                    <ul class="list-unstyled">
                        <li class="m-4"><a href="dashboard_supplier.php" class="text-decoration-none text-light">Dashboard</a>
                        </li>
                        <li class="m-4"><a href="supplier_products.php" class="text-decoration-none text-light">Products</a></li>
                        <li class="m-4"><a href="supplier_contact_details.php" class="text-decoration-none text-light">Contact Details</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Header -->
            <div class="col-10">
                <?php include('includes/header.php') ?>

                <!-- Main Contents -->
                <!-- Add New Products Form -->
                <div class="container mt-3">
                    <h3>Add Products</h3>
                    <form action="supplier_products.php" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="product_name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name" required>
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
                        <button type="submit" class="btn btn-primary" name="update">Add Product</button>
                    </form>
                </div>

                <!-- Product table -->
                <div class="container mt-4 border-top p-3">
                    <h3>List of Products</h3>
                    <div class="col-10">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product Name</th>
                                    <th>Product Quantity</th>
                                    <th>Product Price</th>
                                    <th>Product Description </th>
                                    <th>Picture</th>
                                    <th colspan="2">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $userId = $_SESSION['user'];
                                $productsQuery = "SELECT * FROM product WHERE supplier_user_id = $userId";
                                $productsResult = mysqli_query($connection, $productsQuery);

                                if ($productsResult) {
                                    while ($productsRow = mysqli_fetch_assoc($productsResult)) {
                                ?>
                                        <tr>
                                            <td><?php echo $productsRow['product_id']; ?></td>
                                            <td><?php echo $productsRow['product_name']; ?></td>
                                            <td><?php echo $productsRow['product_quantity']; ?></td>
                                            <td><?php echo $productsRow['product_price']; ?></td>
                                            <td><?php echo $productsRow['product_description']; ?></td>
                                            <td><img src="<?php echo $productsRow['product_path']; ?>" width="55" height="50"></td>
                                            <td><a href="supplier_edit.php?id=<?php echo $productsRow['product_id']; ?>">Edit</a></td>
                                            <td><a href="supplier_edit.php?action=Delete&id=<?php echo $productsRow['product_id']; ?>">Delete</a></td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error alerts -->
    <?php if (isset($successMessage)): ?>
        <div class="alert alert-success position-fixed top-0 start-50 translate-middle-x">
            <?php echo $successMessage; ?>
        </div>

        <script>
            setTimeout(function () {
                document.querySelector('.alert').style.display = 'none';
            }, 3000); // Disappear after 3 seconds (3000 milliseconds)
        </script>
    <?php endif; ?>

    <!-- Display Error Message -->
    <?php if (isset($errorMessage)): ?>
        <div class="alert alert-danger position-fixed top-0 start-50 translate-middle-x">
            <?php echo $errorMessage; ?>
        </div>

        <script>
            setTimeout(function () {
                document.querySelector('.alert').style.display = 'none';
            }, 3000); // Disappear after 3 seconds (3000 milliseconds)
        </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
