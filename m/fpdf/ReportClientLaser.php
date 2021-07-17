<?php
require('mysql_table.php');
require('connection.php');

$z_id = $_REQUEST['z_id'];
$f_date = $_REQUEST['f_date'];
$t_date = $_REQUEST['t_date'];

if($z_id == 'all'){
	class PDF extends PDF_MySQL_Table
		{
		function Header()
		{
			parent::Header();
			
			$this->SetFillColor(160, 160, 160);
			$this->SetTextColor(0,0,0);
			$this->SetDrawColor(8, 102, 198);
			$this->SetFont('Helvetica','B',8);
			$this->SetX(2);
			
			$this->Cell(8,6,'S/L','LTB',0,'L',true);
			$this->Cell(17,6,'ID','LTB',0,'L',true);
			$this->Cell(65,6,'Clint Address','LTBR',0,'C',true);
			$this->Cell(23,6,'Cell No','LTB',0,'C',true);
			$this->Cell(10,6,'Act Bill','LTB',0,'C',true); 
			$this->Cell(12,6,'P.Dis','LTB',0,'C',true);
			$this->Cell(12,6,'Due','LTB',0,'C',true);
			$this->Cell(12,6,'Adv','LTB',0,'C',true);
			$this->Cell(14,6,'Tot Bill','LTB',0,'C',true);
			$this->Cell(12,6,'Dis','LTB',0,'C',true);
			$this->Cell(14,6,'Paid','LTB',0,'C',true);
			$this->Cell(13,6,'Balance','LTB',0,'C',true);
			$this->Cell(81,6,'Note','LTBR',0,'C',true); 
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
		$pdf->AddPage('L', 'A4');
										
		$result = mysql_query("SELECT t1.c_id, t1.address, t1.cell, t1.discount AS diss, t1.note, t1.p_price, t2.dis, t2.bill, t3.bill_disc, t3.pay FROM
								(
								SELECT c.c_id, c.address, c.cell, c.p_id, p.p_price, c.discount, c.note FROM clients AS c 
								LEFT JOIN package AS p ON c.p_id = p.p_id
								WHERE c.sts = 0 AND c.mac_user = '0'
								)t1
								LEFT JOIN
								(
								SELECT b.c_id, SUM(b.bill_amount) AS bill, SUM(b.discount) AS dis FROM billing AS b
								WHERE MONTH(b.bill_date) = MONTH('$f_date')
								GROUP BY b.c_id
								)t2
								ON t1.c_id = t2.c_id
								LEFT JOIN
								(
								SELECT p.c_id, SUM(p.pay_amount) AS pay, SUM(p.bill_discount) AS bill_disc FROM payment AS p 
								WHERE p.pay_date BETWEEN '$f_date' AND '$t_date'
								GROUP BY p.c_id
								)t3
								ON t1.c_id = t3.c_id ORDER BY t1.address");  							
		
			$ActualBill = 0;
			$DewsBill = 0;
			$AdvanceBill = 0;
			$TotalBill = 0;
			$BillDiscount = 0;
			$TotalPayment = 0;
			$Balance = 0;
			$disss = 0;
			$x=1;
		while ($row=mysql_fetch_array($result)) {
				$date = $row['pay_date'];
				$c_id = $row['c_id'];
				$address = $row['address'];
				$cell = $row['cell'];
				$p_price = $row['p_price'];
				$discount = $row['dis'];
				$bill = $row['bill'];
				$bill_disc = $row['bill_disc'];
				$pay = $row['pay'];
				$diss = $row['diss'];
				$note = $row['note'];
				
				$balance = $bill - ($bill_disc + $pay);
				
				$results = mysql_query("SELECT t2.c_id, t2.dis, t2.bill, t3.bill_disc, t3.pay FROM
										(
										SELECT b.c_id, SUM(b.bill_amount) AS bill, SUM(b.discount) AS dis FROM billing AS b
										WHERE b.bill_date < '$f_date' AND b.c_id = '$c_id'
										GROUP BY b.c_id
										)t2
										LEFT JOIN
										(
										SELECT p.c_id, SUM(p.pay_amount) AS pay, SUM(p.bill_discount) AS bill_disc FROM payment AS p 
										WHERE p.pay_date < '$f_date' AND p.c_id = '$c_id'
										GROUP BY p.c_id
										)t3
										ON t2.c_id = t3.c_id");
				$rows = mysql_fetch_array($results);					
				$dew = $rows['bill'] - ($rows['bill_disc'] + $rows['pay']);
				
				if($dew < 0){
					$dews = 0;
					$advs = abs($dew);
				}else{
					$dews = $dew;
					$advs = 0;
				}
				
				$total_bill = ($bill + $dews)-$advs;
				$total_payable = $balance + $dew;
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(8,5,$x,'LTB',0,'L',true);
				$pdf->Cell(17,5,$c_id,'LTB',0,'L',true);
				$pdf->Cell(65,5,$address,'LTBR',0,'L',true);
				$pdf->Cell(23,5,$cell,'LTB',0,'L',true);
				$pdf->Cell(10,5,number_format($p_price,0),'LTB',0,'R',true); 
				$pdf->Cell(12,5,number_format($diss,0),'LTB',0,'R',true); 
				$pdf->Cell(12,5,number_format($dews,0),'LTB',0,'R',true);
				$pdf->Cell(12,5,number_format($advs,0),'LTB',0,'R',true);
				$pdf->Cell(14,5,number_format($total_bill,0),'LTB',0,'R',true);
				$pdf->Cell(12,5,number_format($bill_disc,0),'LTB',0,'R',true);
				$pdf->Cell(14,5,number_format($pay,0),'LTB',0,'R',true);
				$pdf->Cell(13,5,number_format($total_payable,0),'LTB',0,'R',true);
				$pdf->Cell(81,5,$note,'LTBR',0,'L',true);
				$pdf->Ln();
				
			$ActualBill += $p_price;
			$DewsBill += $dews;
			$AdvanceBill += $advs;
			$TotalBill += $total_bill;
			$BillDiscount += $bill_disc;
			$TotalPayment += $pay;
			$Balance += $total_payable;
			$disss += $diss;
			$x++;
		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','B',8);
			
			$pdf->Cell(90,6,'Total','LTB',0,'C',true);
			$pdf->Cell(33,6,number_format($ActualBill,0),'TB',0,'R',true); 
			$pdf->Cell(12,6,number_format($disss,0),'LTB',0,'R',true); 
			$pdf->Cell(12,6,number_format($DewsBill,0),'LTB',0,'R',true);
			$pdf->Cell(12,6,number_format($AdvanceBill,0),'LTB',0,'R',true);
			$pdf->Cell(14,6,number_format($TotalBill,0),'LTBR',0,'R',true);
			$pdf->Cell(12,6,number_format($BillDiscount,0),'LTB',0,'R',true);
			$pdf->Cell(14,6,number_format($TotalPayment,0),'LTBR',0,'R',true);
			$pdf->Cell(13,6,number_format($Balance,0),'LTBR',0,'R',true);
			$pdf->Cell(81,6,'','LTBR',0,'L',true);
			$pdf->Ln();
			
		
}
else{
		class PDF extends PDF_MySQL_Table
		{
		function Header()
		{
			parent::Header();
			
			$this->SetFillColor(160, 160, 160);
			$this->SetTextColor(0,0,0);
			$this->SetDrawColor(8, 102, 198);
			$this->SetFont('Helvetica','B',8);
			$this->SetX(2);
			
			$this->Cell(8,6,'S/L','LTB',0,'L',true);
			$this->Cell(17,6,'ID','LTB',0,'L',true);
			$this->Cell(65,6,'Clint Address','LTBR',0,'C',true);
			$this->Cell(23,6,'Cell No','LTB',0,'C',true);
			$this->Cell(10,6,'Act Bill','LTB',0,'C',true); 
			$this->Cell(12,6,'P.Dis','LTB',0,'C',true);
			$this->Cell(12,6,'Due','LTB',0,'C',true);
			$this->Cell(12,6,'Adv','LTB',0,'C',true);
			$this->Cell(14,6,'Tot Bill','LTB',0,'C',true);
			$this->Cell(12,6,'Dis','LTB',0,'C',true);
			$this->Cell(14,6,'Paid','LTB',0,'C',true);
			$this->Cell(13,6,'Balance','LTB',0,'C',true);
			$this->Cell(81,6,'Note','LTBR',0,'C',true); 
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
		$pdf->AddPage('L', 'A4');
										
		$result = mysql_query("SELECT t1.c_id, t1.address, t1.cell, t1.discount AS diss, t1.note, t1.p_price, t2.dis, t2.bill, t3.bill_disc, t3.pay FROM
								(
								SELECT c.c_id, c.address, c.cell, c.p_id, p.p_price, c.discount, c.note FROM clients AS c 
								LEFT JOIN package AS p ON c.p_id = p.p_id
								WHERE c.sts = 0 AND c.z_id = '$z_id' AND c.mac_user = '0'
								)t1
								LEFT JOIN
								(
								SELECT b.c_id, SUM(b.bill_amount) AS bill, SUM(b.discount) AS dis FROM billing AS b
								WHERE MONTH(b.bill_date) = MONTH('$f_date')
								GROUP BY b.c_id
								)t2
								ON t1.c_id = t2.c_id
								LEFT JOIN
								(
								SELECT p.c_id, SUM(p.pay_amount) AS pay, SUM(p.bill_discount) AS bill_disc FROM payment AS p 
								WHERE p.pay_date BETWEEN '$f_date' AND '$t_date'
								GROUP BY p.c_id
								)t3
								ON t1.c_id = t3.c_id ORDER BY t1.address");  							
		
			$ActualBill = 0;
			$DewsBill = 0;
			$AdvanceBill = 0;
			$TotalBill = 0;
			$BillDiscount = 0;
			$TotalPayment = 0;
			$Balance = 0;
			$disss = 0;
			$x=1;
		while ($row=mysql_fetch_array($result)) {
				$date = $row['pay_date'];
				$c_id = $row['c_id'];
				$address = $row['address'];
				$cell = $row['cell'];
				$p_price = $row['p_price'];
				$discount = $row['dis'];
				$bill = $row['bill'];
				$bill_disc = $row['bill_disc'];
				$pay = $row['pay'];
				$diss = $row['diss'];
				$note = $row['note'];
				
				$balance = $bill - ($bill_disc + $pay);
				
				$results = mysql_query("SELECT t2.c_id, t2.dis, t2.bill, t3.bill_disc, t3.pay FROM
										(
										SELECT b.c_id, SUM(b.bill_amount) AS bill, SUM(b.discount) AS dis FROM billing AS b
										WHERE b.bill_date < '$f_date' AND b.c_id = '$c_id'
										GROUP BY b.c_id
										)t2
										LEFT JOIN
										(
										SELECT p.c_id, SUM(p.pay_amount) AS pay, SUM(p.bill_discount) AS bill_disc FROM payment AS p 
										WHERE p.pay_date < '$f_date' AND p.c_id = '$c_id'
										GROUP BY p.c_id
										)t3
										ON t2.c_id = t3.c_id");
				$rows = mysql_fetch_array($results);					
				$dew = $rows['bill'] - ($rows['bill_disc'] + $rows['pay']);
				
				if($dew < 0){
					$dews = 0;
					$advs = abs($dew);
				}else{
					$dews = $dew;
					$advs = 0;
				}
				
				$total_bill = ($bill + $dews)-$advs;
				$total_payable = $balance + $dew;
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(8,5,$x,'LTB',0,'L',true);
				$pdf->Cell(17,5,$c_id,'LTB',0,'L',true);
				$pdf->Cell(65,5,$address,'LTBR',0,'L',true);
				$pdf->Cell(23,5,$cell,'LTB',0,'L',true);
				$pdf->Cell(10,5,number_format($p_price,0),'LTB',0,'R',true); 
				$pdf->Cell(12,5,number_format($diss,0),'LTB',0,'R',true); 
				$pdf->Cell(12,5,number_format($dews,0),'LTB',0,'R',true);
				$pdf->Cell(12,5,number_format($advs,0),'LTB',0,'R',true);
				$pdf->Cell(14,5,number_format($total_bill,0),'LTB',0,'R',true);
				$pdf->Cell(12,5,number_format($bill_disc,0),'LTB',0,'R',true);
				$pdf->Cell(14,5,number_format($pay,0),'LTB',0,'R',true);
				$pdf->Cell(13,5,number_format($total_payable,0),'LTB',0,'R',true);
				$pdf->Cell(81,5,$note,'LTBR',0,'L',true);
				$pdf->Ln();
				
			$ActualBill += $p_price;
			$DewsBill += $dews;
			$AdvanceBill += $advs;
			$TotalBill += $total_bill;
			$BillDiscount += $bill_disc;
			$TotalPayment += $pay;
			$Balance += $total_payable;
			$disss += $diss;
			$x++;
		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','B',8);
			
			$pdf->Cell(90,6,'Total','LTB',0,'C',true);
			$pdf->Cell(33,6,number_format($ActualBill,0),'TB',0,'R',true); 
			$pdf->Cell(12,6,number_format($disss,0),'LTB',0,'R',true); 
			$pdf->Cell(12,6,number_format($DewsBill,0),'LTB',0,'R',true);
			$pdf->Cell(12,6,number_format($AdvanceBill,0),'LTB',0,'R',true);
			$pdf->Cell(14,6,number_format($TotalBill,0),'LTBR',0,'R',true);
			$pdf->Cell(12,6,number_format($BillDiscount,0),'LTB',0,'R',true);
			$pdf->Cell(14,6,number_format($TotalPayment,0),'LTBR',0,'R',true);
			$pdf->Cell(13,6,number_format($Balance,0),'LTBR',0,'R',true);
			$pdf->Cell(81,6,'','LTBR',0,'L',true);
			$pdf->Ln();
}
$pdf->Output();
?>