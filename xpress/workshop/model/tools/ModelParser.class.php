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

require_once("XParse.class.php");
require_once("core/ModelObject.class.php");
require_once("core/ModelObjectAttribute.class.php");
require_once("core/ModelObjectRelationship.class.php");
require_once("ObjectGenerator.class.php");


class ModelParser {
	
	public $parser;					// The XParse object for parsing XML files
	public $filePath;
	public $currentLineNumber;		// The line being parsed in the XML model
	
	public $objects = array();		// The model objects themselves

	private $workingObject = '';	// The object currently being built
	private $workingRelationObject = ''; // The object for the working relation
	private $workingRelation = '';	// The relation currently being built

	public $xrefTables    = array();
	public $allAttributes = array();
	
	private $startTime;
	private $endTime;
	
	private $messages = array();	// Stores log information
	public  $output   = '';
	private $shouldAbort = false;	// Flag to terminate processing

	private function log($prefix,$msg) {
		if (!$this->shouldAbort) {
			// Append only if have not already encountered a fatal error
			if (!DEBUGGING && $prefix == "DEBUG"){return;}
			$this->messages[] = "{$prefix}: {$msg}";
		}
	}
	private function dump_log() {
		$this->output .= "<div class=\"model_parser_log\">\r\n";
		$this->output .= "ModelParser Log:<br/>\r\n";
		$this->output .= implode("<br/>\r\n",$this->messages);
		$this->output .= "</div>";
	}
	
	public function __construct($xmlFilePath) {
		$this->filePath = $xmlFilePath;
		try {
			// Create the XML Parser and override the handlers
			$this->log("INFO","Testing Provided XML Input File Path: {$xmlFilePath} ");
			$this->parser = new XParse($xmlFilePath);
			$this->parser->SetElementHandler(
				array($this,"elementStart"),
				array($this,"elementStop") );
			$this->parser->SetCharacterDataHandler(
				array($this,"characterData"));
			$this->log("INFO","XML Path Test Passed");
		} catch (Exception $e) {
			throw new XPressException(
				"File Error","XML",$e->getMessage());
		}
	}
	
	public function Init(){
		$this->startTime = microtime();
		$this->log("INFO", "ModelParser Started Parsing XML Model File @ ".date("h:i:s",time()));
		
		// Parse The File
		$this->parser->Parse();
		
		// If something went wrong, quit
		if ($this->shouldAbort) {
			$this->dump_log();
			return false;
		}
		
		// Notify that we completed
		$this->endTime = microtime();	
		$this->log("INFO","ModelParser Finished Parsing XML Model File @ ".date("h:i:s",time()));
		$this->log("INFO","ModelParser Elapsed Time: " . ($this->endTime - $this->startTime) . " Seconds.");		
		if (sizeof($this->objects) == 0) {
			$this->log("WARNING","No objects were found in: <em>{$this->filePath}</em>. "
				. "Please check that the file contains at "
				. "least one <code>object</code> definition block.");
		}
		
		// Display log messages
		$this->dump_log();
		return true;
	}
	
	public function elementStart($parser,$element_name,$element_attrs){
		$this->processTag($parser,$element_name,$element_attrs);
	}
	
	public function elementStop($parser,$element_name){
		$this->processTagStop($parser,$element_name);
	}
	
	public function characterData($parser,$data){
		// unused right now (no character data defined in model schema)
	}
	
	private function processTag($parser,$element_name,$element_attrs){
		// Save the line number being processed in case of error
		$this->currentLineNumber = xml_get_current_line_number($parser);
		
		// Determine how to process the current tag
		switch ($element_name){
			case "OBJECT":
				$this->addObject($element_attrs);
				break;
			case "ATTR":
				$this->addAttribute($element_attrs);
				break;
			case "RELATION":
				if (!isset($element_attrs['OBJECT'])){
					$this->abort("Encountered RELATION tag with "
						. "undefined OBJECT attribute on line "
						. "{$this->currentLineNumber}.");
				}
				$this->workingRelationObject = 
					$this->fixVariableName($element_attrs['OBJECT']);
				break;
			case "HAS":
				$this->addRelationship($element_attrs);
				break;
			case "RDFINFO":
				$this->addRdfInfo($element_attrs);
				break;
			case "MODEL": 
				break;
			default:
				$this->log("WARN","Encountered unrecognized tag: ".
							$element_name ." on line ".
							$this->currentLineNumber . ".");
				break;				
		}
	}
	
