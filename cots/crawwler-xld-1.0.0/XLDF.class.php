<?php

require_once("GenericBuilder.class.php");
require_once("QueryBuilder.class.php");

class XLDF extends GenericBuilder {
	


	var $queries = array();		// The queries discovered in the XML file;
	var $currentQuery = '';		// The query currently being built;
	var $updateQuery = '';		// The query skeleton used to update fields
	var $result			= '';	// The database result as a resultset
	var $resultArray	= '';	// The database result as an assoc. array

	var $type	= '';		// The type of query (99% of time is SELECT)
	
	var $bInDatabase 	= false;	// Are we processing db definitions?
	var $bHasQuery		= false;	// Does this form interact with a db?
	var $bInQuery		= false;	// Are we processing query definitions?
	var $bInUpdateQuery	= false;	// Arew we processing the 'update' query?
	var $bQueryBuilt	= false;	// Has the query been built yet?
	var $bQueryRun		= false;	// Has the query been executed yet?

	
	var $conn			= '';		// The database connection
	var $phpHandlerURL	= '';		// The url to the php AJAX handler
	var $bEditable		= true;		// Is this form editable?

	var $iterationCounter = 0;		// Stores result rows displayed so far


	var $contents		= array();	// The form contents implemented as a FIFO queue


	

	
	function XLDF($xmlFilePath, 
						$dbConnection = '', 
						$phpHandlerURL = '', 
						$bEditable = true){
		
		// Create the XML Parser
		$this->parser = new XParse($xmlFilePath);
		
		
		// Override the Handlers 
		$this->parser->SetElementHandler(array($this,"elementStart"),
										array($this,"elementStop") );
		$this->parser->SetCharacterDataHandler(array($this,"characterData") );
		
		// Set the DB Connection
		$this->conn = $dbConnection;
		
		// Set the URL for the AJAX Handler
		$this->phpHandlerURL = $phpHandlerURL;
		
		// Set the Editable flag
		$this->bEditable = $bEditable;
	}
	

	
	function elementStart($parser,$element_name,$element_attrs){	
		// Tags that should be processed immediately
		if ($element_name == 'PAGE' || $element_name == 'DATABASE' ||
			$element_name == 'QUERY' || $element_name == 'SELECT' ||
			$element_name == 'UPDATE' || $element_name == 'WHERE' ||
			$element_name == 'CLAUSE' || $element_name == 'TABLE' ||
			$element_name == 'FIELD' || $element_name == 'FORM' ||
			$element_name == 'ORDERBY' ){
				$this->processTag($element_name,$element_attrs);
		} else {		
			// content tags that should be added to contents
			$arr = array("tag" => $element_name, "attrs" => $element_attrs);
			$this->contents[] = $arr;
		}
	}
	
	function processContents() {
		if ($this->bHasQuery && ! $this->bQueryRun ) {
			die (" ERROR! &lt;database&gt; block must preceed form contents block in &lt;form&gt; definition ");
		}
		
		if ($this->bHasQuery) {
			// Complex (database enabled) Forms
			$this->iterationCount = 0;	// Reset counter
			while ($this->resultArray = $this->result->fetchRow(DB_FETCHMODE_ASSOC)) {
				foreach ($this->contents as $tagdata) {
					if ($tagdata["attrs"] == -1){
						// process end tag 
						$this->processTagClose($tagdata["tag"]);
					} else {
						// process start tag
						$this->processTag($tagdata["tag"],$tagdata["attrs"]);
					}
				}
				$this->iterationCounter++;			
			}	
		} else {
			// Simple Forms (those not containing database queries)
			foreach ($this->contents as $tagdata) {
				if ($tagdata["attrs"] == -1){
					// process end tag 
					$this->processTagClose($tagdata["tag"]);
				} else {
					// process start tag
					$this->processTag($tagdata["tag"],$tagdata["attrs"]);
				}
			}	
		}
	}
	
