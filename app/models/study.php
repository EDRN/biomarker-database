<?php
	class Study extends AppModel {
		
		public $hasMany = array(
			'StudyResource' => array(
				'className' => 'StudyResource',
				'foreignKey'=> 'study_id',
				'dependent' => true
			)
		);
		
		public $hasAndBelongsToMany = array(
	
			/*
			 * A 'Study' object HAS AND BELONGS TO MANY 'Publication' objects
			 */
			'Publication' => array(
				'className' => 'Publication',
				'join_table'=> 'publications_studies',
				'foreignKey'=> 'study_id',
				'associationForeignKey'=>'publication_id',
				'unique'=>true
			),
		
		);
	
		var $actsAs = 'ExtendAssociations';
		
		function getSitesFor($id) {
			$q = "SELECT `site_id` FROM `sites_studies` WHERE `study_id`={$id} ";
			return($this->query($q));
		}
		
		function getExtendedSiteDetailsFor($id) {
			$q = "SELECT `site_id` FROM `sites_studies` WHERE `study_id`={$id} ";
			$res = $this->query($q);
			$site_ids = array();
			foreach ($res as $r) {
				$site_ids[] = $r['sites_studies']['site_id'];
			}
			if (count($site_ids) > 0) {
				$q = "SELECT * FROM `sites`	WHERE `site_id` IN (".implode(",",$site_ids).")";
				return ($this->query($q));
			} else {
				return array();
			}
		}
	}
?>