<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

// use new single sign on API
require_once "Gov/Nasa/Jpl/Edrn/Security/EDRNAuth.php";


/**
 * This is a placeholder class.
 * Create the same file in app/app_controller.php
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @link http://book.cakephp.org/view/957/The-App-Controller
 */
class AppController extends Controller {
       
       var $edrnAuth;
       
       public function beforeFilter() {
               // Create an instance of the EDRN Authentication object
               $this->edrnAuth = new Gov_Nasa_Jpl_Edrn_security_EDRNAuthentication();
               // obtain the username of the current user
               $username = @$this->edrnAuth->getCurrentUsername();
               if ($username) {
                       // If a user found, add username to the session
                       $this->Session->write('username',$username);
               } else {
                       // If no user, remove any username from the session
                       $this->Session->delete('username');
               }
       }
 
       function checkSession($afterlogin='/') {
               
               // First check for the magic cookie
               if (@$this->edrnAuth->isLoggedIn()) {
                       // Store the details for the templates to use
                       $this->Session->write('username',@$this->edrnAuth->getCurrentUsername());
                       $this->set('LdapUser',@$this->edrnAuth->getCurrentUsername());
                       // We have a valid user, so just return
                       return;
               } else {
                       // No magic cookie, no party. send them to the login page
                       $this->Session->write('afterlogin',$afterlogin);
                       $this->redirect('/users/login/');
               }
        }

}
