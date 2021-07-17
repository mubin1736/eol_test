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

if($z_id == 'all'){
	$result = mysql_query("SELECT b.id, c.c_id, c.c_name, z.z_name, c.address, c.cell, b.bill_date, p.p_name, b.p_price, b.discount, b.bill_amount FROM billing AS b
						LEFT JOIN package AS p ON p.p_id = b.p_id
						LEFT JOIN clients AS c ON b.c_id = c.c_id
						LEFT JOIN zone AS z ON z.z_id = c.z_id
						WHERE DATE_FORMAT(b.bill_date, '%Y-%m') = DATE_FORMAT('$new_date', '%Y-%m')");
						
	while ($row=mysql_fetch_array($result)) {					
		$inWord = floatval($row['bill_amount']);
		$pdf->SetX(5);
		$pdf->SetDrawColor(8, 102, 198);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0,0,160);
		$pdf->SetFont('Helvetica','',10);
		$pdf->SetLineWidth(.1);			
		$pdf->Cell(67,5,'CLIENT DETAILS','0',0,'L',false);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(66,5,'Invoice No: INV-'.$row['id'],'0',0,'C',false);
		$pdf->SetFont('Helvetica','',10);
		$pdf->SetTextColor(0,0,160);
		$pdf->Cell(67,5,'ISP Solution','0',0,'R',false);
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(67,5,'Client ID: '.$row['c_id'],'0',0,'L',false);
		$pdf->Cell(66,5,'Issue Date: '.$date,'0',0,'C',false);
		$pdf->Cell(67,5,'Uttara, Dhaka','0',0,'R',false);
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(67,5,'Zone: '.$row['z_name'],'0',0,'L',false);
		$pdf->Cell(66,5,'Due Date: '.$row['bill_date'],'0',0,'C',false);
		$pdf->Cell(67,5,'01711111110','0',0,'R',false);
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(67,5,'Address: '.$row['address'],'0',0,'L',false);
		$pdf->Cell(66,5,'','0',0,'C',false);
		$pdf->Cell(67,5,'','0',0,'R',false);
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(67,5,'Mobile: '.$row['cell'],'0',0,'L',false);
		$pdf->Cell(66,5,'','0',0,'C',false);
		$pdf->Cell(67,5,'','0',0,'R',false);
		
		
		
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(140,5,'Details','LTRB',0,'C',true);
		$pdf->Cell(60,5,'Amount','TRB',0,'C',true);
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(140,5,$row['p_name'],'LRB',0,'L',true);
		$pdf->Cell(60,5,$row['p_price'],'RB',0,'R',true);
		
		if($row['adv_bill'] != '0.00'){
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(140,5,'Advanced','LRB',0,'L',true);
		$pdf->Cell(60,5,$row['adv_bill'],'RB',0,'R',true);
		}
		if($row['discount'] != '0.00'){
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(140,5,'Discount','LRB',0,'L',true);
		$pdf->Cell(60,5,$row['discount'],'RB',0,'R',true);
		}
		
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(140,5,'Total','LRB',0,'L',true);
		$pdf->Cell(60,5,$row['bill_amount'],'RB',0,'R',true);
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','B',8);
		$pdf->Cell(200,5,'In Words : '.convert_number_to_words($inWord).' Taka Only','LRB',0,'L',true);
		
				
		
		$pdf->Ln(10);
		$pdf->SetX(5);
		$pdf->SetDrawColor(255,0,0);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(100,5,'* Sign up charge can not be return.','0',0,'L',false);
		$pdf->Cell(100,5,'Sister Concern','0',0,'R',false);
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetDrawColor(255,0,0);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(100,5,'* Please pay your bill first week of every month.','0',0,'L',false);
		$pdf->Cell(100,5,'Walletmix Limted.','0',0,'R',false);
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetDrawColor(255,0,0);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(100,5,'* If you close your connection please inform before one month.','0',0,'L',false);
		$pdf->Cell(100,5,'Secure Online Payment Gateway','0',0,'R',false);
		
		$pdf->Ln();
		$pdf->SetX(0);
		$pdf->SetDrawColor(255,0,0);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(210,5,'','B',0,'L',true);
		
		$pdf->Ln(20);
		
	
	}
}
else{
	$result = mysql_query("SELECT b.id, c.c_id, c.c_name, z.z_name, c.address, c.cell, b.bill_date, p.p_name, b.p_price, b.discount, b.bill_amount FROM billing AS b
						LEFT JOIN package AS p ON p.p_id = b.p_id
						LEFT JOIN clients AS c ON b.c_id = c.c_id
						LEFT JOIN zone AS z ON z.z_id = c.z_id
						WHERE DATE_FORMAT(b.bill_date, '%Y-%m') = DATE_FORMAT('$new_date', '%Y-%m') AND c.z_id = '$z_id'");
						
	while ($row=mysql_fetch_array($result)) {					
		$inWord = floatval($row['bill_amount']);
		$pdf->SetX(5);
		$pdf->SetDrawColor(8, 102, 198);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0,0,160);
		$pdf->SetFont('Helvetica','',10);
		$pdf->SetLineWidth(.1);			
		$pdf->Cell(67,5,'CLIENT DETAILS','0',0,'L',false);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(66,5,'Invoice No: INV-'.$row['id'],'0',0,'C',false);
		$pdf->SetFont('Helvetica','',10);
		$pdf->Cell(67,5,'ISP Solution','0',0,'R',false);
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(67,5,'Client ID: '.$row['c_id'],'0',0,'L',false);
		$pdf->Cell(66,5,'Issue Date: '.$date,'0',0,'C',false);
		$pdf->Cell(67,5,'Uttara, Dhaka','0',0,'R',false);
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(67,5,'Zone: '.$row['z_name'],'0',0,'L',false);
		$pdf->Cell(66,5,'Due Date: '.$row['bill_date'],'0',0,'C',false);
		$pdf->Cell(67,5,'01711111110','0',0,'R',false);
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(67,5,'Address: '.$row['address'],'0',0,'L',false);
		$pdf->Cell(66,5,'','0',0,'C',false);
		$pdf->Cell(67,5,'','0',0,'R',false);
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(67,5,'Mobile: '.$row['cell'],'0',0,'L',false);
		$pdf->Cell(66,5,'','0',0,'C',false);
		$pdf->Cell(67,5,'','0',0,'R',false);
		
		
		
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(140,5,'Details','LTRB',0,'C',true);
		$pdf->Cell(60,5,'Amount','TRB',0,'C',true);
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(140,5,$row['p_name'],'LRB',0,'L',true);
		$pdf->Cell(60,5,$row['p_price'],'RB',0,'R',true);
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(140,5,'Discount','LRB',0,'L',true);
		$pdf->Cell(60,5,$row['discount'],'RB',0,'R',true);
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(140,5,'Total','LRB',0,'L',true);
		$pdf->Cell(60,5,$row['bill_amount'],'RB',0,'R',true);
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','B',8);
		$pdf->Cell(200,5,'In Words : '.convert_number_to_words($inWord).' Taka Only','LRB',0,'L',true);
		
				
		
		$pdf->Ln(10);
		$pdf->SetX(5);
		$pdf->SetDrawColor(255,0,0);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(100,5,'* Sign up charge can not be return.','0',0,'L',false);
		$pdf->Cell(100,5,'Sister Concern','0',0,'R',false);
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetDrawColor(255,0,0);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(100,5,'* Please pay your bill first week of every month.','0',0,'L',false);
		$pdf->Cell(100,5,'Walletmix Limted.','0',0,'R',false);
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetDrawColor(255,0,0);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(100,5,'* If you close your connection please inform before one month.','0',0,'L',false);
		$pdf->Cell(100,5,'Secure Online Payment Gateway','0',0,'R',false);
		$pdf->Ln();
		$pdf->SetX(0);
		$pdf->SetDrawColor(255,0,0);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(210,5,'','B',0,'L',true);
		
		$pdf->Ln(20);
		
	
	}
}
$pdf->Output();
?>