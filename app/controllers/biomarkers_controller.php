<?php
class BiomarkersController extends AppController {
	
	var $name    = "Biomarkers";
	var $helpers = array('Html','Ajax','Javascript','Pagination');
	var $components = array('Pagination');
	var $uses = 
		array(
			'Biomarker',
			'BiomarkerName',
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
			'BiomarkerResource'
		);

	/******************************************************************
	 * BROWSE (INDEX)
	 ******************************************************************/
	function index() {
		$this->checkSession('/biomarkers');
		
		$criteria = null;
		$this->Pagination->resultsPerPage = array();
		$this->Pagination->show = 15;
		list($order,$limit,$page) = $this->Pagination->init($criteria);
		
		$biomarkers = $this->Biomarker->getIndex();
		
		for ($i=0;$i<count($biomarkers);$i++) {
			$biomarkers[$i]['OrganDatas'] = $this->Biomarker->getOrganDatasFor($biomarkers[$i]['Biomarker']['id']);
		}
		
		$this->set('biomarkers',$biomarkers);
				
		// Get a list of all the biomarkers for the ajax search
		$names = $this->BiomarkerName->find("all",array('name','biomarker_id'));
		$biomarkerarr = array();
		foreach ($names as $name) {
			$biomarkerarr[] = "{$name['BiomarkerName']['name']}|{$name['BiomarkerName']['biomarker_id']}";
		}
		$s = '"'.implode("\",\"",$biomarkerarr).'"';
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
		if ($biomarker['Biomarker']['isPanel'] == 1) {

			$this->set('availableMarkers',$this->Biomarker->getAvailableBiomarkersForPanel($id));
			for ($i=0;$i<count($biomarker['Panel']);$i++) {
				$biomarker['Panel'][$i]['defaultName'] = $this->Biomarker->getDefaultNameById($biomarker['Panel'][$i]['id']);
			}
			$this->set('panelMarkers',$biomarker['Panel']);
		}
		$this->set('panelMembership',$this->Biomarker->getPanelMembership($biomarker['Biomarker']['id']));
		$this->set('biomarkerName',Biomarker::getDefaultName($biomarker));
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
	
	function setPrimaryName($alias_id) {
		$this->checkSession("/biomarkers");
		$alias = $this->BiomarkerName->find($alias_id);
		
		// Load the requested alias
		$this->BiomarkerName->id = $alias_id;
		$alias = $this->BiomarkerName->find('first',array('conditions' => array('BiomarkerName.id' => $alias_id),'recursive'=>2));

		foreach ($alias['Biomarker']['BiomarkerName'] as $a) {
			if ($a['isPrimary'] == 1) {
				$this->BiomarkerName->id = $a['id'];
				$this->BiomarkerName->saveField('isPrimary',0);
			}
			if ($a['id'] == $alias['BiomarkerName']['id']) {
				$this->BiomarkerName->id = $a['id'];
				$this->BiomarkerName->saveField('isPrimary',1);
			}
		}

		$this->redirect("/biomarkers/view/{$alias['Biomarker']['id']}");
	}
	
	function addAlias() {
		$this->checkSession("/biomarkers");
		$data =& $this->params['form'];
		$this->BiomarkerName->create(
			array('biomarker_id'=>$data['biomarker_id'],
					      'name'=>$data['altname']));
		$this->BiomarkerName->save();
		$this->redirect("/biomarkers/view/{$data['biomarker_id']}");
	}
	
	function removeAlias($alias_id) {
		$this->checkSession("/biomarkers");
		$alias = $this->BiomarkerName->find('first',array('conditions' => array('BiomarkerName.id' => $alias_id),'recursive'=>2));
		if ($alias['BiomarkerName']['isPrimary'] == 1) {
			// Do Nothing... Can not delete the primary name
		} else {
			// Delete the alias
			$biomarker_id = $alias['Biomarker']['id'];
			$this->BiomarkerName->id = $alias['BiomarkerName']['id'];
			$this->BiomarkerName->delete();
			$this->redirect("/biomarkers/view/{$biomarker_id}");
		}
	}
	
	function setPanel($id,$value) {
		$this->checkSession("/biomarkers");
		
		$biomarker = $this->Biomarker->find('first',array(
			'conditions' => array('Biomarker.id' => $id),
			'recursive'  => 1
			)
		);
		$this->Biomarker->id = $id;
		$this->Biomarker->saveField('isPanel',((strtolower($value) == "yes")? 1 : 0));
		
		if (strtolower($value) != "yes") {
			$this->Biomarker->clearPanelMembers($id);
		}

		$this->redirect("/biomarkers/view/{$id}");
	}
	
	function editPanel() {
		$data =& $this->params['form'];
		$this->Biomarker->id = $data['biomarker_id'];
		$members = explode(',',$data['values']);
		unset($members[0]);
		$this->Biomarker->clearPanelMembers($data['biomarker_id']);

		foreach ($members as $mid) {
			$this->Biomarker->habtmAdd('Panel',$data['biomarker_id'],$mid);
		}
		
		$this->redirect("/biomarkers/view/{$data['biomarker_id']}");
	}
	
	/******************************************************************
	 * ORGANS
	 ******************************************************************/
	function organs($id = null,$organ_id=null) {
		$this->checkSession("/biomarkers/organs/{$id}/{$organ_id}");
		// Load the Biomarker object
		$biomarker = $this->Biomarker->find('first',array(
			'conditions' => array('Biomarker.id' => $id),
			'recursive'  => 1
			)
		);
		$this->set('biomarker',$biomarker);
		$this->set('biomarkerName',Biomarker::getDefaultName($biomarker));
		$organdatas = $this->Biomarker->god($biomarker['Biomarker']['id']);
		
		// Try to load the current OrganData object
		$this->set('organData',false);
		foreach ($organdatas as $od) {
			if ($od['OrganData']['id'] == $organ_id) {
				$this->set('organData',$od);
			}
		}
		$this->set('organdatas',$organdatas);
		
		// Get a list of all the Organs
		$this->set('organ',$this->Organ->FindAll());
		
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
			'recursive'  => 1
			)
		);
		$studydatas = $this->Biomarker->getStudyDatasFor($id);

		$this->set('biomarker',$biomarker);
		$this->set('studydatas',$studydatas);
		$this->set('biomarkerName',Biomarker::getDefaultName($biomarker));
		
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
		$this->set('biomarkerName',Biomarker::getDefaultName($biomarker));
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
		$this->set('biomarkerName',Biomarker::getDefaultName($biomarker));
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
				
				// Create an 'alias' and set isPrimary to 1 (true)
				$this->BiomarkerName->create(array('biomarker_id'=>$id,'name'=>$data['name'],'isPrimary'=>1));
				$this->BiomarkerName->save();

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