<?php
ob_start();
ini_set("date.timezone", "Africa/Nairobi");
 require_once "config.php";
 require_once "session.php";
 require_once "functions.php";
 canView();
 $details = list_subscribers();
?>
<!DOCTYPE HTML>
    <html>
    <head>
	<meta http-equiv='content-type' content='text/html'/>
    <meta name='description' content=''/>
    <meta name='keywords' content=''/>
    <link rel='shortcut icon' href='images/logo.jpg'/>
    <link href='css/bootstrap.css' rel='stylesheet' type='text/css'/>
    <link href='css/bootstrap-responsive.css' rel='stylesheet' type='text/css'/>
    <link rel='stylesheet' href='css/main.css'/>  
    <link rel="stylesheet" type="text/css" href="css/chosen.css"/> 
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"/>   
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"/>
     <link href='//cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.1/jquery-ui-timepicker-addon.min.css' rel='stylesheet'/>
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src='//cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.1/jquery-ui-timepicker-addon.min.js'></script>
    <script src='js/bootstrap.min.js'></script>
    <script type="text/javascript" src="js/chosen.jquery.js"></script>
    <script>
    $(function() {
      $(".chosen-select").chosen();
      $("#datepicker").datepicker({dateFormat: 'yy-mm-dd',
                             minDate: -1});
      $("#timepicker").timepicker({
	    timeFormat: "HH:mm:ss"
	}); 
     });
    </script>		
	<title>Bulk SMS:: MLESSON</title>     
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">MLESSON</div>
</div>
<div class="row-fluid" style="background: url('images/bg.jpg');">
<div class="span2" id="accordion"><?php include "header.php";?><h5 style="color: #0097AA; background-color: white; padding: 5px;">PROFESSIONAL SMS</h5></div>
<div class="span8 login_content">
<div class="eh">PROFESSIONAL SMS</div> 
 <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="" style="margin-top: 15px;">
<?php 
    if(isset($_POST['submit'])){
          $code = $_POST['code'];
          $schedule = $_POST['schedule'];
          $time = $_POST['time_'];
          $message = trim(strip_tags($_POST['message']));  
          $phones = list_phones($code);
           if(empty($schedule)){ // not scheduling
             send_message($phones,$message);
             echo "<div style=\"color: green; padding:5px;\">"."Message Sent..."."</div>";                   
                }
            else{
            $now = date("Y-m-d H:i:s",time());
            $schedule = $schedule." ".$time;
            if(strtotime($schedule) < time()){  //prevent negative scheduling
               echo "<div style=\"color: red;padding: 5px;\">"."Cannot schedule in the past!"."</div>";
             }
            else{                
                schedule_message($schedule,$phones,$message);
                echo "<div style=\"color: green; padding: 5px;\">"."Message Scheduled..."."</div>";               
             }
           }                    
 }
 elseif(isset($_POST['reset'])){
     header("Location:bulk-sms");
 }
?>
<fieldset>
    <div class="form-group" style="margin-bottom: 10px;">
    <label class="control-label">Subscriber: </label>
        <?php  if (is_array($details)) { ?>
        <select name="code" class="chosen-select" style="width: 43%;">
           <option value="" selected="">..select group..</option>
             <?php foreach($details as $value) {
                            $code = $value['code'];
                            ?>
             <option value="<?php echo $code;?>"><?php echo $code;?></option>
             <?php  } ?>
             </select> 
    <?php }  else echo "You do not have any subscribers to send messages to."; ?>
    </div>
    <div class="form-group"><label class="control-label">Message: </label>
    <textarea placeholder="Message" name="message" style="height: 100px; width: 350px;"><?php echo isset($message)?$message:"";?></textarea>
    </div>
    <div class="form-group">
    <div style="font-weight: bolder; padding-left: 50px;">Schedule:</div>   	
    <label class="control-label">Date/Time:</label>
    <input type='text' name="schedule" id="datepicker" style="width: 18%;" placeholder="<?php echo date('m-d-Y'); ?>"/>
    <input type="text" name="time_" id="timepicker" value="" style="width: 19%;" placeholder="Time to Send"/>  
</div>
  <div class="form-group" style="text-align: center;padding-top: 20px;">
    <input type="submit" name="submit" class="btn btn-info" value="Send" />
    <input type="submit" name="reset" class="btn btn-info" value="Reset" />
  </div>
    </fieldset>
    </form>    
</div> 
</div>
<?php include "footer.php";?>
</div>
</body>
</html>