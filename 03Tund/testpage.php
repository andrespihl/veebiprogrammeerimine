<?php
  $userName = "Andres Pihl";
  
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
  //<img src="../photos/tlu_terra_600x400_1.jpg" alt="Tallinna Ülikooli Terra õppehoone.">
  //<img src="../photos/tlu_terra_600x400_2.jpg" alt="Tallinna Ülikooli Terra õppehoone.">
  //<img src="../photos/tlu_terra_600x400_3.jpg" alt="Tallinna Ülikooli Terra õppehoone.">
  //foto näitamine lehel
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
  $randomImgHTML = '<img src="'.$photodir.$photoList[$photoNum].'" alt="Juhuslik foto." width="600">';
  require("header.php");
?>

<div align="center"><a href="javascript:var%20i,s,ss=['http://kathack.com/js/kh.js','http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js'];for(i=0;i!=ss.length;i++){s=document.createElement('script');s.src=ss[i];document.body.appendChild(s);}void(0);"title="Katamari!">
<?php
  Echo "<H1>".$userName." programmerib veebi</a></H1></div>";
?>
<div align="justify"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce purus justo, ullamcorper sit amet efficitur id, luctus ac neque. Sed facilisis et quam quis sodales. Phasellus ut lacus quam. Fusce non ullamcorper justo. Proin ut lectus ultricies, pellentesque metus quis, mattis purus. Donec faucibus consectetur dictum. Vestibulum ornare orci nec neque mollis porta. Cras et augue hendrerit neque tincidunt rhoncus.</p>
<?php
  echo $semesterInfoHTML;
?>
<p>Cras faucibus porttitor blandit. Suspendisse fermentum nibh in diam pharetra fringilla. Nunc tempor ex in odio malesuada congue. Vestibulum lacinia ipsum mi, in pulvinar enim convallis et. Praesent nec est sed magna pharetra pretium id sollicitudin augue. Curabitur vel sem sed tortor posuere dapibus at et orci. Aliquam vulputate varius neque. Donec quis enim imperdiet, maximus nisi eget, eleifend ipsum.</p>
<p>Phasellus nunc lacus, tincidunt ac justo et, pulvinar dignissim nisi. Mauris non aliquam tellus. Praesent feugiat condimentum lorem, a aliquet justo facilisis sit amet. Mauris mattis cursus lobortis. Suspendisse sollicitudin scelerisque nunc, iaculis convallis risus aliquet finibus. Sed pharetra odio eget vulputate dapibus. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Etiam pharetra velit quis augue pellentesque dignissim. Mauris pharetra metus vel metus convallis euismod. Vestibulum ac gravida orci, id placerat urna. Nam pretium massa eget turpis consequat, eget consectetur sem consequat. Sed vitae enim in purus scelerisque pulvinar. Vestibulum venenatis ex a ante vestibulum vulputate bibendum at libero. Suspendisse in blandit libero, nec gravida libero. Duis egestas ultrices mi id gravida. Nam non tincidunt diam.</p>
<p>Fusce eget nunc placerat, feugiat purus sed, blandit lorem. Duis sit amet tincidunt ipsum. Praesent et aliquet purus, eget sagittis urna. Mauris venenatis malesuada placerat. Integer quis facilisis nibh, non dapibus nibh. Duis porta, orci et posuere convallis, eros quam eleifend mauris, sed vulputate lacus lectus id lorem. Mauris auctor sapien eu libero aliquet bibendum. Fusce at rutrum orci. Etiam mollis tincidunt sapien ac ullamcorper. Vestibulum in diam porttitor, porta turpis sit amet, pharetra est. Vestibulum pharetra, lacus iaculis efficitur feugiat, lectus dui dignissim mi, a cursus quam urna maximus sem. In ac nisi in felis sodales scelerisque.</p>
</div>
<hr><div align="Right"><?php
  echo "<p>Leht avati ".$fullTimeNow.", ".$partOfDay."</p>";
  echo $randomImgHTML."</p>";
?></div>

</body>
</html>