	private function processTagStop($parser,$element_name){
		switch ($element_name){
			case "OBJECT":
				// Add object's attributes to 'allAttributes'
				$this->allAttributes[$this->workingObject->type] = 
					$this->workingObject->namedAttributes;
				$this->commitObject();
				break;
			case "RELATION":
				$this->workingRelationObject = '';
				break;
			case "MODEL":
				// Finished parsing, calculate remote variable names
				$this->calculateRemoteVariableNames();
					
				break;
			default: break;
		}
	}
	
	private function addObject($element_attrs){
		if (!isset($element_attrs['TYPE'])){
			$this->abort("Encountered OBJECT tag with undefined "
				. "TYPE attribute on line {$this->currentLineNumber}");
		}
		
		$t = $this->fixVariableName($element_attrs['TYPE']);
		$this->workingObject = new ModelObject($t);
		$this->log("DEBUG","Working Object is now of type " 
			. "{$this->workingObject->type}<br/>");		
	}
	private function addAttribute($element_attrs){
		if (!isset($element_attrs['NAME'])){
			$this->abort("Encountered ATTR tag with undefined "
				. "NAME attribute on line {$this->currentLineNumber}");
		}
		if (strtolower($element_attrs['NAME']) == "objid") {
			$this->abort("Encountered ATTR tag with reserved "
				. "NAME attribute \"objid\" on line {$this->currentLineNumber}");
		}
		if (isset($element_attrs['PRIMARY'])) {
			$this->abort("PRIMARY attribute is deprecated for ATTR tags "
				. "as of version 0.2.0 (found on line {$this->currentLineNumber})");
		}
		
		$attr = new ModelObjectAttribute($this->fixVariableName($element_attrs['NAME']));
		list($type,$data) = explode(":",$element_attrs['TYPE']);
		if (strtoupper($type) == "STRING"){
			// Handle varchar and text types
			if (isset($data) && $data <= 255){
				$attr->type = " varchar($data) ";
			} else {
				$attr->type= " text ";
			}
		} else if (strtoupper($type) == "ENUM") {
			$vals = explode(",",$data);
			$attr->type = "ENUM(";
			for ($i =0;$i<sizeof($vals)-1;$i++){
				$attr->type .= "'{$vals[$i]}',";
			}
			if (sizeof($vals)-1 >= 0){
				$attr->type .= "'{$vals[sizeof($vals)-1]}'";
			}
			$attr->type .= ") ";
			$attr->enumValues = $vals;
			
		} else {
			// Handle simple types
			switch (strtoupper($element_attrs['TYPE'])){
				case "UINT": $attr->type =" int(10) unsigned "; break;
				case "INT" : $attr->type =" int(10) "; break;
				case "FLOAT" : $attr->type = "float "; break;
				case "DATETIME" : $attr->type = "datetime "; break;
				default: $this->abort("Unrecognized TYPE attribute "
					. "for ATTRIBUTE tag on line "
					. "{$this->currentLineNumber}");
					break;
			}
		}
		switch (strtoupper($element_attrs['NULL'])){
			case "NO": $attr->null = " NOT NULL "; break;
			case "YES": $attr->null = " NULL "; break;
			default:
				$attr->null = " NULL "; 
				$this->log("WARN","Using default value of null=NULL "
					. "for for ATTRIBUTE tag on line "
					. "{$this->currentLineNumber}");
		}
		switch (strtoupper($element_attrs['AUTOINC'])){
			case "YES": $attr->autoinc = " auto_increment "; break;
			default:break;
		}
		/**
		 * DEPRECATED AS OF VERSION 0.2.0
		 * 
		switch (strtoupper($element_attrs['PRIMARY'])){
			case "YES": 
				$attr->primary = true;
				break;
			default:
				$attr->primary = false;
				break;
		}
		**/
		switch (strtoupper($element_attrs['UNIQUE'])){
			case "YES":
				$attr->unique = true;
				break; 
			default: 
				$attr->unique = false;
				break;
		}
		switch (strtoupper($element_attrs['VISIBILITY'])){
			case "PRIVATE": $attr->visibility = "private"; break;
			default: $attr->visibility = "public";break;
		}
		if (isset($element_attrs['DEFAULT'])) {
			$attr->defaultValue = "DEFAULT"
				." '{$element_attrs['DEFAULT']}' ";		// SQL
		}
		if (isset($element_attrs['COMMENT'])) {
			$attr->comment = $element_attrs['COMMENT']; // SQL
		}
		
		// Add the poplulated attribute to the Working Object
		$this->workingObject->addAttribute($attr);
		$this->log("DEBUG","Added attribute: " . $attr->toString());
	}
	private function addRelationship($element_attrs){
		if ($this->workingRelationObject == ''){
			$this->abort("Working Relation Object is undefined.");
		}
		
		$rel = new ModelObjectRelationship(
			$this->fixVariableName($element_attrs['CLASS']),
			$element_attrs['QUANTITY'],
			$element_attrs['TYPE']);
		if (isset($element_attrs['NAME'])){
			$rel->setVariableName(
				$this->fixVariableName($element_attrs['NAME']));
		}
		if (isset($element_attrs['ONDELETEPEER'])){
			switch (strtoupper($element_attrs['ONDELETEPEER'])){
				case "DELETE":
					$rel->onDeletePeer = "delete";
					break;
				default:
					break;
			}
		}
		if (isset($element_attrs['VISIBILITY'])){
			switch (strtoupper($element_attrs['VISIBILITY'])){
				case "PRIVATE":
					$rel->setVisibility("private");
					break;
				default:
					$rel->setVisibility("public");
					break;
			}
		}
		if (isset($this->objects[$this->workingRelationObject])){
			// Add the relationship to the current object
			$this->objects[$this->workingRelationObject]->addRelationship($rel);
			// Add XREF information to xreftables array
			$local = $this->objects[$this->workingRelationObject]->type;
			$remote = $rel->objectType;
			if ($local == $remote){
				/**
				 * Here we are dealing with a relationship between two objects
				 * of the SAME type, an example being two WORD objects that 
				 * have a relationship between them of type SYNONYM. 
				 */
				if (empty($this->xrefTables[$local."_".$local])){
					$this->xrefTables[$local."_".$local] = array();
					$this->log("DEBUG","adding XREF info for table xr_{$local}_{$local}");
				} 
				// Add the relationship info to the table
				$this->xrefTables[$local."_".$local][] = $local.".".$rel->variableName;

				// Say we completed successfully
				$this->log("DEBUG","Processed Relationship between {$local} and {$local} ({$rel->variableName})");
			} else {
				if (empty($this->xrefTables[$remote."_".$local])){
					$this->xrefTables[$local."_".$remote] = array();
					$this->log("DEBUG","adding XREF info for table: xr_{$local}_{$remote}");
					// Add the relationship info to the table
					$this->xrefTables[$local."_".$remote][] = $local.".".$rel->variableName;
				} else {
					// Add the relationship info to the table
					$this->xrefTables[$remote."_".$local][] = $local.".".$rel->variableName;
				}
				
				// Say we completed successfully
				$this->log("DEBUG","Processed Relationship between {$local} and {$remote}");
			}
		} else {
			$this->log("WARNING","Unable to assign relationship. Have all objects been defined yet?");
		}
		
	}
	private function addRdfInfo($element_attrs){
		// Error Checking First
		if (!isset($element_attrs['OBJECT'])){
			$this->abort("Found RDFINFO tag with no OBJECT attribute on line "
				. "{$this->currentLineNumber}.");
		}
		
		if (!isset($element_attrs['RESOURCEPATH'])){
			$this->abort("Found RDFINFO tag with no RESOURCEPATH attribute on line "
				. "{$this->currentLineNumber}.");
		}	
			
		if (!isset($element_attrs['PARAMETERS'])){
			$this->abort("Found RDFINFO tag with no PARAMETERS attribute on line "
				. "{$this->currentLineNumber}.");
		}
		
		// Break parameters into an array
		$pairs = explode(",",$element_attrs['PARAMETERS']); // separate into key:value pairs
		$parms = array();
		foreach ($pairs as $kv){						// for each key:value pair:
			list($key,$value) = explode(":",$kv);		// separate into key & value
			$parms[$key] = $value;						// create assoc. array entry
		}
		// Should the object itself be processed (isset(hidden)=false or hidden=no), or is it
		// just a pointer for related objects (hidden=yes)
		$hidden = false;
		switch (strtoupper($element_attrs['HIDDEN'])){
			case "YES": $hidden = true; break;
			default: break;
		}
		// Add data to specified object

		$o = $this->fixVariableName($element_attrs['OBJECT']);
				
		if (empty($this->objects[$o])){
			$this->abort("Tried to add RDF information to a non-existant object "
				. " ({$element_attrs['OBJECT']}) on line "
				. " {$this->currentLineNumber}.");
		}
		$this->objects[$o ]->rdfDefined = true;
		$this->objects[$o ]->rdfPassthrough  = $hidden;
		$this->objects[$o ]->rdfResourcePath = $element_attrs['RESOURCEPATH'];
		$this->objects[$o ]->rdfParameters = $parms;
		
		// Report on what we've accomplished
		$this->log("DEBUG","Processed RDF Info for '{$element_attrs['OBJECT']}' ");
		
	}
	private function commitObject(){
		// Add the working object to the array of objects, indexed by type
		$this->objects[$this->workingObject->type] = $this->workingObject;
		$this->workingObject = '';
	}
	
