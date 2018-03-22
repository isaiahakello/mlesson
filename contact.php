<?php
ob_start();
 require_once "config.php";
 require_once "functions.php";
 require_once "session.php";
       if($_POST['register']){ //when creating a user
        $fname = protect($_POST['fname']);
        $email = trim($_POST['email']);
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        if(empty($fname) || empty($username) || empty($password) || empty($email)) {
          $error = "Please fill in all fields!";
        }
        elseif(check_db_username($username) == true){
          $error = "Username already in use.Please choose another one!";
        }
        elseif(check_db_email($email) == true){
          $error = "Email already in use.Please choose another one!";
        }
        else{
          $query = mysql_query("INSERT INTO users VALUES('','$fname','$username','$email','$password','1')");
          $error = "Account Created.";
        }
     }
     if(isset($_POST['change_password'])){
        $username = $_SESSION['accessibility'];
        $pass = $_POST['pass'];
        $query = mysql_query("UPDATE users SET password='$pass' WHERE username = '$username'");
        header("Location: index.php?pass");
     }
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
    <link rel="stylesheet" type="text/css" href="css/slick.css"/>
    <link rel="stylesheet" type="text/css" href="css/slick-theme.css"/>
    <link href='css/smoothness/jquery-ui-1.10.3.custom.css' rel='stylesheet'/>
    <link href='http://fonts.googleapis.com/css?family=Neucha' rel='stylesheet' type='text/css'/>
    <script src='js/jquery-1.9.1.js'></script>
    <script src='js/bootstrap.min.js'></script>
    <script src='js/jquery-ui.js'></script>
    <script type="text/javascript" src="js/slick.min.js"></script>
    <script type="text/javascript" src="js/index.js"></script>
				
	<title>Home: SMS Application</title>
</head>

<body>
<div class="container-fluid all">
<div class="row-fluid header">
<?php if(logged_in()){ ?>
<div class="span8" style="padding: 10px;"><span style="padding-right: 5px; border-right: 2px solid white;">Welcome, <?php echo $_SESSION['uid'];?></span>
                                          <span style="padding: 10px;">Credit Balance: <?php echo $_SESSION['credit_balance'];?></span></div>
<?php } ?>
<div style="float: right; padding: 10px; margin-right: 40px; color: white;">
<ul class="navigation">   
<?php if(logged_in()){ ?>
<li></li>
<li class="dropdown" style="margin-right: 30px;">
   <span data-toggle="dropdown" class="dropdown-toggle" style="cursor: pointer;color: black;">My Account</span>
     <ul class="dropdown-menu" style="padding: 10px; margin-left: -150px; width: 250px;">
     <?php include "templates/account.php";?>
     </ul>           
  </li>
<?php } else { ?>
<li class="dropdown" style="">
   <a href="./home" style="background-color: #00FF00; cursor: pointer; padding: 5px; color: black; width: 70px;">Login</a>              
</li>
<li class="dropdown">
   <span data-toggle="dropdown" class="dropdown-toggle" style="background-color: #00FF00; cursor: pointer; padding: 5px; color: black; width: 70px;">Sign Up</span>
     <ul class="dropdown-menu" style="padding: 10px; margin-left: -100px;">
     <?php include "templates/create_user.php";?>
     </ul>           
</li>
<?php } ?>
  </ul>
</div>
</div>
<div class="row-fluid">
  <div class="span6 slick-carousel">
     <div class="slic">
       <img src="images/bannera.jpg"/>
     </div>
    <div class="slic1">
       <img src="images/bannerb.jpg"/>
     </div>
    <div class="slic2" style="color: white;">
      <img src="images/bannerc.jpg"/>
     </div>
  </div>
  <div class="span6" id="contactForm">
  <p style="padding-top: 5px;">
  Send us your requests.Comments are welcome too!
  </p>
    <form method="post" action="contact.php">
<?php 
if($_POST['contact']){
    $name = htmlentities($_POST['name']);
    $email = htmlentities($_POST['email']);
    $comments = $_POST['comments'];
    $to = "ivan@unidatasolutions.com";
    $headers = "From: ".$email."\r\n";
    $subject = "Query from SMS Application";
    $comments = nl2br($comments);
    if(mail($to,$subject,$comments,$headers)){
       echo "<div class='success'>Thanks for the query,we'll keep in touch!</div>";
    }
    else{
       echo "<div class='error'>Your message could not be sent!</div>";
    }
} 
?>
<fieldset>
        <input name="name" type="text" class="text" placeholder="Name e.g. joe doe" required=""/>
        <input name="email" type="email" class="text" placeholder="Email Address: someone@somewhere.com" required=""/>
        <textarea name="comments" class="comments" placeholder="Query/Comments:" required=""></textarea><br />
        <input type="submit" name="contact" class="btn btn-info" value="Send" tabindex="160" />
    </fieldset>
    </form>
</div>
</div>
<div class="row-fluid" style="background-color: #FAFAFC;">
<div class="span4 offset4 links">
<ul class="navigation">
          <?php if(!logged_in()){ ?>
          <li><a href="./home">Home</a></li>
          <?php }  else { ?>
          <li><a href="./dashboard">Dashboard</a></li>
          <?php } ?>
          <li><a href="./pricing">Pricing</a></li>
          <li><a href="./contacts" class="active">Contact</a></li>          
</ul>
</div>
</div>  	
<?php include "footer.php";?>
</div>
</body>
</html>