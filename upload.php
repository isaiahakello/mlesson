<?php
require "config.php";
if(isset($_FILES["file"])){
    $lecture = $_POST['lecture'];
    $output_dir = 'lectures/'.$lecture;
    if(!is_dir($output_dir)){
         mkdir($output_dir);
        }
	$ret = array();
	$error =$_FILES["file"]["error"];
	if(!is_array($_FILES["file"]["name"])){ //single file
 	 	$fileName = $_FILES["file"]["name"];
 		move_uploaded_file($_FILES["file"]["tmp_name"],$output_dir.'/'.$fileName);
    	$ret[]= $fileName;
	}
	else  //Multiple files, file[]
	{
	  $fileCount = count($_FILES["file"]["name"]);
	  for($i=0; $i < $fileCount; $i++)
	  {
	  	$fileName = $_FILES["file"]["name"][$i];
		move_uploaded_file($_FILES["file"]["tmp_name"][$i],$output_dir.'/'.$fileName);
	  	$ret[]= $fileName;
	  }
	
	}
    $que = @mysql_query("INSERT INTO broadcasts (lecture) VALUES ('$lecture')") or die(mysql_error());
    echo json_encode($ret);
 }
 ?>