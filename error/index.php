<?php
	require_once("../xpress/app.php");
	
	// Get the error type
	$type = $_GET['e'];
	// Get the target page (if provided)
	$target = (isset($_GET['target']))? $_GET['target'] : "";
	$target_encoded = urlencode($target);
	
	$p = new XPressPage(App::NAME." ".App::VERSION . " - Error","text/html","UTF-8");
	$p->includeCSS('../static/css/frozen.css');
	$p->includeCSS('../static/css/eip.css');
	$p->includeCSS("../static/css/autocomplete.css");
	$p->view()->LoadTemplate("view/default.html");

	
	$p->open();
	$p->view()->Show();
	$p->close();
?>