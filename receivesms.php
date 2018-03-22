<?php
 ini_set("date.timezone", "Africa/Nairobi");
 require_once "config.php";
 require_once "functions.php";
 class receive{
    public function check($msgparts,$phone,$receiver,$message,$senderid,$shortcode,$charge){
      if($msgparts[0] == 'X'){ //cancelling a single subscription
        $this->cancel($msgparts,$phone,$shortcode,$senderid); 
           }
      elseif(is_numeric($msgparts) && strlen($msgparts) == 1){ //this must be a class selection
         $this->classSelection($msgparts,$phone,$shortcode,$senderid);
         }
      elseif(is_numeric($msgparts) && strlen($msgparts) == 2){ //subject selection      
            $this->subject($msgparts,$phone,$shortcode,$senderid);
             }          
      else {
        switch($msgparts){
         case 'LEARN':  //subscription
         case 'LAN': 
         case 'LEAR': 
         case 'LARN': 
         case 'SUBSCRIBE': 
            $this->subscription($phone,$receiver,$message); 
            break;  
         case 'ADD':  //add a subject
            $this->addsubscription($phone,$shortcode);  
            break;
         case 'CANCEL': 
         case 'STOP':  //remove all subscriptions
         case 'UNSUBSCRIBE':
            $this->cancelsubscription($phone,$shortcode,$senderid);  
            break;
         case 'A':
         case 'B':
         case 'C':
         case 'D':  //answer response
            $this->answer($msgparts,$phone,$shortcode,$senderid);
            break;
         case 'ALL':
            //$this->cancel('ALL',$phone,$message); 
            break;
         default: //treat it as a subscription process
            $this->subscription($phone,$receiver,$message,$senderid,$shortcode);    
           }
         }
     }
 private function subscription($phone,$receiver,$message){
    //first check if he is already signed up for a subject
    global $msgparts;
    global $shortcode;
    global $senderid;
    if(!number_exists($phone)){ //new subscription
    $now = date("Y-m-d H:i:s",time());
    $date = date("Y-m-d",time());
    $sub_id = random_string(9);
    $msgid = random_string(15);
    $reply = mysql_real_escape_string(urlencode("Welcome to M-Lesson! Please reply with EITHER 5, 6, 7, or 8.\n5: Class 5\n6: Class 6\n7: Class 7\n8: Class 8\n"));
    $qu = mysql_query("INSERT INTO inbox VALUES ('','$phone','$msgparts','$receiver','$message','$now')") or die(mysql_error());
     $que = mysql_query("INSERT INTO subscribers (number,country,sub_id,date_,code,sub_level,status) VALUES ('$phone','Kenya','$sub_id','$now','PROGRESS','1','2')") or die(mysql_error());
     if(!is_customer($phone)){
     $quer = mysql_query("INSERT INTO customers (number) VALUES ('$phone')") or die(mysql_error());
     }
    $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,dlr_url,charset) VALUES ('MT','$shortcode','$phone','$reply','2','mlesson','31','$msgid','utf-8')");     
    }
    else { //they send LEARN a second or third time, already subscribed to a subject
    $details = my_subscriptions($phone);
       $content = "";
       foreach($details as $value){
        $content .= 'Class '.$value['class']." ".$value['subject']." ";
        }
    $reply = mysql_real_escape_string(urlencode("You are currently subscribed to {$content}. If you want to add a new subscription, please send ADD to 21475. Call 0709748682 for help"));
    $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset,coding) VALUES ('MT','$shortcode','$phone','$reply','2','mlesson','31','utf-8',0)");     
    }
  }
 private function classSelection($msgparts,$phone,$shortcode,$senderid){
    if(in_array($msgparts,array('5','6','7','8'))){ //classes being offered currently
       $msgid = random_string(15);
       $reply = mysql_real_escape_string(urlencode("Thank you for selecting Class $msgparts. Please REPLY with either: {$msgparts}1, {$msgparts}2, {$msgparts}3, {$msgparts}4, {$msgparts}5\n$msgparts".'1'.": Maths\n$msgparts".'2'.": English\n$msgparts".'3'.": Kiswahili\n$msgparts".'4'.": Science\n$msgparts".'5'.": Social Studies")); 
       $quer = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,dlr_url,charset) VALUES ('MT','$shortcode','$phone','$reply','2','mlesson','31','$msgid','utf-8')"); 
      
    }
    else{
         $reply = urlencode("Thank you for contacting M-Lesson. Our system did not understand your request. One of our represenatives will contact you shortly. Thank you.\n");   
         $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset,coding) VALUES ('MT','$senderid','$phone','$reply','2','mlesson','31','utf-8',0)"); 
     }
 }
 private function subject($msgparts,$phone,$shortcode,$senderid){
    $now = date("Y-m-d H:i:s",time());
    $class = $msgparts[0];
    $sub = $msgparts[1];
    $details = set_things($sub);
    $subject = $details[0];
    $su = $details[1];
    $subject_id = 'C'.$class.$subject."1";
    if(service_exists($subject_id)){ //we have the subject id
    if(!is_registered($phone,$subject_id)){  //if pupil not already registered for the subject
    $details = get_previous_level($phone);
    $level = $details[0];
    $new_level = $level+1;
    $status = $details[1];
    //check if it is a new subscription
    if($status == '2'){   //continuation of previous process
    $query = mysql_query("UPDATE subscribers SET code='$subject_id',subject='$su',class='$class',status='1' WHERE (number = '$phone' AND sub_level = '$level') AND status='2'") or die(mysql_error());  
    }
    else{  //subscription short cut,status = 1
      $sub_id = random_string(9);
      $msgid = random_string(9);
      $que = mysql_query("INSERT INTO subscribers (number,country,sub_id,date_,code,subject,class,sub_level,status) VALUES ('$phone','Kenya','$sub_id','$now','$subject_id','$su','$class','$new_level','1')") or die(mysql_error());   
    }
    $reply = "Thank you for joining M-Lesson Class $class $su. To receive daily revision questions at 6pm, please deposit 72 shillings to Till Number 847347. Call 0709748682.";    
    $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset,coding) VALUES ('MT','$shortcode','$phone','$reply','2','mlesson','31','utf-8',0)");    
    $quer = mysql_query("INSERT INTO billing (number,subject,date_,start,next_billing,status) VALUES ('$phone','$subject_id','$now','yes','$now','begin')") or die(mysql_error()); 
    $reply2 = "In order to start receiving M-Lesson revision questions, deposit 72 shillings to your account using MPESA. \n1: Go to your MPESA menu\n2: Select Lipa na M-PESA\n3: Select Buy Goods and Services\n4: Enter Till No. 847347\n5: Enter 72\n6: Enter your MPESA Pin\n7: Select OK.";    
    $query2 = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset,coding,deferred) VALUES ('MT','$shortcode','$phone','$reply2','2','mlesson','31','utf-8',0,1)"); 
    }
    else {
      $reply = "You have already subscribed to Class $class $su.  If you want to add a new subscription, please send ADD to 21475. Call 0709748682 for help\n";
      $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset,coding) VALUES ('MT','$shortcode','$phone','$reply','2','mlesson','31','utf-8',0)");     
     }     
    }
    else{
      $reply = "Sorry we did not understand your message. Please sms the correct subject code to $shortcode. Call 0709748682 for help."; 
      $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset,coding) VALUES ('MT','$shortcode','$phone','$reply','2','mlesson','31','utf-8',0)");  
    }         
  }
  private function addsubscription($phone,$shortcode){
    $details = get_previous_level($phone);
    $level = $details[0];
    $new_level = $level+1;
    $sub_id = random_string(9);
    $msgid = random_string(15);
    $now = date("Y-m-d H:i:s",time());
    $reply = mysql_real_escape_string(urlencode("Welcome to M-Lesson! Please reply with EITHER 5, 6, 7, or 8.\n5: Class 5\n6: Class 6\n7: Class 7\n8: Class 8\n"));
    $que = mysql_query("INSERT INTO subscribers (number,country,sub_id,date_,sub_level,status) VALUES ('$phone','Kenya','$sub_id','$now','$new_level','2')") or die(mysql_error());
    $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,dlr_url,charset) VALUES ('MT','$shortcode','$phone','$reply','2','mlesson','31','$msgid','utf-8')");     
  } 
 private function answer($msgparts,$phone,$shortcode,$senderid){
    $date = date("Y-m-d",time());
    $now = date("Y-m-d H:i:s",time());
    //first check from subscribers which subject this is 
    $subjectid = find_subject_id($phone);    
    $responses = list_current($subjectid);//only if the answer is on schedule
    if(is_array($responses)){
    $next = date("Y-m-d H:i:s",time()+86400); //add one day for next billing
    $glob_question_id = $responses[0];
    $glob_subject = $responses[1];
    $glob_correct_answer = $responses[2];
    $glob_correct_text = $responses[3];
    $glob_incorrect_text = $responses[4];
    $subid = find_sub_id($phone,$subjectid);
     if($msgparts == strtoupper($glob_correct_answer)){ //correct nswer
       $reply = mysql_real_escape_string($glob_correct_text);
      }
    else $reply = mysql_real_escape_string($glob_incorrect_text); 
    $quer = mysql_query("INSERT INTO responses (number,sub_id,subject,question_id,response,correct_response,time_) VALUES ('$phone','$subid','$glob_subject','$glob_question_id','$msgparts','$glob_correct_answer','$now')") or die(mysql_error()); 
    $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset,coding) VALUES ('MT','$shortcode','$phone','$reply','2','mlesson','31','utf-8',0)") or die(mysql_error()); 
    $que = mysql_query("UPDATE billing SET next_billing = '$next',status = 'begin',sending_order = sending_order+1 WHERE number = '$phone' AND subject = '$subjectid'") or die(mysql_error());
    if(has_funds($phone)){
      $this->send_more_mt($phone,$shortcode);
    }
   }
  }
  private function send_more_mt($phone,$shortcode){
  $details = list_mtbilling($phone); 
   if(is_array($details)){  
    $date = date("Y-m-d",time());
    $next = date("Y-m-d H:i:s",time()+86400); //add one day for next billing
    $time = date("Y-m-d H:i:s",time());
   foreach($details as $detail){
    $subjectid = $detail['subject'];
    $id = $detail['id'];
    $order = $detail['sending_order'];
    $text = get_content($subjectid,$date);
    $msgid = random_string(15);
    $text = mysql_real_escape_string(urlencode($text));
    $qu_ = mysql_query("INSERT INTO sent_totals (msgid,number,subject,date_) VALUES ('$msgid','$phone','$subjectid','$time')");
    $q = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,dlr_url,charset,coding,deferred) VALUES ('MT','$shortcode','$phone','$text','2','mlesson','31','$msgid','utf-8',0,5)");
    $qu = mysql_query("UPDATE billing SET status = 'current' WHERE number = '$phone' AND subject = '$subjectid'") or die(mysql_error());
    $que = mysql_query("UPDATE customers SET balance = balance-3 WHERE number = '$phone'") or die(mysql_error());
    }   
   }
  }
 private function cancelsubscription($phone,$shortcode,$senderid){
    $now = date("Y-m-d H:i:s",time());
     global $msgparts;
     global $message;
    $qu = mysql_query("INSERT INTO inbox VALUES ('','$phone','$msgparts','$shortcode','$message','$now')") or die(mysql_error());
    $details = my_subscriptions($phone);    
    if(is_array($details)){
       $content = " ";
       foreach($details as $value){
        $subject = $value['subject'];
        $class = $value['class'];
        $content .= $class." ".$subject.",";
      }
        $content = substr($content, 0, -1); //remove trailing , from entire string
      $reply = mysql_real_escape_string(urlencode("You are currently subscribed to {$content}. To unsubscribe  Call 0709748682"));
    }
    else $reply = "Your are not registered on M-Lesson. Please sms LEARN to {$shortcode} to register";
    $msgid = random_string(15);
    $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,dlr_url,charset) VALUES ('MT','$shortcode','$phone','$reply','2','mlesson','31','$msgid','utf-8')");     
  } 
  private function cancel($msgparts,$phone,$message){
    global $senderid;
    global $shortcode;
    $now = date("Y-m-d H:i:s",time());
    $msgid = random_string(15);
    $qu = mysql_query("INSERT INTO inbox VALUES ('','$phone','$msgparts','$shortcode','$message','$now')") or die(mysql_error());
    if($msgparts == 'ALL'){ //cancelling ALL
        if(number_exists($phone)){ //if he is subscribed to a subject
           $qu = mysql_query("UPDATE subscribers SET status = '3' WHERE number = '$phone'") or die(mysql_error()); 
           $que = @mysql_query("DELETE FROM billing WHERE number = '$phone'") or die(mysql_error()); 
           $reply = mysql_real_escape_string(urlencode("We are sorry to see you go! You have cancelled all subscriptions. To find out how your child is learning sms LEARN to $shortcode. Call 0709748682 for help.")); 
        }
        else{ ///no active subscriptions
           $reply = "You do not have any active subscriptions";             
        }        
        $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,dlr_url,charset) VALUES ('MT','$shortcode','$phone','$reply','2','mlesson','31','$msgid','utf-8')");     
    }
    else{
    $class = $msgparts[1];
    $sub = strtoupper(substr($msgparts, -1));
    $details = set_things($sub);
    $subject = $details[0];
    $su = $details[1];
    $subject_id = 'C'.$class.$subject."1";
    if(is_registered($phone,$subject_id)){  //if pupil was registered for the subject
    $que = mysql_query("UPDATE subscribers SET status = '3' WHERE number = '$phone' AND code = '$subject_id'") or die(mysql_error());
    $reply = mysql_real_escape_string(urlencode("We are sorry to see you go! You have cancelled C$class {$su}. To resubscribe sms ADD to $shortcode. Call 0709748682 for help."));    
    $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset,coding) VALUES ('MT','$shortcode','$phone','$reply','2','mlesson','31','utf-8',0)");   
    $que = @mysql_query("DELETE FROM billing WHERE number = '$phone' AND subject = '$subject_id'") or die(mysql_error());  
    }
    else {
      $reply = "We did not understand your message. Please resend the correct subject code you wish to cancel."; 
      $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset,coding) VALUES ('MT','$shortcode','$phone','$reply','2','mlesson','31','utf-8',0)");    
       }        
     }
   }
  }    
    $phone = trim(preg_replace("/[^0-9]/","",$_REQUEST['sender']));
    $receiver = $_REQUEST['receiver'];
    $messages = trim(urldecode($_REQUEST['text']));
    $themessages = explode(" ",$messages);
    $message = mysql_real_escape_string($messages);
    $msgparts = trim(strtoupper($themessages[0]));
    $shortcode = "21475";
    $senderid = "MLesson";
    $charge = "3";
 $receive = new receive();
 $receive->check($msgparts,$phone,$receiver,$message,$senderid,$shortcode,$charge);
?>
