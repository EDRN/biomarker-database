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
		// Associate a publication with this biomarker-organ
		if (isset($_GET['m']) && isset($_GET['o']) && isset($_GET['p'])){
			$bop = biomarker_organ_publication::Create($_GET['p'],$_GET['m'],$_GET['o']);
			cwsp_page::httpRedirect("showBiomarkerOrgan.php?m=$_GET[m]&o=$_GET[o]");
		}
		
		// Associate a study with this biomarker-organ
		

		// Dissasociate a study with this biomarker
		

		// Dissociate a publication with this biomarker-organ
		if (isset($_GET['remove']) && $_GET['remove'] == 'publication'){
			if (!isset($_GET['which'])){
				cwsp_messages::err("You must specify which publication ID ".
				"to remove using the 'which' GET parameter, ie: which=21");
				exit();
			}
			$bop = biomarker_organ_publication::Retrieve(array("BiomarkerID","OrganSite","PublicationID"),array($_GET['m'],$_GET['o'],$_GET['which']),null,true,1);
			biomarker_organ_publication::Delete(&$bop);
			cwsp_page::httpRedirect("showBiomarkerOrgan.php?m=$_GET[m]&o=$_GET[o]");
		}

		// Dissociate a resource with this biomarker-organ
		if (isset($_GET['remove']) && $_GET['remove'] == 'resource'){
			if (!isset($_GET['which'])){
				cwsp_messages::err("You must specify which resource ID ".
				"to remove using the 'which' GET parameter, ie: which=21");
				exit();
			}
			$bor = biomarker_organ_resource::Retrieve(array("BiomarkerID","OrganSite","ResourceID"),array($_GET['m'],$_GET['o'],$_GET['which']),null,false,1);
			biomarker_organ_resource::Delete(&$bor);
			cwsp_page::httpRedirect("showBiomarkerOrgan.php?m=$_GET[m]&o=$_GET[o]");
		}

	}
	
	// Page Header Setup
	$p = new cwsp_page("EDRN - Biomarker Database - Show Biomarker - Organ");
	$p->includeJS('../cots/scriptaculous-js-1.7.0/lib/prototype.js');
	$p->includeJS('../cots/scriptaculous-js-1.7.0/src/scriptaculous.js');
	$p->includeCSS('../css/common.css');
	$p->includeCSS('../css/alkalai.css');
	$p->includeJS('../js/textInputs.js');
	$p->insertRaw(PloneHeader::generate('../'));
	$p->drawHeader();
	
	include_once(BMDB_ROOT.'/plone/plone_body.inc.php');
	
	Bmdb::doTopStatusBar($bLoggedIn,'../');
	
	// Check for required GET parameters 
	if (!isset($_GET['m'])){
		cwsp_messages::fatal("No Biomarker ID provided in GET parameters.");
	}
	if (!isset($_GET['o'])){
		cwsp_messages::fatal("No Organ ID provided in GET parameters.");
	}
	
	// Create the biomarker_organ object
	$bo = biomarker_organ::Retrieve(array("BiomarkerID","OrganSite"),array($_GET['m'],$_GET['o']),null,false,1);
	$b  = biomarker::RetrieveByID($_GET['m']);
	
	// Build the XLD Form
	$formBuilder = new XLDF(BMDB_ROOT.'/objects/BiomarkerOrgan.xml',
							$db->conn,
							'../util/handlers/biomarkerOrganHandler.php',
							$bLoggedIn);
	$formBuilder->Init();
?>
<div id="outerContainer">
	<div id="editTools">
		<div class="categoryBox">
			<h2>Actions</h2>
			<ul><li><a href="../">Return Home</a></li></ul>
		</div>
 <!--
		<div class="categoryBox">
			<h2>Studies</h2>
<?php
	if (sizeof($b->studies) == 0){
		echo 'This biomarker-organ pair has not yet been associated with any studies. ';
	} else {
		echo 'This biomarker-organ pair has been associated with the following studies: ';
		echo "<ul>";
		foreach ($b->studies as $study){
			echo "<li><a href=\"showStudy.php?s={$study->study->ID}\">{$study->study->Title}</a> &nbsp;</li>";
		}
		echo "</ul>";
	}
?>
		</div>	
-->
		<div class="categoryBox">
			<h2>Biomarker</h2>
			<div style="padding-left:3px;padding-bottom:5px;">Biomarker-specific Information</div>
			<ul><li><a href="showBiomarker.php?m=<?php echo $b->ID;?>"><?php echo $b->Title;?></a></li>
			</ul>
		</div>
		
		<div class="categoryBox">
			<h2>Other Organs</h2>
<?php
	if (sizeof($b->organs) == 0){
		echo "This biomarker has no other organ data";
	} else {
		echo "Other organs with data for this biomarker:";
		echo "<ul style=\"list-style:none;\">";
		foreach ($b->organs as $organ){
			if ($organ->organ->ID == $_GET['o']){continue; /* Don't print self-referential organ link */ }
			echo "<li><a href=\"showBiomarkerOrgan.php?m=$_GET[m]&o={$organ->organ->ID}\">{$organ->organ->Name}</a> &nbsp;</li>";
		}
		echo "</ul>";
	}

