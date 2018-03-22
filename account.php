<?php
ob_start();
 require_once "config.php";
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
    <script type="text/javascript" src="js/js.js"></script>			
	<title>My Account:: mLESSON</title>
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">MLESSON</div>
</div>
<div class="row-fluid">
<div class="span2" id="accordion"><?php include "header.php";?><h5 style="color: #0097AA; background-color: white; padding: 10px;">ACCOUNT</h5></div>
<div class="span3">
<h4 style="color: #0097AA;">Account Details</h4>
    <form action="" method="POST">
       <div class="span12 shadow" style="padding-left: 10px; margin-top: 10px;">
       <fieldset style="margin-top: 10px;">         
          <table style="">
          <tr><td></td><td>
           <?php  
              if($_POST['update']){ //
                $uname = protect($_POST['username']);
                $email = trim($_POST['email']);
                $password = $_POST['password'];
                $cid = $_SESSION['uid'];
                  $query = mysql_query("UPDATE users SET username='$uname',email='$email',password='$password' WHERE username='$cid'");
                  $_SESSION['uid'] = $uname;
                  $_SESSION['email'] = $email;
                  echo "<div style=\"color: green; padding-bottom:5px; padding-top: 10px;\">"."Information Updated"."</div>";
             }
       ?></td></tr>
            <tr>
            <td><label>Email Address</label></td>
            <td><input type="email" name="email" value="<?php echo $_SESSION['email']; ?>" required="" style="" placeholder="Enter Email"/></td>
           </tr>
           <tr>
            <td><label>Username</label></td>
            <td><input type="text" name="username" id="username" value="<?php echo $_SESSION['uid']; ?>" readonly="" style="" placeholder="Enter Preferred Username"/></td>
           </tr>
            <tr>
            <td><label>Password</label></td>
            <td><input type="password" name="password" id="password" value="<?php echo $_SESSION['passCode'];?>" required="" style="width: 80%;" placeholder="Choose a Password"/></td>
           </tr>
           <tr><td></td><td><input type="submit" name="update" class="btn btn-info" value="Update"/></td></tr>
          </table>
          </div>                 
          </fieldset>
</form> 
</div>
<?php if(isAdmin()){ ?>
    <div class="span4"><h4 style="color: #0097AA;">All Users</h4>
    <?php $details = list_users($cid);
         if(is_array($details)){ ?>
                <div class="row-fluid" style="background-color: #54C571; color: white; font-size: .9em;">
                    <div class="span1" style=""><b>Edit</b></div>
                    <div class="span3" style=""><b>Full Name</b></div>
                    <div class="span2"><strong>Username</strong></div>
                    <div class="span2"><b>Status</b></div>
                    <div class="span2"><b>Level</b></div>
                    <div class="span2"><b>Action</b></div>
                </div>
                <div style="height: 450px; overflow-y: auto; font-size: .8em;">
<?php     foreach($details as $value){
           if($i%2 == 0) $style="background-color:#eee;";
             else $style="background-color:#fff;";
              $id = $value['id'];?>
            <div class="row-fluid" style="<?php echo $style; ?>">
			<div class="span1" style=""><a href="delete.php?del=<?php echo $id;?>" title="Delete user"><i class="fa fa-trash"></i></a></div>
			<div class="span3" style=""><?php echo $value['fname'];?></div>
			<div class="span2" style=""><?php echo $value['username'];?></div>
            <div class="span2" style="">
               <?php 
                  if($value['level']=='1'){
                      echo "<span style=\"color: #728C00; \">Administrator</span>";
                     }
                  if($value['level']=='2'){
                      echo "<span style=\"color: #728C00; \">Content Manager</span>";
                     }
                  elseif($value['level']=='3'){
                     echo "<span style=\"color: #59E817; \">General</span>";
                     }
               ?>
            </div>
            <div class="span2" style="">
               <?php 
                  if($value['status']=='0'){
                     echo "<span style=\"color: red; \">Inactive</span>";
                     }
                  elseif($value['status']=='1'){
                     echo "<span style=\"color: #8FC412; \">Active</span>";
                     }
                  else echo "<span style=\"color: black; \">Demo</span>";
               ?>
            </div>
            <div class="span2" style=""><?php if($value['status']=='0'){ ?>
                                        <a href="delete.php?a=<?php echo $id;?>"><span style="color: red;">Activate</span></a>
                                        <?php }else { ?>
                                        <a href="delete.php?dea_user=<?php echo $id;?>"><span style="color: #8FC412;">Deactivate</span></a>
                                        <?php } ?>
            </div>
    </div>     
    <?php	$i++; } ?>
    </div>
    <?php } else echo "No Users at the moment.."; ?>         
    </div> 
    <div class="span3"><h4 style="color: #0097AA;">Add User</h4>
    <form action="" method="POST" style="font-size:.9em;">
    <?php  
     if(isset($_GET['add'])){
        echo "<div style=\"color: green; padding-bottom:5px; padding-top: 10px;\">"."User Created"."</div>";
        } 
        if($_POST['register']){ //when creating a user
        $fname = protect($_POST['fname']);
        $user_email = trim($_POST['email']);
        $userid = trim($_POST['username']);
        $password = $_POST['password'];
        $user_phone = $_POST['phone'];
        $level = $_POST['type'];
        if(check_db_username($userid) == true){
        echo "<div style=\"color: red; padding-top: 10px; padding-bottom: 5px;\">"."Username already registered!"."</div>";
               }
        elseif(check_db_email($user_email) == true){
        echo "<div style=\"color: red; padding-top: 10px; padding-bottom: 5px;\">"."Email in use.Please choose another!"."</div>";
              }
        else{
          $query = mysql_query("INSERT INTO users VALUES('','$fname','$userid','$email','$password','$phone','','1','$level')") or die(mysql_error());
           header("Location: account?add");
             }      
         }
       ?>
       <fieldset style="margin-top: 10px;">
          <table style="" align="center">
           <tr>
            <td><label>Full Name</label></td>
            <td style="padding-top: 10px;"><input type="text" name="fname" required="" value="<?php echo isset($fname)?$fname:"";?>" class="text-input" style="" placeholder="Enter Full Name"/></td>
           </tr>
           <tr>
            <td><label>Email Address</label></td>
            <td><input type="email" name="email" value="<?php echo isset($user_email)?$user_email:"";?>" required="" style="" placeholder="Enter Email"/></td>
           </tr>
            <tr>
            <td><label>Phone</label></td>
            <td><input type="telephone" name="phone" value="<?php echo isset($user_phone)?$user_phone:"";?>" required="" style="" placeholder="Enter Phone"/></td>
           </tr>
           <tr>
            <td><label>Username</label></td>
            <td><input type="text" name="username" id="username" value="<?php echo isset($userid)?$userid:"";?>" required="" style="" placeholder="Enter Preferred Username"/></td>
           </tr>
            <tr>
            <td><label>Password</label></td>
            <td><input type="password" name="password" id="password" value="" required="" placeholder="Choose a Password"/></td>
           </tr>
            <tr>
            <td><label class="form-label">User Type</label></td>
            <td><select name="type" style="width: 100%;">
                            <option value="" selected="">..select..</option>
                            <option value="1">Administrator</option>
                            <option value="2">Content Manager</option>
                            <option value="3">General</option>
                            <option value="4">Subscription Manager</option>
                </select></td>
           </tr>
           <tr><td></td>
               <td><input type="submit" name="register" class="btn btn-info" value="Sign Up"/></td></tr>
          </table>        
          </fieldset>
       </form> 
    </div>
<?php } ?>
</div>
<?php include "footer.php";?>
</div>
</body>
</html>