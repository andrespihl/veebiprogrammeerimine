<?php
	class PicUpload {
		private $imageFileType;
		private $tempImageFile;
		private $myTempImage;
		private $myNewImage;
		
		
		function __construct($tempImageFile,$imageFileType){
			$this->imageFileType=$imageFileType;
			$this->tempImageFile=$tempImageFile;
			$this->createImageFromFile();
		}//==================================================================================	Construct Lõppeb
		
		function __deconstruct(){
			imagedestroy($this->myTempImage);
		}//==================================================================================	Deconstruct Lõppeb
		
		
		private function createImageFromFile(){
			if($this->imageFileType=="jpg" or $this->imageFileType=="jpeg"){
				$this->myTempImage=imagecreatefromjpeg($this->tempImageFile);
			}
			if($this->imageFileType=="png"){
				$this->myTempImage=imagecreatefrompng($this->tempImageFile);
			}
			if($this->imageFileType=="gif"){
				$this->myTempImage=imagecreatefromgif($this->tempImageFile);
			}
		}//==================================================================================	createImageFromFile Lõppeb
		
		public function resizeImage($maxPicW,$maxPicH){
			$imageW=imagesx($this->myTempImage);
			$imageH=imagesy($this->myTempImage);
			if($imageW>$maxPicW or $imageH>$maxPicH){
				if($imageW/$maxPicW>$imageH/$maxPicH){
					$picSizeRatio=$imageW/$maxPicW;
				}else{
					$picSizeRatio=$imageH/$maxPicH;
				}
				$imageNewW=round($imageW/$picSizeRatio,0);
				$imageNewH=round($imageH/$picSizeRatio,0);
				$this->myNewImage=setPicSize($this->myTempImage,$imageW,$imageH,$imageNewW,$imageNewH);
			}
		}//==================================================================================	resizeImage Lõppeb
		
		private function setPicSize($myTempImage,$imageW,$imageH,$imageNewW,$imageNewH){
			$this->myNewImage = imagecreatetruecolor($imageNewW,$imageNewH);
			imagecopyresampled($myNewImage,$myTempImage,0,0,0,0,$imageNewW,$imageNewH,$imageW,$imageH);
			
			return $myNewImage;
		}

		
		public function addWatermark($wmFile){
			$watermark=imagecreatefrompng($wmFile);
			$watermarkW=imagesx($watermark);
			$watermarkH=imagesy($watermark);
			$watermarkX=imagesx($this->myNewImage)-$watermarkW-10;
			$watermarkY=imagesy($this->myNewImage)-$watermarkH-10;
			imagecopy($this->myNewImage,$watermark,$watermarkX,$watermarkY,0,0,$watermarkW,$watermarkH);
		}//==================================================================================	addWatermark Lõppeb
			
			
			
		public function saveImageFile($filename){	
			if($this->imageFileType=="jpg" or $this->imageFileType=="jpeg"){
				if(imagejpeg($this->myNewImage,$filename,90)){
					$notice="<br>Vähendatud faili salvestamine õnnestus. ";
				}else{
					$notice="<br>Vähendatud faili salvestamine ei õnnestunud. ";	
				}
			}
			if($this->imageFileType=="png"){
				if(imagepng($this->myNewImage,$filename,6)){
					$notice="<br>Vähendatud faili salvestamine õnnestus. ";
				}else{
					$notice="<br>Vähendatud faili salvestamine ei õnnestunud. ";	
				}
			}
			if($this->imageFileType=="gif"){
				if(imagegif($this->myNewImage,$filename)){
					$notice="<br>Vähendatud faili salvestamine õnnestus. ";
				}else{
					$notice="<br>Vähendatud faili salvestamine ei õnnestunud. ";	
				}
			}
			imagedestroy($this->myNewImage);
			return $notice;
		}//==================================================================================	saveImageFile Lõppeb

		
		
	}//======================================================================================	Lõppeb PicUpload class