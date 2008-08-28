<?php
class Site extends AppModel {
	
	public  $name = "Site";
	public $use_table = false;
	
	public function getemall() {
		return $this->query("SELECT * FROM `sites` WHERE 1");
	}
}
?>