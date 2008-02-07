<?php
	// Page Header Setup
	$p = new cwsp_page("EDRN - Biomarker Database v0.4 Beta","text/html; charset=UTF-8");
	$p->includeJS('../js/scriptaculous-js-1.7.0/lib/prototype.js');
	$p->includeJS('../js/scriptaculous-js-1.7.0/src/scriptaculous.js');
	$p->includeJS('../js/textInputs.js');
	$p->includeJS('../model/AjaxHandler.js');
	$p->includeCSS('../css/whiteflour.css');
	$p->includeCSS('../css/cwspTI.css');
	$p->drawHeader();
	
	require_once(Modeler::ROOT_DIR."/assets/skins/edrn/prologue.php");
?>
<div class="main">
<!-- Breadcrumbs Area -->
	<div class="mainContent" style="padding-bottom:0px;margin-bottom:0px;border-bottom:solid 3px #a0a0a0;padding:3px;color:#666;">
<?php 
	echo "<a href=\"./\">Home</a> / Log In";
?>
</div><!-- End Breadcrumbs -->
	<div class="mainContent">
	<h2 class="title" style="margin-bottom:13px;">Log In:</h2>

