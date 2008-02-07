<?php

//======================================================================
// test for membership in a LDAP group
//======================================================================
//
// DN (Distinguished name) is a unique LDAP data type.  Because of
// this, the following DNs are the same:
//
//     "uid=Joe User, ou=personnel, dc=dir, dc=jpl, dc=gov"
//     "uid=Joe User  , ou=personnel  , dc=dir  , dc=jpl  , dc=gov"
//     "uid = Joe User, ou= personnel, dc=dir, dc = jpl ,dc =gov"
//
// Because of the above, comparing DNs using "string matching" can
// be tricky.  However, there is  an easy, efficient, and simple
// way to compare DNs.   The basic LDAP protocol has an "server side"
// compare function that understands the DN data type and will find
// all of the the above examples equal.
//
// The LDAP protocol "compare" function tests to see if an attribute
// in a specific LDAP entry (DN) contains a specified value.  The
// attribute can be multi-valued and a match is found if one of the
// values matches the test value.
//
//======================================================================
//
// Change Log:
//   27 Apr 06  T L Wolfe
//      - Original code completed
//
//======================================================================
// Copyright (C) 2006, California Institute of Technology, JPL.
//                     U.S. Government Sponsorship acknowledged.
//======================================================================


echo '<h2>LDAP Compare (Test For Group Membership)</h2>';


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
// global variables
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

$attr    = "uniqueMember"; 
$groupdn = "cn=oodt,ou=personnel,dc=dir,dc=jpl,dc=nasa,dc=gov";
$server  = "ldap.jpl.nasa.gov";
$userdn  = "uid=ahart,ou=personnel,dc=dir,dc=jpl,dc=nasa,dc=gov";



// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
// display runtime environment
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

echo '<table border="1" cellspacing="0" cellpadding="8">';
echo '<tr><td>Server</td><td>'.$server.'</td></tr>';
echo '<tr><td>Group DN</td><td>'.$groupdn.'</td></tr>';
echo '<tr><td>User DN</td><td>'.$userdn.'</td></tr>';
echo '<tr><td>Attribute</td><td>'.$attr.'</td></tr>';
echo '</table>';
echo '<p />';


echo 'Connecting to server...<p />';

$ds=ldap_connect($server);

if ($ds)
{
   echo 'Binding to server...<p />';

   if(ldap_bind($ds))
   {
      echo 'Testing group membership...<p />';

      $r = ldap_compare($ds, $groupdn, $attr, $userdn);

      if ($r == TRUE)
      {
         echo "<b>Is a group member</b><p />";
      }
      elseif ($r == FALSE)
      {
         echo "<b>Not a group member</b><p />";
      }
      else
      {
         echo '<b>Compare Error: ' . ldap_error($ds) . '</b><p />';
      }
   }
   else
   {
      echo 'Bind Error: ',ldap_error($ds),'<p />';
   }          

   echo 'Unbinding for server...<p />';

   ldap_unbind($ds);
}
else
{
   echo '<b>Unable to create server connection (' . $server. ')</b>';
}

?>