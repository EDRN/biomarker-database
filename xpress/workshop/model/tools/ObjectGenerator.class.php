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

require_once("core/ModelObject.class.php");
require_once("core/ModelObjectAttribute.class.php");
require_once("FileWriter.class.php");


class ObjectGenerator {
	
	public  $object;
	public  $outputDir;
	private $xrTableMap     = array();
	private $xrefTables 	= array();
	private $allAttributes	= array();
	
	private $objVars = "";		// the object variables class string
	private $objFactory = "";	// the object factory class string
	private $obj  = "";			// the instantiable Object class string

	
	public function __construct(&$obj,$outputDirectory,$xrefTableMap = array(),$allAttributes = array(),$xrefTables = array()){
		$this->object = &$obj;
		$this->outputDir = $outputDirectory;
		$this->xrTableMap = $xrefTableMap;
		$this->allAttributes = $allAttributes;
		$this->xrefTables = $xrefTables;
	}
	
	public function generate(){
		// Generate Content
		$this->generate_objVars();		// generate object variables class 
		$this->generate_objFactory();	// generate object factory class
		$this->generate_obj();			// generate object class

		// Finally, Write everything to the Object.php file
		$this->writeToFile();
	}
	
	private function writeToFile(){
		// Preamble
		$preamble = "";
		$startTag = "// -@-";
		$endTag   = "\t// -@-";
		// Create a new Writer
		$fw = new PHPFileWriter($this->outputDir."/{$this->object->type}.php",$preamble,$startTag,$endTag);
		
		// Write Content
		$fw->write($this->objVars); 	// write the object variables class
		$fw->write($this->objFactory);	// write the object factory class
		$fw->write($this->obj);			// write the Object class

		// Finally, close the file
		$fw->close();
	}
	
	private function generate_objVars() {
		$this->objVars = "class {$this->object->type}Vars {\r\n";
		foreach ($this->object->attributes as $attr){
			$this->objVars .= "\tconst ".strtoupper($attr->name)." = \"{$attr->name}\";\r\n";
		}
		foreach ($this->object->relationships as $rel){
			$this->objVars .= "\tconst ".strtoupper($rel->variableName)." = \"{$rel->variableName}\";\r\n";
		}
		$this->objVars .= "}\r\n\r\n";
	}
	
