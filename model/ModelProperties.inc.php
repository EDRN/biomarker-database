<?php
	// Configurable Model Properties:
	class Modeler {
		const DSN = "mysql://root:root@localhost/bmdb3";
		const ROOT_DIR = "/Applications/MAMP/htdocs/edrn_bmdb3";
	}
	// Require the model definition files:
	require_once(Modeler::ROOT_DIR."/model/biomarker.php");
	require_once(Modeler::ROOT_DIR."/model/biomarker_alias.php");
	require_once(Modeler::ROOT_DIR."/model/study.php");
	require_once(Modeler::ROOT_DIR."/model/biomarker_study.php");
	require_once(Modeler::ROOT_DIR."/model/organ.php");
	require_once(Modeler::ROOT_DIR."/model/biomarker_organ.php");
	require_once(Modeler::ROOT_DIR."/model/publication.php");
	require_once(Modeler::ROOT_DIR."/model/biomarker_publication.php");
	require_once(Modeler::ROOT_DIR."/model/biomarker_organ_publication.php");
	require_once(Modeler::ROOT_DIR."/model/study_publication.php");
	require_once(Modeler::ROOT_DIR."/model/resource.php");
	require_once(Modeler::ROOT_DIR."/model/biomarker_resource.php");
	require_once(Modeler::ROOT_DIR."/model/biomarker_organ_resource.php");
	require_once(Modeler::ROOT_DIR."/model/study_resource.php");
	require_once(Modeler::ROOT_DIR."/model/person.php");
	require_once(Modeler::ROOT_DIR."/model/site.php");
	require_once(Modeler::ROOT_DIR."/model/person_site.php");
?>