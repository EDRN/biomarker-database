<?php
	require_once("../xpress/app.php");
	header("content-type:application/rdf+xml; charset=utf-8");

echo <<<END
<?xml version='1.0' encoding='UTF-8'?>
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:bmdb="http://edrn.nci.nih.gov/rdf/rdfs/bmdb-1.0.0#">

END;
	
	
	$q = "SELECT `objId` "
		."FROM `BiomarkerOrganData` ";
	$ids = $xpress->db()->getAll($q);
	
	foreach ($ids as $id) {
		$bod = BiomarkerOrganDataFactory::Retrieve($id['objId']);
		$aboutURL = "http://bmdb.jpl.nasa.gov/edit/biomarkerorgan/?id={$bod->getObjId()}";
		
		// Basics
		echo "  <bmdb:BiomarkerOrganData rdf:about=\"{$aboutURL}\">\r\n";
		echo "    <bmdb:URN>urn:edrn:bmdb:biomarkerorgan:{$bod->getObjId()}</bmdb:URN>\r\n";
		echo "    <bmdb:Biomarker rdf:about=\"http://bmdb.jpl.nasa.gov/edit/biomarker/?id={$bod->getBiomarker()->getObjId()}\"/>\r\n";
		echo "    <bmdb:Organ>{$bod->getOrgan()->getName()}</bmdb:Organ>\r\n";
		echo "    <bmdb:SensitivityMin>{$bod->getSensitivityMin()}</bmdb:SensitivityMin>\r\n";
		echo "    <bmdb:SensitivityMax>{$bod->getSensitivityMax()}</bmdb:SensitivityMax>\r\n";
		echo "    <bmdb:SensitivityComment>".urlencode($bod->getSensitivityComment())."</bmdb:SensitivityComment>\r\n";
		echo "    <bmdb:SpecificityMin>{$bod->getSpecificityMin()}</bmdb:SpecificityMin>\r\n";
		echo "    <bmdb:SpecificityMax>{$bod->getSpecificityMax()}</bmdb:SpecificityMax>\r\n";
		echo "    <bmdb:SpecificityComment>".urlencode($bod->getSpecificityComment())."</bmdb:SpecificityComment>\r\n";
		echo "    <bmdb:NPVMin>{$bod->getNPVMin()}</bmdb:NPVMin>\r\n";
		echo "    <bmdb:NPVMax>{$bod->getNPVMax()}</bmdb:NPVMax>\r\n";
		echo "    <bmdb:NPVComment>".urlencode($bod->getNPVComment())."</bmdb:NPVComment>\r\n";
		echo "    <bmdb:PPVMin>{$bod->getPPVMin()}</bmdb:PPVMin>\r\n";
		echo "    <bmdb:PPVMax>{$bod->getPPVMax()}</bmdb:PPVMax>\r\n";
		echo "    <bmdb:PPVComment>".urlencode($bod->getPPVComment())."</bmdb:PPVComment>\r\n";
		echo "    <bmdb:Phase>{$bod->getPhase()}</bmdb:Phase>\r\n";
		echo "    <bmdb:QAState>{$bod->getQAState()}</bmdb:QAState>\r\n";
	
	
		// Studies
		if (count($bod->getStudyDatas()) > 0) {
			echo "    <bmdb:BiomarkerOrganStudyDatas>\r\n";
			foreach ($bod->getStudyDatas() as $studyData) {
				echo "      <bmdb:BiomarkerOrganStudyData rdf:about=\"".urlencode("http://bmdb.jpl.nasa.gov/edit/biomarkerorgan/?view=studies&id=23#{$studyData->getObjId()}")."\">\r\n";
				echo "        <bmdb:Study rdf:about=\"http://bmdb.jpl.nasa.gov/edit/study/?id={$studyData->getStudy()->getObjId()}\"/>\r\n";
				echo "        <bmdb:Sensitivity>{$studyData->getSensitivity()}</bmdb:Sensitivity>\r\n";
				echo "        <bmdb:Specificity>{$studyData->getSpecificity()}</bmdb:Specificity>\r\n";
				echo "        <bmdb:NPV>{$studyData->getNPV()}</bmdb:NPV>\r\n";
				echo "        <bmdb:PPV>{$studyData->getPPV()}</bmdb:PPV>\r\n";
				
				// Publications
				if (count($studyData->getPublications()) > 0) {
					echo "        <bmdb:Publications>\r\n";
					foreach ($studyData->getPublications() as $pub) {
						echo "          <bmdb:Publication rdf:about=\"http://bmdb.jpl.nasa.gov/goto/publication/?id={$pub->getObjId()}\"/>\r\n";
					}
					echo "        </bmdb:Publications>\r\n";
				} else {
					echo "        <bmdb:Publications/>\r\n";
				}
				
				// Resources
				if (count($studyData->getResources()) > 0) {
					echo "        <bmdb:Resources>\r\n";
					foreach ($studyData->getResources() as $res) {
						echo "          <bmdb:Resource rdf:about=\"{$res->getURL()}\"/>\r\n";
					}
					echo "        </bmdb:Resources>\r\n";
				} else {
					echo "        <bmdb:Resources/>\r\n";
				}
				echo "      </bmdb:BiomarkerOrganStudyData>\r\n";
			}
			echo "    </bmdb:BiomarkerOrganStudyDatas>\r\n";
		} else {
			echo "    <bmdb:BiomarkerOrganStudyDatas/>\r\n";
		}
		
		// Publications
		if (count($bod->getPublications()) > 0) {
			echo "    <bmdb:Publications>\r\n";
			foreach ($bod->getPublications() as $pub) {
				echo "      <bmdb:Publication rdf:about=\"http://bmdb.jpl.nasa.gov/goto/publication/?id={$pub->getObjId()}\"/>\r\n";
			}
			echo "    </bmdb:Publications>\r\n";
		} else {
			echo "    <bmdb:Publications/>\r\n";
		}
		
		// Resources
		if (count($bod->getResources()) > 0) {
			echo "    <bmdb:Resources>\r\n";
			foreach ($bod->getResources() as $res) {
				echo "      <bmdb:Resource rdf:about=\"{$res->getURL()}\"/>\r\n";
			}
			echo "    </bmdb:Resources>\r\n";
		} else {
			echo "    <bmdb:Resources/>\r\n";
		}
	
		echo "  </bmdb:BiomarkerOrganData>\r\n";
	} /* end foreach */
	echo "</rdf:RDF>\r\n";
?>