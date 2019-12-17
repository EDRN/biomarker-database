<?php
class Term extends AppModel {
	
	
	public $hasAndBelongsToMany = array(
	
		/*
		 * A 'Term' object HAS AND BELONGS TO MANY 'OrganData' objects
		 */
		'OrganData' => array(
			'className' => 'OrganData',
			'join_table'=> 'organ_datas_terms',
			'foreignKey'=> 'term_id',
			'associationForeignKey' => 'organ_data_id'
		),
	);
	
	var $actsAs = array('ExtendAssociations');

}