	public function buildXrefTableMap($object){
		$local = $object->type;
		$xtables = array();
 
		
		foreach ($this->xrefTables as $xtablename => $data){
			foreach ($object->relationships as $rel){
				$remote = $rel->objectType;
				$enums = array("local" => array(),"remote" => array());
				if ($xtablename == "{$local}_{$remote}" || 
					$xtablename == "{$remote}_{$local}"){
					//split the values by '.'
					foreach ($data as $variable){
						list($obj,$var) = explode(".",$variable);
						if ($obj == $local){
							$enums["local"][] = array("var"=>$var,"relation"=>$rel->relation,"quantity"=>$rel->quantity);
						}
						if ($obj == $remote){
							$enums["remote"][] = $var;
						}
					}
					$xtables[$remote] = array("foreignType" => $remote,
									   "tableName" => $xtablename,
									   "vars" => $enums);
				}
			}
		}
		return $xtables;
	}
	
	public function buildXrefTablesSql(){
		$str = "";
		foreach ($this->xrefTables as $xtablename => $data){

			// split the name by a '_' (guaranteed not to exist due to replacement rules)
			list($obj1,$obj2) = explode("_",$xtablename);
			
			// split the data by a '.' to get local and remote variable names for enums
			$enums = array();
			foreach ($data as $variable){
				list($obj,$var) = explode(".",$variable);
				if ($obj == $obj1){
					$enums['local'][] = "'{$var}'";
				}
				if ($obj == $obj2){
					$enums['remote'][] = "'{$var}'";
				}
			}
			//$this->log("XREFSQL","data is : " . print_r($data,true));
			
			if ($obj1 == $obj2){
				$str .= "CREATE TABLE xr_{$xtablename} (\r\n";
				$str .= "\t`{$obj1}ID1` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'the unique ID of the first{$obj1}',\r\n";
				$str .= "\t`{$obj1}ID2` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'the unique ID of the second {$obj1}',\r\n";
				if (sizeof($enums['local']) == 0){
					$str .= "\t`Var` enum('null') DEFAULT 'null' COMMENT 'the{$obj1} variable for this relationship',\r\n";
				} else {
					$str .= "\t`Var` enum('null',".implode(",",$enums['local']).") DEFAULT 'null' COMMENT 'the {$obj1} variable for this relationship',\r\n";
				}
				$str .= "\tPRIMARY KEY (`{$obj1}ID1`,`{$obj1}ID2`,`Var`)\r\n";
				$str .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 ;\r\n\r\n";		 
				
			} else {
				$str .= "CREATE TABLE xr_{$xtablename} (\r\n";
				$str .= "\t`{$obj1}ID` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'the unique ID of the {$obj1}',\r\n";
				$str .= "\t`{$obj2}ID` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'the unique ID of the {$obj2}',\r\n";
				if (sizeof($enums['local']) == 0){
					$str .= "\t`{$obj1}Var` enum('null') DEFAULT 'null' COMMENT 'the {$obj1} variable for this relationship',\r\n";
				} else {
					$str .= "\t`{$obj1}Var` enum('null',".implode(",",$enums['local']).") DEFAULT 'null' COMMENT 'the {$obj1} variable for this relationship',\r\n";
				}
				if (sizeof($enums['remote']) == 0){
					$str .= "\t`{$obj2}Var` enum('null') DEFAULT 'null' COMMENT 'the {$obj2} variable for this relationship',\r\n";
				} else { 
					$str .= "\t`{$obj2}Var` enum('null',".implode(",",$enums['remote']).") DEFAULT 'null' COMMENT 'the {$obj2} variable for this relationship',\r\n";
				}
				$str .= "\tPRIMARY KEY (`{$obj1}ID`,`{$obj2}ID`,`{$obj1}Var`,`{$obj2}Var`)\r\n";
				$str .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 ;\r\n\r\n";		
			}
		
		}
		return $str;
		
	}
	
