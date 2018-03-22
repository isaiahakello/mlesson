<?php
ini_set('post_max_size','64M');
ini_set('upload_max_filesize','64M');
ob_start();
 require_once "config.php";
 require_once "timeout.php";
 require_once "session.php";
 require_once "functions.php";
 canView();
 $cid = $_SESSION['uid'];
 $details = list_subjects();
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
    <link href='//cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.1/jquery-ui-timepicker-addon.min.css' rel='stylesheet'/>
    <link rel="stylesheet" type="text/css" href="css/chosen.css"/>
    <script src='js/jquery-1.9.1.js'></script>
    <script src='js/bootstrap.min.js'></script>
    <script src='js/jquery-ui.js'></script>
    <script src='//cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.1/jquery-ui-timepicker-addon.min.js'></script>
    <script type="text/javascript" src="js/js.js"></script>	
    <script type="text/javascript" src="js/reports.js"></script>
    <script type="text/javascript" src="js/chosen.jquery.js"></script>
    <script>
    $(function() {
      $(".chosen-select").chosen();
     });
    </script>			
  <title>Scheduled Content:: mLESSON</title>
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">MLESSON</div>
</div>
<div class="row-fluid" style="background: url('images/bg.jpg');">
<div class="span2" id="accordion"><?php include "header.php";?><h5 style="color: #0097AA; background-color: white; padding: 5px;">SCHEDULED CONTENT</h5></div>
<div class="span10 login_content">
<div class="row-fluid">
  <form action="" method="GET" style="text-align: center; background-color: #F0FFFF; padding-top: 5px;">
    <div class="input-prepend"><span class="add-on" style="color: green;">Filter By</span>
       <select name="filter" style="width: 35%;">
            <option value="" selected="">..select..</option>
            <option value="id">Question ID</option>
            <option value="subject">Subject</option>
       </select>
    <input type="text" name="new_value" value="" placeholder="" id="new_value" style="width: 45%;"/>
    <input type="submit" name="filtering" value="Filter" style="margin-top: 3px; margin-left: 5px;" />
    </div>
  </form>
</div>
<div class="row-fluid">
<?php 
    include "pagination.php";
    $per_page = 20;
    $page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
  if(isset($_GET['filter'])){  
        $value = $_GET['new_value'];
        switch($_GET['filter']){
            case 'subject':
            if(empty($value)) break;           
             $subject = substr(strtoupper($value),0,3).'1';
             $query = mysql_query("SELECT COUNT(*) AS count FROM schedule WHERE subject LIKE '%$subject%'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM schedule WHERE subject LIKE '%$subject%' ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}",$connect);
            break;
            case 'id':
            if(empty($value)) break;
             $id = $value;
             $query = mysql_query("SELECT COUNT(*) AS count FROM schedule WHERE question_id LIKE '%$id%'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM schedule WHERE question_id LIKE '%$id%' ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
            case 'null':
             $query = @mysql_query("SELECT COUNT(*) AS count FROM schedule");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM schedule ORDER by id ASC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
             }
          }
  if(is_resource($que)){
    if(mysql_num_rows($que)!=0){ ?>
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
    <div class="span2" style="padding-left: 5px;">ID</div>
    <div class="span1">Date</div>
    <div class="span3">Question</div>
    <div class="span3">Correct Text</div>
    <div class="span3">Incorrect Text</div>
    
    
</div>
<div class="row-fluid" style="height: 450px; overflow-y: auto; font-size: .9em;">
<?php     while($value=mysql_fetch_array($que)){
           if($i%2 == 0) $style="background-color:#eee;";
             else $style="background-color:#fff;";
              $id = $value['id'];
              $qid = $value['question_id'];
              $correct = $value['correct_text'];
              $incorrect = $value['incorrect_text'];
              $question = get_question($qid);
              $date = date('d-M-y',strtotime($value['date_']));
              ?>
<div class="row-fluid" style="<?php echo $style; ?>">
			<div class="span2" style="padding: 3px;"><?php echo $qid;?></div>
            <div class="span1" style="padding: 3px 2px 3px 0px;"><?php echo $date;?></div>
			<div class="span3" style="padding: 3px 2px 3px 0px;"><?php echo $question;?></div>           
            <div class="span3" style="padding: 3px 3px 3px 0px;"><?php echo $correct;?></div>
            <div class="span3" style="padding: 3px 3px 3px 0px;"><?php echo $incorrect;?></div>
            
           
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