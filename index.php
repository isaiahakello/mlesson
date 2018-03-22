<?php
ob_start();
session_start();
require_once "config.php";
 require_once "session.php";
 require_once "functions.php";
 if(logged_in()){
    header("Location: interaction?filter=null&page=1");
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
    <link href='css/smoothness/jquery-ui-1.10.3.custom.css' rel='stylesheet'/>
    <script src='js/jquery-1.9.1.js'></script>
    <script src='js/bootstrap.min.js'></script>
    <script src='js/jquery-ui.js'></script>		
	<title>Home:: M-Lesson</title>
</head>
<body>
<div class="container-fluid">
<div class="row-fluid header">
<div class="span8" style="padding: 5px;"> M-Lesson</div>
</div>
<div class="row-fluid" style="background: url('images/bg.jpg');  height: 400px;">  
  <div class="span5 offset4 login_content" style="margin-top: 80px;">
     <div class="eh">Login to M-Lesson</div>
      <form action="" method="POST" class="login_d">
      <?php  
         if($_POST['login']){  //during log in
         $username	= trim($_POST['username']);
         $password	= $_POST['password']; 
         if(empty($username) || empty($password)){
            echo "<div style='color: red;'>Please fill in all fields!</div>";
         }
         else{
         $details = show_user($username,$password);
         if(is_array($details)){
            $lastlogin = date("Y-m-d H:i:s",time());
            foreach($details as $value){
             $_SESSION['fullname'] = $value['fname'];
             $_SESSION['email'] = $value['email'];
             $_SESSION['uid'] = $value['username'];
             $_SESSION['user_level'] = $value['level'];           
             $_SESSION['phone'] = $value['phone'];
             $query = mysql_query("UPDATE users SET lastlogin = '$lastlogin' WHERE username = '$username'");
             if($value['status'] !='0'){ //validated user log in
                    header("Location: dashboard");
                    }
              else{ //inactive user login
                 echo "<div style='color: red;'>Your account is inactive!</div>";
                  }
                 }
              // create the timestamp for timeout session
              $_SESSION['timestamp']=time();   	   
                 }
             else{
                echo "<div style='color: red;'>Unknown username or password!</div>";
             } 
          }
       }
?>
     <div class="form-group">
        <input type="text" class="form-control" name="username" placeholder="Username" required="" value="<?php echo isset($username)?$username:"";?>" autocomplete="off"/>
    </div>
   <div class="form-group">
        <input type="password" class="form-control" name="password" placeholder="Password" required="" value=""/>
    </div>
    <div class="form-group">                       
        <a class="reset_pass" href="forgot">Lost your password?</a>
          <input type="submit" class="btn btn-success" name="login" value="Log in" />
    </div>
     </form>
  </div>
</div>  	
<?php include "footer.php";?>
</div>
</body>
</html>
