<?php
ob_start();
 require_once "config.php";
 require_once "session.php";
 require_once "functions.php";
 confirm_logged_in();
?>
<!DOCTYPE HTML>
    <html>
    <head>
	<meta http-equiv='content-type' content='text/html'/>
    <meta name='description' content=''/>
    <meta name='keywords' content=''/>
    <link rel='shortcut icon' href='images/favicon.ico'/>
    <link href='css/bootstrap.css' rel='stylesheet' type='text/css'/>
    <link href='css/bootstrap-responsive.css' rel='stylesheet' type='text/css'/>
    <link rel='stylesheet' href='css/main.css'/>  
    <link rel="stylesheet" type="text/css" href="css/chosen.css"/> 
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"/>   
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"/>
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src='js/bootstrap.min.js'></script>
    <script type="text/javascript" src="js/chosen.jquery.js"></script>
    <script>
    $(function() {
      $(".chosen-select").chosen();
     });
    </script>		
	<title>Dashboard:: MLESSON</title>     
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span4" style="font-size: 2em;"><img src="images/logo.png" height="30"/></div>
<div class="span8"style="padding: 10px;color: white; text-align: right;">
<ul class="navigation">
<li><i class="fa fa-home" style="color: white;"></i> <a href="#" class="active">Home</a></li>       
<li><i class="fa fa-users" style="color: white;"></i> <a href="subscribers?filter=null&page=1">Subscriptions</a></li>
<li><i class="fa fa-file" style="color: white;"></i> <a href="interaction?filter=null&page=1">Content</a></li>   
<li><i class="fa fa-usd" style="color: white;"></i> <a href="interaction?filter=null&page=1">Revenue</a></li>   
<li><i class="fa fa-comments" style="color: white;"></i> <a href="inbox?filter=null&page=1">Messenger</a></li>
<li><i class="fa fa-area-chart" style="color: white;"></i> <a href="inbox?filter=null&page=1">Reports</a></li>
<li><i class="fa fa-lock" style="color: white;"></i> <a href="account" style="">Account</a></li>
<li class="dropdown" style="margin-right: 30px; color: black;">
   <span data-toggle="dropdown" class="dropdown-toggle" style="cursor: pointer;"><i class="fa fa-user" style="color: white;"></i> <?php echo $_SESSION['uid'];?> <i class="fa fa-caret-down"></i></span>
     <ul class="dropdown-menu" style="padding: 5px; margin-left: -110px;">
      <a href="logout.php" style="color: #D1D0CE;">Logout</a>
     </ul>           
  </li>
</ul>
</div>
</div>
<div class="row-fluid main-content">
<div class="span12 login_content" style="height: 550px;"></div>
</div>
<?php include "footer.php";?>
</div>
</body>
</html>