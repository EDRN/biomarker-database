<?php
	require_once("XPressSession.class.php");
	
	XPressSession::init();
	
	echo "TESTING FOR \$_SESSION['complex']['array']['element']: ".
		((XPressSession::test(array('complex','array','element')))? "Exists" : "Does Not Exist");
	echo "<br/>";
	
	XPressSession::spew();
	echo "<br/>";
		
	XPressSession::set(array('complex','array','element'),25);
	
	echo "TESTING FOR \$_SESSION['complex']['array']['element']: ".
		((XPressSession::test(array('complex','array','element')))? "Exists" : "Does Not Exist");
	echo "<br/>";

	XPressSession::spew();
	echo "<br/>";
	
	XPressSession::clear(array('complex','array','element'));
	
	echo "TESTING FOR \$_SESSION['complex']['array']['element']: ".
		((XPressSession::test(array('complex','array','element')))? "Exists" : "Does Not Exist");
	echo "<br/>";
	
	XPressSession::spew();
	echo "<br/>";

	
	XPressSession::destroy();
?>