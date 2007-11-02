<?php
	require_once ('../cots/crawwler-cwsp-1.0.75/cwsp.inc.php');
	
	$xmldb = new cwsp_db("mysql://root:root@localhost/edrn-scrape");
	$bmdb  = new cwsp_db("mysql://bmdb:canc3r@localhost/biomarker2");
	
	$r = $xmldb->safeQuery("SELECT * FROM protocols");
	echo "ABOUT TO IMPORT " . $r->numRows() . " STUDIES. <br/>-- scroll to the bottom of this page for summary info...<br/><br/>";
	$processed = 0; 
	$empty = 0;
	
	while ($prot = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
		if ($prot['Name'] == ''){$empty++;continue;}
		
		try {
			$q = "INSERT INTO study VALUES(\"\",\"$prot[ID]\",\"$prot[Name]\",\"$prot[Abstract]\",\"$prot[StudyDesign]\",\"\")";
			$bmdb->query($q);
			$processed++;
			echo "ADDED: " . $prot['ID'] . " : " . $prot['Name'] . "<br/>";
		} catch (cwsp_QueryException $e){
			echo $e->__toString();
			echo "STUDY WAS: $prot[ID] -- $prot[Name]<br/><br/>";
		}
	}
	echo "<br/>FINISHED:<br/>IMPORTED $processed STUDIES. (" . ($r->numRows() - $processed - $empty) . " errors encountered, $empty empty rows)<br/><br/>";
?>