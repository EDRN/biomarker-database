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
			'Acl'
	);
	
	function index() {
		$host = $_SERVER['HTTP_HOST'];
		$root   = PROJROOT;
		echo <<<__END
<h2>BMDB::RDF</h2>
The EDRN Biomarker Database provides data exports in Resource Description Format (RDF). 
There are currently three flavors of RDF export supported:

<h3>RDF - Plone-3 (all)</h3>
<table style="border:solid 1px #ccc;font-size:80%;font-family:courier-" cellspacing="3" cellpadding="5" border="1">
  <tr><th style="text-align:left;">Biomarkers </th><td><a href="/{$root}/rdf/biomarkers">http://{$host}/{$root}/rdf/biomarkers</a></td></tr>
  <tr><th style="text-align:left;">Biomarker Organs </th><td><a href="/{$root}/rdf/biomarkerorgans">http://{$host}/{$root}/rdf/biomarkerorgans</a></td></tr>
  <tr><th style="text-align:left;">Publications </th><td><a href="/{$root}/rdf/publications">http://{$host}/{$root}/rdf/publications</a></td></tr>
  <tr><th style="text-align:left;">Principal Investigators </th><td><a href="/{$root}/rdf/pis">http://{$host}/{$root}/rdf/pis</a></td></tr>
  <tr><th style="text-align:left;">Sites </th><td><a href="/{$root}/rdf/sites">http://{$host}/{$root}/rdf/sites</a></td></tr>
  <tr><th style="text-align:left;">Resources </th><td><a href="/{$root}/rdf/resources">http://{$host}/{$root}/rdf/resources</a></td></tr>
</table>
  
<h3>RDF - Plone-2 (all)</h3>
<table style="border:solid 1px #ccc;font-size:80%;font-family:courier-" cellspacing="3" cellpadding="5" border="1">
  <tr><th style="text-align:left;">Biomarkers </th><td><a href="/{$root}/rdfp2/biomarkers">http://{$host}/{$root}/rdfp2/biomarkers</a></td></tr>
  <tr><th style="text-align:left;">Biomarker Organs </th><td><a href="/{$root}/rdfp2/biomarkerorgans">http://{$host}/{$root}/rdfp2/biomarkerorgans</a></td></tr>
  <tr><th style="text-align:left;">Publications </th><td><a href="/{$root}/rdfp2/publications">http://{$host}/{$root}/rdfp2/publications</a></td></tr>
  <tr><th style="text-align:left;">Principal Investigators </th><td><a href="/{$root}/rdfp2/pis">http://{$host}/{$root}/rdfp2/pis</a></td></tr>
  <tr><th style="text-align:left;">Sites </th><td><a href="/{$root}/rdfp2/sites">http://{$host}/{$root}/rdfp2/sites</a></td></tr>
  <tr><th style="text-align:left;">Resources </th><td><a href="/{$root}/rdfp2/resources">http://{$host}/{$root}/rdfp2/resources</a></td></tr>
</table>

<h3>RDF - Plone-2 (public only)</h3>
<table style="border:solid 1px #ccc;font-size:80%;font-family:courier-" cellspacing="3" cellpadding="5" border="1">
  <tr><th style="text-align:left;">Biomarkers </th><td><a href="/{$root}/rdfp2public/biomarkers">http://{$host}/{$root}/rdfp2public/biomarkers</a></td></tr>
  <tr><th style="text-align:left;">Biomarker Organs </th><td><a href="/{$root}/rdfp2public/biomarkerorgans">http://{$host}/{$root}/rdfp2public/biomarkerorgans</a></td></tr>
  <tr><th style="text-align:left;">Publications </th><td><a href="/{$root}/rdfp2public/publications">http://{$host}/{$root}/rdfp2public/publications</a></td></tr>
  <tr><th style="text-align:left;">Principal Investigators </th><td><a href="/{$root}/rdfp2public/pis">http://{$host}/{$root}/rdfp2public/pis</a></td></tr>
  <tr><th style="text-align:left;">Sites </th><td><a href="/{$root}/rdfp2public/sites">http://{$host}/{$root}/rdfp2public/sites</a></td></tr>
  <tr><th style="text-align:left;">Resources </th><td><a href="/{$root}/rdfp2public/resources">http://{$host}/{$root}/rdfp2public/resources</a></td></tr>
</table>
__END;
		die();
	}
	
	/*
	 * Function: getResourceBase
	 * This function determines the host name to use as the base for all rdf::resource URLs.
	 * Optionally, if $bIncludeProjRoot is true, the value of the constant PROJROOT is
	 * appended to the end.
	 * 
	 */
	private function getResourceBase($bIncludeProjRoot = true) {
		return strip_tags($_SERVER['HTTP_HOST']) . (($bIncludeProjRoot) 
				? "/".PROJROOT
				: ""
				);
	}
	
	function biomarkers() {
		header("content-type:application/rdf+xml; charset=utf-8");
		
		$this->printRdfStart();

		$biomarkers = $this->Biomarker->findAll(null,null,null,null,1,2);
		foreach ($biomarkers as $b) {
			$aboutURL = "http://{$this->getResourceBase()}/biomarkers/view/{$b['Biomarker']['id']}";
			$biomarkerName = Biomarker::getDefaultName($b);
			// Basics
			echo "  <bmdb:Biomarker rdf:about=\"{$aboutURL}\">\r\n";
			echo "    <bmdb:Title>".$this->escapeEntities($biomarkerName)."</bmdb:Title>\r\n";
			echo "    <bmdb:ShortName>".$this->escapeEntities($b['Biomarker']['shortName'])."</bmdb:ShortName>\r\n";
			echo "    <bmdb:BiomarkerID>urn:edrn:bmdb:biomarker:{$b['Biomarker']['id']}</bmdb:BiomarkerID>\r\n";
			echo "    <bmdb:URN>urn:edrn:bmdb:biomarker:{$b['Biomarker']['id']}</bmdb:URN>\r\n";
			echo "    <bmdb:IsPanel>{$b['Biomarker']['isPanel']}</bmdb:IsPanel>\r\n";
			//echo "    <bmdb:PanelID>{$b['Biomarker']['panelID']}</bmdb:PanelID>\r\n";
			echo "    <bmdb:Description>".$this->escapeEntities($b['Biomarker']['description'])."</bmdb:Description>\r\n";
			echo "    <bmdb:QAState>{$b['Biomarker']['qastate']}</bmdb:QAState>\r\n";
			echo "    <bmdb:Phase>{$b['Biomarker']['phase']}</bmdb:Phase>\r\n";
			echo "    <bmdb:Security>{$b['Biomarker']['security']}</bmdb:Security>\r\n";
			echo "    <bmdb:Type>{$b['Biomarker']['type']}</bmdb:Type>\r\n";
			// Alternative Names
			if (count($b['BiomarkerName']) > 0) {
				foreach ($b['BiomarkerName'] as $alias) {
					if ($alias['name'] != $biomarkerName) {
						echo "    <bmdb:Alias>".$this->escapeEntities($alias['name'])."</bmdb:Alias>\r\n";
					}
				}
			} 
			
			// Panel members
			if ($b['Biomarker']['isPanel']) {
				foreach ($b['Panel'] as $member) {
					echo "    <bmdb:hasBiomarker rdf:resource=\"http://{$this->getResourceBase()}/biomarkers/view/{$member['id']}\"/>\r\n";
				}
			}
			
			// Member of Panel(s)
			$members = $this->Biomarker->getPanelMembership($b['Biomarker']['id']);
			if (count($members) > 0) {
				foreach ($members as $m) {
					echo "    <bmdb:memberOfPanel rdf:resource=\"http://{$this->getResourceBase()}/biomarkers/view/{$m['id']}\"/>\r\n";
				}
			}
			
			// Panel Details
			// If the biomarker is a panel, show the members as a <bmdb:hasBiomarker>
			// If the biomarker belongs to a panel, show a <bmdb:belongsToPanel>

			// Access Control / Security
			// Display the LDAP groups that should have access to this data
			$groups = $this->Biomarker->readACL($b['Biomarker']['id']);
			foreach ($groups as $group) {
				echo "    <bmdb:AccessGrantedTo>{$group['acl']['ldapGroup']}</bmdb:AccessGrantedTo>\r\n";
			}
			
			// Organs
			if (count($b['OrganData']) > 0) {
				foreach ($b['OrganData'] as $bod) {
					echo "    <bmdb:indicatorForOrgan rdf:resource=\"http://{$this->getResourceBase()}/biomarkers/organs/{$b['Biomarker']['id']}/{$bod['id']}\"/>\r\n";
				}
			}
			// Studies
			if (count($b['BiomarkerStudyData']) > 0) {
				echo "    <bmdb:hasBiomarkerStudyDatas>\r\n";
				foreach ($b['BiomarkerStudyData'] as $studyData) {
					$aboutURL = "http://{$this->getResourceBase()}/biomarkers/studies/{$b['Biomarker']['id']}/{$studyData['id']}";
					echo "        <bmdb:BiomarkerStudyData rdf:about=\"".$this->escapeEntities("{$aboutURL}")."\">\r\n";
					echo "          <bmdb:referencesStudy rdf:resource=\"http://edrn.nci.nih.gov/data/protocols/{$studyData['Study']['FHCRC_ID']}\"/>\r\n";
					
					// Sensitivity/Specificity Information
					if (count($studyData['Sensitivity']) > 0) {
						echo "          <bmdb:SensitivityDatas>\r\n";
						foreach ($studyData['Sensitivity'] as $ordinal => $s) {
							$pv = $this->calculatePV($s['sensitivity'],$s['specificity'],$s['prevalence']);
							echo "            <bmdb:SensitivityData rdf:about=\"{$aboutURL}/sensitivity-data-{$ordinal}\">\r\n";
							echo "              <bmdb:SensSpecDetail>{$this->escapeEntities($s['notes'])}</bmdb:SensSpecDetail>\r\n";
							echo "              <bmdb:Sensitivity>{$s['sensitivity']}</bmdb:Sensitivity>\r\n";
							echo "              <bmdb:Specificity>{$s['specificity']}</bmdb:Specificity>\r\n";
							echo "              <bmdb:Prevalence>{$s['prevalence']}</bmdb:Prevalence>\r\n";
							echo "              <bmdb:NPV>{$pv['NPV']}</bmdb:NPV>\r\n";
							echo "              <bmdb:PPV>{$pv['PPV']}</bmdb:PPV>\r\n";
							echo "            </bmdb:SensitivityData>\r\n";
						}
						echo "          </bmdb:SensitivityDatas>\r\n";
					}
					echo "        </bmdb:BiomarkerStudyData>\r\n";
				}
				echo "    </bmdb:hasBiomarkerStudyDatas>\r\n";
				
			} 
			
			// Publications
			if (count($b['Publication']) > 0) {
				foreach ($b['Publication'] as $pub) {
					echo "    <bmdb:referencedInPublication rdf:resource=\"http://{$this->getResourceBase()}/publications/view/{$pub['id']}\"/>\r\n";
				}
			} 
		
			// Resources
			if (count($b['BiomarkerResource']) > 0) {
				foreach ($b['BiomarkerResource'] as $res) {
					echo "    <bmdb:referencesResource rdf:resource=\"{$this->escapeEntities($res['URL'])}\"/>\r\n";
				}
			} 
			echo "  </bmdb:Biomarker>\r\n";
		} /* end foreach */

		$this->printRdfEnd();
		exit();
	}
	
	private function handleOrganStudyData($bosd) {
		
	}
	
	private function sensitivities($data) {
		foreach ($data as $id => $s) {
			$pv = $this->calculatePV($s['sensitivity'],$s['specificity'],$s['prevalence']);
			echo "  <bmdb:SensitivityData rdf:about=\"{$id}\">\r\n";
			echo "    <bmdb:SensSpecDetail>{$this->escapeEntities($s['notes'])}</bmdb:SensSpecDetail>\r\n";
			echo "    <bmdb:Sensitivity>{$s['sensitivity']}</bmdb:Sensitivity>\r\n";
			echo "    <bmdb:Specificity>{$s['specificity']}</bmdb:Specificity>\r\n";
			echo "    <bmdb:Prevalence>{$s['prevalence']}</bmdb:Prevalence>\r\n";
			echo "    <bmdb:NPV>{$pv['NPV']}</bmdb:NPV>\r\n";
			echo "    <bmdb:PPV>{$pv['PPV']}</bmdb:PPV>\r\n";
			echo "  </bmdb:SensitivityData>\r\n";
		}
	}
	
	//
	// Generate RDF for the given BiomarkerOrganStudyData objects.
	// Params:
	// 	$data - a set of BiomarkerOrganStudyData objects
	//  $biomarkerOrganData - a reference to the containing BiomarkerOrganData object
	//  $sensitivities      - a reference to the list of sensitivityData objects
	//
	private function studydatas($data,&$biomarkerOrganData,&$sensitivities) {
		foreach ($data as $about_id => $studyData) {
			echo "  <bmdb:BiomarkerOrganStudyData rdf:about=\"{$about_id}\">\r\n";
			echo "    <bmdb:referencesStudy rdf:resource=\"http://edrn.nci.nih.gov/data/protocols/{$studyData['Study']['FHCRC_ID']}\"/>\r\n";
			
			// Decision Rule
			echo "    <bmdb:DecisionRule>{$this->escapeEntities($studyData['decision_rule'])}</bmdb:DecisionRule>\r\n";
			
			// Sensitivity/Specificity Information
			if (count($studyData['Sensitivity']) > 0) {
				echo "    <bmdb:SensitivityDatas>\r\n";
				echo "      <rdf:Bag>\r\n";
				foreach ($studyData['Sensitivity'] as $ordinal => $s) {
					$sens_id = "http://{$this->getResourceBase()}/biomarkers/organs/{$biomarkerOrganData['Biomarker']['id']}/{$biomarkerOrganData['OrganData']['id']}/sensitivity-data-{$ordinal}";
					echo "        <rdf:li rdf:resource=\"{$sens_id}\"/>\r\n";
					$sensitivities[$sens_id] = $s;
				}
				echo "      </rdf:Bag>\r\n";
				echo "    </bmdb:SensitivityDatas>\r\n";
			}
			// Publications
			if (count($studyData['Publication']) > 0) {
				foreach ($studyData['Publication'] as $pub) {
					echo "        <bmdb:referencedInPublication rdf:resource=\"http://{$this->getResourceBase()}/publications/view/{$pub['id']}\"/>\r\n";
				}
			}
			
			// Resources
			if (count($studyData['StudyDataResource']) > 0) {
				foreach ($studyData['StudyDataResource'] as $res) {
					echo "        <bmdb:referencesResource rdf:resource=\"{$this->escapeEntities($res['URL'])}\"/>\r\n";
				}
			} 
			echo "  </bmdb:BiomarkerOrganStudyData>\r\n";
		}
	}
	
	function biomarkerorgans() {
		header("content-type:application/rdf+xml; charset=utf-8");
		$this->printRdfStart();
		$biomarkerorgandatas = $this->OrganData->findAll(null,null,null,null,1,2);
		$sensitivities = array();
		foreach ($biomarkerorgandatas as $bod) {
			
			$aboutURL   = "http://{$this->getResourceBase()}/biomarkers/organs/{$bod['Biomarker']['id']}/{$bod['OrganData']['id']}";
			$studyDatas = array();
			
			// Basics
			echo "  <bmdb:BiomarkerOrganData rdf:about=\"{$aboutURL}\">\r\n";
			echo "    <bmdb:URN>urn:edrn:bmdb:biomarkerorgan:{$bod['OrganData']['id']}</bmdb:URN>\r\n";
			echo "    <bmdb:Biomarker rdf:resource=\"http://{$this->getResourceBase()}/biomarkers/view/{$bod['Biomarker']['id']}\"/>\r\n";
			echo "    <bmdb:Description>". $this->escapeEntities($bod['OrganData']['description']). "</bmdb:Description>\r\n";
			echo "    <bmdb:PerformanceComment>". $this->escapeEntities($bod['OrganData']['performance_comment']). "</bmdb:PerformanceComment>\r\n";
			echo "    <bmdb:Organ>{$bod['Organ']['name']}</bmdb:Organ>\r\n";
			echo "    <bmdb:Phase>{$bod['OrganData']['phase']}</bmdb:Phase>\r\n";
			echo "    <bmdb:QAState>{$bod['OrganData']['qastate']}</bmdb:QAState>\r\n";
			
			// Access Control / Security
			// Display the LDAP groups that should have access to this data
			$groups = $this->OrganData->readACL($bod['OrganData']['id']);
			foreach ($groups as $group) {
				echo "    <bmdb:AccessGrantedTo>{$group['acl']['ldapGroup']}</bmdb:AccessGrantedTo>\r\n";
			}
			
			// Associated Term Definitions
			foreach ($bod['Term'] as $term) {
				$aboutId = $this->escapeEntities("http://{$this->getResourceBase()}/terms/view/{$term['id']}");
				echo "    <bmdb:ReferencesTerm rdf:resource=\"{$aboutId}\"/>\r\n"; 
			}
		
			// Studies
			if (count($bod['StudyData']) > 0) {
				echo "    <bmdb:hasBiomarkerOrganStudyDatas>\r\n";
				echo "      <rdf:Bag>\r\n";
				foreach ($bod['StudyData'] as $studyData) {
					$aboutId = $this->escapeEntities("{$aboutURL}#{$studyData['id']}"); 
					echo "        <rdf:li rdf:resource=\"{$aboutId}\"/>\r\n";
					$studyDatas[$aboutId] = $studyData;
				}
				echo "      </rdf:Bag>\r\n";
				echo "    </bmdb:hasBiomarkerOrganStudyDatas>\r\n";
			} else {
				echo "    <bmdb:hasBiomarkerOrganStudyDatas/>\r\n";
			}
			
			// Publications
			if (count($bod['Publication']) > 0) {
				foreach ($bod['Publication'] as $pub) {
					echo "    <bmdb:referencedInPublication rdf:resource=\"http://{$this->getResourceBase()}/publications/view/{$pub['id']}\"/>\r\n";
				}
			} 
			
			// Resources
			if (count($bod['OrganDataResource']) > 0) {
				foreach ($bod['OrganDataResource'] as $res) {
					echo "    <bmdb:referencesResource rdf:resource=\"{$this->escapeEntities($res['URL'])}\"/>\r\n";
				}
			} 
		
			echo "  </bmdb:BiomarkerOrganData>\r\n";
			// Print corresponding study data objects
			$this->studydatas($studyDatas,$bod,$sensitivities);
		} /* end foreach */

		// Print corresponding sensitivity datas
		$this->sensitivities($sensitivities);
		$this->printRdfEnd();
		exit();
	}
	
	function studies() {
		header("content-type:application/rdf+xml; charset=utf-8");
		$this->printRdfStart();
		$studies = $this->Study->findAll();
		foreach ($studies as $s) {
			//$aboutURL = "http://{$this->getResourceBase()}/studies/view/{$s['Study']['id']}";
			$aboutURL = "http://edrn.nci.nih.gov/data/protocols/{$s['Study']['FHCRC_ID']}";
	
			// Basics
			echo "  <rdf:Description rdf:about=\"{$aboutURL}\">\r\n";
			/*
			 * THIS INFORMATION NOW COMES FROM THE DMCC
			 * http://ginger.fhcrc.org/dmcc/rdf-data/protocols/rdf
			 * 
			 **/
			echo "    <bmdb:Title>".$this->escapeEntities($s['Study']['title'])."</bmdb:Title>\r\n";
			echo "    <bmdb:URN>urn:edrn:bmdb:study:{$s['Study']['id']}</bmdb:URN>\r\n";
			echo "    <bmdb:FHCRC_ID>{$s['Study']['FHCRC_ID']}</bmdb:FHCRC_ID>\r\n";
			echo "    <bmdb:DMCC_ID>{$s['Study']['DMCC_ID']}</bmdb:DMCC_ID>\r\n";
			echo "    <bmdb:StudyAbstract>".$this->escapeEntities($s['Study']['studyAbstract'])."</bmdb:StudyAbstract>\r\n";
			echo "    <bmdb:StudyObjective>".$this->escapeEntities($s['Study']['studyObjective'])."</bmdb:StudyObjective>\r\n";
			echo "    <bmdb:StudySpecificAims>".$this->escapeEntities($s['Study']['studySpecificAims'])."</bmdb:StudySpecificAims>\r\n";
			echo "    <bmdb:StudyResultsOutcome>".$this->escapeEntities($s['Study']['studyResultsOutcome'])."</bmdb:StudyResultsOutcome>\r\n";
			echo "    <bmdb:CollaborativeGroups>".$this->escapeEntities($s['Study']['collaborativeGroups'])."</bmdb:CollaborativeGroups>\r\n";
			echo "    <bmdb:BiomarkerPopulationCharacteristics>{$s['Study']['bioPopChar']}</bmdb:BiomarkerPopulationCharacteristics>\r\n";
			echo "    <bmdb:BPCDescription>".$this->escapeEntities($s['Study']['BPCDescription'])."</bmdb:BPCDescription>\r\n";
			echo "    <bmdb:Design>{$s['Study']['design']}</bmdb:Design>\r\n";
			echo "    <bmdb:DesignDescription>".$this->escapeEntities($s['Study']['designDescription'])."</bmdb:DesignDescription>\r\n";
			echo "    <bmdb:BiomarkerStudyType>{$s['Study']['biomarkerStudyType']}</bmdb:BiomarkerStudyType>\r\n";
			/**/
			
			// Publications
			if (count($s['Publication']) > 0) {
				foreach ($s['Publication'] as $pub) {
					echo "    <bmdb:isDescribedIn rdf:resource=\"http://{$this->getResourceBase()}/publications/view/{$pub['id']}\"/>\r\n";
				}
			} 
			
			// Resources
			if (count($s['StudyResource']) > 0) {
				foreach ($s['StudyResource'] as $res) {
					echo "    <bmdb:externalResource rdf:resource=\"{$this->escapeEntities($res['URL'])}\"/>\r\n";
				}
			} 
			// Sites 
			/*
			 * THIS INFORMATION NOW COMES FROM THE DMCC
			 * http://ginger.fhcrc.org/dmcc/rdf-data/protocols/rdf
			 * 
			 **/
			$sites = $this->Study->getSitesFor($s['Study']['FHCRC_ID']);
			if (count($sites) > 0) {
				foreach ($sites as $site) {
					$site_id = $site['sites_studies']['site_id'];
					echo "    <bmdb:participatingSite rdf:resource=\"http://{$this->getResourceBase()}/rdf/sites/{$site_id}\"/>\r\n";
				}
			}
			/**/
			echo "  </rdf:Description>\r\n";
		}/* end foreach */
		$this->printRdfEnd();
		exit();
	}

	function publications() {
		header("content-type:application/rdf+xml; charset=utf-8");
		$this->printRdfStart();
		$publications = $this->Publication->findAll();
		foreach ($publications as $p) {
			$aboutURL = "http://{$this->getResourceBase()}/publications/view/{$p['Publication']['id']}";
	
			// Basics
			echo "  <edrntype:Publication rdf:about=\"{$aboutURL}\">\r\n";
			echo "    <dc:title>".$this->escapeEntities($p['Publication']['title'])."</dc:title>\r\n";
			echo "    <dc:author>{$p['Publication']['author']}</dc:author>\r\n";
			echo "    <edrn:journal>{$p['Publication']['journal']}</edrn:journal>\r\n";
			echo "    <bmdb:Published>{$p['Publication']['published']}</bmdb:Published>\r\n";
			echo "    <edrn:pmid>{$p['Publication']['pubmed_id']}</edrn:pmid>\r\n";
			echo "  </edrntype:Publication>\r\n";
		}/* end foreach */
		$this->printRdfEnd();
		exit();
	}

	function resources() {
		header("content-type:application/rdf+xml; charset=utf-8");
		$this->printRdfStart();
		$results = $this->Resource->getemall();
		
		foreach ($results as $url=>$desc) {
			echo "  <bmdb:ExternalResource rdf:about=\"{$this->escapeEntities($url)}\">\r\n";
			echo "    <bmdb:URI>{$this->escapeEntities($url)}</bmdb:URI>\r\n";
			echo "    <bmdb:Description>".$this->escapeEntities($desc)."</bmdb:Description>\r\n";
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
			$aboutURL = "http://{$this->getResourceBase()}/rdf/pis/{$p['pis']['site_id']}";
	
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
			echo "    <bmdb:belongsToSite rdf:resource=\"http://{$this->getResourceBase()}/rdf/sites/{$p['pis']['site_id']}\"/>\r\n";
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
			$aboutURL = "http://{$this->getResourceBase()}/rdf/sites/{$s['sites']['site_id']}";
	
			// Basics
			echo "  <bmdb:Site rdf:about=\"{$aboutURL}\">\r\n";
			echo "    <bmdb:Name>".$this->escapeEntities($s['sites']['name'])."</bmdb:Name>\r\n";
			echo "    <bmdb:SiteId>{$s['sites']['site_id']}</bmdb:SiteId>\r\n";
			echo "    <bmdb:hasPrincipalInvestigator rdf:resource=\"http://{$this->getResourceBase()}/pis/{$s['sites']['site_id']}\"/>\r\n";
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
		$source = str_replace("'","&apos;",  $source);
		$source = str_replace("&","&amp;",   $source);
		
		return $source;
	}
	
	private function printRdfStart() {
		echo <<<END
<?xml version='1.0' encoding='UTF-8'?>
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" 
         xmlns:bmdb="http://edrn.nci.nih.gov/rdf/rdfs/bmdb-1.0.0#"
         xmlns:edrn="http://edrn.nci.nih.gov/rdf/schema.rdf#"
         xmlns:edrntype="http://edrn.nci.nih.gov/rdf/types.rdf#" 
         xmlns:dc="http://purl.org/dc/terms/">

END;
	}
	
	private function printRdfEnd() {
		echo "</rdf:RDF>\r\n";
	}
	
	private function calculatePV($sensitivity,$specificity,$prevalence=0) {
		if ($prevalence == 0) {
			return array(
				"NPV"=>'',
				"PPV"=>'');
		}
		/*
		 * Calculate NPV/PPV from Sens/Spec/Prevalence data 
		 * PPV = (Sens. x Prev.)/[Sens. x Prev. + (1-Spec.) x (1-Prev.)]
		 * NPV = [Spec. x (1-Prev.)]/[(1-Sens.) x Prev. + Spec. x (1-Prev.)]
		 * where
		 *
		 * Sens. = Sensitivity
		 * Spec. = Specificity
		 * Prev. = Prevalence
		*/
		$sens = $sensitivity / 100;
		$spec = $specificity / 100;
		$prev = $prevalence;
	
		if ($sens > 0 && $spec > 0 && $prev > 0) {
			$ppv = (round(($sens * $prev)/($sens * $prev + (1-$spec) * (1-$prev)),2) * 100);
			$npv = (round(($spec * (1-$prev))/((1-$sens)*$prev + $spec * (1-$prev)),2) * 100);
		} else {
			$ppv = '';
			$npv = '';
		}
		
		return array (
			"NPV" => $npv,
			"PPV" => $ppv);
	}
}
?>