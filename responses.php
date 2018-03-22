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
    <link rel='shortcut icon' href='images/logo.jpg'/>
    <link href='css/bootstrap.css' rel='stylesheet' type='text/css'/>
    <link href='css/bootstrap-responsive.css' rel='stylesheet' type='text/css'/>
    <link rel='stylesheet' href='css/main.css'/>
    <link href='css/smoothness/jquery-ui-1.10.3.custom.css' rel='stylesheet'/>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"/>
    <script src='js/jquery-1.9.1.js'></script>
    <script src='js/bootstrap.min.js'></script>
    <script src='js/jquery-ui.js'></script>
    <script type="text/javascript" src="js/reports.js"></script>			
	<title>Responses:: MLESSON</title>
</head>
<body>
<div class="container-fluid">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">MLESSON</div>
</div>
<div class="row-fluid" style="background: url('images/bg.jpg');">
<div class="span2" id="accordion"><?php include "header.php";?></div>
<div class="span9 login_content">
<div class="eh">RESPONSES</div> 
 <div class="row-fluid">
<div class="span9 offset1">
 <form action="" method="GET" class="login_d">
    <div class="input-prepend"><span class="add-on" style="color: black;">Filter By</span>
        <select name="filter" class="chosen-select" style="width: 40%;">
            <option value="" selected="">..select..</option>
            <option value="subject">SUBJECT</option>
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
    $per_page = 10;
    $page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
  if(isset($_GET['filter'])){
       $filter_term = $_GET['filter'];
       $value = $_GET['new_value'];
        switch($filter_term){
            case 'subject':
            if(empty($value)) break;
             $subject = substr(strtoupper($value),0,3).'1';
             $query = mysql_query("SELECT COUNT(*) AS count FROM responses WHERE subject LIKE '%$subject'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM responses WHERE subject LIKE '%$subject' ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
            case 'class':
            if(empty($value)) break;
             $class = trim(preg_replace("/[^0-9]/","",$value));
             $query = mysql_query("SELECT COUNT(*) AS count FROM subjects WHERE class = '$class'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM subjects WHERE class = '$class' ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
            case 'code':
            if(empty($value)) break;
             $class = $value;
             $query = mysql_query("SELECT COUNT(*) AS count FROM subjects WHERE subject_id LIKE '$code'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM subjects WHERE subject_id LIKE '$code' ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
            case 'null':
             $query = mysql_query("SELECT COUNT(*) AS count FROM responses");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM responses ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
             }
          }
  if(is_resource($que)){
    if(mysql_num_rows($que)!=0){ ?>
<div class="row-fluid" style="background-color: #54C571; color: white; font-size: .9em;">
    <div class="span2" style="padding-left: 5px;">Number</div>
    <div class="span2" style="">Sub ID</div>
    <div class="span2" style="">Question ID</div>
    <div class="span1">Subject Code</div>
    <div class="span2">Date</div>
    <div class="span1">Response</div>
    <div class="span2">Correct?</div>
</div>
<div style="height: 350px; overflow-y: auto;">
<?php     while($value=mysql_fetch_array($que)){
           if($i%2 == 0) $style="background-color:#eee;";
             else $style="background-color:#fff;";
              $id = $value['id'];
              $number = $value['number'];
              $subid = $value['sub_id'];
              $question = $value['question_id'];
              $code = $value['subject'];
              $response = $value['response'];
              $co_response = $value['correct_response'];
              $correct = ($response == $co_response?'Correct':'Incorrect');
              $time = $value['time_'];?>
<div class="row-fluid" style="<?php echo $style; ?> font-size: .9em;">
			<div class="span2" style="padding-left: 5px;"><?php echo $number;?></div>
            <div class="span2" style=""><?php echo $subid;?></div>
			<div class="span2" style=""><?php echo $question;?></div>
			<div class="span1" style=""><?php echo $code;?></div>
            <div class="span2" style=""><?php echo $time;?></div>
            <div class="span1" style=""><?php echo $response;?></div>
            <div class="span2" style=""><?php echo $correct;?></div>
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