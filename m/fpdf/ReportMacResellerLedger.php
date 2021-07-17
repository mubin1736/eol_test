<?php
require('mysql_table.php');
require('connection.php');

$z_id = $_GET['id'];
$yrdata= strtotime(date("Y-m-d"));
$month = date('d F, Y', $yrdata);


$resultz = mysql_query("SELECT z.z_id, z.z_name, z.e_id, e.e_name, e.e_cont_per FROM zone AS z
						LEFT JOIN emp_info AS e
						ON e.z_id = z.z_id
						WHERE z.z_id = '$z_id'");  							
$rowz = mysql_fetch_assoc($resultz);

$e_cont_per = $rowz['e_cont_per'];
$z_name = $rowz['z_name'];
$e_name = $rowz['e_name'];

	class PDF extends PDF_MySQL_Table
		{
		function Header()
		{
			parent::Header();
			
			$this->SetFillColor(160, 160, 160);
			$this->SetTextColor(0,0,0);
			$this->SetDrawColor(8, 102, 198);
			$this->SetFont('Helvetica','',8);
			$this->SetX(2); 
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
		
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			$pdf->SetX(2);
			
			$pdf->Cell(170,6,'Zone: '.$z_name.'        Owner: '.$e_name.'         Cell: '.$e_cont_per,'LTB',0,'L',true);
			$pdf->Cell(35,6,'Date: '.$month,'TBR',0,'R',true);
			$pdf->Ln();
			$pdf->SetX(2);
			$pdf->Cell(7,6,'S/L','LTB',0,'C',true);
			$pdf->Cell(30,6,'Bill/Payment Date','LTB',0,'C',true);
			$pdf->Cell(15,6,'Time','LTB',0,'C',true);
			$pdf->Cell(20,6,'Clients','LTB',0,'C',true);
			$pdf->Cell(20,6,'Bill','LTB',0,'C',true);
			$pdf->Cell(30,6,'Opening Balance','LTB',0,'C',true);
			$pdf->Cell(20,6,'Payments','LTB',0,'C',true);
			$pdf->Cell(20,6,'Pay Mode','LTB',0,'C',true);
			$pdf->Cell(18,6,'Discount','LTB',0,'C',true);
			$pdf->Cell(25,6,'Closing Balance','LTBR',0,'R',true);
			$pdf->Ln();
			
		$result = mysql_query("SELECT a.bill_date, a.pay_time, a.totalclients, a.totalbill, a.opening_balance, a.pay_amount, a.pay_mode, a.discount, a.closing_balance FROM
								(SELECT  b.bill_date, COUNT(b.c_id) totalclients, SUM(b.bill_amount) AS totalbill, '-' AS pay_time, '-' AS pay_mode, '-' AS discount, '-' AS opening_balance, '-' AS closing_balance, '-' AS pay_amount FROM billing AS b
								LEFT JOIN clients AS c
								ON c.c_id = b.c_id
								WHERE c.mac_user = '1' AND c.z_id = '$z_id' GROUP BY MONTH(b.bill_date), YEAR(b.bill_date)
								UNION
								SELECT p.pay_date, '-', '-', p.pay_time, p.pay_mode, p.discount, p.opening_balance, p.closing_balance, p.pay_amount FROM payment_macreseller AS p
								WHERE p.z_id = '$z_id' AND p.sts = '0' GROUP BY p.pay_date, p.pay_time) AS a
								ORDER BY a.bill_date ASC");  							
		
			$x = 1;
			
		while ($row1=mysql_fetch_array($result)) {
			
				$bill_date = $row1['bill_date'];
				$pay_time = $row1['pay_time'];
				$totalclients = $row1['totalclients'];
				$totalbill = $row1['totalbill'];
				$opening_balance = $row1['opening_balance'];
				$pay_amount = $row1['pay_amount'];
				$pay_mode = $row1['pay_mode'];
				$discount = $row1['discount'];
				$closing_balance = $row1['closing_balance'];
				
				$yourtext = iconv('UTF-8', 'windows-1252', $ZoneNameBn);
				
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(7,5,$x,'LTB',0,'C',true);
				$pdf->Cell(30,5,$bill_date,'LTB',0,'L',true);
				$pdf->Cell(15,5,$pay_time,'LTB',0,'L',true);
				$pdf->Cell(20,5,$totalclients,'LTB',0,'L',true);
				$pdf->Cell(20,5,number_format($totalbill,0),'LTB',0,'C',true);
				$pdf->Cell(30,5,number_format($opening_balance,0),'LTB',0,'C',true);
				$pdf->Cell(20,5,number_format($pay_amount,0),'LTB',0,'C',true);
				$pdf->Cell(20,5,$pay_mode,'LTB',0,'L',true);
				$pdf->Cell(18,5,number_format($discount,0),'LTB',0,'C',true);
				$pdf->Cell(25,5,number_format($closing_balance,0),'LTBR',0,'R',true);
				$pdf->Ln();
			$x++;
		}
//			$pdf->SetX(2);
//			$pdf->SetFillColor(160, 160, 160);
//			$pdf->SetTextColor(0,0,0);
//			$pdf->SetDrawColor(8, 102, 198);
//			$pdf->SetFont('Helvetica','',8);
			
//			$pdf->Cell(152,6,'Total    ','LTB',0,'R',true);
//			$pdf->Cell(15,6,number_format($totalclintss,0),'LTBR',0,'C',true);
//			$pdf->Cell(38,6,number_format($totalbillamounts,0).' TK','LTBR',0,'R',true);
			
			$pdf->Ln();
			
$pdf->Output();
?>