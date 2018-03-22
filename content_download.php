<?php
ob_start();
  $filter = $_GET['filter'];
  $value= isset($_GET['value'])? $_GET['value']:"";
  if($filter == 'null'){
    $filename = 'Content.xlsx';
  }
  else{
    $filename = $value.".xlsx";
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
          $sql = "SELECT subject,question,question_date,question_text,answer_a,answer_b,answer_c,answer_d,correct_answer,correct_text,incorrect_text,start_date,end_date FROM uploadcontent ORDER BY id ASC";
        break;
        case 'id':
         $sql = "SELECT subject,question,question_date,question_text,answer_a,answer_b,answer_c,answer_d,correct_answer,correct_text,incorrect_text,start_date,end_date FROM uploadcontent WHERE question LIKE '%$value%' ORDER BY id ASC";
        break;
        case 'subject': 
         $subject = strtoupper(substr($value,0,3)).'1';           
         $sql = "SELECT subject,question,question_date,question_text,answer_a,answer_b,answer_c,answer_d,correct_answer,correct_text,incorrect_text,start_date,end_date FROM uploadcontent WHERE subject LIKE '%$subject' ORDER BY id ASC";
        break;
        case 'class':
         $sql = "SELECT number,sub_id,code,subject,class,date_ FROM subscribers WHERE class LIKE '%$value%' ORDER BY id ASC";
        break;
        case 'quizdate':
         $sql = "SELECT subject,question,question_date,question_text,answer_a,answer_b,answer_c,answer_d,correct_answer,correct_text,incorrect_text,start_date,end_date FROM uploadcontent WHERE DATE(question_date) = '$value' ORDER BY id ASC";
        break;
        case 'start':
         $sql = "SELECT subject,question,question_date,question_text,answer_a,answer_b,answer_c,answer_d,correct_answer,correct_text,incorrect_text,start_date,end_date FROM uploadcontent WHERE DATE(start_date) = '$value' ORDER BY id ASC";
        break;
        case 'end':
         $sql = "SELECT subject,question,question_date,question_text,answer_a,answer_b,answer_c,answer_d,correct_answer,correct_text,incorrect_text,start_date,end_date FROM uploadcontent WHERE DATE(end_date) = '$value' ORDER BY id ASC";
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
		$colHeaders = array("Code","Question ID","Question Date","Question","Answer A","Answer B","Answer C","Answer D","Correct Answer","Correct Answer Text","Incorrect Answer Text","Start Date","End Date");
		$col = 'A';
		$rownum = 1;
		
		// Set column headings
		foreach ($colHeaders as $header) {
			$activeSheet->setCellValue($col . $rownum, $header);
			$activeSheet->getStyle($col . $rownum)->getFont()->setBold(true);
            if ($col == 'D') {
				$activeSheet->getColumnDimension($col)->setWidth(60);
                
			} 
            elseif($col == 'J') {
				$activeSheet->getColumnDimension($col)->setWidth(60);
                
			}
            elseif($col == 'K') {
				$activeSheet->getColumnDimension($col)->setWidth(60);
			}else {
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
        $activeSheet->getStyle('D2:D' . $rownum)->getAlignment()
		    ->setWrapText(true);
     	$activeSheet->getStyle('E2:E' . $rownum)->getAlignment()
		    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
     	$activeSheet->getStyle('F2:F' . $rownum)->getAlignment()
		    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $activeSheet->getStyle('G2:G' . $rownum)->getAlignment()
		    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $activeSheet->getStyle('H2:H' . $rownum)->getAlignment()
		    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $activeSheet->getStyle('I2:I' . $rownum)->getAlignment()
		    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $activeSheet->getStyle('J2:J' . $rownum)->getAlignment()
		    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $activeSheet->getStyle('J2:J'.$rownum)->getAlignment()
		    ->setWrapText(true);
        $activeSheet->getStyle('K2:K' . $rownum)->getAlignment()
		    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $activeSheet->getStyle('L2:L' . $rownum)->getAlignment()
		    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $activeSheet->getStyle('M2:M' . $rownum)->getAlignment()
		    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		// Give spreadsheet a title
		$activeSheet->setTitle('COntent');
		
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
