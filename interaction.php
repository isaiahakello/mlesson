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
  <title>Revenue:: M-Lesson</title>
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span4" style="font-size: 2em;"><img src="images/logo.png" height="30"/></div>
<div class="span8"style="padding: 10px;color: white; text-align: right;">
<ul class="navigation">
<li><i class="fa fa-home" style="color: white;"></i> <a href="dashboard">Home</a></li>       
<li><i class="fa fa-users" style="color: white;"></i> <a href="subscribers?filter=null&page=1">Subscriptions</a></li>
<li><i class="fa fa-file" style="color: white;"></i> <a href="interaction?filter=null&page=1">Content</a></li>   
<li><i class="fa fa-usd" style="color: white;"></i> <a href="interaction?filter=null&page=1" class="active">Revenue</a></li>   
<li><i class="fa fa-comments" style="color: white;"></i> <a href="inbox?filter=null&page=1">Messenger</a></li>
<li><i class="fa fa-area-chart" style="color: white;"></i> <a href="inbox?filter=null&page=1">Reports</a></li>
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
<div class="span2" id="accordion"><?php include "header.php";?><h5 style="color: #0097AA; background-color: white; padding: 5px;">Revenue</h5></div>
<div class="span10 login_content">
<div class="row-fluid">
  <div class="span8">
   <form action="" method="GET" class="login_d">
    <div class="input-prepend"><span class="add-on" style="color: green;">Select</span>
       <select name="filter" style="width: 35%;">
            <option value="null" selected="">All</option>
            <option value="code">Subject Code</option>
            <option value="class">Class</option>
            <option value="subject">Subject</option>
       </select>
    <input type="text" name="new_value" value="" placeholder="" id="new_value" style="width: 47%;"/>
    </div><br />
    <div class="input-prepend"><span class="add-on" style="color: green;">Filter By</span>
       <select name="filter2" style="width: 49%;">
            <option value="" selected="">Time Period</option>
            <option value="today">Today</option>
            <option value="week">This Week</option>
            <option value="month">This Month</option>
            <option value="term">This Term</option>
            <option value="lifetime">Lifetime</option>
            <option value="custom">Custom</option>
       </select>
    <input type="hidden" name="new_value2" value="" placeholder="" id="new_value2" style="width: 30%;"/>
    <input type="hidden" name="new_value3" value="" placeholder="" id="new_value3" style="width: 30%;"/>
    <input type="submit" name="filtering" value="Filter" style="margin-top: 3px; margin-left: 5px;" />
    </div>
  </form>
  </div>
  <div class="span4" style="margin-top: 10px;">
  <a href="repts_download.php?filter=<?php echo $filter_term;?>&value=<?php echo $value;?>" style="" class="btn btn-success">EXPORT CSV</a>
  </div>
