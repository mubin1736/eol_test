<?php
require('mysql_table.php');
require('connection.php');

$z_id = $_REQUEST['z_id'];
$f_date = $_REQUEST['f_date'];
$t_date = $_REQUEST['t_date'];

class PDF extends PDF_MySQL_Table
{
/*
function Header()
{
	//Title
	$this->Image('logo.png',4,0,35);
	$this->SetFont('Helvetica','',12);
	$this->SetDrawColor(8, 102, 198);
	$this->SetLineWidth(.1);
	$this->Cell(0,10,'Expert Online Bill Vs Payment Report','B',1,'C');
	
	$this->Ln(5);
	//Ensure table header is output
	parent::Header();
	
}
*/
function Footer()
		function Footer()
				{
					global $comp_name;
					//Position at 1.5 cm from bottom
					$this->SetY(-15);
					//Arial italic 8
					$this->SetFont('Helvetica','I',8);
					//Page number
					$this->Cell(0,10,'Page '.$this->PageNo().'/'.$comp_name,'T',0,'C');
					$this->SetDrawColor(8, 102, 198);
					parent::Footer();
				}
}

$pdf=new PDF();
$pdf->AddPage();

if($z_id == 'all'){
		
			$sql = mysql_query("SELECT SUM(t.bill_amount) AS bill, SUM(t.pay) AS payment, SUM(t.adv_bill) AS advanced, SUM(t.dic) AS dicount FROM
								(
								SELECT b.id, b.c_id, c.c_name, b.bill_date, b.adv_bill, b.bill_amount, SUM(b.pay_amount) AS pay, SUM(b.bill_discount) AS dic  
								FROM billing AS b
								LEFT JOIN clients AS c ON c.c_id = b.c_id
								WHERE MONTH(b.bill_date) = MONTH('$f_date') AND b.p_id != '13'
								GROUP BY b.id ORDER BY pay
								)t");
			$row = mysql_fetch_array($sql);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(255, 255, 255);
			$pdf->SetXY(7.5, 2);
			$pdf->SetFont('Helvetica','B',7);
			$pdf->Cell(65,5,'Date: '.$f_date. ' To '. $t_date,'TLB',0,'L',false);
			$pdf->Cell(65,5,'All Zone','TLB',0,'C',false);
			$pdf->Cell(65,5,'Bill Vs Payment','TLB',0,'R',false);
			$pdf->Ln();	
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			$pdf->SetXY(7.5, 8);
			$pdf->Cell(95,5,'Total ','TLB',0,'C',true);
			$pdf->Cell(20,5,number_format($row['bill'],0),'TLB',0,'R',true);
			$pdf->Cell(20,5,number_format($row['payment'],0),'TLB',0,'R',true);
			$pdf->Cell(20,5,number_format($row['advanced'],0),'TLB',0,'R',true);
			$pdf->Cell(20,5,number_format($row['dicount'],0),'TLB',0,'R',true);
			$pdf->Cell(20,5,number_format(($row['bill'] - ($row['payment'] + $row['dicount'])),0),'TLBR',0,'R',true);
			
			$pdf->SetXY(30, 13);
			$pdf->AddCol('id',10,'Bill Id','L');
			$pdf->AddCol('c_id',20,'Clint ID','L');
			$pdf->AddCol('address',45,'Clint Address','L');
			$pdf->AddCol('cell',20,'Cell No','L');
			$pdf->AddCol('bill',20,'Bill Amount','R');
			$pdf->AddCol('pay',20,'Payment','R');
			$pdf->AddCol('adv',20,'Advanced','R');
			$pdf->AddCol('disc',20,'Discount','R');
			$pdf->AddCol('dew',20,'Deu','R');


			$prop=array('HeaderColor'=>array(160, 160, 160),
						'padding'=>1);
						
			$pdf->Table("SELECT t.id, t.c_id, t.c_name, t.address, t.cell, t.bill_date, ROUND(t.adv_bill) AS adv, ROUND(t.bill_amount) AS bill, ROUND(t.pay) AS pay, 
						ROUND(t.dic) AS disc, ROUND((t.bill_amount - (t.pay + t.dic))) AS dew FROM
						(
						SELECT b.id, b.c_id, c.c_name, c.address, c.cell, b.bill_date, b.adv_bill, b.bill_amount, SUM(b.pay_amount) AS pay, SUM(b.bill_discount) AS dic 
						FROM billing AS b
						LEFT JOIN clients AS c ON c.c_id = b.c_id
						WHERE MONTH(b.bill_date) = MONTH('$f_date') AND b.p_id != '13'
						GROUP BY b.id ORDER BY c.address
						)t",$prop);
}
else{
				$sqla = mysql_query("SELECT z_name FROM zone WHERE z_id = '$z_id'");
				$rs = mysql_fetch_array($sqla);
				$sql = mysql_query("SELECT SUM(t.bill_amount) AS bill, SUM(t.pay) AS payment, SUM(t.adv_bill) AS advanced, SUM(t.dic) AS dicount FROM
								(
								SELECT b.id, b.c_id, c.c_name, b.bill_date, b.adv_bill, b.bill_amount, SUM(b.pay_amount) AS pay, SUM(b.bill_discount) AS dic  
								FROM billing AS b
								LEFT JOIN clients AS c ON c.c_id = b.c_id
								WHERE MONTH(b.bill_date) = MONTH('$f_date') AND c.z_id = '$z_id' AND b.p_id != '13'
								GROUP BY b.id ORDER BY pay
								)t");
								
			$row = mysql_fetch_array($sql);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(255, 255, 255);
			$pdf->SetXY(7.5, 2);
			$pdf->SetFont('Helvetica','B',7);
			$pdf->Cell(65,5,'Date: '.$f_date. ' To '. $t_date,'TLB',0,'L',false);
			$pdf->Cell(65,5,'Zone: '.$rs['z_name'],'TLB',0,'C',false);
			$pdf->Cell(65,5,'Bill Vs Payment','TLB',0,'R',false);
			$pdf->Ln();	
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			$pdf->SetXY(7.5, 8);
			$pdf->Cell(95,5,'Total ','TLB',0,'C',true);
			$pdf->Cell(20,5,number_format($row['bill'],0),'TLB',0,'R',true);
			$pdf->Cell(20,5,number_format($row['payment'],0),'TLB',0,'R',true);
			$pdf->Cell(20,5,number_format($row['advanced'],0),'TLB',0,'R',true);
			$pdf->Cell(20,5,number_format($row['dicount'],0),'TLB',0,'R',true);
			$pdf->Cell(20,5,number_format(($row['bill'] - ($row['payment'] + $row['dicount'])),0),'TLBR',0,'R',true);
			
			$pdf->SetXY(30, 13);
			$pdf->AddCol('id',10,'Bill Id','L');
			$pdf->AddCol('c_id',20,'Clint ID','L');
			$pdf->AddCol('address',45,'Clint Address','L');
			$pdf->AddCol('cell',20,'Cell No','L');
			$pdf->AddCol('bill',20,'Bill Amount','R');
			$pdf->AddCol('pay',20,'Payment','R');
			$pdf->AddCol('adv',20,'Advanced','R');
			$pdf->AddCol('disc',20,'Discount','R');
			$pdf->AddCol('dew',20,'Deu','R');


			$prop=array('HeaderColor'=>array(160, 160, 160),
						'padding'=>1);
						
			$pdf->Table("SELECT t.id, t.c_id, t.c_name, t.address, t.cell, t.bill_date, ROUND(t.adv_bill) AS adv, ROUND(t.bill_amount) AS bill, ROUND(t.pay) AS pay, 
						ROUND(t.dic) AS disc, ROUND((t.bill_amount - (t.pay + t.dic))) AS dew FROM
						(
						SELECT b.id, b.c_id, c.c_name, c.address, c.cell, b.bill_date, b.adv_bill, b.bill_amount, SUM(b.pay_amount) AS pay, SUM(b.bill_discount) AS dic 
						FROM billing AS b
						LEFT JOIN clients AS c ON c.c_id = b.c_id
						WHERE MONTH(b.bill_date) = MONTH('$f_date') AND c.z_id = '$z_id' AND b.p_id != '13'
						GROUP BY b.id ORDER BY c.address
						)t",$prop);
}	
	
$pdf->Output();
?>