<?php
	require_once("../../xpress/app.php");
	
	$start = isset($_GET['start']) ? $_GET['start'] : 0;
	$count = min(isset($_GET['count']) ? $_GET['count'] : 10, 250);
	$q = "SELECT `objId`,`Title`,`BiomarkerID`,`Type` "
		."FROM `Biomarker` "
		."LIMIT $start,$count ";
	$markers = $xpress->db()->getAll($q);
	
	// Page Header Setup
	$p = new XPressPage("EDRN - Biomarker Database 0.3.0 Beta","text/html","UTF-8");
	$p->includeCSS('../../static/css/frozen.css');
	$p->open();
	$p->view()->LoadTemplate('view/browse.html');
	$p->view()->MergeBlock("marker",$markers);
	$p->view()->Show();
	$p->close();
	
	exit();
	require_once("../../assets/skins/edrn/prologue.php");
?>
<div class="main">
<!-- Breadcrumbs Area -->
	<div class="mainContent" style="padding-bottom:0px;margin-bottom:0px;border-bottom:solid 3px #a0a0a0;padding:3px;color:#666;">
<?php 
	echo "<a href=\"../../index.php\">Home</a> / Biomarkers ";
?>
	</div><!-- End Breadcrumbs -->
	<div class="mainContent">
<?php if (!empty($_GET['notice'])){?>
	<div id="notice" class="info"><?php if(isset($_GET['notice'])){echo $_GET['notice'];}?>&nbsp;&nbsp;(<span class="pseudolink" onclick="Element.hide('notice');">OK</span>)</div>
<?php } ?>
	<h2 class="title">Browse Biomarkers</h2>
	<table class="browser">
	  <tr><th>Title (Long Name)</th><th>Identifier</th><th>Phase</th></tr>
<?php
	foreach ($markers as $marker){
		$marker_type = (($marker['Type'] == '') ? "<em>not specified</em>" : $marker['Type']);
		echo "<tr><td><a href=\"../../biomarker.php?view=basics&objId={$marker['objId']}\">{$marker['Title']}</td>"
			."<td>{$marker['BiomarkerID']}</td>"
			."<td>{$marker_type}</td></tr>";
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