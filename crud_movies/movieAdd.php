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
    $categoryId = $_POST['categoryId'];
    $movieName = $_POST['movieName'];
    $movieDuration = $_POST['movieDuration'];
    $movieCopies = $_POST['movieCopies'];
    //$songLyricsby = $_POST['songLyricsby'];
    
        
    // checking empty fields
    if(empty($movieName) || empty($movieDuration) || empty($movieCopies)) { 

                
        if(empty($movieName)) {
            echo "<font color='red'>Movie Name field is empty.</font><br/>";
        }
        
        if(empty($movieDuration)) {
            echo "<font color='red'>Movie Duration field is empty.</font><br/>";
        }
        
        if(empty($movieCopies)) {
            echo "<font color='red'>Movie Copies field is empty.</font><br/>"; 
        }
        
        /*if(empty($songLyricsby)) { 
            echo "<font color='red'>Song Lyrics By field is empty.</font><br/>";    
        } */
        
        //link to the previous page
        echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";
    } else { 
        // if all the fields are filled (not empty) 
            
        //insert data to database        
        $sql = "INSERT INTO movie(categoryId, movieName, movieDuration, movieCopies) VALUES(:categoryId, :movieName, :movieDuration, :movieCopies)";
        $query = $pdo->prepare($sql);
        
        $query->bindparam(':categoryId', $categoryId);
        $query->bindparam(':movieName', $movieName);
        $query->bindparam(':movieDuration', $movieDuration);
        $query->bindparam(':movieCopies', $movieCopies);
        //$query->bindparam(':songLyricsby', $songLyricsby);
        $query->execute();
        
        // Alternative to above bindparam and execute
        // $query->execute(array(':joketext' => $joketext, ':authorId' => $authorId));
        
        //display success message
        echo "<font color='green'>Data added successfully.";
        echo "<br/><a href='movie.php'>View Result</a>";
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
        <title>Add Movie</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <a href="movie.php">Home</a>
    <br/><br/>

    <form action="movieAdd.php" method="post" name="form1">
        <table width="25%" border="0">
            <tr>
                <!-- Här lägger vi till det nya låten -->
                <td>Name</td>
                <td><input type="text" name="movieName" /></td>
                <td>Duration</td>
                <td><input type="text" name="movieDuration" /></td>
                <td>Copies</td>
                <td><input type="text" name="movieCopies" /></td>
                <!-- <td>Lyrics By</td>
                <td><input type="text" name="songLyricsby" /></td> -->
            </tr>
            
            <tr>
<td>Category</td> 
<td>

<!-- Vi skapar en dropdown som laddas med författare från databasen, så att inte
användare inte lägger till författare som inte existerar-->    
<select name="categoryId"> 
    
<?php

while($category = $categorySqlQuery->fetch()) { 
if ($category['categoryId'] == $categoryId) { 
//The author is currently associated to the joke, select it by default 
    //den raden kanske inte behövs
echo "<option value=\"{$category['categoryId']}\" selected>{$category['categoryName']}</option>"; 
} else { 
//The author is not currently associated to the joke 
echo "<option value=\"{$category['categoryId']}\">{$category['categoryName']}</option>"; 
} 
} 
?> 
    
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