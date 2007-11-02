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
	
	// Create the organ object
	if (isset($_GET['o']) && $_GET['o'] == 'new'){
		if (!isset($_GET['ln'])){
			cwsp_messages::err("You must specify a unique Name ".
				"for newly created Organ Sites.");
			exit();
		}
		$o = organ::Create($_GET['ln']);
		cwsp_page::httpRedirect("showOrgan.php?o={$o->ID}");
	}
	
	// Delete the organ object
	if (isset($_GET['o']) && isset($_GET['remove'])
		&& $_GET['remove'] == 'organ'){
		$o = organ::RetrieveByID($_GET['which']);
		organ::Delete(&$o);	
		cwsp_page::httpRedirect("../browsers/browseOrgans.php");	
	}
	
	// Page Header Setup
	$p = new cwsp_page("EDRN - Biomarker Database - Show Organ Site");
	$p->includeJS('../cots/scriptaculous-js-1.7.0/lib/prototype.js');
	$p->includeJS('../cots/scriptaculous-js-1.7.0/src/scriptaculous.js');
	$p->includeCSS('../css/common.css');
	$p->includeCSS('../css/alkalai.css');
	$p->insertRaw(PloneHeader::generate('../'));
	$p->drawHeader();
	
	include_once(BMDB_ROOT.'/plone/plone_body.inc.php');
	
	Bmdb::doTopStatusBar($bLoggedIn,'../');
	
	// Check for required GET parameters 
	if (!isset($_GET['o'])){
		cwsp_messages::fatal("No Organ Site ID provided in GET parameters.");
	}
	
	// Get Organ Data
	$organ = organ::RetrieveByID($_GET['o']);
	
	
	// Build the XLD Form
	$formBuilder = new XLDF(BMDB_ROOT.'/objects/Organ.xml',
							$db->conn,
							'../util/handlers/OrganHandler.php',
							$bLoggedIn);
	$formBuilder->Init();
	
	// Load the organ
	$o = organ::RetrieveByID($_GET['o']);
?>
<div id="outerContainer">
	<div id="editTools">
		<div class="categoryBox">
			<h2>Actions</h2>
			<ul><li><a href="../">Return Home</a></li>
				<li><a href="../browsers/browseOrgans.php">Browse Organ Sites</a></li>
				<li><a href="showOrgan.php?o=<?php echo $_GET['o']?>&remove=organ&which=<?php echo $_GET['o']?>">Delete this Organ Site</a></li>
			</ul>
		</div>

		
		<div class="categoryBox">
			<h2>Biomarkers</h2>
<?php
	if (sizeof($o->biomarkers) == 0){
		echo "No biomarkers exist at this site";
	} else {
		echo "Biomarkers with data at this site:";
		echo "<ul style=\"list-style:none;\">";
		foreach ($o->biomarkers as $marker){
			echo "<li><a href=\"showBiomarkerOrgan.php?m={$marker->BiomarkerID}&o={$o->ID}\">{$marker->biomarker->Title}</a> &nbsp;</li>";
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
