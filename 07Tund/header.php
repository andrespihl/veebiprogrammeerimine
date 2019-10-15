<?php
	$usernameErrorLI = null;
	$passwordErrorLI = null;
	$usernameLI = null;
	$noticeLI = null;
	if(isset($_POST["submitLogIn"])){
		if(isset($_POST["usernameLI"]) and !empty($_POST["usernameLI"])){//kasutajanimi
			$usernameLI = test_input($_POST["usernameLI"]);
		}else{
			$usernameErrorLI="Palun sisestage oma kasutajanimi (email).";
		}
		if(empty($_POST["passwordLI"]) or strlen($_POST["passwordLI"])<8){//kasutajanimi
			$passwordErrorLI="Sisestage parool, mis on vähemalt 8 sümbolit.";
		}
		if(empty($usernameErrorLI) and empty($passwordErrorLI)){
			$noticeLI=signIn($usernameLI,$_POST["passwordLI"]);
		}else{
			$noticeLI="Ei saa sisse logida.";
		}
	}
	if(isset($_SESSION["userID"])){
		$userName= $_SESSION["userFirstName"]." ".$_SESSION["userLastName"];
	}
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
		exit();
	}
?>

<!DOCTYPE html>
<html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <title>
  <?php
  if(isset($userName)){
    echo $userName." programmerib veebi";
  }else{
	echo "Veebiprogrammeerimine";
  }
  ?>
</title>
</head>

<body>
<div align="center"><a href="javascript:var%20i,s,ss=['http://kathack.com/js/kh.js','http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js'];for(i=0;i!=ss.length;i++){s=document.createElement('script');s.src=ss[i];document.body.appendChild(s);}void(0);"title="Katamari!">
<?php
  if(isset($userName)){
    echo "<H1>".$userName." programmerib veebi</a></H1></div><hr>";
  }else{
	echo "<H1>Veebiprogrammeerimine</a></H1></div><hr>";
  }
  if(!isset($_SESSION["userID"])){
	    Echo '<div align="right"><form method="POST">
		  <label>Kasutajanimi: </label>
		  <input name="usernameLI" type="email" value="'.$usernameLI.'">
		  <label>  Parool: </label>
		  <input name="passwordLI" type="password">
		  <input name="submitLogIn" type="submit" value="Logige sisse"><br><span>'.$usernameErrorLI.'</span>&nbsp <span>'.$passwordErrorLI.'</span>&nbsp <span>'.$noticeLI.'</span>
		</form>
		<p>Kui pole kasutaja kontot: loo <a href="newuser.php">kasutajakonto</a></p>';
  }else{
	  echo '<div align="right"><p>Olete sisse logitud kui <a href="home.php">'.$userName.'</a>.<br><a href="profile.php">Profiil.</a><br><a href="messages.php">Sõnumid.</a><br><a href="?logout=1">Logige välja.</a></p>';
  }
	?></div>
<hr>