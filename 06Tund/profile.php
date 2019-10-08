<?php
  require("common.php");
  require("header.php");
  if(!isset($_SESSION["userID"])){
	  header("Location: page.php");
	  exit();
  }
  	if(isset($_POST["submitProfile"])){
		$profileNotice= saveProfile($_POST["description"],$_POST["bgcolor"],$_POST["txtcolor"]);
	}
?>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>Minu kirjeldus</label><br>
	  <textarea rows="10" cols="80" name="description"><?php if(isset($_SESSION["userDescription"])){echo $_SESSION["userDescription"];} ?></textarea>
	  <br>
	  <label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $_SESSION["userBG"]; ?>"><br>
	  <label>Minu valitud tekstivärv: </label><input name="txtcolor" type="color" value="<?php echo $_SESSION["userColor"]; ?>"><br>
	  <input name="submitProfile" type="submit" value="Salvesta profiil">
	</form>

<?php
require("footer.php");
?>
</body>
</html>