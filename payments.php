<?php
ob_start();
 require_once "config.php";
 require_once "functions.php";
//$str = "msisdn=491626839553&transactionid=505478&service=<apikey>&clientid=AB123DNB&country=de&status=success&event_type=one_off&enduserprice=10.00"; 
    $phone = $_REQUEST['msisdn'];
    $trx = $_REQUEST['transactionid'];
    $service = $_REQUEST['service'];
    $clientid = $_REQUEST['clientid'];
    $country = $_REQUEST['country'];
    $status = $_REQUEST['status'];
    $event_type = $_REQUEST['event_type'];
    $enduserprice = $_REQUEST['enduserprice'];
if(!empty($service)){                             
   $now = date("Y-m-d H:i:s",time());
   $query = mysql_query("INSERT INTO payments VALUES ('','$phone','$trx','$service','$clientid','$country','$status','$event_type','$enduserprice','$now')");                                  
 } 
 http_response_code(200);
?>