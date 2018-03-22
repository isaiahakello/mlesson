<?php
function list_phones($code){
	global $connect;
	$que = mysql_query("SELECT number AS number FROM subscribers WHERE code = '$code' ORDER by id ASC") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs['number'];
		}
		return $nei;
	}
}
function send_message($phones,$message){
	global $connect;
    $time = time();
    $message2 = urlencode($message); 
    foreach($phones as $phone){
    $msgid = random_string(15);
    $phone = trim($phone);//remove white space before the phone number  
    $que = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,time,dlr_mask,dlr_url,charset,coding) VALUES ('MT','MLesson','$phone','$message2','2','mlesson','$time','27','$msgid','utf-8',0)");   
    }
}
function schedule_message($schedule,$phones,$message){
    global $connect;
    $time = time();  
    $deferred = (strtotime($schedule)-time())/60;
    $message2 = urlencode($message);
    foreach($phones as $phone){
    $msgid = random_string(15);
    $phone = trim($phone);//remove white space before the phone number
    $que = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,time,dlr_mask,dlr_url,charset,deferred,coding) VALUES ('MT','MLesson','$phone','$message2','2','mlesson','$time','27','$msgid','utf-8','$deferred',coding)");
      }
}
function list_subscribers(){
	global $connect;
	$que = mysql_query("SELECT * FROM subscribers WHERE status = '1' GROUP BY code") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}
function select_subscribers(){
	global $connect;
	$que = mysql_query("SELECT * FROM subscribers ORDER BY id ASC") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}
function list_subscriber($id){
	global $connect;
	$que = mysql_query("SELECT * FROM subscribers WHERE sub_id = '$id'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}
function check_status($msgid){
	global $connect;
	$que = mysql_query("SELECT dlr_mask AS status FROM sent_sms WHERE dlr_url = '$msgid'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['status'];
		}
		return $nei;
	}
}
function list_unsubscribers(){
	global $connect;
	$que = mysql_query("SELECT * FROM subscribers WHERE status = '3' ORDER by id DESC") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}
function get_content($subject,$date){
	global $connect;
	$que = mysql_query("SELECT question_text AS content,answer_a AS a,answer_b AS b,answer_c AS c,answer_d AS d FROM uploadcontent WHERE DATE(question_date) = '$date' AND subject = '$subject'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return "";
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['content']."\n"."A:". $rs['a']."\n"."B:". $rs['b']."\n"."C:". $rs['c']."\n"."D:". $rs['d'];
		}
		return $nei;
	}
}
function list_mtbilling($phone){
	global $connect;
    $date = date("Y-m-d",time());
	$que = mysql_query("SELECT * FROM billing WHERE DATE(next_billing) = '$date' AND (status = 'begin' AND number = '$phone') ORDER by id ASC LIMIT 1") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}
function list_mtbilling2($phone){
	global $connect;
    $date = date("Y-m-d",time());
	$que = mysql_query("SELECT * FROM billing2 WHERE DATE(next_billing) = '$date' AND (status = 'begin' AND number = '$phone') ORDER by id ASC LIMIT 1") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}
function list_mtbilling_numbers($date){
	global $connect;
	$que = mysql_query("SELECT * FROM billing WHERE DATE(next_billing) = '$date' AND status = 'begin' GROUP BY number ORDER by id ASC") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs['number'];
		}
		return $nei;
	}
}
function list_mtbilling2_numbers($date){
	global $connect;
	$que = mysql_query("SELECT * FROM billing2 WHERE DATE(next_billing) = '$date' AND status = 'begin' GROUP BY number ORDER by id ASC") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs['number'];
		}
		return $nei;
	}
}
function list_failed_messages($date){
	global $connect;
	$que = mysql_query("SELECT * FROM outbox WHERE status = '2' AND DATE(time_) = '$date' ORDER by id ASC") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}