	private function generate_objFactory() {
		$this->objFactory = "class {$this->object->type}Factory {\r\n";
		
		/**
		 * CREATE
		 */
		$uAttrNames = array();
		foreach ($this->object->uniqueAttrs as $u){
			$uAttrNames[] = "\${$u->name}";
		}
		foreach ($this->object->relationships as $r){
			if ($r->quantity == "one" && $r->relation == "parent"){
				$uAttrNames[] = "\${$r->variableName}Id";
			}
		}
		$this->objFactory .= "\tpublic static function Create(" . implode(",",$uAttrNames) . "){\r\n";
		$this->objFactory .= "\t\t\$o = new {$this->object->type}();\r\n";
		foreach ($this->object->uniqueAttrs as $unique){
			$this->objFactory .= "\t\t\$o->{$unique->name} = \${$unique->name};\r\n";
		}
		$this->objFactory .= "\t\t\$o->save();\r\n";
		foreach ($this->object->relationships as $rel){
			if ($rel->quantity == "one" && $rel->relation == "parent"){
				$this->objFactory .= "\t\t\$o->link({$this->object->type}Vars::"
					. strtoupper($rel->variableName)
					. ",\${$rel->variableName}Id"
					. (($rel->remoteVariableName == '')
						? '' 
						: ",{$rel->remoteVariableClass}Vars::"
							. strtoupper($rel->remoteVariableName))
					. ");\r\n";
			}
		}
		$this->objFactory .= "\t\treturn \$o;\r\n";
		$this->objFactory .= "\t}\r\n";
		

		/*******
		 * RETREIVE
		 *
		$this->objFactory .= "\tpublic static function retrieve(\$objId,\$fetchStrategy = XPress::FETCH_LOCAL) {\r\n";
		$this->objFactory .= "\t\tif (\$objId == 0) { return false; /* must not be zero *//* }\r\n"
					. "\t\t\$o = new {$this->object->type}(\$objId);\r\n"
					. "\t\tif (\$fetchStrategy == XPress::FETCH_NONE) {\r\n"
					. "\t\t\treturn \$o;\r\n"
					. "\t\t}\r\n"
					. "\t\t\$q = \"SELECT * FROM `{$this->object->type}` WHERE objId={\$objId} LIMIT 1\";\r\n"
					. "\t\t\$r = XPress::getInstance()->getDatabase()->query(\$q);\r\n"
					. "\t\tif (\$r->numRows() != 1){\r\n"
					. "\t\t\treturn false;\r\n"
					. "\t\t} else {\r\n"
					. "\t\t\t\$result = \$r->fetchRow(DB_FETCHMODE_ASSOC);\r\n";
		foreach ($this->object->attributes as $attr){
			$this->objFactory .= "\t\t\t\$o->{$attr->name} = \$result['{$attr->name}'];\r\n";
		}
		$this->objFactory .= "\t\t\treturn \$o;\r\n"
					."\t\t}\r\n"
					."\t}\r\n\r\n";
		*****/

		/**
		 * RETREIVE BY UNIQUE ID
		 */
	
		$this->objFactory .= "\tpublic static function Retrieve(\$value,\$key = {$this->object->type}Vars::OBJID,\$fetchStrategy = XPress::FETCH_LOCAL) {\r\n"
					. "\t\t\$o = new {$this->object->type}();\r\n"
					. "\t\tswitch (\$key) {\r\n"
					. "\t\t\tcase {$this->object->type}Vars::OBJID:\r\n"
					. "\t\t\t\t\$o->objId = \$value;\r\n"
					. "\t\t\t\t\$q = \"SELECT * FROM `{$this->object->type}` WHERE `objId`=\\\"{\$value}\\\" LIMIT 1\";\r\n"
					. "\t\t\t\t\$data = XPress::getInstance()->getDatabase()->getRow(\$q);\r\n"
					. "\t\t\t\tif (DB::isError(\$data)) {return false;}\r\n"
					. "\t\t\t\tif (! is_array(\$data)) {return false;}\r\n";
		foreach ($this->object->attributes as $a){
			if (strtolower($a->name) == "objid") { continue;}
			$this->objFactory .= "\t\t\t\t\$o->set{$a->name}(\$data['{$a->name}'],false);\r\n";
		}
		$this->objFactory .= "\t\t\t\treturn \$o;\r\n"
					. "\t\t\t\tbreak;\r\n";
					
		foreach ($this->object->uniqueAttrs as $attr){
			$this->objFactory .= "\t\t\tcase {$this->object->type}Vars::".strtoupper($attr->name).":\r\n"
			. "\t\t\t\t\$o->set{$attr->name}(\$value,false);\r\n"
			. "\t\t\t\t\$q = \"SELECT * FROM `{$this->object->type}` WHERE `{$attr->name}`=\\\"{\$value}\\\" LIMIT 1\";\r\n"
			. "\t\t\t\t\$data = XPress::getInstance()->getDatabase()->getRow(\$q);\r\n"
			. "\t\t\t\tif (DB::isError(\$data)) { return false;}\r\n"
			. "\t\t\t\tif (! is_array(\$data)) {return false;}\r\n";
			foreach ($this->object->attributes as $a){
				$this->objFactory .= "\t\t\t\t\$o->set{$a->name}(\$data['{$a->name}'],false);\r\n";
			}
			$this->objFactory .= "\t\t\t\treturn \$o;\r\n"
						. "\t\t\t\tbreak;\r\n";
		}
		$this->objFactory .= "\t\t\tdefault:\r\n"
					. "\t\t\t\treturn false;\r\n"
					. "\t\t\t\tbreak;\r\n";
		$this->objFactory .= "\t\t}\r\n"
					. "\t}\r\n";
		

		$this->objFactory .= "}\r\n\r\n";
	}
	
