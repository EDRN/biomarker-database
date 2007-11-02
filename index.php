<?php

	require_once("util/definitions.inc.php");
	require_once(BMDB_ROOT."/classes/Bmdb.class.php");
	require_once(BMDB_ROOT."/classes/model/User.php");
	
	// Session Initialization
	cwsp_session::init();
	
	// Authentication
	$auth = Bmdb::getAuthObject();
	$bLoggedIn = $auth->checkLoginStatus();
	
	// Redirect if not logged in
	if (!$bLoggedIn) {
		//cwsp_page::httpRedirect("login.php");
	}
	
	// Database
	$db = Bmdb::getDatabaseObject();
	
	// Page Header Setup
	$p = new cwsp_page("EDRN - Biomarker Database - Home");
	$p->includeJS('cots/scriptaculous-js-1.7.0/lib/prototype.js');
	$p->includeJS('cots/scriptaculous-js-1.7.0/src/scriptaculous.js');
	$p->includeJS('js/textinputs.js');
	$p->includeJS('js/createObjects.js');
	$p->includeCSS('css/alkalai.css');
	$p->insertRaw(PloneHeader::generate());
	$p->drawHeader();
	include_once('plone/plone_body.inc.php');
	

	Bmdb::doTopStatusBar($bLoggedIn);
	
	
/*	try {
		$userdao = new dao_user(&$db);
		$user = $userdao->getByID($_SESSION['_bmdb']['User']['ID']);
	} catch (NoSuchUserException $nsue){
		cwsp_messages::err($nsue->__toString());
		exit();	
	}
*/
?>

	<div id="outerContainer">
	<div id="editTools">
	<div class="categoryBox" style="margin-top:0px;">
		<h2>Biomarkers</h2>
		<ul><li><a href="browsers/browseBiomarkers.php">View existing Biomarkers</a></li>
<?php if ($bLoggedIn) {?>
			<li><div><span id="createNewMarkerLabel"><a href="javascript:doCreateMarker();">Create a new Biomarker</a></span>
				<div id="createNewMarker" style="display:none;">
					<form action="editors/showBiomarker.php" method="GET">
						<input type="hidden" name="m" value="new"/>
						<input type="text" id="newMarkerLongName" name="ln" style="width:116px;"/>&nbsp;
						<input type="submit" value="Go" onclick="return $('newMarkerLongName').value != '';"/>&nbsp;<a href="javascript:cancelCreateMarker();">cancel</a>
					</form>
				</div>
				</div>
			</li>	
<?php }?>
		</ul>
	</div>
	
	<div class="categoryBox">
		<h2>Studies</h2>
		<ul><li><a href="browsers/browseStudies.php">View existing Studies</a></li>
<?php if ($bLoggedIn) {?>
			<li><div>
				<span id="createNewStudyLabel">
					<a href="javascript:doCreateStudy();">Create a new Study</a>
				</span>
				<div id="createNewStudy" style="display:none;">
					<form action="editors/showStudy.php">
						<input type="hidden" name="s" value="new"/>
						<input type="text" name="ln" style="width:116px;"/>&nbsp;
						<input type="submit" value="Go"/>&nbsp;
						<a href="javascript:cancelCreateStudy();">cancel</a>
					</form>
				</div>
				</div>
			</li>
<?php } ?>
		</ul>
	</div>
	
		<div class="categoryBox">
		<h2>Publications</h2>
		<ul><li><a href="browsers/browsePublications.php">View existing Publications</a></li>
<?php if ($bLoggedIn){?>
			<li><div><span id="createNewPublicationLabel"><a href="javascript:doCreatePublication();">Create a new Publication</a></span>
				<div id="createNewPublication" style="display:none;">
					<form action="editors/showPublication.php">
						<input type="hidden" name="p" value="new"/>
						<input type="text" name="pmid" style="width:116px;"/>&nbsp;
						<input type="submit" value="Go"/>&nbsp;<a href="javascript:cancelCreatePublication();">cancel</a>
					</form>
				</div>
				</div>
			</li>	
<?php } ?>
		</ul>
	</div>
	
	</div>
	
	<div id="editSubject" style="width:500px;">
		<h1>Welcome to the EDRN Biomarker Database.</h1>
		
<?php if (!$bLoggedIn){?>
		<p>
		Browse the Biomarker Database objects and their relationships by
		clicking on one of the links on the left
		</p>
		<p><strong>Note:</strong> To make changes to the information, you need to 
		<a href="login.php">log in</a> first.
<?php } else { ?>
		Begin managing Biomarker Database objects and their relationships by clicking 
		on one of the links on the left and providing information as necessary.
<?php } ?>
		
	</div>
	
	</div><!-- End outer container -->


<?php
	// Close off the page
	$p->drawFooter();
?>