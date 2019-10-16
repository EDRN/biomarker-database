<?php
//==================================================================
// LDAP Authorization PHP Code
//
// if PAGEGROUP does not exist...
//    return "access denied" error message
//    exit
// else if the group stored in the session does not equal PAGEGROUP...
//    if the user's DN stored in the session is not a
//    member of the group PAGEGROUP
//       return "access denied" error message
//       exit
//    store the group PAGEGROUP in the session 
// allow access...
//
// Notes:
//
//   1. PAGEGROUP is the LDAP group used to control access to
//      web content.  This demo code allow different web pages
//      to be "protected" by different LDAP groups.  PAGEGROUP
//      is defined in a seperate file for each page.   
//   
//==================================================================

//------------------------------------------------------------------
// is PAGEGROUP defined ?
//------------------------------------------------------------------

if (! defined('PAGEGROUP'))
{
   message('No Group Defined to Control Access to ' .
           'The Page<br />Access Denied');
}

if (! isset($_SESSION['userdn']))
{
   message('No User DN Defined In the Session' .
           '<br />Access Denied');
}

//------------------------------------------------------------------
// is PAGEGROUP equal to the user's group stored in the session ?
// YES, allow access
// NO,  Check for group membership
//      YES, replace the session group with PAGEGROUP and
//           allow access
//      NO,  deny access
//------------------------------------------------------------------

if (strcmp($_SESSION['userdn'],PAGEGROUP) != 0)
{
   //---------------------------------------------------------------
   // create server connection
   //---------------------------------------------------------------

   $ds = ldap_connect(MY_LDAP_SERVER);
   ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);


   if (! $ds)
   {
      echo "<p />Unable to create LDAP server connection ($SERVER)<p />";
      exit;
   }


   //---------------------------------------------------------------
   // bind to server - anonymous
   //---------------------------------------------------------------

   $r = ldap_bind($ds);

   if (! $r)
   {
      ldap_close($ds);
      echo "<p />Unable to anonymous bind to LDAP server ($SERVER)<p />";
      exit;
   }

    
   //---------------------------------------------------------------
   // check for group membership
   //---------------------------------------------------------------

   $r = ldap_compare($ds,PAGEGROUP,'uniqueMember',$_SESSION['userdn']);

   if ($r === -1)
   {
      $err = ldap_error($ds);
      $eno = ldap_errno($ds);
      ldap_close($ds);

      if ($eno === 32)
      {
         message("LDAP Access Control Group Does Not Exist" .
                 "<br />Access Denied");
      }
      elseif ($eno === 16)
      {
         message("Membership Atribute Does Not Exist " .
                 "In The LDAP Access Control Group" .
                 "<br />Access Denied");
      }
      else
      {
         message("LDAP Error: $err<br />Access Denied");
      }
   }
   elseif ($r === false)
   {
      ldap_close($ds);
      message('Access Denied');
   }    

   $_SESSION['usergroup'] = PAGEGROUP;

   ldap_close($ds);
   
   // At this point, redirect the user to another page, if specified
   if (isset($_POST['redirect']) && !empty($_POST['redirect'])){
   	 header("Location: {$_POST['redirect']}");
   }
}


//==================================================================
// display a message block (page)
//==================================================================

function message($msg)
{
   echo '<center>';
   echo '<p />';
   echo '<table border="1" cellpadding="12" cellspacing="0">';
   echo '<tr><td>' . "\n";
   echo '<p /><center><b>' . $msg . '</center></b><p />' . "\n";
   echo '</td></tr></table>' . "\n";
   echo '</center>';
  exit;
}

?>
