<?php 
	require_once 'config/connection.php';
	$connection = mysqli_connect(HOST_NAME, USER_NAME, PASSWORD, DB_NAME);

	$id = $_GET['id'];

	$sql_delete = "delete from cart where id='$id'";
	$result = mysqli_query($connection, $sql_delete);

	if ($result == true) {
		header('location: cart.php');
		exit();
	}
?>
