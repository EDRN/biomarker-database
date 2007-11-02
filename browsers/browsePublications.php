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
	$p = new cwsp_page("EDRN - Biomarker Database - Browse Publications");
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
	
	// Get information on total number of markers
	$pubdao = new dao_publication(&$db);
	$pubCount = $pubdao->numRecords();
	if ($pubCount <= $count) {
		$start = 0;
	}
	
	// Retreive data
	$publications   = $pubdao->getRange($start,$end);
	
	echo '<div id="outerContainer">';
	echo '<div id="editTools">';
	echo '	<div class="categoryBox">';
	echo '		<h2>Actions</h2>
		<ul><li><a href="../">Return Home</a></li>';
	if ($bLoggedIn) {
		echo '<li><div><span id="createNewPublicationLabel"><a href="javascript:doCreatePublication();">Create a new Publication</a></span>
				<div id="createNewPublication" style="display:none;">
					<form action="../editors/showPublication.php">
						<input type="hidden" name="p" value="new"/>
						<input type="text" name="pmid" style="width:116px;"/>&nbsp;
						<input type="submit" value="Go"/>&nbsp;<a href="javascript:cancelCreatePublication();">cancel</a>
					</form>
				</div>
				</div>
			</li>';
	}	
	echo '	</ul>';
	echo '	</div>';
	echo '</div>';
	
	echo '<div id="resultContainer">';
	echo '<h2>Browse Publications</h2>';
	echo '<div style="text-align:right;">'.$pubCount.' Publications Found | Order By: ID | Results per page: ';
	
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
	$numPages = ceil($pubCount / $count);
	for ($i = 0; $i < $numPages; $i++){
		if ($i*$count >= $start && ($i*$count + $count) <= ($start+$count)){
			echo ($i+1)." ";	// Current page (no link)
		} else {
			echo '<a href="?s='.$count*$i.'&c='.$count.'">'.($i+1).'</a> ';
		}
	}
	if ($start + $count < $pubCount){
		echo '&nbsp;<a href="?s='.($start+$count).'&c='.$count.'">Next</a> &nbsp;';
	} else {
		echo '&nbsp;Next &nbsp;';
	}
	echo '</div>';
	
	// Results
	echo '<table id="resultTable">';
	echo '<tr><th>ID</th><th>PubMed ID</th><th>Title</th><th>Journal</th></tr>';
	$pubCount = 0;
	foreach ($publications as $pub){
		if (($pubCount++ %2) == 0){
			echo '<tr class="browseEvenRow">';
		} else {
			echo '<tr class="browseRow">';
		}
		echo "<td class=\"browseEntry\">$pub->ID</td>";
		echo "<td class=\"browseEntry\"><a href=\"../editors/showPublication.php?p=$pub->ID\">$pub->PubMedID</a></td>";
		echo "<td class=\"browseEntry\">$pub->Title</td>";
		echo "<td class=\"browseEntry\">$pub->Journal</td></tr>";
	}
	echo '</table>';
	echo '</div>';

	echo '<div style="clear:both;"></div>';
	echo '</div>';

	// Close off the page
	$p->drawFooter();
?>