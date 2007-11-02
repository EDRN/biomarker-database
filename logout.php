<?php
	require_once("util/definitions.inc.php");
	require_once(BMDB_ROOT."/classes/Bmdb.class.php");
	
	// Session Initialization
	cwsp_session::init();
	
	// Authentication
	$auth = Bmdb::getAuthObject();
	$bLoggedIn = $auth->checkLoginStatus();

	// Log out Processing Code
	if ($bLoggedIn){
		Bmdb::logOut(&$auth);
	}

	// Page Header Setup
	$p = new cwsp_page("EDRN - Biomarker Database - Logged Out");
	$p->includeCSS('css/common.css');
	$p->includeCSS('css/alkalai.css');
	$p->insertRaw(PloneHeader::generate());
	
	$p->drawHeader();
	include_once('plone/plone_body.inc.php');
		
?>
<p>&nbsp;</p>
<img src="assets/images/bmdb.png" style="margin-left:5px;"/>
<!-- <div id="outerContainer"> -->
	<div class="centered" style="text-align:center;">
		<h3>You have logged out successfully</h3>
		Click 
		<a href="./login.php">here</a> to log in again, or 
		<a href="./">here</a> to go to the home page.
	</div>
<!-- </div> -->
<?php
	// Close off the page
	$p->drawFooter();
?><?php

?>