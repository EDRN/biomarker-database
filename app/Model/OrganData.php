<?php
class OrganData extends AppModel
{

	public $belongsTo = array(
		/*
		 * An 'OrganData' object BELONGS TO one 'Biomarker' object
		 */
		'Biomarker' => array(
			'className' => 'Biomarker',
			'foreignKey'=> 'biomarker_id'
		),
		/*
		 * An 'OrganData' object BELONGS TO one 'Organ' object
		 */
		'Organ' => array(
			'className' => 'Organ',
			'foreignKey'=> 'organ_id'
		)
	);
	
	public $hasMany = array(
		/*
		 * An 'OrganData' object HAS MANY 'StudyData' objects
		 */
		'StudyData' => array(
			'className' => 'StudyData',
			'foreignKey'=> 'organ_data_id',
			'dependent' => true
		),
		'OrganDataResource' => array(
			'className' => 'OrganDataResource',
			'foreignKey'=> 'organ_data_id',
			'dependent' => true
		)
	);
	
	public $hasAndBelongsToMany = array(
	
		/*
		 * A 'OrganData' object HAS AND BELONGS TO MANY 'Publication' objects
		 */
		'Publication' => array(
			'className' => 'Publication',
			'join_table'=> 'publications_organ_datas',
			'foreignKey'=> 'organ_data_id',
			'associationForeignKey'=>'publication_id',
			'unique'=>true
		),
		
		/*
		 * A 'OrganData' object HAS AND BELONGS TO MANY 'Term' objects
		 */
		'Term' => array(
			'className' => 'Term',
			'join_table'=> 'organ_datas_terms',
			'foreignKey'=> 'organ_data_id',
			'associationForeignKey'=>'term_id',
			'unique'=>true
		),
	
	);
	
	var $actsAs = array('ExtendAssociations');
	
	public function readACL($id) {
		$q = "SELECT * FROM `acl` WHERE `objectType`='biomarkerorgan' AND `objectId`='{$id}' ";
		return $this->query($q);	
	}
	
}
?>