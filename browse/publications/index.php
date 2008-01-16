<?php
	require_once("../../model/ModelProperties.inc.php");
	
	$start = isset($_GET['start']) ? $_GET['start'] : 0;
	$count = min(isset($_GET['count']) ? $_GET['count'] : 10, 250);
	$q = "SELECT `objId`,`PubMedID`,`Title`,`Author`,`Journal` "
		."FROM `Publication` "
		."LIMIT $start,$count ";
	$publications = $XPress->Database->safeGetAll($q);
	
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
<!-- Breadcrumbs Area -->
	<div class="mainContent" style="padding-bottom:0px;margin-bottom:0px;border-bottom:solid 3px #a0a0a0;padding:3px;color:#666;">
<?php 
	echo "<a href=\"../../index.php\">Home</a> / Publications ";
?>
	</div><!-- End Breadcrumbs -->
	<div class="mainContent">
<?php if (!empty($_GET['notice'])){?>
	<div id="notice" class="info"><?php if(isset($_GET['notice'])){echo $_GET['notice'];}?>&nbsp;&nbsp;(<span class="pseudolink" onclick="Element.hide('notice');">OK</span>)</div>
<?php } ?>
	<h2 class="title">Browse Publications</h2>
	<table class="browser">
	  <tr><th>PubMedID</th><th>Title</th><th>Journal</th><th>Author</th></tr>
<?php
	foreach ($publications as $publication){
		echo "<tr><td><a href=\"../../publication.php?view=basics&objId={$publication['objId']}\">{$publication['PubMedID']}</td>"
			."<td>{$publication['Title']}</td>"
			."<td>{$publication['Journal']}</td>"
			."<td>{$publication['Author']}</td></tr>";
	}
?> 
	</table>
	
		<div class="actions">
			<ul>
				  <li><a href="../../index.php">Return Home</a></li>
			</ul>
		</div>
		<div class="specialactions">
			<ul>
				<li><a href="../../util/importpubmed.php">Import a publication</a></li>
			</ul>
		</div>
	</div>
</div>
<?php
	require_once("../../assets/skins/edrn/epilogue.php");
	$p->drawFooter();
?>