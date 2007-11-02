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

		// Create a new study
		if (isset($_GET['s']) && $_GET['s'] == 'new'){
			if (!$bLoggedIn){
				cwsp_messages::err("You must be logged in to create a Study.");
				echo "<br/><center><a href=\"../login.php\">Click Here to Log ".
				"In</a></center>";
				exit();
			}
			if (!isset($_GET['ln'])){
				cwsp_messages::err("You must specify a unique Title ".
				"(Long Name) for newly created Studies.");
				exit();
			}
			$s = study::Create($_GET['ln']);
			cwsp_page::httpRedirect("showStudy.php?s={$s->ID}");
		}

		// Delete a study
		if (isset($_GET['remove']) && $_GET['remove'] == 'study'){
			if (!isset($_GET['which'])){
				cwsp_messages::err("You must specify which study ID ".
				"to remove using teh 'which' GET parameter, ie: which=21");
				exit();
			}
			$s = study::RetrieveByID($_GET['which'],false);
			study::Delete(&$s);
			cwsp_page::httpRedirect("../browsers/browseStudies.php");
		}

		// Associate a publication with this study
		if (isset($_GET['s']) && isset($_GET['p'])){
			$sp = study_publication::Create($_GET['p'],$_GET['s']);
			cwsp_page::httpRedirect("showStudy.php?s=$_GET[s]");
		}

		// Dissociate a publication with this study
		if (isset($_GET['remove']) && $_GET['remove'] == 'publication'){
			if (!isset($_GET['which'])){
				cwsp_messages::err("You must specify which publication ID ".
				"to remove using the 'which' GET parameter, ie: which=21");
				exit();
			}
			$sp = study_publication::Retrieve(array("StudyID","PublicationID"),array($_GET['s'],$_GET['which']),null,true,1);
			study_publication::Delete(&$sp);
			cwsp_page::httpRedirect("showStudy.php?s=$_GET[s]");
		}

		// Associate a Resource with this study
		if (isset($_GET['s']) && isset($_GET['r'])){
			$sr = study_resource::Create($_GET['s'],$_GET['r']);
			cwsp_page::httpRedirect("showStudy.php?s=$_GET[s]");
		}
		// Dissociate a resource with this study
		if (isset($_GET['remove']) && $_GET['remove'] == 'resource'){
			if (!isset($_GET['which'])){
				cwsp_messages::err("You must specify which resource ID ".
				"to remove using the 'which' GET parameter, ie: which=21");
				exit();
			}
			$sr = study_resource::Retrieve(array("StudyID","ResourceID"),array($_GET['s'],$_GET['which']),null,false,1);
			study_resource::Delete(&$sr);
			cwsp_page::httpRedirect("showStudy.php?s=$_GET[s]");
		}

	}
	
	// Page Header Setup
	$p = new cwsp_page("EDRN - Biomarker Database - Show Study");
	$p->includeJS('../cots/scriptaculous-js-1.7.0/lib/prototype.js');
	$p->includeJS('../cots/scriptaculous-js-1.7.0/src/scriptaculous.js');
	$p->includeJS('../js/textInputs.js');
	$p->includeCSS('../css/common.css');
	$p->includeCSS('../css/alkalai.css');
	$p->insertRaw(PloneHeader::generate('../'));
	$p->drawHeader();
	include_once(BMDB_ROOT.'/plone/plone_body.inc.php');
	
	Bmdb::doTopStatusBar($bLoggedIn,'../');
	
	// Check for required GET parameters
	if (!isset($_GET['s'])){
		cwsp_messages::fatal("No Study ID provided in GET parameters.");
	}
	
	// Get the study
	$study = study::RetrieveByID($_GET['s']);
	
	// Build the XLD Form

	$formBuilder = new XLDF(BMDB_ROOT.'/objects/Study.xml',
		$db->conn,
		'../util/handlers/StudyHandler.php',
		$bLoggedIn);
	$formBuilder->Init();
?>
<div id="outerContainer">
	<div id="editTools">
		<div class="categoryBox">
			<h2>Actions</h2>
			<ul><li><a href="../">Return Home</a></li>
				<li><a href="../browsers/browseStudies.php">Browse All Studies</a></li>
<?php if ($bLoggedIn) {?><li><a href="showStudy.php?remove=study&which=<?php echo $_GET['s']?>">Delete This Study</a></li><?php } ?>
			</ul>
		</div>
		<div class="categoryBox">
			<h2>Biomarkers</h2>
<?php
	if (sizeof($study->biomarkers) == 0){
		echo "No biomarkers reference this study";
	} else {
		echo "Biomarkers that reference this study:";
		echo "<ul style=\"list-style:none;\">";
		foreach ($study->biomarkers as $marker){
			echo "<li><a href=\"showBiomarker.php?m={$marker->biomarker->ID}\">{$marker->biomarker->Title}</a> &nbsp;</li>";
		}
		echo "</ul>";
	}

