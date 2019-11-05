<?php
  require("functions.php");
  require("common.php");
  require("header.php");
    if(!isset($_SESSION["userID"])){
	  header("Location: page.php");
	  exit();
  }
  //var_dump($_POST);
  $submitFilmNotice="";
  $submitPersonNotice="";
  $submitStudioNotice="";
  $submitGenreNotice="";
  $submitPositionNotice="";
  $submitStaffNotice="";
  $submitCitationNotice="";
  $birthMonth = null;
  $birthYear = null;
  $birthDay = null;
  $birthDate = null;
  $monthNamesET = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni","juuli", "august", "september", "oktoober", "november", "detsember"];
  if(isset($_POST["submitFilm"])){ // Filmi Lisamine ================================================================
		if($_POST["filmTitle"]!=null and $_POST["filmYear"]!=null and $_POST["filmDuration"]!=null and $_POST["filmGenre"]!=null and $_POST["filmStudio"]!=null and $_POST["filmDirector"]!=null and $_POST["filmSummary"]!=null){
			$filmTitle=test_input($_POST["filmTitle"]);
			$filmSummary=test_input($_POST["filmSummary"]);
		$submitFilmNotice=storeFilmInfo($filmTitle,$_POST["filmYear"],$_POST["filmDuration"],$_POST["filmGenre"],$_POST["filmStudio"],$_POST["filmDirector"],$filmSummary);
	  }else{
		  $submitFilmNotice="Palun täitke kõik väljad.";
	  }
  }
  if(isset($_POST["submitPerson"])){ //Inimese lisamine ================================================================
		if($_POST["personFirstName"]!=null and $_POST["personLastName"]!=null and $_POST["birthDay"]!=null and $_POST["birthMonth"]!=null and $_POST["birthYear"]!=null){
			$birthDay = intval($_POST["birthDay"]);
			$birthMonth = intval($_POST["birthMonth"]);
			$birthYear = intval($_POST["birthYear"]);
 //kas on korrektne kuupäev ja kui on, siis teeme objekti
			if(!empty($birthDay) and !empty($birthMonth) and !empty($birthYear)){
				if(checkdate($birthMonth,$birthDay,$birthYear)){
					$tempDate=new DateTime($birthYear."-".$birthMonth."-".$birthDay);
					$birthDate = $tempDate->format("Y-m-d");
					$personFirstName=test_input($_POST["personFirstName"]);
					$personLastName=test_input($_POST["personLastName"]);
					$submitPersonNotice=storePersonInfo($personFirstName,$personLastName,$birthDate);
				} else {
					$submitPersonNotice = "Kahjuks ei ole valitud kuupäeva olemas!";
				}
			}
		  }else{
			  $submitPersonNotice="Palun täitke kõik väljad.";
	  }
  }
  if(isset($_POST["submitStudio"])){ // Filmitootja Lisamine ================================================================
		if($_POST["studioName"]!=null){
			$studioName=test_input($_POST["studioName"]);
		$submitStudioNotice=storeStudioInfo($studioName);
	  }else{
		  $submitStudioNotice="Palun sisestage nimetus.";
	  }
  }
  if(isset($_POST["submitGenre"])){ // Žanri Lisamine ================================================================
		if($_POST["genreName"]!=null){
			$genreName=test_input($_POST["genreName"]);
		$submitGenreNotice=storeGenreInfo($genreName);
	  }else{
		  $submitGenreNotice="Palun sisestage nimetus.";
	  }
  }
  if(isset($_POST["submitPosition"])){ // Ameti Lisamine ================================================================
		if($_POST["positionName"]!=null){
			$positionName=test_input($_POST["positionName"]);
		$submitPositionNotice=storePositionInfo($positionName);
	  }else{
		  $submitPositionNotice="Palun sisestage nimetus.";
	  }
  }
  if(isset($_POST["submitStaff"])){ // Seose Lisamine ================================================================
		if($_POST["staffPersonID"]!=null and $_POST["staffMovieID"]!=null and $_POST["staffPositionID"]!=null){
			if($_POST["staffPositionID"]==1 and $_POST["staffRole"]!=null){
				$staffRole=test_input($_POST["staffRole"]);
				$submitStaffNotice=storeStaffInfo($_POST["staffPersonID"],$_POST["staffMovieID"],$_POST["staffPositionID"],$staffRole);
			}elseif($_POST["staffPositionID"]==1){
				$submitStaffNotice="Palun seostage näitlejaga roll.";
			}else{
				$staffRole=null;
				$submitStaffNotice=storeStaffInfo($_POST["staffPersonID"],$_POST["staffMovieID"],$_POST["staffPositionID"],$staffRole);
			}
	  }else{
		  $submitPositionNotice="Palun määrake seos.";
	  }
  }
  if(isset($_POST["submitCitation"])){ // Tsitaadi Lisamine ================================================================
		if($_POST["citationPersonID"]!=null and $_POST["citationMovieID"]!=null and $_POST["citationQuote"]!=null){
			$citationQuote=test_input($_POST["citationQuote"]);
		$submitCitationNotice=storeCitationInfo($_POST["citationPersonID"],$_POST["citationMovieID"],$citationQuote);
	  }else{
		  $submitCitationNotice="Palun täitke kõik väljad.";
	  }
  }
  $movieList = retrieveMovieList();
  $genreList = retrieveGenreList();
  $studioList=retrieveStudioList();
  $personList=retrievePersonList();
  $positionList=retrievePositionList();
