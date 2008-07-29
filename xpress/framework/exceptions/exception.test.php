<?php
	require_once("XPressException.class.php");
	
	define("XPRESS_DEBUG",true);
	
	class ErrorThrower {
		
		public function __construct() {
			
		}
		
		public function justThrowIt() {
			$this->throwError();
		}
		
		private function throwError() {
			throw new XPressException("XPressDB",
				"ConnectionException",
				"An error was encountered while trying to connect to the database",
				32);
		}
	}
	
	
	try {
		$et = new ErrorThrower;
		$et->justThrowIt();
		
	} catch (XPressException $e) {
		echo $e->getFormattedMessage();
	} 
?>