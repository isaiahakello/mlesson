<?php
 require "config.php";
 require "functions.php";
 $que = mysql_query("SELECT *, COUNT(status) AS ct FROM sent_totals GROUP BY DATE(date_)");
  while($value=mysql_fetch_array($que)){
    $count = $value['ct'];
    $time = $value['date_'];
    $qu = mysql_query("INSERT INTO revenue (day_,messages_) VALUES ('$time','$count')") or die(mysql_error());
    //echo $id.": ".$phone.":  ".$code." date 1=".$dat_.": signup=".$date_.": cancel=".$cancel_."<br/>";
}
?>