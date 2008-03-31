<?php
/*
 * Copyright 2006-2008 Crawwler Software Development.
 * http://www.crawwler.com
 * 
 * Project: CWSP - XPress
 * File: XPressPage.class.php
 * Created on March 07, 2008
 * Author: andrew
 *
 */

class XPressPage {
	
	private $title;
	private $contentType;
	private $charset;
	private $xmlns; 
	
	private $cssFiles;
	private $jsFiles;
	
	private $nameMetas;
	private $httpMetas;
	
	private $doctype;
	
	private $onload;
	private $onUnload;
	
	private $rawInserts;
	private $startTime;
	private $endTime;
	
	private $version 		= "0.2.0";
	private $versionDate 	= "07.Mar.2008";
	
	private $view;
	
	public function __construct(
		$title = 'Untitled Document',
		$contentType = 'text/html',
		$charset = 'iso-8859-1',
		$xmlns = 'http://www.w3.org/1999/xhtml') {
			
		$this->nameMetas    = array();
		$this->httpMetas    = array();
		$this->startTime 	= microtime();
		$this->title		= $title;
		$this->contentType	= $contentType;
		$this->charset		= $charset;
		$this->xmlns		= $xmlns;
		
		$this->cssFiles		= array();
		$this->jsFiles		= array();
		
		$this->insertNameMeta("Content-Type",
			"{$this->contentType}; charset={$this->charset}");
		$this->insertNameMeta("Generator", 
			"Crawwler CWSP Version {$this->version}");
		$this->rawInserts 	= '';
		$this->view 		= new clsTinyButStrong();
		
		$this->includeCSS(App::HTTP_SITE_ROOT."/xpress/static/css/xpress.css");
	}
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function includeCSS($url){
		$this->cssFiles[] = $url;
	}

	public function includeJS($url){
		$this->jsFiles[] = $url;
	}
	
	public function insertHttpMeta($http_equiv,$content){
		if ($http_equiv != '' && $content != ''){
	      $this->httpMetas[] = 
	      	'<meta http-equiv="'.$http_equiv.'" content="'.$content.'"/>';
	    }
	}
	
	public function insertNameMeta($name,$content){
		if ($name != '' && $content != ''){
		   $this->nameMetas[] = 
		   	'<meta name="'.$name.'" content="'.$content.'"/>';
		}
	}
	
	public function insertRaw($content){
		$this->rawInserts .= $content;
	}
	
	public function setBodyOnLoad($value){
		$this->onLoad = $value;
	}
	
	public function setBodyOnUnload($value){
		$this->onUnload = $value;
	}
	
	public function redirect($url) {
		header("Location: {$url}");
		exit();
	}
	
	// Static version for those times you really just want to 
	// redirect without having to create a page object
	public static function httpRedirect($url) {
		header("Location: {$url}");
		exit();
	}
	
	public function getView() {
		return $this->view;
	}
	
	public function view() {
		return $this->view;
	}

	public final function loadModule($module,$tag) {
		$module->init();
		$this->view->MergeBlock($tag,"text",$module->display(false));
	}
	
	public final function open() {
		// Prep page data
		$xmlns = "xmlns=\"{$this->xmlns}\"";
		$httpMetaString = implode("\r\n\t",$this->httpMetas);
		$httpMetaString = implode("\r\n\t",$this->nameMetas);
		$formattedCssIncludes = array();
		$formattedJsIncludes  = array();
		foreach ($this->cssFiles as $c){
			$formattedCssIncludes[] = 
				'<link rel="stylesheet" type="text/css" href="'.$c.'"/>';
		}
		foreach ($this->jsFiles as $j) {
			$formattedJsIncludes[] = 
				'<script type="text/javascript" src="'.$j.'"></script>';	
		}
		$cssIncludeString = implode("\r\n\t",$formattedCssIncludes);
		$jsIncludeString  = implode("\r\n\t",$formattedJsIncludes);
		
		$onloadString = ($this->onload == '') 
			? ''
			: " onload=\"{$this->onload}\" ";
		$onUnloadString = ($this->onUnload == '')
			? ''
			: "onunload=\"{$this->onunload}\"";
		
		// Draw the page
		echo <<<END
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html {$xmlns}>
  <head>
	{$httpMetaString}
	{$nameMetaString}
	<title>{$this->title}</title>
	<!-- CSS Includes -->
	{$cssIncludeString}
	<!-- JS Includes -->
	{$jsIncludeString}
	
	{$this->rawInserts}
  </head>
  <body{$onloadString}{$onUnloadString}>\r\n
END;
	}


	public final function close($showStats = false) {
		if ($showStats || App::SHOW_PAGE_STATS) {
			$this->endTime = microtime();
			echo "\r\n<div id=\"xpress_pagestats\">"
				. "Page Rendered in " 
				. ($this->endTime - $this->startTime) 
				. " Seconds.</div>";
		}
		echo <<<END
\r\n
  </body>
</html>
END;
	}

}


/**
 * PAGE MODULE INTERFACE & EXAMPLES
 */
interface XPressPageModule {
	
	public function init();
	
	public function display($bEcho = true);
}

class HelloWorldModule implements XPressPageModule {
	private $source = '';
	
	public function __construct() {
		$this->source = 'Hello World!';		
	}
	
	public function init() {
		
	}
	
	public function display($bEcho = true) {
		if ($bEcho ) {
			echo $this->source;
		} else {
			return $this->source;
		}
	}
}

class TimeOfDayModule implements XPressPageModule {
	private $time_built = '';
	private $source = '';
	
	public function __construct() {
		$this->time_built = date('Y-m-d h:i:s',mktime());	
	}
	
	public function init() {
		$time_now = date('Y-m-d h:i:s',mktime());
		$this->source = <<<END
<table>
	<tr><td>Constructed:</td><td>{$this->time_built}</td></tr>
	<tr><td>Initialized:</td><td>{$time_now}</td></tr>
</table>
END;
	}
	
	public function display($bEcho = true) {
			if ($bEcho ) {
			echo $this->source;
		} else {
			return $this->source;
		}
	}
	
}

class EchoGetModule implements XPressPageModule {
	private $source = '';
	
	public function __construct() {
			
	}
	
	public function init() {
		if (isset($_GET['text'])) {
			$this->source = $_GET['text'];
		} else {
			$this->source = "<em>No text to display</em>";
		}
	}
	
	public function display($bEcho = true) {
			if ($bEcho ) {
			echo $this->source;
		} else {
			return $this->source;
		}
	}
	
}


?>