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

		function getPeople($id) {
			$q = "SELECT `givenname`, `surname`, `location`, `person_id`, `study_id` FROM `person_study` JOIN `people` ON `person_study`.`person_id`=`people`.`dmcc_id` WHERE `study_id`={$id}";
                        return($this->query($q));
                }

		function dropResearcher($person_id, $study_id) {
			$q = "DELETE FROM `person_study` WHERE `person_id`={$person_id} AND `study_id`={$study_id}";
			return($this->query($q));
		}
	
		function associateResearcher($person_id, $study_id) {
			$q = "INSERT INTO `person_study` (person_id, study_id) VALUES({$person_id}, {$study_id})";
			return($this->query($q));
		}

		function associateSite($site_id, $study_id) {
			$q = "INSERT INTO `sites_studies` (study_id, site_id) VALUES({$study_id}, {$site_id})";
			return($this->query($q));
		}

		function dropSite($site_id, $study_id) {
			$q = "DELETE FROM `sites_studies` WHERE `site_id`={$site_id} AND `study_id`={$study_id}";
			return($this->query($q));
		}

                function getMaxId() {
                        $q = "SELECT MAX(`FHCRC_ID`) FROM `studies`";
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
