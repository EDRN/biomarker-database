<?php
/*
 * Copyright 2007 Crawwler Software Development
 * http://www.crawwler.com
 * 
 * 
 * Provides formatted page construction
 * 
 */

class cwsp_page {
  
  public $pageTitle;        // The title of this page
  public $cssIncludes;      // The list of CSS pages to include
  public $jsIncludes;       // The list of Javascript pages to include
  public $nameMetas;		// The list of META name=  tags
  public $httpMetas;		// The list of META http-equiv=  tags
  public $doctype = 
	'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
  public $htmlOpener;		// The HTML tag
  public $bodyOnLoad;		// Javascript onLoad handler
  public $rawInserts;		// extra information to be inserted

  
  private $version			= "1.1";
  private $versionDate		= "22 July, 2007";
  
  
  public function cwsp_page($title = "Untitled Document",
  			$contentType = 'text/html; charset=iso-8859-1',
  			$htmlOpener  = '<html xmlns="http://www.w3.org/1999/xhtml">'){
 
    $this->pageTitle = $title;
    $this->htmlOpener = $htmlOpener;
    $this->cssIncludes = array();
    $this->jsIncludes = array();
    
	$this->insertHttpMeta("Content-Type",$contentType);
	$this->insertNameMeta("Generator","Crawwler CWSP Version $this->version");
	$this->rawInserts = "";
  }
  
  public function setTitle($title){
    $this->pageTitle = $title;
  }
  
  public function includeCSS($url){
    $this->cssIncludes[] = $url;
  }
  
  public function includeJS($url){
    $this->jsIncludes[] = $url;
  }
  
  public function insertHttpMeta($http_equiv,$content){
    if ($http_equiv != '' && $content != ''){
      $this->httpMetas[] = '<meta http-equiv="'.$http_equiv.'" content="'.$content.'"/>';
    }
  }
  
  public function insertNameMeta($name,$content){
    if ($name != '' && $content != ''){
      $this->nameMetas[] = '<meta name="'.$name.'" content="'.$content.'"/>';
  	}
  }
  
  public function insertRaw($content){
  	$this->rawInserts .= $content;
  }
  
  public function setBodyOnLoad($value){
    $this->bodyOnLoad = $value;
  }
  
  public function drawHeader(){
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"';
    echo ' "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    echo "\r\n";
    echo $this->htmlOpener . "\r\n";
    echo "<head>\r\n";
    foreach ($this->httpMetas as $httpMeta){
      echo $httpMeta . "\r\n";
    }
    foreach ($this->nameMetas as $nameMeta){
      echo $nameMeta . "\r\n";
    }
    echo '<title>'.$this->pageTitle.'</title>' . "\r\n";
    echo '<!-- CSS Includes -->' . "\r\n";
    foreach($this->cssIncludes as $url){
      echo '<link rel="stylesheet" type="text/css" href="'.$url.'"/>' . "\r\n";
    }
    echo '<!-- JS Includes -->' . "\r\n";
    foreach ($this->jsIncludes as $url){
      echo '<script type="text/javascript" src="'.$url.'"></script>' . "\r\n";
    }
    echo $this->rawInserts;
    echo '<head>' . "\r\n\r\n";
    echo '<body ';
    if ($this->bodyOnLoad != ''){
      echo 'onload="'.$this->bodyOnLoad.'">';
    } else {
      echo '>';
    }
    echo "\r\n";
  }
  
  function drawFooter(){
    echo '</body>' . "\r\n";
    echo '</html>' . "\r\n";
  }
  
  public static final function httpRedirect($locationURL){
  	header("Location: $locationURL");
  }
  
}

?>