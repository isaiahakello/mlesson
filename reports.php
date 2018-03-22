<?php
ob_start();
 require_once "config.php";
 require_once "timeout.php";
 require_once "session.php";
 require_once "functions.php";
 confirm_logged_in();
 $cid = $_SESSION['uid'];
 $filter = $_GET['filter'];
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
    <link rel="stylesheet" type="text/css" href="css/chosen.css"/>
    <script src='js/jquery-1.9.1.js'></script>
    <script src='js/bootstrap.min.js'></script>
    <script src='js/jquery-ui.js'></script>
	<script type="text/javascript" src="js/chosen.jquery.js"></script>
    <script type="text/javascript" src="js/index.js"></script>
    <script type="text/javascript" src="js/js.js"></script>		
	<title>Outgoing Calls: Just2MinutesAday</title>
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">JUST 2 MINUTES A DAY</div>
<div class="span4"style="padding: 10px;color: white; text-align: right;">
<ul class="navigation">
<li><a href="message" style="background-color: #00FF00; cursor: pointer; padding: 5px; color: black; width: 70px;">Calls</a></li>
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
<div class="span10">
<div class="row-fluid">
 <div class="span8 offset1">
  <form action="" method="GET" >
     <div class="input-prepend" style="margin-top: 10px;"><span class="add-on" style="color: black;">Filter By</span>
        <select name="filter" class="select">
            <option value="to" selected="">To Number</option>
            <option value="date">Date Sent</option>
            <option value="delivery_status">Status</option>
         </select>
    <input type="text" name="new_value" value="" placeholder="" id="new_value"/>
    <input type="submit" name="filtering" value="Filter" style="margin-top: 3px;margin-left: 5px;" />
    </div>
   </form>
   </div>
</div>
<div class="row-fluid">
<?php 
    include "pagination.php";
    $per_page = 15;
    $page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
  if(isset($_GET['filter'])){
       $filter_term = $_GET['filter'];
       $value = $_GET['new_value'];
        switch($filter_term){
            case 'to':
            if(empty($value)) break;
             $phone = trim(preg_replace("/[^0-9]/","",$value));
             $query = mysql_query("SELECT COUNT(*) AS count FROM sms_history WHERE receiver LIKE '%$phone%'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM sms_history WHERE receiver LIKE '%$phone%' ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
            case 'date':
            if(empty($value)) break;
             $date = $value;
             $query = mysql_query("SELECT COUNT(*) AS count FROM sms_history WHERE DATE(reqlogtime) ='$date'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM sms_history WHERE DATE(reqlogtime) ='$date' ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
            case 'delivery_status':
             if(empty($value)) break;
             $status = strtolower($value);
             $status = ucfirst($status);
             $query = mysql_query("SELECT COUNT(*) AS count FROM sms_history WHERE report ='$status' AND userid = '$cid'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM sms_history WHERE report ='$status' AND userid = '$cid' ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
            case 'null':
             $query = mysql_query("SELECT COUNT(*) AS count FROM sms_history");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM sms_history ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
             }
          }
  if(is_resource($que)){
    if(mysql_num_rows($que)!=0){ ?>
<div class="row-fluid shadow" style="font-size: 1.1em; padding-left: 5px;">
    <div class="span2" style=""><b>TO</b></div>
    <div class="span3" style=""><b>LECTURE</b></div>
    <div class="span3"><b>DATE SENT</b></div>
    <div class="span2"><b>STATUS</b></div>
    <div class="span1"><b>PLAY</b></div>
</div>
<div class="row-fluid" style="height: 450px; overflow-y: auto; font-size: 1em; padding-left: 3px;">
<?php     while($value=mysql_fetch_array($que)){
           if($i%2 == 0) $style="background-color:#eee;";
             else $style="background-color:#fff;";
              $id = $value['id'];
              $file = $value['file'];
              ?>
<div class="row-fluid" style="<?php echo $style; ?>">
			<div class="span2" style="padding: 3px;"><?php echo $value['receiver'];?></div>
			<div class="span3" style="padding: 3px 2px 3px 0px;"><?php echo $file;?></div>
            <div class="span3" style="padding: 3px 3px 3px 0px;"><?php echo $value['reqlogtime'];?></div>
            <div class="span2" style="padding: 3px 3px 3px 0px;"><?php echo $value['report'];?></div>
            <div class="span1" style="padding: 3px 3px 3px 0px;"><a href="<?php echo $file;?>" id="play"><i class="fa fa-play"></i></a></div>
</div>
<?php    $i++; } ?>
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