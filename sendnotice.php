<?php
set_time_limit(0);
ini_set("date.timezone", "Africa/Nairobi");
ini_set("memory_limit","2G");
require_once "config.php";
require_once "functions.php";
$date = date("Y-m-d",time());
$now = date("Y-m-d H:i:s",time());
$senderid = "MLesson";
$numbers = list_failed_messages($date);
if(is_array($numbers)){
foreach($numbers as $detail){
    $subjectid = $detail['subject_id'];
    $id = $detail['id'];
    $phone = $detail['number'];
    $text = mysql_real_escape_string("Do you want to learn how your child is performing today? Kindly top up your airtime so you can receive the daily questions from M-Lesson!"); 
    $q = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset,coding) VALUES ('MT','$senderid','$phone','$text','2','mlesson','31','utf-8',0)") or die(mysql_error()); 
    $quer = mysql_query("INSERT INTO billing2 (number,subject,date_,start,next_billing,status) VALUES ('$phone','$subjectid','$now','yes','$now','begin')") or die(mysql_error()); 
  }
}
?>