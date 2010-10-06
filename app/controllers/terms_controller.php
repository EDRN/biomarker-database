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
}