?>

		</div>
	</div>
	<div id="editSubject">
	<?php echo $formBuilder->getHtml(); ?>
<br/>
<h2>Studies</h2>
<?php
	if (sizeof($bo->studies) == 0){
		echo 'This biomarker has not yet been associated with any studies. ';
	} else {
		echo 'This biomarker has been associated with the following studies: ';
		echo "<ul>";
		foreach ($bo->studies as $study){
			echo "<li><a href=\"showStudy.php?s={$study->study->ID}\">{$study->study->Title}</a> &nbsp;";
			if ($bLoggedIn){
				echo "[ <a href=\"showBiomarker.php?m={$_GET['m']}&remove=study&".
				"which={$study->study->ID}\">Remove This Association</a> ]";
			}
			echo "</li>";
		}
		echo "</ul>";
	}
	
	// Associations may only be performed if the user is logged in
	if ($bLoggedIn) {
?>
	<div id="associateStudy"><br/>
	<div id="study_autocomplete_choices" class="autocomplete">&nbsp;</div>
		<span class="hint">Associate an existing study:</span>
		<form action="../editors/showBiomarkerOrgan.php" method="GET">
			<input type="hidden" name="m" value="<?php echo $_GET['m']?>"/>
			<input type="hidden" name="o" value="<?php echo $_GET['o']?>"/>
			<script type="text/javascript">
				ti('study_autocomplete','autocomplete_parameter',' Search Existing Studies By Title...','width:350px;');
			</script>
			<input type="hidden" id="studyAutocompleteID" name="s" value=""/>
			<span id="indicator1" style="display: none;"><img src="../assets/images/spinner.gif" alt="Working..." /></span>
			<input type="submit" onclick="return verifySelected('study_autocomplete',' Search Existing Studies By Title...');" value="Associate"/>
		</form>
		<script type="text/javascript">
			function afterAutocomplete(field,element){
				$('studyAutocompleteID').value = element.id;
			}
			new Ajax.Autocompleter("study_autocomplete", "study_autocomplete_choices", "../util/autocomplete/Study.php", {indicator: 'indicator1',afterUpdateElement:afterAutocomplete});
		</script>
	</div>
<?php } ?>
	<p>&nbsp;</p>
	
<h2>Publications</h2>
	<?php
	if (sizeof($bo->publications) == 0){
		echo 'This biomarker-organ pair has not yet been associated with any publications. ';
	} else {
		echo 'This biomarker-organ pair has been associated with the following publications: ';
		echo "<ul>";
		foreach ($bo->publications as $pub){
			echo "<li><a href=\"showPublication.php?p={$pub->publication->ID}\">{$pub->publication->Title}</a> &nbsp;";
				if ($bLoggedIn) {
					echo "[ <a href=\"showBiomarkerOrgan.php?m={$_GET['m']}&o={$_GET['o']}&remove=publication&".
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
		<form action="../editors/showBiomarkerOrgan.php" method="GET">
			<input type="hidden" name="m" value="<?php echo $_GET['m']?>"/>
			<input type="hidden" name="o" value="<?php echo $_GET['o']?>"/>
			<script type="text/javascript">
				ti('publication_autocomplete','autocomplete_parameter',' Search Existing Publications By Title...','width:350px;');
			</script>
			<input type="hidden" id="pubAutocompleteID" name="p" value=""/>
			<span id="indicator1" style="display: none;"><img src="../assets/images/spinner.gif" alt="Working..." /></span>
			<input type="submit" onclick="return verifySelected('publication_autocomplete',' Search Existing Publications By Title...');" value="Associate"/>
		</form>
		<script type="text/javascript">
			function pubAfterAutocomplete(field,element){
				$('pubAutocompleteID').value = element.id;
			}
			new Ajax.Autocompleter("publication_autocomplete", "publication_autocomplete_choices", "../util/autocomplete/Publication.php", {indicator: 'indicator1',afterUpdateElement:pubAfterAutocomplete});
		</script>
	</div>
<?php } ?>
	<p>&nbsp;</p>	
<h2>Resources</h2>
<?php
	if (sizeof($bo->resources) == 0){
		echo 'This biomarker-organ pair has not yet been associated with any resources. ';
	} else {
		echo 'This biomarker-organ pair has been associated with the following resources: ';
		echo "<ul>";
		foreach ($bo->resources as $res){
			echo "<li><a href=\"{$res->resource->URL}\">{$res->resource->Name}</a> &nbsp;";
			if ($bLoggedIn) {
				echo "[ <a href=\"showBiomarkerOrgan.php?m={$_GET['m']}&o={$_GET['o']}&remove=resource&".
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
			<input type="hidden" name="type" value="biomarker_organ"/>
			<input type="hidden" name="which" value="m:<?php echo $_GET['m'];?>,o:<?php echo $_GET['o'];?>"/>
			<input type="hidden" name="redirect" value="../editors/showBiomarkerOrgan.php?m=<?php echo $_GET['m'];?>&o=<?php echo $_GET['o'];?>"/>
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
	// print the javascript edit in place code for the XLD form
	echo '<script type="text/javascript">';
	echo $formBuilder->getJavascript();
	echo '</script>';
	// Close off the page
	$p->drawFooter();
?>