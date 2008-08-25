<?php
class BiomarkersController extends AppController {
	
	var $helpers = array('Html','Ajax','Javascript');
	var $uses = 
		array(
			'LdapUser',
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
			'BiomarkerResource'
		);

	/******************************************************************
	 * BROWSE (INDEX)
	 ******************************************************************/
	function index($sort='',$key='',$ad='') {
		$this->checkSession('/biomarkers');
		
		
		
		if ($sort == "sort") {
			$order = (($ad == "ascending") ? "ASC":"DESC");
			$this->set('biomarkers', $this->Biomarker->findAll(null,null,"{$key} {$order}",null,1,2));
		} else {
			$this->set('biomarkers', $this->Biomarker->findAll(null,null,null,null,1,2));
		}
				
		// Get a list of all the biomarkers for the ajax search
		$biomarkers = $this->Biomarker->find("all",array('title','id'));
		$biomarkerarr = array();
		foreach ($biomarkers as $biomarker) {
			$biomarkerarr[] = "{$biomarker['Biomarker']['name']}|{$biomarker['Biomarker']['id']}";
		}
		$this->set('biomarkerstring','"'.implode("\",\"",$biomarkerarr).'"');
	}
	
	/******************************************************************
	 * BASICS
	 ******************************************************************/
	function view($id = null) {
		$this->checkSession("/biomarkers/view/{$id}");
		
		$biomarker = $this->Biomarker->find('first',array(
			'conditions' => array('Biomarker.id' => $id),
			'recursive'  => 1
			)
		);
		$this->set('biomarker',$biomarker);
	}
	
	function savefield() {
		$data =& $this->params['form'];
		$this->checkSession("/biomarkers/view/{$data['id']}");
		if ($data['object'] == "biomarker") {
			$this->Biomarker->id = $data['id'];
			$this->Biomarker->saveField($data['attr'],$data[$data['attr']]);
			$output = $data[$data['attr']];

		} else if ($data['object'] == "organ_data") {
			$this->OrganData->id = $data['id'];
			$this->OrganData->saveField($data['attr'],$data[$data['attr']]);
			$output = $data[$data['attr']];
		} else if ($data['object'] == "organ_study_data") {
			$this->StudyData->id = $data['id'];
			$this->StudyData->saveField($data['attr'],$data[$data['attr']]);
			$output = $data[$data['attr']];
		} else if ($data['object'] == "study_data") {
			$this->BiomarkerStudyData->id = $data['id'];
			$this->BiomarkerStudyData->saveField($data['attr'],$data[$data['attr']]);
			$output = $data[$data['attr']];
		}
		echo ($output == "") 
			? 'click to edit'
			: $output;
		die(); //prevent layout from being sent >:l
	}
	
	
	/******************************************************************
	 * ORGANS
	 ******************************************************************/
	function organs($id = null,$organ_id=null) {
		$this->checkSession("/biomarkers/organs/{$id}/{$organ_id}");
		// Load the Biomarker object
		$biomarker = $this->Biomarker->find('first',array(
			'conditions' => array('Biomarker.id' => $id),
			'recursive'  => 3
			)
		);
		$this->set('biomarker',$biomarker);
		
		// Get a list of all the Organs
		$this->set('organ',$this->Organ->FindAll());
		
		// Try to load the current OrganData object
		$this->set('organData',false);
		foreach ($biomarker['OrganData'] as $od) {
			if ($od['id'] == $organ_id) {
				$this->set('organData',$od);
			}
		}
		
		if ($organ_id == null && count($biomarker['OrganData']) > 0) {
			$this->redirect("/biomarkers/organs/{$biomarker['Biomarker']['id']}/{$biomarker['OrganData'][0]['id']}");
		}
		
		// Get a list of all the studies
		$studies = $this->Study->find("all",array('title','id'));
		$studyarr = array();
		foreach ($studies as $study) {
			$studyarr[] = "{$study['Study']['title']}|{$study['Study']['id']}";
		}
		$this->set('studystring','"'.implode("\",\"",$studyarr).'"');
	}
	
