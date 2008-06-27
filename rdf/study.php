<?php
	require_once("../xpress/app.php");
	header("content-type:application/rdf+xml; charset=utf-8");
	
	function escapeAmpersand($str) {
		return str_replace("&","&amp;",$str);
	}

echo <<<END
<?xml version='1.0' encoding='UTF-8'?>
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:bmdb="http://edrn.nci.nih.gov/rdf/rdfs/bmdb-1.0.0#">

END;
	
	$q = "SELECT `objId` "
		."FROM `Study` ";
	$ids = $xpress->db()->getAll($q);
	foreach ($ids as $id) {
		$s = StudyFactory::Retrieve($id['objId']);
		$aboutURL = "http://bmdb.jpl.nasa.gov/edit/study/?id={$s->getObjId()}";
	
		// Basics
		echo "  <bmdb:Study rdf:about=\"{$aboutURL}\">\r\n";
		echo "    <bmdb:Title>".escapeAmpersand($s->getTitle())."</bmdb:Title>\r\n";
		echo "    <bmdb:URN>urn:edrn:bmdb:study:{$s->getObjId()}</bmdb:URN>\r\n";
		echo "    <bmdb:FHCRC_ID>{$s->getFHCRCID()}</bmdb:FHCRC_ID>\r\n";
		echo "    <bmdb:DMCC_ID>{$s->getDMCCID()}</bmdb:DMCC_ID>\r\n";
		echo "    <bmdb:StudyAbstract>".escapeAmpersand($s->getStudyAbstract())."</bmdb:StudyAbstract>\r\n";
		echo "    <bmdb:BiomarkerPopulationCharacteristics>{$s->getBiomarkerPopulationCharacteristics()}</bmdb:BiomarkerPopulationCharacteristics>\r\n";
		echo "    <bmdb:BPCDescription>".escapeAmpersand($s->getBPCDescription())."</bmdb:BPCDescription>\r\n";
		echo "    <bmdb:Design>{$s->getDesign()}</bmdb:Design>\r\n";
		echo "    <bmdb:DesignDescription>".escapeAmpersand($s->getDesignDescription())."</bmdb:DesignDescription>\r\n";
		echo "    <bmdb:BiomarkerStudyType>{$s->getBiomarkerStudyType()}</bmdb:BiomarkerStudyType>\r\n";
		
		
		// Biomarkers
		if (count($s->getBiomarkers()) > 0) {
			echo "    <bmdb:Biomarkers>\r\n";
			foreach ($s->getBiomarkers() as $biomarker) {
				echo "      <bmdb:Biomarker rdf:about=\"http://bmdb.jpl.nasa.gov/edit/biomarker/?id={$biomarker->getObjId()}\"/>\r\n";
			}
			echo "    </bmdb:Biomarkers>\r\n";
		} else {
			echo "    <bmdb:Biomarkers/>\r\n";
		}
		// Biomarker Organ Datas
		if (count($s->getBiomarkerOrganDatas()) > 0) {
			echo "    <bmdb:BiomarkerOrganDatas>\r\n";
			foreach ($s->getBiomarkerOrganDatas() as $bod) {
				echo "      <bmdb:BiomarkerOrganData rdf:about=\"http://bmdb.jpl.nasa.gov/edit/biomarkerorgan/?id={$bod->getObjId()}\"/>\r\n";
			}
			echo "    </bmdb:BiomarkerOrganDatas>\r\n";
		} else {
			echo "    <bmdb:BiomarkerOrganDatas/>\r\n";
		}
		
		// Publications
		if (count($s->getPublications()) > 0) {
			echo "    <bmdb:Publications>\r\n";
			foreach ($s->getPublications() as $pub) {
				echo "      <bmdb:Publication rdf:about=\"http://bmdb.jpl.nasa.gov/goto/publication/?id={$pub->getObjId()}\"/>\r\n";
			}
			echo "    </bmdb:Publications>\r\n";
		} else {
			echo "    <bmdb:Publications/>\r\n";
		}
		
		// Resources
		if (count($s->getResources()) > 0) {
			echo "    <bmdb:Resources>\r\n";
			foreach ($s->getResources() as $res) {
				echo "      <bmdb:Resource rdf:about=\"{$res->getURL()}\"/>\r\n";
			}
			echo "    </bmdb:Resources>\r\n";
		} else {
			echo "    <bmdb:Resources/>\r\n";
		}
	
		echo "  </bmdb:Study>\r\n";
	}/* end foreach */
	echo "</rdf:RDF>\r\n";
?>