<?php
$from_number = $_REQUEST['From'];
$sip = "sip:700@166.62.119.180";
 header('Content-type: text/xml');
  echo '<?xml version="1.0" encoding="UTF-8"?>';
  echo '<Response>';
  echo '<Dial callerId="'.$from_number.'">';
        echo '<User>'.$sip.'</User>';
    echo '</Dial>';
  echo '</Response>';    
?>