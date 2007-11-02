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
	
	// Associations may only be performed if a user is logged in
	if ($bLoggedIn) {

		// Create a new Marker in the database, and get its ID
		if (isset($_GET['m']) && $_GET['m'] == 'new'){
			if (!$bLoggedIn){
				cwsp_messages::err("You must be logged in to create a Biomarker.");
				echo "<br/><center><a href=\"../login.php\">Click Here to Log ".
				"In</a></center>";
				exit();
			}
			if (!isset($_GET['ln'])){
				cwsp_messages::err("You must specify a unique Title ".
				"(Long Name) for newly created Biomarkers.");
				exit();
			}
			$b = biomarker::Create($_GET['ln']);
			$nameNoSpaces = preg_replace("/ /","",$_GET['ln']);
			$b->set_BiomarkerID("urn:bmdb:biomarker:{$nameNoSpaces}");
			$vo = $b->getVO();
			$dao = new dao_Biomarker();
			$dao->save(&$vo);
			cwsp_page::httpRedirect("showBiomarker.php?m={$b->ID}");
		}

		// Delete this biomarker from the database
		if (isset($_GET['m']) && isset($_GET['remove'])
		&& $_GET['remove'] == 'biomarker'){
			$b = biomarker::RetrieveByID($_GET['m'],false);
			biomarker::Delete(&$b);
			cwsp_page::httpRedirect("../browsers/browseBiomarkers.php");
		}

		// Associate a study with this biomarker
		if (isset($_GET['m']) && isset($_GET['s'])){
			$bs = biomarker_study::Create($_GET['m'],$_GET['s']);
			cwsp_page::httpRedirect("showBiomarker.php?m=$_GET[m]");
		}

		// Dissasociate a study with this biomarker
		if (isset($_GET['remove']) && $_GET['remove'] == 'study'){
			if (!isset($_GET['which'])){
				cwsp_messages::err("You must specifiy which study ID ".
				"to remove using the 'which' GET parameter, ie: which=21");
				exit();
			}
			$bs = biomarker_study::Retrieve(array("BiomarkerID","StudyID"),array($_GET['m'],$_GET['which']),null,true,1);
			biomarker_study::Delete(&$bs);
			cwsp_page::httpRedirect("showBiomarker.php?m=$_GET[m]");
		}

		// Associate a publication with this biomarker
		if (isset($_GET['m']) && isset($_GET['p'])){
			$bp = biomarker_publication::Create($_GET['p'],$_GET['m']);
			cwsp_page::httpRedirect("showBiomarker.php?m=$_GET[m]");
		}

		// Dissociate a publication with this biomarker
		if (isset($_GET['remove']) && $_GET['remove'] == 'publication'){
			if (!isset($_GET['which'])){
				cwsp_messages::err("You must specify which publication ID ".
				"to remove using the 'which' GET parameter, ie: which=21");
				exit();
			}
			$bp = biomarker_publication::Retrieve(array("BiomarkerID","PublicationID"),array($_GET['m'],$_GET['which']),null,true,1);
			biomarker_publication::Delete(&$bp);
			cwsp_page::httpRedirect("showBiomarker.php?m=$_GET[m]");
		}


		// Add an alias to this biomarker
		if (isset($_GET['alias'])){
			$ba = biomarker_alias::Create($_GET['m'],$_GET['alias']);
			cwsp_page::httpRedirect("showBiomarker.php?m=$_GET[m]");
		}

		// Remove an alias from this biomarker
		if (isset($_GET['remove']) && $_GET['remove'] == 'alias'){
			if (!isset($_GET['which'])){
				cwsp_messages::err("You must specifiy which alias to remove ".
				"using the 'which' GET parameter, ie: which=CA-27.29");
				exit();
			}
			$ba = biomarker_alias::Retrieve(array("BiomarkerID","Alias"),array($_GET['m'],$_GET['which']),null,true,1);
			biomarker_alias::Delete(&$ba);
			cwsp_page::httpRedirect("showBiomarker.php?m=$_GET[m]");
		}

		// Add an organ to this biomarker
		if (isset($_GET['addorgan'])){
			$bo = biomarker_organ::Create($_GET['m'],$_GET['addorgan']);
			cwsp_page::httpRedirect("showBiomarker.php?m=$_GET[m]");
		}

		// Delete an organ from this biomarker
		if (isset($_GET['remove']) && $_GET['remove'] == 'organ'){
			if (!isset($_GET['which'])){
				cwsp_messages::err("You must specifiy which organSite id to remove ".
				"using the 'which' GET parameter, ie: which=9");
				exit();
			}
			$bo = biomarker_organ::Retrieve(array("BiomarkerID","OrganSite"),array($_GET['m'],$_GET['which']),null,true,1);
			biomarker_organ::Delete(&$bo);
			cwsp_page::httpRedirect("showBiomarker.php?m=$_GET[m]");
		}

		// Dissociate a resource from this biomarker
		if (isset($_GET['remove']) && $_GET['remove'] == 'resource'){
			if (!isset($_GET['which'])){
				cwsp_messages::err("You must specify which resource ID ".
				"to remove using the 'which' GET parameter, ie: which=21");
				exit();
			}
			$br = biomarker_resource::Retrieve(array("BiomarkerID","ResourceID"),array($_GET['m'],$_GET['which']),null,false,1);
			biomarker_resource::Delete(&$br);
			cwsp_page::httpRedirect("showBiomarker.php?m=$_GET[m]");
		}

	}
	
	
	// Load all data for this biomarker
	$marker = biomarker::RetrieveByID($_GET['m'],false);
	
	// Page Header Setup
	$p = new cwsp_page("EDRN - Biomarker Database - Show Biomarker");
	$p->includeJS('../cots/scriptaculous-js-1.7.0/lib/prototype.js');
	$p->includeJS('../cots/scriptaculous-js-1.7.0/src/scriptaculous.js');
	$p->includeJS('../js/textInputs.js');
	$p->includeJS('../js/createObjects.js');
	$p->includeCSS('../css/common.css');
	$p->includeCSS('../css/alkalai.css');
	$p->insertRaw(PloneHeader::generate('../'));
	$p->drawHeader();
	include_once(BMDB_ROOT.'/plone/plone_body.inc.php');
	
	Bmdb::doTopStatusBar($bLoggedIn,'../');
	
	// Check for required GET parameters
	if (!isset($_GET['m'])){
		cwsp_messages::fatal("No Biomarker ID provided in GET parameters.");
	}
	
	// Build the XLD Form
	$formBuilder = new XLDF(BMDB_ROOT.'/objects/Biomarker.xml',
		$db->conn,
		'../util/handlers/biomarkerHandler.php',
		$bLoggedIn);
	$formBuilder->Init();
