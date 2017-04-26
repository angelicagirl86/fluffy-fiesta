<?php 

// including the database connection file 
include_once("config.php");

//include_once("config_local.php");

/* Vi organiserar sidan på sån sätt att vi har all processkod här i toppen av
   sidan. All information som vi behöver processa hämtas från den nedre delen
   av sidan där html formuläret finns.
   Där finns namnen på formulärfälten som vi använder i php koden här upp för 
   att processas. */

/* Vi vill ta emot resultatet från föregående sida och med $_GET ta emot id för
   den raden vi ska redigera */
$albumID = $_GET['albumID'];

/* Vi använder värdet på id från föregående sida som vi fick från get och 
   skriver en sql fråga där vi använder :id för vara basis för en where fråga.
   Vi vill alltså bara presentera en enskild album baserat på den id vi
   sprarade från raden från förra sidan. */
$sql = "SELECT * FROM album WHERE albumID=:albumID";
$query = $pdo->prepare($sql); 
$query->execute(array(':albumID' => $albumID)); 

/* Resultatet av nedanstående kod kommer vi fylla ut i en html forumlär längre
   ner på sidan. */
while($row = $query->fetch()) 
{ 
$albumName = $row['albumName'];
$fk_artistID = $row['fk_artistID'];
}

/* På redigerings sidan så kommer vi en textruta för att en enskild album.
   Vi vill däremot inte att användaren ska behöva skriva en artistid för
   albumet. Förutom att möjligheten till misstförstånd blir större att hålla 
   koll pa en siffra så försämrar det avsevärt användarupplevelsen.
   Vi skapar en seperat fråga som kommer vara basis för en dropdown senare i 
   html formuläret. */
$artistSql = "SELECT * FROM artist";
$artistQuery = $pdo->prepare($artistSql);
$artistQuery->execute();


?> 
<?php 

/* Vi kontrollerar om vi har tryckt på uppdatera knappen som har namnet update
   i formuläret, i så fall så lagrar vi id och fälten Album och ArtistID i 
   respektive variabel, som ska finnas i vår db tabell. */
if(isset($_POST['update'])) 
{ 
$albumID = $_POST['albumID']; 

$albumName=$_POST['albumName']; 
$fk_artistID=$_POST['fk_artistID']; 


// checking empty fields


if(empty($albumName)) {
echo "<font color='red'>Album field is empty.</font><br/>"; 
    } else { 
    
/* Vi använder sql syntaxen för uppdateringar och skickar med id för raden.
   OBS att man i PDO använder platshållare (joketext=:joketext) där :joketext är
   namnet på platshållaren för att para ihop det som finns i  formuläret till
   databasen. Detta läggs till i variablen $sql */

$sql = "UPDATE album SET albumName=:albumName, fk_artistID=:fk_artistID WHERE albumID=:albumID";

/* Vi använder pdo objektets prepare metod som tar $sql som argument och sparar
   resultaet i variabeln $query */
$query = $pdo->prepare($sql); 

/* Sedan binder vi det som finns i platshållaren till variabeln */
$query->bindparam(':albumID', $albumID); 
$query->bindparam(':albumName', $albumName); 
$query->bindparam(':fk_artistID', $fk_artistID);

//vi använder det som nu finns i $query för att köra sql frågan 
$query->execute(); 

// Alternative to above bindparam and execute 
// $query->execute(array(':id' => $id, ':joketext' => $joketext)); 

// header används för att skicka tillbaka efter proccesn är klart till en sida
header("Location: index.php"); 
} 
}  
?> 


<!DOCTYPE html>
<!--   -->
<html>
    <head>
        <meta charset="UTF-8">
        <title>CRUD - edit</title>
    </head>
    <body>
        <!-- -->
    <!-- go back to home -->
<a href="index.php">Home</a> 
<br/><br/> 

<!-- post to send something, edit -->
<form name="form1" method="post" action="edit.php"> 
<table border="0"> 
    <!-- table row -->
<tr> 
    <!-- table data -->
<td>Album</td> 
<!-- Resultatet av vår sql fråga från rad34 lägger vi en textarea, man kan alltid
blanda html och php som ni ser, genom att flika in php taggar som start och slut-->
<td><textarea name="albumName" rows="6" cols="40" ><?php echo $albumName;?></textarea></td>
</tr> 
<tr> 
<td>Artist</td> 
<td>
<!-- För att användare inte ska behöva stoppa in siffror för en ArtistID så skapar
vi en dropdown, där resultatet av sql frågan från rad 40 $artistQuery stoppar in
i $artist-->    
<select name="fk_artistID"> 
    
<?php 
while($artist = $artistQuery->fetch()) { 
if ($artist['artistID'] == $fk_artistID) { 
/*
 * Vi använder id som vi har för att, som defualt visa den författaren som var
 * kopplat till ett viss skämt vald från föregående sida.
*/ 
echo "<option value=\"{$artist['artistID']}\" selected>{$artist['artistName']}</option>"; 
} else { 
/*
 * Skulle vi däremot vilj ändra artister till nåt annat det som vi fick från
 * förra sidan, så väljer vi det nu och också fångar upp id:et för den
 * artisten
*/ 
echo "<option value=\"{$artist['artistID']}\">{$artist['artistName']}</option>"; 
} 
} 
?> 
</select> 
</td> 
</tr> 

<tr>
<!-- Vi visar inte id för det albumet vi vill redigera -->    
<td><input type="hidden" name="albumID" value=<?php echo $_GET['albumID'];?></td> 
<td><input type="submit" name="update" value="Update"></td> 
</tr> 
</table> 
</form>
    </body>
</html>