<?php
  require("functions.php");
  require("common.php");
  require("header.php");
  $notice=null;
  $maxPicW=600;
  $maxPicH=400;
  if(!isset($_SESSION["userID"])){
	  header("Location: page.php");
	  exit();
  }
  //var_dump($_POST);
  //var_dump($_FILES);
  //pildi üleslaadimise osa____________________________________________________________
  	$uploadOk = 1;
	//$target_dir = "uploads/";
	//$target_file = $target_dir . basename($_FILES["picToUpload"]["name"]);
	if(isset($_POST["submitPic"])) {
		//$target_file = $picUploadDirOrig .$_SESSION["userID"]..basename($_FILES["picToUpload"]["name"]);
		//$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$imageFileType = strtolower(pathinfo($_FILES["picToUpload"]["name"],PATHINFO_EXTENSION));
		$filename="vp_";
		$timeStamp= microtime(1) * 10000;
		$filename.=$timeStamp.".".$imageFileType;
		$targetFile=$picUploadDirOrig.$filename;
	// Check if image file is a actual image or fake image
		$check = getimagesize($_FILES["picToUpload"]["tmp_name"]);
		if($check !== false) {
			$notice .= "<br>File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			$notice .= "<br>File is not an image.";
			$uploadOk = 0;
		}
		// Check if file already exists
		if (file_exists($targetFile)) {
			$notice .= "<br>Sorry, file already exists. ";
			$uploadOk = 0;
		}
		// Check file size
		if ($_FILES["picToUpload"]["size"] > 2500000) {
			$notice .= "<br>Sorry, your file is too large. ";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			$notice .= "<br>Sorry, only JPG, JPEG, PNG & GIF files are allowed. ";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			$notice .= "<br>Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
			//Teeme pildi väiksemaks;
			//loeme pildi sisu pikslikogumiks ehk "pildiobjektiks"
			if($imageFileType=="jpg" or $imageFileType=="jpeg"){
				$myTempImage=imagecreatefromjpeg($_FILES["picToUpload"]["tmp_name"]);
			}
			if($imageFileType=="png"){
				$myTempImage=imagecreatefrompng($_FILES["picToUpload"]["tmp_name"]);
			}
			if($imageFileType=="gif"){
				$myTempImage=imagecreatefromgif($_FILES["picToUpload"]["tmp_name"]);
			}
			$imageW=imagesx($myTempImage);
			$imageH=imagesy($myTempImage);
			if($imageW>$maxPicW or $imageH>$maxPicH){
				if($imageW/$maxPicW>$imageH/$maxPicH){
					$picSizeRatio=$imageW/$maxPicW;
				}else{
					$picSizeRatio=$imageH/$maxPicH;
				}
				$imageNewW=round($imageW/$picSizeRatio,0);
				$imageNewH=round($imageH/$picSizeRatio,0);
				$myNewImage=setPicSize($myTempImage,$imageW,$imageH,$imageNewW,$imageNewH);
				//kirjutame vähendatud pildi faili
				if($imageFileType=="jpg" or $imageFileType=="jpeg"){
					if(imagejpeg($myNewImage,$picUploadDirW600.$filename,90)){
						$notice.="<br>Vähendatud faili salvestamine õnnestus. ";
					}else{
						$notice.="<br>Vähendatud faili salvestamine ei õnnestunud. ";	
					}
				}
				if($imageFileType=="png"){
					if(imagepng($myNewImage,$picUploadDirW600.$filename,6)){
						$notice.="<br>Vähendatud faili salvestamine õnnestus. ";
					}else{
						$notice.="<br>Vähendatud faili salvestamine ei õnnestunud. ";	
					}
				}
				if($imageFileType=="gif"){
					if(imagegif($myNewImage,$picUploadDirW600.$filename)){
						$notice.="<br>Vähendatud faili salvestamine õnnestus. ";
					}else{
						$notice.="<br>Vähendatud faili salvestamine ei õnnestunud. ";	
					}
				}
				imagedestroy($myTempImage);
				imagedestroy($myNewImage);
			}//kas on liiga suur lõppeb
			
			if (move_uploaded_file($_FILES["picToUpload"]["tmp_name"], $targetFile)) {
				$notice .= "<br>The file ". basename( $_FILES["picToUpload"]["name"]). " has been uploaded.";
			} else {
				$notice .= "<br>Sorry, there was an error uploading your file.";
			}
		}
	}
  //___________________________________________________________________________________
?>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
	  <label>Vali üleslaetav pildifail.</label><br>
	  <input type="file" name="picToUpload" id="picToUpload">
	  <br>
	  <input name="submitPic" type="submit" value="Lae pilt üles!"><span><?php echo $notice;?></span>
	</form>

<?php

require("footer.php");
?>
</body>
</html>