<?php
	class Test{
		//muutujad ehk properties
		private $privateNumber;
		public $publicNumber;
		//meetodid/funktsioonid ehk methods
		//constructor, see on funktsioon, mis käivitub üks kord, klassi kasutusele võtmisel.
		function __construct($inputNumber){
			$this->privateNumber=666;
			$this->publicNumber=$inputNumber;
			echo "Salajase ja avaliku arvu korrutis on: ".$this->privateNumber * $this->publicNumber;
			//$this->tellSecret();
		}
		//destructor, mis käivitatakse, kui klass eemaldatakse (töö lõpp)
		function __destruct(){
			echo "<br>Class has been terminated.";
		}
		private function tellSecret(){
			echo "<br>Salajane number on: ".$this->privateNumber;
		}
		public function tellPublicSecret(){
			echo "<br>Salajane number on: ".$this->privateNumber.". Aga see on saladus!";
		}
	}//class lõppeb