	function getActualValues($input) {
		$output = $input;	
		$matches = array();
		$tmpVal = preg_match_all('/{{{match:([a-zA-Z0-9]+)}}}/',$input,$matches,PREG_PATTERN_ORDER);
		if (sizeof($matches) == 0){
			return $input; // Input *is* the actual value
		}
		
		foreach ($matches[1] as $match) {
			$trueValue = $this->resultArray[$match];
			$output = preg_replace('/{{{match:'.$match.'}}}/',$trueValue,$output);
		} 

		return $output;
	}
	

	
	function processTag($element_name,$element_attrs){
		
		switch ($element_name){
			/*
			 * QUERY
			 */
			case "DATABASE":
				$this->bInDatabase = true;
				$this->tables = array();
				$this->fields = array();
				$this->wheres = array();
				$this->whereString = '';
				$this->type = '';
				$this->bInQuery = false;
				$this->bQueryBuilt = false;
				$this->bQueryRun = false;
				$this->query = '';
				$this->result = null;
				$this->resultArray = array();
				$this->contents = array();
				
				break;
			case "QUERY":
				$this->bHasQuery = true;
				$this->bInQuery = true;
				break;
			case "SELECT":
				$this->type="SELECT";
				// Create a new SelectQuery object
				$this->currentQuery = new SelectQuery();
				break;
			case "UPDATE":
				$this->updateQuery = new UpdateQuery();
				$this->bInUpdateQuery = true;
				
				break;
			case "TABLE":
				if ($this->bInUpdateQuery){
					if (!empty($element_attrs['NAME'])){
						$this->updateQuery->addTable($element_attrs['NAME']);
						$this->updateQuery->setCurrentTable($element_attrs['NAME']);
					} else {
						die("<br/>ERROR: Found malformed TABLE element (Missing NAME attribute on line ".xml_get_current_line_number($parser).")");
					}
				} else {
					if (!empty($element_attrs['NAME'])){
						$this->currentQuery->addTable($element_attrs['NAME']);
						$this->currentQuery->setCurrentTable($element_attrs['NAME']);
					} else {
						die("<br/>ERROR: Found malformed TABLE element (Missing NAME attribute on line ".xml_get_current_line_number($parser).")");
					}
				}
				break;
			case "FIELD":
				if (!empty($element_attrs['NAME'])){
					$this->currentQuery->addField($element_attrs['NAME']);
					if (!empty($element_attrs['AS'])){
						$this->currentQuery->addAlias($element_attrs['NAME'],$element_attrs['AS']);
					}
				} else {
					die("<br/>ERROR: Found malformed FIELD element (Missing NAME attribute on line ".xml_get_current_line_number($parser).")");
				}
				break;
			case "ORDERBY":
				if (empty($element_attrs['TABLE']) || empty($element_attrs['FIELD'])){
					die("<br/>ERROR: Found ORDERBY without both TABLE and FIELD specified on line ".xml_get_current_line_number($parser).")");
				}
				$this->currentQuery->setOrderBy($element_attrs['TABLE'],$element_attrs['FIELD']);
				break;
			case "CLAUSE":
					if (!empty($element_attrs['TABLE']) && !empty($element_attrs['FIELD']) && !empty($element_attrs['VALUE'])){
						$newWhere = "`{$element_attrs['TABLE']}`.{$element_attrs['FIELD']}= ";
						
						// Take the data from the VALUE token
						$data = $element_attrs['VALUE'];
						if (!empty($element_attrs['SOURCE'])){
							switch ($element_attrs['SOURCE']){
								case 'GET':
									// Take the data from the GET parameter instead
									$data = "\"".$_GET[$element_attrs['VALUE'] ]."\"";
									break;
								case 'POST':
									// Take the data from the POST parameter instead
									$data = "\"".$_POST[$element_attrs['VALUE'] ]."\"";
									break;
								case 'SESSION':
									if (!empty($element_attrs['PREFIX'])){
										// Take the data from the SESSION parameter with the given prefix
										$prefix = $element_attrs['PREFIX'];
										$data = "\"".$_SESSION[$prefix][$element_attrs['VALUE'] ]."\"";
									} else {
										// Take the data from the SESSION parameter
										$data = $_SESSION[$element_attrs['VALUE'] ];
									}
									break;
								default:
									// Default behavior is to treat 'source' as a database
									// table name (this allows specification joins like:
									// WHERE programs.ID = reports.ProgramID, which look like
									// this in XML:
									// <clause table="programs" field="ID" value="ProgramID" source="reports"/>
									$data =  "`{$element_attrs['SOURCE']}`.{$element_attrs['VALUE']}";
									break;

							}
							
						}
						$newWhere .= $data;
						//$this->wheres[] = $newWhere;
						if ($this->bInUpdateQuery){
							$this->updateQuery->addCondition($newWhere);
						} else {
							$this->currentQuery->addCondition($newWhere);
						}
					} else {
					die("<br/>ERROR: Found malformed CLAUSE element (Missing TABLE or FIELD or VALUE attribute on line ".xml_get_current_line_number($parser).")");
				}
				break;
			
			
			
			
			
			
			/*
			 * CONTENT
			 */
			case "FORM":
				$this->html .= "<table ";
				$this->html .= empty($element_attrs['CLASS'])? 'class="xldForm ' : 'class="xldForm '. $element_attrs['CLASS']. '" ';
				$this->html .= empty($element_attrs['STYLE'])? '' : 'style="'. $element_attrs['STYLE']. '" ';						
				$this->html .= empty($element_attrs['NAME'])? '' : 'name="'. $element_attrs['NAME']. '" ';
				$this->html .= empty($element_attrs['ID'])? '' : 'id="'. $element_attrs['ID']. '" ';
				$this->html .= ">";
				if (!empty($element_attrs['ONLOAD'])){
					$this->jsCoda .= $element_attrs['ONLOAD'];
				}
				break;
			case "BANNER":
				$this->html .= "<tr><td ";
				$this->html .= empty($element_attrs['CLASS'])? 'class="xldBanner" ' : 'class="xldBanner '. $element_attrs['CLASS']. '" ';
				$this->html .= empty($element_attrs['STYLE'])? '' : 'style="'. $element_attrs['STYLE']. '" ';						
				$this->html .= empty($element_attrs['NAME'])? '' : 'name="'. $element_attrs['NAME']. '" ';
				$this->html .= empty($element_attrs['ID'])? '' : 'id="'. $element_attrs['ID']. '" ';
				$this->html .= empty($element_attrs['COLSPAN'])? '' : "colspan=\"$element_attrs[COLSPAN]\" ";
				$this->html .= empty($element_attrs['ROWSPAN'])? '' : "colspan=\"$element_attrs[ROWSPAN]\" ";
				$this->html .= ">";
				$this->html .= empty($element_attrs['TITLE'])? '&nbsp;' : $element_attrs['TITLE'];
				$this->html .= "</td></tr>";
				break;	
			case "HEADERS":		
				// We only print headers 1x
				if ($this->iterationCounter > 0){
					break;	
				} // otherwise, print the row-opener
			case "ROW":
				$this->html .= "<tr ";
				$this->html .= empty($element_attrs['CLASS'])? 'class="xldRow" ' : 'class="xldRow '. $element_attrs['CLASS']. '" ';
				$this->html .= empty($element_attrs['STYLE'])? '' : 'style="'. $element_attrs['STYLE']. '" ';						
				$this->html .= empty($element_attrs['NAME'])? '' : 'name="'. $element_attrs['NAME']. '" ';
				$this->html .= empty($element_attrs['ID'])? '' : 'id="'. $element_attrs['ID']. '" ';
				$this->html .= ">";
				break;
			case "CELL":
				$trueIDValue = empty($element_attrs['ID'])? '' : $element_attrs['ID'];
				
				
				$this->html .= "<td ";
				/**
				 * Allow for styling (skinning) of cells based on cell content type:
				 */
				switch ($element_attrs['TYPE']){
					case "label":
						$this->html .= empty($element_attrs['CLASS'])? 'class="xldLabel" ' : 'class="xldLabel '. $element_attrs['CLASS']. '" ';
						break;
					case "link":
						$this->html .= empty($element_attrs['CLASS'])? 'class="xldLink" ' : 'class="xldLink '. $element_attrs['CLASS']. '" ';
						break;
					case "text":
						$this->html .= empty($element_attrs['CLASS'])? 'class="xldText" ' : 'class="xldText '. $element_attrs['CLASS']. '" ';
						break;
					case "number":
						$this->html .= empty($element_attrs['CLASS'])? 'class="xldNumber" ' : 'class="xldNumber '. $element_attrs['CLASS']. '" ';
						break;
					case "select":
						$this->html .= empty($element_attrs['CLASS'])? 'class="xldSelect" ' : 'class="xldSelect '. $element_attrs['CLASS']. '" ';
						break;
					default:
						$this->html .= empty($element_attrs['CLASS'])? '' : 'class="'. $element_attrs['CLASS']. '" ';
						break;
				}
				$this->html .= empty($element_attrs['STYLE'])? '' : 'style="'. $element_attrs['STYLE']. '" ';						
				$this->html .= empty($element_attrs['NAME'])? '' : 'name="td_'. $element_attrs['NAME']. '" ';
				$this->html .= empty($element_attrs['ID'])? '' : 'id="td_'. $this->getActualValues($element_attrs['ID']) . '" ';
				$this->html .= empty($element_attrs['COLSPAN'])? '' : "colspan=\"$element_attrs[COLSPAN]\" ";
				$this->html .= empty($element_attrs['ROWSPAN'])? '' : "colspan=\"$element_attrs[ROWSPAN]\" ";
				$this->html .= ">";
				switch ($element_attrs['TYPE']){
					/* STATIC LABEL */
					case "label":
						$this->html .= empty($element_attrs['VALUE'])? '&nbsp;' : $element_attrs['VALUE'];
						break;
					/* HYPERLINK */
					case "link":
						$this->html .= "<a ";
						$this->html .= empty($element_attrs['LINKCLASS'])? '' : 'class="'. $element_attrs['LINKCLASS']. '" ';
						$this->html .= empty($element_attrs['LINKSTYLE'])? '' : 'style="'. $element_attrs['LINKSTYLE']. '" ';
						$this->html .= empty($element_attrs['LINKID'])? '' : 'id="'. $this->getActualValues($element_attrs['LINKID']) . '" ';
						$this->html .= empty($element_attrs['HREF'])? '' : 'href="'. $this->getActualValues($element_attrs['HREF']). '" ';
						$this->html .= ">";
						$this->html .= empty($element_attrs['VALUE'])? '': $this->getActualValues($element_attrs['VALUE']);
						$this->html .= "</a>";
						break;
					/* TEXT INPUT */
					case "text":
						$this->addTextInput($element_attrs,false);
						break;
					case "number":
						$this->addTextInput($element_attrs,true);
						break;
					case "select":
						$this->addSelectInput($element_attrs);
						break;
				}
				$this->html .= "</td>";
				break;
			case "HEADER":
				// We only print headers 1x
				if ($this->iterationCounter > 0){
					break;
				}
				$this->html .= "<th ";
				$this->html .= empty($element_attrs['CLASS'])? '' : 'class="'. $element_attrs['CLASS']. '" ';
				$this->html .= empty($element_attrs['STYLE'])? '' : 'style="'. $element_attrs['STYLE']. '" ';						
				$this->html .= empty($element_attrs['NAME'])? '' : 'name="td_'. $element_attrs['NAME']. '" ';
				$this->html .= empty($element_attrs['ID'])? '' : 'id="td_'. $this->getActualValues($element_attrs['ID']) . '" ';
				$this->html .= empty($element_attrs['COLSPAN'])? '' : "colspan=\"$element_attrs[COLSPAN]\" ";
				$this->html .= empty($element_attrs['ROWSPAN'])? '' : "colspan=\"$element_attrs[ROWSPAN]\" ";
				$this->html .= ">";
				$this->html .= empty($element_attrs['VALUE'])? '' : $element_attrs['VALUE'];
				$this->html .= "</th>";
				break;
			case "CUSTOMJS":
				$this->jsCoda .= empty($element_attrs['CONTENT'])? '' : $element_attrs['CONTENT'] . ' ';
				break;
			default: 
				break;
		}
	}
	
