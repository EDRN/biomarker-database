<?php
	require_once("../util/definitions.inc.php");
	require_once(BMDB_ROOT."/classes/Bmdb.class.php");
	require_once(BMDB_ROOT."/model/ModelProperties.inc.php");
	
	// Authentication
	$auth = Bmdb::getAuthObject();
	$bLoggedIn = $auth->checkLoginStatus();
	
	// Database 
	$db = Bmdb::getDatabaseObject();
	
	// Page Header Setup
	$p = new cwsp_page("EDRN - Biomarker Database - Browse Studies");
	$p->includeJS('../cots/scriptaculous-js-1.7.0/lib/prototype.js');
	$p->includeJS('../cots/scriptaculous-js-1.7.0/src/scriptaculous.js');
	$p->includeJS('../js/createObjects.js');
	$p->includeCSS('../css/common.css');
	$p->includeCSS('../css/alkalai.css');
	$p->includeCSS('../css/browser.css');
	$p->insertRaw(PloneHeader::generate('../'));
	$p->drawHeader();
	include_once(BMDB_ROOT.'/plone/plone_body.inc.php');
	
	Bmdb::doTopStatusBar($bLoggedIn,'../');
	
	// Grab pagination data from the GET parameters
	$start = isset($_GET['s']) ? $_GET['s'] : 0;
	$count = min(isset($_GET['c']) ? $_GET['c'] : 10, 250); // force limit max results per page to 250
	$end   = $start + $count;
	
	// Get information on total number of studies
	$studydao = new dao_study();
	$studyCount = $studydao->numRecords();
	if ($studyCount <= $count) {
		$start = 0;
	}
	
	// Retreive data
	$studies   = $studydao->getRange($start,$end);
?>
	
<div id="outerContainer">
<div id="editTools">
<div class="categoryBox">
	<h2>Actions</h2>
	<ul><li><a href="../">Return Home</a></li>
<?php if ($bLoggedIn) {?>
		<li><div><span id="createNewStudyLabel"><a href="javascript:doCreateStudy();">Create a new Study</a></span>
			<div id="createNewStudy" style="display:none;">
				<form action="../editors/showStudy.php">
					<input type="hidden" name="s" value="new"/>
					<input type="text" id="newStudyLongName" name="ln" style="width:116px;"/>&nbsp;
					<input type="submit" value="Go" onclick="return $(\'newStudyLongName\').value != \'\';"/>&nbsp;<a href="javascript:cancelCreateStudy();">cancel</a>
				</form>
			</div>
			</div>
		</li>	
<?php } ?>
		</ul>
	</div>
	</div>

<div id="resultContainer">
<h2>Browse Studies</h2>
<div style="text-align:right;"><?php echo $studyCount;?> Studies Found | Order By: ID | Results per page: 
<?php 
	// Results Per Page --
	echo '<form id="frmResultsPerPage" method="GET" style="display:inline;">';
	echo '<input type="hidden" name="s" value="'.$start.'">'; 
	echo '<select name="c" onchange="this.form.submit();" style="font-size:10px;"><option value="10">--</option>';
	echo '<option ' . (($count == 5)? 'selected="selected"' : '') .' value="5">5</option>';
	echo '<option ' . (($count == 10)? 'selected="selected"' : '').' value="10">10</option>';
	echo '<option ' . (($count == 25)? 'selected="selected"' : '').' value="25">25</option>';
	echo '<option ' . (($count == 50)? 'selected="selected"' : '').' value="50">50</option>';
	echo '</select>';
	echo '</form>';
	
	// Pagination --
	echo ' | ';
	if ($start - $count >= 0){
		echo '&nbsp;<a href="?s='.($start-$count).'&c='.$count.'">Prev</a> &nbsp;';
	} else {
		echo '&nbsp;Prev &nbsp;';
	}
	$numPages = ceil($studyCount / $count);
	for ($i = 0; $i < $numPages; $i++){
		if ($i*$count >= $start && ($i*$count + $count) <= ($start+$count)){
			echo ($i+1)." ";	// Current page (no link)
		} else {
			echo '<a href="?s='.$count*$i.'&c='.$count.'">'.($i+1).'</a> ';
		}
	}
	if ($start + $count < $studyCount){
		echo '&nbsp;<a href="?s='.($start+$count).'&c='.$count.'">Next</a> &nbsp;';
	} else {
		echo '&nbsp;Next &nbsp;';
	}
	echo '</div>';
	
	// Results
	echo '<table id="resultTable" style="width:440px;">';
	echo '<tr><th>ID</th><th>Title</th></tr>';
	$studyCount = 0;
	foreach ($studies as $study){
		if (($studyCount++ %2) == 0){
			echo '<tr class="browseEvenRow">';
		} else {
			echo '<tr class="browseRow">';
		}
		echo "<td class=\"browseEntry\">$study->ID</td>";
		echo "<td class=\"browseEntry\"><a href=\"../editors/showStudy.php?s=$study->ID\">$study->Title</a></td>";
		echo "</tr>";
	}
	echo '</table>';
?>
<br/>
<form action="../editors/showStudy.php" method="GET">
<input type="text" style="width:300px;background:rgb(240,240,240);color:rgb(80,80,80);" id="autocomplete" name="autocomplete_parameter" value=" Search by 'Long Name' Here..." onFocus="this.style.color='rgb(0,0,0)';this.style.background='rgb(255,255,255)';this.value='';" onBlur="this.style.color='rgb(80,80,80)';this.style.background='rgb(240,240,240)';this.value=' Search by \'Long Name\' Here...';"/>
<input type="hidden" id="autocompleteID" name="s" value=""/>
<span id="indicator1" style="display: none;"><img src="../assets/images/spinner.gif" alt="Working..." /></span>
<!-- <input type="submit" value="Go"/>-->
</form>
<div id="autocomplete_choices" class="autocomplete">&nbsp;</div>

<script type="text/javascript">
	function afterAutocomplete(field,element){
		$('autocompleteID').value = element.id;
	}
	new Ajax.Autocompleter("autocomplete", "autocomplete_choices", "../util/autocomplete/Study.php", {indicator: 'indicator1',afterUpdateElement:afterAutocomplete});
</script>
<?php
	echo '</div>';		
	echo '<div style="clear:both;"></div>';
	echo '</div>';

	// Close off the page
	$p->drawFooter();
?>