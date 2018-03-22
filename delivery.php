<?php
require_once "config.php";
require_once "functions.php";
$now = date("Y-m-d H:i:s",time()); 
$next = date("Y-m-d H:i:s",time()+86400); //add one day for next billing
$phone = $_REQUEST['msisdn'];
$id = $_REQUEST['REF'];
$synref = $_REQUEST['SYNREF'];
$status = $_REQUEST['status'];
$service = get_service($id);
$reason = 'Delivery Feedback';
$que = mysql_query("UPDATE billing SET synref = '$synref',status = '$status',reason='$reason' WHERE id = '$id'");

?>
