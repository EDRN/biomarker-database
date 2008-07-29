<?php
/**
 * Global Definitions and Environment Setup
 * ----------------------------------------
 */

// Where is the bmdb located on the server?
define("BMDB_ROOT","/path/to/project");

// What is the DSN string to use to connect to the database?
define("BMDB_DSN","mysql://user:pass@server/dbname");

// What prefix should be used namespacing session variables
define("BMDB_SESSION_PREFIX","_bmdb");

// What debug state should be used (true/false)
// This determines the level of detail of error messages shown
define("DEBUGGING",true);


define("CWSP_PATH","/path/to/cwsp/library");
define("PEAR_PATH","/path/to/pear/library");
// Set the location of the CWSP Library classes
set_include_path(CWSP_PATH . PATH_SEPARATOR . get_include_path());
// Set the location of the PEAR Library packages
set_include_path(PEAR_PATH . PATH_SEPARATOR . get_include_path());


?>