<?php
class Resource extends AppModel {
	
	
	public $name = "Resource";
	public $useTable = false;
	
	public function getemall() {
		
		$resources = array();
		
		// Biomarker Resources
		$r = $this->query("SELECT * FROM `biomarker_resources` WHERE 1");
		foreach ($r as $res) {
			$resources[$res['biomarker_resources']['URL']] = $res['biomarker_resources']['description'];
		}
		
		// Biomarker Study Data Resources
		$r = $this->query("SELECT * FROM `biomarker_study_data_resources` WHERE 1");
		foreach ($r as $res) {
			$resources[$res['biomarker_study_data_resources']['URL']] = $res['biomarker_study_data_resources']['description'];
		}
		
		// Organ Data Resources
		$r = $this->query("SELECT * FROM `organ_data_resources` WHERE 1");
		foreach ($r as $res) {
			$resources[$res['organ_data_resources']['URL']] = $res['organ_data_resources']['description'];
		}
		
		// Study Data Resources
		$r = $this->query("SELECT * FROM `study_data_resources` WHERE 1");
		foreach ($r as $res) {
			$resources[$res['study_data_resources']['URL']] = $res['study_data_resources']['description'];
		}
		
		// Study Resources
		$r = $this->query("SELECT * FROM `study_resources` WHERE 1");foreach ($r as $res) {
			$resources[$res['study_resources']['URL']] = $res['study_resources']['description'];
		}
	
		return ($resources);
	}
}
?>