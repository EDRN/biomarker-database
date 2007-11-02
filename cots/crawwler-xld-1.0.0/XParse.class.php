<?php

/*
 * XParse
 * @author: ahart
 * 
 * This class is a wrapper for the bundled PHP expat
 * XML Parser. It maintains the event-driven nature
 * of the expat parser.
 * 
 */

class XParse{
	
	var $filePointer;	// The XML file to parse
	var $parser;		// The XML event-based parser

	
	function XParse($XMLpath){
		// Create the XML Parser
		$this->parser = xml_parser_create();
		
		// Get a pointer to the XML File
		try {
			$this->filePointer = fopen($XMLpath,"r");
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	
	function Destroy(){
		// Free the XML Parser (this object can not be used again)
		xml_parser_free($this->parser);		
	}
	
	function Parse(){
		while ($data=fread($this->filePointer,4096)){
  			xml_parse($this->parser,$data,feof($this->filePointer)) or  
  				die (sprintf("XML Error: %s at line %d",
  					xml_error_string(xml_get_error_code($this->parser)),
  					xml_get_current_line_number($this->parser)));
  		}
	}
	
	// Function to use at the start and at the end of an element
	function SetElementHandler($startHandler,$stopHandler){
		xml_set_element_handler($this->parser,$startHandler,$stopHandler);
	}
	
	// Function to use when finding character data
	function SetCharacterDataHandler($handler){
		xml_set_character_data_handler($this->parser,$handler);
	}
	
}

?>