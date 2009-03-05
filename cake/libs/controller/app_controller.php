<?php
/* SVN FILE: $Id: app_controller.php 7296 2008-06-27 09:09:03Z gwoo $ */
/**
 * Short description for file.
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.cake.libs.controller
 * @since			CakePHP(tm) v 0.2.9
 * @version			$Revision: 7296 $
 * @modifiedby		$LastChangedBy: gwoo $
 * @lastmodified	$Date: 2008-06-27 02:09:03 -0700 (Fri, 27 Jun 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * This is a placeholder class.
 * Create the same file in app/app_controller.php
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		cake
 * @subpackage	cake.cake.libs.controller
 */

// use new single sign on API
require_once "Gov/Nasa/Jpl/Edrn/Security/EDRNAuth.php";

class AppController extends Controller {
	
	protected $edrnAuth;
	
	public function __construct() {
		parent::__construct();
		// Create an instance of the EDRN Authentication object
		$this->edrnAuth = new Gov_Nasa_Jpl_Edrn_security_EDRNAuthentication();
		// obtain the username of the current user
		$username = @$this->edrnAuth->getCurrentUsername();
		if ($username) {
			// If a user found, add username to the session
			$_SESSION['username'] = $username;
		} else {
			// If no user, remove any username from the session
			unset($_SESSION['username']);
		}
	}
	
	
	function checkSession($afterlogin='/') {
		
		// First check for the magic cookie
		if (@$this->edrnAuth->isLoggedIn()) {
			// Store the details for the templates to use
			$this->Session->write('username',@$edrnAuth->getCurrentUsername());
			$this->set('LdapUser',@$edrnAuth->getCurrentUsername());
			// We have a valid user, so just return
			return;
		} else {
			// No magic cookie, no party. send them to the login page
			$this->Session->write('afterlogin',$afterlogin);
            $this->redirect('/users/login/');
		}
    }
}
?>