<?php
class BiomarkersController extends AppController {
	
	var $name    = "Biomarkers";
	var $helpers = array('Html','Ajax','Javascript','Pagination');
	var $components = array('Pagination');
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

	/******************************************************************
	 * BROWSE (INDEX)
	 ******************************************************************/
	function index() {
		// Ensure that a valid user is logged in
		$this->checkSession('/biomarkers');
		
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
	 * BASICS
	 ******************************************************************/
	
	function view($id = null) {
		$this->checkSession("/biomarkers/view/{$id}");
		
		$biomarker = $this->Biomarker->find('first',array(
			'conditions' => array('Biomarker.id' => $id),
			'recursive'  => 1
			)
		);
		if ($biomarker !== false) {
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
		} else {
			die("<b>Error:</b> No matching Biomarker for id {$id}");
		}
	}
	
	function savefield() {
		$data =& $this->params['form'];
		$this->checkSession("/biomarkers/view/{$data['id']}");
		if ($data['object'] == "biomarker") {
			/**
			 * If the attribute to be saved is the 'name', this should be handled
			 * specially because 'name' is not directly associated with the Biomarker
			 * object itself, but rather comes from a list of Aliases, one of which
			 * has been designated as 'primary'. If the user is attempting to update
			 * the Biomarker name, what really must be updated is the value of the 
			 * primary Alias for that biomarker.
			 */
			if ($data['attr'] == 'name') {
				// Retrieve the alias
				$alias = $this->BiomarkerName->find('first',
					array('conditions' => 
						array('BiomarkerName.biomarker_id' => $data['id'],
						      'BiomarkerName.isPrimary'    => 1),
					'recursive'=>1));
				// Make the update
				$this->BiomarkerName->id = $alias['BiomarkerName']['id'];
				$this->BiomarkerName->saveField('name',$data['name']);
			} else {
				// Make the update
				$this->Biomarker->id = $data['id'];
				$this->Biomarker->saveField($data['attr'],$data[$data['attr']]);
			}
			$output = $data[$data['attr']];
			$this->Auditor->audit("set '{$data['attr']}' to '{$data[$data['attr']]}' for marker ".$this->Biomarker->getDefaultNameById($data['id']).".");
		} else if ($data['object'] == "organ_data") {
			$this->OrganData->id = $data['id'];
			$this->OrganData->saveField($data['attr'],$data[$data['attr']]);
			$output = $data[$data['attr']];
			$this->Auditor->audit("set '{$data['attr']}' to '{$data[$data['attr']]}' for {$data['object']} #{$data['id']}. ");
		} else if ($data['object'] == "organ_study_data") {
			$this->StudyData->id = $data['id'];
			$this->StudyData->saveField($data['attr'],$data[$data['attr']]);
			$output = $data[$data['attr']];
			$this->Auditor->audit("set '{$data['attr']}' to '{$data[$data['attr']]}' for {$data['object']} #{$data['id']}. ");
		} else if ($data['object'] == "study_data") {
			$this->BiomarkerStudyData->id = $data['id'];
			$this->BiomarkerStudyData->saveField($data['attr'],$data[$data['attr']]);
			$output = $data[$data['attr']];
			$this->Auditor->audit("set '{$data['attr']}' to '{$data[$data['attr']]}' for {$data['object']} #{$data['id']}. ");
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
				$this->Auditor->audit("set '{$alias['BiomarkerName']['name']}' as the Primary Name for biomarker #{$alias['Biomarker']['id']}.");		
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
		$this->Auditor->audit("added '{$data['altname']}' as an alias for " . $this->Biomarker->getDefaultNameById($data['biomarker_id']).".");
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
			$this->Auditor->audit("removed '{$alias['BiomarkerName']['name']}' as an alias for biomarker #{$biomarker_id}.");
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

		if ($biomarker !== false) {
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
		} else {
			die("<b>Error:</b> No matching Biomarker for id {$id}");
		}
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
	
	// -- Add BiomarkerOrganStudyData Sensitivity Details--
	function addSensSpec() {
		$data =&$this->params['form'];
		// Create the Sensitivity object
		$this->Sensitivity->create(
			array("study_id" =>$data['study_id'],
				"sensitivity"=>$data['sensitivity'],
				"specificity"=>$data['specificity'],
				"prevalence" =>$data['prevalence'],
				"specificAssayType" => $data['specificAssayType'],
				"notes"=>$data['sensspec_details'])
			);
		$this->Sensitivity->save();
		$sensitivity_id = $this->Sensitivity->getLastInsertId();
		
		// Create the HABTM link
		$this->checkSession("/biomarkers/organs/{$data['biomarker_id']}/{$data['organ_data_id']}");
		$this->StudyData->habtmAdd('Sensitivity',$data['study_data_id'],$sensitivity_id);
		// Redirect
		$this->redirect("/biomarkers/organs/{$data['biomarker_id']}/{$data['organ_data_id']}");
	}
	function editsensspec($sensitivity_id,$biomarker_id,$organ_data_id,$study_id,$source="organs") {
		$this->checkSession("/biomarkers/organs/{$biomarker_id}/{$organ_data_id}");
		
		// Load the Biomarker object
		$biomarker = $this->Biomarker->find('first',array(
			'conditions' => array('Biomarker.id' => $biomarker_id),
			'recursive'  => 1
			)
		);
		$this->set('biomarker',$biomarker);
		$this->set('biomarkerName',Biomarker::getDefaultName($biomarker));
		
		
		// Try to load the current OrganData object
		$organdatas = $this->Biomarker->god($biomarker_id);
		$this->set('organData',false);
		foreach ($organdatas as $od) {
			if ($od['OrganData']['id'] == $organ_data_id) {
				$this->set('organData',$od);
				break;
			}
		}
		
		// Try to load the current study object
		$study = $this->Study->find("first",array(
				'conditions' => array('Study.id' => $study_id)
			)
		);
		$this->set('study',$study);
		
		// Try to load the current sens/spec object
		$sensitivity = $this->Sensitivity->find("first",array(
				'conditions' => array('Sensitivity.id' => $sensitivity_id)
			)
		);
		$this->set('sensitivity',$sensitivity);
		
		// Compute the next page to return to
		if ($source == "organs") {
			$this->set('next_page',"/biomarkers/organs/{$biomarker['Biomarker']['id']}/{$od['OrganData']['id']}");
		} else {
			$this->set('next_page',"/biomarkers/studies/{$biomarker['Biomarker']['id']}");
		}
	}
	function doEditSensSpec() {
		// Get data from the form
		$data =& $this->params['form'];
		$biomarker_id   = $data['biomarker_id'];
		$organ_data_id  = $data['organ_data_id'];
		$sensitivity_id = $data['sensitivity_id'];
		$next_page      = $data['next_page'];
		
		// Try to load the sens/spec data point
		$sensitivity = $this->Sensitivity->find('first',array(
			'conditions' => array('Sensitivity.id' => $sensitivity_id)
			)
		);
		$this->Sensitivity->id = $sensitivity_id;
		
		// Save the changes
		$this->Sensitivity->saveField('sensitivity',$data['sensitivity']);
		$this->Sensitivity->saveField('specificity',$data['specificity']);
		$this->Sensitivity->saveField('prevalence',$data['prevalence']);
		$this->Sensitivity->saveField('specificAssayType',$data['specificAssayType']);
		$this->Sensitivity->saveField('notes',$data['notes']);
		
		// Return the user to the specified next page
		$this->redirect($next_page);
	}
	
	// -- Remove BiomarkerOrganStudyData Sensitivity Details--
	function removeSensSpec($biomarker_id,$organ_data_id,$study_data_id,$sensitivity_id) {
		$this->checkSession("/biomarkers/organs/{$biomarker_id}/{$organ_data_id}");
		$this->StudyData->habtmDelete("Sensitivity",$study_data_id,$sensitivity_id);
		$this->redirect("/biomarkers/organs/{$biomarker_id}/{$organ_data_id}");
	}
	
	// -- Add BiomarkerStudyData Sensitivity Details--
	function addSensSpec2() {
		$data =&$this->params['form'];
		// Create the Sensitivity object
		$this->Sensitivity->create(
			array("study_id" =>$data['study_id'],
				"sensitivity"=>$data['sensitivity'],
				"specificity"=>$data['specificity'],
				"prevalence" =>$data['prevalence'],
				"specificAssayType" => $data['specificAssayType'],
				"notes"=>$data['sensspec_details'])
			);
		$this->Sensitivity->save();
		$sensitivity_id = $this->Sensitivity->getLastInsertId();
		
		// Create the HABTM link
		$this->checkSession("/biomarkers/studies/{$data['biomarker_id']}");
		$this->BiomarkerStudyData->habtmAdd('Sensitivity',$data['study_data_id'],$sensitivity_id);
		// Redirect
		$this->redirect("/biomarkers/studies/{$data['biomarker_id']}");
	}
	
	// -- Remove BiomarkerStudyData Sensitivity Details--
	function removeSensSpec2($biomarker_id,$study_data_id,$sensitivity_id) {
		$this->checkSession("/biomarkers/studies/{$biomarker_id}");
		$this->BiomarkerStudyData->habtmDelete("Sensitivity",$study_data_id,$sensitivity_id);
		$this->redirect("/biomarkers/studies/{$biomarker_id}");
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
	
	function addOrganTermDefinition() {
		$data = &$this->params['form'];
		$this->checkSession("/biomarkers/organs/{$data['biomarker_id']}/{$data['organ_data_id']}");
		$this->OrganData->habtmAdd('Term',$data['organ_data_id'],$data['term_id']);
		$this->redirect("/biomarkers/organs/{$data['biomarker_id']}/{$data['organ_data_id']}");
	}
	
	function removeOrganTermDefinition($biomarker_id,$organ_data_id,$term_id) {
		$this->checkSession("/biomarkers/organs/{$biomarker_id}/{$organ_data_id}");
		$this->OrganData->habtmDelete('Term',$organ_data_id,$term_id);
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
		if ($biomarker != false) {
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
		} else {
			die("<b>Error:</b> No matching Biomarker for id {$id}");
		}
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
		if ($biomarker != false) {
			$this->set('biomarker',$biomarker);
			$this->set('biomarkerName',Biomarker::getDefaultName($biomarker));
		} else {
			die("<b>Error:</b> No matching Biomarker for id {$id}");
		}
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
		if ($biomarker != false) {
			$this->set('biomarker',$biomarker);
			$this->set('biomarkerName',Biomarker::getDefaultName($biomarker));
		} else {
			die("<b>Error:</b> No matching Biomarker for id {$id}");
		}
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
				// Check for existing biomarker of same name (uniqueness check)
				$biomarker = $this->BiomarkerName->find('first',array(
					'conditions' => array("BiomarkerName.name" => $data['name']),
					'recursive'  => 2
					)
				);
				if (is_array($biomarker) && isset($biomarker['Biomarker'])) {
					// Determine the primary name for the resulting biomarker
					$primaryBiomarkerName = '';
					foreach ($biomarker['Biomarker']['BiomarkerName'] as $bn) {
						if ($bn['isPrimary'] == 1) {
							$primaryBiomarkerName = $bn['name']; 
							break; 
						}
					}
					// Provide an explanation message to the user
					die("<b>Error:</b> Biomarker '{$data['name']}' already exists as 
						either the name or an alias of: '{$primaryBiomarkerName}'");
				}
				$this->Biomarker->create(array('name'=>$data['name']));
				$this->Biomarker->save();
				$id = $this->Biomarker->getLastInsertID();
				
				// Create an 'alias' and set isPrimary to 1 (true)
				$this->BiomarkerName->create(array('biomarker_id'=>$id,'name'=>$data['name'],'isPrimary'=>1));
				$this->BiomarkerName->save();
				$this->Auditor->audit("created a new biomarker '{$data['name']}' with unique id '{$id}'.",Auditor::VERBOSITY_NORMAL,"biomarker",$id);
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
		$this->Auditor->audit("deleted biomarker with unique id '{$id}'.",Auditor::VERBOSITY_NORMAL,"biomarker",$id);
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
	 * REDIRECTION
	 ******************************************************************/
	function redirection() {
		$data = &$this->params['form'];
		if ($data['id']) {
			$this->redirect("/biomarkers/view/{$data['id']}");
		} else {
			$this->redirect("/biomarkers");
		}
	}
}
?>