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

require_once("ModelParser.class.php");
require_once("AjaxInterfaceGenerator.class.php");


class Generator {
	private $root_dir   = '';
	private $xpress_root= '';
	private $dsn 		= '';
	private $pear_path  = '';
	private $start_time = '';
	private $end_time   = '';
	
	private $messages	= array();
	public  $output     = '';
	
	private function log($prefix,$msg) {
		$this->messages[] = "{$prefix}: {$msg}";	
	}
	
	private function dump_log() {
		$this->output .= "<div class=\"generator_log\">\r\n";
		$this->output .= "Generator Log:<br/>\r\n";
		$this->output .= implode("<br/>\r\n",$this->messages);
		$this->output .= "</div>";
	}
	
	private function abort($msg) {
		$this->messages[] = "ERROR: {$msg}";
		$this->messages[] = "ABORT: exiting due to previous errors.";
		$this->dump_log();
		return false;	
	}

	
	public function __construct() {
		
	}
	
	public function Init($root_dir,$dsn,$pearPath) {
		
		// Ensure that a root directory has been provided
		if ($root_dir == '') {
			return $this->abort("You must specify a project root directory in <em>xpress/config/app.config</em>");
		}
		
		// Ensure that a DSN has been provided
		if ($dsn == '') {
			return $this->abort("You must specify a Data Source Name (DSN) in <em>xpress/config/app.config</em>");	
		}
		
		// Test the provided DSN
		try {
			$db = new XPressDB($dsn);
		} catch (XPressException $e) {
			$this->log("WARNING", "An error occurred when trying to use the provided DSN to ".
				"access the database (details below). This could be because ".
				"the database has not yet been set up. However, it may also ".
				"indicate an error with the DSN string. You should double ".
				"check your DSN, then rebuild. <br/><br/>");	
		}
		
		// Ensure that the path to the PEAR library has been provided
		if ($pearPath == ''){
			return $this->abort("You must specify a directory path for the PEAR library!");
		}
			
		// Save the provided values for later use
		$this->root_dir		= $root_dir;
		$this->xpress_root	= '../..';
		$this->dsn 			= $dsn;
		$this->pear_path 	= $pearPath;
		
		return true;
	}
	
