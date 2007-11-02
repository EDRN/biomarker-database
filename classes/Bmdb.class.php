<?php
require_once("cwsp.inc.php");
require_once(BMDB_ROOT."/plone/plone_header.inc.php");
require_once(BMDB_ROOT."/classes/model/User.php");

class Bmdb {
	
	public static function getAuthObject(){
		return new cwsp_auth(BMDB_DSN,array("dbFields" => array("ID"),"unColumn" =>"Email"));		
	}
	
	public static function getDatabaseObject(){
		try {
			return new cwsp_db(BMDB_DSN);
		} catch (cwsp_ConnectionException $ce){
			cwsp_messages::fatal($ce->__toString());
		}
	}
	
	public static function doTopStatusBar($bLoggedIn,$prefix = ''){
		echo '<div id="topStatus">';
		echo '<div id="breadcrumbs">';
		echo '  <!-- breadcrumbs -->';
		echo '</div>';
		echo '<div id="loginStatus">';
		if ($bLoggedIn){
			$u = cwsp_session::get('User',BMDB_SESSION_PREFIX);
			echo 'Logged in as: '.$u['Email'].' (Role: '.$u['Role'].')&nbsp;';
			echo '[<a href="#">Edit Profile</a>]&nbsp;[<a href="'.$prefix.'logout.php">Log Out</a>]&nbsp;';		
		} else {
			echo 'Not Logged In. <a href="'.$prefix.'login.php">Log In</a>&nbsp;';			
		}
		echo '</div>';
		echo '<div style="clear:both;"></div>';
		echo '</div>';
		echo '<div id="titleImage">';
		echo '<img src="'.$prefix.'assets/images/bmdb.png" style="margin-left:5px;margin-top:0px;">';
		echo '</div>';
	}
	
	public static function logIn($email,&$conn,&$auth){
		if ($auth->checkLoginStatus() ){
			try {
				$userdao = new dao_user($conn);
				$user = $userdao->getBy("Email",$email);
				cwsp_session::set("User",$user->toAssocArray(),BMDB_SESSION_PREFIX);
				return(true);
			} catch (NoSuchUserException $nsue){
				// ignore (just return false since login has failed)
				return false;
			}
		}
		return(false);
	}
	
	public static function logOut(&$auth,&$conn = null){
		$auth->endSession();
		cwsp_session::clear("User",BMDB_SESSION_PREFIX);
	}
	
	
}

?>