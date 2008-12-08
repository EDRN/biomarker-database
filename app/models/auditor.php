<?php
class Auditor extends AppModel 
{
	const VERBOSITY_NORMAL  =1;
	const VERBOSITY_VERBOSE =2;
	
	var $useTable = "auditor";
	
	var $name = 'Auditor';
	
	function audit($message,$level = 1,$objectType='',$objectId='') {
		
		// Insert a log entry into the audit table. Audits consist of 
		// the following information:
		//
		// username    = who performed the action
		// timestamp   = when the action was performed
		// message     = a brief description of the action. 
		// objectType  = (optional) the class of the affected object
		// objectId    = (optional) the id of the affected object
		// level       = (default:normal) a verbosity indicator useful
		//					for controlling how much information is displayed
		//					when generating reports from the audit log.
		
		$username = $_SESSION['username'];
		$msg      = addslashes($message);
		$q = "INSERT INTO `auditor` (`username`,`timestamp`,`message`,`objectType`,`objectId`,`level`) "
			."VALUES ('{$username}',NOW(),'{$msg}','{$objectType}','{$objectId}','{$level}')";
		
		$this->query($q);
		
	}
	
	function getLatest($level,$count=5,$objectType='',$objectId='') {
		
		// If no objectType specified, get all events for all objects (at the specified level)
		// If objectType and objectId specified, get all events for that object (at the specified level)

		if ($objectType == '') {
			// All events
			$q   = "SELECT * FROM `auditor` WHERE `level` <= '{$level}' ORDER BY `timestamp` DESC LIMIT " . max($count,5);
			$log = $this->query($q);
			var_dump($log);
		} else if ($objectId == '') {
			// All events for objectType
		} else {
			// All events for objectId
			
		}
		
		
		
		
	}
	
	
}
?>