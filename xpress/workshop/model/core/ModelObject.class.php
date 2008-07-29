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
class ModelObject {
	public $type;
	public $attributes = array();		// Attributes indexed numerically
	public $namedAttributes = array();	// Attributes indexed by name
	public $uniqueAttrs = array();		// Only attributes where unique=yes

	public $rdfDefined		= false;	// Has RDF info been defined for this object?
	public $rdfPassthrough	= false;	// Only process relationships?
	public $rdfResourcePath = "";		// Name of the relative path to a resource
	public $rdfParameters = array();	// URL Parameters to uniquely specify a resource 

	public $relationships = array();	// The relationships to other objects

	public function __construct($type = ''){
		if ($type != ''){
			$this->type = $type;
		}	
		
		// Insert the 'objId' variable automatically into this object
		$oid = new ModelObjectAttribute('objId');
		$oid->autoinc = " auto_increment ";
		$oid->comment = " XPress unique identifier for this object ";
		$oid->null    = " NOT NULL ";
		$oid->primary = true;
		$oid->type    = " int(10) unsigned ";
		$oid->visibility = "public";
		
		$this->addAttribute($oid);
			
	}
	
	public function setType($type){
		$this->type = type;
	}
	
	public function addAttribute($attr){
		if (isset($this->attributes[$attr->name])) { return false;}
		else {
			$this->attributes[] = $attr;
			$this->namedAttributes[$attr->name] = $attr;
			if ($attr->unique){
				$this->uniqueAttrs[] = $attr;
			}
			return true;
		}
	}
	
	public function setAttribute($label,$attr){
		$this->attributes[$label] = $attr;
	}
	
	public function addRelationship($rel){
		$this->relationships[] = $rel;
	}
	
	public function toSQL(){
		$str = "CREATE TABLE `{$this->type}` (\r\n";
		foreach($this->attributes as $attr){
			$str .= "\t`$attr->name` $attr->type $attr->null $attr->defaultValue  $attr->autoinc ";
			if ($attr->comment != ''){
				$str .= " COMMENT '$attr->comment' ";
			}
			$str .= ",\r\n";
		}
		$numPrimaryKeys = sizeof($this->primaryKeys);
		$numUniqueKeys  = sizeof($this->uniqueAttrs);
		
		// PRIMARY keys
		$str .= "\tPRIMARY KEY (`objId`)";
		
		$uks = "";	// unique keys text				
		// UNIQUE keys
		for ($i=0;$i<$numUniqueKeys -1;$i++){
			$uks .= "\tUNIQUE KEY `{$this->uniqueAttrs[$i]->name}` (`{$this->uniqueAttrs[$i]->name}`),\r\n";
		}
		if ($numUniqueKeys-1 >= 0){
			$uks .= "\tUNIQUE KEY `{$this->uniqueAttrs[$numUniqueKeys-1]->name}` (`{$this->uniqueAttrs[$numUniqueKeys-1]->name}`)\r\n";
		}
		
		if ($uks != ""){
			$str .= ",\r\n$uks";
		} else {
			// nothing to add (no unique keys exist)
		}
		
		$str .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1; \r\n\r\n";
		return $str;
	}
}
?>