?>
<h2>Eesti filmid</h2>
<p>Lisa uus film andmebaasi:</p><hr>
<form method="POST">
	<label>Kirjutage filmi pealkiri:</label>
	<input type="text" name="filmTitle"><br>
    <label>Kirjutage filmi tootmisaasta:</label>
	<input type="number" min="1912" max="2019" value="2019" name="filmYear"><br>
    <label>Kirjutage filmi kestus (min):</label>
	<input type="number" min="1" max="300" value="80" name="filmDuration"><br>
    <label>Valige filmi žanr:</label>
		<select name=filmGenre><option value="" selected disabled>Žanr</option>
			<?php echo($genreList);?>
		</select><br>
    <label>Valige filmi tootja:</label>
		<select name=filmStudio><option value="" selected disabled>Filmi tootja</option>
			<?php echo($studioList);?>
		</select><br>
    <label>Kirjutage filmi lavastaja:</label>
		<select name=filmDirector><option value="" selected disabled>Lavastaja</option>
			<?php echo($personList);?>
		</select><br>
	<label>Filmi kokkuvõte:</label><br>
	  <textarea rows="10" cols="80" name="filmSummary" placeholder="Kirjutage siia filmi kokkuvõte (kuni 3000 sümbolit)."></textarea>
	  <br>
	<input type="submit" value="Kinnita filmi info" name="submitFilm"> <?php echo $submitFilmNotice; ?>
	</form><br><br><br><hr>

<p>Lisa uus inimene andmebaasi:</p><hr>
<form method="POST">
	<label>Sisestage inimese eesnimi:</label>
	<input type="text" name="personFirstName"><br>
    <label>Sisestage inimese perekonnanimi:</label>
	<input type="text" name="personLastName"><br>
	  <label>Sünnipäev </label><select name="birthDay">
	  <option value="" selected disabled>Päev</option>
	  <?php
		for($i = 1; $i < 32; $i ++){
			echo "\t \t".'<option value="'.$i.'"';
			echo ">" .$i ."</option>\n";
		}
	  ?>
	  </select>
	  <label>Sünnikuu: </label>
	  <?php
	    echo '<select name="birthMonth">' ."\n";
		echo '<option value="" selected disabled>Kuu</option>' ."\n";
		for ($i = 1; $i < 13; $i ++){
			echo '<option value="' .$i .'"';
			echo ">" .$monthNamesET[$i - 1] ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <label>Sünniaasta: </label>
	  <?php
	    echo '<select name="birthYear">' ."\n";
		echo '<option value="" selected disabled>Aasta</option>' ."\n";
		for ($i = date("Y") - 15; $i >= date("Y") - 110; $i --){
			echo '<option value="' .$i .'"';
			echo ">" .$i ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <br>
	<input type="submit" value="Kinnita inimese info" name="submitPerson"> <?php echo $submitPersonNotice; ?>
	</form><br><br><br><hr>
	
<p>Lisa uus filmitootja andmebaasi:</p><hr>
<form method="POST">
	<label>Sisestage filmitootja nimetus:</label>
	<input type="text" name="studioName"><br>
	<input type="submit" value="Kinnita filmitootja info" name="submitStudio"> <?php echo $submitStudioNotice; ?>
	</form><br><br><br><hr>

<p>Lisa uus žanr andmebaasi:</p><hr>
<form method="POST">
	<label>Sisestage žanri nimetus:</label>
	<input type="text" name="genreName"><br>
	<input type="submit" value="Kinnita žanri info" name="submitGenre"> <?php echo $submitGenreNotice; ?>
	</form><br><br><br><hr>

<p>Lisa uus amet andmebaasi:</p><hr>
<form method="POST">
	<label>Sisestage ameti nimetus:</label>
	<input type="text" name="positionName"><br>
	<input type="submit" value="Kinnita andme info" name="submitPosition"> <?php echo $submitPositionNotice; ?>
	</form><br><br><br><hr>
	
<p>Seo isik filmiga andmebaasis:</p><hr>
<form method="POST">
	<label>Valige isik:</label>
		<select name=staffPersonID><option value="" selected disabled>Isik</option>
			<?php echo($personList);?>
		</select><br>
	<label>Valige film:</label>
		<select name=staffMovieID><option value="" selected disabled>Film</option>
			<?php echo($movieList);?>
		</select><br>
	<label>Valige amet:</label>
		<select name=staffPositionID><option value="" selected disabled>Amet</option>
			<?php echo($positionList);?>
		</select><br>
	<label>Sisestage roll juhul kui tegemist on näitlejaga:</label>
	<input type="text" name="staffRole"><br>
	<input type="submit" value="Kinnita seos" name="submitStaff"> <?php echo $submitStaffNotice; ?>
	</form><br><br><br><hr>

<p>Sisestage tsitaat andmebaasi:</p><hr>
<form method="POST">
	<label>Valige isik:</label>
		<select name=citationPersonID><option value="" selected disabled>Isik</option>
			<?php echo($personList);?>
		</select><br>
	<label>Valige film:</label>
		<select name=citationMovieID><option value="" selected disabled>Film</option>
			<?php echo($movieList);?>
		</select><br>
	<label>Sisestage tsitaat:</label><br>
	<textarea rows="3" cols="80" name="citationQuote" placeholder="Kirjutage siia tsitaat."></textarea><br>
	<input type="submit" value="Kinnita tsitaat" name="submitCitation"> <?php echo $submitCitationNotice; ?>
	</form><br><br><br><hr>

<?php
  require("footer.php");
?>
</body>
</html>