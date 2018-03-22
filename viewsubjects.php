<?php
ob_start();
 require_once "config.php";
 require_once "session.php";
 require_once "functions.php";
 confirm_logged_in();
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
	<title>Subjects:: MLESSON</title>
</head>
<body>
<div class="container-fluid">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">MLESSON</div>
</div>
<div class="row-fluid" style="background: url('images/bg.jpg');">
<div class="span2" id="accordion"><?php include "header.php";?></div>
<div class="span9 login_content">
<div class="eh">SUBJECTS</div> 
<div class="row-fluid">
<?php
   if(isset($_POST['next'])){
      $sub = $_POST['subject'];     
      $class = $_POST['class'];
      switch($sub){
        case 'MAT':
          $su = 'MATHS';
         break;
        case 'ENG':
          $su = 'ENGLISH';
         break;
        case 'KIS':
         $su = 'KISWAHILI';
         break;
        case 'SCI':
         $su = 'SCIENCE';
         break;
        case 'SOC':
         $su = 'SOCIAL STUDIES';
         break;        
    }    
      $code = $class.$sub.'1';
       if(service_exists($code)){
        echo "<span style=\"color: red;padding: 10px; padding-bottom: 5px;\">"."The subject code already exists!"."</span>";
      }
      else{
        $qu = mysql_query("INSERT INTO subjects (subject,subject_id,class)VALUES('$su','$code','$class')") or die(mysql_error());
        echo "<span style=\"color: green;padding: 10px; padding-bottom: 5px;\">"."Subject created..."."</span>";
        }        
   }
 ?>
 </div>
 <div class="row-fluid">
<div class="span9 offset1">
 <form action="" method="GET" class="login_d">
    <div class="input-prepend"><span class="add-on" style="color: black;">Filter By</span>
        <select name="filter" class="chosen-select" style="width: 40%;">
            <option value="" selected="">..select..</option>
            <option value="subject">SUBJECT</option>
            <option value="class">CLASS</option>
            <option value="code">SUBJECT ID</option>
        </select>
    <input type="text" name="new_value" value="" placeholder="" id="new_value"/>
    <input type="submit" name="filtering" value="FILTER" style="margin-left: 5px;" />
    </div>   
   </form>   
   </div>
   <div class="span2"><a href=""data-toggle="modal" data-target="#myModal">Add New Subject<i class="fa fa-plus-circle" style="padding-left: 5px;"></i></a></div>
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
             $subject = ucfirst(strtolower($value));
             $query = mysql_query("SELECT COUNT(*) AS count FROM subjects WHERE subject = '$subject'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM subjects WHERE subject = '$subject' ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
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
             $query = mysql_query("SELECT COUNT(*) AS count FROM subjects");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM subjects ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
             }
          }
  if(is_resource($que)){
    if(mysql_num_rows($que)!=0){ ?>
<div class="row-fluid" style="background-color: #54C571; color: white; font-size: .9em;">
    <div class="span2" style="padding-left: 5px;">SUBJECT ID</div>
    <div class="span2" style="">SUBJECT</div>
    <div class="span2">CLASS</div>
    <div class="span2">ACTION</div>
</div>
<div style="height: 350px; overflow-y: auto;">
<?php     while($value=mysql_fetch_array($que)){
           if($i%2 == 0) $style="background-color:#eee;";
             else $style="background-color:#fff;";
              $id = $value['id'];
              $code = $value['subject_id'];?>
<div class="row-fluid" style="<?php echo $style; ?> font-size: .9em;">
			<div class="span2" style="padding-left: 5px;"><?php echo $code;?></div>
			<div class="span2" style=""><?php echo $value['subject'];?></div>
			<div class="span2" style=""><?php echo $value['class'];?></div>
            <div class="span2" style="padding: 3px 3px 3px 0px;">
                 <a href="delete.php?subject=<?php echo $id;?>" title="delete" style="color: red;"> Delete</a>
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
</div>
<?php include "footer.php";?>
</div>
<div id="myModal" class="modal fade" role="dialog">
<div class="modal-dialog" id="results">
<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header"><h5 class="modal-title" style="text-align: center; color: orange;">ADD SUBJECT</h5></div>
  <div class=""style="text-align: center; padding: 10px;">
  <form action="viewsubjects?filter=null&page=1" method="POST" class="">
    <div class="form-group" style="margin-bottom: 10px;"> 
                <select name="subject" class="chosen-select" style="">
                  <option value="" selected="">...select subject...</option>
                  <option value="MAT">Maths</option>
                  <option value="ENG">English</option>
                  <option value="KIS">Kiswahili</option>
                  <option value="SCI">Science</option>
                  <option value="SOC">Social Studies</option>
                </select>  
    </div>
   <div class="form-group" style="margin-top: 10px;"> 
                <select name="class" class="chosen-select" style="">
                  <option value="" selected="">...select class...</option>
                  <option value="C5">Class 5</option>
                  <option value="C6">Class 6</option>
                  <option value="C7">Class 7</option>
                  <option value="C8">Class 8</option>
                </select>  
    </div>
    <div style="">
    <input type="submit" value="ADD" name="next" class="btn btn-primary" style="padding-bottom: 10px;"/>
    </div>
  </form>     
</div>
      <div class="modal-footer">
        <a href="" class="btn btn-default" data-dismiss="modal" style="color: black;">Close</a>
      </div>
    </div>
  </div>
</div> 	
</body>
</html>