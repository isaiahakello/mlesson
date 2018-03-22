<?php
 require "config.php";
 require "functions.php";
 $que = mysql_query("SELECT * FROM scheduled_questions  WHERE Question_Date < '2016-11-18 00:00:00' ORDER by id ASC");
  while($value=mysql_fetch_array($que)){
    $code_ = $value['Subject'];
    $dat_ = strtotime($value['Question_Date']);
    $new_code = $code_.'1';
    //$id = $value['id'];
    $text = mysql_real_escape_string($value['Question']);
    $time = $value['Question_Date'];
    echo $new_code."<br/>";
    $query = mysql_query("SELECT * FROM subscribers WHERE code = '$new_code'");
      while($rows=mysql_fetch_array($query)){
       $code = $rows['code'];
       $date_ = $rows['date_'];
       //$cancel_ = strtotime($rows['canceldate']);
       $phone = $rows['number'];
       $msgid = random_string(15);
       $qu = mysql_query("INSERT INTO sent_totals (msgid,text,number,subject,date_) VALUES ('$msgid','$text','$phone','$code','$time')") or die(mysql_error());
      //echo $id.": ".$phone.":  ".$code." date 1=".$dat_.": signup=".$date_.": cancel=".$cancel_."<br/>";
      
    }
}
?>