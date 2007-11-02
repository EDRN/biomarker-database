<?php

class Query {
	/*
	 * Member Variables 
	 */
	
	public $tables = array();		// The tables this query operates on
	public $fields = array(); 		// The fields this query operates on
	public $aliases = array(); 	// The aliases defined in this query ('AS' clauses)
	public $conditions = array();	// The conditionals defined ('WHERE' clauses)

	public $currentTable = '';	// The working table (prepended to field names)

	public $groupBy = '';		// The 'GROUP BY' clause)	//TBD
	public $orderBy = '';		// The 'ORDER BY' clause)	//TBD

	public $limit = '';			// The limit value		//TBD
	public $count = '';			// The count value		//TBD	

	/*
	 * Member Functions
	 */
	
	public function addTable($tableName){
		// Check for pre-existing value (duplicate check)
		foreach ($this->tables as $table){
			if ($table == $tableName) {
				return;	// Don't insert duplicate value
			}
		}
		// Add this table to the array
		$this->tables[] = $tableName;
	}
	
	public function addField($fieldName){
		// Check for pre-existing value (duplicate check)
		foreach ($this->fields as $field){
			if ($field == $fieldName) {
				return;	// Don't insert duplicate value
			}
		}
		// Add this fields to the array
		$this->fields[] = (empty($this->currentTable)?  "" : "`$this->currentTable`.") . $fieldName;		
	}
	
	public function addCondition($conditionText){
		// Check for pre-existing value (duplicate check)
		foreach ($this->conditions as $condition){
			if ($condition == $conditionText) {
				return;	// Don't insert duplicate value
			}
		}
		// Add this conditional to the array
		$this->conditions[] = $conditionText;		
	}
	
	public function addAlias($field,$alias){
		// Check for pre-existing value (duplicate check)
		// A field can only have one alias... solve collisions
		// by overwriting previously stored alias
		$index = $this->currentTable.'.'.$field;
		foreach ($this->aliases as $f=>$a){
			if ($f == $index) {
				$this->aliases[$index] = $alias;
				return;	
			}
		}
		// Add this alias to the array
		$this->aliases[$index] = $alias;	
	}
	
	public function getAliasFor($field){
		foreach ($this->aliases as $f=>$a){
			if ($f == $field) {
				return $a;
			}
		}
		return $field; // if there is no alias, return the field itself		
	}
	
	public function getFieldFor($alias){
		foreach ($this->aliases as $f=>$a){
			if ($a == $alias) {
				return $f;
			}
		}
	}

	public function setCurrentTable($tableName){
		// Only set value if the table already exists in the tables array
		foreach ($this->tables as $table){
			if ($table == $tableName) {
				$this->currentTable = $tableName;
				return;	// Done
			}
		}	
	}
	
	public function setOrderBy($table,$field){
		$this->orderBy = " ORDER BY `$table`.$field ";
	}
	
	public function buildConditionsString(){
		if (sizeof($this->conditions) == 0){
			return;
		}
		$ret = " WHERE ";
		for ($i=0;$i<sizeof($this->conditions);$i++){
			if($i < sizeof($this->conditions) -1 ){
				$ret .= $this->conditions[$i] . " AND ";
			} else {
				$ret .= $this->conditions[$i];
			}
		}
		return $ret;
	}
}

class SelectQuery extends Query {
	
	public function SelectQuery(){
		
	}
	
	public function build(){
		// Build Query String
		$qstring = "SELECT ";
		
		// Field List
		for ($i = 0; $i < sizeof($this->fields); $i++) {
			// Check for aliases
			$aliasValue = '';
			foreach($this->aliases as $f=>$a){
				if ($f == $this->fields[$i]){
					$aliasValue = $a;
					break;
				}
			}			
			$fieldValue = $this->fields[$i] . ((empty($aliasValue))?  "" : " AS $aliasValue");
			if ($i < sizeof($this->fields) - 1) {
				$qstring .= $fieldValue . ', ';
			} else {
				$qstring .= $fieldValue;
			}
		}		
		
		$qstring .= " FROM ";
		
		// Table List
		for ($i = 0; $i < sizeof($this->tables); $i++) {
			if ($i < sizeof($this->tables) - 1) {
				$qstring .= "`{$this->tables[$i]}`,";
			} else {
				$qstring .= "`{$this->tables[$i]}`";
			}
		}
		
		
		// WHERE Clauses, if any
		if (sizeof($this->conditions) > 0){
			$qstring .= " WHERE ";
			
			for ($i = 0; $i < sizeof($this->conditions); $i++) {
				if ($i < sizeof($this->conditions) - 1) {
					$qstring .= $this->conditions[$i] . ' AND ';
				} else {
					$qstring .= $this->conditions[$i];
				}
			}				
		}
		
		if (strlen($this->orderBy) > 0) {
			$qstring .= $this->orderBy . " ";
		}
		// Return the SQL string representation of the query components
		return $qstring;
	}	
}

class UpdateQuery extends Query {
	public function UpdateQuery(){
		
	}
	
	public function build(){
		
	}
}

class InsertQuery extends Query {
	
}

class DeleteQuery extends Query {
	
}

/*
 * SQL EXAMPLE STATEMENTS
 * 
 * // Add a field after a certain existing field
 * ALTER TABLE `users` ADD `deleteme` VARCHAR( 20 ) NOT NULL AFTER `Disclaimer` ;
 * 
 * // Delete a given field from the table
 * ALTER TABLE `users` DROP `deleteme` 
 * 
 */
?>