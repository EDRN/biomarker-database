<?php
/**
 * 	EDRN Biomarker Database
 *  Curation Webapp
 * 
 *  Author: Andrew F. Hart (andrew.f.hart@jpl.nasa.gov)
 *  
 *  Copyright (c) 2008, California Institute of Technology. 
 *  ALL RIGHTS RESERVED. U.S. Government sponsorship acknowledged.
 * 
 */
	require_once("../../xpress/app.php");
	
	// Set up pagination
	$start = isset($_GET['start']) ? $_GET['start'] : 0;
	$startp1 = $start + 1;
	$count = min(isset($_GET['count']) ? $_GET['count'] : 25, 250);
	$q1 = "SELECT COUNT(*) FROM `Publication`";
	$total = $xpress->db()->getOne($q1);
	$stop  = min($total,$start + $count);
	
	// Process sort key
	$order_by = isset($_GET['order_by'])
		? strtolower($_GET['order_by']) 
		: '';
	if ($order_by == "id") {$order_by = "objId";}
	else if ($order_by == "author") {$order_by = "Author";}
	else {$order_by = "Title";} // Default sort key

	// Process sort order key
	$ascdesc = isset($_GET['order'])
		? strtolower($_GET['order']) 
		: '';
	if ($ascdesc == "down") {$ascdesc = "DESC";}
	else {$ascdesc = "ASC";} // Default sort order
	
	// Issue query
	$q = "SELECT `objId`,`Title`,`Author`,`Journal` "
		."FROM `Publication` "
		."ORDER BY `{$order_by}` {$ascdesc} "
		."LIMIT $start,$count ";
	$publications = $xpress->db()->getAll($q);
	
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
	
	$pages = array();
	
	for ($i =0; $i<(ceil($total / $count)); $i++){
		$pages[] = array("start"=>($i * $count),"label"=>($i+1));
	}
	
	// Display the page
	$p = new XPressPage(App::NAME." ".App::VERSION,"text/html","UTF-8");
	$p->includeJS("../../static/js/mootools/mootools-release-1.11.js");	
	$p->includeJS("../../static/js/autocomplete/Observer.js");
	$p->includeJS("../../static/js/autocomplete/Autocompleter.js");
	$p->includeJS("view/browse.js");
	$p->includeCSS('../../static/css/frozen.css');
	$p->includeCSS('../../static/css/frozenbrowser.css');
	$p->includeCSS("../../static/css/autocomplete.css");
	$p->open();
	$p->view()->LoadTemplate('view/browse.html');
	$p->view()->MergeBlock("pub",$publications);
	$p->view()->MergeBlock("pages",$pages);
	$p->view()->Show();
	$p->close();
	
	exit();
?>