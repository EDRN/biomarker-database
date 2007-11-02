<?php

require_once("Validate.php");
require_once("../definitions.inc.php");
require_once("../../cots/crawwler-cwsp-1.0.75/cwsp.inc.php");
require_once("../../cots/crawwler-xld-1.0.0/GenericHandler.class.php");



$field = $_POST['field'];				// The field to store the value in
$value = $_POST['value'];				// The value to save
$values = $_POST['values'];				// The select box option values
$labels = $_POST['labels'];				// The select box option labels
$validation = stripslashes($_POST['validation']);	// The validation information
$wherestring = stripslashes($_POST['wherestring']);	// The conditional clause to use
$table = 'organ';						// The organ table stores organ data

$db = new cwsp_db(BMDB_DSN);



if($validation == '' || $validation == 'any' || $validation == 'all'){
	// no validation required, simply save whatever has been provided
	GenericHandler::SaveInput($db->conn,
							$table,
							$field,
							$value,
							$wherestring,
							$labels,
							$values);
} else {	
	// if validation passes, save the new value to the database
	if (GenericHandler::ValidateInput($value,$validation)){
		GenericHandler::SaveInput($db->conn,
								$table,
								$field,
								$value,
								$wherestring,
								$labels,
								$values);
	} else {
		// if validation fails, return the current value from the database
		$val = $db->getOne("SELECT $field FROM $table $wherestring");
		echo $val; 
	}
}



?><?php

?>