<?php
ob_start();
 require_once "config.php";
 require_once "timeout.php";
 require_once "session.php";
 require_once "functions.php";
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
    <script src='js/jquery-1.9.1.js'></script>
    <script src='js/bootstrap.min.js'></script>
    <script src='js/jquery-ui.js'></script>
    <script src="js/js.js"></script>	
	<title>Upload Files:: Just2MinutesAday</title>
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">JUST 2 MINUTES A DAY</div>
<div class="span4"style="padding: 10px;color: white; text-align: right;">
<ul class="navigation">
<li><a href="allcontacts" style="background-color: #00FF00; cursor: pointer; padding: 5px; color: black; width: 70px;">Roster</a></li>
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
<div style="background-color: #98AFC7; padding: 10px; color: white;"> System Files: New User</div>
     <div style="height: 500px; overflow-y: auto;">
     <?php $clgroup = list_files_newuser();
          if(is_array($clgroup)){  ?>
          <table class="table">
                                    <thead>
                                    <tr>
                                        
                                        <th>Menu</th>
                                        <th>Preview Lect.</th>
                                        <th>Sign Up</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php                                        
                                         foreach($clgroup as $value){
                                            $id=$value['id'];
                                            $menu=$value['menu'];
                                            $preview=$value['preview'];
                                            $signup=$value['signup'];
                                        ?>
                                    <tr>
                                        <td><a href="newuser?change=menu" title="change"><?php echo $menu; ?></a>
                                            <a href="<?php echo $menu;?>" id="listen" title="play" onclick="return playFile(this);"> 
                                            <i class="fa fa-play" style="font-size: 0.8em; color: red;"></i></a></td>
                                        <td><a href="newuser?change=preview" title="change"><?php echo $preview; ?></a>
                                            <a href="<?php echo $preview;?>" id="listen" title="play" onclick="return playFile(this);"> 
                                            <i class="fa fa-play" style="font-size: 0.8em; color: red;"></i></a></td>
                                        <td><a href="newuser?change=signup" title="change"><?php echo $signup; ?></a>
                                            <a href="<?php echo $signup;?>" id="listen" title="play" onclick="return playFile(this);"> 
                                            <i class="fa fa-play" style="font-size: 0.8em; color: red;"></i></a></td>
                                    </tr>
                                    </tbody>
                                </table>
       <?php } } else {  echo "You have not uploaded any files! "; } 
       if(isset($_GET['change'])){ ?>
        
        <form class="form-horizontal" enctype="multipart/form-data" action="" method="POST" style="font-size: 1em; padding: 10px; background-color: white;">
        <?php 
           $value = $_GET['change'];
           if(isset($_POST['change'])){
            $upload_dir = "/var/lib/asterisk/sounds"; 
            $fall_back = "uploads";
            $tmp_file = $_FILES['change_file']['tmp_name'];
            $newfile = $value.'.mp3';  
            $upload_dir = "/var/lib/asterisk/sounds"; 
            $fileToCopy = "./uploads/".$newfile;
            if (file_exists($fileToCopy)) {
                 unlink($fileToCopy);
               }
            $destination = $upload_dir."/".$newfile;       
            move_uploaded_file($tmp_file,$destination);
            if(!@copy($destination,$fileToCopy)){
                $errors= error_get_last();
                echo "COPY ERROR: ".$errors['type'];
                echo "<br />\n".$errors['message'];
                } 
            else{
                $query = mysql_query("UPDATE newuser SET $value = '$newfile'") or die(mysql_error());
                header("Location: newuser");
            }   
           }
           ?>
        <div class="form-group">
        <span>Upload New File:</span>
            <input type="file" name="change_file" style="width: 60%;"/> 
        </div>
        <div class="form-group">
            <input type="submit" name="change" class="btn btn-success" value="CHANGE" style="margin-left: 110px; margin-top: 10px;"/>
        </div>
        </form>
       <?php } ?>            
 </div>
</div> 
</div>
<?php include "footer.php";?>
</div>
</body>
<script type="text/javascript">
  function playFile(clicked){
	var src = clicked.getAttribute('href');
    var file = "uploads/"+src;
    var audio = new Audio(file).play();
    return false;
}
</script>
</html>