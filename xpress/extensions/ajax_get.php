<?php
	require_once("../app.php");
	if ((!isset($_GET['otype']) || empty($_GET['otype'])) ||
		(!isset($_GET['oid']) || empty($_GET['oid']))) {
			
		echo "error";
		exit();
	}
	
	$otype = strtolower($_GET['otype']);
	$oid = $_GET['oid'];
	if ($oid <= 0) {
		echo "error";
		exit();
	}
	
	try {
		switch ($otype) {
			case 'publication':
				if (false !== ($p = PublicationFactory::Retrieve($oid))) {
					echo $p->toJSON();
				} else {echo "error"; exit();}
				break;
			default:
				echo "error";
				exit();
			
		}
	} catch (XPressException $e) {
		echo "error";
		exit();
	}	
?>