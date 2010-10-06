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
		
		// If form data is provided, attempt to add a new term
		if (isset($this->params['form']['label']) && isset($this->params['form']['definition'])) {
			
			// Check for existing term (uniqueness check)
			$term = $this->Term->find('first',array(
				'conditions' => array("Term.label" => $this->params['form']['label']),
				'recursive'  => 1
				)
			);
			if (is_array($term) && isset($term['Term'])) {
				// Provide an explanation message to the user
				die("<b>Error:</b> The term '{$this->params['form']['label']}' has 
					already been defined");
			} else {
				// Create the term in the database
				$this->Term->create(array(
					"label" => $this->params['form']['label'],
					"definition" => $this->params['form']['definition']));
				$this->Term->save();
			
				$this->redirect("/terms");
			}
		}
		
		// Otherwise, just show the web form
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
