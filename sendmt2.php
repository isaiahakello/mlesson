<?php
set_time_limit(0);
ini_set("date.timezone", "Africa/Nairobi");
ini_set("memory_limit","2G");
require_once "config.php";
require_once "functions.php";
$date = date("Y-m-d",time());
$next = date("Y-m-d H:i:s",time()+86400); //add one day for next billing
$shortcode = "21475";
$senderid = "MLesson";
$time = date("Y-m-d H:i:s",time());
$numbers = list_mtbilling_numbers($date); //make unique
if(is_array($numbers)){
 foreach($numbers as $phone){
   if(has_funds($phone)){ //does he have money
   $details = list_mtbilling($phone); 
   foreach($details as $detail){
    $subjectid = $detail['subject'];
    $id = $detail['id'];
    $order = $detail['sending_order'];
    $text = get_content($subjectid,$date); //make sure there is content first
    if(!empty($text)){
    $msgid = random_string(15);
    $text = mysql_real_escape_string(urlencode($text));
    $qu = mysql_query("INSERT INTO outbox (msgid,number,subject_id,time_) VALUES ('$msgid','$phone','$subjectid','$time')");
    $q = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,dlr_url,charset,coding) VALUES ('MT','$shortcode','$phone','$text','2','mlesson','31','$msgid','utf-8',0)");
    $que = mysql_query("UPDATE billing SET status = 'current' WHERE number = '$phone' AND subject = '$subjectid'") or die(mysql_error());
    $quer = mysql_query("UPDATE customers SET balance = balance-3 WHERE number = '$phone'") or die(mysql_error());
       } 
      }
    }  
  }
}
?>