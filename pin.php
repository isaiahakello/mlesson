<?php
ob_start();
 require_once "config.php";
 require_once "functions.php";
 $trx = $_GET['trx'];
 $phone = $_GET['msisdn'];
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
    <script type="text/javascript" src="js/js.js"></script>			
	<title>PIN Confirmation:: Mvas System</title>
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">MVAS SYSTEM DESIGN</div>
</div>
<div class="row-fluid">
<div class="span2" id="accordion"><?php include "header.php";?></div>
<div class="span8">
<h4 style="color: #0097AA; padding: 5px 5px 5px 2px; font-family:Arial, sans-serif;border-bottom: 1px solid rgb(204, 204, 204);">PIN Confirmation Page</h4>
    <form action="" method="POST" style="height: 350px;">
       <div class="span12 shadow" style="padding-left: 10px; margin-top: 10px;">
       <span>Your PIN has been sent to <b><?php echo $phone;?></b>. Please enter it in the field below.</span>
       <fieldset style="margin-top: 10px;">         
          <table style="" align="center">
          <tr><td></td><td>
           <?php  
              if($_POST['submit']){ 
                $apikey = "52dd73a6801eaf19691d731aff788446";
                $pin = protect($_POST['pin']);
                $price = trim($_POST['price']);
                $data = array("apikey" => $apikey, "pin" => $pin, "confirmed" => "No");                                                                    
                $data_string = json_encode($data);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://api.centili.com/api/payment/1_3/transaction/$trx");                                                                                                                                                                                                                                                                                  
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                  
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                    'Content-Type: application/json',                                                                                
                    'Content-Length: '.strlen($data_string))                                                                       
                );                                                                                                                   
                                                                                                                                     
                $result = json_decode(curl_exec($ch),true);
                curl_close($ch);
                $status = $result['status'];               
                if($status == 'ACCEPTED'){
                   $query = mysql_query("UPDATE subscribers SET status = '1' WHERE number = '$phone'"); 
                     echo "<div style=\"color: green; padding-bottom:5px; padding-top: 10px;\">"."Subscription successfully completed"."</div>";
                    }                
                else{
                  $error = $result['errorMessage'];
                  echo "<div style=\"color: red; padding-bottom:5px; padding-top: 10px;\">"."Error:  $error. Please try again.</div>";
                 
                }
             }
       ?></td></tr>
           <tr>
            <td><label>PIN</label></td>
            <td><input type="number" name="pin" value="" required="" style="" placeholder="Enter Received PIN"/></td>
           </tr>
           <tr><td></td><td><input type="submit" name="submit" class="btn btn-info" value="Submit"/></td></tr>
          </table>
          </div>                 
          </fieldset>
</form> 
</div> 
</div>
<?php include "footer.php";?>
</div>
</body>
</html>