	function addOrganData() {
		$data = &$this->params['form'];
		$this->checkSession("/biomarkers/organs/{$data['biomarker_id']}");
		$this->OrganData->create(
			array('biomarker_id'=>$data['biomarker_id'],
					'organ_id'  =>$data['organ']));
		$this->OrganData->save();
		$id = $this->OrganData->getLastInsertID();
		$this->redirect("/biomarkers/organs/{$data['biomarker_id']}/{$id}");
	}
	
	function removeOrganData($biomarker_id,$organ_data_id) {
		$this->checkSession("/biomarkers/organs/{$biomarker_id}");
		$this->OrganData->id = $organ_data_id;
		$this->OrganData->delete();
		$this->redirect("/biomarkers/organs/{$biomarker_id}");
	}
	
	function addOrganStudyData() {
		$data = &$this->params['form'];
		$this->checkSession("/biomarkers/organs/{$data['biomarker_id']}/{$data['organ_data_id']}");
		$this->StudyData->create(
			array('organ_data_id'=>$data['organ_data_id'],
					'study_id'=>$data['study_id']));
		$this->StudyData->save();
		$this->redirect("/biomarkers/organs/{$data['biomarker_id']}/{$data['organ_data_id']}");
		
	}
	function removeOrganStudyData($biomarker_id,$organ_data_id,$study_data_id) {
		$this->checkSession("/biomarkers/organs/{$biomarker_id}/{$organ_data_id}");
		$this->StudyData->id = $study_data_id;
		$this->StudyData->delete();
		$this->redirect("/biomarkers/organs/{$biomarker_id}/{$organ_data_id}");
	}
	
	function addStudyDataPub() {
		$data = &$this->params['form'];
		$this->checkSession("/biomarkers/organs/{$data['biomarker_id']}/{$data['organ_data_id']}");
		$this->StudyData->habtmAdd('Publication',$data['study_data_id'],$data['pub_id']);
		$this->redirect("/biomarkers/organs/{$data['biomarker_id']}/{$data['organ_data_id']}");
	}
	
	function removeStudyDataPub($biomarker_id,$organ_data_id,$study_data_id,$pub_id) {
		$this->checkSession("/biomarkers/organs/{$biomarker_id}/{$organ_data_id}");
		$this->StudyData->habtmDelete('Publication',$study_data_id,$pub_id);
		$this->redirect("/biomarkers/organs/{$biomarker_id}/{$organ_data_id}");
	}
	
	function addOrganDataPub() {
		$data = &$this->params['form'];
		$this->checkSession("/biomarkers/organs/{$data['biomarker_id']}/{$data['organ_data_id']}");
		$this->OrganData->habtmAdd('Publication',$data['organ_data_id'],$data['pub_id']);
		$this->redirect("/biomarkers/organs/{$data['biomarker_id']}/{$data['organ_data_id']}");
	}
	
	function removeOrganDataPub($biomarker_id,$organ_data_id,$pub_id) {
		$this->checkSession("/biomarkers/organs/{$biomarker_id}/{$organ_data_id}");
		$this->OrganData->habtmDelete('Publication',$organ_data_id,$pub_id);
		$this->redirect("/biomarkers/organs/{$biomarker_id}/{$organ_data_id}");
	}
	
	function addStudyDataResource() {
		$data = &$this->params['form'];
		$this->checkSession("/biomarkers/organs/{$data['biomarker_id']}/{$data['organ_data_id']}");
		$this->StudyDataResource->create(
			array('study_data_id'=>$data['study_data_id'],
					'URL'=>$data['url'],
					'description'=>$data['desc']
			)
		);
		$this->StudyDataResource->save();
		$this->redirect("/biomarkers/organs/{$data['biomarker_id']}/{$data['organ_data_id']}");
		
	}
	function removeStudyDataResource($biomarker_id,$organ_data_id,$study_data_id,$res_id) {
		$this->checkSession("/biomarkers/organs/{$biomarker_id}/{$organ_data_id}");
		$this->StudyDataResource->id = $res_id;
		$this->StudyDataResource->delete();
		$this->redirect("/biomarkers/organs/{$biomarker_id}/{$organ_data_id}");
		
	}
	
