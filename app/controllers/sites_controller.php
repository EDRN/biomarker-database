<?php
class SitesController extends AppController {

	var $uses = array('Site')

	function index() {
		$this->checkSession("/sites");

		$sites = $this->Site->getAllSites();
	}
}
?>
