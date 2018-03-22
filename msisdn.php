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
	<title>Help Desk:: MSISDN</title>
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">MVAS SYSTEM DESIGN</div>
</div>
<div class="row-fluid">
<div class="span2" id="accordion"><?php include "header.php";?></div>
<div class="span10">
<h4 style="color: #0097AA; padding: 5px 5px 5px 2px; font-family:Arial, sans-serif;border-bottom: 1px solid rgb(204, 204, 204);">Help Desk</h4>
  <ul class="navigation"> 
       <li><a href="users?filter=null&page=1">All Users</a></li>  
       <li style="background-color:#00FF00;color: white; padding: 7px;"><a href="">MSISDN Report</a></li>       
  </ul>
  <div class="row-fluid">
  <form action="" method="GET" style="width: 100%; margin-top: 10px;">
    <input type="text" name="msisdn" value="" style="" placeholder="INSERT MSISDN"/>
    <input type="submit" name="filtering" value="OK" style="margin-left: 5px; margin-top: -10px;" />
    <a href="rpts_download.php?filter=<?php echo $filter;?>&value=<?php echo $value_term;?>" style="color: white;margin-left: 50px; margin-top: -10px;" class="btn btn-success">EXPORT CSV</a>    
   </form>
   <div style="margin-top: -15px; padding-bottom: 10px; border-bottom: 2px solid black;">
    <ul class="navigation" style="margin-top: 0px;"> 
       <li><a href="#" class="btn">General Info</a></li>  
       <li><a href="#" class="btn">Events</a></li> 
       <li><a href="#" class="btn">Bill Events</a></li>
       <li><a href="#" class="btn">SMS Sent</a></li>      
   </ul>
  </div>
</div>
<?php 
    include "pagination.php";
    $per_page = 15;
    $page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
  if(isset($_GET['filter'])){
       $filter_term = $_GET['filter'];
       $value = $_GET['new_value'];
        switch($filter_term){
            case 'username':
            if(empty($value)) break;
             $username = $value;
             $que = mysql_query("SELECT * FROM users WHERE level != 2 AND username = '$username'");
            break;
            case 'accstatus':
            if(empty($value)) break;
             $status = strtolower($value);
             if($status =='active') $status = '1'; elseif($status =='inactive') $status = '0';
             $query = mysql_query("SELECT COUNT(*) AS count FROM users WHERE level != 2 AND status = '$status'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM users WHERE level != 2 AND status = '$status' ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
            case 'credit':
            if(empty($value)) break;
             $credit = $value;
             $query = mysql_query("SELECT COUNT(*) AS count FROM users WHERE level != 2 AND balance < '$credit'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM users WHERE level != 2 AND balance < '$credit' ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
            case 'null':
             $query = mysql_query("SELECT COUNT(*) AS count FROM users WHERE level != 2");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM users WHERE level != 2 ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
             }
          }
  if(is_resource($que)){
    if(mysql_num_rows($que)!=0){ ?>
<div class="row-fluid">
    <div class="span1" style=""><b>USERID</b></div>
    <div class="span2"><strong>MSISDN</strong></div>
    <div class="span1"><b>OPERATOR</b></div>
    <div class="span1"><b>SERVICE</b></div>
    <div class="span1"><b>OPTIN DATE</b></div>
    <div class="span1"><b>OPTOUT DATE</b></div>
    <div class="span1"><b>NEXT BILL</b></div>
    <div class="span1"><b>OPTIN METHOD</b></div>
    <div class="span1"><b>OPTIN ID/KEYWORD</b></div>
    <div class="span1"><b>STATUS</b></div>
    <div class="span1"><b>Unsubscribe</b></div>
</div>
<div style="height: 290px; overflow-y: auto; font-size: 1.0em;">
<?php     while($value=mysql_fetch_array($que)){
           if($i%2 == 0) $style="background-color:#eee;";
             else $style="background-color:#fff;";
              $id = $value['id'];?>
<div class="row-fluid" style="<?php echo $style; ?>">
			<div class="span1" style="padding-left: 5px;"></div>
			<div class="span2" style=""></div>
			<div class="span2" style=""></div>
  	        <div class="span2" style=""></div>
          	<div class="span2" style=""></div>
          	<div class="span1" style=""></div>
          	<div class="span1" style=""></div>
            <div class="span1" style="">
               <?php 
                  if($value['status']=='0'){
                     //echo "<span style=\"color: red; \">Inactive</span>";
                     }
                  elseif($value['status']=='1'){
                     //echo "<span style=\"color: #8FC412; \">Active</span>";
                     }
                  else //echo "<span style=\"color: black; \">Demo</span>";
               ?>
            </div>
            
</div>
<?php	$i++; } ?>
</div>
<div class="shadows">
<?php
	if($pagination->total_pages() > 1) {
		if($pagination->has_previous_page()) {
        $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $page = $pagination->previous_page(); ?>
    	<a href="<?php echo change_page($url,$page);?>">
        <?php 
        echo "&laquo; Previous</a> "; 
       }
		echo "...Page ".++$page."...";
		if($pagination->has_next_page()){ 
        $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $page = $pagination->next_page(); ?>
	   <a href="<?php echo change_page($url,$page);?>">
		<?php 
		echo "Next &raquo;</a>"; 
            }		
    	}
?> 
<div style="float: right; margin-right: 0px; font-size: 0.8em; padding-right: 5px; padding-bottom: 15px"><b>RECORDS : <span style="color: black;"><?php echo $total_count;?></span></b></div>
</div>
<?php } else { echo "Nothing found!"; } 
  }
  else { echo "Nothing found!"; }
?>
</div> 
</div>
<?php include "footer.php";?>
</div>
</body>
</html>