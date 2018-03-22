<?php
ob_start();
 require_once "config.php";
 require_once "timeout.php";
 require_once "session.php";
 require_once "functions.php";
 confirm_logged_in();
 $cid = $_SESSION['uid'];
 $clgroup = list_files_newuser();
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
	<title>Manage Files:: Just2MinutesAday</title>
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">JUST 2 MINUTES A DAY</div>
<div class="span4"style="padding: 10px;color: white; text-align: right;">
<ul class="navigation">
<li><a href="newuser" style="background-color: #00FF00; cursor: pointer; padding: 5px; color: black; width: 70px;">Files</a></li>
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
<div class="span7">
<div style="margin-top: 10px; background-color: #3F6171; color: #FFF; line-height: 200%; padding: 0px 12px;font-size: 1.0em;">Manage Files</div>
  <div style="height: 500px; overflow-y: auto; padding-top: 10px;">
       <table class="table">
                        <tbody>
                            <?php
                            if(is_array($clgroup)){
                             foreach($clgroup as $value){
                                $id=$value['id'];
                                $welcome=$value['welcome'];
                                $preview=$value['preview'];
                                $signup=$value['signup'];
                                $donation=$value['donation'];
                                $comments=$value['comments'];
                                }
                             }
                            ?>
                        <tr><td style="color: black; font-weight: bolder;">Welcome File</td><td><?php echo $welcome;?></td>
                            <td><a href="<?php echo $welcome;?>"class="btn btn-success btn-xs" onclick="return Change()"><i class="fa fa-eye"></i> Update</a></td>
                        </tr>
                        <tr><td style="color: black; font-weight: bolder;">Preview File</td><td><?php echo $preview;?></td>
                        <td><a href="delete.php?_grpid=<?php echo $gname;?>"class="btn btn-success btn-xs"><i class="fa fa-eye"></i> Update</a></td>
                        </tr>
                        <tr><td style="color: black; font-weight: bolder;">Sign Up File</td><td><?php echo $signup;?></td>
                        <td><a href="delete.php?_grpid=<?php echo $gname;?>"class="btn btn-success btn-xs"><i class="fa fa-eye"></i> Update</a></td>
                        </tr>
                        <tr><td style="color: black; font-weight: bolder;">Donation File</td><td><?php echo $donation;?></td>
                        <td><a href="delete.php?_grpid=<?php echo $gname;?>"class="btn btn-success btn-xs"><i class="fa fa-eye"></i> Update</a></td>
                        </tr>                
                        <tr><td style="color: black; font-weight: bolder;">Comments File</td><td><?php echo $comments;?></td>
                        <td><a href="delete.php?_grpid=<?php echo $gname;?>"class="btn btn-success btn-xs"><i class="fa fa-eye"></i> Update</a></td>
                        </tr>                                                                                            
                        </tbody>
                    </table>
      </div>
   </div>
</div> 	
<?php include "footer.php";?>
</div>
</body>
<script type="text/javascript">
  function Change(){
    var myform = "<form><input type='text' name='form'/></form>";
	var test = confirm(myform);
        if(test == true){
         return true;
       }
      else {
        return false;
      }
}
</script>
</html>