?>
		</div>
		<div class="categoryBox">
			<h2>Biomarker-Organ Pairs</h2>
<?php
	if (sizeof($study->biomarker_organs) == 0){
		echo "No Biomarker-Organ pairs reference this study";
	} else {
		echo "Biomarker-Organ pairs that reference this study:";
		echo "<ul style=\"list-style:none;\">";
		foreach ($study->biomarker_organs as $bop){
			echo "<li><a href=\"showBiomarkerOrgan.php?m={$bop->biomarker_organ->BiomarkerID}"
				."&o={$bop->biomarker_organ->OrganSite}\">{$bop->biomarker_organ->biomarker->Title}"
				."-{$bop->biomarker_organ->organ->Name}</a> &nbsp;</li>";
		}
		echo "</ul>";
	}

?>
		</div>
	</div>
	<div id="editSubject">
	<?php echo $formBuilder->getHtml();?>
	<br/>
	<h2>Publications</h2>
<?php
	if (sizeof($study->publications) == 0){
		echo 'This study has not yet been associated with any publications. ';
	} else {
		echo 'This study has been associated with the following publications: ';
		echo "<ul>";
		foreach ($study->publications as $pub){
			echo "<li><a href=\"showPublication.php?p={$pub->publication->ID}\">{$pub->publication->Title}</a> &nbsp;";
			if ($bLoggedIn) {
				echo "[ <a href=\"showStudy.php?s={$_GET['s']}&remove=publication&".
					"which={$pub->publication->ID}\">Remove This Association</a> ]";
			}
			echo "</li>";
		}
		echo "</ul>";
	}
	
	// Associations may only be performed if the user is logged in
	if ($bLoggedIn) {
?>
	<div id="associatePublication"><br/>
	<div id="publication_autocomplete_choices" class="autocomplete">&nbsp;</div>
		<form action="../editors/showStudy.php" method="GET">
			<input type="hidden" name="s" value="<?php echo $_GET['s']?>"/>
			<script type="text/javascript">
				ti('publication_autocomplete','autocomplete_parameter',' Search Existing Publications By Title...','width:350px;');
			</script>
			<input type="hidden" id="publicationAutocompleteID" name="p" value=""/>
			<span id="indicator1" style="display: none;"><img src="../assets/images/spinner.gif" alt="Working..." /></span>
			<input type="submit" onclick="return verifySelected('publication_autocomplete',' Search Existing Publications By Title...');" value="Associate"/>
		</form>
		<script type="text/javascript">
			function afterAutocomplete(field,element){
				$('publicationAutocompleteID').value = element.id;
			}
			new Ajax.Autocompleter("publication_autocomplete", "publication_autocomplete_choices", "../util/autocomplete/Publication.php", {indicator: 'indicator1',afterUpdateElement:afterAutocomplete});
		</script>
	</div>
<?php } ?>

	<p>&nbsp;</p>
	<h2>Resources</h2>
<?php
	if (sizeof($study->resources) == 0){
		echo 'This study has not yet been associated with any resources. ';
	} else {
		echo 'This study has been associated with the following resources: ';
		echo "<ul>";
		foreach ($study->resources as $res){
			echo "<li><a href=\"{$res->resource->URL}\">{$res->resource->Name}</a> &nbsp;";
			if ($bLoggedIn) {
				echo "<a href=\"showStudy.php?s={$_GET['s']}&remove=resource&".
					"which={$res->resource->ID}\">Remove This Association</a> ]";
			}
			echo "</li>";
		}
		echo "</ul>";
	}
	
	// Associations may only be performed if the user is logged in
	if ($bLoggedIn) {
?>
	<div id="addResource">
		<h5>Add a New Resource:</h5>
		<form action="../util/addResources.php" method="GET">
			<input type="hidden" name="type" value="study"/>
			<input type="hidden" name="which" value="<?php echo $_GET['s'];?>"/>
			<input type="hidden" name="redirect" value="../editors/showStudy.php?s=<?php echo $_GET['s'];?>"/>
			<table>
				<tr><td>Title (Name):</td><td><input type="text" id="addresRname" name="rname" style="width:200px;"/></td></tr>
				<tr><td>URL:</td><td><input type="text" id="addresRurl" name="rurl" style="width:300px;"/>
				<input type="submit" onclick="if($('addresRname').value == '' || $('addresRurl').value == ''){alert('You must specify both a name and a url to create a resource!');return false;} else {return true;}" value="Add Resource"/></td></tr>
			</table>
		</form>
	</div>
<?php } ?>
	<p>&nbsp;</p>
	</div>
	<div style="clear:both;"></div>
</div>
<?php
	// print the javascript edit in place code for the XLD Form
	echo '<script type="text/javascript">';
	echo $formBuilder->getJavascript();
	echo '</script>';
	// Close off the page
	$p->drawFooter();
?>