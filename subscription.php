<?php
require_once "config.php";
require_once "functions.php";
$now = date("Y-m-d H:i:s",time());
$phone = $_REQUEST['msisdn'];
$id = $_REQUEST['clientref'];
$synref = $_REQUEST['synref'];
$action = $_REQUEST['subsaction'];
$fb = $_REQUEST['fb']; //subsfb for subscription
$subsresult = $_REQUEST['subsresult'];
if($fb == 'subsfb'){ //subscription
    $status = '1';
}
else $status = '2';//unsubscription
$query = mysql_query("UPDATE subscribers SET action = '$subsresult',status='$status' WHERE id = '$id'");
if(strtolower($subsresult) == 'subscribed'){ //after succssful subscription, update billing in order to begin receiving daily mt
  $query = mysql_query("UPDATE billing SET start = 'yes',next_billing = '$now' WHERE number = '$phone'");
}
if(strtolower($subsresult) == 'unsubscribed'){ //remove from database
   $query = @mysql_query("DELETE FROM billing WHERE number = '$phone'");
    }
?>