	function addOrganDataResource() {
		$data = &$this->params['form'];
		$this->checkSession("/biomarkers/organs/{$data['biomarker_id']}/{$data['organ_data_id']}");
		$this->OrganDataResource->create(
			array('organ_data_id'=>$data['organ_data_id'],
					'URL'=>$data['url'],
					'description'=>$data['desc']
			)
		);
		$this->OrganDataResource->save();
		$this->redirect("/biomarkers/organs/{$data['biomarker_id']}/{$data['organ_data_id']}");
	}
	function removeOrganDataResource($biomarker_id,$organ_data_id,$res_id) {
		$this->checkSession("/biomarkers/organs/{$biomarker_id}/{$organ_data_id}");
		$this->OrganDataResource->id = $res_id;
		$this->OrganDataResource->delete();
		$this->redirect("/biomarkers/organs/{$biomarker_id}/{$organ_data_id}");
		
	}
	
	/******************************************************************
	 * STUDIES
	 ******************************************************************/

	function studies($id = null) {
		$this->checkSession("/biomarkers/studies/{$id}");
		$biomarker = $this->Biomarker->find('first',array(
			'conditions' => array('Biomarker.id' => $id),
			'recursive'  => 2
			)
		);
		$this->set('biomarker',$biomarker);
		
		// Get a list of all the studies
		$studies = $this->Study->find("all",array('title','id'));
		$studyarr = array();
		foreach ($studies as $study) {
			$studyarr[] = "{$study['Study']['title']}|{$study['Study']['id']}";
		}
		$this->set('studystring','"'.implode("\",\"",$studyarr).'"');
	}
	function addStudyData() {
		$data = &$this->params['form'];
		$this->checkSession("/biomarkers/studies/{$data['biomarker_id']}");
		$this->BiomarkerStudyData->create(
			array('biomarker_id'=>$data['biomarker_id'],
					'study_id'  =>$data['study_id']));
		$this->BiomarkerStudyData->save();
		$id = $this->BiomarkerStudyData->getLastInsertID();
		$this->redirect("/biomarkers/studies/{$data['biomarker_id']}");
	}
	function removeStudyData($biomarker_id,$study_data_id) {
		$this->checkSession("/biomarkers/studies/{$biomarker_id}");
		$this->BiomarkerStudyData->id = $study_data_id;
		$this->BiomarkerStudyData->delete();
		$this->redirect("/biomarkers/studies/{$biomarker_id}");
	}
	
	function addBiomarkerStudyDataPub() {
		$data = &$this->params['form'];
		$this->checkSession("/biomarkers/studies/{$data['biomarker_id']}");
		$this->BiomarkerStudyData->habtmAdd('Publication',$data['study_data_id'],$data['pub_id']);
		$this->redirect("/biomarkers/studies/{$data['biomarker_id']}");
	}
	function removeBiomarkerStudyDataPub($biomarker_id,$study_data_id,$pub_id) {
		$this->checkSession("/biomarkers/studies/{$biomarker_id}");
		$this->BiomarkerStudyData->habtmDelete('Publication',$study_data_id,$pub_id);
		$this->redirect("/biomarkers/studies/{$biomarker_id}");
	}
	function addBiomarkerStudyDataResource() {
		$data = &$this->params['form'];
		$this->checkSession("/biomarkers/studies/{$data['biomarker_id']}");
		$this->BiomarkerStudyDataResource->create(
			array('biomarker_study_data_id'=>$data['study_data_id'],
					'URL'=>$data['url'],
					'description'=>$data['desc']
			)
		);
		$this->BiomarkerStudyDataResource->save();
		$this->redirect("/biomarkers/studies/{$data['biomarker_id']}");
	}
	function removeBiomarkerStudyDataResource($biomarker_id,$study_data_id,$res_id) {
		$this->checkSession("/biomarkers/studies/{$biomarker_id}");
		$this->BiomarkerStudyDataResource->id = $res_id;
		$this->BiomarkerStudyDataResource->delete();
		$this->redirect("/biomarkers/studies/{$biomarker_id}");
	}
	
	
	/******************************************************************
	 * PUBLICATIONS
	 ******************************************************************/
	function publications($id = null) {
		$this->checkSession("/biomarkers/publications/{$id}");
		$biomarker = $this->Biomarker->find('first',array(
			'conditions' => array('Biomarker.id' => $id),
			'recursive'  => 1
			)
		);
		$this->set('biomarker',$biomarker);
	}
	
