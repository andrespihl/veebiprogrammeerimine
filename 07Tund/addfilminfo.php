<?php
  require("functions.php");
  require("common.php");
  require("header.php");
  
  //var_dump($_POST);
  if(isset($_POST["submitFilm"])){
	  if($_POST["filmTitle"]!=null){
		storeFilmInfo($_POST["filmTitle"],$_POST["filmYear"],$_POST["filmDuration"],$_POST["filmGenre"],$_POST["filmStudio"],$_POST["filmDirector"]);
	  }
  }
?>
<h2>Eesti filmid</h2>
<p>Lisa uus film andmebaasi:</p><hr>
<?php
  if(isset($_POST["submitFilm"])){
	  if($_POST["filmTitle"]==null){
		echo '<form method="POST"><label>Kirjutage filmi pealkiri:</label>
		<input type="text" value="'.$_POST["filmTitle"].'" name="filmTitle"><br>
		<label>Kirjutage filmi tootmisaasta:</label>
		<input type="number" min="1912" max="2019" value="'.$_POST["filmYear"].'" name="filmYear"><br>
		<label>Kirjutage filmi kestus (min):</label>
		<input type="number" min="1" max="300" value="'.$_POST["filmDuration"].'" name="filmDuration"><br>
		<label>Kirjutage filmi žanr:</label>
		<input type="text" value="'.$_POST["filmGenre"].'" name="filmGenre"><br>
		<label>Kirjutage filmi tootja:</label>
		<input type="text" value="'.$_POST["filmStudio"].'" name="filmStudio"><br>
		<label>Kirjutage filmi lavastaja:</label>
		<input type="text" value="'.$_POST["filmDirector"].'" name="filmDirector"><br>	
		<input type="submit" value="Kinnita filmi info" name="submitFilm">
		</form><p>Palun sisestada filmile vähemalt pealkiri.</p>';
	  }
	   else{
		echo '<form method="POST">
		<label>Kirjutage filmi pealkiri:</label>
		<input type="text" name="filmTitle"><br>
		<label>Kirjutage filmi tootmisaasta:</label>
		<input type="number" min="1912" max="2019" value="2019" name="filmYear"><br>
		<label>Kirjutage filmi kestus (min):</label>
		<input type="number" min="1" max="300" value="80" name="filmDuration"><br>
		<label>Kirjutage filmi žanr:</label>
		<input type="text" name="filmGenre"><br>
		<label>Kirjutage filmi tootja:</label>
		<input type="text" name="filmStudio"><br>
		<label>Kirjutage filmi lavastaja:</label>
		<input type="text" name="filmDirector"><br>	
		<input type="submit" value="Kinnita filmi info" name="submitFilm">
		</form>';
	   }
  }
 else{
	echo '<form method="POST">
	<label>Kirjutage filmi pealkiri:</label>
	<input type="text" name="filmTitle"><br>
    <label>Kirjutage filmi tootmisaasta:</label>
	<input type="number" min="1912" max="2019" value="2019" name="filmYear"><br>
    <label>Kirjutage filmi kestus (min):</label>
	<input type="number" min="1" max="300" value="80" name="filmDuration"><br>
    <label>Kirjutage filmi žanr:</label>
	<input type="text" name="filmGenre"><br>
    <label>Kirjutage filmi tootja:</label>
	<input type="text" name="filmStudio"><br>
    <label>Kirjutage filmi lavastaja:</label>
	<input type="text" name="filmDirector"><br>	
	<input type="submit" value="Kinnita filmi info" name="submitFilm">
	</form>';
  }
 ?>

<?php
  require("footer.php");
?>
</body>
</html>