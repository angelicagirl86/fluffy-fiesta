<?php
//include_once("config_local.php");
include_once("config.php");

/*
 * Vi organiserar sidan på sån sätt att vi har all processkod här i toppen av
 * sidan. All information som vi behöver processa hämtas från den nedre delen
 * av sidan där html formuläret finns.
 * Där finns namnen på formulärfälten som vi använder i php koden här upp för 
 * att processas.
*/

/*
 * Session start koden lägger vi till på samtliga sidor som ska vara skyddade
*/
session_start();
if(empty($_SESSION['email']))
{
 header("location:index.php");
}

echo "Welcome ".$_SESSION['name']."<br>";

if(isset($_POST['Submit'])) {    
    //$categoryId = $_POST['categoryId'];
    $categoryName = $_POST['categoryName'];
    
        
    // checking empty fields
    if(empty($categoryName)) { 

        //link to the previous page
        //echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";
        echo "<font color='red'>Category Name field is empty.</font><br/>";
    } else { 
        // if all the fields are filled (not empty) 
            
        //insert data to database        
        $sql = "INSERT INTO category(categoryId, categoryName) VALUES(:categoryId, :categoryName)";
        $query = $pdo->prepare($sql);
        
        $query->bindparam(':categoryId', $categoryId);
        $query->bindparam(':categoryName', $categoryName);
        $query->execute();
        
        
        //display success message
        echo "<font color='green'>Data added successfully.";
        echo "<br/><a href='category.php'>View Result</a>";
    }
}

/*
 * För att inte användaren ska behöva skriva siffror för en authorid, så vill vi
 * skapa en dropdown så att användare kan välja från namnlista från databasen
 * som ladda i en dropdown.
 * Nedanståend sql fråga är basen för den dropdown
*/
$categorySql = "SELECT * FROM category"; 
$categorySqlQuery = $pdo->prepare($categorySql);
$categorySqlQuery->execute();
        
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Add Category</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <a href="category.php">Category</a>
    <br/><br/>

    <form action="categoryAdd.php" method="post" name="form1">
        <table width="25%" border="0">
            <tr>
                <!-- Här lägger vi till det nya låten -->
                <td>Category Name</td>
                <td><input type="text" name="categoryName" /></td>
            </tr>
            
            <tr>
<td>


    

    
</select> 
</td> 
</tr> 
    <tr> 
        <td></td>
            <td><input type="submit" name="Submit" value="Add"></td>
            </tr>
        </table>
    </form>
<!--För att logga ut skickar vi användaren till en sida där sessionen avslutas
med session_destroy-->    
    <a href="logout.php">Logout</a>
    </body>
</html>