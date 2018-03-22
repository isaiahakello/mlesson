<?php
ob_start();
error_reporting(0);
ini_set('display_errors', 0);
ini_set("date.timezone", "Africa/Nairobi");
 require_once "config.php";
 require_once "timeout.php";
 require_once "session.php";
 require_once "functions.php";
isSubscriberManager();
 $filter_term = $_GET['filter'];
 $value = $_GET['new_value'];
?>
<!DOCTYPE HTML>
    <html>
    <head>
	<meta http-equiv='content-type' content='text/html'/>
    <meta name='description' content=''/>
    <meta name='keywords' content=''/>
    <link rel='shortcut icon' href='images/logo.png'/>
    <link href='css/bootstrap.css' rel='stylesheet' type='text/css'/>
    <link href='css/bootstrap-responsive.css' rel='stylesheet' type='text/css'/>
    <link rel='stylesheet' href='css/main.css'/>
    <link href='css/smoothness/jquery-ui-1.10.3.custom.css' rel='stylesheet'/>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/chosen.css"/>
    <script src='js/jquery-1.9.1.js'></script>
    <script src='js/bootstrap.min.js'></script>
    <script src='js/jquery-ui.js'></script>
     <script type="text/javascript" src="js/chosen.jquery.js"></script>
    <script type="text/javascript" src="js/reports.js"></script>			
  <title>Add Subscriber:: M-Lesson</title>
  <script>
    $(function() {
      $(".chosen-select").chosen();
     });
    </script>	
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">M-LESSON</div>
</div>
<div class="row-fluid" style="background: url('images/bg.jpg');">
<div class="span2" id="accordion"><?php include "header.php";?></div>
<div class="span10 login_content">

 <form action="" method="POST" style="text-align: center;">
 <legend>Add Subscriber</legend>
 <?php
   if($_POST['add']){
    $now = date("Y-m-d H:i:s",time());
    $senderid = 'MLesson';
    $post = $_POST['subject'];
    $subject_id = explode(":",$post)[0];
    $su = explode(":",$post)[1];
    $phone = $_POST['phone'];
     $class = $subject_id[1];
    if(service_exists($subject_id)){ //we have the subject id
    if(!is_registered($phone,$subject_id)){  //if pupil not already registered for the subject   
   
    $details = get_previous_level($phone);
    $level = $details[0];
    $new_level = $level+1;
    $sub_id = random_string(9);
    $msgid = random_string(9);
    $que = mysql_query("INSERT INTO subscribers (number,country,sub_id,date_,code,subject,class,sub_level,status) VALUES ('$phone','Kenya','$sub_id','$now','$subject_id','$su','$class','$new_level','1')") or die(mysql_error());   
    $reply = "Thank you for joining M-Lesson C$class $su. You will receive your daily questions at 6pm-please ensure you have airtime! Call 0709748682 for help";    
    $query = mysql_query("INSERT INTO send_sms (momt,sender,receiver,msgdata,sms_type,smsc_id,dlr_mask,charset,coding) VALUES ('MT','$senderid','$phone','$reply','2','mlesson','31','utf-8',0)");  
     if(!is_schedule($phone,$subject_id)){
     $quer = mysql_query("INSERT INTO billing (number,subject,date_,start,next_billing,status) VALUES ('$phone','$subject_id','$now','yes','$now','begin')") or die(mysql_error()); 
        }
     echo "<span style=\"color: green;padding: 10px; padding-bottom: 5px;\">"."Subscription successfully created..."."</span>";
     }
     else {
     echo "<span style=\"color: red; padding: 10px; padding-bottom: 5px;\">"."<b>$phone </b>already subscribed to C$class $su."."</span>";    
     }  
    }
         
   }
 ?>
    <div class="form-group" style="margin-bottom: 10px;"> <label>Select Subscriber</label>
                <?php 
                   $details = select_subscribers();
                   if(is_array($details)){ ?>
          <select name="phone" class="chosen-select" style="width: 20%;">
            <option value="" selected="">...select subscriber...</option>
            <?php foreach($details as $detail){ ?>
             <option value="<?php echo $detail['number'];?>"><?php echo $detail['number'];?></option>   
            <?php } ?>
         </select>
     <?php } else echo 'There are no subscribers ..';?>   
    </div>
   <div class="form-group" style="margin-top: 10px;"><label>Select Subject</label> 
               <?php $details = list_subjects();
                   if(is_array($details)){ ?>
             <select name="subject" class="chosen-select" style="width: 20%;">
                    <option value="" selected="">...select subject...</option>
                    <?php foreach($details as $detail){ ?>
                     <option value="<?php echo $detail['subject_id'].":".$detail['subject'];?>"><?php echo $detail['subject']." ".$detail['class'];?></option>   
                    <?php } ?>
             </select>
     <?php } else echo 'There are no subjects ..';?> 
    </div>
    <div style="">
    <input type="submit" value="ADD" name="add" class="btn btn-primary" style="margin-top: 10px;"/>
    </div>
  </form>     

</div>
</div>  	
<?php include "footer.php";?>
</div>
</body>
</html>