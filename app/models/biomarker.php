<?php

class Biomarker extends AppModel
{

	public $hasMany = array(
		/*
		 * A 'Biomarker' object HAS MANY 'OrganData' objects
		 */
		'OrganData' => array(
			'className' => 'OrganData',
			'foreignKey'=> 'biomarker_id',
			'dependent' => true
		),
		'BiomarkerStudyData' => array(
			'className' => 'BiomarkerStudyData',
			'foreignKey'=> 'biomarker_id',
			'dependent' => 'true'
		),
		'BiomarkerResource' => array(
			'className' => 'BiomarkerResource',
			'foreignKey'=> 'biomarker_id',
			'dependent' => true
		),
		'BiomarkerName' => array(
			'className' => 'BiomarkerName',
			'foreignKey'=> 'biomarker_id',
			'dependent' => true
		)
	);
	
	public $hasAndBelongsToMany = array(
	
		/*
		 * A 'Biomarker' object HAS AND BELONGS TO MANY 'Publication' objects
		 */
		'Publication' => array(
			'className' => 'Publication',
			'joinTable'=> 'biomarkers_publications',
			'foreignKey'=> 'biomarker_id',
			'associationForeignKey'=>'publication_id',
			'unique'=>true
		),
		/*
		 * A 'Biomarker (Panel)' object HAS AND BELONGS TO MANY 'Biomarker' objects
		 */
		'Panel' => array(
			'className' => 'Biomarker',
			'joinTable'=> 'paneldata',
			'foreignKey'=> 'panel_id',
			'associationForeignKey'=>'biomarker_id',
			'unique'=>true
		)
	);
	
	public function clearPanelMembers($id) {
		/* get rid of all membership records for this panel */
		$q = "DELETE FROM `paneldata` WHERE `panel_id` = '{$id}'";
		$this->query($q);
	}
	
	public function getAvailableBiomarkersForPanel($id) {
		/* which biomarkers are NOT part of this panel */
		$q = "SELECT `id` FROM `biomarkers` WHERE `id` NOT IN "
				."(SELECT `biomarker_id` FROM `paneldata` "
					."WHERE `panel_id`='{$id}') AND `id` <> '{$id}'";
		$results = $this->query($q);

		$result = array();
		foreach ($results as $r) {
			$result[] = array("id"=>$r['biomarkers']['id'],"name"=>$this->getDefaultNameById($r['biomarkers']['id']));
		}
		return $result;
	}
	
	public function getPanelMembership($id) {
		/* which panels is this biomarker a member of? */
		$q = "SELECT `panel_id` FROM `paneldata` "
			. "WHERE `biomarker_id`='{$id}'";	
		$results = $this->query($q);
		$result = array();
		foreach ($results as $r) {
			$result[] = array("id"=>$r['paneldata']['panel_id'],"name"=>$this->getDefaultNameById($r['paneldata']['panel_id']));
		}
		return $result;
	}
	
	public static function getDefaultName($biomarker) {
		/* what is the current default name (out of all the aliases) for this marker */
		foreach ($biomarker['BiomarkerName'] as $name) {
			if ($name['isPrimary'] == 1) {return $name['name'];}
		}
		return "unknown";
	}	
	
	public function getDefaultNameById($biomarker_id) {
		/* same as above, only given a biomarker id */
		$q = "SELECT `name` FROM `biomarker_names` WHERE `biomarker_id`='{$biomarker_id}' AND `isPrimary`='1'";
		$results = $this->query($q);

		if (count($results) > 0) {
			return $results[0]['biomarker_names']['name'];
		} else {
			return "unknown";
		}
	}
	
	/* get a listing of all biomarkers suitable for display on the biomarker browse page */
	public function getIndex() {
		$q = "SELECT Biomarker.id, Biomarker.qastate, Biomarker.type, Biomarker.isPanel, Names.name ".
			"FROM biomarkers as Biomarker ".
				"JOIN biomarker_names AS Names ON (Names.biomarker_id=Biomarker.id AND Names.isPrimary=1) ".
					"WHERE 1";
		return $this->query($q);
	}
	
	/* get important information about the biomarker-organ data pairs for a given biomarker */
	public function getOrganDatasFor($biomarker_id) {
		$q = "SELECT Biomarker.id, OrganData.id, Organ.name ".
				"FROM biomarkers as Biomarker, organ_datas as OrganData, organs as Organ ".
					"WHERE Biomarker.id={$biomarker_id} ".
						"AND OrganData.biomarker_id = {$biomarker_id} ".
						"AND Organ.id = OrganData.organ_id";
		return $this->query($q);
	}
	
	public function god($biomarker_id) {
		return ($this->OrganData->find('all',array('conditions'=>array('biomarker_id'=>$biomarker_id),'recursive'=>2)));
	}
	public function getStudyDatasFor($biomarker_id){ 
		return ($this->BiomarkerStudyData->find('all',array('conditions'=>array('biomarker_id'=>$biomarker_id),'recursive'=>2)));	
	}
	
	
	var $actsAs = 'ExtendAssociations';
}
?>