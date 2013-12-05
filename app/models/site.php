<?php
class Site extends AppModel {
	
	public $name = "Site";
	
	public function getemall() {
		return $this->query("SELECT * FROM `sites` WHERE 1");
	}

	public function getAllSites() {
		return $this->query("SELECT * FROM `sites` WHERE 1");
	}

	public function getMaxId() {
		$q = "SELECT MAX(`site_id`) FROM `sites`";
		return($this->query($q));
	}
}
?>
