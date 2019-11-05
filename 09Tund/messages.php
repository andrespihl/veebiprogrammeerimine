<?php
  require("functions.php");
  require("common.php");
  require("header.php");
  $notice=null;
  $myMessages=null;
  $limit=5;
  if(!isset($_SESSION["userID"])){
	  header("Location: page.php");
	  exit();
  }
    if(isset($_POST["submitMessage"])){
		$myMessage=test_input($_POST["message"]);
		if(!empty($myMessage)){
			$notice = storeMessage($myMessage);
		}else{
			$notice= "Sõnum oli tühi ja seda ei salvestatud.";
		}
	}
	$messagesHTML=retrieveMessages($limit);
?>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>Minu sõnum (256 märki)</label><br>
	  <textarea rows="5" cols="150" name="message" placeholder="Kirjutage sõnum."></textarea>
	  <br>
	  <input name="submitMessage" type="submit" value="Salvesta sõnum!"><span><?php echo $notice;?></span>
	</form>

<?php
echo $messagesHTML;

require("footer.php");
?>
</body>
</html>