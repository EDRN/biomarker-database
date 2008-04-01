<?php
	require_once("../../xpress/app.php");
	
	// Set up pagination
	$start = isset($_GET['start']) ? $_GET['start'] : 0;
	$startp1 = $start + 1;
	$count = min(isset($_GET['count']) ? $_GET['count'] : 10, 250);
	$q1 = "SELECT COUNT(*) FROM `Biomarker`";
	$total = $xpress->db()->getOne($q1);
	$stop  = min($total,$start + $count);
	
	// Process sort key
	$order_by = isset($_GET['order_by'])
		? strtolower($_GET['order_by']) 
		: '';
	if ($order_by == "id") {$order_by = "objId";}
	else if ($order_by == "urn") {$order_by = "BiomarkerID";}
	else if ($order_by == "type"){$order_by = "Type";}
	else {$order_by = "Title";} // Default sort key

	// Process sort order key
	$ascdesc = isset($_GET['order'])
		? strtolower($_GET['order']) 
		: '';
	if ($ascdesc == "down") {$ascdesc = "DESC";}
	else {$ascdesc = "ASC";} // Default sort order
	
	// Issue query
	$q = "SELECT `objId`,`Title`,`BiomarkerID`,`Type` "
		."FROM `Biomarker` "
		."ORDER BY `{$order_by}` {$ascdesc} "
		."LIMIT $start,$count ";
	$markers = $xpress->db()->getAll($q);
	
	// Determine pagination variables
	$pagelast = 0;
	if ($start > 0) {
		$pageback = max(0,$start - $count);
	}
	if ($start < $total) {
		$pagenext = $stop;
	}
	if (($total - $count) > $start) {
		$pagelast = $total-$count;
	}
	
	// Display the page
	$p = new XPressPage("EDRN - Biomarker Database 0.3.0 Beta","text/html","UTF-8");
	$p->includeCSS('../../static/css/frozen.css');
	$p->includeCSS('../../static/css/frozenbrowser.css');
	$p->open();
	$p->view()->LoadTemplate('view/browse.html');
	$p->view()->MergeBlock("marker",$markers);
	$p->view()->Show();
	$p->close();
	
	exit();
?>