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
    $text_ = get_content($subjectid,$date); //make sure there is content first
    if(!empty($text_)){
    $msgid = random_string(15);
    $text = mysql_real_escape_string(urlencode($text_));
    $qu_ = mysql_query("INSERT INTO sent_totals (msgid,number,subject,text,date_) VALUES ('$msgid','$phone','$subjectid','$text_','$time')");
    $quer = mysql_query("UPDATE revenue SET messages_ = messages_+1, total_ = total_+3 WHERE DATE(day_) = '$date'") or die(mysql_error());
    $q = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,dlr_url,charset,coding) VALUES ('MT','$shortcode','$phone','$text','2','mlesson','31','$msgid','utf-8',0)");
    $que = mysql_query("UPDATE billing SET status = 'current' WHERE number = '$phone' AND subject = '$subjectid'") or die(mysql_error());
    $quer = mysql_query("UPDATE customers SET balance = balance-3 WHERE number = '$phone'") or die(mysql_error());
       } 
      }
    }  
    else{ //no airtime
    $text = mysql_real_escape_string("Your M-Lesson account balance is low. Please Lipa na MPESA to Till Number 847347 to continue receiving daily questions from M-Lesson!"); 
    //$q = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset,coding) VALUES ('MT','$shortcode','$phone','$text','2','mlesson','31','utf-8',0)") or die(mysql_error());
    }
  }
}
?>