<?php
 ini_set("date.timezone", "Africa/Nairobi");
 require_once "config.php";
 require_once "functions.php";
 class receive{
    public function check($msgparts,$phone,$receiver,$message,$senderid,$shortcode){     
        switch($msgparts){
         case 'LEARN':  //subscription
            $this->subscription($phone,$receiver,$message,$senderid,$shortcode); 
            break;  
         case 'ADD':  //add a subject
            $this->addsubscription($phone,$shortcode);  
            break;
         case 'C5':
         case 'C6':
         case 'C7':
         case 'C8':  //class selection
            $this->classSelection($msgparts,$phone,$shortcode);
            break; 
         case 'A':
         case 'B':
         case 'C':
         case 'D':  //answer response
            $this->answer($msgparts,$phone,$shortcode);
            break;
         default: //subject selection, first check if they already have subscribed to this subject
            $this->subject($msgparts,$phone,$shortcode,$senderid);    
           }
     }
 private function subscription($phone,$receiver,$message,$senderid,$shortcode){
    //first check if he is already signed up for a subject
    if(!number_exists($phone)){
    $now = date("Y-m-d H:i:s",time());
    $date = date("Y-m-d",time());
    $sub_id = random_string(9);
    $reply = "Reply with \nC5: Class 5\nC6: Class 6\nC7: Class 7\nC8: Class 8\n";
    $qu = mysql_query("INSERT INTO inbox VALUES ('','$phone','$keyword','$receiver','$message','$now')") or die(mysql_error());
    $que = mysql_query("INSERT INTO subscribers (number,country,sub_id,date_,sub_level,status) VALUES ('$phone','Kenya','$sub_id','$now','1','2')") or die(mysql_error());
    $que = mysql_query("UPDATE totals SET revenue = revenue+6") or die(mysql_error());
    $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset) VALUES ('MT','$shortcode','$phone','$reply','2','mlesson','31','utf-8')");     
    }
    else {
    $subjects = get_subscriptions($phone);
    $reply = urlencode("You are currently subscribed to $subjects subjects \nTo add a subject, sms ADD to 21070");
    $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset) VALUES ('MT','$senderid','$phone','$reply','2','mlesson','31','utf-8')");      
    }
  }
  private function classSelection($msgparts,$phone,$shortcode){
    $reply = "Reply with\n$msgparts".'a'.": Maths\n$msgparts".'b'.": English\n$msgparts".'c'.": Kiswahili\n$msgparts".'d'.": Science\n$msgparts".'e'.": Social Studies";
    $que = mysql_query("UPDATE totals SET revenue = revenue+6") or die(mysql_error());
    $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset) VALUES ('MT','$shortcode','$phone','$reply','2','mlesson','31','utf-8')"); 
 }
 private function subject($msgparts,$phone,$shortcode,$senderid){
    $now = date("Y-m-d H:i:s",time());
    $class = trim(substr($msgparts, 0, -1));
    $sub = strtoupper(substr($msgparts, -1));
    $details = set_things($sub);
    $subject = $details[0];
    $su = $details[1];
    $subject_id = $class.$subject."1";
    if(service_exists($subject_id)){ //we have the subject id
    if(!is_registered($phone,$subject_id)){  //if pupil not already registered
    $details = get_previous_level($phone);
    print_r($details);
    $level = $details[0];
    $new_level = $level+1;
    $status = $details[1];
    //check if it is a new subscription
    if($status == '2'){   //continuation of previous process
    $query = mysql_query("UPDATE subscribers SET code='$subject_id',subject='$su',class='$class',status='1' WHERE (number = '$phone' AND sub_level = '$level') AND status='2'") or die(mysql_error());  
    }
    else{  //subscription short cut,status = 1
      $sub_id = random_string(9);
      $que = mysql_query("INSERT INTO subscribers (number,country,sub_id,date_,code,subject,class,sub_level,status) VALUES ('$phone','Kenya','$sub_id','$now','$subject_id','$su','$class','$new_level','1')") or die(mysql_error());   
      $quer = mysql_query("INSERT INTO billing (number,subject,date_,sending_order,start,next_billing) VALUES ('$phone','$subject_id','$now','1','yes','$now')") or die(mysql_error()); 
    }
    $reply = "Thank you for subscribing to M-Lesson $class $su. You will receive your daily questions at 6pm. Please ensure you have enough airtime at the time";    
    $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset) VALUES ('MT','$shortcode','$phone','$reply','2','mlesson','31','utf-8')");   
    $que = mysql_query("UPDATE totals SET subscribers=subscribers+1,revenue = revenue+6") or die(mysql_error());
    }
    else {
      $reply = "Sorry..You have already subscribed to $class $su"; 
      $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset) VALUES ('MT','$senderid','$phone','$reply','2','mlesson','31','utf-8')");    
     }
    }
    else{
      $reply = "Sorry..We do not understand your response..Please try again"; 
      $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset) VALUES ('MT','$senderid','$phone','$reply','2','mlesson','31','utf-8')");   
    }         
  }
  private function addsubscription($phone,$shortcode){
    $details = get_previous_level($phone);
    $level = $details[0];
    $new_level = $level+1;
    //$status = $details[1];
    $sub_id = random_string(9);
    $now = date("Y-m-d H:i:s",time());
    $reply = "Reply with \nC5: Class 5\nC6: Class 6\nC7: Class 7\nC8: Class 8\n";
    $que = mysql_query("INSERT INTO subscribers (number,country,sub_id,date_,sub_level,status) VALUES ('$phone','Kenya','$sub_id','$now','$new_level','2')") or die(mysql_error());
    $que = mysql_query("UPDATE totals SET subscribers = subscribers+1,revenue = revenue+6") or die(mysql_error());
    $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset) VALUES ('MT','$shortcode','$phone','$reply','2','mlesson','31','utf-8')");     
  } 
 private function answer($msgparts,$phone,$shortcode){
    $date = date("Y-m-d",time());
    $now = date("Y-m-d H:i:s",time());
    $responses = list_current($date);
    $glob_question_id = $responses[0];
    $glob_subject = $responses[1];
    $glob_correct_answer = $responses[2];
    $glob_correct_text = $responses[3];
    $glob_incorrect_text = $responses[4];
     if($msgparts == strtoupper($glob_correct_answer)){ //correct nswer
       $reply = $glob_correct_text;
      }
    else $reply = $glob_incorrect_text; 
    $que = mysql_query("UPDATE totals SET revenue = revenue+6") or die(mysql_error());
    $quer = mysql_query("INSERT INTO responses (number,subject,question_id,response,correct_response,time_) VALUES ('$phone','$glob_subject','$glob_question_id','$msgparts','$glob_correct_answer','$now')") or die(mysql_error()); 
    $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset) VALUES ('MT','$shortcode','$phone','$reply','2','mlesson','31','utf-8')"); 
  }
 }
    
    $phone = $_REQUEST['sender'];
    $receiver = $_REQUEST['receiver'];
    $messages = trim(urldecode($_REQUEST['text']));
    $message = mysql_real_escape_string($messages);
    $keyword = $_REQUEST['keyword'];
    $msgparts = trim(strtoupper($keyword));
    $shortcode = "21070";
    $senderid = "UWAZII";
 $receive = new receive();
 $receive->check($msgparts,$phone,$receiver,$message,$senderid,$shortcode);
?>
