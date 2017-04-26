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
$sql = "SELECT * FROM movie WHERE movieId=:movieId"; 
$query = $pdo->prepare($sql); 
$query->execute(array(':movieId' => $id)); 
/*
 * Resultatet av nedanstående kod kommer vi fylla ut i en html forumlär längre
 * ner på sidan. 
*/
while($row = $query->fetch()) 
{ 
$movieName = $row['movieName']; 
$movieDuration = $row['movieDuration'];
$movieCopies = $row['movieCopies'];
//$songLyricsby = $row['songLyricsby'];
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
$categorySql = "SELECT * FROM category"; 
$categorySqlQuery = $pdo->prepare($categorySql); 
$categorySqlQuery->execute();


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

$movieName=$_POST['movieName']; 
$movieDuration=$_POST['movieDuration'];
$movieCopies=$_POST['movieCopies'];
//$songLyricsby=$_POST['songLyricsby']; 


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
/*
if(empty($songLyricsby)) { 
echo "<font color='red'>Song Lyrics By field is empty.</font><br/>"; 
} */

} else { 
/*
 * vi använder sql syntaxen för uppdateringar och skickar med id för raden.
 * OBS att man i PDO använder platshållare (joketext=:joketext) där :joketext är
 * namnet på platshållaren för att para ihop det som finns i  formuläret till
 *  databasen. Detta läggs till i variablen $sql
*/

$sql = "UPDATE movie SET movieName=:movieName, movieDuration=:movieDuration, movieCopies=:movieCopies WHERE movieId=:movieId";

/*
 * vi använder pdo objektets prepare metod som tar $sql som argument och sparar
 * resultaet i variabeln $query
*/
$query = $pdo->prepare($sql); 
/*Sedan binder vi det som finns i platshållaren till variabeln*/
$query->bindparam(':movieId', $id); 
$query->bindparam(':movieName', $movieName); 
$query->bindparam(':movieDuration', $movieDuration);
$query->bindparam(':movieCopies', $movieCopies);
//$query->bindparam(':songLyricsby', $songLyricsby);
//vi använder det som nu finns i $query för att köra sql frågan 
$query->execute(); 

// Alternative to above bindparam and execute 
// $query->execute(array(':id' => $id, ':joketext' => $joketext)); 

//header används för att skicka tillbaka efter proccesn är klart till en sida
header("Location: movie.php"); 
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
<title>Edit Movie</title> 
</head> 
<body> 
<a href="movie.php">Home</a> 
<br/><br/> 

<form name="form1" method="post" action="movieEdit.php"> 
<table border="0"> 
<tr> 
<td>Movie</td> 
<!-- Resultatet av vår sql fråga från rad34 lägger vi en input, man kan alltid
blanda html och php som ni ser, genom att flika in php taggar som start och slut-->
<td><input type="text" name="movieName" value="<?php echo $movieName;?>" ></td>
<td><input type="text" name="movieDuration" value="<?php echo $movieDuration;?>" ></td>
<td><input type="text" name="movieCopies" value="<?php echo $movieCopies;?>" ></td>
<!-- <td><input type="text" name="songLyricsby" value="<?php echo $songLyricsby;?>" ></td> -->
</tr> 
<tr> 
<td>Category</td> 
<td>
<!-- För att användare ine ska behöva stoppa in siffror för en cdID så skapar
vi en dropdown, där resultatet av sql frågan från rad 47 $cdQuery stoppar in
i $cd-->    
<select name="categoryId"> 
<?php 
while($category = $categorySqlQuery->fetch()) { 
if ($category['categoryId'] == $categoryId) { 
/*
 * Vi använder id som vi har för att, som defualt visa den författaren som var
 * kopplat till ett viss skämt vald från föregående sida.
*/ 
echo "<option value=\"{$category['categoryId']}\" selected>{$category['categoryName']}</option>"; 
} else { 
/*
 * Skulle vi däremot vilj ändra författaren till nåt annat det som vi fick från
 * förra sidan, så väljer vi det nu och också fångar upp id:et för den
 * författaren
*/ 
echo "<option value=\"{$category['categoryId']}\">{$category['categoryName']}</option>"; 
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
