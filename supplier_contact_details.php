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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="m-0">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-2 vh-100 bg-dark text-white">
                <h2 class="m-2">Supplier</h2>
                <div class="rounded-circle overflow-hidden mb-3" style="width: 100px; height: 100px; margin: auto;">
                    <img class="img-fluid" src="<?php echo $userimage ?>" alt="">
                </div>
                <div class="text-center">
                    <h6>
                        <?php echo $full_name; ?>
                    </h6>
                </div>
                <div class="ml-2">
                    <h5 class="mt-3">General</h5>
                    <ul class="list-unstyled">
                        <li class="m-4"><a href="dashboard_supplier.php"
                                class="text-decoration-none text-light">Dashboard</a></li>
                        <li class="m-4"><a href="supplier_products.php"
                                class="text-decoration-none text-light">Products</a>
                        </li>
                        <li class="m-4"><a href="supplier_contact_details.php"
                                class="text-decoration-none text-light">Contact Details</a></li>
                    </ul>
                </div>
            </div>

            <!-- Header -->
            <div class="col-10">
                <?php include('includes/header.php') ?>

                <!-- Main Contents -->
                <!-- Contact Details Form -->
                <div class="container mt-3">
                    <?php
                    // Fetch the details of the currently logged-in supplier
                    $supplierId = $_SESSION['user'];
                    $supplierDetailsQuery = "SELECT * FROM users WHERE user_id = $supplierId";
                    $supplierDetailsResult = mysqli_query($connection, $supplierDetailsQuery);

                    if ($supplierDetailsResult && mysqli_num_rows($supplierDetailsResult) > 0) {
                        $supplierDetailsRow = mysqli_fetch_assoc($supplierDetailsResult);
                        ?>
                        <h3>Contact Details</h3>
                        <form action="supplier_config.php" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="supplier_name" class="form-label">Supplier Name</label>
                                    <input type="text" class="form-control" id="supplier_name" name="supplier_name"
                                        value="<?php echo $supplierDetailsRow['user_fname'] . ' ' . $supplierDetailsRow['user_lname']; ?>"
                                        required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="supplier_mobile" class="form-label">Supplier Mobile</label>
                                    <input type="text" class="form-control" id="supplier_mobile" name="supplier_mobile"
                                        value="<?php echo $supplierDetailsRow['user_mobile']; ?>" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="supplier_gender" class="form-label">Supplier Gender</label>
                                    <input type="text" class="form-control" id="supplier_gender" name="supplier_gender"
                                        value="<?php echo $supplierDetailsRow['user_gender']; ?>" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="supplier_address" class="form-label">Supplier Address</label>
                                    <input type="text" class="form-control" id="supplier_address" name="supplier_address"
                                        value="<?php echo $supplierDetailsRow['user_address']; ?>" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="supplier_dob" class="form-label">Supplier Date of Birth</label>
                                    <input type="text" class="form-control" id="supplier_dob" name="supplier_dob"
                                        value="<?php echo $supplierDetailsRow['user_dob']; ?>" required>
                                </div>
                            </div>

                            <?php echo '<a href="edit_supplier_contact.php" class="btn btn-primary">Edit Details</a>'; ?>
                        </form>
                        <?php
                    }
                    ?>
                </div>

                </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    </div>