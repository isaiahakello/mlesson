<?php
 require "config.php";
 require "functions.php";
 $que = mysql_query("SELECT * FROM revenue ORDER BY id ASC");
  while($value=mysql_fetch_array($que)){
    $count = $value['messages_'];
    $time = $value['date_'];
    $id = $value['id'];
    if($id > 41){
        $sum = $count * 3;
      mysql_query("UPDATE revenue SET total_ = '$sum' WHERE  id = '$id'") or die(mysql_error()); 
    }
    else {
        $sum = $count * 6;
        mysql_query("UPDATE revenue SET total_ = '$sum' WHERE  id = '$id'") or die(mysql_error());
    }
}
?>