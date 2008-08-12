<?php
class RdfController extends AppController {

	var $name = 'Rdf';
	var $uses = array(
			'Biomarker',
			'Organ',
			'OrganData',
			'Study',
			'StudyData',
			'BiomarkerStudyData',
			'Publication',
			'StudyDataResource',
			'BiomarkerStudyDataResource',
			'OrganDataResource',
			'BiomarkerResource',
			'Rdf'
	);
	
	function index() {
		$this->checkSession("/rdf");
		die("Please add one of <ul>"
			."<li><a href=\"/".PROJROOT."/rdf/biomarkers\">biomarkers</a></li>"
			."<li><a href=\"/".PROJROOT."/rdf/biomarkerorgans\">biomarkerorgans</a></li>"
			."<li><a href=\"/".PROJROOT."/rdf/studies\">studies</a></li>"
			."</ul> to your query");
	}
	
	function biomarkers() {
		$this->checkSession("/rdf/biomarkers");
		header("content-type:application/rdf+xml; charset=utf-8");
		
		$this->printRdfStart();

		$biomarkers = $this->Biomarker->findAll();
		foreach ($biomarkers as $b) {
			$aboutURL = "http://cancer.jpl.nasa.gov/bmdb/biomarkers/view/{$b['Biomarker']['id']}";
			// Basics
			echo "  <bmdb:Biomarker rdf:about=\"{$aboutURL}\">\r\n";
			echo "    <bmdb:Title>".$this->escapeEntities($b['Biomarker']['name'])."</bmdb:Title>\r\n";
			echo "    <bmdb:ShortName>".$this->escapeEntities($b['Biomarker']['shortName'])."</bmdb:ShortName>\r\n";
			echo "    <bmdb:BiomarkerID>urn:edrn:bmdb:biomarker:{$b['Biomarker']['id']}</bmdb:BiomarkerID>\r\n";
			echo "    <bmdb:URN>urn:edrn:bmdb:biomarker:{$b['Biomarker']['id']}</bmdb:URN>\r\n";
			echo "    <bmdb:IsPanel>{$b['Biomarker']['isPanel']}</bmdb:IsPanel>\r\n";
			echo "    <bmdb:PanelID>{$b['Biomarker']['panelID']}</bmdb:PanelID>\r\n";
			echo "    <bmdb:Description>".$this->escapeEntities($b['Biomarker']['description'])."</bmdb:Description>\r\n";
			echo "    <bmdb:QAState>{$b['Biomarker']['qastate']}</bmdb:QAState>\r\n";
			echo "    <bmdb:Phase>{$b['Biomarker']['phase']}</bmdb:Phase>\r\n";
			echo "    <bmdb:Security>{$b['Biomarker']['security']}</bmdb:Security>\r\n";
			echo "    <bmdb:Type>{$b['Biomarker']['type']}</bmdb:Type>\r\n";
			// Organs
			if (count($b['OrganData']) > 0) {
				echo "    <bmdb:BiomarkerOrganDatas>\r\n";
				foreach ($b['OrganData'] as $bod) {
					echo "      <bmdb:BiomarkerOrganData rdf:resource=\"http://cancer.jpl.nasa.gov/bmdb/biomarkers/organs/{$b['Biomarker']['id']}/{$bod['id']}\"/>\r\n";
				}
				echo "    </bmdb:BiomarkerOrganDatas>\r\n";
			} else {
				echo "    <bmdb:BiomarkerOrganDatas/>\r\n";
			}
			// Studies
			if (count($b['BiomarkerStudyData']) > 0) {
				echo "    <bmdb:Studies>\r\n";
				foreach ($b['BiomarkerStudyData'] as $study) {
					echo "      <bmdb:Study rdf:resource=\"http://cancer.jpl.nasa.gov/bmdb/biomarkers/studies/{$b['Biomarker']['id']}#{$study['id']}\"/>\r\n";
				}
				echo "    </bmdb:Studies>\r\n";
			} else {
				echo "    <bmdb:Studies/>\r\n";
			}
			
			// Publications
			if (count($b['Publication']) > 0) {
				echo "    <bmdb:Publications>\r\n";
				foreach ($b['Publication'] as $pub) {
					echo "      <bmdb:Publication rdf:resource=\"http://cancer.jpl.nasa.gov/bmdb/publications/view/{$pub['id']}\"/>\r\n";
				}
				echo "    </bmdb:Publications>\r\n";
			} else {
				echo "    <bmdb:Publications/>\r\n";
			}
		
			// Resources
			if (count($b['BiomarkerResource']) > 0) {
				echo "    <bmdb:Resources>\r\n";
				foreach ($b['BiomarkerResource'] as $res) {
					echo "      <bmdb:Resource rdf:resource=\"http://{$res['URL']}\"/>\r\n";
				}
				echo "    </bmdb:Resources>\r\n";
			} else {
				echo "    <bmdb:Resources/>\r\n";
			}
			echo "  </bmdb:Biomarker>\r\n";
		} /* end foreach */

		$this->printRdfEnd();
		exit();
	}
	
