<?php
class AuditorsController extends AppController {
	
	var $helpers = array('Html','Ajax','Javascript');
	
	public function weeklySummary() {
		$this->checkSession("/auditors/weeklySummary");
		$audits = $names = $this->Auditor->find("all",
			array("order"=>'`timestamp` DESC'));
		$this->set("audits",$audits);
		
	}
}
?>