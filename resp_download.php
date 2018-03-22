<?php
ob_start();
  $filter = $_GET['filter'];
  $value= isset($_GET['value'])? $_GET['value']:"";
  if($filter == 'null'){
    $filename = 'All responses.xlsx';
  }
  else{
    $filename = $value."_responses.xlsx";
  }
if (isset($filter)){
    require_once 'classes/PHPExcel.php';
     //$db = new mysqli('localhost', 'root', '', 'mlesson'); //structure: localhost,username,password,database
     $db = new mysqli('localhost', 'mlesson_applic', '@T7r3_1DVf*0', 'mlesson_application'); //structure: localhost,username,password,database
    if ($db->connect_error) {
	   $error = $db->connect_error;
        }
    else{
     switch($filter){
        case 'null':
          $sql = "SELECT number,sub_id,subject,question_id,time_,response,correct_response FROM responses ORDER BY id ASC";
        break;
        case 'subject': 
         $subject = substr(strtoupper($value),0,3).'1';           
         $sql = "SELECT number,sub_id,subject,question_id,time_,response,correct_response FROM responses WHERE subject LIKE '%$subject' ORDER BY id ASC";
        break;
        }
    $result = $db->query($sql);
	if ($db->error) {
		$error = $db->error;
        echo $error;
	  }
     } 	  
    function getRow($result) {
	  return $result->fetch_assoc();
    }
	try {
		$sheet = new PHPExcel();
		// Set metadata
		$sheet->getProperties()->setCreator('www.mlesson.com')
		                       ->setLastModifiedBy('www.mlesson.com')
		                       ->setTitle('Reports')
		                       ->setKeywords('mlesson reports uploads');
		
		// Set default settings
		$sheet->getDefaultStyle()->getAlignment()->setVertical(
	            PHPExcel_Style_Alignment::VERTICAL_TOP);
		$sheet->getDefaultStyle()->getAlignment()->setHorizontal(
				PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->getDefaultStyle()->getFont()->setName('Times New Roman');
		$sheet->getDefaultStyle()->getFont()->setSize(12);
		
		// Get reference to active spreadsheet in workbook
		$sheet->setActiveSheetIndex(0);
		$activeSheet = $sheet->getActiveSheet();
		
		// Set print options
		$activeSheet->getPageSetup()->setOrientation(
	            PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE)
	            ->setFitToWidth(1)
	            ->setFitToHeight(0);
		
		$activeSheet->getHeaderFooter()->setOddHeader('&C&B&16' . 
                $sheet->getProperties()->getTitle())
                ->setOddFooter('&CPage &P of &N');
		
		// Populate with data
		$colHeaders = array("Number","Sub ID","Question ID","Subject Code","Date","Response","Correct Answer");
		$col = 'A';
		$rownum = 1;
		
		// Set column headings
		foreach ($colHeaders as $header) {
			$activeSheet->setCellValue($col . $rownum, $header);
			$activeSheet->getStyle($col . $rownum)->getFont()->setBold(true);
			if ($col == 'B') {
				$activeSheet->getColumnDimension($col)->setWidth(30);
			} else {
				$activeSheet->getColumnDimension($col)->setAutoSize(true);
			}
			$col++;
		}
		
		// Populate individual cells with data
		//$row = getRow($result);
		while($row = getRow($result)){
			$col = 'A';
			$rownum++;
			foreach ($row as $value) {
				$activeSheet->setCellValue($col++ .$rownum,$value);
			}
		} 
		
		// Format individual columns
        $activeSheet->getStyle('A2:A' . $rownum)->getAlignment()
		    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$activeSheet->getStyle('B2:B' . $rownum)->getAlignment()
		    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$activeSheet->getStyle('C2:C' . $rownum)->getAlignment()
		    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
     	$activeSheet->getStyle('D2:D' . $rownum)->getAlignment()
		    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
     	$activeSheet->getStyle('E2:E' . $rownum)->getAlignment()
		    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
     	$activeSheet->getStyle('F2:F' . $rownum)->getAlignment()
		    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		// Give spreadsheet a title
		$activeSheet->setTitle('Subscribers');
		
		// Generate Excel file and download
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=$filename");
		header('Cache-Control: max-age=0');
		
		$writer = PHPExcel_IOFactory::createWriter($sheet, 'Excel2007');
		/**function SaveViaTempFile($writer){
           $filePath = 'images' . rand(0, getrandmax()) . rand(0, getrandmax()) . ".tmp";
           $writer->save($filePath);
           readfile($filePath);
           unlink($filePath);
           } ****/
		$writer->save('php://output');
         //SaveViaTempFile($writer);
		exit;
		
	} catch (Exception $e) {
        echo $e->getMessage();
	}
}
?>
