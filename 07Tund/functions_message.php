<?php
    function storeMessage($myMessage){
	$notice = "";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare('INSERT INTO vp_msg (userid,message) values (?,?)');
	echo $conn->error;
	$stmt->bind_param("is",$_SESSION["userID"],$myMessage);
	if($stmt->execute()){
		$notice="Sõnum salvestatud.";
	} else {
		$notice="Sõnumi salvestamisel tekkis viga".$stmt->error;
	}
	$stmt->close();
	$conn->close();
	return $notice;
	}
	
    function retrieveMessages($limit){
	$messageHTML=null;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
//	$stmt = $conn->prepare('SELECT message,created FROM vp_msg');
	$stmt = $conn->prepare('SELECT message,created FROM vp_msg WHERE userid=? and deleted IS NULL ORDER BY CREATED DESC LIMIT ?');
	echo $conn->error;
	$stmt->bind_param("ii",$_SESSION["userID"],$limit);
	$stmt->bind_result($messageFromDB,$creationTimeFromDB);
	$stmt->execute();
	while($stmt->fetch()){
			$messageHTML.="<li>".$messageFromDB.'<div align="right"> -'.$creationTimeFromDB."</div></li>";
	}
	if(!empty($messageHTML)){
		$messageHTML='<div align="center"><h2>Varasemad sõnumid</h2></div><ul>'.$messageHTML.'</ul>';
	}else{
		$messageHTML="Sõnumeid ei ole";
	}
	$stmt->close();
	$conn->close();
	return $messageHTML;
	}