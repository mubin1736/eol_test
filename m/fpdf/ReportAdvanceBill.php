<?php
require('mysql_table.php');
require('connection.php');

$f_date = $_REQUEST['f_date'];
$t_date = $_REQUEST['t_date'];


	class PDF extends PDF_MySQL_Table
		{
		function Header()
		{
			parent::Header();
			
			$this->SetFillColor(160, 160, 160);
			$this->SetTextColor(0,0,0);
			$this->SetDrawColor(8, 102, 198);
			$this->SetFont('Helvetica','',8);
			
			$this->Cell(10,6,'S/L','LTB',0,'C',true);
			$this->Cell(20,6,'Date','LTB',0,'L',true);
			$this->Cell(35,6,'Clint ID','LTBR',0,'C',true);
			$this->Cell(70,6,'Clint Address','LTB',0,'C',true);
			$this->Cell(20,6,'Amount','LTB',0,'C',true);
			$this->Cell(35,6,'Note','LTBR',0,'C',true);
			$this->Ln();
			
		}
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

		//Connect to database
		include("connection.php");

		$pdf=new PDF();
		$pdf->AddPage();
		
		$result = mysql_query("SELECT b.id, c.c_id, c.address, c.c_name, b.adv_date, b.amount, b.adv_dsc FROM bill_advance AS b
							LEFT JOIN clients AS c ON b.c_id = c.c_id 
							WHERE b.adv_date BETWEEN '$f_date' AND '$t_date'");
		
		$x = 1;
		$tot_bill = 0;
		while ($row=mysql_fetch_array($result)) {
			
				$amount = $row['amount'];
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				
				$pdf->Cell(10,5,$x,'LTB',0,'C',true);
				$pdf->Cell(20,5,$row['adv_date'],'LTB',0,'L',true);
				$pdf->Cell(35,5,$row['c_id'],'LTBR',0,'L',true);
				$pdf->Cell(70,5,$row['address'],'LTB',0,'L',true);
				$pdf->Cell(20,5,number_format($amount,0),'LTB',0,'R',true); 
				$pdf->Cell(35,5,$row['adv_dsc'],'LTBR',0,'L',true);
				$pdf->Ln();
				
			$x++;
			$tot_bill += $amount;

		}
			$pdf->SetX(10);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(135,6,'Total','LTB',0,'C',true);
			$pdf->Cell(20,6,number_format($tot_bill,0),'LTBR',0,'R',true);
			$pdf->Cell(35,6,'','LTBR',0,'C',true);
			
			$pdf->Ln();
			
$pdf->Output();
?>