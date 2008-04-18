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
require_once("FileWriter.class.php");

class AjaxInterfaceGenerator {
	
	public static function generateUpdateFile(&$mp) {
		$content = "";
		foreach ($mp->objects as $o) {
			$content .= "case '{$o->type}':\r\n"
				. "\t\t\t\t\$o = {$o->type}Factory::Retrieve(\$_POST['id']);\r\n";
			foreach ($o->attributes as $attr) {
				if ($attr->name == "objId") {continue;}
				$content .= "\t\t\t\tif (isset(\$_POST['{$attr->name}'])) {"
					."\$o->set{$attr->name}(\$_POST['{$attr->name}']);"
					."echo (\$o->get{$attr->name}() == '')"
					."? 'click to edit' "
					.": \$o->get{$attr->name}();"
					."exit();}\r\n";
			}
			$content .= "\t\t\t\tbreak;\r\n";
		}
		$content .= "\t\t\tdefault:\r\n\t\t\t\techo 'error';\r\n\t\t\t\texit();";
		$s = <<<END
	/**
	 * Processes updates to data objects sent via AJAX
	 *
	 * When including this file, please remember that it needs access to your
	 * app.php file, which should be included first. 
	 */
	 
	if (isset(\$_POST['action']) && \$_POST['action'] == 'update') {
		switch (\$_POST['object']) {
			{$content}
		}
	}

END;
		return $s;
	}
	
	
	
	
	
}

?>