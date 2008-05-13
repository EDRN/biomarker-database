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
	// Configurable Model Properties:
	class App {
		const NAME = "EDRN Biomarker Database";
		const VERSION = "0.3.1 Beta";
		const ROOT_DIR = "/path/to/application/root";
		const DSN = "mysql://user:pass@server/dbname";
		const PEAR_PATH = "/path/to/pear/library";
		const HTTP_ROOT = "http://url/to/site/root";
		const USE_DATABASE = true;
		const USE_IDENTITIES = true;
		const SHOW_PAGE_STATS = false;
	}
	
	// Global Debug Mode switch
	//  (controls level of detail provided in error messages. This should
	//   definitely be set to false in a production environment!)
	define("XPRESS_DEBUG",true);	

	// Relativize require_once Statements
	function & rel($r, &$f) {return file_exists( ( $f = ( dirname($r).'/'.$f ) ) );}
	function & relf($r, $f) {return rel($r,$f) ? file_get_contents($f) : null;}
	function & reli($r, $f) {return rel($r,$f) ? include($f) : null;}
	function & relr($r, $f) {return rel($r,$f) ? require($f) : null;}
	function & relio($r, $f) {return rel($r,$f) ? include_once($f) : null;}
	function & relro($r, $f) {return rel($r,$f) ? require_once($f) : null;}

	// XPressObject class
	abstract class XPressObject {
		private $objId = 0;
	}
	
	$http_root_path = App::HTTP_ROOT;
	
	// PHP Include Path modifications:
	set_include_path(App::PEAR_PATH.PATH_SEPARATOR.get_include_path()); // pear

	// Require XPress core clases:
	relro(__FILE__,"framework/XPress.class.php");
	relro(__FILE__,"framework/db/XPressDB.class.php");
	relro(__FILE__,"framework/exceptions/XPressException.class.php");
	relro(__FILE__,"framework/session/XPressSession.class.php");
	relro(__FILE__,"framework/page/tbs/tbs_3.3.0b12_php5.php");
	relro(__FILE__,"framework/page/XPressPage.class.php");

	// If using identities, include identity framework:
	if (App::USE_IDENTITIES) {
		relro(__FILE__,"framework/identities/XPressIdentityObject.class.php");
		relro(__FILE__,"framework/identities/XPressAuthWrapper.class.php");
		relro(__FILE__,"framework/identities/model/Xiuser.php");
		relro(__FILE__,"framework/identities/model/Xigroup.php");
		relro(__FILE__,"framework/identities/model/Xirole.php");
		relro(__FILE__,"framework/ObjectBroker.class.php");
	}

	// Start the session:
	XPressSession::init();

	// Require the model definition files:
	relro(__FILE__,"objects/compiled.php");


	// Create an instance of the XPress class
	try {
		$xpress = XPress::getInstance();
	} catch (XPressException $e) {
		die($e->getFormattedMessage());
	}
	
?>