<?php

//======================================================================
// Bind to an LDAP server using an unsecure connection
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


echo '<h2>LDAP Bind (Unsecure)</h2>';


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
// global variables
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

$server  = 'ldap.jpl.nasa.gov';
$userdn  = "uid=ahart,ou=personnel,dc=dir,dc=jpl,dc=nasa,dc=gov";
$userpw  = 'xxxxxx';

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
// display runtime environment
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

echo '<table border="1" cellspacing="0" cellpadding="8">';
echo '<tr><td>Server</td><td>'.$server.'</td></tr>';
echo '<tr><td>User DN</td><td>'.$userdn.'</td></tr>';
echo '<tr><td>User PW</td><td>'.$userpw.'</td></tr>';
echo '</table>';
echo '<p />';

echo 'Creating server connection...<p />';

$ds=ldap_connect($server);

if ($ds)
{
   echo 'Binding to server...<p />';

   $r = ldap_bind($ds,$userdn,$userpw);

    $e = ldap_errno($ds);

   if ($e == 0x31)
   {
      echo 'Bind Failed: invalid credentials';
   }
   elseif ($e)
   {
      echo 'Bind Failed: ',ldap_error($ds),'<p />';
   }
   else
   {
      echo 'Bind Successful...<p />';

      echo 'Unbinding from server...<p />';

      ldap_unbind($ds);
   }
}
else
{
   echo '<b>Unable to create LDAP server connection ('.$server.')</b>';
}

?>
