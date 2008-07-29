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
	}
?>