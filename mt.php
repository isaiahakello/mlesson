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
    <link rel='shortcut icon' href='images/logo.png'/>
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
	<title>Dashboard:: Mvas System</title>     
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">MVAS SYSTEM DESIGN</div>
</div>
<div class="row-fluid">
<div class="span2" id="accordion"><?php include "header.php";?></div>
<div class="span8" style="">
 <form action="" method="POST" class="">
<div class="row-fluid" style="border-bottom: 2px solid black;background-color: silver;">
  <div class="span6" style="padding: 10px;">ADD SERVICE: 
   <ul class="nav nav-tabs">
    <li style="padding-top: 0px;"><a href="dashboard">General</a></li>
    <li><a href="keywords">Keywords</a></li>
    <li><a href="billing">Billing</a></li>
    <li><a href="#" class="active">MT</a></li>  
  </ul>
  </div>
  <div class="span6" style="padding: 10px;"><a href="viewservices?filter=null&page=1">VIEW ALL SERVICES</a></div>
 </div>
 <?php
   if(isset($_POST['finish'])){
    //MT variables
          $welcomemt = $_POST['welcomemt'];
          $helpmt = $_POST['helpmt'];
          $cancelmt = $_POST['cancelmt'];
          $wrongkeymt = $_POST['wrongkeymt'];
          $pinmt = $_POST['pinmt'];
          $renewalmt = $_POST['renewalmt'];
       if(empty($_SESSION['service_id'])){
         header("Location: dashboard");
       }
      else{
        $service_id = $_SESSION['service_id'];        
        $query = mysql_query("UPDATE services SET welcomemt='$welcomemt',helpmt='$helpmt',cancelmt='$cancelmt',wrongkeymt='$wrongkeymt',pinmt='$pinmt',renewalmt='$renewalmt' WHERE service_id = '$service_id'") or die(mysql_error());
        echo "<div style=\"color: green; padding: 10px; padding-bottom: 5px;\">"."Service created"."</div>";
        unset($_SESSION['service_id']);
        }        
   }
 ?>
 <div class="row-fluid content" style="">    
    <h5 style="padding-left: 10px;">Billing</h5>
    <table style="margin-left: 10px;">
    <tr>
     <td>Welcome MT:</td>
     <td><input type="text" name="welcomemt" style="" required="" placeholder=""/></td> 
    </tr>
    <tr>
     <td>Help MT:</td>
     <td><input type="text" name="helpmt" style="" required="" placeholder=""/></td> 
    </tr>
    <tr>
     <td>Cancelation MT:</td>
     <td><input type="text" name="cancelmt" style="" required="" placeholder=""/></td> 
    </tr>
     <tr>
     <td>Wrong Keyword MT:</td>
     <td><input type="text" name="wrongkeymt" style="" required="" placeholder=""/></td> 
    </tr>
    <tr>
     <td>PIN MT:</td>
     <td><input type="text" name="pinmt" style="" required="" placeholder=""/></td> 
    </tr>
    <tr>
     <td>Renewal MT:</td>
     <td><input type="text" name="renewalmt" style="" required="" placeholder=""/></td> 
    </tr>
    </table>
    <div style="float: right; margin-right: 50px; padding-bottom: 10px;">
    <a href="billing" class="btn">&lsaquo;&lsaquo; PREVIOUS</a>
    <input type="submit" value="FINISH" name="finish" class="btn btn-primary"/>
    </div>
    </div>
  </form>
</div> 
</div>
<?php include "footer.php";?>
</div>
</body>
</html>