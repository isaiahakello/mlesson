<?php
require_once "config.php";
require_once "functions.php";
$now = date("Y-m-d H:i:s",time());
$phone = $_REQUEST['sender'];
$receiver = $_REQUEST['receiver'];
$messages = trim(urldecode($_REQUEST['text']));
$message = mysql_real_escape_string($messages);
$keyword = $_REQUEST['keyword'];
$msgexploded = explode(' ',$messages);
$msgparts = trim(strtoupper($keyword));
$query = mysql_query("INSERT INTO inbox VALUES ('','$phone','$keyword','$receiver','$message','$now')") or die(mysql_error());
if($msgparts == 'LEARN'){  //subscription MO
    $query = mysql_query("INSERT INTO subscribers (number,country,date_) VALUES ('$phone','Kenya','$now')") or die(mysql_error());
    $query = mysql_query("INSERT INTO billing (number,service,date_,sending_order) VALUES ('$phone','subject','$now','1')") or die(mysql_error());  
    }
else{ //msgpart is STOP ,unsubscribe based on base keyword or unsubscribe from all services 
 if(!empty($keyword)){ //if subscribing from a particular service
  switch($keyword){
    case 'PARTNER':
    case 'KEEP':
    case 'FIND':
     $service = "t10e569m";
     break;
    case 'COOK':
    case 'COOKING':
    case 'KITCHEN':
     $service = "COOKING TIPS";
     break;
    case 'FLIRT':
    case 'FLIRTING':
     $service = "HOW TO DEVELOP THE RIGHT FLIRTING";
     break;
    case 'MAN':
    case 'PICK':
     $service = "HOW TO PICK UP A MAN";
     break;
    case 'DAY':
    case 'TODAY':
     $service = "WHAT HAPPENED ON THIS DAY";
     break;
    case 'WORLD':
    case 'RECORD':
    case 'RECORDS':
     $service = "bvnj58t4";
     break;
     }
    $query = mysql_query("UPDATE subscribers SET action='unsubscribe request',status = '3' WHERE service = '$service' AND number = '$phone'") or die(mysql_error());  
 }
 //unsubscribe from all services
else  $query = mysql_query("UPDATE subscribers SET action='unsubscribe request',status = '3' WHERE number = '$phone'") or die(mysql_error());  
}
?>