?>
<div id="outerContainer">
	<div id="editTools">
		<div class="categoryBox">
			<h2>Actions</h2>
			<ul><li><a href="../">Return Home</a></li>
				<li><a href="../browsers/browseBiomarkers.php">Browse All Biomarkers</a></li>
<?php if ($bLoggedIn) {?><li><a href="showBiomarker.php?m=<?php echo $_GET['m']?>&remove=biomarker">Delete This Biomarker</a></li><?php } ?>
			</ul>
		</div>
		<div class="categoryBox">
			<h2>Aliases</h2>
			<div style="padding-left:3px;padding-bottom:5px;">
<?php
	if (sizeof($marker->aliases) == 0){
		echo "This biomarker has no defined aliases";
	} else {
		echo "This biomarker is also referred to as:";
		echo "<ul style=\"list-style:none;\">";
		foreach ($marker->aliases as $alias){
			echo "<li>{$alias->Alias} &nbsp;";
			if ($bLoggedIn) {
				echo "[<a href=\"showBiomarker.php?".
				"m=$_GET[m]&remove=alias&which={$alias->Alias}\">delete</a>]";
			}
			echo "</li>";
		}
		echo "</ul>";
	}
	
	// Associations may only be performed if the user is logged in
	if ($bLoggedIn) {
?>
			<form action="showBiomarker.php" method="GET">
			<input type="hidden" name="m" value="<?php echo $_GET['m'];?>"/>
			<script type="text/javascript">
				ti('tialias','alias','Add an alias...','margin-top:5px;width:160px;');
			</script>
			<input type="submit" style="margin-top:5px;" value="Add"/>
			</form>
<?php } ?>
			</div>
		</div>
		<div class="categoryBox">
			<h2>Organs</h2>
			<div style="padding-left:3px; padding-bottom:5px;">
