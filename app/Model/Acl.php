<?php
class Acl extends AppModel {
	
	
	var $useTable = "acl";
	var $name     = "Acl";

    var $host       = 'ldaps://edrn.jpl.nasa.gov';
    var $port       = 636; //389;
    var $baseDn 	= 'dc=edrn,dc=jpl,dc=nasa,dc=gov';
    var $user       = 'uid=bmdb,dc=edrn,dc=jpl,dc=nasa,dc=gov';
    var $pass       = 'bmdb';

    var $ds;
    
	function __construct() {
    	parent::__construct();
    	$this->ds = ldap_connect($this->host, $this->port);
    	ldap_set_option($this->ds, LDAP_OPT_PROTOCOL_VERSION, 3);
    	ldap_bind($this->ds, $this->user, $this->pass);
	}
	
	function __destruct() {
	    ldap_close($this->ds);
	}
	
	function findAll($attribute = 'uid', $value = '*', $baseDn = 'dc=edrn,dc=jpl,dc=nasa,dc=gov') {
		$r = ldap_search($this->ds, $baseDn, $attribute . '=' . $value);
		if ($r) {
			//if the result contains entries with surnames,
			//sort by surname:
			ldap_sort($this->ds, $r, "sn");

			return ldap_get_entries($this->ds, $r);
		}
	}
	
	public function getLDAPGroups() {
		$result    = $this->findAll("objectClass","groupOfUniqueNames");
		$numGroups = $result['count'];
		
		$resultArray = array();
		for ( $i = 0; $i < $numGroups; $i++ ) {
			$resultArray[] = array("name" => $result[$i]['cn'][0]);
		}
		return $resultArray;
	}
}
?>
