<?php
ob_start();
 require_once "config.php";
 require_once "session.php";
 require_once "functions.php";
 confirm_logged_in();
 $cid = $_SESSION['uid'];
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
    <script type="text/javascript" src="js/js.js"></script>			
	<title>All Customers:: MLESSON</title>
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">MLESSON</div>
</div>
<div class="row-fluid">
<div class="span2" id="accordion"><?php include "header.php";?></div>
<div class="span10">
<h5 style="color: orange; border-bottom: 1px solid black;">CUSTOMERS</h5>
  <div class="row-fluid">
  <form action="" method="GET" style="width: 100%; margin-top: 10px;">
    <div class="input-prepend"><span class="add-on" style="color: black;">Filter By</span>
    <input type="text" name="new_value" value="" placeholder="" id="new_value"/>
    <input type="submit" name="filtering" value="OK" style="margin-top: 3px;margin-left: 5px;" />
    </div>
    <a href="rpts_download.php?filter=<?php echo $filter;?>&value=<?php echo $value_term;?>" style="color: white;margin-left: 50px; margin-top: -10px;" class="btn btn-success">EXPORT CSV</a>    
   </form>
</div>
<?php 
    include "pagination.php";
    $per_page = 15;
    $page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
  if(isset($_GET['filter'])){
       $filter_term = $_GET['filter'];
       $value = $_GET['new_value'];
        switch($filter_term){
            case 'username':
            if(empty($value)) break;
             $username = $value;
             $que = mysql_query("SELECT * FROM customers WHERE level != 2 AND username = '$username'");
            break; 
            case 'null':
             $query = @mysql_query("SELECT COUNT(*) AS count FROM customers");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM customers ORDER by id DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
             }
          }
  if(is_resource($que)){
    if(mysql_num_rows($que)!=0){ ?>
<div class="row-fluid" style="background-color: #54C571; color: white; font-size: .9em;">
			<div class="span2" style="padding-left: 5px;">Customer ID</div>
			<div class="span3" style="">Name</div>
  	        <div class="span2" style="">Number</div>
            <div class="span2" style="">Balance</div>
</div>
<div style="height: 290px; overflow-y: auto; font-size: 1.0em;">
<?php     while($value=mysql_fetch_array($que)){
           if($i%2 == 0) $style="background-color:#eee;";
             else $style="background-color:#fff;";
              $id = $value['id'];
              $name = !empty($value['name'])?$value['name']: "No Name";
              $number = $value['number'];
              $balance = $value['balance'];
              ?>
<div class="row-fluid" style="<?php echo $style; ?>">
			
          	<div class="span2" style=""><?php echo $id;?></div>
          	<div class="span3" style=""><?php echo $name;?></div>
          	<div class="span2" style=""><?php echo $number;?></div>
            <div class="span2" style=""><?php echo "Ksh. ".$balance;?></div>
            
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
<?php } else { echo "Under construction..."; } 
  }
  else { echo "Under construction..."; }
?>
</div> 
</div>
<?php include "footer.php";?>
</div>
</body>
</html>