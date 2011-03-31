<?php
class AuditorsController extends AppController {
	
	var $helpers = array('Html','Ajax','Javascript');
	var $uses = 
		array(
			'Biomarker',
			'BiomarkerName',
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
	
	public function weeklySummary() {
		$this->checkSession("/auditors/weeklySummary");
		$audits = $names = $this->Auditor->find("all",
			array("order"=>'`timestamp` DESC'));
		$this->set("audits",$audits);
		
	}
	
	public function metrics() {
		$this->checkSession("/auditors/metrics");
		
		/***
		 * BIOMARKER METRICS
		 *************************************************************/
		$biomarkers = $this->Biomarker->find('all',array(
			'conditions' => array(),
			'recursive'  => 1,
			"order"=>'`id` DESC'
		));
		
		// Populate each biomarker with the names of its default name
		for ($i=0;$i<count($biomarkers);$i++) {
			$biomarkers[$i]['DefaultName']= $this->Biomarker->getDefaultName($biomarkers[$i]);
		}
		
		// Send it off to the view
		$this->set('numBiomarkers',count($biomarkers));
		$this->set('latestBiomarkers', array_splice($biomarkers,0,5));
		
		
		/***
		 * PROTOCOL METRICS
		 *************************************************************/
		$studies = $this->Study->find("all",array(
			'conditions' => array(),
			'recursive'  => 1,
			'order'=>'`id` DESC'
		));
		$this->set('numProtocols',count($studies));
		$this->set('latestProtocols',array_splice($studies,0,5));
		
		/***
		 * PUBLICATION METRICS
		 *************************************************************/
		$pubs = $this->Publication->findAll();
		$this->set('numPublications',count($pubs));
		$this->set('latestPublications', array_splice($pubs,0,5));
	}
}
?>