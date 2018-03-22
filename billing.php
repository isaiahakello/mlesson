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
    <li><a href="#" class="active">Billing</a></li>
    <li><a href="mt">MT</a></li>  
  </ul>
  </div>
  <div class="span6" style="padding: 10px;"><a href="viewservices?filter=null&page=1">VIEW ALL SERVICES</a></div>
 </div>
 <?php
   if(isset($_POST['next'])){
     //billing variables
          $billtype = $_POST['billtype'];
          $billrecur = $_POST['billrecur'];
          $billpricepoint = $_POST['billpricepoint'];
          $pinoptin = $_POST['billpinoptin'];
          $billretry = $_POST['billretry'];
          $billfallback = $_POST['billfallback'];
       if(empty($_SESSION['service_id'])){
         header("Location: dashboard");
       }
      else{
        $service_id = $_SESSION['service_id'];        
        $query = mysql_query("UPDATE services SET billtype='$billtype',billrecur='$billrecur',billpricepoint='$billpricepoint',pinoptin='$pinoptin',retrydaily='$billretry',fallback='$billfallback' WHERE service_id = '$service_id'") or die(mysql_error());
        header("Location: mt");
        }        
   }
 ?>
 <div class="row-fluid content" style="height: 300px;">    
    <h5 style="padding-left: 10px;">Billing</h5>
    <div class="span5">
    <table>
    <tr>
     <td>Type:</td>
     <td><input type="text" name="billtype" style="" required="" placeholder="MT Billing or Direct Billing"/></td> 
    </tr>
    <tr>
     <td>Recurrence:</td>
     <td><input type="text" name="billrecur" style="" required="" placeholder="Daily/Weekly/Monthly"/></td> 
    </tr>
    <tr>
     <td>Pricepoint:</td>
     <td><input type="text" name="billpricepoint" style="" required="" placeholder=""/></td> 
    </tr>
    <tr>
     <td>PIN optin:</td><td>
                <select name="billpinoptin" style="">
                  <option value="" selected="">...select...</option>
                  <option value="1">YES</option>
                  <option value="0">NO</option>     
                </select> 
                </td> 
    </tr>
    </table>
    </div>
    <div class="span6"> <h6>Retry Policy</h6>
    <table>
    <tr>
     <td>Daily:</td><td>
                <select name="billretry" style="">
                  <option value="" selected="">...select...</option>
                  <option value="2">2x a day</option>
                  <option value="3">3x a day</option>
                  <option value="4">4x a day</option>      
                </select> </td> 
    </tr>
    <tr>
     <td>Fallback:</td><td>
                <select name="billfallback" style="">
                  <option value="" selected="">...select...</option>
                  <option value="1">YES</option>
                  <option value="0">NO</option>     
                </select> </td> 
    </tr>
    </table>
     <div style="float: right; margin-right: 50px;">
      <a href="keywords" class="btn">&lsaquo;&lsaquo; PREVIOUS</a>
     <input type="submit" value="NEXT" name="next" class="btn btn-primary"/>
    </div>
    </div>
    </div>
  </form>
</div> 
</div>
<?php include "footer.php";?>
</div>
</body>
</html>