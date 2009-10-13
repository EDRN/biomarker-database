<?php
class LdapUser extends AppModel
{
    var $name     = 'LdapUser';
    var $useTable = false;

    var $host       = 'ldaps://cancer.jpl.nasa.gov';
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

	function auth($uid, $password) {
		$result = $this->findAll('uid', $uid);
		if($result['count'] == 1) {
			if (@ldap_bind($this->ds, $result[0]['dn'], $password)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function getLDAPGroups() {
		
    	$result = $this->findAll("objectClass","groupOfUniqueNames");

	}

}
?>