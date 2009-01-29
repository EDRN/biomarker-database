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
}
?>