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

	public function runSiteSearch($where, $limit, $order) {
		$q = 'SELECT SQL_CALC_FOUND_ROWS id, site_id, name ' .
			'FROM sites ' .
			$where.
			$order.
			$limit;
		return $this->query($q);
	}
	
	public function getFilteredTotal() {
		$q = 'SELECT FOUND_ROWS() as filteredCount';
		return $this->query($q);
	}

	public function getSiteCount() {
		$q = 'SELECT count(Site.id) as numSite FROM sites as Site';
		return $this->query($q);
	}
}
?>
