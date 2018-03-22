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
	<title>All Members:: Just2MinutesAday</title>
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">JUST 2 MINUTES A DAY</div>
<div class="span4"style="padding: 10px;color: white; text-align: right;">
<ul class="navigation">
<li><a href="allcontacts" style="background-color: #00FF00; cursor: pointer; padding: 5px; color: black; width: 70px;">All Members</a></li>
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
<div class="span8">
<div style="background-color: #98AFC7; padding: 10px; color: white; margin-top: 10px; margin-bottom: 3px;"> All Members</div>
     <div style="height: 500px; overflow-y: auto;">
     <?php $clgroup = list_groups();
          if(is_array($clgroup)){  ?>
          <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Group Name</th>
                                        <th>Members</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php                                        
                                         foreach($clgroup as $value){
                                            $gid=$value['id'];
                                            $gname=$value['group_'];
                                            $count=$value['count'];
                                        ?>
                                    <tr>
                                        <td><?php echo $gname; ?></td>
                                        <td><?php echo $count; ?></td>
                                        <td  class="center-align">
                                            <a href="groupcontacts?filter=null&group=<?php echo $gname; ?>&page=1"class="btn btn-success" title="view members" style="color: white;">View Contacts</a>
                                            <a href="addcontact?group=<?php echo $gname; ?>"title="Add a single member" class="btn btn-success">Add new Member</a>
                                            <a href="addtogroup?group=<?php echo $gname; ?>"title="Add several members at a time"class="btn btn-success">Quick Multi Member</a>
                                            <a href="delete.php?grpid=<?php echo $gname; ?>"class="btn btn-danger" style="color: white;" title="delete members">Delete All Members</a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                               <?php } else {  echo "You do have any contacts yet!"; } ?>  
                            </div>
</div> 
</div>
<?php include "footer.php";?>
</div>
</body>
</html>