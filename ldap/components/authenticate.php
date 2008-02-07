<?php
//==================================================================
// LDAP  Authentication PHP Code
//
// if the session is not authenticated...
//   1. get a uername and password from the user
//   2. locate one (and only one entry) in the directory that
//      matches the username
//   3. login using the entry's DN and the password
//   4. if the login is successful, create a session for the user
//      if the login was not successful start over a step 1.
// if the session is authenticated...
//   1. check the session expiration date
//      reset the session expiration date/time if the inactivity
//      grace period has not been exceeded and ask the user
//      to re-authenticate if it has
//
// Notes:
//
//   1. The search filter is (uid=<username>)
//   
//==================================================================
//
// !! NOTE: this file should only be included from 
// ldap_protect.inc.php because it references global variables
// defined there
//
session_start();

//------------------------------------------------------------------
// is the session's AUTHENTICATION flag set?
// NO,  ask for a username and password and try to authenticate
//      the user.  If the user is authenticated, create a session
//      containing the session information.
// YES, check session exporation date
//      is the inactivity grace period exceeded
//      NO,  reset the session exportation date/time
//           (start a new grace period)
//      YES, ask the user to re-authenticate
//------------------------------------------------------------------

if (! isset($_SESSION['auth']))
{
   //---------------------------------------------------------------
   // was a username and password posted ?
   //---------------------------------------------------------------

   if ($_POST)
   {
      if (isset($_POST['username']) && isset($_POST['password']))
      {
        $username = $_POST['username'];
        $password = $_POST['password'];
      }
      else
      {
         session_destroy();
         login_form('',$SCRIPT_NAME);
      }
   }
   else
   {
      session_destroy();
      login_form('',$SCRIPT_NAME);
   }


   //---------------------------------------------------------------
   // create server connection
   //---------------------------------------------------------------

   $ds = ldap_connect(MY_LDAP_SERVER);

   if (! $ds)
   {
      echo "<p />Unable to create LDAP server connection (".MY_LDAP_SERVER.")<p />";
      exit;
   }

   //---------------------------------------------------------------
   // set the protocol to version 3
   // (required for SSL or TLS)
   //---------------------------------------------------------------

   if (!ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3))
   {
      echo '<p />',"\n";
      echo 'Error: ',ldap_error($ds);
      echo '<p />',"\n";
      echo 'Failed to set LDAP Protocol version to 3, TLS not supported.';
      echo '<p />',"\n";
      exit;
   }


   //---------------------------------------------------------------
   // bind to server - anonymous
   //---------------------------------------------------------------

   $r = ldap_bind($ds);

   if (! $r)
   {
      ldap_close($ds);
      echo "<p />Unable to anonymous bind to LDAP server (".MY_LDAP_SERVER.")<p />";
      exit;
   }


   //---------------------------------------------------------------
   // search for an entry matching the username
   // (create the search filter and then search)
   //---------------------------------------------------------------

   $filter = '(uid=' . $username . ')';

   $sr = ldap_search($ds,MY_LDAP_SEARCHBASE,$filter,array('uid'));
 
   if (! $sr)
   {
      ldap_close($ds);
      echo "<p />Unable to locate user ($username)<p />\n";
      exit;
   }

   //---------------------------------------------------------------
   // was one entry returned ?
   //---------------------------------------------------------------

   $n = ldap_count_entries($ds,$sr);

   if ($DEBUG)
   {
      echo "<p />$n entries returned from search</p />\n";
   }

   if ($n < 1)
   {
      ldap_close($ds);
      login_form("Unable to locate user ($username)",$SCRIPT_NAME);
   }

   if ($n > 1)
   {
      ldap_close($ds);
      login_form("Too many entries match user name ($username)",
               $SCRIPT_NAME);
   }


   //---------------------------------------------------------------
   // one entry returned, get its DN
   //---------------------------------------------------------------

   $info = ldap_get_entries($ds,$sr);

   $userdn = $info[0]['dn'];

   if ($DEBUG)
   {
      echo '<p />User DN: ' . $userdn . '</p />' . "\n";
   }


   //---------------------------------------------------------------
   // connect to the server using TLS
   //---------------------------------------------------------------

   //   if (!ldap_start_tls($ds))
   //   {
   //      echo "<p />\n";
   //      echo 'Error: ',ldap_error($ds);
   //      echo "<p />\n";
   //      echo "Start_TLS failed";
   //      echo "<p />\n";
   //      exit;
   //   }


   //---------------------------------------------------------------
   // bind to server - using DN and password
   //---------------------------------------------------------------

   $r = @ldap_bind($ds,$userdn,$password);

   if (! $r)
   {
      ldap_close($ds);
      login_form("Unable to bind to LDAP server (".MY_LDAP_SERVER.") as" . 
               "<br />$userdn",$SCRIPT_NAME);
   }

   $_SESSION['username']  = $username;
   $_SESSION['userdn']    = $userdn;
   $_SESSION['usergroup'] = '';
   $_SESSION['time']      = time();
   $_SESSION['auth']      = 1;
}
else
{
   // check session exporation date/time

   if (($_SESSION['time']+ MY_LDAP_GRACE) < time())
   {
      session_destroy();
      login_form('Session Inactivity Grace Period Expired',$SCRIPT_NAME);
   }

   // set the session's grace period exporation date/time

   $_SESSION['time'] = time();
}


//==================================================================
// ask for a username and password and return back to this
// script (page)
//==================================================================

function login_form($msg,$script)
{
   require_once("lmgr_begin.php");
   echo '<center>';
   echo '<p />';
   echo '<form method="post" action="' . $script . '">' . "\n";
   echo '<table class="login" ' .
        'align="center">' . "\n";
   echo '<tr><td class="loginMessage" colspan="2" align="center">' . "\n";
   if (strlen($msg) > 0)
   {
      echo '<b>' . $msg . '</b><p />' . "\n";
   }
   echo '<b>You are not authenticated. <br/>Please login in.</b>' .
        '</td></tr>' . "\n";
   echo '<tr><td class="loginUsername">Username:</td><td><input type="text" ' .
        'name="username" size="40"></td></tr>' . "\n";
   echo '<tr><td class="loginPassword">Password:</td><td><input type="password" ' .
        'name="password" size="40"></td></tr>' . "\n";
   echo '<tr><td>&nbsp;</td><td align="left"> ' .
        '<input type="submit" value="Login"></td></tr>' . "\n";
   echo '</table>' . "\n";
   echo '</form>';
   echo '</center>';
   require_once("lmgr_end.php");
   exit();
}


?>