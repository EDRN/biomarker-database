<?php
require_once("XParse.class.php");
class GenericBuilder {
	/*
	 * Variables common to all builders
	 * 
	 */
	public $parser;		// The XParse object used to parse XML files

	public $html		= '';	// The HTML code generated from the XML
	public $jsCoda		= '';	// The JS epilogue code supporting the actions

	public function Init(){
		$this->parser->Parse();
	}
	
	public function getHtml(){
		return $this->html;
	}
	
	public function getJavascript(){
		return $this->jsCoda;
	}
}

?>