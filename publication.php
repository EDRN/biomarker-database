<?php
	require_once("model/ModelProperties.inc.php");
	
	$xp = new XPress();
	$object = new objPublication($xp);
	//print_r($marker);
	
	if (isset($_POST['special'])){
		if ($_POST['special'] == 'delete'){
			$obj = new objPublication($xp,$_POST['objId']);
			$obj->delete();
			cwsp_page::httpRedirect("browse/publications/?notice=Publication+deleted.");
		}
	}
	
	// Page Header Setup
	$p = new cwsp_page("EDRN - Biomarker Database v0.4 Beta","text/html; charset=UTF-8");
	$p->includeJS('js/scriptaculous-js-1.7.0/lib/prototype.js');
	$p->includeJS('js/scriptaculous-js-1.7.0/src/scriptaculous.js');
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
		<h2 class="title">Publication
		<span class="titleDetails"><?php if($validObject){echo "PubMed ID: {$object->getPubMedID()}";}?></span>
		</h2>
		<div class="smallLinks">
			<a href="biomarker.php?view=basics&objId=<?echo $_GET['objId']?>" <?php echo ($_GET['view'] == 'basics')? 'class="activeLink"' : '';?>>Basics</a>
			<!--
			&nbsp; |
			<a href="biomarker.php?view=organs&objId=<?php echo $_GET['objId']?>" <?php echo ($_GET['view'] == 'organs')? 'class="activeLink"' : '';?>>Organs</a>&nbsp; |
			<a href="biomarker.php?view=studies&objId=<?php echo $_GET['objId']?>" <?php echo ($_GET['view'] == 'studies')? 'class="activeLink"' : '';?>>Studies</a>&nbsp; |
			<a href="biomarker.php?view=publications&objId=<?php echo $_GET['objId']?>" <?php echo ($_GET['view'] == 'publications')? 'class="activeLink"' : '';?>>Publications</a>&nbsp; |
			<a href="biomarker.php?view=resources&objId=<?php echo $_GET['objId']?>" <?php echo ($_GET['view'] == 'resources')? 'class="activeLink"' : '';?>>Resources</a>
			-->
		</div>
		<?php 
			if (!$object->initialize($_GET['objId'])){
				echo "<br/>";
				cwsp_messages::err("Publication not found!");	
			} else {
				include_once("sections/publication/{$_GET['view']}.php");
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