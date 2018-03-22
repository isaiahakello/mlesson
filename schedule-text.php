<?php
ini_set("date.timezone", "Africa/Nairobi");
require_once "config.php";
require_once "functions.php";
$date = date("Y-m-d",time());
$now = date("Y-m-d H:i:s",time()); 
$que = mysql_query("TRUNCATE TABLE schedule");
$qu = mysql_query("INSERT INTO revenue (day_,messages_) VALUES ('$now','0')") or die(mysql_error());
$details = list_mtcontent(); //should be date, subject being send that day
if(is_array($details)){
$count = count($details);
foreach($details as $detail){
    $questionno = mysql_real_escape_string($detail['question']);
    $subjectid = $detail['subject'];
    $answer = mysql_real_escape_string($detail['correct_answer']);
    $correct_text = mysql_real_escape_string($detail['correct_text']);
    $incorrect_text = mysql_real_escape_string($detail['incorrect_text']);
    $id = $detail['id'];
    $que = mysql_query("INSERT INTO schedule (question_id,subject,answer,correct_text,incorrect_text,date_) VALUES ('$questionno','$subjectid','$answer','$correct_text','$incorrect_text','$now')");
}
 echo "<b>$count</b> questions scheduled.<br/>";
 echo "<a href='scheduled?filter=null&page=1'> Back </a>";
}
else echo "Nothing to schedule...";
$query = mysql_query("UPDATE billing SET next_billing = '$now', status = 'begin'");
?>