	function addPublication() {
		$data = &$this->params['form'];
		$this->checkSession("/biomarkers/publications/{$data['biomarker_id']}");
		$this->Biomarker->habtmAdd('Publication',$data['biomarker_id'],$data['pub_id']);
		$this->redirect("/biomarkers/publications/{$data['biomarker_id']}");
	}
	
	function removePublication($biomarker_id,$publication_id) {
		$this->checkSession("/biomarkers/publications/{$biomarker_id}");
		$this->Biomarker->habtmDelete('Publication',$biomarker_id,$publication_id);
		$this->redirect("/biomarkers/publications/{$biomarker_id}");
	}
	
	
	/******************************************************************
	 * RESOURCES
	 ******************************************************************/

	function resources($id = null) {
		$this->checkSession("/biomarkers/resources/{$id}");
		$biomarker = $this->Biomarker->find('first',array(
			'conditions' => array('Biomarker.id' => $id),
			'recursive'  => 1
			)
		);
		$this->set('biomarker',$biomarker);
	}
	
	function addResource() {
		$data = &$this->params['form'];
		$this->checkSession("/biomarkers/resources/{$data['biomarker_id']}");
		$this->BiomarkerResource->create(
			array('biomarker_id'=>$data['biomarker_id'],
					'URL'=>$data['url'],
					'description'=>$data['desc']
			)
		);
		$this->BiomarkerResource->save();
		$this->redirect("/biomarkers/resources/{$data['biomarker_id']}");
	}
	
	function removeResource($biomarker_id,$res_id) {
		$this->checkSession("/biomarkers/resources/{$biomarker_id}");
		$this->BiomarkerResource->id = $res_id;
		$this->BiomarkerResource->delete();
		$this->redirect("/biomarkers/resources/{$biomarker_id}");
	}
	
	
	/******************************************************************
	 * CREATE
	 ******************************************************************/
	function create() {
		$this->checkSession("/biomarkers/create");
	}
	function createBiomarker() {
		$this->checkSession("/biomarkers/create");
		if ($this->params['form']) {
			$data = &$this->params['form'];
			if ($data['name'] != '') {
				$this->Biomarker->create(array('name'=>$data['name']));
				$this->Biomarker->save();
				$id = $this->Biomarker->getLastInsertID();

				$this->redirect("/biomarkers/view/{$id}");
				
			} else {
				$this->set('error',true);
			}
		}
	}
	/******************************************************************
	 * DELETE
	 ******************************************************************/
	function delete($id) {
		$this->checkSession("/biomarkers");
		$this->Biomarker->id = $id;
		$this->Biomarker->delete();
		$this->redirect("/biomarkers");
	}
	/******************************************************************
	 * AJAX
	 ******************************************************************/
	function ajax_autocompletePublications () {
		$data =& $this->params['form'];
		$needle  = $data['needle'];
		$results = $this->Publication->query("SELECT `title` AS `title`,`id` AS `id` FROM `publications` WHERE `title` LIKE '%{$needle}%'");
		$rstr = '';
		
		foreach ($results as $r) {
			$rstr .= "<li><span id=\"{$r['publications']['id']}\">{$r['publications']['title']}</span></li>";	
			
		}
		echo ($rstr);
		die();
	}
	/******************************************************************
	 * GOTO
	 ******************************************************************/
	function goto() {
		$data = &$this->params['form'];
		if ($data['id']) {
			$this->redirect("/biomarkers/view/{$data['id']}");
		} else {
			$this->redirect("/biomarkers");
		}
	}
}
?>