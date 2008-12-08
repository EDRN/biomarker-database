<?php
class AuditorsController extends AppController {
	
	var $helpers = array('Html','Ajax','Javascript');
	
	public function weeklySummary() {
		$audits = $names = $this->Auditor->find("all",array());
		$this->set("audits",$audits);
		
	}
}
?>