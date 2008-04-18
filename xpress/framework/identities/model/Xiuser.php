<?php


class XiuserVars {
	const OBJID = "objId";
	const OBJECTCLASS = "ObjectClass";
	const OBJECTID = "ObjectId";
	const USERNAME = "UserName";
	const PASSWORD = "Password";
	const EMAILADDRESS = "EmailAddress";
	const CREATED = "Created";
	const STATUS = "Status";
	const LASTLOGIN = "LastLogin";
	const GROUPS = "Groups";
	const ROLES = "Roles";
}

class XiuserFactory {
	public static function Create($UserName,$EmailAddress){
		$o = new Xiuser();
		$o->UserName = $UserName;
		$o->EmailAddress = $EmailAddress;
		$o->save();
		return $o;
	}
	public static function Retrieve($value,$key = XiuserVars::OBJID,$fetchStrategy = XPress::FETCH_LOCAL) {
		$o = new Xiuser();
		switch ($key) {
			case XiuserVars::OBJID:
				$o->objId = $value;
				$q = "SELECT * FROM `Xiuser` WHERE `objId`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) {return false;}
				if (! is_array($data)) {return false;}
				$o->setObjectClass($data['ObjectClass'],false);
				$o->setObjectId($data['ObjectId'],false);
				$o->setUserName($data['UserName'],false);
				$o->setPassword($data['Password'],false);
				$o->setEmailAddress($data['EmailAddress'],false);
				$o->setCreated($data['Created'],false);
				$o->setStatus($data['Status'],false);
				$o->setLastLogin($data['LastLogin'],false);
				return $o;
				break;
			case XiuserVars::USERNAME:
				$o->setUserName($value,false);
				$q = "SELECT * FROM `Xiuser` WHERE `UserName`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) { return false;}
				if (! is_array($data)) {return false;}
				$o->setobjId($data['objId'],false);
				$o->setObjectClass($data['ObjectClass'],false);
				$o->setObjectId($data['ObjectId'],false);
				$o->setUserName($data['UserName'],false);
				$o->setPassword($data['Password'],false);
				$o->setEmailAddress($data['EmailAddress'],false);
				$o->setCreated($data['Created'],false);
				$o->setStatus($data['Status'],false);
				$o->setLastLogin($data['LastLogin'],false);
				return $o;
				break;
			case XiuserVars::EMAILADDRESS:
				$o->setEmailAddress($value,false);
				$q = "SELECT * FROM `Xiuser` WHERE `EmailAddress`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) { return false;}
				if (! is_array($data)) {return false;}
				$o->setobjId($data['objId'],false);
				$o->setObjectClass($data['ObjectClass'],false);
				$o->setObjectId($data['ObjectId'],false);
				$o->setUserName($data['UserName'],false);
				$o->setPassword($data['Password'],false);
				$o->setEmailAddress($data['EmailAddress'],false);
				$o->setCreated($data['Created'],false);
				$o->setStatus($data['Status'],false);
				$o->setLastLogin($data['LastLogin'],false);
				return $o;
				break;
			default:
				return false;
				break;
		}
	}
}

class Xiuser extends XPressObject {

	const _TYPE = "Xiuser";
	public $StatusEnumValues = array("Pending Confirmation","Active","Disabled");
	public $ObjectClass = '';
	public $ObjectId = '';
	public $UserName = '';
	public $Password = '';
	public $EmailAddress = '';
	public $Created = '';
	public $Status = '';
	public $LastLogin = '';
	public $Groups = array();
	public $Roles = array();


	public function __construct($objId = 0) {
		//echo "creating object of type Xiuser<br/>";
		$this->objId = $objId;
	}

	// Accessor Functions
	public function getObjId() {
		 return $this->objId;
	}
	public function getObjectClass() {
		 return $this->ObjectClass;
	}
	public function getObjectId() {
		 return $this->ObjectId;
	}
	public function getUserName() {
		 return $this->UserName;
	}
	public function getPassword() {
		 return $this->Password;
	}
	public function getEmailAddress() {
		 return $this->EmailAddress;
	}
	public function getCreated() {
		 return $this->Created;
	}
	public function getStatus() {
		 return $this->Status;
	}
	public function getLastLogin() {
		 return $this->LastLogin;
	}
	public function getGroups() {
		if ($this->Groups != array()) {
			return $this->Groups;
		} else {
			$this->inflate(XiuserVars::GROUPS);
			return $this->Groups;
		}
	}
	public function getRoles() {
		if ($this->Roles != array()) {
			return $this->Roles;
		} else {
			$this->inflate(XiuserVars::ROLES);
			return $this->Roles;
		}
	}

