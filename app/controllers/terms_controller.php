<?php
class TermsController extends AppController {
	var $name    = "Terms";
	var $helpers = array("Html","Ajax","Javascript");
	var $uses    = array('Term');
	
	function index() {
		$this->checkSession('/terms');
		$this->set('terms',$this->Term->findAll());
	}
	
	function redirection() {
		$data = &$this->params['form'];
		if ($data['id']) {
			$this->redirect("/terms/view/{$data['id']}");
		} else {
			$this->redirect("/terms");
		}
	}
	
	function define() {
		
	}
	
	/******************************************************************
	 * AJAX
	 ******************************************************************/
	function ajax_autocompleteTerms () {
		$data =& $this->params['form'];
		$needle  = $data['needle'];
		$results = $this->Term->query("SELECT `label` AS `label`,`id` AS `id` FROM `terms` WHERE `label` LIKE '%{$needle}%'");
		$rstr = '';
		
		foreach ($results as $r) {
			$rstr .= "<li><span id=\"{$r['terms']['id']}\">{$r['terms']['label']}</span></li>";	
			
		}
		echo ($rstr);
		die();
	}
}
