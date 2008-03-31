<?php
/*
 * Copyright 2007-2008 Crawwler Software Development
 * http://www.crawwler.com
 * 
 * Project: crawwler-xpress (XPress)
 * File: XPressException.class.php
 * Author: andrew (andrew@crawwler.com)
 * Date: 23.Mar.2008
 * 
 * 
 * Provides basic exception handling
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
	
	public function __toString() {
		return "XPress Exception ({$this->type}".(($this->subtype == '') ? '' : "::{$this->subtype}")."): $message ";
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