	// Mutator Functions 
	public function setObjectClass($value,$bSave = true) {
		$this->ObjectClass = $value;
		if ($bSave){
			$this->save(XiuserVars::OBJECTCLASS);
		}
	}
	public function setObjectId($value,$bSave = true) {
		$this->ObjectId = $value;
		if ($bSave){
			$this->save(XiuserVars::OBJECTID);
		}
	}
	public function setUserName($value,$bSave = true) {
		$this->UserName = $value;
		if ($bSave){
			$this->save(XiuserVars::USERNAME);
		}
	}
	public function setPassword($value,$bSave = true) {
		$this->Password = $value;
		if ($bSave){
			$this->save(XiuserVars::PASSWORD);
		}
	}
	public function setEmailAddress($value,$bSave = true) {
		$this->EmailAddress = $value;
		if ($bSave){
			$this->save(XiuserVars::EMAILADDRESS);
		}
	}
	public function setCreated($value,$bSave = true) {
		$this->Created = $value;
		if ($bSave){
			$this->save(XiuserVars::CREATED);
		}
	}
	public function setStatus($value,$bSave = true) {
		$this->Status = $value;
		if ($bSave){
			$this->save(XiuserVars::STATUS);
		}
	}
	public function setLastLogin($value,$bSave = true) {
		$this->LastLogin = $value;
		if ($bSave){
			$this->save(XiuserVars::LASTLOGIN);
		}
	}

