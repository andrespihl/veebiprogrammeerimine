<?php
  require("functions.php");
  require("common.php");
  require("header.php");
  $database="if19_andres_pi_1";
  $notice = null;
  $firstname = null;
  $surname = null;
  $email = null;
  $gender = null;
  $birthMonth = null;
  $birthYear = null;
  $birthDay = null;
  $birthDate = null;
  $monthNamesET = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni","juuli", "august", "september", "oktoober", "november", "detsember"];
  
  //muutujad võimalike veateadetega
  $firstnameError = null;
  $surnameError = null;
  $birthMonthError = null;
  $birthYearError = null;
  $birthDayError = null;
  $birthDateError = null;
  $genderError = null;
  $emailError = null;
  $passwordError = null;
  $confirmpasswordError = null;
  
  //echo strlen($_POST["password"]);
  //testtsükkel
  //$i = $i + 1		$i += 1		$i ++
  //for($i=0;$i<10; $i ++){echo "Tsükkel töötab ".$i." | ";}
  
  //kui on uue kasutaja loomise nuppu vajutatud
  if(isset($_POST["submitUserData"])){
	  if(isset($_POST["firstName"]) and !empty($_POST["firstName"])){//eesnimi
		$firstname = test_input($_POST["firstName"]);
	  } else {
		  $firstnameError="Palun sisestage oma eesnimi!";
	  }//eesnime kontroll
	  if(isset($_POST["surName"]) and !empty($_POST["surName"])){//eesnimi
		$surname = test_input($_POST["surName"]);
	  } else {
		  $surnameError="Palun sisestage oma perekonnanimi!";
	  }//perekonnanime kontroll
	  $gender=test_input($_POST["gender"]);
	  
		//kontrollime, kas sünniaeg sisestati ja kas on korrektne
	  if(isset($_POST["birthDay"]) and !empty($_POST["birthDay"])){
		$birthDay = intval($_POST["birthDay"]);
	  } else {
	  $birthDayError = "Palun vali sünnikuupäev!";
	  }
  
  if(isset($_POST["birthMonth"]) and !empty($_POST["birthMonth"])){
	  $birthMonth = intval($_POST["birthMonth"]);
  } else {
	  $birthMonthError = "Palun vali sünnikuu!";
  }
  
  if(isset($_POST["birthYear"]) and !empty($_POST["birthYear"])){
	  $birthYear = intval($_POST["birthYear"]);
  } else {
	  $birthYearError = "Palun vali sünniaasta!";
  }
  //kas on korrektne kuupäev ja kui on, siis teeme objekti
	if(!empty($birthDay) and !empty($birthMonth) and !empty($birthYear)){
		if(checkdate($birthMonth,$birthDay,$birthYear)){
			$tempDate=new DateTime($birthYear."-".$birthMonth."-".$birthDay);
			$birthDate = $tempDate->format("Y-m-d");
		} else {
			$birthDateError = "Kahjuks ei ole valitud kuupäeva olemas!";
		}
	}// /checkdate
	if(isset($_POST["email"]) and !empty($_POST["email"])){//email
		$email = test_input($_POST["email"]);
	  } else {
		  $emailError="Palun sisestage oma emaili aadress!";
	  }//emaili kontroll
	if(isset($_POST["password"]) and !empty($_POST["password"]) and (strlen($_POST["password"])>8)){//parool
		if($_POST["password"]!=$_POST["confirmpassword"]){
			$passwordError="Parooli kinnitus ei vasta esimesele paroolile.";
		}
	  } else {
		$passwordError="Parool peab olema vähemalt 8 sümbolit!";
	}//parooli kontroll (kas on olemas, kas vähemalt 8 tähte ja kaks korda ühtemoodi
	//strlen($_POST["password"])
	//kui kõik korras, siis salvestama
	if(empty($firstnameError) and empty($surnameError) and empty($birthMonthError) and empty($birthYearError) and empty($birthDayError) and empty($birthDateError) and empty($genderError) and empty($emailError) and empty($passwordError) and empty($confirmpasswordError)){
		$notice = signUp($firstname,$surname,$email,$gender,$birthDate,$_POST["password"]);
	}
   }//Kui on nuppu vajutatud
  
?>
    <h1>Loo endale kasutajakonto</h1>
	<p>See leht on valminud TLÜ õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	
	<form method="POST">
	  <label>Eesnimi:</label><br>
	  <input name="firstName" type="text" value="<?php echo $firstname; ?>"><span><?php echo $firstnameError; ?></span><br>
      <label>Perekonnanimi:</label><br>
	  <input name="surName" type="text" value="<?php echo $surname; ?>"><span><?php echo $surnameError; ?></span><br>
	  
	  <input type="radio" name="gender" value="2" <?php if($gender == "2"){		echo " checked";} ?>><label>Naine</label>
	  <input type="radio" name="gender" value="1" <?php if($gender == "1"){		echo " checked";} ?>><label>Mees</label>
	  <input type="radio" name="gender" value="0" <?php if($gender == "0"){		echo " checked";} ?>><label>Muu</label><br>
	  <span><?php echo $genderError; ?></span><br>
	  
	  <label>Sünnipäev </label><select name="birthDay">
	  <option value="" selected disabled>Päev</option>
	  <?php
		for($i = 1; $i < 32; $i ++){
			echo "\t \t".'<option value="'.$i.'"';
			if ($i == $birthDay){
				echo " selected";				
			}
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
			if ($i == $birthMonth){
				echo " selected ";
			}
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
			if ($i == $birthYear){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <br>
	  <span><?php echo $birthDateError ." " .$birthDayError ." " .$birthMonthError ." " .$birthYearError; ?></span>
	  <br>
	  
	  <label>E-mail (kasutajatunnus):</label><br>
	  <input type="email" name="email" value="<?php echo $email; ?>"><span><?php echo $emailError; ?></span><br>
	  <label>Salasõna (min 8 tähemärki):</label><br>
	  <input name="password" type="password"><span><?php echo $passwordError; ?></span><br>
	  <label>Korrake salasõna:</label><br>
	  <input name="confirmpassword" type="password"><span><?php echo $confirmpasswordError; ?></span><br>
	  <input name="submitUserData" type="submit" value="Loo kasutaja"><span><?php echo $notice; ?></span>
	</form>
	<p>Tagasi <a href="page.php">algusesse</a></p>
<?php
  require("footer.php")
 ?>