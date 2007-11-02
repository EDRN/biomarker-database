<?php

class ModelGenerator {
	
	// Member Variables
	public $table;		// The name of the table being operated on
	public $idx;		// The field(s) to use as a unique index
	public $vars;		// Correspond directly to the fields in the DB table
	
	private $conn;
	
	
	public function __construct(&$conn, $table, $idx = array("ID")){
		$this->conn = &$conn;
		$this->table = $table;
		$this->idx = $idx;
		
		$this->vars = array();
		$this->loadFields();
	}
	
	public function toString(){
		$str = "<?php \r\n"
			 . "/* -- Auto-generated on " . date("c e") . " -- */\r\n\r\n";
		$str .= $this->write_vo();
		$str .= $this->write_dao();
		$str .= $this->writeExceptionHandler();
		$str .= "?>";
		return $str;		
	}
	
	private function loadFields(){
		$q = "DESCRIBE `$this->table`";
		$r = $this->conn->safeQuery($q);
		while ($field = $r->fetchRow(DB_FETCHMODE_ASSOC)){
			$this->vars[] = $field['Field'];
		}
	}
	
	private function write_vo(){
		$str = "class vo_" . ereg_replace("-","_",$this->table) . " {\r\n";
		foreach($this->vars as $var){
			$str .= "\tpublic \$$var;\r\n";
		}
		
		$str .= $this->vo_toAssocArray();		
		
		$str .= "}\r\n\r\n";
		return $str;
	}
	
