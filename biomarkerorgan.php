<?php
	require_once("model/ModelProperties.inc.php");
	
	// Page Header Setup
	$p = new cwsp_page("EDRN - Biomarker Database v0.4 Beta");
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
	<div class="mainContent">
		<h2 class="title">Biomarker/Organ
		<span class="titleDetails"><?php if ($validObject){echo $object->getBiomarker()->getTitle();}?>/<?php if($validObject){echo $object->getOrgan()->getName();}?></span>
		</h2>
		<div class="smallLinks">
			<a href="biomarkerorgan.php?view=basics&objId=<?php echo $_GET['objId']?>" <?php echo ($_GET['view'] == 'basics')? 'class="activeLink"' : '';?>>Basics</a>&nbsp; |
			<a href="biomarkerorgan.php?view=studies&objId=<?php echo $_GET['objId']?>" <?php echo ($_GET['view'] == 'studies')? 'class="activeLink"' : '';?>>Studies</a>&nbsp; |
			<a href="biomarkerorgan.php?view=biomarker&objId=<?php echo $_GET['objId']?>" <?php echo ($_GET['view'] == 'biomarker')? 'class="activeLink"' : '';?>>Biomarker</a>&nbsp; |
			<a href="biomarkerorgan.php?view=publications&objId=<?php echo $_GET['objId']?>" <?php echo ($_GET['view'] == 'publications')? 'class="activeLink"' : '';?>>Publications</a>&nbsp; |
			<a href="biomarkerorgan.php?view=resources&objId=<?php echo $_GET['objId']?>" <?php echo ($_GET['view'] == 'resources')? 'class="activeLink"' : '';?>>Resources</a>
		</div>
		<?php 
			if (!$validObject){
				echo "<br/>";
				cwsp_messages::err("Biomarker/Organ not found!");	
			} else {
				include_once("sections/biomarker-organ/{$_GET['view']}.php");
			}
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