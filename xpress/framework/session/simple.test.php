<?php
	require_once("XPressSession.class.php");
	
	XPressSession::init();
	
	echo "TESTING FOR \$_SESSION['simple']: ".
		((XPressSession::test("simple"))? "Exists" : "Does Not Exist");
	echo "<br/>";
	
	XPressSession::spew();
	echo "<br/>";
		
	XPressSession::set("simple",25);
	
	echo "TESTING FOR \$_SESSION['simple']: ".
		((XPressSession::test("simple"))? "Exists" : "Does Not Exist");
	echo "<br/>";

	XPressSession::spew();
	echo "<br/>";
	
	XPressSession::clear("simple");
	
	echo "TESTING FOR \$_SESSION['simple']: ".
		((XPressSession::test("simple"))? "Exists" : "Does Not Exist");
	echo "<br/>";
	
	XPressSession::spew();
	echo "<br/>";
	
	XPressSession::destroy();
?>