	function addTextInput($element_attrs,$isNumeric){
		// Capture parameters specific to textbox based input
		$size 	= empty($element_attrs['SIZE'])? 20 : $element_attrs['SIZE'];
		$rows   = empty($element_attrs['ROWS'])? 1 : $element_attrs['ROWS'];
		$js		= empty($element_attrs['ONSAVE'])? '' : $element_attrs['ONSAVE'];
		
	
		// Build the associated javascript actions
						
		// Map alias, if used, to actual database field name
		// Treat $element_attrs['DB'] as an alias. if it IS an alias, the real field name will be returned:
		$realFieldName = $this->currentQuery->getFieldFor($element_attrs['DB']);
		if (empty($realFieldName)){
			$realFieldName = $element_attrs['DB']; // provided value was the field name
		}

		// If the form should be editable...
		if($this->bEditable){
			// ... and the field should be editable
			if (empty($element_attrs['EDITABLE']) || (!empty($element_attrs['EDITABLE']) && $element_attrs['EDITABLE'] != 'no')){//FIXME: ugly
				// Build the Javascript code to support this input
				if (!empty($element_attrs['VALID'])){
					$this->addInPlaceEditor($this->getActualValues($element_attrs['ID']),$realFieldName,$element_attrs['VALID'],$size,$rows,$js);
				} else {
					$this->addInPlaceEditor($this->getActualValues($element_attrs['ID']),$realFieldName,'',$size,$rows,$js);	
				}
			}
		}

		// Print the textual placeholder
		$this->printPlaceholderLabel($element_attrs,$isNumeric);
	}
	
