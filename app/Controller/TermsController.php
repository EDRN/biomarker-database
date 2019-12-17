<?php
class TermsController extends AppController {
	var $name    = "Terms";
	var $helpers = array("Html","Js");
	var $uses    = array('Term');
	
	function index() {
		$this->checkSession('/terms');
		$this->set('terms',$this->Term->find('all'));
	}
	
	function redirection() {
		$data = &$this->request->data;
		if ($data['id']) {
			$this->redirect("/terms/view/{$data['id']}");
		} else {
			$this->redirect("/terms");
		}
	}
	
	function define() {
		$this->checkSession('/terms/define');
		
		// If form data is provided, attempt to add a new term
		if (isset($this->request->data['label']) && isset($this->request->data['definition'])) {
			
			// Check for existing term (uniqueness check)
			$term = $this->Term->find('first',array(
				'conditions' => array("Term.label" => $this->request->data['label']),
				'recursive'  => 1
				)
			);
			if (is_array($term) && isset($term['Term'])) {
				// Provide an explanation message to the user
				die("<b>Error:</b> The term '{$this->request->data['label']}' has 
					already been defined");
			} else {
				// Create the term in the database
				$this->Term->create(array(
					"label" => $this->request->data['label'],
					"definition" => $this->request->data['definition']));
				$this->Term->save();
			
				$this->redirect("/terms");
			}
		}
		
		// Otherwise, just show the web form
	}
	
	function view($id) {
		$this->checkSession("/terms/view/{$id}");
		$this->Term->id = $id;
		$this->set('term', $this->Term->read());
	}
	
	function edit($id = null) {
		$this->checkSession("/terms");
		// If form data is present, attempt to update the term
		if (isset($this->request->data['id'])) {
			$this->Term->id = $this->request->data['id'];
			$this->Term->saveField('definition',$this->request->data['definition']);
			$this->redirect("/terms/view/{$this->Term->id}");
		}
		
		// Otherwise just show the update form
		$this->checkSession("/terms/view/{$id}");
		$this->Term->id = $id;
		$this->set('term', $this->Term->read());
	}
	
	function delete($id) {
		$this->Term->id = $id;
		$this->Term->delete();
		$this->redirect("/terms/");
	}
	
	/******************************************************************
	 * AJAX
	 ******************************************************************/
	function ajax_autocompleteTerms () {
		$data =& $this->request->data;
		$needle  = $data['needle'];
		$results = $this->Term->query("SELECT `label` AS `label`,`id` AS `id` FROM `terms` WHERE `label` LIKE '%{$needle}%'");
		$rstr = '';
		
		foreach ($results as $r) {
			$rstr .= "<li><span id=\"{$r['terms']['id']}\">{$r['terms']['label']}</span></li>";	
			
		}
		echo ($rstr);
		die();
	}
	/******************************************************************
	 * Autocomplete source
	 ******************************************************************/
	function getAutocompleteTerms() {
		$search = "";
		if ($_GET['term'] != "") {
				$search = $_GET['term'];
		}

		$results = $this->Term->query("SELECT `label` as `label`, `id` as `id` FROM `terms` where `label` LIKE '%{$search}%' ORDER BY `label`");
		$returnString = array();
		foreach ($results as $r) {
				array_push($returnString, "{$r['terms']['label']}|{$r['terms']['id']}");
		}

		echo json_encode($returnString);
		die();
	}
}
