<?php
	require_once("util/definitions.inc.php");
	require_once(BMDB_ROOT."/classes/Bmdb.class.php");
	require_once(BMDB_ROOT."/classes/model/User.php");
	
	// Session Initialization
	cwsp_session::init();
	
	// Authentication
	$auth = Bmdb::getAuthObject();
	
	// Database
	$db = Bmdb::getDatabaseObject();
	
	// Login Processing Code
	$loginFailure = false;
	if (isset($_POST['dologin'])){
		if (Bmdb::logIn($_POST['username'],&$db,&$auth)){
			cwsp_page::httpRedirect("./");
		} else {
			$loginFailure = true;
		}
	}
	
	
	
	// Page Header Setup
	$p = new cwsp_page("EDRN - Biomarker Database - Log In");
	$p->includeCSS('css/common.css');
	$p->includeCSS('css/alkalai.css');
	$p->insertRaw(PloneHeader::generate());
	$p->setBodyOnLoad("javascript:document.getElementById('un').focus();");
	
	$p->drawHeader();
	include_once('plone/plone_body.inc.php');

?>
<p>&nbsp;</p>
<img src="assets/images/bmdb.png" style="margin-left:5px;"/>
<!-- <div id="outerContainer">-->
	<div class="centered" style="width:260px;">
		<?php if ($loginFailure) {echo cwsp_messages::err("Invalid Login Details Provided");}?>
		Please enter your login details to continue...
		<p>&nbsp;</p>
		<form action="./login.php" method="post">
			<table>
				<tr><td>Username:</td><td><input type="text" id="un" name="username"/></td></tr>
				<tr><td>Password:</td><td><input type="password" id="pw" name="password"/></td></tr>
				<tr><td colspan="2" style="text-align:right;"><input type="submit" name="dologin" value="Log In"/></td></tr>
			</table>
		</form>
	</div>
<!-- </div> -->
<?php
	// Close off the page
	$p->drawFooter();
?>