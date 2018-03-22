<?php
ob_start();
 require_once "config.php";
 require_once "session.php";
 require_once "functions.php";
 canView();
 $details = list_subjects();
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
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src='js/bootstrap.min.js'></script>
    <script type="text/javascript" src="js/chosen.jquery.js"></script>
     <script type="text/javascript" src="js/js.js"></script>		
	<title>Add Customer:: mLESSON</title>     
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">mLESSON</div>
</div>
<div class="row-fluid">
<div class="span2" id="accordion"><?php include "header.php";?></div>
<div class="span8" style="">
<h5 style="color: #0097AA; border-bottom: 1px solid rgb(204, 204, 204);"><span style="color: orange;">ADD CUSTOMER </span></h5>
 <form action="" method="POST" class="">
 <?php
   if($_POST['finish']){
      $number = $_POST['number'];
      $name = $_POST['name'];
      $query = mysql_query("INSERT INTO customers (name,number) VALUES ('$name','$number')") or die(mysql_error());    
       echo "<div style=\"color: green; padding-bottom: 5px;\">"."Created ..."."</div>";                                                        
 }
 ?>
  <table>
     <tr>
     <td>Full Name:</td>
      <td><input type="text" name="name" style="" required="" value="<?php echo $name;?>"/></td> 
     </tr>
     <tr>
     <td>Number:</td>
      <td><input type="telephone" name="number" style="" required="" value="<?php echo $number;?>"/></td> 
     </tr>
    <tr><td></td>
      <td> <input type="submit" value="SUBMIT" name="finish" class="btn btn-primary"/></td></tr>
    </table>
  </form>
</div> 
</div>
<?php include "footer.php";?>
</div>
</body>
</html>