	function addSelectInput($element_attrs){
		// Capture parameters specific to selectbox based input

		
		// Build the associated javascript actions

		// Map alias, if used, to actual database field name
		// Treat $element_attrs['DB'] as an alias. if it IS an alias, the real field name will be returned:
		$realFieldName = $this->currentQuery->getFieldFor($element_attrs['DB']);
		if (empty($realFieldName)){
			$realFieldName = $element_attrs['DB']; // provided value was the field name
		} 

		// Determine Labels
		$optionsArray = explode(",",$element_attrs['OPTIONS']);
		$values = array();
		$labels = array();
		foreach ($optionsArray as $kvpair){
			$pieces = explode(":",$kvpair);
			if(sizeof($pieces) == 2){
				$values[] = $pieces[0];
				$labels[] = $pieces[1];	
			} else {
				die("Invalid options specified");
			}
		}
		
		// If the form should be editable...
		if($this->bEditable){
			// ... and the field should be editable
			if (empty($element_attrs['EDITABLE']) || (!empty($element_attrs['EDITABLE']) && $element_attrs['EDITABLE'] != 'no')){//FIXME: ugly
				// Build the Javascript code to support this input
				$this->addInPlaceCollectionEditor($this->getActualValues($element_attrs['ID']),$realFieldName,$values,$labels);	
			}
		}
						
		// Use the db result as a first attempt
		$displayValue = empty($element_attrs['DB'])? '' : $this->resultArray[$element_attrs['DB'] ];
		// See if we can't use a label instead
		for($i=0;$i<sizeof($values);$i++){
			if($this->resultArray[$element_attrs['DB'] ] == $values[$i]){
				$displayValue = stripslashes($labels[$i]); // display the associated label
			}			
		}	
				
		
		// Print the textual placeholder
		$this->printPlaceholderLabel($element_attrs,false,$displayValue);
	}
	
