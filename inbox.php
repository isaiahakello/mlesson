<?php
ob_start();
 require_once "config.php";
 require_once "session.php";
 require_once "functions.php";
 confirm_logged_in();
 $filter_term = $_GET['filter'];
 $value = $_GET['new_value'];
?>
<!DOCTYPE HTML>
    <html>
    <head>
	<meta http-equiv='content-type' content='text/html'/>
    <meta name='description' content=''/>
    <meta name='keywords' content=''/>
    <link rel='shortcut icon' href='images/favicon.ico'/>
    <link href='css/bootstrap.css' rel='stylesheet' type='text/css'/>
    <link href='css/bootstrap-responsive.css' rel='stylesheet' type='text/css'/>
    <link rel='stylesheet' href='css/main.css'/>
    <link href='css/smoothness/jquery-ui-1.10.3.custom.css' rel='stylesheet'/>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"/>
    <script src='js/jquery-1.9.1.js'></script>
    <script src='js/bootstrap.min.js'></script>
    <script src='js/jquery-ui.js'></script>
    <script type="text/javascript" src="js/reports.js"></script>			
	<title>Inbox:: MLESSON</title>
</head>
<body>
<div class="container-fluid">
<div class="row-fluid header">
<div class="span4" style="font-size: 2em;"><img src="images/logo.png" height="30"/></div>
<div class="span8"style="padding: 10px;color: white; text-align: right;">
<ul class="navigation">
<li><i class="fa fa-home" style="color: white;"></i> <a href="dashboard">Home</a></li>       
<li><i class="fa fa-users" style="color: white;"></i> <a href="subscribers?filter=null&page=1">Subscriptions</a></li>
<li><i class="fa fa-file" style="color: white;"></i> <a href="interaction?filter=null&page=1">Content</a></li>   
<li><i class="fa fa-usd" style="color: white;"></i> <a href="interaction?filter=null&page=1">Revenue</a></li>   
<li><i class="fa fa-comments" style="color: white;"></i> <a href="inbox?filter=null&page=1">Messenger</a></li>
<li><i class="fa fa-area-chart" style="color: white;"></i> <a href="inbox?filter=null&page=1" class="active">Reports</a></li>
<li><i class="fa fa-lock" style="color: white;"></i> <a href="account" style="">Account</a></li>
<li class="dropdown" style="margin-right: 30px; color: black;">
   <span data-toggle="dropdown" class="dropdown-toggle" style="cursor: pointer;"><i class="fa fa-user" style="color: white;"></i> <?php echo $_SESSION['uid'];?> <i class="fa fa-caret-down"></i></span>
     <ul class="dropdown-menu" style="padding: 5px; margin-left: -110px;">
      <a href="logout.php" style="color: #D1D0CE;">Logout</a>
     </ul>           
  </li>
</ul>
</div>
</div>
<div class="row-fluid main-content">
<div class="span10 offset1 login_content">
<div class="eh">INBOX</div> 
 <div class="row-fluid">
<div class="span9 offset1">
 <form action="" method="GET" class="login_d">
    <div class="input-prepend"><span class="add-on" style="color: black;">Filter By</span>
        <select name="filter" class="chosen-select" style="width: 40%;">
            <option value="" selected="">..select..</option>
            <option value="number">Number</option>
            <option value="date">Date</option>
        </select>
    <input type="text" name="new_value" value="" placeholder="" id="new_value"/>
    <input type="submit" name="filtering" value="FILTER" style="margin-left: 5px;" />
    <a href="resp_download.php?filter=<?php echo $filter_term;?>&value=<?php echo $value;?>" style="margin-left: 20px; margin-top: -5px;" class="btn btn-success">EXPORT CSV</a>
    </div>   
   </form>   
   </div>
</div>
<div style="margin-left: 70px; margin-right: 70px;">
<?php 
    include "pagination.php";
    $per_page = 20;
    $page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
  if(isset($_GET['filter'])){
       $filter_term = $_GET['filter'];
       $value = $_GET['new_value'];
        switch($filter_term){
            case 'number':
            if(empty($value)) break;
             $query = mysql_query("SELECT COUNT(*) AS count FROM inbox WHERE from_ LIKE '$value%'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM inbox WHERE from_ LIKE '$value%' ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
            case 'date':
            if(empty($value)) break;
             $date = $value;
             $query = mysql_query("SELECT COUNT(*) AS count FROM inbox WHERE DATE(datesent) = '$date'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM inbox WHERE DATE(datesent) = '$date' ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
            case 'null':
             $query = mysql_query("SELECT COUNT(*) AS count FROM inbox");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM inbox ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
             }
          }
  if(is_resource($que)){
    if(mysql_num_rows($que)!=0){ ?>
<div class="row-fluid" style="background-color: #54C571; color: white; font-size: .9em;">
    <div class="span2" style="padding-left: 5px;">From</div>
    <div class="span5" style="">Message</div>
    <div class="span2">Date</div>
    <div class="span2">Action</div>
</div>
<div style="height: 450px; overflow-y: auto;">
<?php     while($value=mysql_fetch_array($que)){
           if($i%2 == 0) $style="background-color:#eee;";
             else $style="background-color:#fff;";
              $id = $value['id'];
              $number = $value['from_'];
              $message = $value['message'];
              $time = $value['datesent'];?>
<div class="row-fluid" style="<?php echo $style; ?> font-size: .9em;">
			<div class="span2" style="padding-left: 5px;"><?php echo $number;?></div>
            <div class="span5" style=""><?php echo $message;?></div>
            <div class="span2" style=""><?php echo $time;?></div>
            <div class="span2" style=""><a href="reply?phone=<?php echo $number;?>" title="reply"><i class="fa fa-reply" ></i></a></div>
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
</div>
<?php include "footer.php";?>
</div>
</body>
</html>