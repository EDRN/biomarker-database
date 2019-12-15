<?php
class BiomarkerDataset extends AppModel {
	
	var $useTable = "biomarker_datasets";
	var $name     = "BiomarkerDataset";
	
	function __construct() {
		parent::__construct();
	}
	
	function getDatasetsForBiomarker($id) {
		$q = "SELECT * FROM `biomarker_datasets` WHERE `biomarker_id`='{$id}'";
		$results = $this->query($q);
		
		$dsAssociated = array();
		foreach ($results as $result) {
			$dsname = $result['biomarker_datasets']['dataset_id'];
			$dsAssociated[$dsname] = array(
				"name" => $dsname,
				"id"   => '' // future use
			);
		}
		
		return $dsAssociated;		
	}
	
	public function setDatasetsForBiomarker($id,$datasetIds = array()) {
		
		// Delete any existing entries
		$q = "DELETE FROM `biomarker_datasets` WHERE `biomarker_id`='{$id}'";
		$this->query($q);
		
		// Add the provided entries
		$q = "INSERT INTO `biomarker_datasets` (`biomarker_id`,`dataset_id`) VALUES ";
		$values = array();
		foreach ($datasetIds as $ds) {
			$values[] = " ('{$id}','".mysql_escape_mimic($ds)."') ";
		}
		$q .= implode(' , ',$values);
		if (count($values) > 0) {
			$this->query($q);
		}
	}
	
	public function getEcasDatasets() {
		$ecasRDFData = file_get_contents('http://edrn.jpl.nasa.gov/fmprodp3/rdf/dataset?type=ALL');
		return ($this->rdfToArray($ecasRDFData));
	}
	
	protected function rdfToArray($contents) {
		$parser = xml_parser_create();
		xml_parser_set_option($parser,XML_OPTION_CASE_FOLDING, 0);
		xml_parse_into_struct($parser, $contents, $values,$index);
		xml_parser_free($parser);
		
		$m_array = array();
		$arr     = &$m_array;
		
		foreach ($values as $val) {
	        $tag=$val['tag'];
	        if ($val['type']=='open') {
	            if (isset($arr[$tag])) {
	                if (isset($arr[$tag][0])) {
	                	$arr[$tag][]=array();
	                } else {
	                	$arr[$tag]=array($arr[$tag], array());
	                }
	                $cv=&$arr[$tag][count($arr[$tag])-1];
	            } else {
	            	$cv=&$arr[$tag];
	            }
	            if (isset($val['attributes'])) {
	            	foreach ($val['attributes'] as $k=>$v) $cv['_attributes'][$k]=$v;
	            }
	            $cv['_children']=array();
	            $cv['_children']['_ptr']=&$arr;
	            $arr =& $cv['_children'];
	
	        } else if ($val['type']=='complete') {
	            if (isset($arr[$tag])) { // same as open
	                if (isset($arr[$tag][0])) { 
	                	$arr[$tag][]=array();
	                } else {
	                	$arr[$tag]=array($arr[$tag], array());
	                }
	                $cv =& $arr[$tag][count($arr[$tag])-1];
	            } else {
	            	$cv=&$arr[$tag];
	            }
	            
	            if (isset($val['attributes'])) {
	            	foreach ($val['attributes'] as $k=>$v) $cv['_attributes'][$k]=$v;
	            }
	            $cv['_value']=(isset($val['value']) ? $val['value'] : '');
	
	        } else if ($val['type']=='close') {
	            $arr=&$arr['_ptr'];
	        }
	    }    
	    
	    $this->del_ptr($m_array);
	    return $m_array;
	}
	
	private function del_ptr(&$arr) {
		foreach ($arr as $k=>$v) {
			if ($k === '_ptr') {
				unset($arr[$k]);
			} else if (is_array($arr[$k])) {
				$this->del_ptr($arr[$k]); // Recurse
			}
		}
	}
}