	function printPlaceholderLabel($element_attrs,$isNumeric,$displayValue = ''){
		$this->html .= '<div ';
		$this->html .= empty($element_attrs['CLASS'])? '' : 'class="'. $element_attrs['CLASS']. '" ';
		$this->html .= empty($element_attrs['STYLE'])? '' : 'style="'. $element_attrs['STYLE']. '" ';						
		$this->html .= empty($element_attrs['NAME'])? '' : 'name="'. $element_attrs['NAME']. '" ';
		$this->html .= empty($element_attrs['ID'])? '' : 'id="'. $this->getActualValues($element_attrs['ID']) . '" ';
		$this->html .= '>';
		
		if ($displayValue != ''){
			// Display the override value
			$this->html .= $displayValue;
			
		} else {
			// Get the database value out of the result set
			$dbValue = stripslashes($this->resultArray[$element_attrs['DB'] ]);
			
			if (!empty($element_attrs['EDITABLE']) && $element_attrs['EDITABLE'] == 'no'){
				// The field is not editable. We still need to differentiate between numbers and text
				// just in case there is a client javascript function depending on a numeric value
				if ($isNumeric) { $this->html .= (strlen($dbValue) == 0)? '0' : $dbValue; }
				else {$this->html .= (strlen($dbValue) == 0)? '' : $this->nl2br2($dbValue); }
			} else {
				// The field IS editable. Figure out what label to display as a placeholder
				// based on the type of input expected
				if($isNumeric){ $this->html .= (strlen($dbValue) == 0)? '0' : $dbValue; }
				else { $this->html .= (strlen($dbValue) == 0)? 'click to edit' : nl2br($dbValue); }
			}
		} 
		$this->html .= '</div>';
	}
	
	
	function elementStop($parser,$element_name){
		
		// Tags that should be processed immediately
		if ($element_name == 'PAGE' || $element_name == 'DATABASE' ||
			$element_name == 'QUERY' || $element_name == 'SELECT' ||
			$element_name == 'UPDATE' || $element_name == 'WHERE' ||
			/*$element_name == 'CLAUSE' || */$element_name == 'TABLE' ||
			/*$element_name == 'FIELD' || */ $element_name == 'FORM' ){
				$this->processTagClose($element_name);
		} else {		
			// content tags that should be added to contents
			$arr = array("tag" => $element_name, "attrs" => -1);
			$this->contents[] = $arr;
		}
	}
	
