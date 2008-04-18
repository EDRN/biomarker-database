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

class XPressException extends Exception {
	
	private $type;
	private $subtype;
	private $details;
	
	public function __construct ($type,$subtype,$message,$details='') {
		parent::__construct($message);	
		$this->type = $type;
		$this->subtype = $subtype;
		$this->details = $details;
	}
	
	public function toString() {
		return "XPress Exception ({$this->type}".(($this->subtype == '') ? '' : "::{$this->subtype}")."): $this->message ";
	}
	
	public function getFormattedMessage() {
		return "<div class=\"XPressExceptionContainer\">\r\n"
			. "<strong>XPress Error</strong>: "
			. "({$this->type}".(($this->subtype == '') ? '' : "::{$this->subtype}")."): "
			. "{$this->getMessage()}\r\n"
			. ((XPRESS_DEBUG) ? 
			"<br/>"
			. "In File: {$this->getFile()} (line {$this->getLine()})<br/>\r\n"
			. "<div class=\"XPressExceptionBacktraceContainer\">Backtrace:<br/>\r\n".nl2br($this->getTraceAsString())."\r\n</div>\r\n"
			. "</div>"
			: '')
			. ((XPRESS_DEBUG) ?
			"<br/>"
			. "Details: {$this->details}\r\n"
			: '');
	}
}
?>