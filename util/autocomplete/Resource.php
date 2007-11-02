<?php
require_once("../definitions.inc.php");
require_once("../../classes/Bmdb.class.php");

	$needle = isset($_POST['autocomplete_parameter']) ? $_POST['autocomplete_parameter'] : '';
	
	if ($needle == ''){echo "<ul></ul>";exit();}

	$db = Bmdb::getDatabaseObject();
	$r = $db->safeQuery("SELECT * FROM resource WHERE Name LIKE \"$needle%\" ");
	echo "<ul>";
	while ($res = $r->fetchRow(DB_FETCHMODE_ASSOC)){
		echo "<li id=\"$res[ID]\">$res[Name]</li>";
	}
	echo "</ul>";
?>
