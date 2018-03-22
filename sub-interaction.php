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
    <link rel='shortcut icon' href='images/logo.jpg'/>
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
  <title>Subscriber Interaction:: M-Lesson</title>
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">M-LESSON</div>
</div>
<div class="row-fluid" style="background: url('images/bg.jpg');">
<div class="span2" id="accordion"><?php include "header.php";?><h5 style="color: #0097AA; background-color: white; padding: 5px;">Revenue</h5></div>
<div class="span10 login_content">
<div class="row-fluid">
  <div class="span8">
   <form action="" method="GET" class="login_d">
    <div class="input-prepend"><span class="add-on" style="color: green;">Select</span>
       <select name="filter" style="width: 35%;">
            <option value="" selected="">All</option>
            <option value="code">Subject Code</option>
            <option value="class">Class</option>
            <option value="subject">Subject</option>
       </select>
    <input type="text" name="new_value" value="" placeholder="" id="new_value" style="width: 47%;"/>
    <input type="submit" name="filtering" value="Filter" style="margin-top: 3px; margin-left: 5px;" />
    </div>
  </form>
  </div>
  <div class="span4" style="margin-top: 10px;">
  <a href="rpts_download.php?filter=<?php echo $filter_term;?>&value=<?php echo $value;?>" style="" class="btn btn-success">EXPORT CSV</a>
  <a target="_blank" href="pdf.php?filter=<?php echo $filter_term;?>&value=<?php echo $value;?>" style="" class="btn btn-info">EXPORT PDF</a>
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
            
            break;
            case 'code':         
             $code = $value;
            
            break;
            case 'subject':          
             $subject = $value;
            
            break;
            case 'null':
             $query = @mysql_query("SELECT COUNT(DISTINCT(code)) AS count FROM subscribers");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $quer = mysql_query("SELECT COUNT(*) AS active FROM subscribers WHERE status = 1");
             $row = mysql_fetch_array($quer);
             $active = $row['active'];
             $que = mysql_query("SELECT COUNT(*) AS cancelled FROM subscribers WHERE status = 0");
             $row = mysql_fetch_array($que);
             $cancelled = $row['cancelled'];
             $qu = mysql_query("SELECT COUNT(*) AS incomplete FROM subscribers WHERE status = 2");
             $row = mysql_fetch_array($qu);
             $incomplete = $row['incomplete'];
             $que = mysql_query("SELECT * FROM subscribers WHERE status != '2' GROUP BY number");
            break;
             }
          }
  if(is_resource($que)){
    if(mysql_num_rows($que)!=0){ ?>
<div style="padding-bottom: 10px;">
<span class="summary">Summary: </span><br />
<span style="padding-right: 20px;">Total Active Subscribers: <span style="color: green; border: 1px solid blue; padding: 3px;"><?php echo $active;?></span></span>
<span style="padding-right: 20px;">Total Cancelled: <span style="color: red; border: 1px solid blue; padding: 3px;"><?php echo $cancelled;?></span></span>
<span style="padding-right: 20px;">Incomplete Subscriptions: <span style="color: green; border: 1px solid blue; padding: 3px;"><?php echo $incomplete;?></span></span>
<span style="padding-right: 20px;">Total Lifetime Revenue: <span style="color: blue; border: 1px solid blue; padding: 3px;">Ksh. <?php echo (12*$active)+ (18*$cancelled)+ (6*$incomplete);?></span></span>
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
              $tcorrectrate = ($tresponses == '0'?'0':(number_format(($tcorrectanswers*100/$tresponses),2)))."%";?>
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
<?php    $i++; } ?>
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