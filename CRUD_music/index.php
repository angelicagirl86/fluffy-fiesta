<!DOCTYPE html>
<?php

//including the database connection file
//include_once("config_local.php");
include_once("config.php");
 
/* Vi använder pdo objektets metod query och sparar resultatet i $result 
   (via vår store procedure) Bygga alltid sp där det finns en primarykey id, 
   även om den inte visas i raden, så måste vi ha en id som referens om vi sen
   ska redigera eller ta bort en rad. */

//anropa stored procedure
//pdo är ett object och query en metod
$result = $pdo->query("call sp_show_all_albums_final");

echo date("h:i:sa") . "<br>" . "<br>";
echo "PHP Inlämning 3 - CRUD app" . "<br>" . "<br>";

?>


<html>
    <head>
        <meta charset="UTF-8">
        <title>CRUD app - Inlämning 3</title>
    </head>
    <body>
        
        <!-- Länk till lägga till nya album -->        
        <a href="add_form.php">Add New Albums</a><br/><br/>  
 
     <!-- css code --> 
    <table width='80%' border=0>
 
    <tr bgcolor='#CCCCCC'>
        <td>Album</td>
        <td>Artist</td>
        <td>Update</td>
    </tr>
    <?php
    
/* Vi behöver inte skriva $authorQuery->fetch(FETCH_ASSOC) då vi i vår databas
   kopplinga redan har angett att det är den metoden vi har satt som default.
  
   Vi fetch så loopar vi genom alla rader från sp och matar in i varje rad i 
   tabellen.
  
   För varje rad vi får ut, så tar vi reda på id (vår primary key i tabllen) och 
   sprarar den i varibeln $row. Denna id använder vi sen som basis för att
   redigera en enskild rad som bär med sig det värdet till edit sidan, eller tar
   det värde för att radera en ensild rad i tabellen. */    
    
   //fetch metod som finns i config.php filen
   //loopa rad för rad
   //row och joined namnet från stored procedure (AS ...)
    while($row = $result->fetch()) {         
        echo "<tr>";
        echo "<td>".$row['albumName']."</td>";
        echo "<td>".$row['artistName']."</td>";
        //två länkar
        //vi behöver AlbumID för att veta vilken album man menar
        //id that is primary key in the many table
        echo "<td><a href=\"edit.php?albumID=$row[albumID]\">Edit</a> | <a href=\"delete.php?albumID=$row[albumID]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a></td>";        
    }
    
    ?>
    </table>
    </body>
</html>