	private function write_dao(){
		$str = "class dao_" . ereg_replace("-","_",$this->table) . " {\r\n"
			. "\tprivate \$conn;\r\n"
			. "\tprivate \$idx;\r\n\r\n";
		$str .= $this->dao_construct();
		$str .= $this->dao_getBy();
		$str .= $this->dao_getRange();
		$str .= $this->dao_getSubset();
		$str .= $this->dao_numRecords();
		$str .= $this->dao_save();
		$str .= $this->dao_delete();
		$str .= $this->dao_update();
		$str .= $this->dao_insert();
		$str .= $this->dao_getFromResult();
		$str .= "}\r\n\r\n";
		return $str;
		
	}
	
	
	private function vo_toAssocArray(){
		$str = "\r\n\tpublic function toAssocArray(){\r\n"
			 .  "\t\treturn array(\r\n";
		foreach($this->vars as $var){
			$str .= "\t\t\t\"$var\" => \$this->$var,\r\n";
		}
		$str .= "\t\t);\r\n\t}\r\n";
		return $str;		
	}	

	
	private function dao_construct(){
		$str =  "\tpublic function __construct(&\$conn){\r\n"
			. "\t\t\$this->conn = &\$conn;\r\n"
			. "\t\t\$this->idx = array(";
		for ($i = 0;$i<sizeof($this->idx)-1;$i++){
			$str .= "\"{$this->idx[$i]}\",";
		}
		if (sizeof($this->idx) > 0){
			$str .= "\"{$this->idx[sizeof($this->idx)-1]}\");";
		}
		$str .= "\t}\r\n\r\n";
		return $str;		
	}
	private function dao_getBy(){
		return "\tpublic function getBy(\$attr,\$val){\r\n"
			. "\t\t\$q = \"SELECT * FROM `$this->table` WHERE \$attr=\\\"\$val\\\" \";\r\n"
			. "\t\t\$r = \$this->conn->safeQuery(\$q);\r\n"
			. "\t\tif (\$r->numRows() == 0){\r\n"
			. "\t\t\tthrow new NoSuch".ereg_replace("-","_",$this->table)."Exception(\"No {$this->table} found with \$attr = \$val\");\r\n"
			. "\t\t}\r\n"
			. "\t\twhile (\$result = \$r->fetchRow(DB_FETCHMODE_ASSOC)) {\r\n"
			. "\t\t\t\$vo = new vo_".ereg_replace("-","_",$this->table)."();\r\n"
			. "\t\t\t\$this->getFromResult(&\$vo,\$result);\r\n"
			. "\t\t\t\$results[] = \$vo;\r\n"
			. "\t\t}\r\n"
			. "\t\treturn(\$results);\r\n"
			. "\t}\r\n\r\n";
	}
	private function dao_getRange(){
		return "\tpublic function getRange(\$start,\$end){\r\n"
			. "\t\t\$results = array();\r\n"
			. "\t\t\$count = \$end - \$start;\r\n"
			. "\t\t\$q = \"SELECT * FROM `$this->table` LIMIT \$start, \$count\"; \r\n"
			. "\t\t\$r = \$this->conn->safeQuery(\$q); \r\n"
			. "\t\twhile (\$result = \$r->fetchRow(DB_FETCHMODE_ASSOC)) {\r\n"
			. "\t\t\t\$vo = new vo_".ereg_replace("-","_",$this->table)."();\r\n"
			. "\t\t\t\$this->getFromResult(&\$vo,\$result);\r\n"
			. "\t\t\t\$results[] = \$vo;\r\n"
			. "\t\t}\r\n"
			. "\t\treturn(\$results);\r\n"
			. "\t}\r\n\r\n";
	}
	private function dao_getSubset(){
		return "\tpublic function getSubset(\$field,\$ids){\r\n"
			. "\t\tif (sizeof(\$ids) == 0) { return array();}\r\n"
			. "\t\t\$q = \"SELECT * FROM `$this->table` WHERE \$field IN (\";\r\n"
			. "\t\tfor(\$i=0;\$i<sizeof(\$ids) - 1;\$i++){\r\n"
			. "\t\t\t\$q .= \$ids[\$i] . \",\";\r\n"
			. "\t\t}\r\n"
			. "\t\tif (sizeof(\$ids) > 0){\r\n"
			. "\t\t\t\$q .= \$ids[sizeof(\$ids)-1];\r\n"
			. "\t\t}\r\n"
			. "\t\t\$q .= \")\";\r\n"
			. "\t\t\$r = \$this->conn->safeQuery(\$q);\r\n"
			. "\t\twhile (\$result = \$r->fetchRow(DB_FETCHMODE_ASSOC)) {\r\n"
			. "\t\t\t\$vo = new vo_".ereg_replace("-","_",$this->table)."();\r\n"
			. "\t\t\t\$this->getFromResult(&\$vo,\$result);\r\n"
			. "\t\t\t\$results[] = \$vo;\r\n"
			. "\t\t}\r\n"
			. "\t\treturn(\$results);\r\n"
			. "\t}\r\n\r\n";
	}
	private function dao_numRecords(){
		return "\tpublic function numRecords(){\r\n"
			. "\t\t\$q = \"SELECT COUNT(*) FROM `$this->table`\"; \r\n"
			. "\t\t\$r = \$this->conn->safeGetOne(\$q); \r\n"
			. "\t\treturn(\$r);\r\n"
			. "\t}\r\n\r\n";
	}
	private function dao_save(){
		return "\tpublic function save(&\$vo){\r\n"
			. "\t\tif (\$vo->ID ==0) {\r\n"
			. "\t\t\t\$this->insert(\$vo);\r\n"
			. "\t\t} else {\r\n"
			. "\t\t\t\$this->update(\$vo);\r\n"
			. "\t\t}\r\n\t}\r\n\r\n";
	}
	private function dao_delete(){
		$str = "\tpublic function delete(&\$vo) {\r\n"
			. "\t\t\$q = \"DELETE FROM `$this->table` WHERE ";
		for($i=0;$i<sizeof($this->idx)-1;$i++){
			$str .= $this->idx[$i] . "=\\\"\$vo->" . $this->idx[$i] . "\\\" AND ";
		}
		if (sizeof($this->idx) > 0){
			$str .= $this->idx[sizeof($this->idx)-1] . "=\\\"\$vo->" . $this->idx[sizeof($this->idx)-1] . "\\\" \";\r\n";
		}
		
		$str .= "\t\t\$this->conn->safeQuery(\$q); // delete from the database \r\n";
		foreach($this->idx as $field){
			$str .= "\t\t\$vo->$field=0;\r\n";
		}
		$str .= "\t}\r\n\r\n";	
		return $str;	
	}
	private function dao_update(){
		$str =  "\tpublic function update(&\$vo){\r\n"
			. "\t\t\$q = \"UPDATE `$this->table` SET \";\r\n";
		for ($i = 0; $i < sizeof($this->vars) - 1; $i++){
			$str .= "\t\t\$q .= \"{$this->vars[$i]}=\$vo->{$this->vars[$i]}\" . \", \";\r\n";
		}
		if (sizeof($this->vars) -1 >= 0){
			$str .= "\t\t\$q .= \"{$this->vars[sizeof($this->vars)-1]}=\$vo->{$this->vars[sizeof($this->vars)-1]} \";\r\n";
		}
		$str .= "\t\t\$q .= \"WHERE ";
		for($i=0;$i<sizeof($this->idx)-1;$i++){
			$str .= $this->idx[$i] . "=\\\"\$vo->" . $this->idx[$i] . "\\\" AND ";
		}
		if (sizeof($this->idx) > 0){
			$str .= $this->idx[sizeof($this->idx)-1] . "=\\\"\$vo->" . $this->idx[sizeof($this->idx)-1] . "\\\" \";\r\n";
		}	
		$str .= "\t\t\$r = \$this->conn->safeQuery(\$q);\r\n"
			. "\t}\r\n\r\n";
		return $str;
	}
	private function dao_insert(){
		$str = "\tprivate function insert(&\$vo){\r\n"
			. "\t\t//insert this vo into the database as a new row\r\n"
			. "\t\t\$q = \"INSERT INTO `$this->table` \"; \r\n"
			. "\t\t\$q .= 'VALUES(";
		for ($i = 0; $i < sizeof($this->vars) -1;$i++){
			$str .= "\"'.\$vo->{$this->vars[$i]}.'\",";	
		}
		if (sizeof($this->vars) -1 >= 0){
			$str .= "\"'.\$vo->{$this->vars[sizeof($this->vars)-1]}.'\" ";
		}
		$str .= ") ';\r\n";
		$str .= "\t\t\$r = \$this->conn->safeQuery(\$q);\r\n"
			. "\t\t\$vo->ID = \$this->conn->safeGetOne(\"SELECT LAST_INSERT_ID() FROM `$this->table`\");\r\n"
			. "\t}\r\n\r\n";
		return($str);
	}
	private function dao_getFromResult(){
		$str = "\tpublic function getFromResult(&\$vo,\$result){\r\n";
		for($i = 0;$i<sizeof($this->vars);$i++){
			$str .= "\t\t\$vo->{$this->vars[$i]} = \$result['{$this->vars[$i]}'];\r\n";
		}
		$str .= "\t}\r\n\r\n";
		return $str;
	}
	
