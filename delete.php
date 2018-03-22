<?php
ob_start();
 session_start();
  require_once "config.php";
  require_once "functions.php";
  $cid = $_SESSION['uid'];
  $skip = $_GET['skip']; //delete skip 
  $early = $_GET['early']; //delete early
  $file = $_GET['filename']; 
  $del = $_GET['del'];
  $subject = $_GET['subject']; //delete subject
  $content = trim($_GET['content']); //delete content
  if(isset($del)){
        $query = mysql_query("DELETE FROM users WHERE id = '$del'");
          header("Location: account");  
  } 
  elseif(isset($subject)){
        $quer = mysql_query("DELETE FROM subjects WHERE id = '$subject'");
              header("Location: viewsubjects?filter=null&page=1");
  } 
  elseif(isset($content)){
        $query = @mysql_query("DELETE FROM uploadcontent WHERE id = '$content'");   
        header("Location: uploadcontent?filter=null&page=1");
           
  }
 
?>