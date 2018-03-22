<?php
namespace Chirp;
session_start();
$filter = $_GET['filter'];
  $value= isset($_GET['value'])? $_GET['value']:"";
  if($filter == 'null'){
    $filename = 'interaction';
  }
  else{
    $filename = "interaction_".$filter."_".$value;
  }
$data = $_SESSION['data_array'];
$colnames = array("Subject Code","Class","Subject","Active Subscribers","Cancelled Subscribers","Time Period New Subscribers","Time Period New Cancelled","Time Period Questions Sent","Time Period Responses Received","Time Period Response Rate","Time Period Correct Answers","Time Period Correct %");
  function map_colnames($input){
    global $colnames;
    return isset($colnames[$input]) ? $colnames[$input] : $input;
  }

  function cleanData(&$str)
  {
    if($str == 't') $str = 'TRUE';
    if($str == 'f') $str = 'FALSE';
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }
  // filename for download
  $filename = $filename.".csv";
  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: text/csv");
  $out = fopen("php://output", 'w');
  $flag = false;
  foreach($data as $row) {
    if(!$flag) {
      // display field/column names as first row
      $firstline = array_map(__NAMESPACE__ . '\map_colnames', array_keys($row));
      fputcsv($out, $firstline, ',', '"');
      $flag = true;
    }
    array_walk($row, __NAMESPACE__ . '\cleanData');
    fputcsv($out, array_values($row), ',', '"');
  }

  fclose($out);
  exit;
?>
