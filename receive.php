<?php
require_once "config.php";
require_once "functions.php";
$now = date("Y-m-d H:i:s",time());
$phone = $_REQUEST['sender'];
$receiver = $_REQUEST['receiver'];
$message = trim(urldecode($_REQUEST['text']));
$message1 = mysql_real_escape_string($message);
$keyword = strtoupper($_REQUEST['keyword']);
$query = mysql_query("INSERT INTO inbox VALUES ('','$phone','$keyword','$receiver','$message1','$now')") or die(mysql_error());
//subscription MO
if($keyword != 'STOP'){
switch($keyword){
    case 'PARTNER':
    case 'KEEP':
    case 'FIND':
     $service = "FIND AND KEEPING A PARTNER";
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
    case 'GUINESS':
     $service = "WORLD RECORDS";
     break;
     }
    $query = mysql_query("INSERT INTO subscribers (number,aggregator,country,service,date_) VALUES ('$phone','Mira','Ghana','$service','$now')") or die(mysql_error());
    }
else{ //keyword is STOP ,unsubscribe based on base keyword or unsubscribe from all services
 $msgparts = explode(' ',$message);
 $keyword = strtoupper($msgparts[1]);
  switch($keyword){
    case 'PARTNER':
    case 'KEEP':
    case 'FIND':
     $service = "FIND AND KEEPING A PARTNER";
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
    case 'GUINESS':
     $service = "WORLD RECORDS";
     break;
     }
    $query = mysql_query("UPDATE subscribers SET action='unsubscribe request',status = '3' WHERE service = '$service' AND number = '$phone'") or die(mysql_error());  
}
?>
