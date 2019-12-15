<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

// use new single sign on API
require_once "Gov/Nasa/Jpl/Edrn/Security/EDRNAuth.php";

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
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
