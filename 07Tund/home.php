<?php
  require("functions.php");
  require("common.php");
  require("header.php");
  if(!isset($_SESSION["userID"])){
	  header("Location: page.php");
	  exit();
  }
?>
<p>Olete sisse loginud kui: <?php echo $_SESSION["userFirstName"]?> | Logi <a href="?logout=1">välja!</a></p>

<?php
require("footer.php");
?>
</body>
</html>