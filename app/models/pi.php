<?php
class Pi extends AppModel {
	
	public  $name = "Pi";
	public $use_table = false;
	
	public function getemall() {
		return $this->query("SELECT * FROM `pis` WHERE 1");
	}
}
?>