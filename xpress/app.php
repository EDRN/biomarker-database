<?php


	// Configurable Model Properties:
	class App {
		const DSN = "mysql://username:password@server/dbname";
		const ROOT_DIR = "/path/to/project/root";
		const CWSP_PATH = "";
		const PEAR_PATH = "/path/to/pear/library";
		const HTTP_SITE_ROOT = "http://url/to/project/root";
		const USE_DATABASE = true;
		const USE_IDENTITIES = true;
		const SHOW_PAGE_STATS = true;
	}

	// XPressObject class
	abstract class XPressObject {
		private $objId = 0;
	}
	// PHP Include Path modifications:
	set_include_path(App::PEAR_PATH.PATH_SEPARATOR.get_include_path()); // pear

	// Require XPress core clases:
	require_once(App::ROOT_DIR."/xpress/framework/XPress.class.php");
	require_once(App::ROOT_DIR."/xpress/framework/db/XPressDB.class.php");
	require_once(App::ROOT_DIR."/xpress/framework/exceptions/XPressException.class.php");
	require_once(App::ROOT_DIR."/xpress/framework/session/XPressSession.class.php");
	require_once(App::ROOT_DIR."/xpress/framework/page/tbs/tbs_3.3.0b12_php5.php");
	require_once(App::ROOT_DIR."/xpress/framework/page/XPressPage.class.php");

	// If using identities, include identity framework:
	if (App::USE_IDENTITIES) {
		require_once(App::ROOT_DIR."/xpress/framework/identities/XPressIdentityObject.class.php");
		require_once(App::ROOT_DIR."/xpress/framework/identities/XPressAuthWrapper.class.php");
		require_once(App::ROOT_DIR."/xpress/framework/identities/datamodel/Xiuser.php");
		require_once(App::ROOT_DIR."/xpress/framework/identities/datamodel/Xigroup.php");
		require_once(App::ROOT_DIR."/xpress/framework/identities/datamodel/Xirole.php");
		require_once(App::ROOT_DIR."/xpress/framework/ObjectBroker.class.php");
	}

	// Start the session:
	XPressSession::init();

	// Require the model definition files:
	require_once(App::ROOT_DIR."/xpress/datamodel/Biomarker.php");
	require_once(App::ROOT_DIR."/xpress/datamodel/BiomarkerAlias.php");
	require_once(App::ROOT_DIR."/xpress/datamodel/Study.php");
	require_once(App::ROOT_DIR."/xpress/datamodel/BiomarkerStudyData.php");
	require_once(App::ROOT_DIR."/xpress/datamodel/Organ.php");
	require_once(App::ROOT_DIR."/xpress/datamodel/BiomarkerOrganData.php");
	require_once(App::ROOT_DIR."/xpress/datamodel/BiomarkerOrganStudyData.php");
	require_once(App::ROOT_DIR."/xpress/datamodel/Publication.php");
	require_once(App::ROOT_DIR."/xpress/datamodel/Resource.php");
	require_once(App::ROOT_DIR."/xpress/datamodel/Site.php");
	require_once(App::ROOT_DIR."/xpress/datamodel/Person.php");


	define("XPRESS_DEBUG",false);

	// Create an instance of the XPress class
	try {
		$xpress = XPress::getInstance();

	} catch (XPressException $e) {
		die($e->getFormattedMessage());
	}

	//Load Extended Properties
	@include_once("ModelPropertiesExtensions.inc.php");
?>