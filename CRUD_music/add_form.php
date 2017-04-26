<?php

//include_once("config_local.php");
include_once("config.php");

/* Vi organiserar sidan på sån sätt att vi har all processkod här i toppen av
   sidan. All information som vi behöver processa hämtas från den nedre delen
   av sidan där html formuläret finns.
   Där finns namnen på formulärfälten som vi använder i php koden här upp för 
   att processas. */

if(isset($_POST['Submit'])) {    
    $albumName = $_POST['albumName'];
    $fk_artistID = $_POST['fk_artistID'];
        
    // checking empty fields
    if(empty($albumName) || empty($fk_artistID)) {
                
        if(empty($albumName)) {
            echo "<font color='red'>Album field is empty.</font><br/>";
        }
        
        if(empty($fk_artistID)) {
            echo "<font color='red'>Artist field is empty.</font><br/>";
        }
        
        //link to the previous page
        echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";
    } else { 
        // if all the fields are filled (not empty) 
            
        //insert data to database        
        $sql = "INSERT INTO album(albumName, fk_artistID) VALUES(:albumName, :fk_artistID)";
        $query = $pdo->prepare($sql);
                
        $query->bindparam(':albumName', $albumName);
        $query->bindparam(':fk_artistID', $fk_artistID);
        $query->execute();
        
        // Alternative to above bindparam and execute
        // $query->execute(array(':joketext' => $joketext, ':authorId' => $authorId));
        
        //display success message
        echo "<font color='green'>Data added successfully.";
        echo "<br/><a href='index.php'>View Result</a>";
    }
}

/*
 * För att inte användaren ska behöva skriva siffror för en authorid, så vill vi
 * skapa en dropdown så att användare kan välja från namnlista från databasen
 * som ladda i en dropdown.
 * Nedanståend sql fråga är basen för den dropdown
*/
$artistSql = "SELECT * FROM artist"; 
$artistQuery = $pdo->prepare($artistSql); 
$artistQuery->execute(); 
        
?>

<!DOCTYPE html>

<html>
    <head>
        <title>CRUD app - add form</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <a href="index.php">Home</a>
    <br/><br/>

    <form action="add_form.php" method="post" name="form1">
        <table width="25%" border="0">
            <tr> 
                <td>Album</td>
<!-- Här lägger vi till det nya albumet -->                
                <td><td><textarea name="albumName" rows="6" cols="40" ></textarea></td>
            </tr>
            
            <tr> 
<td>Artist</td> 
<td>

<!-- Vi skapar en dropdown som laddas med artister från databasen, så att inte
användare inte lägger till artister som inte existerar-->    
<select name="fk_artistID"> 
<?php

$fk_artistID="";
while($artist = $artistQuery->fetch()) { 
if ($artist['artistID'] == $fk_artistID) { 
//The artist is currently associated to the album, select it by default 
echo "<option value=\"{$artist['artistID']}\" selected>{$artist['artistName']}</option>"; 
} else { 
//The author is not currently associated to the joke 
echo "<option value=\"{$artist['artistID']}\">{$artist['artistName']}</option>"; 
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
    </body>
</html>



