<?php
ob_start();
error_reporting(0);
ini_set('display_errors', 0);
ini_set("date.timezone", "Africa/Nairobi");
 require_once "config.php";
 require_once "timeout.php";
 require_once "session.php";
 require_once "functions.php";
isSubscriberManager();
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
    <link rel="stylesheet" type="text/css" href="css/chosen.css"/>
    <script src='js/jquery-1.9.1.js'></script>
    <script src='js/bootstrap.min.js'></script>
    <script src='js/jquery-ui.js'></script>
    <script type="text/javascript" src="js/reports.js"></script>			
  <title>Subscribers:: M-Lesson</title>
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span4" style="font-size: 2em;"><img src="images/logo.png" height="30"/></div>
<div class="span8"style="padding: 10px;color: white; text-align: right;">
<ul class="navigation">
<li><i class="fa fa-home" style="color: green;"></i> <a href="dashboard">Home</a></li>       
<li><i class="fa fa-users" style="color: green;"></i> <a href="subscribers?filter=null&page=1" class="active">Subscriptions</a></li>
<li><i class="fa fa-file" style="color: green;"></i> <a href="interaction?filter=null&page=1">Content</a></li>   
<li><i class="fa fa-usd" style="color: green;"></i> <a href="interaction?filter=null&page=1">Revenue</a></li>   
<li><i class="fa fa-comments" style="color: green;"></i> <a href="inbox?filter=null&page=1">Messenger</a></li>
<li><i class="fa fa-area-chart" style="color: green;"></i> <a href="inbox?filter=null&page=1">Reports</a></li>
<li><i class="fa fa-lock" style="color: green;"></i> <a href="account" style="">Account</a></li>
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
<div class="span12 login_content">
<div class="row-fluid">
  <div class="span8">
   <form action="" method="GET" class="login_d">
    <div class="input-prepend"><span class="add-on" style="color: green;">Filter By</span>
       <select name="filter" style="width: 27%;">
            <option value="" selected="">All</option>
            <option value="number">Number</option>
            <option value="status">Status</option>
       </select>
    <input type="text" name="new_value" value="" placeholder="" id="new_value" style="width: 70%;"/>
    <input type="submit" name="filtering" value="Filter" style="margin-top: 3px; margin-left: 5px;" />
    </div>
  </form>
  </div>
  <div class="span4" style="margin-top: 10px;">
  <a href="rpts_download.php?filter=<?php echo $filter_term;?>&value=<?php echo $value;?>" class="btn btn-success" style="font-size: .8em;">EXPORT CSV</a>
  <a href="addsubscriber" class="btn btn-info" style="font-size: .9em;">Add Subscriber</a>
  </div>
