<?php
/*
 * Copyright 2007 Crawwler Software Development
 * http://www.crawwler.com
 * 
 * 
 * Provides formatted messaging / dialog functionality
 * 
 */

class cwsp_messages {
  function info($msg) {
    echo '<div style="border:solid 1px green;background-color:lightgreen;padding:5px;">';
    echo "<strong>Information: </strong> - <br/>$msg";
    echo '</div>';
  }

  function warn($msg) {
    echo '<div style="border:solid 1px gold;background-color:lightyellow;padding:5px;">';
    echo "<strong>Warning!</strong> - <br/>$msg";
    echo '</div>';
  }

  function err($msg) {
    echo '<div style="border:solid 1px red;background-color:pink;padding:5px;margin:5px;">';
    echo "<strong>Error!</strong> - <br/>$msg";
    echo '</div>';
  }

  function fatal($msg) {
    echo '<div style="border:solid 1px red;background-color:pink;padding:5px;">';
    echo "<strong>Error!</strong> - <br/>$msg";
    echo "<p>Unfortunately, this error is non-recoverable. This page will not continue to load.</p>";
    echo '</div>';
    die();
  }

  function displayMessage($tag) {
    switch ($tag) {
      case 'changedPassword' :
        $msg = "Your password was successfully changed " .
        "and you have been signed out. <br/> Please sign in again " .
        "using your new password.";
        cwsp_messages :: info($msg);
        break;
      case 'badAnswer' :
        $msg = "You did not provide the correct answer to your security " .
        "question. <br/>Please try again " .
        "or contact an administrator. ";
        cwsp_messages :: err($msg);
      case 'noMatchingEmail' :
        $msg = 'No matching email address was found. <br/>Please try again ' .
        'or contact an administrator. ';
        cwsp_messages :: err($msg);
      case 'noSecurityQuestion' :
        $msg = "It appears that you have not specified a security question " .
        "and answer in your user profile. As a result, your password can not be recovered / changed automatically. 
      				 Please contact a System Administrator. ";
        cwsp_messages :: err($msg);
        break;
      case 'signinRequired' :
        $msg = "You must be signed in to continue";
        cwsp_messages :: info($msg);
        break;
      case 'missingRequiredFields' :
        $msg = "Please provide data for all required fields! <br/>Required fields are marked with an asterisk (*). ";
        cwsp_messages :: err($msg);
        break;
      case 'passwordMismatch' :
        $msg = "Your passwords did not match! <br/>Please try again and make sure you type your password correctly in each box. ";
        cwsp_messages :: err($msg);
        break;
      default :
        break;
    }
  }

  function writeMessenger($messageLabel) {
    if ($messageLabel == '') {
      return;
    }

    echo '<!-- Messenger --> ';
    echo '<div id="messenger">';
    cwsp_messages :: displayMessage($messageLabel);
    echo '</div>';
    echo '<!-- End Messenger -->';
  }
}
?>