function find_subject_id($phone){
	global $connect;
    $date = date("Y-m-d",time());
	$que = mysql_query("SELECT subject AS sub FROM billing WHERE DATE(next_billing) = '$date' AND (number = '$phone' AND status = 'current') ORDER by id DESC") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['sub'];
		}
		return $nei;
	}
}
function find_sub_id($phone,$code){
	global $connect;
	$que = mysql_query("SELECT sub_id AS sub FROM subscribers WHERE number = '$phone' AND code = '$code'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return 'none';
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['sub'];
		}
		return $nei;
	}
}
function list_current($subjectid){
	global $connect;
    $date = date("Y-m-d",time());
	$que = mysql_query("SELECT * FROM schedule WHERE DATE(date_) = '$date' AND subject = '$subjectid' ORDER by id ASC") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = array($rs['question_id'],$rs['subject'],$rs['answer'],$rs['correct_text'],$rs['incorrect_text']);
		}
		return $nei;
	}
}
function list_mtcontent(){
	global $connect;
    $date = date("Y-m-d",time());
	$que = mysql_query("SELECT * FROM uploadcontent WHERE DATE(question_date) = '$date' ORDER by id ASC") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}
function get_question($qid){
	global $connect;
	$que = mysql_query("SELECT question_text AS qu FROM uploadcontent WHERE question = '$qid'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return 'question not found..';
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['qu'];
		}
		return $nei;
	}
}
function list_files($id){
	global $connect;
	$que = mysql_query("SELECT * FROM uploadcontent WHERE id = '$id'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}
function list_subjects(){
	global $connect;
	$que = mysql_query("SELECT * FROM subjects ORDER by id ASC") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}
function get_service($id){
	global $connect;
	$que = mysql_query("SELECT service AS service FROM billing WHERE id = '$id'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['service'];
		}
		return $nei;
	}
}
function list_totals(){
	global $connect;
	$que = mysql_query("SELECT * FROM totals") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}
function change_page($url,$page){
    $page = "&page=".$page;
	$new_url = str_replace(strrchr($url,"&"),$page,$url);
    return $new_url;    
}

function service_exists($service){
	global $connect;
	$que = mysql_query("SELECT * FROM subjects WHERE subject_id = '$service'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		return true;
	}
}
function get_subject_details($code){
	global $connect;
	$que = mysql_query("SELECT * FROM subjects WHERE subject_id = '$code'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}
function get_subscriptions($number){
	global $connect;
	$que = mysql_query("SELECT COUNT(*) AS count FROM subscribers WHERE number = '$number' AND status ='1'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['count'];
		}
		return $nei;
	}
}
function my_subscriptions($number){
	global $connect;
	$que = mysql_query("SELECT * FROM subscribers WHERE number = '$number' AND status = '1'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}
function get_previous_level($number){
	global $connect;
	$que = mysql_query("SELECT sub_level AS level,status AS status FROM subscribers WHERE number = '$number' AND status !='3' ORDER BY id DESC LIMIT 1") or die(mysql_error());
		while($rs = mysql_fetch_assoc($que)){
			$nei = array($rs['level'],$rs['status']);
		}
		return $nei;
}
function set_things($sub){
    switch($sub){
        case '1':
          $subject = 'MAT';
          $su = 'MATHS';
         break;
        case '2':
          $subject = 'ENG';
          $su = 'ENGLISH';
         break;
        case '3':
         $subject = 'KIS';
         $su = 'KISWAHILI';
         break;
        case '4':
         $subject = 'SCI';
         $su = 'SCIENCE';
         break;
        case '5':
         $subject = 'SOC';
         $su = 'SOCIAL STUDIES';
         break;        
    } 
    return array($subject,$su);   
}
function number_exists($number){
	global $connect;
	$que = mysql_query("SELECT * FROM subscribers WHERE number = '$number' AND status ='1'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		return true;
	}
}
function has_funds($number){
	global $connect;
	$que = mysql_query("SELECT * FROM customers WHERE number = '$number' AND balance > '3'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		return true;
	}
}
function is_registered($phone,$code){
	global $connect;
	$que = mysql_query("SELECT * FROM subscribers WHERE (number='$phone' AND code='$code') AND status = '1'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		return true;
	}
}
function is_customer($phone){
	global $connect;
	$que = mysql_query("SELECT * FROM customers WHERE number='$phone'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		return true;
	}
}
function is_schedule($phone,$code){
	global $connect;
	$que = mysql_query("SELECT * FROM billing WHERE number='$phone' AND subject='$code'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		return true;
	}
}
function random_string($length){
    $key = '';
    $keys = array_merge(range(0, 9), range('A','Z'));
    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }
    return $key;
}
function list_user($id){
	global $connect;
	$que = mysql_query("SELECT * FROM users WHERE id = '$id'",$connect) or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}
function list_users($username){
	global $connect;
	$que = mysql_query("SELECT * FROM users WHERE username != '$username' ORDER by id ASC",$connect) or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}

function show_user($id,$pass){
	global $connect;
	$que = mysql_query("SELECT * FROM users WHERE username= '$id' AND password='$pass' ORDER by id",$connect) or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}
function protect($val){
    $val = trim($val);
    $val = mysql_real_escape_string($val);
    $val = strip_tags($val);
    $val = htmlspecialchars($val);
	return $val;
}

function check_db_email($email){
	global $connect;
	$que = mysql_query("SELECT email FROM users where email='$email'",$connect) or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}else{
		return true;
	}
}
function list_active($code){
	global $connect;
	$que = mysql_query("SELECT COUNT(*) AS active FROM subscribers WHERE status = 1 AND code = '$code'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return '0';
	}else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['active'];
		}
		return $nei;
	}
}
function list_tnew($code,$date,$date2){
	global $connect;
    switch($date){
      case 'today':
      $today = date("Y-m-d",time());
      $que = mysql_query("SELECT COUNT(*) AS active FROM subscribers WHERE (status = 1 AND code = '$code') AND DATE(date_) = '$today'") or die(mysql_error());
      break; 
      case 'week': 
      $que = mysql_query("SELECT COUNT(*) AS active FROM subscribers WHERE (status = 1 AND code = '$code') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW()") or die(mysql_error());
      break;
      case 'month': 
      $que = mysql_query("SELECT COUNT(*) AS active FROM subscribers WHERE (status = 1 AND code = '$code') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 MONTH) AND NOW()") or die(mysql_error());
      break;
      case 'term': 
      $que = mysql_query("SELECT COUNT(*) AS active FROM subscribers WHERE (status = 1 AND code = '$code') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 3 MONTH) AND NOW()") or die(mysql_error());
      break;
    }
	if(mysql_num_rows($que) == 0){
		return '0';
	}else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['active'];
		}
		return $nei;
	}
}
function list_cancelled($code){
	global $connect;
	$que = mysql_query("SELECT COUNT(*) AS inactive FROM subscribers WHERE status = 3 AND code = '$code'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return '0';
	}else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['inactive'];
		}
		return $nei;
	}
}
function list_tcancel($code,$date,$date2){
	global $connect;
    switch($date){
      case 'today':
      $today = date("Y-m-d",time());
      $que = mysql_query("SELECT COUNT(*) AS active FROM subscribers WHERE (status = 3 AND code = '$code') AND DATE(date_) = '$today'") or die(mysql_error());
      break; 
      case 'week': 
      $que = mysql_query("SELECT COUNT(*) AS active FROM subscribers WHERE (status = 3 AND code = '$code') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW()") or die(mysql_error());
      break; 
      case 'month': 
      $que = mysql_query("SELECT COUNT(*) AS active FROM subscribers WHERE (status = 3 AND code = '$code') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 MONTH) AND NOW()") or die(mysql_error());
      break;
      case 'term': 
      $que = mysql_query("SELECT COUNT(*) AS active FROM subscribers WHERE (status = 3 AND code = '$code') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 3 MONTH) AND NOW()") or die(mysql_error());
      break;
    }
	if(mysql_num_rows($que) == 0){
		return '0';
	}else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['active'];
		}
		return $nei;
	}
}
function list_questions(){
	global $connect;
	$que = mysql_query("SELECT COALESCE(SUM(msgid), 0) AS sum FROM sent_totals") or die(mysql_error());
	while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['sum'];
		}
		return $nei;
}
function list_nquestions($number){
	global $connect;
	$que = mysql_query("SELECT COALESCE(SUM(msgid), 0) AS sum FROM sent_totals WHERE number LIKE '%$number%'") or die(mysql_error());
	while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['sum'];
		}
		return $nei;
}
function list_cquestions($class){
	global $connect;
	$que = mysql_query("SELECT COALESCE(SUM(status), 0) AS sum FROM sent_totals WHERE subject LIKE '$class%'") or die(mysql_error());
	while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['sum'];
		}
		return $nei;
}
function list_tquestion($code){
	global $connect;
	$que = mysql_query("SELECT COALESCE(SUM(status), 0) AS sum FROM sent_totals WHERE subject = '$code'") or die(mysql_error());
	while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['sum'];
		}
		return $nei;
}
function list_ntquestion($number,$code){
	global $connect;
	$que = mysql_query("SELECT COALESCE(SUM(status), 0) AS sum FROM sent_totals WHERE number = '$number' AND subject = '$code'") or die(mysql_error());
	while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['sum'];
		}
		return $nei;
}
function list_tquestions($code,$date,$date2){
	global $connect;
    switch($date){
      case 'today':
      $today = date("Y-m-d",time());
      $que = mysql_query("SELECT COALESCE(SUM(status), 0) AS sum FROM sent_totals WHERE subject = '$code' AND DATE(date_) = '$today'") or die(mysql_error());
      break; 
      case 'week': 
      $que = mysql_query("SELECT COALESCE(SUM(status), 0) AS sum FROM sent_totals WHERE subject = '$code' AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW()") or die(mysql_error());
      break; 
      case 'month': 
      $que = mysql_query("SELECT COALESCE(SUM(status), 0) AS sum FROM sent_totals WHERE subject = '$code' AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 MONTH) AND NOW()") or die(mysql_error());
      break;
      case 'term': 
      $que = mysql_query("SELECT COALESCE(SUM(status), 0) AS sum FROM sent_totals WHERE subject = '$code' AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 3 MONTH) AND NOW()") or die(mysql_error());
      break;
    }
	while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['sum'];
		}
		return $nei;
}
function list_responses(){
	global $connect;
	$que = mysql_query("SELECT COUNT(*) AS sum FROM responses") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return '0';
	}else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['sum'];
		}
		return $nei;
	}
}
function list_nresponses($number){
	global $connect;
	$que = mysql_query("SELECT COUNT(*) AS sum FROM responses WHERE number LIKE '%$number%'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return '0';
	}else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['sum'];
		}
		return $nei;
	}
}
function list_cresponses($class){
	global $connect;
	$que = mysql_query("SELECT COUNT(*) AS sum FROM responses WHERE subject LIKE '$class%'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return '0';
	}else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['sum'];
		}
		return $nei;
	}
}
function list_tresponse($code){
	global $connect;
	$que = mysql_query("SELECT COUNT(*) AS sum FROM responses WHERE subject = '$code'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return '0';
	}else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['sum'];
		}
		return $nei;
	}
}
function list_ntresponse($number,$code){
	global $connect;
	$que = mysql_query("SELECT COUNT(*) AS sum FROM responses WHERE number LIKE '%$number%' AND subject = '$code'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return '0';
	}else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['sum'];
		}
		return $nei;
	}
}
function list_tresponses($code,$date,$date2){
	global $connect;
    switch($date){
      case 'today':
      $today = date("Y-m-d",time());
      $que = mysql_query("SELECT COUNT(*) AS sum FROM responses WHERE subject = '$code' AND DATE(time_) = '$today'") or die(mysql_error());
      break;
      case 'week': 
      $que = mysql_query("SELECT COUNT(*) AS sum FROM responses WHERE subject = '$code' AND time_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW()") or die(mysql_error());
      break;  
      case 'month': 
      $que = mysql_query("SELECT COUNT(*) AS sum FROM responses WHERE subject = '$code' AND time_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 MONTH) AND NOW()") or die(mysql_error());
      break;
      case 'term': 
      $que = mysql_query("SELECT COUNT(*) AS sum FROM responses WHERE subject = '$code' AND time_ BETWEEN DATE_SUB(NOW(),INTERVAL 3 MONTH) AND NOW()") or die(mysql_error());
      break;
    }
	if(mysql_num_rows($que) == 0){
		return '0';
	}else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['sum'];
		}
		return $nei;
	}
}
function list_tcorrect($code){
	global $connect;
	$que = mysql_query("SELECT COUNT(*) AS count FROM responses WHERE subject = '$code' AND response = correct_response") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return '0';
	}else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['count'];
		}
		return $nei;
	}
}
function list_ccorrect($class){
	global $connect;
	$que = mysql_query("SELECT COUNT(*) AS count FROM responses WHERE subject LIKE '$class%' AND response = correct_response") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return '0';
	}else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['count'];
		}
		return $nei;
	}
}
function list_ntcorrect($number,$code){
	global $connect;
	$que = mysql_query("SELECT COUNT(*) AS count FROM responses WHERE (number LIKE '%$number%' AND subject = '$code') AND response = correct_response") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return '0';
	}else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['count'];
		}
		return $nei;
	}
}
function list_tcorrects($code,$date,$date2){
	global $connect;
     switch($date){
      case 'today':
      $today = date("Y-m-d",time());
      $que = mysql_query("SELECT COUNT(*) AS count FROM responses WHERE (subject = '$code' AND response = correct_response) AND DATE(time_) = '$today'") or die(mysql_error());
      break;  
      case 'week': 
      $que = mysql_query("SELECT COUNT(*) AS count FROM responses WHERE (subject = '$code' AND response = correct_response) AND time_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW()") or die(mysql_error());
      break;
      case 'month': 
      $que = mysql_query("SELECT COUNT(*) AS count FROM responses WHERE (subject = '$code' AND response = correct_response) AND time_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 MONTH) AND NOW()") or die(mysql_error());
      break;
      case 'term': 
      $que = mysql_query("SELECT COUNT(*) AS count FROM responses WHERE (subject = '$code' AND response = correct_response) AND time_ BETWEEN DATE_SUB(NOW(),INTERVAL 3 MONTH) AND NOW()") or die(mysql_error());
      break;
    }
	if(mysql_num_rows($que) == 0){
		return '0';
	}else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['count'];
		}
		return $nei;
	}
}
function get_revenue_today(){
	global $connect;
    $today = date("Y-m-d",time());
	$que = mysql_query("SELECT COALESCE(SUM(total_), 0) AS sum FROM revenue WHERE DATE(day_) = '$today'") or die(mysql_error());
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['sum'];
		}
		return $nei;
}
function get_revenue_week(){
	global $connect;
	$que = mysql_query("SELECT COALESCE(SUM(total_), 0) AS sum FROM revenue WHERE day_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW()") or die(mysql_error());
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['sum'];
		}
		return $nei;
}
function get_revenue_month(){
	global $connect;
	$que = mysql_query("SELECT COALESCE(SUM(total_), 0) AS sum FROM revenue WHERE day_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 MONTH) AND NOW()") or die(mysql_error());
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['sum'];
		}
		return $nei;
}
function get_revenue_lifetime(){
	global $connect;
	$que = mysql_query("SELECT SUM(total_) AS sum FROM revenue") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return '0';
	}else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['sum'];
		}
		return $nei;
	}
}
function check_user($username){
	global $connect;
	$que = mysql_query("SELECT * FROM users WHERE username='$username'",$connect) or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}
