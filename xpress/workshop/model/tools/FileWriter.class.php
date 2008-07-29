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
class FileWriter {
	
	var $filePointer = 0; 	// The path to write to

	public function __construct($filePath,$mode = "w"){
		try {
			$this->filePointer = fopen($filePath,$mode);
		} catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function write($string){
		fwrite($this->filePointer,$string);
	}
	
	public function close(){
		fclose($this->filePointer);
	}
}

class AdvancedFileWriter {
	
	private $filePointer = 0;	// The path of the file
	private $fileContents = '';
	private $startTag = '';
	private $startPos = 0;
	private $endTag   = '';
	private $endPos   = 0;
	private $savedContent = '';
	

	public function __construct($filePath,$startTag = '',$endTag = '') {
		$this->startTag = $startTag;
		$this->endTag   = $endTag;
		
		if ($startTag != '' && $endTag != '' && file_exists($filePath)){
			// look for start/end tag combo
			try {
				$c = file_get_contents($filePath);
				$this->startPos = strpos($c,$startTag);
				$this->endPos   = strpos($c,$endTag,$this->startPos + strlen($startTag));
				$this->savedContent = substr($c,($this->startPos + strlen($startTag)),($this->endPos - ($this->startPos + strlen($startTag)) ));
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}
		
		// open the file for writing
		try {
			$this->filePointer = fopen($filePath,"w");
		} catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function write($string){
		if ($this->startTag == '' && $this->endTag == '') {
			// handle simple write w/o replacement
			fwrite($this->filePointer,$string);
		} else {
			// handle complex write w/ replacement
			$start = strpos($string,$this->startTag) + strlen($this->startTag);
			if ($start > strlen($this->startTag)) {
				// Found a start tag, now go for the end tag
				$end   = strpos($string,$this->endTag,$start) - strlen($this->endTag);
				if ($start < $end && ($end > 0)) {
					// Found an end tag, now replace
					$s = substr_replace($string,"--@--INSERT_HERE--@--",$start,($end-$start + strlen($this->endTag)));
					$s = str_replace("--@--INSERT_HERE--@--","{$this->savedContent}",$s);
					// Write the modified string
					fwrite($this->filePointer,$s);
				}
			} else {
				// Write the string, unmodified
				fwrite($this->filePointer,$string);
			}
		}
	}
	
	public function close() {
		fclose($this->filePointer);
	}
}

class PHPFileWriter extends AdvancedFileWriter {
	
	public function __construct($filePath,$preamble='',$startTag='',$endTag=''){
		parent::__construct($filePath,$startTag,$endTag);
		$this->write("<?php\r\n{$preamble}\r\n\r\n");
	}
	
	public function close(){
		$this->write("?>");
		parent::close();
	}
}

class JSFileWriter extends FileWriter {
	
	public function __construct($filePath){
		parent::__construct($filePath);
		$this->write("// Javascript\r\n\r\n");
	}
	
	public function close(){
		parent::close();
	}
}
?>