	// API
	private function inflate($variableName) {
		switch ($variableName) {
			case "Groups":
				// Inflate "Groups":
				$q = "SELECT XigroupID AS objId FROM xr_Xiuser_Xigroup WHERE XiuserID = {$this->objId} AND XiuserVar = \"Groups\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Groups = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Groups[] = XigroupFactory::retrieve($id[objId]);
				}
				break;
			case "Roles":
				// Inflate "Roles":
				$q = "SELECT XiroleID AS objId FROM xr_Xiuser_Xirole WHERE XiuserID = {$this->objId} AND XiuserVar = \"Roles\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Roles = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Roles[] = XiroleFactory::retrieve($id[objId]);
				}
				break;
			default:
				return false;
		}
		return true;
	}
	public function deflate(){
		// reset all member variables to initial settings
		$this->objId = '';
		$this->ObjectClass = '';
		$this->ObjectId = '';
		$this->UserName = '';
		$this->Password = '';
		$this->EmailAddress = '';
		$this->Created = '';
		$this->Status = '';
		$this->LastLogin = '';
		$this->Groups = array();
		$this->Roles = array();
	}
	public function save($attr = null) {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `Xiuser` ";
			$q .= 'VALUES("","'.$this->ObjectClass.'","'.$this->ObjectId.'","'.$this->UserName.'","'.$this->Password.'","'.$this->EmailAddress.'","'.$this->Created.'","'.$this->Status.'","'.$this->LastLogin.'") ';
			$r = XPress::getInstance()->getDatabase()->query($q);
			$this->objId = XPress::getInstance()->getDatabase()->getOne("SELECT LAST_INSERT_ID() FROM `Xiuser`");
		} else {
			if ($attr != null) {
				// Update the given field of an existing object in the db
				$q = "UPDATE `Xiuser` SET `{$attr}`=\"{$this->$attr}\" WHERE `objId` = $this->objId";
			} else {
				// Update all fields of an existing object in the db
				$q = "UPDATE `Xiuser` SET ";
				$q .= "`objId`=\"{$this->objId}\","; 
				$q .= "`ObjectClass`=\"{$this->ObjectClass}\","; 
				$q .= "`ObjectId`=\"{$this->ObjectId}\","; 
				$q .= "`UserName`=\"{$this->UserName}\","; 
				$q .= "`Password`=\"{$this->Password}\","; 
				$q .= "`EmailAddress`=\"{$this->EmailAddress}\","; 
				$q .= "`Created`=\"{$this->Created}\","; 
				$q .= "`Status`=\"{$this->Status}\","; 
				$q .= "`LastLogin`=\"{$this->LastLogin}\" ";
				$q .= "WHERE `objId` = $this->objId ";
			}
			$r = XPress::getInstance()->getDatabase()->query($q);
		}
	}
	public function delete() {
		//Delete this object's child objects

		//Intelligently unlink this object from any other objects
		$this->unlink(XiuserVars::GROUPS);
		$this->unlink(XiuserVars::ROLES);

		//Signal objects that link to this object to unlink
		// (this covers the case in which a relationship is only 1-directional, where
		// this object has no idea its being pointed to by something)
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Xiuser_Xigroup WHERE `XiuserID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Xiuser_Xirole WHERE `XiuserID`={$this->objId}");

		//Delete object from the database
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM Xiuser WHERE `objId` = $this->objId ");
		$this->deflate();
	}
	public function _getType(){
		return Xiuser::_TYPE; //Xiuser
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Groups":
				$test = "SELECT COUNT(*) FROM Xigroup WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Xiuser_Xigroup WHERE XiuserID=$this->objId AND XigroupID=$remoteID ";
				$q0 = "INSERT INTO xr_Xiuser_Xigroup (XiuserID,XigroupID,XiuserVar".(($remoteVar == '')? '' : ',XigroupVar').") VALUES($this->objId,$remoteID,\"Groups\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Xiuser_Xigroup SET XiuserVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', XigroupVar="{$remoteVar}" ')." WHERE XiuserID=$this->objId AND XigroupID=$remoteID LIMIT 1 ";
				break;
			case "Roles":
				$test = "SELECT COUNT(*) FROM Xirole WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Xiuser_Xirole WHERE XiuserID=$this->objId AND XiroleID=$remoteID ";
				$q0 = "INSERT INTO xr_Xiuser_Xirole (XiuserID,XiroleID,XiuserVar".(($remoteVar == '')? '' : ',XiroleVar').") VALUES($this->objId,$remoteID,\"Roles\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Xiuser_Xirole SET XiuserVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', XiroleVar="{$remoteVar}" ')." WHERE XiuserID=$this->objId AND XiroleID=$remoteID LIMIT 1 ";
				break;
			default:
				break;
		}
		if (1 != XPress::getInstance()->getDatabase()->getOne($test)) {
			return false; // The requested remote id does not exist!
		}
		$count  = XPress::getInstance()->getDatabase()->getOne($q);
		if ($count == 0){
			XPress::getInstance()->getDatabase()->query($q0);
		} else {
			XPress::getInstance()->getDatabase()->query($q1);
		}
		return true;
	}
	public function unlink($variable,$remoteIDs = ''){
		switch ($variable){
			case "Groups":
				$q = "DELETE FROM xr_Xiuser_Xigroup WHERE XiuserID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND XigroupID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND XiuserVar = \"Groups\" ";
				break;
			case "Roles":
				$q = "DELETE FROM xr_Xiuser_Xirole WHERE XiuserID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND XiroleID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND XiuserVar = \"Roles\" ";
				break;
			default:
				break;
		}
		$r  = XPress::getInstance()->getDatabase()->query($q);
		return true;
	}
	public function equals($objArray){
		if ($objArray == null){return false;}
		//print_r($objArray);
		foreach ($objArray as $obj){
			//echo "::EQUALS:: comparing {$this->_getType()} WITH {$obj->_getType()} &nbsp;<br/>";
			// Check object types first
			if ($this->_getType() == $obj->_getType()){
				// Check objId next
				if ($this->objId != $obj->objId){continue;}
				return true;
			}
		}
		return false;
	}
	public function toJSON(){
		return json_encode($this);
	}
	public function toRDF($namespace,$urlBase) {
		return "";
	}
	public function toRDFStub($namespace,$urlBase) {
		return "";
	}

	// API Extensions 
	// -@-	// -@-
	// End API Extensions --
}

?>