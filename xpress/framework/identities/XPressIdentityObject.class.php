<?php
/*
 * Copyright 2006-2008 Crawwler Software Development.
 * http://www.crawwler.com
 * 
 * Project: CWSP - XPress
 * File: XPressIdentityObject.class.php
 * Created on February 27, 2008
 * Author: andrew
 *
 */

class XPressIdentityObject {
	private $xiuser      = '';
	private $user        = '';
	private $bIsLoggedIn = false;
	
	private $auth;
	private $authOptions = array (
		"dbFields" => array("objId","ObjectClass","ObjectId"),
		"table"    => "Xiuser",
		"unColumn" => "UserName",
		"pwColumn" => "Password");

	public function __construct() {
		// create an auth object
		$this->auth =& new XPressAuthWrapper($this->authOptions);
		// check if a user is logged in
		$this->bIsLoggedIn = $this->auth->checkLoginStatus();
	}
	
	public function initialize($userId) {
		// if a user is logged in, get the stored info for that user
		if (true == $this->bIsLoggedIn) {
			if (false != ($this->xiuser = XiuserFactory::retrieve($userId))) {
				// Retrieve the corresponding model object with data for this user
				$this->user = ObjectBroker::get($this->xiuser->getObjectClass(),$this->xiuser->getObjectId());
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function auth_log_in() {
		// username and password are sent via the POST variables
		// "username" and "password"
		if (true == ($this->bIsLoggedIn = $this->auth->checkLoginStatus())) {
			$this->initialize(
				XPressSession::get(array("_authsession","data","objId")));
			$this->xiuser->setLastLogin(date("Y-m-d h:i:s",mktime()));
		}
		return $this->bIsLoggedIn;
	}
	
	public function auth_log_out() {
		// If Logged in, Log out
		if ($this->auth->checkLoginStatus()){
        	$this->auth->endSession();
        	XPressSession::clear("_authsession");
        	XPressSession::destroy();
		}
		$this->bIsLoggedIn = false;
	}

	/**
	 * ValidUser()
	 *  - Is a valid user logged in to the application?
	 *  - returns: boolean (true=yes)
	 **/
	public function validUser() {
		return $this->bIsLoggedIn;
	}
	
	/**
	 * RequireGroup()
	 *  - Is the currently logged in user a member of the 
	 *    provided group id?
	 *  - takes: int (group id)
	 *  - returns: boolean (true=yes) 
	 *  - notes: returns false if no user logged in
	 **/
	public function requireGroup($groupId) {
		if (!$this->bIsLoggedIn) {
			return false;
		} else {
			foreach ($this->userGetGroups() as $g) {
				if ($groupId == $g->getObjId()) {
					return true;
				} 
			}
			return false;
		}
	}
	
	/**
	 * RequireGroupByName()
	 *  - Is the currently logged in user a member of the 
	 *    provided group name?
	 *  - takes: string (group name)
	 *  - returns: boolean (true=yes) 
	 *  - notes: returns false if no user logged in
	 **/
	public function requireGroupByName($groupName) {
		if (!$this->bIsLoggedIn) {
			return false;
		} else {
			foreach ($this->userGetGroups() as $g) {
				if ($groupName == $g->getName()) {
					return true;
				} 
			}
			return false;
		}
	}
	
	/**
	 * RequireUser()
	 *  - Determines whether the currently logged in user meets
	 *    the specified criteria
	 *  - takes: int (user id)
	 *  - returns: boolean (true=yes) 
	 *  - notes: requireUser() with no user id specified
	 *    is equivalent to validUser()
	 **/
	public function requireUser($userId=0) {
		if (!$this->bIsLoggedIn) {
			return false;
		} else {
			if ($userId == 0) {
				// If userId == 0, the page requires ANY user (i.e.:
				// a page protected by a generic login)
				return true;
			}
			if (empty($this->user)) {
				$this->initialize(
					XPressSession::get(array("_authsession","data","objId")));
			}
			// Compare provided objId (from the user (not xiuser) object) to 
			// the initialized user (not xiuser) object
			if ($userId == $this->user->getObjId()){
				return true;
			} else {
				return false;
			}
		}
	}
	
	/**
	 * RequireUserByUsername()
	 *  - Determines whether the currently logged in user meets
	 *    the specified criteria
	 *  - takes: string (username)
	 *  - returns: boolean (true=yes) 
	 *  - notes: returns false if no user logged in
	 **/
	public function requireUserByUsername($username) {
		if (!$this->bIsLoggedIn) {
			return false;
		} else {
			if (empty($this->user)) {
				$this->initialize(
					XPressSession::get(array("_authsession","data","objId")));
			}
			if ($username == $this->xiuser->getUsername()){
				return true;
			} else {
				return false;
			}
		}		
	}
		
	/**
	 * RequireRole()
	 *  - Determines whether the currently logged in user meets
	 *    the specified criteria (holds the given role)
	 *  - takes: int (role id)
	 *  - returns: boolean (true=yes) 
	 *  - notes: returns false if no user logged in
	 **/
	public function requireRole($roleId) {
		if (!$this->bIsLoggedIn) {
			return false;
		} else {
			foreach ($this->userGetRoles() as $r) {
				if ($roleId == $r->getObjId()){
					return true;
				} 
			}
			return false;
		}
	}
		
	/**
	 * RequireRoleByName()
	 *  - Determines whether the currently logged in user meets
	 *    the specified criteria (holds the given role)
	 *  - takes: string (role name)
	 *  - returns: boolean (true=yes) 
	 *  - notes: returns false if no user logged in
	 **/
	public function requireRoleByName($roleName) {
		if (!$this->bIsLoggedIn) {
			return false;
		} else {
			foreach ($this->userGetRoles() as $r) {
				if ($roleName == $r->getName()){
					return true;
				} 
			}
			return false;
		}		
	}

	/**
	 * USER -- GROUP MANAGEMENT
	 */

	/**
	 * UserGetGroups()
	 *  - Returns an array of Xigroup objects to which the 
	 *    currently logged in user belongs
	 *  - returns: (Xigroup)array()
	 *  - notes: returns false if no user logged in
	 */
	public function userGetGroups() {
		if (!$this->bIsLoggedIn) {
			return false;
		}
		if (empty($this->user)) {
			$this->initialize(
				XPressSession::get(array("_authsession","data","objId")));
		}
		return $this->xiuser->getGroups();		
	}
	
	/**
	 * UserAddGroup()
	 *  - Add the provided group id to the provided user id
	 *  - takes: int (group id)
	 *  - returns: boolean (true=success)
	 *  - notes: uses the currently logged in user if no user
	 *    id is provided
	 */
	public function userAddGroup($groupId,$userId = 0) {
		if ($userId <= 0 && !$this-bIsLoggedIn ) {
			return false; // no user specified and no user logged in
		}
		if ($userId == 0 && $this->bIsLoggedIn) {
			if (empty($this->user)) {
				$this->initialize(
					XPressSession::get(array("_authsession","data","objId")));
			}
			return $this->xiuser->link(XiuserVars::GROUPS,$groupId,XigroupVars::USERS);
		}
		// specific user id given:
		if (false !== ($xu = XiuserFactory::retrieve($userId))) {
			return $xu->link(XiuserVars::GROUPS,$groupId,XigroupVars::USERS);
		}
	}
	
	/**
	 * UserAddGroupByName()
	 *  - Add group with the provided name to the user matching the
	 *    provided id
	 *  - takes: string (group name)
	 *  - returns: boolean (true=success)
	 *  - notes: uses the currently logged in user if no user
	 *    id is provided
	 */
	public function userAddGroupByName($groupName,$userId = 0) {
		if ($userId <= 0 && !$this->bIsLoggedIn) {
			return false; // no user specified and no user logged in
		}
		// Try to get the group id matching provided group name
		$gid = XPress::getInstance()->getDatabase()->safeGetOne(
			"SELECT `objId` FROM `Xigroup` WHERE `Name`=\"{$groupName}\" ");
		if ($gid <= 0) {
			return false; // no group matching provided group name
		}
		if ($userId == 0 && $this->bIsLoggedIn) {
			if (empty($this->user)) {
				$this->initialize(
					XPressSession::get(array("_authsession","data","objId")));
			}
			return $this->xiuser->link(XiuserVars::GROUPS,$gid,XigroupVars::USERS);
		}
		// specific user id given	
		if (false !== ($xu = XiuserFactory::retrieve($userId))) {
			return $xu->link(XiuserVars::GROUPS,$gid,XigroupVars::USERS);
		}
	}
	
	/**
	 * UserRemoveGroup()
	 *  - Remove group with the provided id from the currently logged 
	 *    in user (or the provided user, if provided)
	 *    provided id
	 *  - takes: int (group id)
	 *  - returns: boolean (true=success)
	 *  - notes: uses the currently logged in user if no user
	 *    id is provided
	 */
	public function userRemoveGroup($groupId,$userId = 0) {
		if ($userId <= 0 && !$this->bIsLoggedIn) {
			return false; // no user specified and no user logged in
		}
		
		if ($userId == 0 ) {
			// Remove group from the logged in user
			if (empty($this->user)) {
				$this->initialize(
					XPressSession::get(array("_authsession","data","objId")));
			}
			return $this->xiuser->unlink(XiuserVars::GROUPS,$groupId);
		}	
		// Remove group from the specified user
		if (false !== ($xu = XiuserFactory::retrieve($userId))) {
			return $xu->unlink(XiuserVars::GROUPS,$groupId);	
		}
	}
	
		/**
	 * UserRemoveGroup()
	 *  - Remove group with the provided name from the currently logged 
	 *    in user (or the provided user, if provided)
	 *    provided name
	 *  - takes: string (group name)
	 *  - returns: boolean (true=success)
	 *  - notes: uses the currently logged in user if no user
	 *    id is provided
	 */
	public function userRemoveGroupByName($groupName,$userId = 0) {
		if ($userId <= 0 && !$this->bIsLoggedIn) {
			return false; // no user specified and no user logged in
		}
		
		// Try to get the group id matching provided group name
		$gid = XPress::getInstance()->getDatabase()->safeGetOne(
			"SELECT `objId` FROM `Xigroup` WHERE `Name`=\"{$groupName}\" ");
		if ($gid <= 0) {
			return false; // no group matching provided group name
		}
		
		if ($userId == 0 ) {
			// Remove group from the logged in user
			if (empty($this->user)) {
				$this->initialize(
					XPressSession::get(array("_authsession","data","objId")));
			}
			return $this->xiuser->unlink(XiuserVars::GROUPS,$groupId);
		}	
		// Remove group from the specified user
		if (false !== ($xu = XiuserFactory::retrieve($userId))) {
			return $xu->unlink(XiuserVars::GROUPS,$groupId);	
		}
	}
	
	/**
	 * USER -- ROLE MANAGEMENT
	 */
	public function userGetRoles() {
		if (empty($this->user)) {
			$this->initialize(
				XPressSession::get(array("_authsession","data","objId")));
		}		
	}
	
	public function userAddRole($roleId) {
		if (empty($this->user)) {
			$this->initialize(
				XPressSession::get(array("_authsession","data","objId")));
		}		
	}
	
	public function userAddRoleByName($roleName) {
		if (empty($this->user)) {
			$this->initialize(
				XPressSession::get(array("_authsession","data","objId")));
		}		
	}
	
	public function userRemoveRole($roleId) {
		if (empty($this->user)) {
			$this->initialize(
				XPressSession::get(array("_authsession","data","objId")));
		}			
	}
	
	public function userRemoveRoleByName($roleName) {
		if (empty($this->user)) {
			$this->initialize(
				XPressSession::get(array("_authsession","data","objId")));
		}			
	}
	
	
	
	/**
	 * GROUP MANAGEMENT
	 */
	public function createGroup($groupName) {
		
	}
	
	public function removeGroup($groupId) {
		
	}
	
	public function removeGroupByName($groupName) {
		
	}

	/**
	 * USER MANAGEMENT
	 */
	public function createUser(&$obj,$username,$password,$email = '') {
		$e_addr = ($email == '') 
			? md5($username)
			: $email;
		$xu = XiuserFactory::create($username,$e_addr);
		$xu->setObjectClass($obj->_getType());
		$xu->setObjectId($obj->getObjId());
		$xu->setPassword(md5($password));
		$xu->setStatus("Pending Confirmation");
		$xu->setCreated(date("Y-m-d h:i:s",mktime()));
		
		// Generate random key and send email to addr for confirmation

		// Return the XIUser object
		return $xu;
	}

	public function usernameExists($userName) {
		$q = "SELECT COUNT(*) FROM `Xiuser` WHERE `UserName`=\"{$userName}\" ";
		$r = XPress::getInstance()->getDatabase()->safeGetOne($q);
		return ($r > 0);
	}
	
	public function emailExists($emailAddress) {
		$q = "SELECT COUNT(*) FROM `Xiuser` WHERE `EmailAddress`=\"{$emailAddress}\" ";
		$r = XPress::getInstance()->getDatabase()->safeGetOne($q);
		return ($r > 0);
	}
	
	public function removeUser($userId) {
		
	}
	
	public function removeUserByUsername($username) {
		
	}
	
	public function getUser() {
		if (empty($this->user)) {
			$this->initialize(
				XPressSession::get(array("_authsession","data","objId")));
		}
		return $this->user;
	}
	
	public function user() {
		// For now this is a clone of getUser() with the added overhead
		// of an extra function call. In the future this will be remedied
		// TODO: make user() a true clone of getUser() by factoring out init stuff
		return $this->getUser();	
	}
	
	public function getUserByUsername($username) {
		return XiuserFactory::retrieveByUniqueKey(XiuserVars::USERNAME,$username);
	}
	
	public function getUserData($object = null) {
		if ($object == null) {
			// A null object implies the user wants user data on the 
			// currently logged-in user. If no one is logged in, return false,
			// otherwise return their information
			if ($this->bIsLoggedIn) {
				if (empty($this->user)) {
					$this->initialize(
					XPressSession::get(array("_authsession","data","objId")));
				}
				return array("Username"=>$this->xiuser->getUsername(),
							"Email"=>$this->xiuser->getEmailAddress());
			} else {
				return false;
			}
		}
		$id = $object->getObjId();
		$class= $object->_getType();
		
		$q = "SELECT * FROM `Xiuser` WHERE ObjectClass=\"{$class}\" AND ObjectId=\"{$id}\" ";
		$r = XPress::getInstance()->getDatabase()->safeGetRow($q);
		return array("Username"=>$r['UserName'],"Email"=>$r['EmailAddress']);
	}

	/**
	 * ROLE MANAGEMENT
	 */
	public function createRole($roleName) {
		$xr = XiroleFactory::create($roleName);
		return $xr;
	}
	
	public function removeRole($roleId) {
		
	}
	
	public function removeRoleByName($roleName) {
				
	}
}
?>