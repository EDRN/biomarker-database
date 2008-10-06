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
			'join_table'=> 'biomarkers_publications',
			'foreignKey'=> 'biomarker_id',
			'associationForeignKey'=>'publication_id',
			'unique'=>true
		),
		/*
		 * A 'Biomarker (Panel)' object HAS AND BELONGS TO MANY 'Biomarker' objects
		 */
		'Panel' => array(
			'className' => 'Biomarker',
			'join_table'=> 'biomarkers_biomarkers',
			'foreignKey'=> 'panel_id',
			'associationForeignKey'=>'biomarker_id',
			'unique'=>true
		)
	
	);
	
	public function clearPanelMembers($id) {
		/* get rid of all membership records for this panel */
		$q = "DELETE FROM `biomarkers_biomarkers` WHERE `panel_id` = '{$id}'";
		$this->query($q);
	}
	
	public function getAvailableBiomarkersForPanel($id) {
		/* which biomarkers are NOT part of this panel */
		$q = "SELECT `id` FROM `Biomarkers` WHERE `id` NOT IN "
				."(SELECT `biomarker_id` FROM `biomarkers_biomarkers` "
					."WHERE `panel_id`='{$id}') AND `id` <> '{$id}'";
		
		$results = $this->query($q);

		$result = array();
		foreach ($results as $r) {
			$result[] = array("id"=>$r['Biomarkers']['id'],"name"=>$this->getDefaultNameById($r['Biomarkers']['id']));
		}
		return $result;
	}
	
	public function getPanelMembership($id) {
		/* which panels is this biomarker a member of? */
		$q = "SELECT `panel_id` FROM `biomarkers_biomarkers` "
			. "WHERE `biomarker_id`='{$id}'";	
		$results = $this->query($q);
		$result = array();
		foreach ($results as $r) {
			$result[] = array("id"=>$r['biomarkers_biomarkers']['panel_id'],"name"=>$this->getDefaultNameById($r['biomarkers_biomarkers']['panel_id']));
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
	
	
	var $actsAs = 'ExtendAssociations';
	
}
?>