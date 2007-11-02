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
	$p = new cwsp_page("EDRN - Biomarker Database - Browse Organs");
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
	$start = isset($_GET['o']) ? $_GET['o'] : 0;
	$count = min(isset($_GET['c']) ? $_GET['c'] : 10, 250); // force limit max results per page to 250
	$end   = $start + $count;
	
	// Get information on total number of studies
	$organdao = new dao_organ();
	$organCount = $organdao->numRecords();
	if ($organCount <= $count) {
		$start = 0;
	}
	
	// Retreive data
	$organs   = $organdao->getRange($start,$end);
?>
	
<div id="outerContainer">
<div class="categoryBox">
	<h2>Actions</h2>
	<ul><li><a href="../">Return Home</a></li>
<?php if ($bLoggedIn) {?>
		<li><div><span id="createNewOrganLabel"><a href="javascript:doCreateOrgan();">Create a new Organ Site</a></span>
			<div id="createNewOrgan" style="display:none;">
				<form action="../editors/showOrgan.php">
					<input type="hidden" name="o" value="new"/>
					<input type="text" id="newOrganLongName" name="ln" style="width:116px;"/>&nbsp;
					<input type="submit" value="Go" onclick="return $(\'newOrganLongName\').value != \'\';"/>&nbsp;<a href="javascript:cancelCreateOrgan();">cancel</a>
				</form>
			</div>
			</div>
		</li>	
<?php }?>
		</ul>
	</div>
	</div>

<div id="resultContainer">
<h2>Browse Organ Sites</h2>
<div style="text-align:right;"><?php echo $studyCount;?> Organ Sites Found | Order By: ID | Results per page: 
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
	$organCount = 0;
	foreach ($organs as $organ){
		if (($organCount++ %2) == 0){
			echo '<tr class="browseEvenRow">';
		} else {
			echo '<tr class="browseRow">';
		}
		echo "<td class=\"browseEntry\">$organ->ID</td>";
		echo "<td class=\"browseEntry\"><a href=\"../editors/showOrgan.php?o=$organ->ID\">$organ->Name</a></td>";
		echo "</tr>";
	}
	echo '</table>';
?>
<br/>


<?php
	echo '</div>';		
	echo '<div style="clear:both;"></div>';
	echo '</div>';

	// Close off the page
	$p->drawFooter();
?>