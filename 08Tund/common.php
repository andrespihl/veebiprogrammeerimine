<?php
  $photodir = "../photos/";
  $photoTypes = ["image/jpeg","image/png"];
  
  $fullTimeNow = date ("d.m.Y H:i:s");
  $hourNow = date ("H");
  $partOfDay = "õhtul.";
  if($hourNow <= 18){
	$partOfDay = "pärastlõunal.";
  }
  if($hourNow < 12){
	$partOfDay = "hommikul.";
  }
  if($hourNow >= 23 or $hourNow < 6){
	$partOfDay = "öösel.";
  }
  //info semestri kulgemise kohta.
  $semesterStart = new DateTime("2019-9-2");
  $semesterEnd = new DateTime("2019-12-13");
  $semesterDuration = $semesterStart -> diff($semesterEnd);
  $today = new DateTime("now");
  $semesterElapsed = $semesterStart -> diff($today);
  //echo $semesterStart; (ei tööta!)
  //var_dump($today);
  //<p>Semester on täies hoos: <meter min="0" max="112" value="16">13%</meter></p>
  $semesterInfoHTML = null;
  if($semesterElapsed-> format("%r%a") >= 0) {
	$semesterInfoHTML = '<p>Semester on täies hoos: ';
	$semesterInfoHTML .= '<meter width="240" min="0" max="' .$semesterDuration-> format("%r%a").'" ';
	$semesterInfoHTML .= 'value="' .$semesterElapsed-> format("%r%a").'">';
	$semesterInfoHTML .= round($semesterElapsed-> format("%r%a") / $semesterDuration-> format("%r%a") * 100, 1)."%</meter></p>";
  }
  if($semesterElapsed-> format("%r%a") > $semesterDuration-> format("%r%a")) {
	$semesterInfoHTML = "<p>Semester on läbi.</p>";
  }
  if($today -> format("%r%a") < $semesterStart -> format("%r%a")) {
	$semesterInfoHTML = "<p>Semester on läbi.</p>";
  }
  $fileList = array_slice(scandir($photodir),2);
  $photoList = [];
  foreach ($fileList as $file){
	$fileInfo = getImagesize($photodir.$file);
	if (in_array($fileInfo["mime"],$photoTypes)){
		array_push($photoList, $file);
	}
  }
  
  //$photoList = ["tlu_terra_600x400_1.jpg","tlu_terra_600x400_2.jpg","tlu_terra_600x400_3.jpg"];//array ehk massiiv
  $photoCount = count($photoList);
  $photoNum = mt_rand(0,$photoCount-1);
  $randomImgHTML = '<img src="'.$photodir.$photoList[$photoNum].'" alt="Rebane." width="600">';
?>
  <style type="text/css">
	body {
		font-family: comic-sans;
		width: 1080px;
		margin: 10px auto;
		<?php
		if(isset($_SESSION["userColor"])){
			echo "background-color: ".$_SESSION["userBG"]."; 
			color: ".$_SESSION["userColor"].";";
		}else{
			echo "background-color: #f0e1b9; 
			color: #000000;";
	} ?>
	}
    h1 {
     margin-top: 60px;
    }
	table, th, td {
		border: 1px solid black;
		border-collapse: collapse;
	}
  </style>