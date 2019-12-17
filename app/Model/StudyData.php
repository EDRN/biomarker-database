<?php
class StudyData extends AppModel {
	
	public $belongsTo = array(
	
		/*
		 * A 'StudyData' object BELONGS TO one 'OrganData' object
		 */
		'OrganData' => array(
			'className'  => 'OrganData',
			'foreignKey' => 'organ_data_id'
		),
			
		/*
		 * A 'StudyData' object BELONGS TO one 'Study' object
		 */
		'Study' => array(
			'className'  => 'Study',
			'foreignKey' => 'study_id' 
		)
	
	);
	
	public $hasMany = array(
	
		/*
		 * A 'StudyData' object HAS MANY 'StudyDataResource' objects
		 */
		'StudyDataResource' => array(
			'className' => 'StudyDataResource',
			'foreignKey'=> 'study_data_id',
			'dependent' => true
		)
	
	);
	
	public $hasAndBelongsToMany = array(
	
		/*
		 * A 'StudyData' object HAS AND BELONGS TO MANY 'Publication' objects
		 */
		'Publication' => array(
			'className' => 'Publication',
			'join_table'=> 'publications_study_datas',
			'foreignKey'=> 'study_data_id',
			'associationForeignKey'=>'publication_id',
			'unique'=>true
		),
		/*
		 * A 'StudyData' object HAS AND BELONGS TO MANY 'Sensitivity' objects
		 */
		'Sensitivity' => array(
			'className' => 'Sensitivity',
			'join_table'=> 'sensitivities_study_datas',
			'foreignKey'=> 'study_data_id',
			'associationForeignKey'=>'sensitivity_id',
			'unique'=>true
		)
	
	);
	
	var $actsAs = array('ExtendAssociations'); 
	
	
}
?>