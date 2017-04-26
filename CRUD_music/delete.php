<?php
//including the database connection file
include_once("config.php");
//include_once("config_local.php");
 
//getting id of the data from url
$albumID = $_GET['albumID'];
 
//deleting the row from table
$sql = "DELETE FROM album WHERE albumID=:albumID";
$query = $pdo->prepare($sql);
$query->execute(array(':albumID' => $albumID));
 
//redirecting to the display page (index.php in our case)
header("Location:index.php");
?>
