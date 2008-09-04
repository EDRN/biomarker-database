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
			'Rdf',
			'Pi',
			'Site',
			'Resource',
	);
	
	function index() {
		die("Please add one of <ul>"
			."<li><a href=\"/".PROJROOT."/rdf/biomarkers\">biomarkers</a></li>"
			."<li><a href=\"/".PROJROOT."/rdf/biomarkerorgans\">biomarkerorgans</a></li>"
			."<li><a href=\"/".PROJROOT."/rdf/studies\">studies</a></li>"
			."<li><a href=\"/".PROJROOT."/rdf/publications\">publications</a></li>"
			."<li><a href=\"/".PROJROOT."/rdf/pis\">principal investigators</a></li>"
			."<li><a href=\"/".PROJROOT."/rdf/sites\">sites</a></li>"
			."<li><a href=\"/".PROJROOT."/rdf/resources\">resources</a></li>"
			."</ul> to your query");
	}
	
	function biomarkers() {
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
				foreach ($b['OrganData'] as $bod) {
					echo "    <bmdb:indicatorForOrgan rdf:resource=\"http://cancer.jpl.nasa.gov/bmdb/biomarkers/organs/{$b['Biomarker']['id']}/{$bod['id']}\"/>\r\n";
				}
			}
			// Studies
			if (count($b['BiomarkerStudyData']) > 0) {
				foreach ($b['BiomarkerStudyData'] as $study) {
					echo "    <bmdb:referencedInStudy rdf:resource=\"http://cancer.jpl.nasa.gov/bmdb/studies/view/{$study['id']}\"/>\r\n";
				}
			} 
			
			// Publications
			if (count($b['Publication']) > 0) {
				foreach ($b['Publication'] as $pub) {
					echo "    <bmdb:referencedInPublication rdf:resource=\"http://cancer.jpl.nasa.gov/bmdb/publications/view/{$pub['id']}\"/>\r\n";
				}
			} 
		
			// Resources
			if (count($b['BiomarkerResource']) > 0) {
				foreach ($b['BiomarkerResource'] as $res) {
					echo "    <bmdb:hasExternalResource rdf:resource=\"http://{$res['URL']}\"/>\r\n";
				}
			} 
			echo "  </bmdb:Biomarker>\r\n";
		} /* end foreach */

		$this->printRdfEnd();
		exit();
	}
	
	function biomarkerorgans() {
		header("content-type:application/rdf+xml; charset=utf-8");
		$this->printRdfStart();
		$biomarkerorgandatas = $this->OrganData->findAll(null,null,null,null,1,2);
		foreach ($biomarkerorgandatas as $bod) {
			
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
				foreach ($bod['StudyData'] as $studyData) {
					echo "    <bmdb:hasBiomarkerOrganStudyDatas>\r\n";
					echo "        <bmdb:BiomarkerOrganStudyData rdf:about=\"".$this->escapeEntities("{$aboutURL}#{$studyData['id']}")."\">\r\n";
					echo "          <bmdb:referencesStudy rdf:resource=\"http://cancer.jpl.nasa.gov/bmdb/studies/view/{$studyData['Study']['id']}\"/>\r\n";
					echo "          <bmdb:Sensitivity>{$studyData['sensitivity']}</bmdb:Sensitivity>\r\n";
					echo "          <bmdb:Specificity>{$studyData['specificity']}</bmdb:Specificity>\r\n";
					echo "          <bmdb:NPV>{$studyData['npv']}</bmdb:NPV>\r\n";
					echo "          <bmdb:PPV>{$studyData['ppv']}</bmdb:PPV>\r\n";
					
					// Publications
					if (count($studyData['Publication']) > 0) {
						foreach ($studyData['Publication'] as $pub) {
							echo "          <bmdb:referencesPublication rdf:resource=\"http://cancer.jpl.nasa.gov/bmdb/publications/view/{$pub['id']}\"/>\r\n";
						}
					}
					
					// Resources
					if (count($studyData['StudyDataResource']) > 0) {
						foreach ($studyData['StudyDataResource'] as $res) {
							echo "          <bmdb:referencesResource rdf:resource=\"http://{$res['URL']}\"/>\r\n";
						}
					} 
					echo "        </bmdb:BiomarkerOrganStudyData>\r\n";
					echo "    </bmdb:hasBiomarkerOrganStudyDatas>\r\n";
				}
			} else {
				echo "    <bmdb:hasBiomarkerOrganStudyDatas/>\r\n";
			}
			
			// Publications
			if (count($bod['Publication']) > 0) {
				foreach ($bod['Publication'] as $pub) {
					echo "    <bmdb:referencedInPublication rdf:resource=\"http://cancer.jpl.nasa.gov/bmdb/publications/view/{$pub['id']}\"/>\r\n";
				}
			} 
			
			// Resources
			if (count($bod['OrganDataResource']) > 0) {
				foreach ($bod['OrganDataResource'] as $res) {
					echo "    <bmdb:hasExternalResource rdf:resource=\"http://{$res['URL']}\"/>\r\n";
				}
			} 
		
			echo "  </bmdb:BiomarkerOrganData>\r\n";
		} /* end foreach */
		$this->printRdfEnd();
		exit();
	}
	
	function studies() {
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
			echo "    <bmdb:StudyObjective>".$this->escapeEntities($s['Study']['studyObjective'])."</bmdb:StudyObjective>\r\n";
			echo "    <bmdb:StudySpecificAims>".$this->escapeEntities($s['Study']['studySpecificAims'])."</bmdb:StudySpecificAims>\r\n";
			echo "    <bmdb:StudyResultsOutcome>".$this->escapeEntities($s['Study']['studyResultsOutcome'])."</bmdb:StudyResultsOutcome>\r\n";
			echo "    <bmdb:BiomarkerPopulationCharacteristics>{$s['Study']['bioPopChar']}</bmdb:BiomarkerPopulationCharacteristics>\r\n";
			echo "    <bmdb:BPCDescription>".$this->escapeEntities($s['Study']['BPCDescription'])."</bmdb:BPCDescription>\r\n";
			echo "    <bmdb:Design>{$s['Study']['design']}</bmdb:Design>\r\n";
			echo "    <bmdb:DesignDescription>".$this->escapeEntities($s['Study']['designDescription'])."</bmdb:DesignDescription>\r\n";
			echo "    <bmdb:BiomarkerStudyType>{$s['Study']['biomarkerStudyType']}</bmdb:BiomarkerStudyType>\r\n";
			
			// Publications
			if (count($s['Publication']) > 0) {
				foreach ($s['Publication'] as $pub) {
					echo "    <bmdb:isDescribedIn rdf:resource=\"http://cancer.jpl.nasa.gov/bmdb/publications/view/{$pub['id']}\"/>\r\n";
				}
			} 
			
			// Resources
			if (count($s['StudyResource']) > 0) {
				foreach ($s['StudyResource'] as $res) {
					echo "    <bmdb:externalResource rdf:resource=\"http://{$res['URL']}\"/>\r\n";
				}
			} 
			// Sites 
			$sites = $this->Study->getSitesFor($s['Study']['id']);
			if (count($sites) > 0) {
				foreach ($sites as $site) {
					$site_id = $site['sites_studies']['site_id'];
					echo "    <bmdb:participatingSite rdf:resource=\"http://cancer.jpl.nasa.gov/bmdb/rdf/sites/{$site_id}\"/>\r\n";
				}
			}
			echo "  </bmdb:Study>\r\n";
		}/* end foreach */
		$this->printRdfEnd();
		exit();
	}

	function publications() {
		header("content-type:application/rdf+xml; charset=utf-8");
		$this->printRdfStart();
		$publications = $this->Publication->findAll();
		foreach ($publications as $p) {
			$aboutURL = "http://cancer.jpl.nasa.gov/bmdb/publications/view/{$p['Publication']['id']}";
	
			// Basics
			echo "  <bmdb:Publication rdf:about=\"{$aboutURL}\">\r\n";
			echo "    <bmdb:Title>".$this->escapeEntities($p['Publication']['title'])."</bmdb:Title>\r\n";
			echo "    <bmdb:Author>{$p['Publication']['author']}</bmdb:Author>\r\n";
			echo "    <bmdb:Journal>{$p['Publication']['journal']}</bmdb:Journal>\r\n";
			echo "    <bmdb:Published>{$p['Publication']['published']}</bmdb:Published>\r\n";
			echo "    <bmdb:PubMedId>{$p['Publication']['pubmed_id']}</bmdb:PubMedId>\r\n";
			echo "  </bmdb:Publication>\r\n";
		}/* end foreach */
		$this->printRdfEnd();
		exit();
	}

	function resources() {
		header("content-type:application/rdf+xml; charset=utf-8");
		$this->printRdfStart();
		$results = $this->Resource->getemall();
		
		foreach ($results as $url=>$desc) {
			echo "  <bmdb:ExternalResource rdf:about=\"http://{$url}\">\r\n";
			echo "    <bmdb:URI>http://{$url}</bmdb:URI>\r\n";
			echo "    <bmdb:Description>{$desc}</bmdb:Description>\r\n";
			echo "  </bmdb:ExternalResource>\r\n";
		}
		$this->printRdfEnd();
		exit();
	}
	
	function pis() {
		header("content-type:application/rdf+xml; charset=utf-8");
		$this->printRdfStart();
		$results = $this->Pi->getemall();

		foreach ($results as $p) {
			$aboutURL = "http://cancer.jpl.nasa.gov/bmdb/rdf/pis/{$p['pis']['site_id']}";
	
			// Basics
			echo "  <bmdb:PI rdf:about=\"{$aboutURL}\">\r\n";
			echo "    <bmdb:Name>".$this->escapeEntities($p['pis']['name'])."</bmdb:Name>\r\n";
			echo "    <bmdb:Title>{$p['pis']['title']}</bmdb:Title>\r\n";
			echo "    <bmdb:Specialty>{$p['pis']['specialty']}</bmdb:Specialty>\r\n";
			echo "    <bmdb:AddressLine1>{$p['pis']['addressLine1']}</bmdb:AddressLine1>\r\n";
			echo "    <bmdb:AddressLine2>{$p['pis']['addressLine2']}</bmdb:AddressLine2>\r\n";
			echo "    <bmdb:AddressLine3>{$p['pis']['addressLine3']}</bmdb:AddressLine3>\r\n";
			echo "    <bmdb:Telephone>{$p['pis']['telephone']}</bmdb:Telephone>\r\n";
			echo "    <bmdb:Fax>{$p['pis']['fax']}</bmdb:Fax>\r\n";
			echo "    <bmdb:Email>{$p['pis']['email']}</bmdb:Email>\r\n";
			echo "    <bmdb:belongsToSite rdf:resource=\"http://cancer.jpl.nasa.gov/bmdb/rdf/sites/{$p['pis']['site_id']}\"/>\r\n";
			echo "  </bmdb:PI>\r\n";
		}/* end foreach */
		$this->printRdfEnd();
		exit();
	}
	
	function sites() {
		header("content-type:application/rdf+xml; charset=utf-8");
		$this->printRdfStart();
		$results = $this->Site->getemall();

		foreach ($results as $s) {
			$aboutURL = "http://cancer.jpl.nasa.gov/bmdb/rdf/sites/{$s['sites']['site_id']}";
	
			// Basics
			echo "  <bmdb:Site rdf:about=\"{$aboutURL}\">\r\n";
			echo "    <bmdb:Name>".$this->escapeEntities($s['sites']['name'])."</bmdb:Name>\r\n";
			echo "    <bmdb:SiteId>{$s['sites']['site_id']}</bmdb:SiteId>\r\n";
			echo "    <bmdb:hasPrincipalInvestigator rdf:resource=\"http://cancer.jpl.nasa.gov/bmdb/pis/{$s['sites']['site_id']}\"/>\r\n";
			echo "  </bmdb:Site>\r\n";
		}/* end foreach */
		$this->printRdfEnd();
		exit();
	}
	private function escapeEntities($str) {
		
		/* Multiple characters */
		$source = str_replace("ďż˝", "&quot;",  $str);
		$source = str_replace("â„˘", "&trade;", $source);
		$source = str_replace("\'",  "&apos;",  $source);
		$source = str_replace("â€™", "&apos",   $source);
		
		
		/* Single characters */
		$source = str_replace("’", "&apos;",   $source);
		$source = str_replace("´", "&apos;",   $source);
		$source = str_replace("‘", "&apos;",   $source);
		$source = str_replace("'", "&apos;",   $source);
		$source = str_replace("™", "&trade;",  $source);
		$source = str_replace("—", "&minus;",  $source);
		$source = str_replace("–", "&minus;",  $source);
		$source = str_replace("“", "&quot;",   $source);
		$source = str_replace("”", "&quot;",   $source);
		$source = str_replace("®", "&reg;",    $source);
		$source = str_replace("†", "",  $source);
		$source = str_replace("·", "-", $source);
		$source = str_replace("•", "-", $source);
		
		
		$source = str_replace("<",  "&lt;",   $source);
		$source = str_replace(">",  "&gt;",   $source);
		$source = str_replace("\"", "&quot;", $source);
		$source = str_replace("'"," &apos;",  $source);
		$source = str_replace("&"," &amp;",   $source);
		
		return $source;
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