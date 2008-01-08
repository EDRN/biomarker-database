<?php
	require_once("model/ModelProperties.inc.php");
	
	$xp = new XPress();
	$object = new objBiomarker($xp);
	//print_r($marker);
	
	if (isset($_POST['special'])){
		if ($_POST['special'] == 'createnew'){
			$obj = new objBiomarker($xp);
			$obj->create($_POST['title']);
			$titleNoSpace = preg_replace("/ /","",ucwords($_POST['title']));
			$obj->setBiomarkerID("urn:edrn:biomarker:{$titleNoSpace}");
			cwsp_page::httpRedirect("./biomarker.php?objId={$obj->getObjId()}&view=basics");
		}
		if ($_POST['special'] == 'delete'){
			$obj = new objBiomarker($xp,$_POST['objId']);
			$obj->delete();
			cwsp_page::httpRedirect("browse/biomarkers/?notice=Biomarker+deleted.");
		}
	}
	
	
	// Page Header Setup
	$p = new cwsp_page("EDRN - Biomarker Database v0.4 Beta","text/html; charset=UTF-8");

	$p->includeJS('js/scriptaculous-js-1.7.0/lib/prototype.js');
	$p->includeJS('js/scriptaculous-js-1.7.0/src/scriptaculous.js');
	$p->includeJS('model/AjaxAPI.js');
	$p->includeJS('model/AjaxAPIExtensions.js');
	$p->includeJS('js/textInputs.js');
	$p->includeJS('model/AjaxHandler.js');
	$p->includeCSS('css/whiteflour.css');
	$p->includeCSS('css/cwspTI.css');
	$p->drawHeader();
	
	$validObject = $object->initialize($_GET['objId']);
	
	require_once("assets/skins/edrn/prologue.php");
?>
<div class="main">
	<div class="mainContent">
		<h2 class="title">Biomarker
		<span class="titleDetails"><?php if($validObject){echo $object->getTitle();}?></span>
		</h2>
		<div class="smallLinks">
			<a href="biomarker.php?view=basics&objId=<?php echo $_GET['objId']?>" <?php echo ($_GET['view'] == 'basics')? 'class="activeLink"' : '';?>>Basics</a>&nbsp; |
			<a href="biomarker.php?view=organs&objId=<?php echo $_GET['objId']?>" <?php echo ($_GET['view'] == 'organs')? 'class="activeLink"' : '';?>>Organs</a>&nbsp; |
			<a href="biomarker.php?view=studies&objId=<?php echo $_GET['objId']?>" <?php echo ($_GET['view'] == 'studies')? 'class="activeLink"' : '';?>>Studies</a>&nbsp; |
			<a href="biomarker.php?view=publications&objId=<?php echo $_GET['objId']?>" <?php echo ($_GET['view'] == 'publications')? 'class="activeLink"' : '';?>>Publications</a>&nbsp; |
			<a href="biomarker.php?view=resources&objId=<?php echo $_GET['objId']?>" <?php echo ($_GET['view'] == 'resources')? 'class="activeLink"' : '';?>>Resources</a>
		</div>
		<?php 
			if (!$validObject){
				echo "<br/>";
				cwsp_messages::err("Biomarker not found!");	
			} else {
				include_once("sections/biomarker/{$_GET['view']}.php");
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