	private function generate_obj() {
		$this->obj .= "class {$this->object->type} extends XPressObject {\r\n\r\n";
		/**
		 * OBJECT TYPE
		 */
		$this->obj .= "\tconst _TYPE = \"{$this->object->type}\";\r\n";
		
		/**
		 * ENUM VALUES Arrays (Static)
		 */
		foreach ($this->object->attributes as $attr){
			if ($attr->enumValues != array() ){
				$this->obj .= "\tpublic \${$attr->name}EnumValues = array(\"".implode("\",\"",$attr->enumValues)."\");\r\n";
			}
		}
		
		/**
		 * MEMBER VARIABLES
		 */
		foreach ($this->object->attributes as $attr){
			if (strtolower($attr->name) == "objid") {continue;}
			$this->obj .= "\t{$attr->visibility} \${$attr->name} = '';\r\n";
		}
		
		foreach ($this->object->relationships as $rel){
			$this->obj .= "\t{$rel->visibility} \${$rel->variableName} = {$rel->defaultValue};\r\n";
		}
		$this->obj .= "\r\n\r\n";
		// Function: __construct()
		$this->obj .= "\tpublic function __construct(\$objId = 0) {\r\n"
					. "\t\t//echo \"creating object of type {$this->object->type}<br/>\";\r\n"
					. "\t\t\$this->objId = \$objId;\r\n"
					. "\t}\r\n";
		
		/**
		 * ACCESSOR FUNCTIONS (get)
		 */
		$this->obj .= "\r\n\t// Accessor Functions\r\n";
		foreach ($this->object->attributes as $attr){
			$this->obj .= "\tpublic function get".ucfirst($attr->name)."() {\r\n"
						. "\t\t return \$this->{$attr->name};\r\n"
						. "\t}\r\n";
						
		}
		foreach ($this->object->relationships as $rel){
			$varname = ucfirst($rel->variableName);
			$this->obj .= "\tpublic function get{$varname}() {\r\n"
						. "\t\tif (\$this->{$rel->variableName} != "
						. (($rel->quantity == "many")? "array()" : "\"\"")
						. ") {\r\n"
						. "\t\t\treturn \$this->{$rel->variableName};\r\n"
						. "\t\t} else {\r\n"
						. "\t\t\t\$this->inflate({$this->object->type}Vars::".strtoupper($varname).");\r\n"
						. "\t\t\treturn \$this->{$rel->variableName};\r\n"
						. "\t\t}\r\n"
						. "\t}\r\n";	
		}

		/**
		 * MUTATOR FUNCTIONS (set)
		 */
		$this->obj .= "\r\n\t// Mutator Functions \r\n";
		foreach ($this->object->attributes as $attr){
			if (strtolower($attr->name) == "objid") {continue;}
			$this->obj .= "\tpublic function set".ucfirst($attr->name)."(\$value,\$bSave = true) {\r\n"
						. "\t\t\$this->{$attr->name} = \$value;\r\n"
						. "\t\tif (\$bSave){\r\n"
						. "\t\t\t\$this->save({$this->object->type}Vars::".strtoupper($attr->name).");\r\n"
						. "\t\t}\r\n"
						. "\t}\r\n";
		}
		
		$this->obj .= "\r\n\t// API\r\n";
					
		/**
		 * INFLATE
		 */
		$this->obj .= "\tprivate function inflate(\$variableName) {\r\n";
		$this->obj .= "\t\tswitch (\$variableName) {\r\n";
		foreach ($this->xrTableMap as $tablename => $data) {
			foreach ($data['vars']['local'] as $variable){
				$this->obj .= "\t\t\tcase \"{$variable['var']}\":\r\n";
				$this->obj .= "\t\t\t\t// Inflate \"{$variable['var']}\":\r\n";
				if ($data['foreignType'] == $this->object->type){
					$this->obj .= "\t\t\t\t\$q = \"SELECT {$data['foreignType']}ID2 AS objId FROM xr_{$data['tableName']} WHERE {$this->object->type}ID1 = {\$this->objId} AND Var = \\\"{$variable['var']}\\\" \";\r\n";
				} else {
					$this->obj .= "\t\t\t\t\$q = \"SELECT {$data['foreignType']}ID AS objId FROM xr_{$data['tableName']} WHERE {$this->object->type}ID = {\$this->objId} AND {$this->object->type}Var = \\\"{$variable['var']}\\\" \";\r\n";
				}
				$this->obj .= "\t\t\t\t\$ids = XPress::getInstance()->getDatabase()->getAll(\$q);\r\n";
				if ($variable['quantity'] == "many") {
					$this->obj .= "\t\t\t\t\$this->{$variable['var']} = array(); // reset before repopulation to avoid dups\r\n";	
				}
				$this->obj .= "\t\t\t\tforeach (\$ids as \$id) {\r\n"
					. "\t\t\t\t\t\$this->{$variable['var']}"
					. (($variable['quantity'] == "many")? "[]" : '')
					. " = {$data['foreignType']}Factory::retrieve(\$id[objId]);\r\n"
					. "\t\t\t\t}\r\n"
					. "\t\t\t\tbreak;\r\n";
			}
		}
		$this->obj .= "\t\t\tdefault:\r\n"
					. "\t\t\t\treturn false;\r\n"
					. "\t\t}\r\n"
					. "\t\treturn true;\r\n"
					. "\t}\r\n";
		
		/**
		 * DEFLATE
		 */
		$this->obj .= "\tpublic function deflate(){\r\n"
					. "\t\t// reset all member variables to initial settings\r\n";
		foreach ($this->object->attributes as $attr){
			$this->obj .= "\t\t\$this->{$attr->name} = '';\r\n";
		}
		foreach ($this->object->relationships as $rel){
			$this->obj .= "\t\t\$this->{$rel->variableName} = {$rel->defaultValue};\r\n";	
		}
		$this->obj .= "\t}\r\n";			

		/**
		 * SAVE
		 */
		$this->obj .= "\tpublic function save(\$attr = null) {\r\n"
					. "\t\tif (\$this->objId == 0){\r\n"
					. "\t\t\t// Insert a new object into the db\r\n"
					. "\t\t\t\$q = \"INSERT INTO `{$this->object->type}` \";\r\n";
		$a = array();
		foreach ($this->object->attributes as $attr){
			if ($attr->name == "objId") {
				$a[] = "\"\"";
			} else {
				$a[] = "\"'.\$this->{$attr->name}.'\"";
			}
		}
		$this->obj .= "\t\t\t\$q .= 'VALUES(" . implode(",",$a) . ") ';\r\n"
					. "\t\t\t\$r = XPress::getInstance()->getDatabase()->query(\$q);\r\n"
					. "\t\t\t\$this->objId = XPress::getInstance()->getDatabase()->getOne(\"SELECT LAST_INSERT_ID() FROM `{$this->object->type}`\");\r\n"
					. "\t\t} else {\r\n"
					. "\t\t\tif (\$attr != null) {\r\n"
					. "\t\t\t\t// Update the given field of an existing object in the db\r\n"
					. "\t\t\t\t\$q = \"UPDATE `{$this->object->type}` SET `{\$attr}`=\\\"{\$this->\$attr}\\\" WHERE `objId` = \$this->objId\";\r\n"
					. "\t\t\t} else {\r\n"
					. "\t\t\t\t// Update all fields of an existing object in the db\r\n"
					. "\t\t\t\t\$q = \"UPDATE `{$this->object->type}` SET \";\r\n";
		$attrCount = sizeof($this->object->attributes);
		$attrNow   = 0;
		foreach ($this->object->attributes as $attr){
			$this->obj .= "\t\t\t\t\$q .= \"`{$attr->name}`=\\\"{\$this->{$attr->name}}\\\"";	
			if ($attrNow++ < ($attrCount -1)){
				$this->obj .= ",\"; \r\n";
			} else {
				$this->obj .= " \";\r\n\t\t\t\t\$q .= \"WHERE `objId` = \$this->objId \";\r\n";
			}
		}
		$this->obj .= "\t\t\t}\r\n";
		$this->obj .= "\t\t\t\$r = XPress::getInstance()->getDatabase()->query(\$q);\r\n";
		$this->obj .= "\t\t}\r\n"
					. "\t}\r\n";
					
		/**
		 * DELETE
		 */
		$this->obj .= "\tpublic function delete() {\r\n";
		$this->obj .= "\t\t//Delete this object's child objects\r\n";
		foreach($this->object->relationships as $rel){
			if ($rel->relation != "child"){continue;} //only delete children
			if ($rel->quantity == "one"){
				$this->obj .= "\t\tif (\$this->{$rel->variableName}){\$this->{$rel->variableName}->delete();}\r\n";
			} else {
				$this->obj .= "\t\tforeach (\$this->get{$rel->variableName}() as \$obj){\r\n"
							. "\t\t\t\$obj->delete();\r\n"
							. "\t\t}\r\n";
			}			
		}
		$this->obj .= "\r\n";
		$this->obj .= "\t\t//Intelligently unlink this object from any other objects\r\n";
		foreach($this->object->relationships as $rel){
			$this->obj .= "\t\t\$this->unlink({$this->object->type}Vars::".strtoupper($rel->variableName).");\r\n";
		}
		
		$this->obj .= "\r\n"
					. "\t\t//Signal objects that link to this object to unlink\r\n"
					. "\t\t// (this covers the case in which a relationship is only 1-directional, where\r\n"
					. "\t\t// this object has no idea its being pointed to by something)\r\n";
		foreach ($this->xrefTables as $xtablename => $data) {
			// split the name by a '_' (guaranteed not to exist due to replacement rules)
			list($obj1,$obj2) = explode("_",$xtablename);
			if (($this->object->type == $obj1) XOR ($this->object->type == $obj2) ) {
				// The relation is between objects of different types, use "{type}ID as the var name
				$this->obj .= "\t\t\$r = XPress::getInstance()->getDatabase()->query(\"DELETE FROM xr_{$xtablename} "
							. "WHERE `{$this->object->type}ID`={\$this->objId}\");\r\n";
			}
			if (($this->object->type == $obj1) AND ($obj1 == $obj2) ) {
				// The relation is between objects of the same type, use "{type}ID{1,2} as the var name
				$this->obj .= "\t\t\$r = XPress::getInstance()->getDatabase()->query(\"DELETE FROM xr_{$xtablename} "
							. "WHERE (`{$this->object->type}ID1`={\$this->objId} "
							. "OR `{$this->object->type}ID2`={\$this->objId})\");\r\n";
			}
		}
		
		$this->obj .= "\r\n"
					. "\t\t//Delete object from the database\r\n"
					. "\t\t\$r = XPress::getInstance()->getDatabase()->query(\"DELETE FROM {$this->object->type} WHERE `objId` = \$this->objId \");\r\n"
					. "\t\t\$this->deflate();\r\n";
		$this->obj .= "\t}\r\n";
		
		/**
		 * _GETTYPE
		 */
		$this->obj .= "\tpublic function _getType(){\r\n"
					. "\t\treturn {$this->object->type}::_TYPE; //{$this->object->type}\r\n"
					. "\t}\r\n";
					
		/**
		 * LINK
		 */
		$this->obj .= "\tpublic function link(\$variable,\$remoteID,\$remoteVar=''){\r\n"
					. "\t\tswitch (\$variable){\r\n";
		foreach ($this->xrTableMap as $xtablename => $data){
			foreach ($data['vars']['local'] as $variable){
				$this->obj .= "\t\t\tcase \"{$variable['var']}\":\r\n";
				$this->obj .= "\t\t\t\t\$test = \"SELECT COUNT(*) FROM {$data['foreignType']} WHERE objId=\\\"{\$remoteID}\\\" \";\r\n ";
				if ($data['foreignType'] == $this->object->type){
					$this->obj .= "\t\t\t\t\$q  = \"SELECT COUNT(*) FROM xr_{$data['tableName']} WHERE {$this->object->type}ID1=\$this->objId AND {$this->object->type}ID2=\$remoteID \";\r\n";
					$this->obj .= "\t\t\t\t\$q0 = \"INSERT INTO xr_{$data['tableName']} ({$this->object->type}ID1,{$data['foreignType']}ID2,Var) VALUES(\$this->objId,\$remoteID,\\\"{$variable['var']}\\\");\";\r\n";
					$this->obj .= "\t\t\t\t\$q1 = \"UPDATE xr{$data['tableName']} SET Var=\\\"{\$variable}\\\" WHERE {$this->object->type}ID1=\$this->objId AND {$this->object->type}ID2=\$remoteID LIMIT 1 \";\r\n";
				} else {
					$this->obj .= "\t\t\t\t\$q  = \"SELECT COUNT(*) FROM xr_{$data['tableName']} WHERE {$this->object->type}ID=\$this->objId AND {$data['foreignType']}ID=\$remoteID \";\r\n";
					$this->obj .= "\t\t\t\t\$q0 = \"INSERT INTO xr_{$data['tableName']} ({$this->object->type}ID,{$data['foreignType']}ID,{$this->object->type}Var\".((\$remoteVar == '')? '' : ',{$data['foreignType']}Var').\") VALUES(\$this->objId,\$remoteID,\\\"{$variable['var']}\\\"\".((\$remoteVar == '')? '' : \",\\\"{\$remoteVar}\\\"\").\");\";\r\n";
					$this->obj .= "\t\t\t\t\$q1 = \"UPDATE xr_{$data['tableName']} SET {$this->object->type}Var=\\\"{\$variable}\\\" \".((\$remoteVar == '')? '' : ', {$data['foreignType']}Var=\"{\$remoteVar}\" ').\" WHERE {$this->object->type}ID=\$this->objId AND {$data['foreignType']}ID=\$remoteID LIMIT 1 \";\r\n";
				}
				$this->obj .= "\t\t\t\tbreak;\r\n";
			}
		}
		$this->obj  .= "\t\t\tdefault:\r\n"
					.  "\t\t\t\tbreak;\r\n"
					.  "\t\t}\r\n"
					.  "\t\tif (1 != XPress::getInstance()->getDatabase()->getOne(\$test)) {\r\n"
					.  "\t\t\treturn false; // The requested remote id does not exist!\r\n"
					.  "\t\t}\r\n"
					.  "\t\t\$count  = XPress::getInstance()->getDatabase()->getOne(\$q);\r\n"
					.  "\t\tif (\$count == 0){\r\n"
					.  "\t\t\tXPress::getInstance()->getDatabase()->query(\$q0);\r\n"
					.  "\t\t} else {\r\n"
					.  "\t\t\tXPress::getInstance()->getDatabase()->query(\$q1);\r\n"
					.  "\t\t}\r\n"
					.  "\t\treturn true;\r\n"
					.  "\t}\r\n";
	
		/**
		 * UNLINK
		 */
		$this->obj .= "\tpublic function unlink(\$variable,\$remoteIDs = ''){\r\n"
					. "\t\tswitch (\$variable){\r\n";
		foreach ($this->xrTableMap as $xtablename => $data){
			foreach ($data['vars']['local'] as $variable){
				$this->obj .= "\t\t\tcase \"{$variable['var']}\":\r\n";
				if ($data['foreignType'] == $this->object->type){
					$this->obj .= "\t\t\t\t\$q = \"DELETE FROM xr_{$data['tableName']} WHERE {$this->object->type}ID1 = \$this->objId \".((empty(\$remoteIDs)) ? '' : (\" AND {$data['foreignType']}ID2 \". ((is_array(\$remoteIDs))? \" IN (\".implode(',',\$remoteIDs).\") . \" : \" = \$remoteIDs \"))). \" AND Var = \\\"{$variable['var']}\\\" LIMIT 1\";\r\n";
				} else {
					$this->obj .= "\t\t\t\t\$q = \"DELETE FROM xr_{$data['tableName']} WHERE {$this->object->type}ID = \$this->objId \".((empty(\$remoteIDs)) ? '' : (\" AND {$data['foreignType']}ID \". ((is_array(\$remoteIDs))? \" IN (\".implode(',',\$remoteIDs).\") . \" : \" = \$remoteIDs \"))) .\" AND {$this->object->type}Var = \\\"{$variable['var']}\\\" \";\r\n";
				}
				$this->obj .= "\t\t\t\tbreak;\r\n";
			}
		}
		$this->obj .= "\t\t\tdefault:\r\n"
					.  "\t\t\t\tbreak;\r\n"
					.  "\t\t}\r\n"
					.  "\t\t\$r  = XPress::getInstance()->getDatabase()->query(\$q);\r\n"
					.  "\t\treturn true;\r\n"
					.  "\t}\r\n";


		/**
		 * EQUALS
		 */
		$this->obj .= "\tpublic function equals(\$objArray){\r\n"
					. "\t\tif (\$objArray == null){return false;}\r\n"
					. "\t\t//print_r(\$objArray);\r\n"
					. "\t\tforeach (\$objArray as \$obj){\r\n"
					. "\t\t\t//echo \"::EQUALS:: comparing {\$this->_getType()} WITH {\$obj->_getType()} &nbsp;<br/>\";\r\n"
					. "\t\t\t// Check object types first\r\n"
					. "\t\t\tif (\$this->_getType() == \$obj->_getType()){\r\n"
					. "\t\t\t\t// Check objId next\r\n"
					. "\t\t\t\tif (\$this->objId != \$obj->objId){continue;}\r\n";
		$this->obj .= "\t\t\t\treturn true;\r\n"
					.  "\t\t\t}\r\n"
					.  "\t\t}\r\n"
					.  "\t\treturn false;\r\n"
					.  "\t}\r\n";
		
		/**
		 * TOJSON
		 */
		$this->obj .= "\tpublic function toJSON(){\r\n"
					. "\t\treturn json_encode(\$this);\r\n"
					. "\t}\r\n";

		/**
		 * TORDF
		 */
		$this->obj .= "\tpublic function toRDF(\$namespace,\$urlBase) {\r\n";
		if (! $this->object->rdfDefined){ 
			$this->obj .= "\t\treturn \"\";\r\n\t}\r\n";
		} else {
			$this->obj .= "\t\t\$rdf = '';\r\n";

			if (! $this->object->rdfPassthrough){
				// Create RDF opener
				$this->obj .= "\t\t\$rdf .= \"<{\$namespace}:{$this->object->type} rdf:about=\\\"{\$urlBase}/{$this->object->rdfResourcePath}";
				$parmCount = 0;
				foreach ($this->object->rdfParameters as $parm => $value){
					$this->obj .= ($parmCount++ == 0)? "?" : "&amp;";
					$this->obj .= "{$parm}={\$this->{$value}}";
				}
				$this->obj .= "\\\">\\r\\n";
				// create RDF attributes
				foreach ($this->object->attributes as $attr){
					$this->obj .= "<{\$namespace}:{$attr->name}>\$this->{$attr->name}</{\$namespace}:{$attr->name}>\\r\\n";
				}
				$this->obj .= "\";\r\n";
			}
			// Call toRDF on object's relationships
			foreach ($this->object->relationships as $rel){
				if ($rel->quantity == "many"){
					$this->obj .= "\t\tforeach (\$this->{$rel->variableName} as \$r) {\r\n"
								. "\t\t\t\$rdf .= \$r->toRDFStub(\$namespace,\$urlBase);\r\n"
								. "\t\t}\r\n";
				} else {
					$this->obj .= "\t\tif (\$this->{$rel->variableName} != {$rel->defaultValue}){\$rdf .= \$this->{$rel->variableName}->toRDFStub(\$namespace,\$urlBase);}\r\n";
				}
			}
			
			if (! $this->object->rdfPassthrough){
				// Create RDF closer
				$this->obj .= "\r\n\t\t\$rdf .= \"</{\$namespace}:{$this->object->type}>\\r\\n\";\r\n";
			}
			// Return the RDF string
			$this->obj .= "\t\treturn \$rdf;\r\n";
			// Close the Function
			$this->obj .= "\t}\r\n";
		}
		
		/**
		 * TORDFSTUB
		 */
		$this->obj .= "\tpublic function toRDFStub(\$namespace,\$urlBase) {\r\n";
		if (! $this->object->rdfDefined ){
			$this->obj .= "\t\treturn \"\";\r\n\t}\r\n";
		} else {
			$this->obj .= "\t\t\$rdf = '';\r\n";
			if (! $this->object->rdfPassthrough){
				// Create RDF tag as a self-closing tag
				$this->obj .= "\t\t\$rdf .= \"<{\$namespace}:{$this->object->type} rdf:about=\\\"{\$urlBase}/{$this->object->rdfResourcePath}";
				$parmCount = 0;
				foreach ($this->object->rdfParameters as $parm => $value){
					$this->obj .= ($parmCount++ == 0)? "?" : "&amp;";
					$this->obj .= "{$parm}={\$this->{$value}}";
				}
				$this->obj .= "\\\"/>\\r\\n\";\r\n";
			} else {
				// Call toRDFStub on object's relationships
				foreach ($this->object->relationships as $rel){
					if ($rel->quantity == "many"){
						$this->obj .= "\t\tforeach (\$this->{$rel->variableName} as \$r) {\r\n"
									. "\t\t\t\$rdf .= \$r->toRDFStub(\$namespace,\$urlBase);\r\n"
									. "\t\t}\r\n";
					} else {
						$this->obj .= "\t\tif(\$this->{$rel->variableName} != {$rel->defaultValue}){\$rdf .= \$this->{$rel->variableName}->toRDFStub(\$namespace,\$urlBase);}\r\n";
					}
				}
			}
			// Return the RDF string
			$this->obj .= "\t\treturn \$rdf;\r\n";
			// Close the Function
			$this->obj .= "\t}\r\n";
		}
		
		
		
		$this->obj .= "\r\n\t// API Extensions \r\n"
					. "\t// -@-\r\n\r\n"
					. "\t/**\r\n"
					. "\t * Code in this section will NOT be overwritten when\r\n"
					. "\t * the framework is regenerated. Place any API extensions\r\n"
					. "\t * in this section. Please make sure that the two section\r\n"
					. "\t * delimiters ('API Extensions'' and 'End API Extensions')\r\n"
					. "\t * are not modified as doing so may cause your code to be\r\n"
					. "\t * overwritten when the framework is regenerated.\r\n"
					. "\t **/\r\n"
					. "\r\n\t// -@-"
					. "\r\n\t// End API Extensions --\r\n";
		

		/**
		 * CLASS CLOSE
		 */
		$this->obj .= "}\r\n\r\n";
	}
}
?>
