<?php
ob_start();
set_time_limit(0);
require('fpdf.php');
require "config.php";
class PDF extends FPDF {
function Header()
{
    // Select Arial bold 15
    $this->SetFont('Times','B',15);
    // Move to the right
    //$this->Cell(80);
    $this->Ln(10);
}
function Footer(){
    // Go to 1.5 cm from bottom
    $this->SetY(-15);
    // Select Arial italic 8
    $this->SetFont('Arial','I',8);
    // Print centered page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}
function FancyTable($header){
    // Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Header
    $w = array(30, 30, 20, 20, 30, 30);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Data
    $fill = false;
  $value= isset($_GET['value'])? $_GET['value']:"";
  if(isset($_GET['filter'])){     
       switch($_GET['filter']){
            case 'class':
            if(empty($value)) break;           
             $class = $value;
             $que = mysql_query("SELECT * FROM subscribers WHERE class LIKE = '%$class%' ORDER by id ASC");
            break;
            case 'number':
            if(empty($value)) break;           
             $number = $value;
             $que = mysql_query("SELECT * FROM subscribers WHERE number LIKE = '%$number%' ORDER by id ASC");
            break;
            case 'subject':
            if(empty($value)) break;           
             $subject = $value;
             $que = mysql_query("SELECT * FROM subscribers WHERE subject LIKE '%$subject%' ORDER by id ASC");
            break;
            case 'code':
            if(empty($value)) break;
             $code = $value;
             $que = mysql_query("SELECT * FROM subscribers WHERE code LIKE '%$code%' ORDER by id ASC");
            break;
             case 'status':
            if(empty($value)) break;
             $status = $value;
             $que = mysql_query("SELECT * FROM subscribers WHERE status = '$status' ORDER by id ASC");
            break;
            case 'null':
             $que = mysql_query("SELECT * FROM subscribers ORDER by id ASC");
            break;
             }
          }
  while($value=mysql_fetch_array($que)){
          $number = $value['number'];
          $subject = $value['subject'];
          $code = $value['code'];
          $class = $value['class'];
          $subid = $value['sub_id'];
          $status = $value['status'];
          $start = date('d-M-y',strtotime($value['date_']));          
        $this->Cell($w[0],6,$number,'LR',0,'L',$fill);
        $this->Cell($w[1],6,$subid,'LR',0,'L',$fill);
        $this->Cell($w[2],6,$code,'LR',0,'L',$fill);
        $this->Cell($w[3],6,$class,'LR',0,'L',$fill);
        $this->Cell($w[4],6,$subject,'LR',0,'R',$fill);
        $this->Cell($w[5],6,$start,'LR',0,'R',$fill);
        $this->Ln();
        $fill = !$fill;
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
  }
}
$pdf = new PDF();
$header = array("Number","Sub ID","Code","Subject","Class","Date Subscribed");
// Data loading
$pdf->SetFont('Times','',10);
$pdf->SetTitle('M-Lesson');
$pdf->SetAuthor('moses pandi');
$pdf->SetSubject('mlesson');
$pdf->SetKeywords('subscribers, SMS, pupils');
$pdf->AddPage();
$pdf->SetFont('Times','',10);

$pdf->FancyTable($header);
$pdf->Output('D','subscribers.pdf');  
?>