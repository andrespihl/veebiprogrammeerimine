<?php
session_start();

function signUp($firstname,$surname,$email,$gender,$birthDate,$password){
	$notice = null;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn ->prepare("INSERT INTO vp_users (firstname, lastname, birthdate, gender, email, password) VALUES (?,?,?,?,?,?)");
	echo $conn->error;
	$options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)];
	$pwdHash = password_hash($password, PASSWORD_BCRYPT, $options);
	$stmt->bind_param("sssiss", $firstname, $surname, $birthDate, $gender, $email, $pwdHash);
		if ($stmt->execute()){
			$notice = " Kasutaja loomine õnnestus.";
		} else {
			$notice = " Kasutaja loomisel tekkis tehniline viga: ".$stmt->error;
		}
	$stmt -> close();
	$conn -> close();
	return $notice;
}
function logIn($usernameLI,$passwordLI){//Ei ole kasutuses, sest kasutasin õppejõu antud funktsiooni, sest sealt sain ka tulemuse. Kasutasin erinevaid muutuja nimesid.
	$noticeLI = null;
	$passwordfromDB=null;
	$firstnameLI=null;
	$surnameLI=null;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn ->prepare("select password,firstname,lastname from vp_users where email=?");
	echo $conn->error;
	$stmt->bind_param("s", $usernameLI);
	$stmt->bind_result($passwordfromDB,$firstnameLI,$surnameLI);
	if($stmt->execute()){
		if($stmt->fetch()){
			if(password_verify($passwordLI,$passwordfromDB)){
				$noticeLI="Olete sisse logitud kui ".$firstnameLI." ".$surnameLI.".";
				//kuna siirdume teisele lehele, sulgeme andmebaasi ühendused.
				$stmt -> close();
				$conn -> close();
				header("Location: home:php");
			} else {
				$noticeLI="Parooli kontroll ei õnnestunud.";
			}
		}else{
			$noticeLI="Sellist kasutajat (".$usernameLI.") ei leitud.";
		}
	}else{
		$noticeLI="Tekkis tehniline viga";
	}
	$stmt -> close();
	$conn -> close();
	return $noticeLI;
}

  function signIn($usernameLI, $passwordLI){
	$notice = "";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT password FROM vp_users WHERE email=?");
	echo $conn->error;
	$stmt->bind_param("s", $usernameLI);
	$stmt->bind_result($passwordFromDb);
	if($stmt->execute()){
		//kui päring õnnestus
	  if($stmt->fetch()){
		//kasutaja on olemas
		if(password_verify($passwordLI, $passwordFromDb)){
		  //kui salasõna klapib
		  $stmt->close();
		  $stmt = $conn->prepare("SELECT id, firstname, lastname FROM vp_users WHERE email=?");
		  echo $conn->error;
		  $stmt->bind_param("s", $usernameLI);
		  $stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb);
		  $stmt->execute();
		  $stmt->fetch();
		  $notice = "Sisse logis " .$firstnameFromDb ." " .$lastnameFromDb ."!";
		  $_SESSION["userID"]=$idFromDb;
		  $_SESSION["userFirstName"]=$firstnameFromDb;
		  $_SESSION["userLastName"]=$lastnameFromDb;
		  $stmt->close();
		  $stmt = $conn->prepare("select description, bgcolor, txtcolor from vp_userprofiles where userid=?");
		  echo $conn->error;
		  $stmt->bind_param("s", $_SESSION["userID"]);
		  $stmt->bind_result($descriptionFromDB,$bgcolorFromDatabase,$txtcolorFromDatabase);
		  if($stmt->execute()){
			if($stmt->fetch()){
				$_SESSION["userDescription"]=$descriptionFromDB;
				$_SESSION["userBG"]=$bgcolorFromDB;
				$_SESSION["userColor"]=$txtcolorFromDB;
			}
		  }
		  $stmt->close();
		  $conn->close();
		  header("Location: home.php");
		} else {
		  $notice = "Vale salasõna!";
		}
	  } else {
		$notice = "Sellist kasutajat (" .$usernameLI .") ei leitud!";  
	  }
	} else {
	  $notice = "Sisselogimisel tekkis tehniline viga!" .$stmt->error;
	}
	$stmt->close();
	$conn->close();
	return $notice;
  }//sisselogimine lõppeb
  
  function saveProfile($description,$bgcolor,$txtcolor){
	$notice = "";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT userid FROM vp_userprofiles WHERE userid=?");
	echo $conn->error;
	$stmt->bind_param("s", $_SESSION["userID"]);
	if($stmt->execute()){
		if($stmt->fetch()){
			$stmt->close();
			$stmt=$conn->prepare("UPDATE `if19_andres_pi_1`.`vp_userprofiles` SET `description` =?, `bgcolor` =?, `txtcolor` =? WHERE `vp_userprofiles`.`userid` = ?");
			$stmt->bind_param("sssi", $description, $bgcolor, $txtcolor, $_SESSION["userID"]);
			$stmt->execute();
			$notice="Salvestamine õnnestus.";
			$_SESSION["userDescription"]=$description;
			$_SESSION["userBG"]=$bgcolor;
			$_SESSION["userColor"]=$txtcolor;
		}else{
			$stmt->close();
			$stmt=$conn->prepare("INSERT INTO `vp_userprofiles`(`userid`, `description`, `bgcolor`, `txtcolor`) VALUES (?,?,?,?)");
			$stmt->bind_param("sssi",$_SESSION["userID"],$description,$bgcolor,$txtcolor);
			$stmt->execute();
			$notice="Salvestamine õnnestus.";
			$_SESSION["userDescription"]=$description;
			$_SESSION["userBG"]=$bgcolor;
			$_SESSION["userColor"]=$txtcolor;
		}
	}else{
		$notice="Salvestamisel tekkis viga!".$stmt->error;
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
  }