<?php
	if (sizeof($marker->organs) == 0){
		echo "This biomarker has no organ data";
	} else {
		echo "Organs with data for this biomarker:";
		echo "<ul style=\"list-style:none;\">";
		foreach ($marker->organs as $organ){
			echo "<li><a href=\"showBiomarkerOrgan.php?m=$_GET[m]&o={$organ->organ->ID}\">{$organ->organ->Name}</a> &nbsp;";
			if ($bLoggedIn){
				echo " [<a href=\"showBiomarker.php?".
				"m=$_GET[m]&remove=organ&which={$organ->organ->ID}\">delete</a>]";
			}
		echo "</li>";
		}
		echo "</ul>";
	}
	
	// Associations may only be performed if the user is logged in
	if ($bLoggedIn) {
?>			
			<form action="showBiomarker.php" method="GET">
			<input type="hidden" name="m" value="<?php echo $_GET['m'];?>"/>
			<select name="addorgan" style="width:160px;vertical-align:middle;font-size:1.0em;background-color:#eee;">
<?php
			$organdao = new dao_organ();
			$organCount = $organdao->numRecords();
			$organs   = $organdao->getRange(0,$organCount);
			foreach ($organs as $o){
				echo "<option value=\"{$o->ID}\">{$o->Name}</option>\r\n";
			}
?>
			</select>
			<input type="submit" style="margin-top:5px;" value="Add"/>
			</form>
<?php } ?>
			</div>
			</div>
		</div>

	<div id="editSubject">
	<?php echo $formBuilder->getHtml();?>
	<br/>
	<h2>Studies</h2>
<?php
	if (sizeof($marker->studies) == 0){
		echo 'This biomarker has not yet been associated with any studies. ';
	} else {
		echo 'This biomarker has been associated with the following studies: ';
		echo "<ul>";
		foreach ($marker->studies as $study){
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
		<form action="../editors/showBiomarker.php" method="GET">
			<input type="hidden" name="m" value="<?php echo $_GET['m']?>"/>
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
	if (sizeof($marker->publications) == 0){
		echo 'This biomarker has not yet been associated with any publications. ';
	} else {
		echo 'This biomarker has been associated with the following publications: ';
		echo "<ul>";
		foreach ($marker->publications as $pub){
			echo "<li><a href=\"showPublication.php?p={$pub->publication->ID}\">{$pub->publication->Title}</a> &nbsp;";
			if ($bLoggedIn) {
				echo "[ <a href=\"showBiomarker.php?m={$_GET['m']}&remove=publication&".
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
		<span class="hint">Associate an existing publication:</span>
		<form action="../editors/showBiomarker.php" method="GET">
			<input type="hidden" name="m" value="<?php echo $_GET['m']?>"/>
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
	if (sizeof($marker->resources) == 0){
		echo 'This biomarker has not yet been associated with any resources. ';
	} else {
		echo 'This biomarker has been associated with the following resources: ';
		echo "<ul>";
		foreach ($marker->resources as $res){
			echo "<li><a href=\"{$res->resource->URL}\">{$res->resource->Name}</a> &nbsp;";
			if ($bLoggedIn) {
				echo "[ <a href=\"showBiomarker.php?m={$_GET['m']}&remove=resource&".
				"which={$res->resource->ID}\">Remove This Association</a> ]";
			}
			echo "</li>";
		}
		echo "</ul>";
	}
	
	// Associations may only be performed if the user is logged in
	if ($bLoggedIn) {
?>
	
		<span class="hint">Add a New Resource:</span>
		<div id="addResource">
		<form action="../util/addResources.php" method="GET">
			<input type="hidden" name="type" value="biomarker"/>
			<input type="hidden" name="which" value="<?php echo $_GET['m'];?>"/>
			<input type="hidden" name="redirect" value="../editors/showBiomarker.php?m=<?php echo $_GET['m'];?>"/>
			<table>
				<tr><td>Title (Name):</td><td><input id="addresRname" type="text" name="rname" style="width:200px;"/></td></tr>
				<tr><td>URL:</td><td><input id="addresRurl" type="text" name="rurl" style="width:300px;"/>
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