	private function createOctopusTable($object){
		$t = new dbTable($object->type);
		foreach ($object->attributes as $attr){
			$f = new dbField();
			$f->name = $attr->name;
			$f->type = $attr->type;
			$f->null = ($attr->null)? "YES" : "NO";
			$f->key  = ($attr->primary)? "PRI" : (($attr->unique)? "UNI" : "");
			$f->defaultValue = $attr->defaultValue;
			$f->extra = ($attr->autoinc)? "auto_increment" : "";
			$f->action = "NONE";
			$f->actionDetails = "";
			$t->fields[$f->name] = $f;  // Add the field
		}
		return $t;	// Return the built table object
	}

	
	private function abort($message){
		$this->log("ERROR",$message);
		$this->log("ABORT","Exiting due to previous errors.");
		$this->shouldAbort = true;
	}
	
	private function fixVariableName($name) {
		return preg_replace("/ /","",ucwords(strtr($name,"_"," ")));

	}
	
	private function calculateRemoteVariableNames(){
		foreach ($this->objects as $obj){
			foreach ($obj->relationships as $rel){
				if ($rel->remoteVariableName != ''){continue;}
				$remoteType = $rel->objectType;
				foreach ($this->objects as $obj2){
					if ($obj2->type == $remoteType){
						foreach ($obj2->relationships as $rel2){
							if ($rel2->objectType == $obj->type){
								$rel->setRemoteVariableName(preg_replace("/ID[0-9]*$/","",$rel2->variableName));
								$rel->setRemoteVariableClass($remoteType);
								$rel2->setRemoteVariableName(preg_replace("/ID[0-9]*$/","",$rel->variableName));
								$rel2->setRemoteVariableClass($obj->type);
								break;
							}
						}
						break;
					}
				}
			}
		}
	}
}
?>