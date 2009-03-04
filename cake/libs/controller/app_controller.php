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
	
	function checkSession($afterlogin='/') {
		
		// First check for the magic cookie
		$edrnAuth = new Gov_Nasa_Jpl_Edrn_Security_EDRNAuthentication();
		if ($edrnAuth->isLoggedIn()) {
			// Store the details for the templates to use
			$this->set('LdapUser','[SingleSignOn User]');
			// We have a valid user, so just return
			return;
		}
		
		// Then check the session
        if (!$this->Session->check('username'))
        {
            // If the session info hasn't been set, force the user to login
			$this->Session->write('afterlogin',$afterlogin);
            $this->redirect('/users/login/');
            exit();
        } else {
            // Store the details for the templates to use
            $this->set('LdapUser',$this->Session->read('LdapUser'));
        }
    }
	
	
}
?>