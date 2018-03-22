<?php
ob_start();
ini_set("date.timezone", "Africa/Nairobi");
 require_once "config.php";
 require_once "session.php";
 require_once "functions.php";
 canView();

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
    <link href='css/smoothness/jquery-ui-1.10.3.custom.css' rel='stylesheet'/>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"/>
    <link href='//cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.1/jquery-ui-timepicker-addon.min.css' rel='stylesheet'/>
    <link rel="stylesheet" type="text/css" href="css/chosen.css"/>
    <script src='js/jquery-1.9.1.js'></script>
    <script src='js/bootstrap.min.js'></script>
    <script src='js/jquery-ui.js'></script>
    <script src='//cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.1/jquery-ui-timepicker-addon.min.js'></script>
    <script type="text/javascript" src="js/js.js"></script>	
    <script type="text/javascript" src="js/chosen.jquery.js"></script>
    <script>
    $(function() {
      $(".chosen-select").chosen();
     });
    </script>			
	<title>Reply:: mLESSON</title>     
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">MLESSON</div>
<div class="span4" style="padding: 10px;color: white; text-align: right;">
<ul class="navigation">
<li><a href="interaction?filter=null&page=1">Revenue</a></li>         
<li><a href="subscribers?filter=null&page=1">Subscriptions</a></li>
<li><a href="inbox?filter=null&page=1">Inbox</a></li>
<li class="dropdown" style="margin-right: 30px; color: black;">
   <span data-toggle="dropdown" class="dropdown-toggle" style="cursor: pointer;"><i class="fa fa-user"></i> <?php echo $_SESSION['uid'];?> <i class="fa fa-caret-down"></i></span>
     <ul class="dropdown-menu" style="padding: 5px; margin-left: -110px;">
      <a href="logout.php" style="color: #D1D0CE;">Logout</a>
     </ul>           
  </li>
</ul>
</div>
</div>
<div class="row-fluid">
<div class="span2" id="accordion"><?php include "header.php";?><h5 style="color: #0097AA; background-color: white; padding: 10px;">EDIT SUBSCRIBER</h5></div>
<div class="span8 login_content" style="padding: 10px;">
<h5 style="color: #0097AA; border-bottom: 1px solid rgb(204, 204, 204);"><span style="color: orange;">REPLY : </span><?php echo $_GET['phone'];?></h5>
 <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="" style="margin-top: 15px;">
<?php 
    if(isset($_POST['send'])){
          $schedule = $_POST['schedule'];
          $time = $_POST['time_'];
          $message = trim(strip_tags($_POST['message']));  
          $phones = array(protect($_POST['tophone']));
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
?>
<fieldset>
    <div class="form-group" style="margin-bottom: 10px;">
    <label class="control-label">Subscriber: </label>
        <input type="text" name="tophone" value="<?php echo $_GET['phone'];?>" readonly=""/>
    </div>
    <div class="form-group"><label class="control-label">Message: </label>
    <textarea placeholder="Message" name="message" style="height: 100px; width: 350px;"><?php echo isset($message)?$message:"";?></textarea>
    </div>
    <div class="form-group">
    <div style="font-weight: bolder; padding-left: 50px;">Schedule:</div>   	
    <label class="control-label">Date/Time:</label>
    <input type='text' name="schedule" id="datepicker" style="width: 18%;" placeholder="pick date"/>
    <input type="text" name="time_" id="timepicker" value="" style="width: 19%;" placeholder="Time to Send"/>  
    <input type="submit" name="send" class="btn btn-info" value="Send" />
</div>
    </fieldset>
    </form>    
</div> 
</div>
<?php include "footer.php";?>
</div>
</body>
</html>