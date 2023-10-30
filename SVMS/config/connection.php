<?php 
require 'constants.php';

$connection = mysqli_connect(HOST_NAME, USER_NAME, PASSWORD, DB_NAME);

    if (!$connection) { die("could not connect" . mysqli_connect_error() );}
?>