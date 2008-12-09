<?php
class BiomarkerStudyData extends AppModel {
	
	public $belongsTo = array(
	
		/*
		 * A 'BiomarkerStudyData' object BELONGS TO one 'Biomarker' object
		 */
		'Biomarker' => array(
			'className'  => 'Biomarker',
			'foreignKey' => 'biomarker_id'
		),
			
		/*
		 * A 'BiomarkerStudyData' object BELONGS TO one 'Study' object
		 */
		'Study' => array(
			'className'  => 'Study',
			'foreignKey' => 'study_id' 
		)
	
	);
	
	public $hasMany = array(
	
		/*
		 * A 'BiomarkerStudyData' object HAS MANY 'BiomarkerStudyDataResource' objects
		 */
		'StudyDataResource' => array(
			'className' => 'BiomarkerStudyDataResource',
			'foreignKey'=> 'biomarker_study_data_id',
			'dependent' => true
		)
	
	);
	
	public $hasAndBelongsToMany = array(
	
		/*
		 * A 'BiomarkerStudyData' object HAS AND BELONGS TO MANY 'Publication' objects
		 */
		'Publication' => array(
			'className' => 'Publication',
			'join_table'=> 'biomarker_study_datas_publications',
			'foreignKey'=> 'biomarker_study_data_id',
			'associationForeignKey'=>'publication_id',
			'unique'=>true
		),
		/*
		 * A 'BiomarkerStudyData' object HAS AND BELONGS TO MANY 'Sensitivity' objects
		 */
		'Sensitivity' => array(
			'className' => 'Sensitivity',
			'join_table'=> 'biomarker_study_datas_sensitivities',
			'foreignKey'=> 'biomarker_study_data_id',
			'associationForeignKey'=>'sensitivity_id',
			'unique'=>true
		)
	);
	
	var $actsAs = 'ExtendAssociations'; 
}
?>