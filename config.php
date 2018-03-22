<?php
$connect = mysql_connect('localhost', 'mlesson_applic', '@T7r3_1DVf*0');

mysql_select_db('mlesson_application', $connect) or die('Error:Database connection failed !');
mysql_set_charset('utf8',$connect);
ini_set('error_reporting',~E_NOTICE); 
?>

