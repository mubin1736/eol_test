<?php
require('mysql_table.php');
require('connection.php');
require('Function.php');

$date = date('Y-m-d');

$t_id = $_REQUEST['t_id'];

$year = $_REQUEST['year'];
$month = $_REQUEST['month'];
$new_date = $year.'-'.$month.'-01';
$z_id = $_REQUEST['z_id'];
		
class PDF extends PDF_MySQL_Table
{

}
//Connect to database
include("connection.php");

$pdf=new PDF();
$pdf->AddPage();


//$str = iconv('UTF-8', 'windows-1252', '??? ??? ????');
//$pdf->Write(10,$str);
$cityName = '';
$converted_cityName = iconv('UTF-8', 'ASCII//TRANSLIT', $cityName);
		$pdf->SetX(5);
		$pdf->SetDrawColor(8, 102, 198);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0,0,160);
		$pdf->SetFont('Helvetica','',10);
		$pdf->SetLineWidth(.1);			
		$pdf->Cell(67,5,$converted_cityName,'0',0,'L',false);

$pdf->Output();
?>