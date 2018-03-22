<?php
ini_set('post_max_size','64M');
ini_set('upload_max_filesize','64M');
ob_start();
 require_once "config.php";
 require_once "timeout.php";
 require_once "session.php";
 require_once "functions.php";
isSubscriberManager();
 $cid = $_SESSION['uid'];
 $details = list_subjects();
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
  <title>Upload Content:: mLESSON</title>
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">MLESSON</div>
</div>
<div class="row-fluid" style="background: url('images/bg.jpg');">
<div class="span2" id="accordion"><?php include "header.php";?><h5 style="color: #0097AA; background-color: white; padding: 5px;">UPLOAD CONTENT</h5></div>
<div class="span10 login_content">
<form class="form-horizontal" enctype="multipart/form-data" action="" method="POST" style="font-size: 1em; padding-top: 10px;">
<?php 
 if(isset($_POST['submit'])){ 
    $now = date("Y-m-d H:i:s",time());
    if(isset($_FILES['upload_content'])){
    $subject= $_POST['subject'];
    $start= $_POST['startdate'];
    $endate = $_POST['endate'];
    $time_ = $_POST['time_'];
    $tmp_file = $_FILES['upload_content']['tmp_name'];
    $target_file = basename($_FILES['upload_content']['name']);
    $upload_dir = "uploads";
    if(!is_dir($upload_dir)){
       mkdir($upload_dir);
    }
    if(move_uploaded_file($tmp_file,$upload_dir."/".$target_file)){
     $inputFileName = $upload_dir."/".$target_file;
      try {
        include 'classes/PHPExcel/IOFactory.php';
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFileName);
            }
      catch (Exception $e) {
        $error = "Problem reading the file:";
        echo "<meta http-equiv='refresh' content='0;URL=uploadcontent?e'>";
              unlink($inputFileName);exit;
        } 
      //Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0); 
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();
    //get all the columns
        $lastColumn++;
         for($row = 2; $row <= $lastRow; $row++){
              $cells = "";
                for($column = 'A'; $column != $lastColumn; $column++){
                  $cell = $sheet->getCell($column.$row)->getFormattedValue($column.$row);
                  if(!empty($cell)){
                   $cells .= $cell."~";  
                   }
                 }
               $cells = substr($cells, 0, -1); //remove trailing * from entire string
               $cells = mysql_real_escape_string($cells);
               $cells = explode("~",$cells);
              
               if(count($cells) > 8){
               $cells7 = "";
               foreach($cells as $cells6){
                   $cells7 .= "'".$cells6."',";
                   }
               $q_id = random_string(4)."_".$subject;
               $cells8 = "'',"."'".$subject."',"."'".$q_id."',".$cells7."'".$start."','".$endate."','".$time_."'";
               $query = mysql_query("INSERT INTO uploadcontent VALUES ($cells8)");
             if(mysql_error()) {
               echo "<span style=\"color: red;\">Error: </span> ". mysql_error()."<br/>";
                 }                    
              } 
              else  echo "<div style=\"color: red; padding-bottom:5px; padding-top: 10px;\">"."You have fewer columns in the excel file"."</div>";
            }                                        
      echo "<div style=\"color: green; padding-bottom:5px; padding-top: 10px;\">"."File Uploaded"."</div>";
      unlink($inputFileName);
    }
    else{  //if move file failed
    echo "<div style=\"color: red; padding-top: 10px; padding-bottom: 5px;\">";
    echo "Problem in file upload!";
    echo "</div>";
    }
}  
 }
?>
<fieldset class="row-fluid">
<div class="span2" style="padding-left: 10px; margin-top: -10px;">
    <?php if(is_array($details)){ ?>
     <select name="subject" class="chosen-select" style="width: 120%;">
            <option value="" selected="">...select subject...</option>
            <?php foreach($details as $detail){ ?>
             <option value="<?php echo $detail['subject_id'];?>"><?php echo $detail['subject']." ".$detail['class'];?></option>   
            <?php } ?>
     </select>
     <?php } else echo '<a href="dashboard">Please add a subject before uploading a file</a>';?>     
     </div>
     <div class="span5 form-group">
      <input type="text" name="startdate" id="datepicker" value="" required="" style="width: 29%;" placeholder="Start Date"/>
      <input type="text" name="endate" id="datepicker2" value="" required="" style="width: 28%;" placeholder="End Date"/>
      <input type="text" name="time_" id="timepicker" value="" required="" style="width: 27%;" placeholder="Time to Deploy"/>
     </div>
    <div class="span5 form-group" style="background-color: white;">
    <span style="">File to upload:</span>
            <input type="file" name="upload_content" style="width: 45%;" required=""/> 
             <input type="submit" name="submit" class="btn btn-info" value="Upload" style=""/>
             
    </div>
