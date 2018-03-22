<?php
    $username = 'Mosespandi';
    $password = 'e8O80dKi';
    $postdata =  array(
        'from' => 'Uwazii',
        'to' => '254717856330',
        'text' => 'test message'
       );
    $process = curl_init("https://api.infobip.com/sms/1/text/single");
    curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/json','accept: application/json'));
    curl_setopt($process, CURLOPT_HEADER, 1);
    curl_setopt($process, CURLOPT_USERPWD, $username.":".$password);
    curl_setopt($process, CURLOPT_TIMEOUT, 30);
    curl_setopt($process, CURLOPT_POST, 1);
    curl_setopt($process, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
    $return = curl_exec($process);
   
    print_r (json_decode($return,true));
     curl_close($process);
?>