</div>
<div class="row-fluid">
<?php 
    include "pagination.php";
    $per_page = 50;
    $page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
  if(isset($_GET['filter'])){  
        $value = $_GET['new_value'];
        switch($_GET['filter']){           
            case 'number':
            if(empty($value)) break;           
             $number = trim(preg_replace("/[^0-9]/","",$value));
             $quer = mysql_query("SELECT COUNT(*) AS active FROM subscribers WHERE number LIKE '%$number%' AND status = 1");
             $row = mysql_fetch_array($quer);
             $active = $row['active'];
             $que = mysql_query("SELECT COUNT(*) AS cancelled FROM subscribers WHERE number LIKE '%$number%' AND status = 3");
             $row = mysql_fetch_array($que);
             $cancelled = $row['cancelled'];
             $questions = list_nquestions($number);
             $responses = list_nresponses($number);
             $qu = mysql_query("SELECT COUNT(*) AS incomplete FROM subscribers WHERE number LIKE '%$number%' AND status = 2");
             $row = mysql_fetch_array($qu);
             $incomplete = $row['incomplete'];
             $query = mysql_query("SELECT COUNT(*) AS count FROM subscribers WHERE number LIKE '%$number%'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM subscribers WHERE number LIKE '%$number%' ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
            case 'status':
            if(empty($value)) break;
             $status = $value;
             $active = 'NA';
             $cancelled = 'NA';
             $incomplete = 'NA';
             $questions = '0';
             $query = mysql_query("SELECT COUNT(*) AS count FROM subscribers WHERE status='$status'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM subscribers WHERE status='$status' ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
            case 'null':
             $quer = mysql_query("SELECT COUNT(*) AS active FROM subscribers WHERE status = 1");
             $row = mysql_fetch_array($quer);
             $active = $row['active'];
             $que = mysql_query("SELECT COUNT(*) AS cancelled FROM subscribers WHERE status = 3");
             $row = mysql_fetch_array($que);
             $cancelled = $row['cancelled'];
             $questions = list_questions();
             $responses = list_responses();
             $qu = mysql_query("SELECT COUNT(*) AS incomplete FROM subscribers WHERE status = 2");
             $row = mysql_fetch_array($qu);
             $incomplete = $row['incomplete'];
             $query = @mysql_query("SELECT COUNT(*) AS count FROM subscribers");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM subscribers ORDER by id ASC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
             }
          }
  if(is_resource($que)){
    if(mysql_num_rows($que)!=0){ ?>
<div style="padding-bottom: 5px;">
<h5 class="summary">Summary</h5>
<span style="padding-right: 20px;">Total Active Subscribers: <span style="color: green; border: 1px solid blue; padding: 3px;"><?php echo $active;?></span></span>
<span style="padding-right: 20px;">Total Cancelled: <span style="color: red; border: 1px solid blue; padding: 3px;"><?php echo $cancelled;?></span></span>
<span style="padding-right: 20px;">Incomplete Subscriptions: <span style="color: green; border: 1px solid blue; padding: 3px;"><?php echo $incomplete;?></span></span>
<span style="padding-right: 20px;">Total Lifetime Revenue: <span style="color: green; border: 1px solid blue; padding: 3px;">Ksh. <?php echo (12*$active)+ (18*$cancelled)+ (6*$incomplete);?></span></span>
<span>Average Response Rate: <?php echo ($questions == '0'?'0':(number_format(($responses*100/$questions),2)))."%";?></span>
</div>
<div class="row-fluid shadows">
<div class="span5">
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
</div>
<div class="span5 offset2" style="text-align: right; font-size: 0.8em; padding-right: 5px;">
      <b>RECORDS : <span style="color: black;"><?php echo $total_count;?></span></b></div>
</div>	
<div class="row-fluid" style="background-color: #54C571; color: white; font-size: .9em;">
    <div class="span1">Number</div>
    <div class="span1">Name</div>
    <div class="span1">Subscription ID</div>
    <div class="span1">Subject Code</div>
    <div class="span1">Status</div>
    <div class="span1">Date Subscribed</div>
    <div class="span1">Lifetime</div>
    <div class="span1">Lifetime Questions Sent</div>
    <div class="span1">Lifetime Responses Received</div>
    <div class="span1">Lifetime Response  Rate</div>
    <div class="span1">Lifetime Correct Answers</div>
    <div class="span1">Lifetime Correct %</div>
</div>
<div class="row-fluid" style="height: 300px; overflow-y: auto; font-size: .8em;">
<?php     
  while($value=mysql_fetch_array($que)){
           if($i%2 == 0) $style="background-color:#eee;";
             else $style="background-color:#fff;";
              $id = $value['id'];
              $number = $value['number'];
              $name = $value['name_'];
              $subject = $value['subject'];
              $code = $value['code'];
              $class = $value['class'];
              $subid = $value['sub_id'];
              $status = $value['status'];
              $start = date('d-M-y',strtotime($value['date_']));
              $canceltime = $value['canceldate'];;
              $cancel = $canceltime != '0000-00-00 00:00:00'?date("d-M-y",strtotime($canceltime)):"";
              $lifetime = floor((time()-strtotime($value['date_']))/86400);
              
                $ntquestions = list_ntquestion($number,$code);
                $ntresponses = list_ntresponse($number,$code);
                $ntcorrectanswers = list_ntcorrect($number,$code);
              
              if($status=='0'){ $status = "Cancelled"; }
              elseif($status=='1'){$status = "Active";}
              elseif($status=='2'){$status = "Progress";}
              
              $responserate = ($ntresponses == '0'?'0':(number_format(($ntresponses*100/$ntquestions),2)))."%";
              $correctrate = ($ntresponses == '0'?'0':(number_format(($ntcorrectanswers*100/$ntresponses),2)))."%";
              $data[]=array($number,$name,$subid,$code,$status,$start,$cancel,$lifetime,$ntquestions,$ntresponses,$responserate,$ntcorrectanswers,$correctrate);
              ?>
<div class="row-fluid" style="<?php echo $style; ?>">
			<div class="span1" style="padding: 3px;"><a href="su-edit?subid=<?php echo $subid;?>" title="edit"><?php echo !empty($number)?$number: "No Number";?></a></div>
            <div class="span1" style="padding: 3px 2px 3px 3px;"><?php echo $name;?></div>
            <div class="span1" style="padding: 3px 2px 3px 3px;"><?php echo $subid;?></div>
            <div class="span1" style="padding: 3px 2px 3px 3px;"><?php echo $code;?></div>           
            <div class="span1" style="padding: 3px 3px 3px 5px;">
                <?php 
                  if($value['status']=='3'){
                     echo "<span style=\"color: red; \">Cancelled</span>";
                     }
                  elseif($value['status']=='1'){
                     echo "<span style=\"color: #8FC412; \">Active</span>";
                     }
                  elseif($value['status']=='2'){
                     echo "<span style=\"color: #E8A317; \">Progress..</span>";
                     }
               ?>
            </div>
            <div class="span1" style="padding: 3px 3px 3px 5px;"><?php echo $start;?></div>
            <div class="span1" style="padding: 3px 3px 3px 8px;"><?php echo $lifetime." days";?></div>
            <div class="span1" style="padding: 3px 3px 3px 3px;"><?php echo $ntquestions;?></div>
            <div class="span1" style="padding: 3px 3px 3px 3px;"><?php echo $ntresponses;?></div>
            <div class="span1" style="padding: 3px 3px 3px 5px;"><?php echo $responserate;?></div>
            <div class="span1" style="padding: 3px 3px 3px 8px;"><?php echo $ntcorrectanswers;?></div>
            <div class="span1" style="padding: 3px 3px 3px 8px;"><?php echo $correctrate;?></div>
</div>
<?php    $i++; } 
$_SESSION['data_array'] = $data;
?>
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