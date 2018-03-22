<?php
ob_start();
 require_once "config.php";
 require_once "session.php";
 require_once "functions.php";
 canView();
 $id = $_GET['quizid'];
 $details = list_files($id);
 $subjects = list_subjects();
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
	<title>Edit Content:: mLESSON</title>     
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">mLESSON</div>
</div>
<div class="row-fluid">
<div class="span2" id="accordion"><?php include "header.php";?><h5 style="color: #0097AA; background-color: white; padding: 10px;">EDIT CONTENT</h5></div>
<div class="span8 login_content" style="padding: 10px;">
<h5 style="color: #0097AA; border-bottom: 1px solid rgb(204, 204, 204);"><span style="color: orange;">EDIT : </span><?php echo $details[0]['question'];?></h5>
<form action="" method="POST" class="row-fluid">
 <div class="span6">
  <table><tr><td></td><td>
 <?php
 if(isset($_GET['done'])){
    echo "<span style=\"color: green;\">"."Updated"."</span>";   
 }
   if($_POST['finish']){
          $subject = mysql_real_escape_string($_POST['subject']);
          $question = mysql_real_escape_string($_POST['question']);
          $opta = mysql_real_escape_string($_POST['opta']);
          $optb = mysql_real_escape_string($_POST['optb']);
          $optc = mysql_real_escape_string($_POST['optc']);
          $optd = mysql_real_escape_string($_POST['optd']);
          $correcta = mysql_real_escape_string($_POST['correcta']);
          $corr = mysql_real_escape_string($_POST['correcttext']);
          $incor = mysql_real_escape_string($_POST['incorrecttext']);
          $qdate = mysql_real_escape_string($_POST['qdate']);
          $qnumber = mysql_real_escape_string($_POST['qnumber']);
          $time = $_POST['time_'];
   $query = mysql_query("UPDATE uploadcontent SET subject='$subject',question_text='$question',question_no='$qnumber',answer_a='$opta',answer_b='$optb',answer_c='$optc',answer_d='$optd',correct_answer='$correcta',correct_text='$corr',incorrect_text='$incor',question_date='$qdate',deploy_time='$time' WHERE id = '$id'") or die(mysql_error());
    header("Location: edit?quizid=$id&done");                                                    
 }
 ?></td></tr> 
     <tr><td>Subject</td>
         <td> <select name="subject" class="chosen-select"style="width: 100%;">
               <option value="<?php echo $details[0]['subject_id'];?>" selected=""><?php echo $details[0]['subject']." ".$details[0]['class'];?></option>
                <?php foreach($subjects as $subject){ ?>
               <option value="<?php echo $subject['subject_id'];?>"><?php echo $subject['subject']." ".$subject['class'];?></option>   
               <?php } ?>
             </select></td></tr> 
     <tr>
     <td>Question:</td>
      <td><textarea name="question" style="" required="" ><?php echo $details[0]['question_text'];?></textarea></td> 
     </tr>
     <tr>
     <td>Option A:</td>
      <td><input type="text" name="opta" required="" value="<?php echo $details[0]['answer_a'];?>" style="border: 1px solid #2B65EC; color: #2B65EC;"/></td> 
     </tr>
     <tr>
     <td>Option B:</td>
      <td><input type="text" name="optb" style="" required="" value="<?php echo $details[0]['answer_b'];?>" style="border: 1px solid #6A287E; color: #6A287E;"/></td> 
    </tr>
     <tr>
     <td>Option C:</td>
      <td><input type="text" name="optc" required="" value="<?php echo $details[0]['answer_c'];?>" style="border: 1px solid #C7A317; color: #C7A317;"/></td> 
    </tr>
     <tr>
     <td>Option D:</td>
      <td><input type="text" name="optd" required="" value="<?php echo $details[0]['answer_d'];?>" style="border: 1px solid #C04000; color: #C04000;"/></td> 
    </tr>
     <tr>
     <td>Correct Answer:</td>
      <td><input type="text" name="correcta" required="" value="<?php echo $details[0]['correct_answer'];?>" style="border: 1px solid #41A317; color: #41A317;"/></td> 
    </tr>
    </table>
    </div>
    <div class="span6">
    <table>    
     <tr>
     <td>Correct Answer Text:</td>
      <td><textarea name="correcttext" required="" style="color: green;"><?php echo $details[0]['correct_text'];?></textarea></td> 
     </tr>
     <tr>
     <td>Incorrect Answer Text:</td>
      <td><textarea name="incorrecttext" required="" style="color: #FF2400;"><?php echo $details[0]['incorrect_text'];?></textarea></td> 
     </tr>
     <tr>
     <td>Question Date:</td>
      <td><input type="text" name="qdate" id="datepicker2" required="" value="<?php echo $details[0]['question_date'];?>"/></td> 
    </tr>
    <tr>
     <td>Deploy Time:</td>
     <td><input type="text" name="time_" id="timepicker" required="" value="<?php echo $details[0]['deploy_time'];?>" /></td> 
    </tr>
    <tr>
     <td>Question Number:</td>
      <td><input type="text" name="qnumber" required="" value="<?php echo $details[0]['question_no'];?>"/></td> 
    </tr> 
    <tr>
     <td></td><td><input type="submit" value="UPDATE" name="finish" class="btn btn-primary"/></td> 
    </tr>   
    </table>
    </div>
    
  </form>
  
</div> 
</div>
<?php include "footer.php";?>
</div>
</body>
</html>