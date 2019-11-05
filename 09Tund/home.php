<?php
  require("functions.php");
  require("common.php");
  require("header.php");
  if(!isset($_SESSION["userID"])){
	  header("Location: page.php");
	  exit();
  }
?>
<p>Tere, <?php echo $_SESSION["userFirstName"]?>!</p>
<a href="profile.php">Profiil.</a><br>
<a href="messages.php">Sõnumid.</a><br>
<a href="picupload.php">Piltide üleslaadimine.</a><br>
<a href="filminfo.php">Filmide andmebaas.</a><br>
<a href="addfilminfo.php">Filmide andmebaasi täiendamine.</a><br>

<?php
require("footer.php");
?>
</body>
</html>