	function processTagClose($element_name) {		
		
		switch ($element_name){
			case "DATABASE":
				$this->bInDatabase = false;
				break;
			case "QUERY":
				$this->bInQuery = false;
				//$this->buildQuery();
				//$this->runQuery();
				// Add this query to the queries array
				$this->queries[] = $this->currentQuery;
				// Build and Execute the query
				$queryString = $this->currentQuery->build();
				$this->result = $this->runQuery($queryString,DEBUGGING);
				if(DB::isError($this->result)){
					die("Error running query: " . $this->result->getMessage());
				}
				if($this->result->numRows() == 0){
					die("No results matched the query");
				}
				$this->bQueryRun = true;
				break;
			case "SELECT":
				break;
			case "UPDATE":
				$this->bInUpdateQuery = false;
				break;
			case "HEADERS":
				// We only print headers 1x
				if ($this->iterationCounter > 0){
					break;
				} // print the close-row:
			case "ROW":
				$this->html .= "</tr>";
				break;
			case "FORM":
				// Actually process the form contents into HTML + JS
				$this->processContents();
				$this->html .= "</table>";
				$this->bHasQuery = false;	// Reset flag for next form
				$this->contents = array();	// Reset Contents for next form
				break;
			default: 
				break;
		}
	}
	
	function characterData($parser,$data){
		$this->html .= $data;
	}

	
	function RunQuery($queryString,$bEchoQuery = false){
		if ($bEchoQuery) {echo $queryString; }
		//$this->html .= $this->query;
		return $this->conn->query($queryString);
	}
	
	/*
	 * Javascript Functionality
	 * 
	 * This code assumes the existence the script.aculo.us javascript tool kit
	 * available online from http://script.aculo.us
	 * 
	 */
	
	function addInPlaceEditor($id,$dbField,$validation,$cols,$rows=1,$js=''){
		$this->jsCoda .= "new Ajax.InPlaceEditor('$id','$this->phpHandlerURL', { callback: function(form, value) { return 'table={$this->updateQuery->tables[0]}&field=$dbField&validation='+ escape('$validation') + '&value=' + escape(value) + '&wherestring=' + escape('".$this->getActualValues($this->updateQuery->buildConditionsString())."')}, onComplete: function(transport, element) {new Effect.Highlight(element, {startcolor: this.options.highlightcolor});$js}, cols:$cols,rows:$rows });\r\n";
	}
	
	function addInPlaceCollectionEditor($id,$dbField,$values,$labels){
		// Build Option String
		$optionString = '[';			// Opener
		if(sizeof($values) != sizeof($labels) || sizeof($values) <= 1){
			die("Invalid Option List passed to addInPlaceCollectionEditor: ".print_r($labels));
		}
		$optionString .= "['".$values[0]."','".$labels[0]."']";
		for($i=1;$i < sizeof($values);$i++){
			$optionString .= ",['".$values[$i]."','".$labels[$i]."']";
		}
		$optionString .= ']';			// Closer

		//echo "OPTION STRING IS: $optionString";

		$valuesString = $values[0];
		for($i=1; $i < sizeof($values);$i++){
			$valuesString .= ",".$values[$i];
		}
		
		$labelsString = $labels[0];
		for($i=1; $i < sizeof($labels);$i++){
			$labelsString .= ",".$labels[$i];
		}

		//echo "<p>VALUESSTRING: $valuesString </p>";
		//echo "<p>LABELSTRING: $labelsString </p>";
		
		$this->jsCoda .= "new Ajax.InPlaceCollectionEditor('$id','$this->phpHandlerURL', { collection: $optionString, callback: function(form, value) { return 'table={$this->updateQuery->tables[0]} &field=$dbField&value=' + escape(value) + '&wherestring=' + escape('".$this->getActualValues($this->updateQuery->buildConditionsString())."') + '&labels=' + escape('$labelsString') + '&values=' + escape('$valuesString') }});\r\n";
	
	}

	function nl2br2($string) {
		$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
		return $string;
	}
}
?>