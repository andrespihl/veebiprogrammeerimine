<?php
  $notice=null;
  require("functions.php");
  	if(isset($_POST["submitProfile"])){
		$notice= saveProfile($_POST["description"],$_POST["bgcolor"],$_POST["txtcolor"]);
	}
	if(isset($_POST["submitPasswordChange"])){
	if(isset($_POST["password"]) and !empty($_POST["password"]) and (strlen($_POST["password"])>8)){//parool
		if(isset($_POST["newpassword"]) and !empty($_POST["newpassword"]) and (strlen($_POST["newpassword"])>8)){
			if($_POST["newpassword"]!=$_POST["confirmpassword"]){
				$passwordError="Parooli kinnitus ei vasta uuele paroolile.";
			}
		}
	  } else {
		$passwordError="Algne parool on vale!";
	}//parooli kontroll (kas on olemas, kas vähemalt 8 tähte ja kaks korda ühtemoodi
	//strlen($_POST["password"])
	//kui kõik korras, siis salvestama
	if(empty($passwordError) and empty($confirmpasswordError)){
		$notice = changePassword($_POST["password"],$_POST["newpassword"]);
	}
   }
  require("common.php");
  require("header.php");
  $passwordError=null;
  $confirmpasswordError=null;
  if(!isset($_SESSION["userID"])){
	  header("Location: page.php");
	  exit();
  }
?>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>Minu kirjeldus</label><br>
	  <textarea rows="10" cols="80" name="description" placeholder="Lisa siia oma tutvustus."><?php if(isset($_SESSION["userDescription"])){echo $_SESSION["userDescription"];} ?></textarea>
	  <br>
	  <label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $_SESSION["userBG"]; ?>"><br>
	  <label>Minu valitud tekstivärv: </label><input name="txtcolor" type="color" value="<?php echo $_SESSION["userColor"]; ?>"><br>
	  <input name="submitProfile" type="submit" value="Salvesta profiil"><span><?php echo $notice; ?></span>
	</form>
<hr><form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>Salasõna (min 8 tähemärki):</label><br>
	  <input name="password" type="password"><span><?php echo $passwordError; ?></span><br>
	  <label>Uus salasõna:</label>
	  <input name="newpassword" type="password"><br>
	  <label>Korrake uut salasõna:</label>
	  <input name="confirmpassword" type="password"><span><?php echo $confirmpasswordError; ?></span><br>
	  <input name="submitPasswordChange" type="submit" value="Muuda parooli"><span><?php echo $notice; ?></span>
	</form>

<?php
require("footer.php");
?>
</body>
</html>