	private function writeExceptionHandler(){
		$str = "class NoSuch".ereg_replace("-","_",$this->table)."Exception extends Exception {\r\n";
		$str .= $this->exc_construct();
		$str .= $this->exc_toString();
		$str .= $this->exc_getFormattedStackTrace();
		$str .= "}\r\n";
		return $str;
	}
	
	private function exc_construct(){
		return "\tpublic function __construct(\$message,\$code = 0){\r\n"
			. "\t\tparent::__construct(\$message,\$code);\r\n"
			. "\t}\r\n\r\n";
	}
	private function exc_toString(){
		return "\tpublic function __toString() {\r\n"
			. "\t\t\$str = \"<strong>\".__CLASS__.\" Occurred: </strong>\";\r\n"
			. "\t\t\$str .= \"(Code: {\$this->code}) \";\r\n"
			. "\t\t\$str .= \"[Message: {\$this->message}]\\n<br/>\";\r\n"
			. "\t\tif (DEBUGGING){\r\n"
			. "\t\t\t\$str .= \"&nbsp;&nbsp; at \" . self::getFile() . \":\" . self::getLine();\r\n"
			. "\t\t\t\$str .= \"<br/><br/>\\n\";\r\n"
			. "\t\t\t\$str .= self::getFormattedStackTrace();\r\n"
			. "\t\t}\r\n"
			. "\t\treturn \$str;\r\n"
			. "\t}\r\n\r\n";
	}
	private function exc_getFormattedStackTrace(){
		return "\tpublic function getFormattedStackTrace(){\r\n"
			. "\t\t\$trace = \"<strong>Stack Trace:</strong><br/>\";\r\n"
			. "\t\tforeach (self::getTrace() as \$file){\r\n"
			. "\t\t\t\$trace .= \"At line \$file[line] of \$file[file] \";\r\n"
			. "\t\t\t\$trace .= \"[\";\r\n"
			. "\t\t\tif (isset(\$file['class'])){\r\n"
			. "\t\t\t\t\$trace .= \"\$file[class]\";\r\n"
			. "\t\t\t}\r\n"
			. "\t\t\tif (isset(\$file['function'])){\r\n"
			. "\t\t\t\t\$trace .= \"::\$file[function]\";\r\n"
			. "\t\t\t}\r\n"
			. "\t\t\t\$trace .= \"]<br/>\";\r\n"
			. "\t\t}\r\n"
			. "\t\treturn \$trace;\r\n"
			. "\t}\r\n\r\n";
	}
	

	
	
}


?>