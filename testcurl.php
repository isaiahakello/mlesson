<?php
session_start();
// init the resource
$ch = curl_init();
$postData = array(
    'txtConsultantID' => 'PQ1824',
    'txtPassword' => 'jacen1',
    '__VIEWSTATE' => '/wEPDwULLTEzNTk2NTY5NjAPZBYCAgMPZBYEAgMPDxYCHgdWaXNpYmxlaGQWAgIDDxBkZBYBZmQCDQ8PZBYCHglvbmtleWRvd24FG2ZuVHJhcEtEKCdidG5TdWJtaXQnLGV2ZW50KWQYAQUeX19Db250cm9sc1JlcXVpcmVQb3N0QmFja0tleV9fFgEFCWJ0blN1Ym1pdKNi/T2WNzRb10gymtPiZ60r6Oiv',
    '__LASTFOCUS' => '',
    '__EVENTTARGET' => '',
    '__EVENTARGUMENT' => '',
    '__VIEWSTATEGENERATOR' => '5E7FB4E4',
    'testcookie' => '1',
    '__EVENTVALIDATION' => '/wEdAAa3yVZZGdn1ks2zfxYZVabCaNti0mEkTV3j9V7Mi2VCTxQWAUFrlAyxHCZURO+G9k52NvjHOkq5wKoqN6Aim8WGPOaW1pQztoQA36D1w/+bXTLe5XW+Uh3muxbWB9+U8FnMAgQr56c8qRnfpP/RkOzKXIQP1w=='
);
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://applications.marykayintouch.com/login/login.aspx',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $postData,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_COOKIESESSION => true,
    CURLOPT_COOKIEJAR => 'cookie.txt'
));
curl_exec($ch);
curl_close($ch);
$handle = fopen('cookie.txt','r');
$cookie = "";
  while(!feof($handle)) {
    $cookie .= fgets($handle);
    }
  echo $cookie;
?>