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
class ModelObjectRelationship {
	
	// quantity: one | many
	// relation: child | parent | peer
	// lazy: true | false
	
	public $objectType = '';
	public $quantity = '';
	public $relation = '';
	public $variableName = '';
	public $remoteVariableName = '';
	public $remoteVariableClass = '';
	public $defaultValue = '';
	public $onDeletePeer = '';
	public $visibility = "public";
	
	public $foreignKeys = array();
	
	public function __construct($objectType,$quantity="one",$relation="child"){
		$this->objectType = $objectType;
		$this->quantity   = $quantity;
		$this->relation   = $relation;
		$this->variableName = $objectType . (($this->quantity == "many")? "s":"");
		$this->remoteVariableName = '';
		$this->defaultValue = ($this->quantity == "many")? "array()" : "''";		
	}
	
	public function setVariableName($name){
		$this->variableName = $name;
	}
	public function setRemoteVariableName($name){
		$this->remoteVariableName = $name;
	}
	public function getRemoteVariableName(){
		return $this->remoteVariableName;
	}
	public function setRemoteVariableClass($className) {
		$this->remoteVariableClass = $className;
	}
	public function getRemoteVariableClass() {
		return $this->remoteVariableClass;
	}
	
	
	public function setVisibility($visibility = "public"){
		$this->visibility = $visibility;
	}
	
	public function setDefaultValue($value = ''){
		$this->defaultValue = $value;
	}

	public function addForeignKey($key,$value){
		$this->foreignKeys[] = array("native" => $key,"foreign" => $value);
	}
}
?>