<?php
	require_once("model/ModelProperties.inc.php");
	
	// Page Header Setup
	$p = new cwsp_page("EDRN - Biomarker Database v0.4 Beta","text/html; charset=UTF-8");
	$p->includeJS('js/scriptaculous-js-1.7.0/lib/prototype.js');
	$p->includeJS('js/scriptaculous-js-1.7.0/src/scriptaculous.js');
	$p->includeJS('js/textInputs.js');
	$p->includeJS('model/AjaxAPI.js');
	$p->includeJS('model/AjaxAPIExtensions.js');
	$p->includeJS('model/AjaxHandler.js');
	$p->includeCSS('css/whiteflour.css');
	$p->includeCSS('css/cwspTI.css');
	$p->drawHeader();
	
	$xp = new XPress();
	$object = new objBiomarkerOrganData($xp);
	if ($object->initialize($_GET['objId'])){
		$validObject = true;
	}
	
	require_once("assets/skins/edrn/prologue.php");	
?>
<div class="main">
	<?php 
			if (!$validObject){
				cwsp_messages::err("Biomarker/Organ not found!");	
				exit();
			} 
		?>
	<!-- Breadcrumbs Area -->
	<div class="mainContent" style="padding-bottom:0px;margin-bottom:0px;border-bottom:solid 3px #a0a0a0;padding:3px;color:#666;">
<?php 
	echo "<a href=\"index.php\">Home</a> / <a href=\"browse/biomarkers\">Biomarkers</a> / <a href=\"biomarker.php?view=basics&objId={$object->getBiomarker()->getObjId()}\">{$object->getBiomarker()->getTitle()}</a> / <a href=\"biomarker.php?view=organs&objId={$object->getBiomarker()->getObjId()}\">Organs</a> / ";
	if ($_GET['view'] == 'basics') { echo "  {$object->getOrgan()->getName()} "; }
	else if ($_GET['view'] == 'studies') {echo " <a href=\"biomarkerorgan.php?view=basics&objId={$_GET['objId']}\">{$object->getOrgan()->getName()}</a> / studies"; }
	else if ($_GET['view'] == 'publications') { echo " <a href=\"biomarkerorgan.php?view=basics&objId={$_GET['objId']}\">{$object->getOrgan()->getName()}</a> / publications"; }
	else if ($_GET['view'] == 'resources') { echo " <a href=\"biomarkerorgan.php?view=basics&objId={$_GET['objId']}\">{$object->getOrgan()->getName()}</a> / resources"; }
?>
	</div><!-- End Breadcrumbs -->
	<div class="mainContent">
		<h2 class="title">
		<span class="titleDetails"><?php if ($validObject){echo $object->getBiomarker()->getTitle();}?> :: <?php if($validObject){echo $object->getOrgan()->getName();}?></span>
		</h2>
		<div class="smallLinks">
			<a href="biomarkerorgan.php?view=basics&objId=<?php echo $_GET['objId']?>" <?php echo ($_GET['view'] == 'basics')? 'class="activeLink"' : '';?>>Basics</a>&nbsp; |
			<a href="biomarkerorgan.php?view=studies&objId=<?php echo $_GET['objId']?>" <?php echo ($_GET['view'] == 'studies')? 'class="activeLink"' : '';?>>Studies</a>&nbsp; |
			<a href="biomarkerorgan.php?view=publications&objId=<?php echo $_GET['objId']?>" <?php echo ($_GET['view'] == 'publications')? 'class="activeLink"' : '';?>>Publications</a>&nbsp; |
			<a href="biomarkerorgan.php?view=resources&objId=<?php echo $_GET['objId']?>" <?php echo ($_GET['view'] == 'resources')? 'class="activeLink"' : '';?>>Resources</a>
		</div>
		<?php 
			include_once("sections/biomarker-organ/{$_GET['view']}.php");
		?>
	</div>
	<div class="actions">
		<ul>
			  <li><a href="index.php">Return Home</a></li>
		</ul>
	</div>
</div>
<?php
	require_once("assets/skins/edrn/epilogue.php");
	$p->drawFooter();
?>