</div>
<div class="row-fluid">
<?php 
    include "pagination.php";
    $per_page = 50;
    $page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
  if(isset($_GET['filter'])){ 
        $value = $_GET['new_value'];
        $date = $_GET['new_value2'];
        $date2 = $_GET['new_value3'];
        $now = date("Y-m-d",time());
        switch($_GET['filter']){
            case 'class':
             $class = $value;
            switch($_GET['filter2']){           
             case 'today':  
             $date = 'today';
             $date2 = 'null';   
             $total_count = list_subscribers_today('class',$class);
             $que = mysql_query("SELECT * FROM subscribers WHERE class='$class' AND DATE(date_)='$now' GROUP BY code");
             break;
             case 'week':
             $date = 'week';
             $date2 = 'null';  
             $total_count = list_subscribers_week('class',$class);
             $que = mysql_query("SELECT * FROM subscribers WHERE class='$class' AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW() GROUP BY code");
             break;
             case 'month':
             $date = 'month';
             $date2 = 'null';
             $total_count = list_subscribers_month('class',$class);
             $que = mysql_query("SELECT * FROM subscribers WHERE class='$class' AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 MONTH) AND NOW() GROUP BY code");
             break;
             case 'term':
             $date = 'term';
             $date2 = 'null';
             $total_count = list_subscribers_term('class',$class);
             $que = mysql_query("SELECT * FROM subscribers WHERE class='$class' AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 3 MONTH) AND NOW() GROUP BY code");
             break;
             case 'lifetime':
             $date = 'null';
             $date2 = 'null';
             $query = @mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $que = mysql_query("SELECT * FROM subscribers GROUP BY code");
             break;
             case 'custom': 
             $total_count = list_subscribers_custom('class',$class,$date,$date2);
             $que = mysql_query("SELECT * FROM subscribers WHERE class='$class' AND date_ BETWEEN '$date' AND '$date2' GROUP BY code");
             break;
             }
            break;
            case 'code':         
             $code = $value;
            switch($_GET['filter2']){           
             case 'today':  
             $date = 'today';
             $date2 = 'null';   
             $total_count = list_subscribers_today('code',$code);
             $que = mysql_query("SELECT * FROM subscribers WHERE code LIKE '%$code%' AND DATE(date_)='$now' GROUP BY code");
             break;
             case 'week':
             $date = 'week';
             $date2 = 'null';  
             $total_count = list_subscribers_week('code',$code);
             $que = mysql_query("SELECT * FROM subscribers WHERE code LIKE '%$code%' AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW() GROUP BY code");
             break;
             case 'month':
             $date = 'month';
             $date2 = 'null';
             $total_count = list_subscribers_month('code',$code);
             $que = mysql_query("SELECT * FROM subscribers WHERE code LIKE '%$code%' AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 MONTH) AND NOW() GROUP BY code");
             break;
             case 'term':
             $date = 'term';
             $date2 = 'null';
             $total_count = list_subscribers_term('code',$code);
             $que = mysql_query("SELECT * FROM subscribers WHERE code LIKE '%$code%' AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 3 MONTH) AND NOW() GROUP BY code");
             break;
             case 'lifetime':
             $date = 'null';
             $date2 = 'null';
             $query = @mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $que = mysql_query("SELECT * FROM subscribers GROUP BY code");
             break;
             case 'custom': 
             $total_count = list_subscribers_custom('code',$code,$date,$date2);
             $que = mysql_query("SELECT * FROM subscribers WHERE code LIKE '%$code%' AND date_ BETWEEN '$date' AND '$date2' GROUP BY code");
             break;
             }
            break;
            case 'subject':          
             $subject = strtoupper($value);
             switch($_GET['filter2']){           
             case 'today':  
             $date = 'today';
             $date2 = 'null';   
             $total_count = list_subscribers_today('subject',$subject);
             $active = list_active_subscribers_today('subject',$subject);
             $cancelled = list_cancelled_subscribers_today('subject',$subject);
             $incomplete = list_incomplete_subscribers_today('subject',$subject);
             $que = mysql_query("SELECT * FROM subscribers WHERE subject LIKE '%$subject%' AND DATE(date_)='$now' GROUP BY code");
             break;
             case 'week':
             $date = 'week';
             $date2 = 'null';  
             $total_count = list_subscribers_week('subject',$subject);
             $que = mysql_query("SELECT * FROM subscribers WHERE subject LIKE '%$subject%' AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW() GROUP BY code");
             break;
             case 'month':
             $date = 'month';
             $date2 = 'null';
             $total_count = list_subscribers_month('code',$code);
             $que = mysql_query("SELECT * FROM subscribers WHERE subject LIKE '%$subject%' AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 1 MONTH) AND NOW() GROUP BY code");
             break;
             case 'term':
             $date = 'term';
             $date2 = 'null';
             $total_count = list_subscribers_term('subject',$subject);
             $que = mysql_query("SELECT * FROM subscribers WHERE subject LIKE '%$subject%' AND date_ BETWEEN DATE_SUB(NOW(),INTERVAL 3 MONTH) AND NOW() GROUP BY code");
             break;
             case 'lifetime':
             $date = 'null';
             $date2 = 'null';
             $query = @mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $que = mysql_query("SELECT * FROM subscribers  WHERE subject LIKE '%$subject%' GROUP BY code");
             break;
             case 'custom': 
             $total_count = list_subscribers_custom('subject',$subject,$date,$date2);
             $que = mysql_query("SELECT * FROM subscribers WHERE subject LIKE '%$subject%' AND date_ BETWEEN '$date' AND '$date2' GROUP BY code");
             break;
             }
            break;
            case 'null':
             $date = 'null';
             $date2 = 'null';
             $query = @mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $que = mysql_query("SELECT * FROM subscribers GROUP BY code");
            break;
             }
          }
  if(is_resource($que)){
    if(mysql_num_rows($que)!=0){ ?>
<div style="padding-bottom: 10px;">
<span class="summary">Summary: </span><br />
<span style="padding-right: 20px;">Todays Revenue: <span style="color: blue; border: 1px solid blue; padding: 3px;">Ksh. <?php echo get_revenue_today();?></span></span>
<span style="padding-right: 20px;">This Weeks Revenue: <span style="color: blue; border: 1px solid blue; padding: 3px;">Ksh. <?php echo get_revenue_week();?></span></span>
<span style="padding-right: 20px;">This Months Revenue: <span style="color: blue; border: 1px solid blue; padding: 3px;">Ksh. <?php echo get_revenue_month();?></span></span>
<span style="padding-right: 20px;">Total Lifetime Revenue: <span style="color: blue; border: 1px solid blue; padding: 3px;">Ksh. <?php echo get_revenue_lifetime();?></span></span>
</div>
<div class="row-fluid shadows">
<div class="span5"></div>
<div class="span5 offset2" style="text-align: right; font-size: 0.8em; padding-right: 5px;">
      <b>RECORDS : <span style="color: black;"><?php echo $total_count;?></span></b></div>
</div>	
<div class="row-fluid" style="background-color: #54C571; color: white; font-size: .9em;">   
    <div class="span1">Subject Code</div>
    <div class="span1">Class</div>
    <div class="span1">Subject</div>
    <div class="span1">Active Subscribers</div>
    <div class="span1">Cancelled Subscribers</div>
    <div class="span1">Time Period New Subscribers</div>
    <div class="span1">Time Period New Cancelled</div>
    <div class="span1">Time Period Questions Sent</div>
    <div class="span1">Time Period Responses Received</div>
    <div class="span1">Time Period Response  Rate</div>
    <div class="span1">Time Period Correct Answers</div>
    <div class="span1">Time Period Correct %</div>
    
</div>
<div class="row-fluid" style="height: 350px; overflow-y: auto; font-size: .8em;">
<?php     while($value=mysql_fetch_array($que)){
           if($i%2 == 0) $style="background-color:#eee;";
             else $style="background-color:#fff;";
              $subject = $value['subject'];
              $code = $value['code'];
              $class = $value['class'];
              $active = list_active($code);
              $inactive = list_cancelled($code);
              if($date == 'null'){
                $tnew = $active;
                $tcancel = $inactive;
                $tquestions = list_tquestion($code);
                $tresponses = list_tresponse($code);
                $tcorrectanswers = list_tcorrect($code);
              }
              else{
                $tnew = list_tnew($code,$date,$date2);
                $tcancel = list_tcancel($code,$date,$date2);
                $tquestions = list_tquestions($code,$date,$date2);
                $tresponses = list_tresponses($code,$date,$date2);
                $tcorrectanswers = list_tcorrects($code,$date,$date2);
              }
              $tresponserate = ($tresponses == '0'?'0':(number_format(($tresponses*100/$tquestions),2)))."%";
              $tcorrectrate = ($tresponses == '0'?'0':(number_format(($tcorrectanswers*100/$tresponses),2)))."%";
              $data[]=array($code,$subject,$active,$inactive,$tnew,$tcancel,$tquestions,$tresponses,$tresponserate,$tcorrectanswers,$tcorrectrate);
              ?>
<div class="row-fluid" style="<?php echo $style; ?>">
			<div class="span1" style="padding: 3px;"><?php echo $code;?></div>
            <div class="span1" style="padding: 3px 2px 3px 3px;"><?php echo $class;?></div>
            <div class="span1" style="padding: 3px 2px 3px 3px;"><?php echo $subject;?></div>
			<div class="span1" style="padding: 3px 2px 3px 3px;"><?php echo $active;?></div>           
            <div class="span1" style="padding: 3px 3px 3px 3px;"><?php echo $inactive;?></div>
            <div class="span1" style="padding: 3px 3px 3px 3px;"><?php echo $tnew;?></div>
            <div class="span1" style="padding: 3px 3px 3px 3px;"><?php echo $tcancel;?></div>
            <div class="span1" style="padding: 3px 3px 3px 3px;"><?php echo $tquestions;?></div>
            <div class="span1" style="padding: 3px 3px 3px 3px;"><?php echo $tresponses;?></div>
            <div class="span1" style="padding: 3px 3px 3px 5px;"><?php echo $tresponserate;?></div>
            <div class="span1" style="padding: 3px 3px 3px 8px;"><?php echo $tcorrectanswers;?></div>
            <div class="span1" style="padding: 3px 3px 3px 8px;"><?php echo $tcorrectrate;?></div>
</div>
<?php    $i++; } 
$_SESSION['data_array'] = $data;
?>
</div>
<?php } else { echo "No Records found!"; } 
  }
  else { echo "No records found!"; }
?>	 		
</div>
</div>
</div>  	
<?php include "footer.php";?>
</div>
</body>
</html>