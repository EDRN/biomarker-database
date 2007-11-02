<?php
	require_once("../util/definitions.inc.php");
	require_once(BMDB_ROOT."/classes/Bmdb.class.php");
	require_once(BMDB_ROOT."/cots/crawwler-xld-1.0.0/XLDF.class.php");
	require_once(BMDB_ROOT."/model/ModelProperties.inc.php");
	
	// Session Initialization
	cwsp_session::init();
	
	// Authentication
	$auth = Bmdb::getAuthObject();
	$bLoggedIn = $auth->checkLoginStatus();
	
	// Database
	$db = Bmdb::getDatabaseObject();
	
	// Associations may only be performed if the user is logged in
	if ($bLoggedIn) {

		// Create the publication object
		if (isset($_GET['p']) && $_GET['p'] == 'new'){
			if (!$bLoggedIn){
				cwsp_messages::err("You must be logged in to create a Publication.");
				echo "<br/><center><a href=\"../login.php\">Click Here to Log In</a></center>";
				exit();
			}
			if (!isset($_GET['pmid'])){
				cwsp_messages::err("You must specify a unique PubMedID ".
				"for newly created publications.");
				exit();
			}
			$p = publication::Create($_GET['pmid']);
			cwsp_page::httpRedirect("showPublication.php?p={$p->ID}");
		}

		// Delete the publication object
		if (isset($_GET['p']) && isset($_GET['remove'])
		&& $_GET['remove'] == 'publication'){
			$p = publication::RetrieveByID($_GET['p']);
			publication::Delete(&$p);
			cwsp_page::httpRedirect("../browsers/browsePublications.php");
		}

	}
	
	// Page Header Setup
	$p = new cwsp_page("EDRN - Biomarker Database - Show Publication");
	$p->includeJS('../cots/scriptaculous-js-1.7.0/lib/prototype.js');
	$p->includeJS('../cots/scriptaculous-js-1.7.0/src/scriptaculous.js');
	$p->includeCSS('../css/common.css');
	$p->includeCSS('../css/alkalai.css');
	$p->insertRaw(PloneHeader::generate('../'));
	$p->drawHeader();
	
	include_once(BMDB_ROOT.'/plone/plone_body.inc.php');
	
	Bmdb::doTopStatusBar($bLoggedIn,'../');
	
	// Check for required GET parameters 
	if (!isset($_GET['p'])){
		cwsp_messages::fatal("No Publication ID provided in GET parameters.");
	}
	
	// Get Organ Data
	$organ = publication::RetrieveByID($_GET['p']);
	
	
	// Build the XLD Form
	$formBuilder = new XLDF(BMDB_ROOT.'/objects/Publications.xml',
							$db->conn,
							'../util/handlers/publicationsHandler.php',
							$bLoggedIn);
	$formBuilder->Init();
	
	// Load the organ
	$publication = publication::RetrieveByID($_GET['p']);
?>
<div id="outerContainer">
	<div id="editTools">
		<div class="categoryBox">
			<h2>Actions</h2>
			<ul><li><a href="../">Return Home</a></li>
				<li><a href="../browsers/browsePublications.php">Browse Publications</a></li>
<?php if($bLoggedIn){?><li><a href="showPublication.php?p=<?php echo $_GET['p'];?>&remove=publication">Delete this Publication</a></li><?php } ?>
			</ul>
		</div>

		
		<div class="categoryBox">
			<h2>Biomarkers</h2>
<?php
	if (sizeof($publication->biomarkers) == 0){
		echo "No biomarkers reference this publication";
	} else {
		echo "Biomarkers that reference this pub.:";
		echo "<ul style=\"list-style:none;\">";
		foreach ($publication->biomarkers as $marker){
			echo "<li><a href=\"showBiomarker.php?m={$marker->biomarker->ID}\">{$marker->biomarker->Title}</a> &nbsp;</li>";
		}
		echo "</ul>";
	}

?>
		</div>
		
		<div class="categoryBox">
			<h2>Biomarker-Organ Pairs</h2>
<?php
	if (sizeof($publication->biomarker_organs) == 0){
		echo "No Biomarker-Organ pairs reference this publication";
	} else {
		echo "Biomarker-Organ pairs that reference this pub.:";
		echo "<ul style=\"list-style:none;\">";
		foreach ($publication->biomarker_organs as $bop){
			echo "<li><a href=\"showBiomarkerOrgan.php?m={$bop->biomarker_organ->BiomarkerID}"
				."&o={$bop->biomarker_organ->OrganSite}\">{$bop->biomarker_organ->biomarker->Title}"
				."-{$bop->biomarker_organ->organ->Name}</a> &nbsp;</li>";
		}
		echo "</ul>";
	}

?>
		</div>

		<div class="categoryBox">
			<h2>Studies</h2>
<?php
	if (sizeof($publication->studies) == 0){
		echo "No studies reference this publication";
	} else {
		echo "Studies that reference this pub.:";
		echo "<ul style=\"list-style:none;\">";
		foreach ($publication->studies as $study){
			echo "<li><a href=\"showStudy.php?s={$study->study->ID}\">{$study->study->Title}</a> &nbsp;</li>";
		}
		echo "</ul>";
	}

?>
		</div>
	</div>
	<div id="editSubject">
	<?php echo $formBuilder->getHtml(); ?>
	<p>&nbsp;</p>
	</div>
	<div style="clear:both;"></div>
</div>
<?php
	// print the javascript edit in place code for the XLD form
	echo '<script type="text/javascript">';
	echo $formBuilder->getJavascript();
	echo '</script>';
	// Close off the page
	$p->drawFooter();
?>
