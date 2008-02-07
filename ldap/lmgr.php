<?php
	
	require_once("../model/ModelProperties.inc.php");
	require_once("components/authenticate.php");
	require_once("components/authorize.php");
	
	if (isset($_GET['r']) && !empty($_GET['r'])){
		header("Location: {$_GET['r']}");
	} else {
		echo "<span>You have logged in successfully. <br/>"
			."<a href=\"../\">Click here to go home</a></span>";
	}
	
?>