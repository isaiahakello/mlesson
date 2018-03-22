<?php
ob_start();
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
  <title>Class Interaction:: M-Lesson</title>
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">M-LESSON</div>
</div>
<div class="row-fluid" style="background: url('images/bg.jpg');">
<div class="span2" id="accordion"><?php include "header.php";?></div>
<div class="span10 login_content">
<div class="row-fluid">
  <div class="span9">
   <form action="" method="GET" class="login_d">
    <div class="input-prepend"><span class="add-on" style="color: green;">Filter By</span>
       <select name="filter" style="width: 27%;">
            <option value="" selected="">All</option>
            <option value="class">Class</option>
       </select>
    <input type="text" name="new_value" value="" placeholder="" id="new_value" style="width: 70%;"/>
    <input type="submit" name="filtering" value="Filter" style="margin-top: 3px; margin-left: 5px;" />
    </div>
  </form>
  </div>
  <div class="span3" style="margin-top: 10px;">
  <a href="rpts_download.php?filter=<?php echo $filter_term;?>&value=<?php echo $value;?>" class="btn btn-success" style="font-size: .8em;">EXPORT CSV</a>
  <a target="_blank" href="pdf.php?filter=<?php echo $filter_term;?>&value=<?php echo $value;?>" class="btn btn-info" style="font-size: .8em;">EXPORT PDF</a>
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
            case 'class':
            if(empty($value)) break;           
             $class = $value;
             $quer = mysql_query("SELECT COUNT(*) AS active FROM subscribers WHERE class = '$class' AND status = 1");
             $row = mysql_fetch_array($quer);
             $active = $row['active'];
             $que = mysql_query("SELECT COUNT(*) AS cancelled FROM subscribers WHERE class = '$class' AND status = 3");
             $row = mysql_fetch_array($que);
             $cancelled = $row['cancelled'];
             $questions = list_cquestions('C'.$class);
             $responses = list_cresponses('C'.$class);
             $qu = mysql_query("SELECT COUNT(*) AS incomplete FROM subscribers WHERE class = '$class' AND status = 2");
             $row = mysql_fetch_array($qu);
             $incomplete = $row['incomplete'];
             $query = mysql_query("SELECT COUNT(*) AS count FROM subscribers WHERE class = '$class'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM subscribers WHERE class = '$class' ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
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
             $query = @mysql_query("SELECT COUNT(*) AS count FROM subscribers WHERE class != ''");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM subscribers WHERE class != '' GROUP BY class ORDER by id ASC LIMIT {$per_page} OFFSET {$pagination->offset()}");
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
    <div class="span1">Class</div>
    <div class="span2">Questions Sent</div>
    <div class="span2">Responses Received</div>
    <div class="span2">Response  Rate</div>
    <div class="span2">Correct Answers</div>
    <div class="span2">Correct %</div>
</div>
<div class="row-fluid" style="height: 350px; overflow-y: auto; font-size: .8em;">
<?php     while($value=mysql_fetch_array($que)){
           if($i%2 == 0) $style="background-color:#eee;";
             else $style="background-color:#fff;";
              $id = $value['id'];
              $class = $value['class'];
            
                $cquestions = list_cquestions('C'.$class);
                $cresponses = list_cresponses('C'.$class);
                $correctanswers = list_ccorrect('C'.$class);
              
              
              $responserate = ($cresponses == '0'?'0':(number_format(($cresponses*100/$cquestions),2)))."%";
              $correctrate = ($cresponses == '0'?'0':(number_format(($correctanswers*100/$cresponses),2)))."%";?>
<div class="row-fluid" style="<?php echo $style; ?>">
			<div class="span1" style="padding: 3px 2px 3px 3px;"><?php echo 'Class '.$class;?></div>
            <div class="span2" style="padding: 3px 3px 3px 3px;"><?php echo $cquestions;?></div>
            <div class="span2" style="padding: 3px 3px 3px 3px;"><?php echo $cresponses;?></div>
            <div class="span2" style="padding: 3px 3px 3px 5px;"><?php echo $responserate;?></div>
            <div class="span2" style="padding: 3px 3px 3px 8px;"><?php echo $correctanswers;?></div>
            <div class="span2" style="padding: 3px 3px 3px 8px;"><?php echo $correctrate;?></div>
</div>
<?php    $i++; } ?>
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