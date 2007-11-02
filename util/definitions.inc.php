<?php
/**
 * Global Definitions and Environment Setup
 * ----------------------------------------
 */

// Where is the bmdb located on the server?
define("BMDB_ROOT","/Applications/MAMP/htdocs/edrn_bmdb3");

// What is the DSN string to use to connect to the database?
define("BMDB_DSN","mysql://bmdb:canc3r@localhost/bmdb3");

// What prefix should be used namespacing session variables
define("BMDB_SESSION_PREFIX","_bmdb");

// What debug state should be used (true/false)
// This determines the level of detail of error messages shown
define("DEBUGGING",false);


define("CWSP_PATH","/Applications/MAMP/htdocs/cwsp");
define("PEAR_PATH","/Applications/MAMP/htdocs/PEAR/pear/php");
// Set the location of the CWSP Library classes
set_include_path(CWSP_PATH . PATH_SEPARATOR . get_include_path());
// Set the location of the PEAR Library packages
set_include_path(PEAR_PATH . PATH_SEPARATOR . get_include_path());


?>