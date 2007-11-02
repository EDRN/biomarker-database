<?php

require_once("Validate.php");	// Include the PEAR Validation class


class GenericHandler {

	public static function ValidateInput($value,$validationCriteria){
		$validateResult = 0;
		$validationArgs = explode(",",$validationCriteria);
		//print_r($validationArgs);

		switch ($validationArgs[0]){
			case 'string':
				$arr = array('format' => VALIDATE_ALPHA.VALIDATE_NUM.VALIDATE_PUNCTUATION."'");
				if (!empty($validationArgs[1])) { $arr['min_length'] = $validationArgs[1]; }
				if (!empty($validationArgs[2])) { $arr['max_length'] = $validationArgs[2]; }
					
				//print_r($arr);
					
				$validateResult = Validate::string($value,$arr);
				break;
			case 'urn':
				$arr = array('format' => VALIDATE_ALPHA.VALIDATE_NUM.'\:\-/');
				if (!empty($validationArgs[1])) { $arr['min_length'] = $validationArgs[1]; }
				if (!empty($validationArgs[2])) { $arr['max_length'] = $validationArgs[2]; }
				
				// print_r($arr);

				$validateResult = Validate::string($value,$arr);
				break;
			case 'name':
				$arr = array('format' => VALIDATE_NAME);
				if (!empty($validationArgs[1])) { $arr['min_length'] = $validationArgs[1]; }
				if (!empty($validationArgs[2])) { $arr['max_length'] = $validationArgs[2]; }
					
				//print_r($arr);
					
				$validateResult = Validate::string($value,$arr);
				break;
			case 'email':
					
				$validateResult = Validate::email($value);
				break;
			case 'phone':
				$arr = array('format' => VALIDATE_NUM);
				$arr['min_length'] = '10';
				$arr['max_length'] = '10';
					
				//print_r($arr);		
					
				$validateResult = Validate::string($value,$arr);
				break;
			case 'number':
				$arr = array();
				if (!empty($validationArgs[1])) { $arr['min'] = $validationArgs[1]; }
				if (!empty($validationArgs[2])) { $arr['max'] = $validationArgs[2]; }
				
				// print_r($arr);

				$validateResult = Validate::number($value,$arr);
				break;
			case 'date':
				$arr = array();
				if (!empty($validationArgs[1])) { $arr['format'] = $validationArgs[1]; }
				
				//print_r($arr);
				
				$validateResult = Validate::date($value,$arr);
				break;
			case 'money':
				$arr = array();
				$arr['decimal'] = '.';	// Decimal point character
				$arr['dec_prec'] = 2;	// Number of allowed decimal points
				if (empty($validationArgs[1])) { $arr['min'] = $validationArgs[1]; }
				if (empty($validationArgs[2])) { $arr['max'] = $validationArgs[2]; }
				
				// print_r($arr);

				$validateResult = Validate::number($value,$arr);
				break;
		}

		//echo "<p>RESULT WAS: $validateResult</p>";
		if ($validateResult == 1){
			return true;
		} else {
			return false;
		}
	}

	public static function SaveInput($conn,$table,$field,$value,$conditions,$labels=null,$values=null){
		//echo "<p> CONDITIONS WERE: $conditions </p>";
		$q = "UPDATE `$table` SET $field=\"$value\" $conditions ";
		//echo "<p> QUERY WAS: $q </p>";
		$result = $conn->query($q);
		if (DB::isError($result) ){
			echo $result->getMessage();
			$val = $conn->getOne("SELECT $field FROM `$table` $conditions");
			// Should we return a label?
			$labelsArray = explode(",",$labels);
			$valuesArray = explode(",",$values);
			if(!empty($labels) && !empty($values)){
				for($i=0;$i<sizeof($valuesArray);$i++){
					if($valuesArray[$i] == $val){
						echo stripslashes($labelsArray[$i]);	// returning label for value
						break;
					}
				}
			} else {
				echo ($val == '')? 'click to edit' : self::nl2br2(stripslashes($val));	// returning raw value
			}
		} else {
			// Should we return a label?

			$labelsArray = explode(",",$labels);
			$valuesArray = explode(",",$values);
			if(!empty($labels) && !empty($values)){
				for($i=0;$i<sizeof($valuesArray);$i++){
					if($valuesArray[$i] == $value){
						echo stripslashes($labelsArray[$i]);	// returning label for value
						break;
					}
				}
			} else {
				echo ($value == '')? 'click to edit' : self::nl2br2(stripslashes($value));	// returning raw value
			}
		}
	}
	
	public static function nl2br2($string) {
		$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
		return $string;
	}
}
?>