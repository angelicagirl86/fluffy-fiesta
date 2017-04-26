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
    $cdID = $_POST['cdID'];
    $songTitle = $_POST['songTitle'];
    $songDuration = $_POST['songDuration'];
    $songGenre = $_POST['songGenre'];
    $songLyricsby = $_POST['songLyricsby'];
    
        
    // checking empty fields
    if(empty($songTitle) || empty($songDuration) || empty($songGenre) || empty($songLyricsby)) { 

                
        if(empty($songTitle)) {
            echo "<font color='red'>Song Title field is empty.</font><br/>";
        }
        
        if(empty($songDuration)) {
            echo "<font color='red'>Song Duration field is empty.</font><br/>";
        }
        
        if(empty($songGenre)) {
            echo "<font color='red'>Song Genre field is empty.</font><br/>"; 
            
        }
        
        if(empty($songLyricsby)) { 
            echo "<font color='red'>Song Lyrics By field is empty.</font><br/>"; 
            
        } 
        
        //link to the previous page
        echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";
    } else { 
        // if all the fields are filled (not empty) 
            
        //insert data to database        
        $sql = "INSERT INTO song(cdID, songTitle, songDuration, songGenre, songLyricsby) VALUES(:cdID, :songTitle, :songDuration, :songGenre, :songLyricsby)";
        $query = $pdo->prepare($sql);
        
        $query->bindparam(':cdID', $cdID);
        $query->bindparam(':songTitle', $songTitle);
        $query->bindparam(':songDuration', $songDuration);
        $query->bindparam(':songGenre', $songGenre);
        $query->bindparam(':songLyricsby', $songLyricsby);
        $query->execute();
        
        // Alternative to above bindparam and execute
        // $query->execute(array(':joketext' => $joketext, ':authorId' => $authorId));
        
        //display success message
        echo "<font color='green'>Data added successfully.";
        echo "<br/><a href='song.php'>View Result</a>";
    }
}

/*
 * För att inte användaren ska behöva skriva siffror för en authorid, så vill vi
 * skapa en dropdown så att användare kan välja från namnlista från databasen
 * som ladda i en dropdown.
 * Nedanståend sql fråga är basen för den dropdown
*/
$cdSql = "SELECT * FROM cd"; 
$cdSqlQuery = $pdo->prepare($cdSql);
$cdSqlQuery->execute();
        
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Add Song</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <a href="song.php">Home</a>
    <br/><br/>

    <form action="add_form.php" method="post" name="form1">
        <table width="25%" border="0">
            <tr>
                <!-- Här lägger vi till det nya låten -->
                <td>Title</td>
                <td><input type="text" name="songTitle" /></td>
                <td>Duration</td>
                <td><input type="text" name="songDuration" /></td>
                <td>Genre</td>
                <td><input type="text" name="songGenre" /></td>
                <td>Lyrics By</td>
                <td><input type="text" name="songLyricsby" /></td>
            </tr>
            
            <tr>
<td>CD</td> 
<td>

<!-- Vi skapar en dropdown som laddas med författare från databasen, så att inte
användare inte lägger till författare som inte existerar-->    
<select name="cdID"> 
<?php


while($cd = $cdSqlQuery->fetch()) { 
if ($cd['cdID'] == $cdID) { 
//The author is currently associated to the joke, select it by default 
    //den raden kanske inte behövs
echo "<option value=\"{$cd['cdID']}\" selected>{$cd['cdTitle']}</option>"; 
} else { 
//The author is not currently associated to the joke 
echo "<option value=\"{$cd['cdID']}\">{$cd['cdTitle']}</option>"; 
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