<?php
//including the database connection file
include_once("config.php");
//include_once("config_local.php");
 
//getting id of the data from url
$id = $_GET['id'];
 
//deleting the row from table
$sql = "DELETE FROM movie WHERE movieId=:movieId";
$query = $pdo->prepare($sql);
$query->execute(array(':movieId' => $id));
 
//redirecting to the display page (index.php in our case)
header("Location:movie.php");
?>