<?php
/**
 * 	EDRN Biomarker Database
 *  Curation Webapp
 * 
 *  Author: Andrew F. Hart (andrew.f.hart@jpl.nasa.gov)
 *  
 *  Copyright (c) 2008, California Institute of Technology. 
 *  ALL RIGHTS RESERVED. U.S. Government sponsorship acknowledged.
 * 
 */

class ConfigParse {

	public static function parse($path) {
		$content = @file_get_contents($path);
		if ($content == ''){return array();}
		preg_match_all(
			"/([a-zA-Z_ ]+)=([0-9a-z:\/A-Z\-_@\. ]+)/",
			$content,$matches);
		for ($i =0; $i< sizeof($matches[1]);$i++) {
			$config[$matches[1][$i]] = $matches[2][$i];
		}
		return $config;
	}
}