<?php
 ini_set("date.timezone", "Africa/Nairobi");
 require_once "config.php";
 require_once "functions.php";
 class receive{
    public function check($msgparts,$phone,$receiver,$message,$senderid,$shortcode){
      if($msgparts[0] == 'X'){ //cancelling a single subscription
        $this->cancel($msgparts,$phone,$shortcode,$senderid); 
           }
      elseif($msgparts[0] == 'C'){ //either class selection or subject selection
        
      }
      else {
        switch($msgparts){
         case 'LEARN':  //subscription
            $this->subscription($phone,$receiver,$message,$senderid,$shortcode); 
            break;  
         case 'ADD':  //add a subject
            $this->addsubscription($phone,$shortcode);  
            break;
         case 'CANCEL':  //add a subject
            $this->cancelsubscription($phone,$shortcode,$senderid);  
            break;
         case 'ALL':  //cancel all
            $this->cancel('ALL',$phone,$shortcode,$senderid);  
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
     }
 private function subscription($phone,$receiver,$message,$senderid,$shortcode){
    //first check if he is already signed up for a subject
    if(!number_exists($phone)){ //new subscription
    $now = date("Y-m-d H:i:s",time());
    $date = date("Y-m-d",time());
    $sub_id = random_string(9);
    $reply = "Welcome to M-Lesson! Select your child's class by replying C5, C6, C7, or C8. Classes 1-4 not yet available.\nC5: Class 5\nC6: Class 6\nC7: Class 7\nC8: Class 8\n";
    $qu = mysql_query("INSERT INTO inbox VALUES ('','$phone','$keyword','$receiver','$message','$now')") or die(mysql_error());
    $que = mysql_query("INSERT INTO subscribers (number,country,sub_id,date_,sub_level,status) VALUES ('$phone','Kenya','$sub_id','$now','1','2')") or die(mysql_error());
    $que = mysql_query("UPDATE totals SET revenue = revenue+6") or die(mysql_error());
    $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset) VALUES ('MT','$shortcode','$phone','$reply','2','mlesson','31','utf-8')");     
    }
    else { //they send LEARN a second or third time, already subscribed to a subject
    $details = my_subscriptions($phone);
       $content = "\n";
       foreach($details as $value){
        $content .= $value['class']." ".$value['subject']." ";
        }
    $reply = urlencode("You are currently subscribed to {$content}To add a subject, sms ADD to {$shortcode}");
    $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset) VALUES ('MT','$senderid','$phone','$reply','2','mlesson','31','utf-8')");      
    }
  }
 private function classSelection($msgparts,$phone,$shortcode){
    $reply = mysql_real_escape_string("Select your child's subject by replying with code (e.g.C5a).\n$msgparts".'a'.": Maths\n$msgparts".'b'.": English\n$msgparts".'c'.": Kiswahili\n$msgparts".'d'.": Science\n$msgparts".'e'.": Social Studies");
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
    $reply = "Thank you for subscribing to M-Lesson $class $su. You will receive your daily questions at 6pm beginning 16 May. Please ensure you have enough airtime at the time. Call 07XXXXXXXX for help.";    
    $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset) VALUES ('MT','$senderid','$phone','$reply','2','mlesson','31','utf-8')");   
    $que = mysql_query("UPDATE totals SET subscribers=subscribers+1") or die(mysql_error());
    }
    else {
      $reply = "You have already subscribed to $class $su. If you want to add a new subscription, please sms ADD to $shortcode. Call 07XXXXXXXX for help";
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
    $sub_id = random_string(9);
    $now = date("Y-m-d H:i:s",time());
    $reply = "Welcome to M-Lesson! Select your child's class by replying C5, C6, C7, or C8. Classes 1-4 not yet available.\nC5: Class 5\nC6: Class 6\nC7: Class 7\nC8: Class 8\n";
    $que = mysql_query("INSERT INTO subscribers (number,country,sub_id,date_,sub_level,status) VALUES ('$phone','Kenya','$sub_id','$now','$new_level','2')") or die(mysql_error());
    $que = mysql_query("UPDATE totals SET revenue = revenue+6") or die(mysql_error());
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
 private function cancelsubscription($phone,$shortcode,$senderid){
    $details = my_subscriptions($phone);
    $now = date("Y-m-d H:i:s",time());
    if(is_array($details)){
       $content = " ";
       foreach($details as $value){
        $subject = $value['subject'];
        $class = $value['class'];
        switch($subject){
            case 'ENGLISH':
             $code  = "X".$class."b";
             break;
            case 'KISWAHILI':
             $code  = "X".$class."c";
             break;
            case 'SCIENCE':
             $code  = "X".$class."d";
             break;
            case 'SOCIAL STUDIES':
             $code  = "X".$class."e";
             break;
            case 'MATHS':
             $code  = "X".$class."a";
             break;
        }
        $content .= $class." ".$subject." (".$code."), ";
      }
       $content = substr($content, 0, -2); //remove trailing * from entire string
      $reply = "Are you sure? You are subscribed to:{$content}. To cancel reply with subject code (e.g. $code) To cancel all sms ALL.";
    }
    else $reply = "Your are not registered on M-Lesson. Please sms LEARN to {$shortcode} to register";
    $que = mysql_query("UPDATE totals SET revenue = revenue+6") or die(mysql_error());
    $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset) VALUES ('MT','$shortcode','$phone','$reply','2','mlesson','31','utf-8')");     
  } 
  private function cancel($msgparts,$phone,$shortcode,$senderid){
    $now = date("Y-m-d H:i:s",time());
    if($msgparts == 'ALL'){ //cancelling ALL
        if(number_exists($phone)){ //if he is subscribed to a subject
           $que = mysql_query("UPDATE subscribers SET status = '0' WHERE number = '$phone'") or die(mysql_error()); 
           $reply = "We are sorry to see you go! You have cancelled all subscriptions. To find out how your child is learning sms LEARN to $shortcode. Call 07XXXXXXXX for help."; 
        }
        else{ ///no active subscriptions
           $reply = "You do not have any active subscriptions";             
        }        
        $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset) VALUES ('MT','$shortcode','$phone','$reply','2','mlesson','31','utf-8')");   
        $que = mysql_query("UPDATE totals SET revenue=revenue+6") or die(mysql_error());
    }
    else{
    $class = $msgparts[2];
    $sub = strtoupper(substr($msgparts, -1));
    $details = set_things($sub);
    $subject = $details[0];
    $su = $details[1];
    $subject_id = 'C'.$class.$subject."1";
    if(is_registered($phone,$subject_id)){  //if pupil was registered to the subject
    $que = mysql_query("UPDATE subscribers SET status = '0' WHERE number = '$phone' AND code = '$subject_id'") or die(mysql_error());
    $reply = "We are sorry to see you go! You have cancelled C$class {$su}. To find out how your child is learning sms LEARN to $shortcode. Call 07XXXXXXXX for help.";    
    $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset) VALUES ('MT','$shortcode','$phone','$reply','2','mlesson','31','utf-8')");   
    $que = mysql_query("UPDATE totals SET subscribers=subscribers-1, revenue=revenue+6") or die(mysql_error());
    }
    else {
      $reply = "Sorry..You have havent subscribed to that subject"; 
      $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset) VALUES ('MT','$senderid','$phone','$reply','2','mlesson','31','utf-8')");    
     }        
    }
  }
 }    
    $phone = $_REQUEST['sender'];
    $receiver = $_REQUEST['receiver'];
    $messages = trim(urldecode($_REQUEST['text']));
    $message = mysql_real_escape_string($messages);
    $keyword = $_REQUEST['keyword'];
    $msgparts = trim(strtoupper($keyword));
    $shortcode = "21070";
    $senderid = "MLESSON";
    $charge = "6";
 $receive = new receive();
 $receive->check($msgparts,$phone,$receiver,$message,$senderid,$shortcode);
?>
