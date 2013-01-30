<?php
class ApisController extends AppController {
	
	var $name    = "Apis";
	var $helpers = array('Html','Ajax','Javascript','Pagination');
	var $components = array('Pagination');
	var $uses = 
		array(
			'Biomarker',
			'BiomarkerName',
			'BiomarkerDataset',
			'Auditor',
			'LdapUser',
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
			'Sensitivity',
			'Term'
		);

	/******************************************************************
	 * BROWSE (INDEX)
	 ******************************************************************/
	function index() {
		
		// Retrieve information about all of the biomarkers in the system,
		// recursing only 1 level deep (don't need lots of study, etc data) here
		$biomarkers = $this->Biomarker->find('all',array(
			'conditions' => array(),
			'recursive'  => 1
		));
		
		// Populate each biomarker with the names of its associated organs and its default name
		for ($i=0;$i<count($biomarkers);$i++) {
			$biomarkers[$i]['OrganDatas'] = $this->Biomarker->getOrganDatasFor($biomarkers[$i]['Biomarker']['id']);
			$biomarkers[$i]['DefaultName']= $this->Biomarker->getDefaultName($biomarkers[$i]);
		}
		
		// Send it off to the view
		$this->set('biomarkers',$biomarkers);
	}
	
	/******************************************************************
	 * APIs
	 ******************************************************************/
	
	function biomarkers() {

	  $csv = array();

	  // Retrieve information about all of the biomarkers in the system,
	  // recursing only 1 level deep (don't need lots of study, etc data) here
	  $biomarkers = $this->Biomarker->find('all',array(
							   'conditions' => array(),
							   'recursive'  => 1
							   ));

	  // Populate each biomarker with the names of its associated organs and its default name
	  // and build the CSV data structure to return
	  for ($i=0;$i<count($biomarkers);$i++) {
	    $biomarkers[$i]['OrganDatas'] = $this->Biomarker->getOrganDatasFor($biomarkers[$i]['Biomarker']['id']);
	    $biomarkers[$i]['DefaultName']= $this->Biomarker->getDefaultName($biomarkers[$i]);
	    $biomarkers[$i]['HgncName']   = $this->Biomarker->getHgncName($biomarkers[$i]);
	    $biomarkers[$i]['AlternativeNames'] = implode('$$',$this->Biomarker->getAlternativeNames($biomarkers[$i]));

	    foreach ($biomarkers[$i]['OrganDatas'] as $od) {
	      $od_full = $this->OrganData->find('first',array('conditions'=>array('OrganData.id'=>$od['OrganData']['id'])));
	      $csv[]   = array(
	          , "Id"             => $biomarkers[$i]['Biomarker']['id']
	          , "Biomarker Name" => $biomarkers[$i]['DefaultName']
			      , "HgncName"       => $biomarkers[$i]['HgncName']
			      , "AlternativeNames" => $biomarkers[$i]['AlternativeNames'] 
			      , "Organ"          => $od['Organ']['name']
			      , "Type"           => $biomarkers[$i]['Biomarker']['type']
			      , "QA State"       => $od_full['OrganData']['qastate']
			      , "Phase"          => $od_full['OrganData']['phase']
			      );
	    }
	  }

	  // Convert to CSV
	  if (count($csv) == 0) { return; } // No Data

	  ob_start();
	  $columns = array_keys($csv[0]);
          echo implode(',',$columns) . "\r\n";
          foreach ($csv as $row) {
            echo "\"" .  implode('","',$row) . "\"" . "\r\n";
          }

	  // Header settings
	  header('Content-Description: File Transfer');
	  header('Content-Type: text/csv');
	  header('Content-Disposition: attachment; filename=EDRN_BMDB_Biomarkers.csv');
	  header('Expires: 0');
	  header('Cache-Control: must-revalidate');
	  header('Pragma: public');
	  ob_end_flush();

	  // Done
	  exit();
	}

}
