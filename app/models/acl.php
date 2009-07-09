<?php
class Acl extends AppModel {
	
	
	var $useTable = "acl";
	var $name     = "Acl";
	
	public function getLDAPGroups() {
		return array(
			// This is temporary placeholder data which will be replaced
			// with a live query to the LDAP server.
			array("name"=>"edrn.pi"),
			array("name"=>"edrn.nci"),
			array("name"=>"edrn.curator"),
			array("name"=>"edrn.review.0309"),
			array("name"=>"edrn.ic"),
			array("name"=>"edrn.dmcc")
		);
	}
	
	public function getLDAPGroupCommonNames() {
		return array(
			// This is temporary placeholder data which will be replaced
			// with a live query to the LDAP server.
			array("edrn.pi"          =>"Principal Investigators"),
			array("edrn.nci"         =>"National Cancer Institute"),
			array("edrn.curator"     =>"Curators"),
			array("edrn.review.0309" =>"Reviewers"),
			array("edrn.ic"          =>"Informatics Team"),
			array("edrn.dmcc"        =>"Data Management and Coordinating Center")
		);
	}
	
	public function getCommonNameFor($name) {
		$cnData = self::getLDAPGroupCommonNames();
		foreach ($cnData as $username => $commonName) {
			if ($name == $username) {
				return $commonName;
			}
		}
		return $name;
	}
}
?>