	function biomarkerorgans() {
		$this->checkSession("/rdf/biomarkerorgans");
		header("content-type:application/rdf+xml; charset=utf-8");
		$this->printRdfStart();
		$biomarkerorgandatas = $this->OrganData->findAll();
		foreach ($biomarkerorgandatas as $b) {
			$bod = $this->OrganData->find('first',
				array(
					'conditions' => array('Biomarker.id' => $b['OrganData']['id']),
					'recursive'  => 2
				)
			);
			$aboutURL = "http://cancer.jpl.nasa.gov/bmdb/biomarkers/organs/{$bod['Biomarker']['id']}/{$bod['OrganData']['id']}";
			
			// Basics
			echo "  <bmdb:BiomarkerOrganData rdf:about=\"{$aboutURL}\">\r\n";
			echo "    <bmdb:URN>urn:edrn:bmdb:biomarkerorgan:{$bod['OrganData']['id']}</bmdb:URN>\r\n";
			echo "    <bmdb:Biomarker rdf:resource=\"http://cancer.jpl.nasa.gov/bmdb/biomarkers/view/{$bod['Biomarker']['id']}\"/>\r\n";
			echo "    <bmdb:Organ>{$bod['Organ']['name']}</bmdb:Organ>\r\n";
			echo "    <bmdb:SensitivityMin>{$bod['OrganData']['sensitivity_min']}</bmdb:SensitivityMin>\r\n";
			echo "    <bmdb:SensitivityMax>{$bod['OrganData']['sensitivity_max']}</bmdb:SensitivityMax>\r\n";
			echo "    <bmdb:SensitivityComment>".$this->escapeEntities($bod['OrganData']['sensitivity_comment'])."</bmdb:SensitivityComment>\r\n";
			echo "    <bmdb:SpecificityMin>{$bod['OrganData']['specificity_min']}</bmdb:SpecificityMin>\r\n";
			echo "    <bmdb:SpecificityMax>{$bod['OrganData']['specificity_max']}</bmdb:SpecificityMax>\r\n";
			echo "    <bmdb:SpecificityComment>".$this->escapeEntities($bod['OrganData']['specificity_comment'])."</bmdb:SpecificityComment>\r\n";
			echo "    <bmdb:NPVMin>{$bod['OrganData']['npv_min']}</bmdb:NPVMin>\r\n";
			echo "    <bmdb:NPVMax>{$bod['OrganData']['npv_max']}</bmdb:NPVMax>\r\n";
			echo "    <bmdb:NPVComment>".$this->escapeEntities($bod['OrganData']['npv_comment'])."</bmdb:NPVComment>\r\n";
			echo "    <bmdb:PPVMin>{$bod['OrganData']['ppv_min']}</bmdb:PPVMin>\r\n";
			echo "    <bmdb:PPVMax>{$bod['OrganData']['ppv_max']}</bmdb:PPVMax>\r\n";
			echo "    <bmdb:PPVComment>".$this->escapeEntities($bod['OrganData']['ppv_comment'])."</bmdb:PPVComment>\r\n";
			echo "    <bmdb:Phase>{$bod['OrganData']['phase']}</bmdb:Phase>\r\n";
			echo "    <bmdb:QAState>{$bod['OrganData']['qastate']}</bmdb:QAState>\r\n";
		
		
			// Studies
			if (count($bod['StudyData']) > 0) {
				echo "    <bmdb:BiomarkerOrganStudyDatas>\r\n";
				foreach ($bod['StudyData'] as $studyData) {
					echo "      <bmdb:BiomarkerOrganStudyData rdf:resource=\"".$this->escapeEntities("{$aboutURL}#{$studyData['id']}")."\">\r\n";
					echo "        <bmdb:Study rdf:about=\"http://cancer.jpl.nasa.gov/bmdb/studies/view/{$studyData['Study']['id']}\"/>\r\n";
					echo "        <bmdb:Sensitivity>{$studyData['sensitivity']}</bmdb:Sensitivity>\r\n";
					echo "        <bmdb:Specificity>{$studyData['specificity']}</bmdb:Specificity>\r\n";
					echo "        <bmdb:NPV>{$studyData['npv']}</bmdb:NPV>\r\n";
					echo "        <bmdb:PPV>{$studyData['ppv']}</bmdb:PPV>\r\n";
					
					// Publications
					if (count($studyData['Publication']) > 0) {
						echo "        <bmdb:Publications>\r\n";
						foreach ($studyData['Publication'] as $pub) {
							echo "          <bmdb:Publication rdf:resource=\"http://cancer.jpl.nasa.gov/bmdb/publications/view/{$pub['id']}\"/>\r\n";
						}
						echo "        </bmdb:Publications>\r\n";
					} else {
						echo "        <bmdb:Publications/>\r\n";
					}
					
					// Resources
					if (count($studyData['StudyDataResource']) > 0) {
						echo "        <bmdb:Resources>\r\n";
						foreach ($studyData['StudyDataResource'] as $res) {
							echo "          <bmdb:Resource rdf:resource=\"{$res['URL']}\"/>\r\n";
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
			if (count($bod['Publication']) > 0) {
				echo "    <bmdb:Publications>\r\n";
				foreach ($bod['Publication'] as $pub) {
					echo "      <bmdb:Publication rdf:resource=\"http://cancer.jpl.nasa.gov/bmdb/publications/view/{$pub['id']}\"/>\r\n";
				}
				echo "    </bmdb:Publications>\r\n";
			} else {
				echo "    <bmdb:Publications/>\r\n";
			}
			
			// Resources
			if (count($bod['OrganDataResource']) > 0) {
				echo "    <bmdb:Resources>\r\n";
				foreach ($bod['OrganDataResource'] as $res) {
					echo "      <bmdb:Resource rdf:resource=\"{$res['URL']}\"/>\r\n";
				}
				echo "    </bmdb:Resources>\r\n";
			} else {
				echo "    <bmdb:Resources/>\r\n";
			}
		
			echo "  </bmdb:BiomarkerOrganData>\r\n";
		} /* end foreach */
		$this->printRdfEnd();
		exit();
	}

	function studies() {
		$this->checkSession("/rdf/studies");
		header("content-type:application/rdf+xml; charset=utf-8");
		$this->printRdfStart();
		$studies = $this->Study->findAll();
		foreach ($studies as $s) {
			$aboutURL = "http://cancer.jpl.nasa.gov/bmdb/studies/view/{$s['Study']['id']}";
	
			// Basics
			echo "  <bmdb:Study rdf:about=\"{$aboutURL}\">\r\n";
			echo "    <bmdb:Title>".$this->escapeEntities($s['Study']['title'])."</bmdb:Title>\r\n";
			echo "    <bmdb:URN>urn:edrn:bmdb:study:{$s['Study']['id']}</bmdb:URN>\r\n";
			echo "    <bmdb:FHCRC_ID>{$s['Study']['FHCRC_ID']}</bmdb:FHCRC_ID>\r\n";
			echo "    <bmdb:DMCC_ID>{$s['Study']['DMCC_ID']}</bmdb:DMCC_ID>\r\n";
			echo "    <bmdb:StudyAbstract>".$this->escapeEntities($s['Study']['studyAbstract'])."</bmdb:StudyAbstract>\r\n";
			echo "    <bmdb:BiomarkerPopulationCharacteristics>{$s['Study']['bioPopChar']}</bmdb:BiomarkerPopulationCharacteristics>\r\n";
			echo "    <bmdb:BPCDescription>".$this->escapeEntities($s['Study']['BPCDescription'])."</bmdb:BPCDescription>\r\n";
			echo "    <bmdb:Design>{$s['Study']['design']}</bmdb:Design>\r\n";
			echo "    <bmdb:DesignDescription>".$this->escapeEntities($s['Study']['designDescription'])."</bmdb:DesignDescription>\r\n";
			echo "    <bmdb:BiomarkerStudyType>{$s['Study']['biomarkerStudyType']}</bmdb:BiomarkerStudyType>\r\n";
			
			// Publications
			if (count($s['Publication']) > 0) {
				echo "    <bmdb:Publications>\r\n";
				foreach ($s['Publication'] as $pub) {
					echo "      <bmdb:Publication rdf:resource=\"http://cancer.jpl.nasa.gov/bmdb/publications/view/{$pub['id']}\"/>\r\n";
				}
				echo "    </bmdb:Publications>\r\n";
			} else {
				echo "    <bmdb:Publications/>\r\n";
			}
			
			// Resources
			if (count($s['StudyResource']) > 0) {
				echo "    <bmdb:Resources>\r\n";
				foreach ($s['StudyResource'] as $res) {
					echo "      <bmdb:Resource rdf:resource=\"{$res['url']}\"/>\r\n";
				}
				echo "    </bmdb:Resources>\r\n";
			} else {
				echo "    <bmdb:Resources/>\r\n";
			}
			echo "  </bmdb:Study>\r\n";
		}/* end foreach */
		$this->printRdfEnd();
		exit();
	}
	
	private function escapeEntities($str) {
		return str_replace("<","&lt;",
			str_replace(">","&gt;",
				str_replace("\"","&quot;",
					str_replace("'","&apos;",
						str_replace("&","&amp;",$str)
					)
				)
			)
		);
	}
	
	private function printRdfStart() {
		echo <<<END
<?xml version='1.0' encoding='UTF-8'?>
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:bmdb="http://edrn.nci.nih.gov/rdf/rdfs/bmdb-1.0.0#">

END;
	}
	
	private function printRdfEnd() {
		echo "</rdf:RDF>\r\n";
	}
}
?>