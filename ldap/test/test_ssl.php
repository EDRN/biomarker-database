<?php

//======================================================================
// Secure LDAP bind (SSL connection)
//======================================================================
//
// Change Log:
//   22 May 06  T L Wolfe
//      - Original code completed
//
//======================================================================
// Copyright (C) 2006, California Institute of Technology, JPL.
//                     U.S. Government Sponsorship acknowledged.
//======================================================================


echo '<h2>LDAP Bind (SSL)</h2>';


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
// global variables
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

$server  = "ldaps://ldap.jpl.nasa.gov";


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
// display runtime environment
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

echo '<table border="1" cellspacing="0" cellpadding="8">';
echo '<tr><td>Server</td><td>'.$server.'</td></tr>';
echo '</table>';
echo '<p />';


echo 'Connecting to server...<p />';

$ds = ldap_connect($server);
ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);


if ($ds)
{
   echo 'Setting protocol to V3...<p />';

   if (! ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3))
   {
      echo 'Set Option Error: ',ldap_error($ds),'<p />';
      exit;
   }

   echo 'Binding to server using SSL...<p />';

   $r = ldap_bind($ds);

   $e = ldap_errno($ds);

   if ($e)
   {
      echo 'Bind Error: ',ldap_error($ds),'<p />';
   }
   else
   {
      echo 'Unbind from server...<p />';

      ldap_unbind($ds);
   }
}
else
{
   echo '<b>Unable to create LDAP server connection ('.$server.')</b>';
}

?>
