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

require_once("ModelObject.class.php");
require_once("ModelObjectAttribute.class.php");
require_once("extensions/crawwler/cwsp/utilities/common/FileWriter.class.php");
	
	
class ModelBrowserGenerator {
	private $outputDir = '';
	private $object    = '';
	private $v;
	private $e;
	private $c;
	
	public function __construct($outputDir = '/path/to/model/browser',$object){
		$this->outputDir = $outputDir;
		$this->object = $object;
		$this->v = '';
		$this->c = '';	
	}
	
	private function createView() {
		$this->v .= "<!-- TBS template for {$this->object->type} object browser -->\r\n"
				. "<div id=\"xpress_header\"></div>"
				. "<div id=\"xp_browser_wrapper\">"
				. "<h1 class=\"xp_browser_nav\"><a href=\"./\">Model Browser</a> :: "
				. "{$this->object->type}</h1>\r\n"
				. "<div class=\"xp_browser_new\">\r\n"
				. "<h3>Create a new {$this->object->type} Object:</h3>\r\n"
				. "<form method=\"POST\">\r\n"
				. "<table>\r\n";
		foreach ($this->object->attributes as $attr) {
			if ($attr->name == "objId") {continue;}
			$this->v .= "<tr><td>{$attr->name}:</td><td><input type=\"text\" name=\"{$attr->name}\"/></td></tr>\r\n";
		}
		foreach ($this->object->relationships as $rel) {
			if ($rel->quantity == "one" && $rel->relation == "parent") {
				$this->v .= "<tr><td>{$rel->variableName} Id:</td><td>"
					. "<input type=\"text\" name=\"{$rel->variableName}Id\"/></td></tr>\r\n";
			}
		}
		$this->v .= "<tr><td colspan=\"2\" style=\"text-align:right;\">"
				. " <input type=\"submit\" name=\"createObject\" value=\"Create Object\"/></td></tr>\r\n"
				. "</table>\r\n"
				. "</form>\r\n"
				. "</div>\r\n"
				. "<div id=\"xp_browser_existing\">\r\n"
				. "<h3>Existing {$this->object->type} Objects:</h3>\r\n"
				. "<table>\r\n"
				. "  <tr>\r\n"
				. "  <th>&nbsp;</th>\r\n<th>&nbsp;\r\n";
		foreach ($this->object->attributes as $attr){
			$this->v .= "<th>{$attr->name}</th>\r\n";
		}
		$this->v .= "  </tr>\r\n";
		$this->v .= "  <tr>\r\n";
		$this->v .= "    <td><a style=\"text-decoration:none;\" "
					 . "href=\"?id=[obj.objId;block=tr]\">"
					 . "<input type=\"button\" value=\"Edit\"/></a>\r\n"
					 . "     <td><form method=\"POST\">"
					 . "<input type=\"hidden\" name=\"id\" value=\"[obj.objId]\"/>"
					 . "<input type=\"submit\" name=\"deleteObject\" value=\"Delete\" "
					 . "onclick=\"return confirm('Really delete this object?');\"/>"
					 . "</form></td>";
		foreach ($this->object->attributes as $attr){
			$this->v .= "    <td style=\"text-align:center;\">[obj.{$attr->name}]</td>\r\n";
		}
		$this->v .= "  </tr>\r\n";
		$this->v .= "</table>\r\n"
					. "</div>\r\n</div>\r\n\r\n";
		
	}

