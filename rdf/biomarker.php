<?php
	require_once("../xpress/app.php");
	header("content-type:application/rdf+xml; charset=utf-8");

echo <<<END
<?xml version='1.0' encoding='UTF-8'?>
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:bmdb="http://edrn.nci.nih.gov/rdf/rdfs/bmdb-1.0.0#">

END;
	
	$biomarkerId = 20;
	$aboutURL = "http://bmdb.jpl.nasa.gov/edit/biomarker/?id={$biomarkerId}";
	$q = "SELECT `objId` "
		."FROM `Biomarker` ";
	$ids = $xpress->db()->getAll($q);
	foreach ($ids as $id) {
		$b = BiomarkerFactory::Retrieve($id['objId']);
		// Basics
		echo "  <bmdb:Biomarker rdf:about=\"{$aboutURL}\">\r\n";
		echo "    <bmdb:Title>".urlencode($b->getTitle())."</bmdb:Title>\r\n";
		echo "    <bmdb:ShortName>".urlencode($b->getShortName())."</bmdb:ShortName>\r\n";
		echo "    <bmdb:BiomarkerID>urn:edrn:bmdb:biomarker:{$biomarkerId}</bmdb:BiomarkerID>\r\n";
		echo "    <bmdb:URN>urn:edrn:bmdb:biomarker:{$biomarkerId}</bmdb:URN>\r\n";
		echo "    <bmdb:IsPanel>{$b->getIsPanel()}</bmdb:IsPanel>\r\n";
		echo "    <bmdb:PanelID>{$b->getPanelID()}</bmdb:PanelID>\r\n";
		echo "    <bmdb:Description>".urlencode($b->getDescription())."</bmdb:Description>\r\n";
		echo "    <bmdb:QAState>{$b->getQAState()}</bmdb:QAState>\r\n";
		echo "    <bmdb:Phase>{$b->getPhase()}</bmdb:Phase>\r\n";
		echo "    <bmdb:Security>{$b->getSecurity()}</bmdb:Security>\r\n";
		echo "    <bmdb:Type>{$b->getType()}</bmdb:Type>\r\n";
		
		// Organs
		if (count($b->getOrganDatas()) > 0) {
			echo "    <bmdb:BiomarkerOrganDatas>\r\n";
			foreach ($b->getOrganDatas() as $bod) {
				echo "      <bmdb:BiomarkerOrganData rdf:about=\"http://bmdb.jpl.nasa.gov/edit/biomarkerorgan/?id={$bod->getObjId()}\"/>\r\n";
			}
			echo "    </bmdb:BiomarkerOrganDatas>\r\n";
		} else {
			echo "    <bmdb:BiomarkerOrganDatas/>\r\n";
		}
	
		// Studies
		if (count($b->getStudies()) > 0) {
			echo "    <bmdb:Studies>\r\n";
			foreach ($b->getStudies() as $study) {
				echo "      <bmdb:Study rdf:about=\"http://bmdb.jpl.nasa.gov/edit/study/?id={$study->getObjId()}\"/>\r\n";
			}
			echo "    </bmdb:Studies>\r\n";
		} else {
			echo "    <bmdb:Studies/>\r\n";
		}
		
		// Publications
		if (count($b->getPublications()) > 0) {
			echo "    <bmdb:Publications>\r\n";
			foreach ($b->getPublications() as $pub) {
				echo "      <bmdb:Publication rdf:about=\"http://bmdb.jpl.nasa.gov/goto/publication/?id={$pub->getObjId()}\"/>\r\n";
			}
			echo "    </bmdb:Publications>\r\n";
		} else {
			echo "    <bmdb:Publications/>\r\n";
		}
	
		// Resources
		if (count($b->getResources()) > 0) {
			echo "    <bmdb:Resources>\r\n";
			foreach ($b->getResources() as $res) {
				echo "      <bmdb:Resource rdf:about=\"{$res->getURL()}\"/>\r\n";
			}
			echo "    </bmdb:Resources>\r\n";
		} else {
			echo "    <bmdb:Resources/>\r\n";
		}
		echo "  </bmdb:Biomarker>\r\n";
	} /* end foreach */
	echo "</rdf:RDF>\r\n";
?>