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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplierId = $_SESSION['user'];
    
    // Get data from the form
    $newName = mysqli_real_escape_string($connection, $_POST['supplier_name']);
    $newMobile = mysqli_real_escape_string($connection, $_POST['supplier_mobile']);
    $newGender = mysqli_real_escape_string($connection, $_POST['supplier_gender']);
    $newAddress = mysqli_real_escape_string($connection, $_POST['supplier_address']);
    $newDob = mysqli_real_escape_string($connection, $_POST['supplier_dob']);

    // Update the user details in the database
    $updateQuery = "UPDATE users SET 
                    user_fname = '$newName', 
                    user_mobile = '$newMobile', 
                    user_gender = '$newGender', 
                    user_address = '$newAddress', 
                    user_dob = '$newDob'
                    WHERE user_id = $supplierId";

    $updateResult = mysqli_query($connection, $updateQuery);

    if ($updateResult) {
        // Redirect to the dashboard after successful update
        header("Location: dashboard_supplier.php");
        exit;
    } else {
        // Handle the case where the update fails
        echo "Update failed: " . mysqli_error($connection);
    }
} else {
    // Redirect if accessed directly without a form submission
    header("Location: dashboard_supplier.php");
    exit;
}
?>
