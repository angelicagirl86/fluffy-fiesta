<?php 
// including the database connection file 
include_once("config.php");
//include_once("config_local.php");

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

/*
 * Vi vill ta emot resultatet från föregående sida och med $_GET ta emot id för
 * den raden vi ska redigera
*/
$id = $_GET['id'];

/*
 * vi använder värdet på id från föregående sida som vi fick från get och 
 * skriver en sql fråga där vi anvnder :id för vara basis för en where fråga.
 * Vi vill alltså bara presentera ett enskild skämt baserat på den id vi
 * sprarade från raden från förra sidan.
 */
$sql = "SELECT * FROM song WHERE songID=:songID"; 
$query = $pdo->prepare($sql); 
$query->execute(array(':songID' => $id)); 
/*
 * Resultatet av nedanstående kod kommer vi fylla ut i en html forumlär längre
 * ner på sidan. 
*/
while($row = $query->fetch()) 
{ 
$songTitle = $row['songTitle']; 
$songDuration = $row['songDuration'];
$songGenre = $row['songGenre'];
$songLyricsby = $row['songLyricsby'];
}

/*
 * På redigerings sidan så kommer vi en textruta för att en enskild skämt.
 * Vi vill däremot inte att användaren ska behöva skriva en authorid för
 * skämtet. Förutom att möjligheten till misstförstånd blir större att hålla 
 * koll pa en siffra så försämrar det avsevärt användarupplevelsen.
 * Vi skapar en seperat fråga som kommer vara basis för en dropdown senare i 
 * html formuläret
*/
//prepare för dropdown
$cdSql = "SELECT * FROM cd"; 
$cdSqlQuery = $pdo->prepare($cdSql); 
$cdSqlQuery->execute();


?> 
<?php 

/*
 * vi kontrollerar om vi har tryckt på uppdatera knappen som har namnet update
 * i formuläret, i så fall så lagrar vi id och fälten joketext och authorid i 
 * respektive variabel, som ska finnas i vår db tabell.
*/
if(isset($_POST['update'])) 
{ 
$id = $_POST['id']; 

$songTitle=$_POST['songTitle']; 
$songDuration=$_POST['songDuration'];
$songGenre=$_POST['songGenre'];
$songLyricsby=$_POST['songLyricsby']; 


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

} else { 
/*
 * vi använder sql syntaxen för uppdateringar och skickar med id för raden.
 * OBS att man i PDO använder platshållare (joketext=:joketext) där :joketext är
 * namnet på platshållaren för att para ihop det som finns i  formuläret till
 *  databasen. Detta läggs till i variablen $sql
*/

$sql = "UPDATE song SET songTitle=:songTitle, songDuration=:songDuration, songGenre=:songGenre, songLyricsby=:songLyricsby WHERE songID=:songID";

/*
 * vi använder pdo objektets prepare metod som tar $sql som argument och sparar
 * resultaet i variabeln $query
*/
$query = $pdo->prepare($sql); 
/*Sedan binder vi det som finns i platshållaren till variabeln*/
$query->bindparam(':songID', $id); 
$query->bindparam(':songTitle', $songTitle); 
$query->bindparam(':songDuration', $songDuration);
$query->bindparam(':songGenre', $songGenre);
$query->bindparam(':songLyricsby', $songLyricsby);
//vi använder det som nu finns i $query för att köra sql frågan 
$query->execute(); 

// Alternative to above bindparam and execute 
// $query->execute(array(':id' => $id, ':joketext' => $joketext)); 

//header används för att skicka tillbaka efter proccesn är klart till en sida
header("Location: song.php"); 
} 
}  
?> 
<!DOCTYPE html> 
<!-- 
To change this license header, choose License Headers in Project Properties. 
To change this template file, choose Tools | Templates 
and open the template in the editor. 
--> 
<html> 
<head> 
<meta charset="UTF-8"> 
<title>Edit</title> 
</head> 
<body> 
<a href="song.php">Home</a> 
<br/><br/> 

<form name="form1" method="post" action="edit.php"> 
<table border="0"> 
<tr> 
<td>Song</td> 
<!-- Resultatet av vår sql fråga från rad34 lägger vi en input, man kan alltid
blanda html och php som ni ser, genom att flika in php taggar som start och slut-->
<td><input type="text" name="songTitle" value="<?php echo $songTitle;?>" ></td>
<td><input type="text" name="songDuration" value="<?php echo $songDuration;?>" ></td>
<td><input type="text" name="songGenre" value="<?php echo $songGenre;?>" ></td>
<td><input type="text" name="songLyricsby" value="<?php echo $songLyricsby;?>" ></td>
</tr> 
<tr> 
<td>CD</td> 
<td>
<!-- För att användare ine ska behöva stoppa in siffror för en cdID så skapar
vi en dropdown, där resultatet av sql frågan från rad 47 $cdQuery stoppar in
i $cd-->    
<select name="cdID"> 
<?php 
while($cd = $cdSqlQuery->fetch()) { 
if ($cd['cdID'] == $cdID) { 
/*
 * Vi använder id som vi har för att, som defualt visa den författaren som var
 * kopplat till ett viss skämt vald från föregående sida.
*/ 
echo "<option value=\"{$cd['cdID']}\" selected>{$cd['cdTitle']}</option>"; 
} else { 
/*
 * Skulle vi däremot vilj ändra författaren till nåt annat det som vi fick från
 * förra sidan, så väljer vi det nu och också fångar upp id:et för den
 * författaren
*/ 
echo "<option value=\"{$cd['cdID']}\">{$cd['cdTitle']}</option>"; 
} 
} 
?> 
</select> 
</td> 
</tr> 

<tr>
<!-- Vi visar inte id för den låten vi vill redigera -->    
<td><input type="hidden" name="id" value=<?php echo $_GET['id'];?></td> 
<td><input type="submit" name="update" value="Update"></td> 
</tr> 
</table> 
</form>
<!--För att logga ut skickar vi användaren till en sida där sessionen avslutas
med session_destroy--> 
<a href="logout.php">Logout</a>
    </body>
</html>
