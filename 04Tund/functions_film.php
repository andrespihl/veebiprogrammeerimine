<?php
  $database="if19_andres_pi_1";
  
  function readAllFilms(){
  $filmInfoHTML=null;
  //Loeme andmebaasist filmide infot
  //Loome ühenduse andmebaasi ($mysqli / $conn)
  //$conn = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
  $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
  //MySQL päringu ettevalmistus
  $stmt = $conn -> prepare("select pealkiri,aasta from film");
  echo $conn -> error;
  $stmt -> bind_result($filmTitle,$filmYear);
  $stmt -> execute();
  //sain pinu (stack) täie info ja hakkan ühekaupa võtma kuni saab.
  while($stmt -> fetch()){
    //echo $filmTitle."</p><p>";}
	$filmInfoHTML .= "<h3>" .$filmTitle.", (".$filmYear.")</h3>";
	}
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