function check_db_username($username){
   global $connect;
	$que = mysql_query("SELECT username FROM users WHERE username='$username'",$connect) or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}else{
		return true;
	}
}
function list_db_password($email){
   global $connect;
	$que = mysql_query("SELECT password FROM users WHERE email='$email'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['password'];
		}
		return $nei;
	}
}
function log_action($action, $message="",$logfile = 'logs/logs.txt') {
    if($handle = fopen($logfile, 'a')) { // append
    $timestamp = date("F j, Y, g:i a",time());
	$content = "{$timestamp} | {$action}: {$message}\n";
    fwrite($handle, $content);
    fclose($handle);
  } 
}
function list_subscribers_today($filter,$value){
	global $connect;
    switch($filter){
        case 'class':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE class LIKE '%$value%' AND DATE(date_) = DATE(NOW())") or die(mysql_error());
        break;
        case 'code':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE code LIKE '%$value%' AND DATE(date_) = DATE(NOW())") or die(mysql_error());
        break;
        case 'subject':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE subject LIKE '%$value%' AND DATE(date_) = DATE(NOW())") or die(mysql_error());
        break;
    }
     if(mysql_num_rows($query) ==1){
              while($row = mysql_fetch_array($query)){
                if($row['count']==''){
                  return "0";
                  }
                else return $row['count'];
                }
            }
          else return "0";
}
function list_active_subscribers_today($filter,$value){
	global $connect;
    switch($filter){
        case 'class':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE status = 1 AND (class LIKE '%$value%' AND DATE(date_) = DATE(NOW()))") or die(mysql_error());
        break;
        case 'code':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE status = 1 AND (code LIKE '%$value%' AND DATE(date_) = DATE(NOW()))") or die(mysql_error());
        break;
        case 'subject':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE status = 1 AND (subject LIKE '%$value%' AND DATE(date_) = DATE(NOW()))") or die(mysql_error());
        break;
    }
     if(mysql_num_rows($query) ==1){
              while($row = mysql_fetch_array($query)){
                if($row['count']==''){
                  return "0";
                  }
                else return $row['count'];
                }
            }
          else return "0";
}
function list_cancelled_subscribers_today($filter,$value){
	global $connect;
    switch($filter){
        case 'class':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE status = 3 AND (class LIKE '%$value%' AND DATE(date_) = DATE(NOW()))") or die(mysql_error());
        break;
        case 'code':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE status = 3 AND (code LIKE '%$value%' AND DATE(date_) = DATE(NOW()))") or die(mysql_error());
        break;
        case 'subject':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE status = 3 AND (subject LIKE '%$value%' AND DATE(date_) = DATE(NOW()))") or die(mysql_error());
        break;
    }
     if(mysql_num_rows($query) ==1){
              while($row = mysql_fetch_array($query)){
                if($row['count']==''){
                  return "0";
                  }
                else return $row['count'];
                }
            }
          else return "0";
}
function list_incomplete_subscribers_today($filter,$value){
	global $connect;
    switch($filter){
        case 'class':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE status = 2 AND (class LIKE '%$value%' AND DATE(date_) = DATE(NOW()))") or die(mysql_error());
        break;
        case 'code':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE status = 2 AND (code LIKE '%$value%' AND DATE(date_) = DATE(NOW()))") or die(mysql_error());
        break;
        case 'subject':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE status = 2 AND (subject LIKE '%$value%' AND DATE(date_) = DATE(NOW()))") or die(mysql_error());
        break;
    }
     if(mysql_num_rows($query) ==1){
              while($row = mysql_fetch_array($query)){
                if($row['count']==''){
                  return "0";
                  }
                else return $row['count'];
                }
            }
          else return "0";
}
function list_subscribers_week($filter,$value){
	global $connect;
    switch($filter){
        case 'class':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE class LIKE '%$value%' AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW()") or die(mysql_error());
        break;
        case 'code':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE code LIKE '%$value%' AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW()") or die(mysql_error());
        break;
        case 'subject':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE subject LIKE '%$value%' AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW()") or die(mysql_error());
        break;
    }
     if(mysql_num_rows($query) ==1){
              while($row = mysql_fetch_array($query)){
                if($row['count']==''){
                  return "0";
                  }
                else return $row['count'];
                }
            }
          else return "0";
}
function list_active_subscribers_week($filter,$value){
	global $connect;
    switch($filter){
        case 'class':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=1 AND class LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW()") or die(mysql_error());
        break;
        case 'code':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=1 AND code LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW()") or die(mysql_error());
        break;
        case 'subject':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=1 AND subject LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW()") or die(mysql_error());
        break;
    }
     if(mysql_num_rows($query) ==1){
              while($row = mysql_fetch_array($query)){
                if($row['count']==''){
                  return "0";
                  }
                else return $row['count'];
                }
            }
          else return "0";
}
function list_cancelled_subscribers_week($filter,$value){
	global $connect;
    switch($filter){
        case 'class':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=3 AND class LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW()") or die(mysql_error());
        break;
        case 'code':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=3 AND code LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW()") or die(mysql_error());
        break;
        case 'subject':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=3 AND subject LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW()") or die(mysql_error());
        break;
    }
     if(mysql_num_rows($query) ==1){
              while($row = mysql_fetch_array($query)){
                if($row['count']==''){
                  return "0";
                  }
                else return $row['count'];
                }
            }
          else return "0";
}
function list_incomplete_subscribers_week($filter,$value){
	global $connect;
    switch($filter){
        case 'class':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=2 AND class LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW()") or die(mysql_error());
        break;
        case 'code':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=2 AND code LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW()") or die(mysql_error());
        break;
        case 'subject':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=2 AND subject LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW()") or die(mysql_error());
        break;
    }
     if(mysql_num_rows($query) ==1){
              while($row = mysql_fetch_array($query)){
                if($row['count']==''){
                  return "0";
                  }
                else return $row['count'];
                }
            }
          else return "0";
}
function list_subscribers_month($filter,$value){
	global $connect;
    switch($filter){
        case 'class':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE class LIKE '%$value%' AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 30 DAY) AND NOW()") or die(mysql_error());
        break;
        case 'code':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE code LIKE '%$value%' AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 30 DAY) AND NOW()") or die(mysql_error());
        break;
        case 'subject':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE subject LIKE '%$value%' AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 30 DAY) AND NOW()") or die(mysql_error());
        break;
    }
     if(mysql_num_rows($query) ==1){
              while($row = mysql_fetch_array($query)){
                if($row['count']==''){
                  return "0";
                  }
                else return $row['count'];
                }
            }
          else return "0";
}
function list_active_subscribers_month($filter,$value){
	global $connect;
    switch($filter){
        case 'class':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=1 AND class LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 30 DAY) AND NOW()") or die(mysql_error());
        break;
        case 'code':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=1 AND code LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 30 DAY) AND NOW()") or die(mysql_error());
        break;
        case 'subject':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=1 AND subject LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 30 DAY) AND NOW()") or die(mysql_error());
        break;
    }
     if(mysql_num_rows($query) ==1){
              while($row = mysql_fetch_array($query)){
                if($row['count']==''){
                  return "0";
                  }
                else return $row['count'];
                }
            }
          else return "0";
}
function list_cancelled_subscribers_month($filter,$value){
	global $connect;
    switch($filter){
        case 'class':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=3 AND class LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 30 DAY) AND NOW()") or die(mysql_error());
        break;
        case 'code':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=3 AND code LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 30 DAY) AND NOW()") or die(mysql_error());
        break;
        case 'subject':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=3 AND subject LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 30 DAY) AND NOW()") or die(mysql_error());
        break;
    }
     if(mysql_num_rows($query) ==1){
              while($row = mysql_fetch_array($query)){
                if($row['count']==''){
                  return "0";
                  }
                else return $row['count'];
                }
            }
          else return "0";
}
function list_incomplete_subscribers_month($filter,$value){
	global $connect;
    switch($filter){
        case 'class':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=2 AND class LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 30 DAY) AND NOW()") or die(mysql_error());
        break;
        case 'code':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=2 AND code LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 30 DAY) AND NOW()") or die(mysql_error());
        break;
        case 'subject':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=2 AND subject LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 30 DAY) AND NOW()") or die(mysql_error());
        break;
    }
     if(mysql_num_rows($query) ==1){
              while($row = mysql_fetch_array($query)){
                if($row['count']==''){
                  return "0";
                  }
                else return $row['count'];
                }
            }
          else return "0";
}
function list_subscribers_term($filter,$value){
	global $connect;
    switch($filter){
        case 'class':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE class LIKE '%$value%' AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 3 MONTH) AND NOW()") or die(mysql_error());
        break;
        case 'code':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE code LIKE '%$value%' AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 3 MONTH) AND NOW()") or die(mysql_error());
        break;
        case 'subject':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE subject LIKE '%$value%' AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 3 MONTH) AND NOW()") or die(mysql_error());
        break;
    }
     if(mysql_num_rows($query) ==1){
              while($row = mysql_fetch_array($query)){
                if($row['count']==''){
                  return "0";
                  }
                else return $row['count'];
                }
            }
          else return "0";
}
function list_active_subscribers_term($filter,$value){
	global $connect;
    switch($filter){
        case 'class':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=1 AND class LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 3 MONTH) AND NOW()") or die(mysql_error());
        break;
        case 'code':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=1 AND code LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 3 MONTH) AND NOW()") or die(mysql_error());
        break;
        case 'subject':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=1 AND subject LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 3 MONTH) AND NOW()") or die(mysql_error());
        break;
    }
     if(mysql_num_rows($query) ==1){
              while($row = mysql_fetch_array($query)){
                if($row['count']==''){
                  return "0";
                  }
                else return $row['count'];
                }
            }
          else return "0";
}
function list_cancelled_subscribers_term($filter,$value){
	global $connect;
    switch($filter){
        case 'class':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=3 AND class LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 3 MONTH) AND NOW()") or die(mysql_error());
        break;
        case 'code':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=3 AND code LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 3 MONTH) AND NOW()") or die(mysql_error());
        break;
        case 'subject':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=3 AND subject LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 3 MONTH) AND NOW()") or die(mysql_error());
        break;
    }
     if(mysql_num_rows($query) ==1){
              while($row = mysql_fetch_array($query)){
                if($row['count']==''){
                  return "0";
                  }
                else return $row['count'];
                }
            }
          else return "0";
}
function list_incomplete_subscribers_term($filter,$value){
	global $connect;
    switch($filter){
        case 'class':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=2 AND class LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 3 MONTH) AND NOW()") or die(mysql_error());
        break;
        case 'code':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=2 AND code LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 3 MONTH) AND NOW()") or die(mysql_error());
        break;
        case 'subject':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=2 AND subject LIKE '%$value%') AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 3 MONTH) AND NOW()") or die(mysql_error());
        break;
    }
     if(mysql_num_rows($query) ==1){
              while($row = mysql_fetch_array($query)){
                if($row['count']==''){
                  return "0";
                  }
                else return $row['count'];
                }
            }
          else return "0";
}
function list_subscribers_custom($value,$filter,$date,$date2){
	global $connect;
    switch($filter){
        case 'class':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE class LIKE '%$value%' AND date_ BETWEEN '$date' AND '$date2'") or die(mysql_error());
        break;
        case 'code':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE code LIKE '%$value%' AND date_ BETWEEN '$date' AND '$date2'") or die(mysql_error());
        break;
        case 'subject':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE subject LIKE '%$value%' AND date_ BETWEEN '$date' AND '$date2'") or die(mysql_error());
        break;
    }
     if(mysql_num_rows($query) ==1){
              while($row = mysql_fetch_array($query)){
                if($row['count']==''){
                  return "0";
                  }
                else return $row['count'];
                }
            }
          else return "0";
}
function list_active_subscribers_custom($filter,$value,$date,$date2){
	global $connect;
    switch($filter){
        case 'class':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=1 AND class LIKE '%$value%') AND date_ BETWEEN BETWEEN '$date' AND '$date2'") or die(mysql_error());
        break;
        case 'code':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=1 AND code LIKE '%$value%') AND date_ BETWEEN '$date' AND '$date2'") or die(mysql_error());
        break;
        case 'subject':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=1 AND subject LIKE '%$value%') AND date_ BETWEEN '$date' AND '$date2'") or die(mysql_error());
        break;
    }
     if(mysql_num_rows($query) ==1){
              while($row = mysql_fetch_array($query)){
                if($row['count']==''){
                  return "0";
                  }
                else return $row['count'];
                }
            }
          else return "0";
}
function list_cancelled_subscribers_custom($filter,$value,$date,$date2){
	global $connect;
    switch($filter){
        case 'class':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=3 AND class LIKE '%$value%') AND date_ BETWEEN '$date' AND '$date2'") or die(mysql_error());
        break;
        case 'code':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=3 AND code LIKE '%$value%') AND date_ BETWEEN '$date' AND '$date2'") or die(mysql_error());
        break;
        case 'subject':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=3 AND subject LIKE '%$value%') AND date_ BETWEEN '$date' AND '$date2'") or die(mysql_error());
        break;
    }
     if(mysql_num_rows($query) ==1){
              while($row = mysql_fetch_array($query)){
                if($row['count']==''){
                  return "0";
                  }
                else return $row['count'];
                }
            }
          else return "0";
}
function list_incomplete_subscribers_custom($filter,$value,$date,$date2){
	global $connect;
    switch($filter){
        case 'class':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=2 AND class LIKE '%$value%') AND date_ BETWEEN '$date' AND '$date2'") or die(mysql_error());
        break;
        case 'code':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=2 AND code LIKE '%$value%') AND date_ BETWEEN '$date' AND '$date2'") or die(mysql_error());
        break;
        case 'subject':
        $query = mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers WHERE (status=2 AND subject LIKE '%$value%') AND date_ BETWEEN $date AND '$date2'") or die(mysql_error());
        break;
    }
     if(mysql_num_rows($query) ==1){
              while($row = mysql_fetch_array($query)){
                if($row['count']==''){
                  return "0";
                  }
                else return $row['count'];
                }
            }
          else return "0";
}
?>