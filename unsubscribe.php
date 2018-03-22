<?php
require_once "config.php";
require_once "functions.php";
$details = list_Unsubscribes();
$base_url = "http://gateway.miranetworks.net/bulksms/v2";
foreach($details as $detail){
    $service = $detail['service'];
    $msisdn = $detail['number'];
    $id = $detail['id'];
  switch($service){
    case 't10e569m': //partner
       $username = "bigjs_findapartner_1907_gh_i";
       $password = "ka5bhmf7";
     break;
    case 'HOW TO DEVELOP THE RIGHT FLIRTING':
       $username = "bigjs_flirtingtips_1907_gh_i";
       $password = "ecb9edmf";
     break;
    case 'COOKING TIPS':
       $username = "bigjs_findapartner_1907_gh_i";
       $password = "ka5bhmf7";
     break;
    case 'HOW TO PICK UP A MAN':
       $username = "bigjs_pickupmen_1907_gh_i";
       $password = "c585cgm5";
     break;
    case 'WHAT HAPPENED ON THIS DAY':
       $username = "bigjs_onthisday_1907_gh_i";
       $password = "m94p7kgd";
     break;
     case 'bvnj58t4': //world records
       $username = "bigjs_worldrecords_1907_gh_i";
       $password = "7549797m";
     break;
     }
     $text = "unsubscription";
     $slap_url = build_url($base_url,$username,$password,$text,$msisdn,$id);
     $results = explode(':',file_get_contents($slap_url));
     print_r($results);
     $action = end($results);
     $query = mysql_query("UPDATE subscribers SET action = '$action',status='2' WHERE id = '$id'");
}
function build_url($base_url,$username,$password,$message,$msisdn,$id){
    $query_string ='username='.urlencode($username).'&text='.urlencode($message).'&msisdn='.urlencode($msisdn).'&subsaction=unsubscribe&cost=0&clientref='.$id;
    $md5_value = md5($password . $query_string);
    $query_string .= '&md5='.$md5_value;
    $url = $base_url . "?" . $query_string;
  return $url;
 }
?>