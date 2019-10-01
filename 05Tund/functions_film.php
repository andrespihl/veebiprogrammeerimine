<?php
  $database="if19_andres_pi_1";
  
  function readAllFilms(){
  $filmInfoHTML='<table style="width:100%"><tr><th>Pealkiri</th><th>Aasta</th><th>Kestus</th><th>Žanr</th><th>Tootja</th><th>Lavastaja</th>';
  //Loeme andmebaasist filmide infot
  //Loome ühenduse andmebaasi ($mysqli / $conn)
  //$conn = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
  $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
  //MySQL päringu ettevalmistus
  $stmt = $conn -> prepare("select pealkiri,aasta,kestus,zanr,tootja,lavastaja from film order by aasta");
  echo $conn -> error;
  $stmt -> bind_result($filmTitle,$filmYear,$filmDuration,$filmGenre,$filmStudio,$filmDirector);
  $stmt -> execute();
  //sain pinu (stack) täie info ja hakkan ühekaupa võtma kuni saab.
  while($stmt -> fetch()){
    //echo $filmTitle."</p><p>";}
	$filmInfoHTML .= '<tr><td>' .$filmTitle.'</td><td>'.$filmYear."</td><td>";
	if(floor($filmDuration/60) == 1){
		$filmInfoHTML .= floor($filmDuration/60)." tund ";
	}
	elseif(floor($filmDuration/60) == 0){
	}
	else {
		$filmInfoHTML .= floor($filmDuration/60)." tundi ";
	}
	if(floor($filmDuration%60) == 1){
		$filmInfoHTML .= floor($filmDuration%60)." minut";
	}
	elseif(floor($filmDuration%60) == 0){
	}
	else {
		$filmInfoHTML .= floor($filmDuration%60)." minutit";
	}
	$filmInfoHTML .= "</td><td>".$filmGenre."</td><td>".$filmStudio."</td><td>".$filmDirector."</td></tr>";
	}
  $filmInfoHTML .= "</table>";
  //sulgen ühenduse
  $stmt -> close();
  $conn -> close();
  return $filmInfoHTML;
  }
  $filmInfoHTML = readAllFilms();
  
  function storeFilmInfo($filmTitle,$filmYear,$filmDuration,$filmGenre,$filmStudio,$filmDirector){
   $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
   $stmt = $conn -> prepare ("Insert INTO film (pealkiri, aasta, kestus, zanr, tootja, lavastaja) VALUES(?,?,?,?,?,?)");
   echo $conn -> error;
   $stmt -> bind_param("siisss",$filmTitle,$filmYear,$filmDuration,$filmGenre,$filmStudio,$filmDirector);
   $stmt -> execute();
   //andmetüübid:	s-string	i-integer	d-decimal
   $stmt -> close();
   $conn -> close();
  }
  
  function BlankTemplate(){
   $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
   
   $stmt -> close();
   $conn -> close();
  }
?>