</fieldset>
</form>
<div class="row-fluid">
<div class="span8">
  <form action="" method="GET" style="text-align: center; background-color: #F0FFFF; padding-top: 5px;">
    <div class="input-prepend"><span class="add-on" style="color: green;">Filter By</span>
       <select name="filter" style="width: 35%;">
            <option value="" selected="">..select..</option>
            <option value="id">Question ID</option>
            <option value="start">Start Date</option>
            <option value="end">End Date</option>
            <option value="quizdate">Question Date</option>
            <option value="subject">Subject</option>
       </select>
    <input type="text" name="new_value" value="" placeholder="" id="new_value" style="width: 45%;"/>
    <input type="submit" name="filtering" value="Filter" style="margin-top: 3px; margin-left: 5px;" />
    </div>
  </form>
   </div>
  <div class="span4" style="margin-top: 10px;">
  <a href="content_download.php?filter=<?php echo $filter_term;?>&value=<?php echo $value;?>" style="" class="btn btn-success">EXPORT CSV</a>
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
            case 'start':
            if(empty($value)) break;           
             $date = $value;
             $query = mysql_query("SELECT COUNT(*) AS count FROM uploadcontent WHERE DATE(start_date) = '$date'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM uploadcontent  WHERE DATE(start_date) = '$date' ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}",$connect);
            break;
            case 'end':
            if(empty($value)) break;           
             $date = $value;
             $query = mysql_query("SELECT COUNT(*) AS count FROM uploadcontent WHERE DATE(end_date) = '$date'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM uploadcontent  WHERE DATE(end_date) = '$date' ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}",$connect);
            break;
            case 'quizdate':
            if(empty($value)) break;           
             $date = $value;
             $query = mysql_query("SELECT COUNT(*) AS count FROM uploadcontent WHERE DATE(question_date) = '$date'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM uploadcontent WHERE DATE(question_date) = '$date' ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}",$connect);
            break;
            case 'subject':
            if(empty($value)) break;           
             $subject = strtoupper(substr($value,0,3)).'1';
             $query = mysql_query("SELECT COUNT(*) AS count FROM uploadcontent WHERE subject LIKE '%$subject'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM uploadcontent WHERE subject LIKE '%$subject' ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}",$connect);
            break;
            case 'id':
            if(empty($value)) break;
             $id = $value;
             $query = mysql_query("SELECT COUNT(*) AS count FROM uploadcontent WHERE question LIKE '%$id%'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM uploadcontent WHERE question LIKE '%$id%' ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
            case 'null':
             $query = @mysql_query("SELECT COUNT(*) AS count FROM uploadcontent");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM uploadcontent ORDER by id ASC LIMIT {$per_page} OFFSET {$pagination->offset()}");
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
    <div class="span1" style="padding-left: 5px;">ID</div>
    <div class="span1">Subject</div>
    <div class="span1">Date</div>
    <div class="span2">Question</div>
    <div class="span1">Answer A</div>
    <div class="span1">Answer B</div>
    <div class="span1">Answer C</div>
    <div class="span1">Answer D</div>
    <div class="span1">Correct Answer</div>
    <div class="span2">Response Text</div>
   
</div>
<div class="row-fluid" style="height: 450px; overflow-y: auto; font-size: .8em;">
<?php     while($value=mysql_fetch_array($que)){
           if($i%2 == 0) $style="background-color:#eee;";
             else $style="background-color:#fff;";
              $id = $value['id'];
              $qid = $value['question'];
              $subject = $value['subject'];
              $date = $value['question_date'];
              $question = $value['question_text'];
              $qnumber = $value['question_no'];
              $response = $value['correct_text'];
              $ansa = $value['answer_a'];
              $ansb = $value['answer_b'];
              $ansc = $value['answer_c'];
              $ansd = $value['answer_d'];
              $answer = $value['correct_answer'];
              ?>
<div class="row-fluid" style="<?php echo $style; ?>">
			<div class="span1" style="padding: 3px;"><?php echo $qid;?>
                 <a href="edit?quizid=<?php echo $id;?>" title="view" style=""> open</a>
                 <a href="delete.php?content=<?php echo $id;?>" title="delete" style="color: red; font-style: italic;" onclick="return deleteContent()"> delete</a>
                 </div>
            <div class="span1" style="padding: 3px 2px 3px 0px;"><?php echo $subject;?></div>
            <div class="span1" style="padding: 3px 2px 3px 0px;"><?php echo $date;?></div>
			<div class="span2" style="padding: 3px 2px 3px 0px;"><?php echo $question;?></div>           
            <div class="span1" style="padding: 3px 3px 3px 0px;"><?php echo $ansa;?></div>
            <div class="span1" style="padding: 3px 3px 3px 0px;"><?php echo $ansb;?></div>
            <div class="span1" style="padding: 3px 3px 3px 0px;"><?php echo $ansc;?></div>
            <div class="span1" style="padding: 3px 3px 3px 0px;"><?php echo $ansd;?></div>
            <div class="span1" style="padding: 3px 3px 3px 0px;"><?php echo $answer;?></div>
            <div class="span2" style="padding: 3px 3px 3px 0px;"><?php echo $response;?></div>
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
<script type="text/javascript">
  function deleteContent(){
	var test = confirm("Are you sure?");
        if(test == true){
         return true;
       }
      else {
        return false;
      }
}
</script>
</html>