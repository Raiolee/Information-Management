<?php
require_once 'config/connection.php';

$connection = mysqli_connect(HOST_NAME, USER_NAME, PASSWORD, DB_NAME);

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

$query = "SELECT SUM(price * quantity) as totalAmount FROM cart";
$result = mysqli_query($connection, $query);

if ($result === false) {
    die("Error in SQL query: " . mysqli_error($connection));
}

$row = mysqli_fetch_assoc($result);

echo number_format($row['totalAmount'], 2);
?>

