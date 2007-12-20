<?php
	require_once("../../model/ModelProperties.inc.php");
	
	$start = isset($_GET['start']) ? $_GET['start'] : 0;
	$count = min(isset($_GET['count']) ? $_GET['count'] : 10, 250);
	$q = "SELECT `objId`,`DMCC_ID`,`Title`,`BiomarkerStudyType`,`Abstract` "
		."FROM `Study` "
		."LIMIT $start,$count ";
	$studies = $XPress->Database->safeGetAll($q);
	
	// Page Header Setup
	$p = new cwsp_page("EDRN - Biomarker Database v0.4 Beta");
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
<?php if (!empty($_GET['notice'])){?>
	<div id="notice" class="info"><?php if(isset($_GET['notice'])){echo $_GET['notice'];}?>&nbsp;&nbsp;(<span class="pseudolink" onclick="Element.hide('notice');">OK</span>)</div>
<?php } ?>
	<div class="mainContent">
	<h2 class="title">Browse Studies</h2>
	<table class="browser" style="width:800px;">
	  <tr><th>Title</th><th>DMCC ID</th><th>Abstract Clip</th></tr>
<?php
	foreach ($studies as $study){
		echo "<tr><td style=\"width:280px;\"><a href=\"../../study.php?view=basics&objId={$study['objId']}\">{$study['Title']}</td>"
			."<td style=\"text-align:center;\">{$study['DMCC_ID']}</td>";
			if ($study['Abstract'] != ''){
				echo "<td>".substr($study['Abstract'],0,300).((strlen($study['Abstract']) > 300)? "..." : "")."</td></tr>";
			} else {
				echo "<td><span class=\"hint\"><em>no abstract available</em></span></td></tr>";
			}
			
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