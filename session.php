<?php
ob_start();
session_start();
	function logged_in() {
		if(isset($_SESSION['uid'])){
		  return true;
		}
	}
    function confirm_logged_in(){
		if (!logged_in()){
			header("Location: home");
		}
	}
    function isAdmin(){
		if($_SESSION['user_level'] == '1'){
			return true;
		}
	}
    function canView() {
      if(logged_in()){
		if($_SESSION['user_level'] == '1' || $_SESSION['user_level'] == '2'){
            return true;
        }
        else{
          header("Location: restricted");  
         }
      }
	}
    function isSubscriberManager() {
      if(logged_in()){
		if($_SESSION['user_level'] == '4' || $_SESSION['user_level'] == '1' || $_SESSION['user_level'] == '2'){
            return true;
        }
        else{
          header("Location: restricted");  
         }
      }
	}	
?>
