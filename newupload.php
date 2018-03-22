<?php
ob_start();
 require_once "config.php";
 require_once "timeout.php";
 require_once "functions.php";
 require_once "session.php";
 confirm_logged_in();
 $cid = $_SESSION['uid'];
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
    <script type="text/javascript" src="js/js.js"></script>			
	<title>Upload Files:: Just2MinutesAday</title>
</head>

<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">JUST 2 MINUTES A DAY</div>
<div class="span4"style="padding: 10px;color: white; text-align: right;">
<ul class="navigation">
<li><a href="newupload" style="background-color: #00FF00; cursor: pointer; padding: 5px; color: black; width: 70px;">Upload Files</a></li>
<li class="dropdown" style="margin-right: 30px;">
   <span data-toggle="dropdown" class="dropdown-toggle" style="cursor: pointer;"><i class="fa fa-user"></i> <?php echo $_SESSION['uid'];?> <i class="fa fa-caret-down"></i></span>
     <ul class="dropdown-menu" style="padding: 10px; margin-left: -198px; width: 250px;">
     <?php include "templates/account.php";?>
     </ul>           
  </li>
</ul>
</div>
</div>
<div class="row-fluid">
  <div class="span2" id="accordion"><?php include "header.php";?></div>
  <div class="span10">
     <div style="background-color: #98AFC7; color: white; padding: 10px;margin-top: 10px;"><i class="fa fa-upload" style="color: white;"></i> Upload Files</div>
      <div class="span6 offset2 shadow" style="margin-top: 10px;">
      <form class="form-horizontal" enctype="multipart/form-data" action="" method="POST" style="font-size: 1em; padding-top: 10px;">
      <?php  
      if($_POST['upload']){ 
        $welcome = @basename($_FILES['intro']['name']);
        $preview = @basename($_FILES['preview']['name']);
        $signup = @basename($_FILES['signup']['name']);
        $donation = @basename($_FILES['donation']['name']);
        $comments = @basename($_FILES['comments']['name']);
        //make an array of the files and upload all
      if(!empty($welcome) && !empty($preview) && !empty($signup) && !empty($donation) && !empty($comments)){
        $files = array('intro','preview','signup','donation','comments');
         $to_insert = "";
         for($i=0; $i<count($files); $i++){
            $sub_file = $files[$i];
            $tmp_file = $_FILES[$sub_file]['tmp_name'];
            $newfile = $sub_file.'.mp3';
	        $upload_dir = "/var/lib/asterisk/sounds"; 
            $fileToCopy = "./uploads/".$newfile;
            $destination = $upload_dir."/".$newfile;       
            move_uploaded_file($tmp_file,$destination);
            if(!@copy($destination,$fileToCopy)){
                $errors= error_get_last();
                echo "COPY ERROR: ".$errors['type'];
                echo "<br />\n".$errors['message'];
                } 
             $to_insert .= "'".$newfile."',";                 
             }
           $to_insert = substr($to_insert, 0, -1);
           $to_insert = "'',".$to_insert;
          $que = @mysql_query("TRUNCATE TABLE newuser");
          $query = mysql_query("INSERT INTO newuser VALUES ($to_insert)") or die(mysql_error());
          echo "<div style=\"color: green; padding-bottom:5px; padding-top: 10px;\">"."Files uploaded."."</div>";      
      }
     else echo "<div style=\"color: red;\">"."Upload failed. Please select all files"."</div>";                              
     }
  ?>
          <fieldset style="margin-top: 10px;">                   
          <table style="" align="center">
           <tr><td><label>Welcome File</label></td>
               <td><input type="file" name="intro" style=""/></td>
           </tr>
           <tr><td><label>Preview Lecture</label></td>
               <td><input type="file" name="preview" style=""/></td>
           </tr>
           <tr><td><label>Sign Up File</label></td>
               <td><input type="file" name="signup" style=""/></td>
           </tr>
           <tr>
            <td><label>Donation File</label></td>
            <td><input type="file" name="donation" style=""/></td>
           </tr>
           <tr>
            <td><label>Comments File</label></td>
            <td><input type="file" name="comments" style=""/></td>
           </tr>
           <tr><td></td>
               <td><input type="submit" name="upload" class="btn btn-success" id="submit_btn" value="UPLOAD" /></td></tr>
          </table>                 
          </fieldset>
</form>
  </div>
  </div>
<?php include "footer.php";?>
</div>
</body>
</html>