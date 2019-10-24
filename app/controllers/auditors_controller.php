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
	
	public function weeklySummary($format = 'html') {
		$this->checkSession("/auditors/weeklySummary");
		$audits = $this->actionsSince(strtotime('1 WEEK AGO'));
		$this->set("audits",$audits);	
		
		if (strtolower($format) == 'json' ) {
			echo json_encode($audits);
			exit();
		}
	}
	
	public function previousMonth($format = 'html') {
		$this->checkSession("/auditors/weeklySummary");
		$audits = $this->actionsSince(strtotime('1 MONTH AGO'));
		$this->set("audits",$audits);
		
		if (strtolower($format) == 'json' ) {
			echo json_encode($audits);
			exit();
		}
	}
	
	protected function actionsSince($unix_time) {
		$actions = $this->Auditor->find('all',array(
			'conditions' => array(
				"`timestamp` > '" . date('Y-m-d 00:00:00',$unix_time) ."'"
			)
			,'order'=>'`timestamp` DESC'));
		
		return $actions;
	}
	
	public function metrics($format='html') {
		$this->checkSession("/auditors/metrics");
		
		if (strtolower($format) == 'json') {
			return $this->metrics_json();
		} else {
			return $this->metrics_html();
		}
	}
	
	protected function metrics_html() {
			
		/***
		 * BIOMARKER METRICS
		 *************************************************************/
		$biomarkers = $this->Biomarker->find('all',array(
			'conditions' => array(),
			'recursive'  => 1,
			"order"=>'`created` DESC'
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
		$pubs = $this->Publication->find('all');
		$this->set('numPublications',count($pubs));
		$this->set('latestPublications', array_splice($pubs,0,5));
	}
	
	public function metrics_json() {
	
		$data = array(
				'collected' => microtime(true)
		);
	
		/** Biomarker metrics **/
		$biomarkers = $this->Biomarker->find('all',array(
				'conditions' => array(),
				'recursive'  => 1,
				"order"=>'`created` ASC',
		));
	
		$numBiomarkers = count($biomarkers);
	
		$data['Biomarker'] = array(
				'count' => count($biomarkers),
				'latest'=> array()
		);
	
		
		$latest = array_splice($biomarkers,-5);
		foreach ($latest as $marker) {
			$defaultName = $this->Biomarker->getDefaultName($marker);
			$hgncName    = $this->Biomarker->getHgncName($marker);
			$ploneName   = strtolower(str_replace(' ','-',$defaultName));
				
			$data['Biomarker']['latest'][] = array(
					"name"       => $defaultName,
					"hgncName"   => $hgncName,
					"portalName" => $ploneName,
					"modified"   => $marker['Biomarker']['modified']			
			);
		}
	
		/** Publication metrics **/
		$publications = $this->Publication->find('all');

		$data['Publication'] = array(
			'count' => count($publications),
			'latest'=> array()
		);
		
		$latest = array_splice($publications,0,5);
		foreach ($latest as $pub) {
			$data['Publication']['latest'][] = array(
				"title" => $pub['Publication']['title'],
				"author"=> $pub['Publication']['author'],
				"journal"=>$pub['Publication']['journal'],
				"published" => $pub['Publication']['published']
			);	
		}	
		echo json_encode($data);
		exit();
	}
}
