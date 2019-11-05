<?php
  $database="if19_andres_pi_1";
  
  function readAllFilms(){
  $filmInfoHTML='<table style="width:100%"><tr><th>Pealkiri</th><th>Aasta</th><th>Kestus</th><th>Žanr</th><th>Tootja</th><th>Lavastaja</th>';
  //Loeme andmebaasist filmide infot
  //Loome ühenduse andmebaasi ($mysqli / $conn)
  //$conn = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
  $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
  $connInit = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
  //MySQL päringu ettevalmistus
  $stmtInit = $connInit -> prepare("select movie_ID, title, releaseYear, duration, studioName from Movie inner join Movie_Studio on Movie.movie_ID = Movie_Studio.Movie_movie_ID inner join Studio on Studio.Studio_ID = Movie_Studio.Studio_studio_ID order by title");
  echo $connInit -> error;
  echo $conn -> error;
  $stmtInit -> bind_result($filmID,$filmTitle,$filmYear,$filmDuration,$filmStudio);
  $stmtInit -> execute();
  //sain pinu (stack) täie info ja hakkan ühekaupa võtma kuni saab.
  while($stmtInit -> fetch()){
	$genreCount=0;
	$directorCount=0;
	$stmt = $conn -> prepare("select GenreName from Genre inner join Movie_Genre on Genre.Genre_ID = Movie_Genre.Genre_genre_ID inner join Movie on Movie.movie_ID = Movie_Genre.Movie_movie_ID where movie_movie_ID=?");
	$stmt -> bind_result($filmGenreFromDB);
	$stmt -> bind_param("i",$filmID);
	$stmt->execute();
	while($stmt->fetch()){
		if($genreCount==0){
			$filmGenre=$filmGenreFromDB;
			$genreCount.=1;
		}else{
			$filmGenre.=", ".$filmGenreFromDB;
		}
	}
	$stmt->close();
	$stmt = $conn -> prepare("select firstName, lastName from Person inner join Staff on Person.person_ID = Staff.Person_person_ID inner join Movie on Movie.Movie_ID = Staff.Movie_movie_ID where Movie_ID=? and Position_position_ID=2");
	$stmt -> bind_result($directorFirstNameFromDB,$directorSurNameFromDB);
	$stmt -> bind_param("i",$filmID);
	$stmt->execute();
	while($stmt->fetch()){
		if($directorCount==0){
			$filmDirector=$directorFirstNameFromDB." ".$directorSurNameFromDB;
			$genreCount.=1;
		}else{
			$filmGenre.=", ".$directorFirstNameFromDB." ".$directorSurNameFromDB;
		}
	}
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
	$stmt->close();
	}
  $filmInfoHTML .= "</table>";
  //sulgen ühenduse
  $conn -> close();
  $stmtInit -> close();
  $connInit -> close();
  return $filmInfoHTML;
  }

  function storeFilmInfo($filmTitle,$filmYear,$filmDuration,$filmGenre,$filmStudio,$filmDirector,$filmSummary){
   $submitFilmNotice="";
   $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
   $stmt = $conn-> prepare("select movie_ID from Movie where title=? and releaseYear=?");
   $stmt -> bind_result($filmID);
   $stmt -> bind_param("si",$filmTitle,$filmYear);
   $stmt -> execute();
   if($stmt->fetch()){
	   $submitFilmNotice="Film on juba andmebaasis.";
   }else{
	   $stmt->close();
	   $stmt = $conn -> prepare ("Insert INTO Movie (title, releaseYear, duration, summary) VALUES(?,?,?,?)");
	   $stmt -> bind_param("siis",$filmTitle,$filmYear,$filmDuration,$filmSummary);
	   $stmt -> execute();
	   //andmetüübid:	s-string	i-integer	d-decimal
	   $stmt -> close();
	   $stmt = $conn->prepare("Select movie_ID from Movie where title=? and releaseYear=?");
	   $stmt -> bind_param("si",$filmTitle,$filmYear);
	   $stmt -> bind_result($filmID);
	   $stmt -> execute();
	   if($stmt->fetch()){
		   $stmt->close();
		   $stmt = $conn -> prepare ("Insert INTO Movie_Genre (Movie_movie_ID, Genre_genre_ID) VALUES(?,?)");
		   $stmt -> bind_param("ii",$filmID,$filmGenre);
		   $stmt -> execute();
		   //andmetüübid:	s-string	i-integer	d-decimal
		   $stmt->close();
		   $stmt = $conn -> prepare ("Insert INTO Movie_Studio (Studio_Studio_ID,Movie_movie_ID) VALUES(?,?)");
		   $stmt -> bind_param("ii",$filmStudio,$filmID);
		   $stmt -> execute();
		   //andmetüübid:	s-string	i-integer	d-decimal
		   $stmt->close();
		   $stmt = $conn -> prepare ("Insert INTO Staff (Person_person_ID, Movie_movie_ID, Position_position_ID) VALUES(?,?,2)");
		   $stmt -> bind_param("ii",$filmDirector,$filmID);
		   $stmt -> execute();
		   $submitFilmNotice="Film on edukalt andmebaasi lisatud";
		   //andmetüübid:	s-string	i-integer	d-decimal
		   $stmt->close();
		   $stmt = $conn -> prepare ("Insert INTO vp_connection (vp_users_ID, Movie_movie_ID) VALUES(?,?)");
		   $stmt -> bind_param("ii",$_SESSION["userID"],$filmID);
		   $stmt -> execute();
		   $submitFilmNotice="Film on edukalt andmebaasi lisatud";
		   //andmetüübid:	s-string	i-integer	d-decimal
	   }else{
		   $submitFilmNotice="Salvestamisel tekkis viga.";
	   }
   }
   $stmt -> close();
   $conn -> close();
   return $submitFilmNotice;
  }
  
  function retrieveMovieList(){
   $movieList="";
   $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
   $stmt = $conn->prepare("Select movie_ID, title from Movie order by title");
   $stmt->bind_result($movieIDFromDB,$movieTitleFromDB);
   echo $conn -> error;
   $stmt->execute();
   while($stmt->fetch()){
	   $movieList.='<option value="'.$movieIDFromDB.'"> '.$movieTitleFromDB .'</option> \n"';
   }
   $stmt -> close();
   $conn -> close();
   return $movieList;
  }
   
   function retrieveGenreList(){
   $genreList="";
   $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
   $stmt = $conn->prepare("Select genre_ID, genreName from Genre order by genreName");
   $stmt->bind_result($genreIDFromDB,$genreNameFromDB);
   echo $conn -> error;
   $stmt->execute();
   while($stmt->fetch()){
	   $genreList.='<option value="'.$genreIDFromDB.'"> '.$genreNameFromDB .'</option> \n"';
   }
   $stmt -> close();
   $conn -> close();
   return $genreList;
  }
 
   function retrievePersonList(){
   $personList="";
   $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
   $stmt = $conn->prepare("Select person_ID, firstName, lastName from Person order by lastName");
   $stmt->bind_result($personIDFromDB,$firstNameFromDB,$lastNameFromDB);
   echo $conn -> error;
   $stmt->execute();
   while($stmt->fetch()){
	   $personList.='<option value="'.$personIDFromDB.'"> '.$lastNameFromDB.", ".$firstNameFromDB .'</option> \n"';
   }
   $stmt -> close();
   $conn -> close();
   return $personList;
  }
   
   function retrievePositionList(){
   $positionList="";
   $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
   $stmt = $conn->prepare("Select position_ID, position from Position order by position");
   $stmt->bind_result($positionIDFromDB,$positionNameFromDB);
   echo $conn -> error;
   $stmt->execute();
   while($stmt->fetch()){
	   $positionList.='<option value="'.$positionIDFromDB.'"> '.$positionNameFromDB .'</option> \n"';
   }
   $stmt -> close();
   $conn -> close();
   return $positionList;
  }
   
   function retrieveStudioList(){
   $studioList="";
   $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
   $stmt = $conn->prepare("Select studio_ID, studioName from Studio order by studioName");
   $stmt->bind_result($studioIDFromDB,$studioNameFromDB);
   echo $conn -> error;
   $stmt->execute();
   while($stmt->fetch()){
	   $studioList.='<option value="'.$studioIDFromDB.'"> '.$studioNameFromDB .'</option> \n"';
   }
   $stmt -> close();
   $conn -> close();
   return $studioList;
  }
  
  function storePersonInfo($personFirstName,$personLastName,$birthDate){
	$submitPersonNotice="";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn-> prepare("select person_ID from Person where firstname=? and lastName=? and birthDate=?");
	$stmt -> bind_result($personID);
	$stmt -> bind_param("sss",$personFirstName,$personLastName,$birthDate);
	$stmt -> execute();
	if($stmt->fetch()){
	   $submitPersonNotice="Inimene on juba andmebaasis.";
	}else{
	   $stmt->close();
	   $stmt = $conn -> prepare ("Insert INTO Person (firstName, lastName, birthDate) VALUES(?,?,?)");
	   $stmt -> bind_param("sss",$personFirstName,$personLastName,$birthDate);
	   $stmt -> execute();
	   $submitPersonNotice="Inimene on edukalt andmebaasi sisestatud.";
	}
	$stmt -> close();
	$conn -> close();
	return $submitPersonNotice;
  }

  function storeStudioInfo($studioName){
	$submitStudioNotice="";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn-> prepare("select studio_ID from Studio where studioName=?");
	$stmt -> bind_result($studioID);
	$stmt -> bind_param("s",$studioName);
	$stmt -> execute();
	if($stmt->fetch()){
	   $submitStudioNotice="Filmitootja on juba andmebaasis.";
	}else{
	   $stmt->close();
	   $stmt = $conn -> prepare ("Insert INTO Studio (studioName) VALUES(?)");
	   $stmt -> bind_param("s",$studioName);
	   $stmt -> execute();
	   $submitStudioNotice="Filmitootja on edukalt andmebaasi sisestatud.";
	}
	$stmt -> close();
	$conn -> close();
	return $submitStudioNotice;
  }
  
  function storeGenreInfo($genreName){
	$submitGenreNotice="";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn-> prepare("select genre_ID from Genre where genreName=?");
	$stmt -> bind_result($genreID);
	$stmt -> bind_param("s",$genreName);
	$stmt -> execute();
	if($stmt->fetch()){
	   $submitGenreNotice="Žanr on juba andmebaasis.";
	}else{
	   $stmt->close();
	   $stmt = $conn -> prepare ("Insert INTO Genre (genreName) VALUES(?)");
	   $stmt -> bind_param("s",$genreName);
	   $stmt -> execute();
	   $submitGenreNotice="Žanr on edukalt andmebaasi sisestatud.";
	}
	$stmt -> close();
	$conn -> close();
	return $submitGenreNotice;
  }
  
  function storePositionInfo($positionName){
	$submitPositionNotice="";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn-> prepare("select position_ID from Position where position=?");
	$stmt -> bind_result($positionID);
	$stmt -> bind_param("s",$positionName);
	$stmt -> execute();
	if($stmt->fetch()){
	   $submitPositionNotice="Amet on juba andmebaasis.";
	}else{
	   $stmt->close();
	   $stmt = $conn -> prepare ("Insert INTO Position (position) VALUES(?)");
	   $stmt -> bind_param("s",$positionName);
	   $stmt -> execute();
	   $submitPositionNotice="Amet on edukalt andmebaasi sisestatud.";
	}
	$stmt -> close();
	$conn -> close();
	return $submitPositionNotice;
  }
  
  function storeStaffInfo($staffPersonID,$staffMovieID,$staffPositionID,$staffRole){
	$submitStaffNotice="";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn-> prepare("select staff_ID from Staff where person_person_id=? and movie_movie_ID=? and position_position_ID=? and role=?");
	$stmt -> bind_result($positionID);
	$stmt -> bind_param("iiis",$staffPersonID,$staffMovieID,$staffPositionID,$staffRole);
	$stmt -> execute();
	if($stmt->fetch()){
	   $submitStaffNotice="Seos on juba andmebaasis.";
	}elseif($staffRole!=null){
	   $stmt->close();
	   $stmt = $conn -> prepare ("Insert INTO Staff (person_person_ID, movie_movie_ID, position_position_ID, role) VALUES(?,?,?,?)");
	   $stmt -> bind_param("iiis",$staffPersonID,$staffMovieID,$staffPositionID,$staffRole);
	   $stmt -> execute();
	   $submitStaffNotice="Seos on edukalt andmebaasi sisestatud.";
	}else{
	   $stmt->close();
	   $stmt = $conn -> prepare ("Insert INTO Staff (person_person_ID, movie_movie_ID, position_position_ID) VALUES(?,?,?)");
	   $stmt -> bind_param("iii",$staffPersonID,$staffMovieID,$staffPositionID);
	   $stmt -> execute();
	   $submitStaffNotice="Seos on edukalt andmebaasi sisestatud.";
	}
	$stmt -> close();
	$conn -> close();
	return $submitStaffNotice;
  }

  function storeCitationInfo($citationPersonID,$citationMovieID,$citationQuote){//On vaja lisada kontroll, et inimene on filmis näitleja.
	$submitCitationNotice="";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn-> prepare("select Citation_ID from Citation where person_person_id=? and movie_movie_ID=? and citationText=?");
	$stmt -> bind_result($citationID);
	$stmt -> bind_param("iis",$citationPersonID,$citationMovieID,$citationQuote);
	$stmt -> execute();
	if($stmt->fetch()){
	   $submitCitationNotice="Tsitaat on juba andmebaasis.";
	}else{
	   $stmt->close();
	   $stmt = $conn-> prepare("select staff_ID from Staff where person_person_id=? and movie_movie_ID=? and position_position_ID=1");
		$stmt -> bind_result($staffID);
		$stmt -> bind_param("ii",$citationPersonID,$citationMovieID);
		$stmt -> execute();
		if($stmt->fetch()){
			   $stmt->close();
			   $stmt = $conn -> prepare ("Insert INTO Citation (person_person_ID, movie_movie_ID, citationText) VALUES(?,?,?)");
			   $stmt -> bind_param("iis",$citationPersonID,$citationMovieID,$citationQuote);
			   $stmt -> execute();
			   $submitCitationNotice="Tsitaat on edukalt andmebaasi sisestatud.";
		}else{
			$submitCitationNotice="Isikul puudub näitleja roll antud filmis.";
		}
	}
	$stmt -> close();
	$conn -> close();
	return $submitCitationNotice;
  }
?>