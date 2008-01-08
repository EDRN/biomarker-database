<?php
	require_once("../../model/ModelProperties.inc.php");
	
	$start = isset($_GET['start']) ? $_GET['start'] : 0;
	$count = min(isset($_GET['count']) ? $_GET['count'] : 10, 250);
	$q = "SELECT `objId`,`Title`,`BiomarkerID`,`Phase` "
		."FROM `Biomarker` "
		."LIMIT $start,$count ";
	$markers = $XPress->Database->safeGetAll($q);
	
	// Page Header Setup
	$p = new cwsp_page("EDRN - Biomarker Database v0.4 Beta","text/html; charset=UTF-8");
	$p->includeJS('../../js/scriptaculous-js-1.7.0/lib/prototype.js');
	$p->includeJS('../../js/scriptaculous-js-1.7.0/src/scriptaculous.js');
	$p->includeJS('../../js/textInputs.js');
	$p->includeJS('../../model/AjaxHandler.js');
	$p->includeCSS('../../css/whiteflour.css');
	$p->includeCSS('../../css/cwspTI.css');
	$p->drawHeader();
	
	require_once("../../assets/skins/edrn/prologue.php");
?>
<div class="main">
	<div class="mainContent">
<?php if (!empty($_GET['notice'])){?>
	<div id="notice" class="info"><?php if(isset($_GET['notice'])){echo $_GET['notice'];}?>&nbsp;&nbsp;(<span class="pseudolink" onclick="Element.hide('notice');">OK</span>)</div>
<?php } ?>
	<h2 class="title">Browse Biomarkers</h2>
	<table class="browser">
	  <tr><th>Title (Long Name)</th><th>Identifier</th><th>Phase</th></tr>
<?php
	foreach ($markers as $marker){
		echo "<tr><td><a href=\"../../biomarker.php?view=basics&objId={$marker['objId']}\">{$marker['Title']}</td>"
			."<td>{$marker['BiomarkerID']}</td>"
			."<td>{$marker['Phase']}</td></tr>";
	}
?> 
	</table>
	
		<div class="actions">
		<ul>
			  <li><a href="../../index.php">Return Home</a></li>
		</ul>
	</div>
	</div>
</div>
<?php
	require_once("../../assets/skins/edrn/epilogue.php");
	$p->drawFooter();
?>