<!DOCTYPE html>

<?php
//including the database connection file
//include_once("config_local.php");
include_once("config.php");
/*
 * Vi kontrollerar alltid att ignen har kommit till en sida som är skyddad, i 
 * detta fall gör vi med det med att kontrollera om sessionen för epost inte är
 * tomt, så fall så skickas den personen tillbaka till start sidan. 
*/
session_start();
if(empty($_SESSION['email']))
{
 header("location:index.php");
}
/*
 * Annars blir vi insläppta med ett hälsningsfras och namnet på användaren som
 * vi sparade från inloggningssidan.
*/
echo "Welcome ".$_SESSION['name']; 


 
/*Vi använder pdo objekets metod query och sparar resultatet i $result 
 * (via vår store procedure) Bygga alltid sp där det finns en primarykey id, 
 * även om den inte visas i raden, så måste vi ha en id som referens om vi sen
 * ska redigera eller ta bort en rad.
 */

$result = $pdo->query("call sp_show_category");



?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Category</title>
    </head>
    <body>
<!-- Länk till lägga till nya ... -->        
        <a href="categoryAdd.php">Add New Category</a><br/><br/>
        <a href="movie.php">Home</a><br/><br/>
        
    <table width='80%' border=0>
 
    <tr bgcolor='#CCCCCC'>
        <td>Category Name</td>
        <td>Update</td>
    </tr>
    <?php
/*
 * vi behöver inte skriva $authorQuery->fetch(FETCH_ASSOC) då vi i vår databas
 * kopplinga redan har angett att det är den metoden vi har satt som default.
 * 
 * Vi fetch så loopar vi genom alla rader från sp och matar in i varje rad i 
 * tabellen.
 * 
 * För varje rad vi får ut, så tar vi reda på id (vår primary key i tabllen) och 
 * sprarar den i varibeln $row. Denna id använder vi sen som basis för att
 * redigera en enskild rad som bär med sig det värdet till edit sidan, eller tar
 * det värde för att radera en ensild rad i tabellen.
*/    
    while($row = $result->fetch()) {         
        echo "<tr>";
        echo "<td>".$row['categoryName']."</td>";
        //namnet på primary key
        echo "<td><a href=\"categoryEdit.php?id=$row[categoryId]\">Edit</a> | <a href=\"categoryDelete.php?id=$row[categoryId]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a></td>";        
    }
    
    ?>
<!--För att logga ut skickar vi användaren till en sida där sessionen avslutas
med session_destroy-->    
    <a href="logout.php">Logout</a>
    </table>
    </body>
</html>
