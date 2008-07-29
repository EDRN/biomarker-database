<?php
/**
 * 	EDRN Biomarker Database
 *  Curation Webapp
 * 
 *  Author: Andrew F. Hart (andrew.f.hart@jpl.nasa.gov)
 *  
 *  Copyright (c) 2008, California Institute of Technology. 
 *  ALL RIGHTS RESERVED. U.S. Government sponsorship acknowledged.
 * 
 */
class ModelObjectAttribute {
	public $name = '';
	public $type = '';
	public $null = false;
	public $autoinc = false;
	public $primary = false;
	public $unique  = false;
	public $visibility = '';
	public $defaultValue = '';
	public $defaultValueString = '\'\'';
	public $comment = '';
	public $enumValues = array();

	public function __construct($name){
		$this->name = $name;
	}
	
	public function toString(){
		return "[NAME: $this->name, ".
				"TYPE: $this->type, ".
				"NULL: $this->null, ".
				"AUTOINC: $this->autoinc, ".
				"PRIMARY: $this->primary, ".
				"VISIBILITY: $this->visibility, ".
				"DEFAULT VALUE: $this->defaultValue, ".
				"DEFAULT VALUE STRING: $this->defaultValueString, ".
				"COMMENT: $this->comment]";
	}
	
	public function toSQL(){
		
	}
}

?>