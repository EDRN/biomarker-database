<?php
	if (empty($_SESSION) || !isset($_SESSION['auth'])){
		header("Location: " . MY_LDAP_MGR_URL . "?r={$_SERVER['REQUEST_URI']}");
	}

?>