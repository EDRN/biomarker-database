<?php

class UsersController extends AppController {
	
	public $uses = array('LdapUser','User');
	
	function index() {
		$this->redirect("/users/login");
		exit();
	}
	
	function login() {
		$this->set('error',false);
		if (count($this->params['form']) >0) {
			// Process a login attempt
			$data = &$this->params['form'];
			if (!isset($data['username']) || !isset($data['password'])
					|| $data['username'] =='' || $data['password'] == '') {
				// Incomplete data provided
				$this->set('error',true);
			} else {
				// Test provided data for validity
				/* if ($this->LdapUser->auth($data['username'],$data['password'])) {
				 * 
				 * Use the new Single-Sign-On API:
				 */
				$edrnAuth = new Gov_Nasa_Jpl_Edrn_Security_EDRNAuthentication();
				if (@$edrnAuth->login($data['username'],$data['password'])) {
					// Passed! Valid user
					$this->Session->write('username',$data['username']);
					if($this->Session->check('afterlogin')){
						$next = $this->Session->read('afterlogin');
						$this->Session->delete('afterlogin');
						$this->redirect($next);
					} else {
						$this->redirect("/");	
					}
				} else {
					// Failed! Invalid credentials
					$this->set('error',true);
				}
			}
			
		} else {
			// Display the login form
		}

	}
	
	function logout() {
		$this->Session->delete('username');
		$this->redirect("/");
	}
	
	
}
?>