	private function createEditView() {
		$this->e .= "<!-- TBS template for {$this->object->type} object editor -->\r\n"
				. "<div id=\"xpress_header\"></div>"
				. "<div id=\"xp_browser_wrapper\">"
				. "<h1 class=\"xp_browser_nav\"><a href=\"./\">Model Browser</a> :: "
				. "<a href=\"{$this->object->type}.php\">{$this->object->type}</a> :: "
				. "Object [var.attr.objId]</h1>\r\n"
				. "<div id=\"xp_browser_new\">\r\n"
				. "<h3>Edit {$this->object->type} Object:</h3>\r\n"
				. "<table>\r\n"
				. "  <tr><th style=\"text-align:left;background-color:#ddd;padding:3px;\">objId:</th><td style=\"background-color:#cde;padding:3px;\">[var.attr.objId]</td></tr>\r\n";
				foreach ($this->object->attributes as $attr) {
					if ($attr->name == "objId") { continue;}
					$this->e .= "<tr><th style=\"text-align:left;background-color:#ddd;padding:3px;\">{$attr->name}:</th><td style=\"background-color:#cde;padding:3px;\"><div id=\"[var.attr.objId]{$attr->name}\">[var.attr.{$attr->name};ifempty='click to edit']</div></td></tr>\r\n";
					
				}
		$this->e .= "</table>\r\n"
				. "</div>";
		
		$this->e .= "<div id=\"xp_browser_existing\">\r\n"
				. "<h3>Edit Relationships:</h3>\r\n";
		foreach ($this->object->relationships as $rel) {
			$this->e .= "<fieldset>\r\n"
				. "  <legend>{$rel->variableName}:</legend>\r\n"
				. "  <table>\r\n"
				. "    <tr><th>Object Type (ID)</th></tr>";
			if ($rel->quantity == "many") {
				$this->e .= "    <tr><td><a href=\"{$rel->objectType}.php?id=[o{$rel->variableName}.objId;block=tr]\">{$rel->objectType} ([o{$rel->variableName}.objId;])</a></td></tr>";
			} else {
				$this->e .= "    <tr><td><a href=\"{$rel->objectType}.php?id=[var.o{$rel->variableName}.objId]\">{$rel->objectType} ([var.o{$rel->variableName}.objId;])</a></td></tr>";
			}
			$this->e .= "  </table>\r\n"
				. "</fieldset>\r\n";
		}
		$this->e .= "</div>\r\n</div>\r\n\r\n";
		
		$this->e .= "<script type=\"text/javascript\">\r\n";
		foreach ($this->object->attributes as $attr) {
			if ($attr->name == "objId") {continue;}
			$this->e .= "new Ajax.InPlaceEditor('[var.o.objId]{$attr->name}','../../../xpress/js/AjaxHandler.php', { callback: function(form, value) { return 'action=update&objType={$this->object->type}&objID=[var.o.objId]&attr={$attr->name}&value='+encodeURIComponent(value);},cols:14,rows:1,highlightcolor:\"#cccccc\",highlightendcolor:\"#dddddd\"});\r\n";
		}
		$this->e .= "</script>\r\n";
	}
	private function createController() {
		$this->c .= "\t/* PHP Controller code for {$this->object->type} object browser */\r\n"
				. "\trequire_once('../../../xpress/app.php');\r\n"
				. "\r\n\r\n";
				
		$this->c .= "\tif (isset(\$_POST['createObject'])) {\r\n"
				. "\t\t\$o = {$this->object->type}Factory::create(";
		$ua = array();
		foreach ($this->object->uniqueAttrs as $u){
			$ua[] = "\$_POST['{$u->name}']";
		}
		foreach ($this->object->relationships as $r){
			if ($r->quantity == "one" && $r->relation == "parent"){
				$ua[] = "\$_POST['{$r->variableName}Id']";
			}
		}
		$this->c .= implode(",",$ua);
		$this->c .= ");\r\n";
		foreach ($this->object->attributes as $a) {
			if ($a->name == "objId") { continue;}
			$this->c .= "\t\t\$o->set{$a->name}(\$_POST['{$a->name}'],false);\r\n";
		}
		$this->c .= "\t\t\$o->save();\r\n"
				. "\t}\r\n\r\n";
		
		$this->c .= "\tif (isset(\$_POST['deleteObject'])) {\r\n"
				. "\t\tif (false !== (\$o = {$this->object->type}Factory::Retrieve(\$_POST['id']))) {\r\n"
				. "\t\t\t\$o->delete();\r\n"
				. "\t\t}\r\n"
				. "\t}\r\n\r\n";
				
		$this->c .= "\tif (isset(\$_GET['id'])) {\r\n"
				. "\t\t// Working with an individual object\r\n"
				. "\t\tif (!false == (\$o = {$this->object->type}Factory::retrieve(\$_GET['id']))) {\r\n";
		foreach ($this->object->attributes as $attr) {
			$this->c .= "\t\t\t\$attr['{$attr->name}'] = \$o->get{$attr->name}();\r\n";
		}
		foreach ($this->object->relationships as $rel) {
			$this->c .= "\t\t\t\$o{$rel->variableName} = \$o->get{$rel->variableName}();\r\n";
			
		}
		$this->c .= "\t\t\t\r\n"
				. "\t\t} else {\r\n"
				. "\t\t\tXPressPage::httpRedirect('../../../notfound.php');\r\n"
				. "\t\t}\r\n\r\n"
				. "\t\t\$p = new XPressPage(\"Model Browser: {$this->object->type} ({\$_GET['id']})\");\r\n"
				. "\t\t\$p->includeCSS(\"../../static/css/xpress.css\");\r\n"
				. "\t\t\$p->includeCSS(\"../../static/css/browser.css\");\r\n"
				. "\t\t\$p->includeJS(\"../../static/js/lib/scriptaculous-js-1.7.0/lib/prototype.js\");\r\n"
				. "\t\t\$p->includeJS(\"../../static/js/lib/scriptaculous-js-1.7.0/src/scriptaculous.js\");\r\n"
				. "\t\t\$p->open();\r\n"
				. "\t\t\$p->view()->LoadTemplate('view/Edit{$this->object->type}.html');\r\n";
		foreach ($this->object->relationships as $rel) {
			if ($rel->quantity == "many") {
				$this->c .= "\t\t\$p->view()->MergeBlock('o{$rel->variableName}',\$o{$rel->variableName});\r\n";
			}
		}
		$this->c .= "\t\t\$p->view()->Show();\r\n"
				. "\t\t\$p->close();\r\n"
				. "\t} else {\r\n";
				
		$this->c .= "\t\t// Displaying a list of all objects\r\n"
				. "\t\t\$data = \$xpress->db()->getAll(\r\n"
				. "\t\t\t\"SELECT objId FROM `{$this->object->type}` ORDER BY objId DESC\");\r\n"
				. "\t\t\$o = array();\r\n"
				. "\t\tforeach (\$data as \$d) {\r\n"
				. "\t\t\t\$a  = array();\r\n"
				. "\t\t\t\$oe = {$this->object->type}Factory::retrieve(\$d['objId']);\r\n";
		foreach ($this->object->attributes as $attr) {
			$this->c .= "\t\t\t\$a['{$attr->name}'] = \$oe->get{$attr->name}();\r\n";
		}
		$this->c .= "\t\t\t\$o[] = \$a;\r\n"
				. "\t\t}\r\n\r\n\r\n";
		$this->c .= "\t\t\$p = new XPressPage('Model Browser: {$this->object->type}');\r\n"
				. "\t\t\$p->includeCSS(\"../../static/css/xpress.css\");\r\n"
				. "\t\t\$p->includeCSS(\"../../static/css/browser.css\");\r\n"
				. "\t\t\$p->open();\r\n"
				. "\t\t\$p->view()->LoadTemplate('view/{$this->object->type}.html');\r\n"
				. "\t\t\$p->view()->MergeBlock('obj',\$o);\r\n"
				. "\t\t\$p->view()->Show();\r\n"
				. "\t\t\$p->close();\r\n"
				. "\t}\r\n\r\n";
	}
	
	public function writeAllToFile() {
		// Write the html view template
		$this->createView();
		$this->createEditView();
		$fw = new FileWriter("{$this->outputDir}/view/{$this->object->type}.html");
		$fw->write($this->v);
		$fw->close();
		$fw = new FileWriter("{$this->outputDir}/view/Edit{$this->object->type}.html");
		$fw->write($this->e);
		$fw->close();
		
		// Write the php controller file
		$this->createController();
		$fw = new PHPFileWriter("{$this->outputDir}/{$this->object->type}.php");
		$fw->write($this->c);
		$fw->close();
	}
}

?>