	public function generate() {
		require_once("ObjectGenerator.class.php");
		
		$this->start_time = microtime();
		
		// Create and initialize a ModelParser
		try {
			$mp = new ModelParser("{$this->xpress_root}/config/model.xmf");	
			$mp->Init();
			$this->output .= $mp->output;
		} catch (XPressException $e) {
			return $this->abort($e->toString());
		}
		
		// Build the XPress Identitiy Framework
		try {
			$xif = new ModelParser("{$this->xpress_root}/framework/identities/model/xid.xml");
			$xif->Init();
			$this->output .= $mp->output;
		} catch (XPressException $e) {
			return $this->abort($e->toString());
		}

		// Generate XIF Class Files
		foreach ($xif->objects as $obj) {
			$this->log("INFO","Generating "
				. "framework/identities/model/{$obj->type}.php");
			$xtables = $xif->buildXrefTableMap($obj);
			$generator = new ObjectGenerator($obj,
				"{$this->xpress_root}/framework/identities/model",
				$xtables,
				$xif->allAttributes,
				$xif->xrefTables);
			// Write the PHP for the object
			$generator->generate();
		}
		
				
		// Generate XIF SQL Schema 
		$this->log("INFO","Generating SQL Schema File: framework/identities/model/xid.sql");
		$xifsql = new FileWriter("{$this->xpress_root}/framework/identities/model/xid.sql");
		foreach ($xif->objects as $obj) {
			$xifsql->write($obj->toSQL());
		}
		$xifsql->write($xif->buildXrefTablesSql());	// xref tables sql
		$xifsql->close();
		
		// Generate PHP Object Class Files
		$this->log("INFO","Generating PHP Object Class Files");
		$this->log("INFO","Will generate " 
			. sizeof($mp->objects) . " class files:");
		foreach ($mp->objects as $obj) {
			$this->log("INFO","Generating objects/{$obj->type}.php");
			$xtables = $mp->buildXrefTableMap($obj);
			$generator = new ObjectGenerator($obj,
				$this->xpress_root.'/objects',
				$xtables,
				$mp->allAttributes,
				$mp->xrefTables);
			// Write the PHP for the object
			$generator->generate();
		}
		
		// Compile Object Class Files into single file
		$fw = new PHPFileWriter("{$this->xpress_root}/objects/compiled.php");
		foreach ($mp->objects as $object) {
			$c = file_get_contents("{$this->xpress_root}/objects/{$object->type}.php");
			$cs= str_replace("<?php","/** {$object->type} **/",$c);
			$cs= str_replace("?>","",$cs);
			$fw->write($cs);
		}
		$fw->close();
		
		// Generate Model SQL Schema
		$this->log("INFO","Generating SQL Schema File");
		$sqlWriter = new FileWriter("{$this->xpress_root}/config/model.sql");
		foreach ($mp->objects as $obj) {
			$sqlWriter->write($obj->toSQL());
		}
		$sqlWriter->write($mp->buildXrefTablesSql());	// xref tables sql
		// Append XPress Identities Framework Schema to sql file
		$this->log("INFO","Adding XPress Identities Framework to generated SQL.");
		$sqlWriter->write(file_get_contents("{$this->xpress_root}/framework/identities/model/xid.sql"));
		
		$sqlWriter->close();
		
		
		// Create the Object Broker File (xpress/ObjectBroker.class.php)
		$this->log("INFO","Writing Object Broker class file: framework/ObjectBroker.class.php");
		$s =  "class ObjectBroker {\r\n"
			. "\tpublic static function get(\$className,\$objId) {\r\n"
			. "\t\tswitch (\$className) {\r\n";
		foreach ($mp->objects as $obj) {
			$s .= "\t\t\tcase \"{$obj->type}\":\r\n"
				. "\t\t\t\treturn {$obj->type}Factory::retrieve(\$objId);\r\n"
				. "\t\t\t\tbreak;\r\n";
		}
		$s .= "\t\t\tdefault:\r\n"
			. "\t\t\t\treturn false;\r\n"
			. "\t\t\t\tbreak;\r\n"
			. "\t\t}\r\n"
			. "\t}\r\n"
			. "}\r\n\r\n";
		$fw = new PHPFileWriter("{$this->xpress_root}/framework/ObjectBroker.class.php");
		$fw->write($s); 	// write the object broker class
		$fw->close();
		
		// Generate AJAX Handler files (PHP)
		$update = AjaxInterfaceGenerator::generateUpdateFile($mp);
		$fw = new PHPFileWriter(
			"{$this->xpress_root}/extensions/ajax/ajax_update.inc.php");
		$fw->write($update);
		$fw->close();
		
		// Record End time
		$this->end_time = microtime();
		$this->dump_log();

		$this->output .= "<br/><br/>";
		$this->output .= "<div style=\"background-color:#cfc;padding:5px;border:1px solid #3a3;\">";
		$this->output .= "<strong>Finished</strong>. All Files Generated in ".($this->microtime_diff($this->start_time,$this->end_time)) . " Seconds";
		$this->output .= "<br/>PHP Model Object Class Files can be found here: "
			. "<em>xpress/objects/*.php</em><br/>";
		$this->output .= "SQL File can be found here: "
			. "<em>xpress/config/model.sql</em><br/>";
		$this->output .= "</div>";
		$this->output .= "<br/><br/>";	
	}

	private function singularizeName($originalName){
		// Determine spelling of singular variable name
		// [*]ies  -> 'y'
		// [*]ings -> 'ing'
		// [*]ions -> 'ion'
		// [*]ses  -> 's'
		// [*]ces  -> 'ce'
		// [*]s    -> ''
		// Procedure: first convert trailing 's' to '@' symbol
		$name = preg_replace("/s$/","@",$originalName);
		// Then, replace common endings
		$name = preg_replace("/ie@$/","y",$name);
		$name = preg_replace("/se@$/","s",$name);
		$name = preg_replace("/ce@$/","ce",$name);
		$name = preg_replace("/ion@$/","ion",$name);
		$name = preg_replace("/ing@$/","ing",$name);
		// Finally, trim and format according to rules
		$name = preg_replace("/ /","",ucwords(preg_replace("/_/"," ",rtrim($name,"@"))));
		return $name;
	}

	private function microtime_diff($a, $b) {
	 	list($a_dec, $a_sec) = explode(" ", $a);
		list($b_dec, $b_sec) = explode(" ", $b);
		return $b_sec - $a_sec + $b_dec - $a_dec;
	}

}