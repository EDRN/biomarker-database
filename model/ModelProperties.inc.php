<?php
	// Configurable Model Properties:
	class Modeler {
		const DSN = "mysql://user:pass@server/database";
		const ROOT_DIR = "/path/to/project/root";
		const CWSP_PATH = "/path/to/cwsp/library";
		const PEAR_PATH = "/path/to/pear/library";
	}

	// PHP Include Path modifications:
	set_include_path(Modeler::CWSP_PATH.PATH_SEPARATOR.get_include_path()); // cwsp
	set_include_path(Modeler::PEAR_PATH.PATH_SEPARATOR.get_include_path()); // pear

	// Include the CWSP library:
	require_once("cwsp.inc.php");

	// XPress container class:
	class XPress {
		public $Database;
		public function __construct(){
			$this->Database =  new cwsp_db(Modeler::DSN);
		}
	}

	$XPress = new XPress();

	// Require the model definition files:
	require_once(Modeler::ROOT_DIR."/model/Biomarker.php");
	require_once(Modeler::ROOT_DIR."/model/BiomarkerAlias.php");
	require_once(Modeler::ROOT_DIR."/model/Study.php");
	require_once(Modeler::ROOT_DIR."/model/BiomarkerStudyData.php");
	require_once(Modeler::ROOT_DIR."/model/Organ.php");
	require_once(Modeler::ROOT_DIR."/model/BiomarkerOrganData.php");
	require_once(Modeler::ROOT_DIR."/model/BiomarkerOrganStudyData.php");
	require_once(Modeler::ROOT_DIR."/model/Publication.php");
	require_once(Modeler::ROOT_DIR."/model/Resource.php");
	require_once(Modeler::ROOT_DIR."/model/Site.php");
	require_once(Modeler::ROOT_DIR."/model/Person.php");
?>