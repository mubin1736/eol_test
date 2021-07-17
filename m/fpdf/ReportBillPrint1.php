<?php
require('mysql_table.php');
require('connection.php');

ini_alter('date.timezone','Asia/Almaty');
$z_id = $_REQUEST['z_id'];
$p_m = $_REQUEST['p_m'];

$query_date = date("Y-m-d");

$f_date = date('Y-m-01', strtotime($query_date));
$t_date = date('Y-m-t', strtotime($query_date));

if($z_id == 'all'){
	if($p_m != 'all'){
	class PDF extends PDF_MySQL_Table
		{
		function Header()
		{
			parent::Header();
			
			$this->SetFillColor(160, 160, 160);
			$this->SetTextColor(0,0,0);
			$this->SetDrawColor(0, 0, 0);
			$this->SetFont('Helvetica','',8);
			$pdf->SetX(7, 5);
			
			$this->Cell(8,6,'S/L','LTB',0,'C',true);
			$this->Cell(45,6,'PPPoe ID','LTB',0,'C',true);
			$this->Cell(45,6,'Client Name','LTB',0,'C',true);
			$this->Cell(50,6,'Zone','LTBR',0,'C',true);
			$this->Cell(23,6,'Cell No','LTB',0,'C',true);
			$this->Cell(23,6,'Package','LTB',0,'C',true);
			$this->Cell(23,6,'Status','LTB',0,'C',true);
			$this->Cell(13,6,'Actual Bill','LTB',0,'C',true); 
			$this->Cell(13,6,'Due','LTB',0,'C',true);
			$this->Cell(13,6,'Total Bill','LTB',0,'C',true);
			$this->Cell(13,6,'Total Pay','LTB',0,'C',true);
			$this->Cell(13,6,'Balance','LTBR',0,'C',true);
			$this->Ln();
			
		}
		function Footer()
				{
					//Position at 1.5 cm from bottom
					$this->SetY(-15);
					//Arial italic 8
					$this->SetFont('Helvetica','I',8);
					//Page number
					$this->Cell(0,10,'Page '.$this->PageNo().'/Jack Net','T',0,'C');
					$this->SetDrawColor(0, 0, 0);
					parent::Footer();
				}
		}

		//Connect to database
		include("connection.php");
		
		$pdf=new PDF();
		$pdf->AddPage('L', 'A4');
										
		$result = mysql_query("SELECT t1.c_id, t1.z_name, t1.p_name, t1.con_sts, t1.c_name, t1.note, t1.cell, t1.p_price, t2.dis, t2.bill, t3.bill_disc, t1.p_m, t3.pay FROM
								(
								SELECT c.c_id, c.c_name, c.con_sts, c.address, z.z_name, c.cell, c.p_id, p.p_price, c.note, c.p_m, p.p_name FROM clients AS c 
								LEFT JOIN package AS p ON c.p_id = p.p_id
								LEFT JOIN zone AS z on z.z_id = c.z_id
								WHERE c.sts = 0 AND c.p_m = '$p_m' AND c.mac_user = '0'
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
								ON t1.c_id = t3.c_id ORDER BY t1.con_sts");  							
		$x=1;
		while ($row=mysql_fetch_array($result)) {
				$date = $row['pay_date'];
				$c_id = $row['c_id'];
				$c_name = $row['c_name'];
				$z_name = $row['z_name'];
				$p_name = $row['p_name'];
				$cell = $row['cell'];
				$p_price = $row['p_price'];
				$discount = $row['dis'];
				$bill = $row['bill'];
				$bill_disc = $row['bill_disc'];
				$pay = $row['pay'];
				$note = $row['note'];
				$pa_m = $row['p_m'];
				$con_sts = $row['con_sts'];
				
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
				
				$total_payable = $balance + $dew;
				
			if($total_payable <= 0){
					
			}else{
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(7, 5);
				
				$pdf->Cell(8,5,$x,'LTB',0,'C',true);
				$pdf->Cell(45,5,$c_id,'LTB',0,'L',true);
				$pdf->Cell(45,5,$c_name,'LTB',0,'L',true);
				$pdf->Cell(50,5,$z_name,'LTBR',0,'L',true);
				$pdf->Cell(23,5,$cell,'LTB',0,'L',true);
				$pdf->Cell(23,5,$p_name,'LTB',0,'L',true);
				$pdf->Cell(23,5,$con_sts,'LTB',0,'L',true);
				$pdf->Cell(13,5,number_format($p_price,0),'LTB',0,'R',true); 
				$pdf->Cell(13,5,number_format($dews,0),'LTB',0,'R',true);
				$pdf->Cell(13,5,number_format($bill,0),'LTB',0,'R',true);
				$pdf->Cell(13,5,number_format($pay,0),'LTB',0,'R',true);
				$pdf->Cell(13,5,number_format($total_payable,0),'LTBR',0,'R',true);
				$pdf->Ln();
				$x++;
			}
			$TotalPpaid += $bill;
			$TotalPayment += $total_payable;
		}
		$pdf->SetX(7);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(243,6,'Total','LTB',0,'C',true);
			$pdf->Cell(13,6,number_format($TotalPpaid,0),'LTBR',0,'R',true);
			$pdf->Cell(13,6,'','LTBR',0,'R',true);
			$pdf->Cell(13,6,number_format($TotalPayment,0),'LTBR',0,'R',true);
			$pdf->Ln();
		
}}


if($z_id != 'all'){
	if($p_m == 'all'){
	class PDF extends PDF_MySQL_Table
		{
		function Header()
		{
			parent::Header();
			
			$this->SetFillColor(160, 160, 160);
			$this->SetTextColor(0,0,0);
			$this->SetDrawColor(0, 0, 0);
			$this->SetFont('Helvetica','',8);
			$pdf->SetX(7, 5);
			
			$this->Cell(8,6,'S/L','LTB',0,'C',true);
			$this->Cell(45,6,'PPPoe ID','LTB',0,'C',true);
			$this->Cell(45,6,'Client Name','LTB',0,'C',true);
			$this->Cell(50,6,'Zone','LTBR',0,'C',true);
			$this->Cell(23,6,'Cell No','LTB',0,'C',true);
			$this->Cell(23,6,'Package','LTB',0,'C',true);
			$this->Cell(23,6,'Status','LTB',0,'C',true);
			$this->Cell(13,6,'Actual Bill','LTB',0,'C',true); 
			$this->Cell(13,6,'Due','LTB',0,'C',true);
			$this->Cell(13,6,'Total Bill','LTB',0,'C',true);
			$this->Cell(13,6,'Total Pay','LTB',0,'C',true);
			$this->Cell(13,6,'Balance','LTBR',0,'C',true);
			$this->Ln();
			
		}
		function Footer()
				{
					//Position at 1.5 cm from bottom
					$this->SetY(-15);
					//Arial italic 8
					$this->SetFont('Helvetica','I',8);
					//Page number
					$this->Cell(0,10,'Page '.$this->PageNo().'/Discovery Internet','T',0,'C');
					$this->SetDrawColor(0, 0, 0);
					parent::Footer();
				}
		}

		//Connect to database
		include("connection.php");

		$pdf=new PDF();
		$pdf->AddPage('L', 'A4');
										
		$result = mysql_query("SELECT t1.c_id, t1.z_name, t1.p_name, t1.con_sts, t1.c_name, t1.note, t1.cell, t1.p_price, t2.dis, t2.bill, t3.bill_disc, t1.p_m, t3.pay FROM
								(
								SELECT c.c_id, c.c_name, c.con_sts, c.address, z.z_name, c.cell, c.p_id, p.p_price, c.note, c.p_m, p.p_name FROM clients AS c 
								LEFT JOIN package AS p ON c.p_id = p.p_id
								LEFT JOIN zone AS z on z.z_id = c.z_id
								WHERE c.sts = 0  AND c.mac_user = '0' AND c.z_id = '$z_id'
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
								ON t1.c_id = t3.c_id ORDER BY t1.con_sts");  							
		
			//$ActualBill = 0;
			//$DewsBill = 0;
			//$AdvanceBill = 0;
			//$TotalBill = 0;
			//$BillDiscount = 0;
			//$TotalPayment = 0;
			//$Balance = 0;
		$x=1;	
		while ($row=mysql_fetch_array($result)) {
				$date = $row['pay_date'];
				$c_id = $row['c_id'];
				$c_name = $row['c_name'];
				$z_name = $row['z_name'];
				$p_name = $row['p_name'];
				$cell = $row['cell'];
				$p_price = $row['p_price'];
				$discount = $row['dis'];
				$bill = $row['bill'];
				$bill_disc = $row['bill_disc'];
				$pay = $row['pay'];
				$note = $row['note'];
				$pa_m = $row['p_m'];
				$con_sts = $row['con_sts'];
				
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
				
				$total_payable = $balance + $dew;
				
			if($total_payable <= 0){
					
			}else{
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(7, 5);
				
				$pdf->Cell(8,5,$x,'LTB',0,'C',true);
				$pdf->Cell(45,5,$c_id,'LTB',0,'L',true);
				$pdf->Cell(45,5,$c_name,'LTB',0,'L',true);
				$pdf->Cell(50,5,$z_name,'LTBR',0,'L',true);
				$pdf->Cell(23,5,$cell,'LTB',0,'L',true);
				$pdf->Cell(23,5,$p_name,'LTB',0,'L',true);
				$pdf->Cell(23,5,$con_sts,'LTB',0,'L',true);
				$pdf->Cell(13,5,number_format($p_price,0),'LTB',0,'R',true); 
				$pdf->Cell(13,5,number_format($dews,0),'LTB',0,'R',true);
				$pdf->Cell(13,5,number_format($bill,0),'LTB',0,'R',true);
				$pdf->Cell(13,5,number_format($pay,0),'LTB',0,'R',true);
				$pdf->Cell(13,5,number_format($total_payable,0),'LTBR',0,'R',true);
				$pdf->Ln();
				$x++;
			}
			$TotalPpaid += $bill;
			$TotalPayment += $total_payable;
		}
		$pdf->SetX(7);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(243,6,'Total','LTB',0,'C',true);
			$pdf->Cell(13,6,number_format($TotalPpaid,0),'LTBR',0,'R',true);
			$pdf->Cell(13,6,'','LTBR',0,'R',true);
			$pdf->Cell(13,6,number_format($TotalPayment,0),'LTBR',0,'R',true);
			$pdf->Ln();
		
}}

if($z_id != 'all'){
	if($p_m != 'all'){
	class PDF extends PDF_MySQL_Table
		{
		function Header()
		{
			parent::Header();
			
			$this->SetFillColor(160, 160, 160);
			$this->SetTextColor(0,0,0);
			$this->SetDrawColor(0, 0, 0);
			$this->SetFont('Helvetica','',8);
			$pdf->SetX(7, 5);
			
			$this->Cell(8,6,'S/L','LTB',0,'C',true);
			$this->Cell(45,6,'PPPoe ID','LTB',0,'C',true);
			$this->Cell(45,6,'Client Name','LTB',0,'C',true);
			$this->Cell(50,6,'Zone','LTBR',0,'C',true);
			$this->Cell(23,6,'Cell No','LTB',0,'C',true);
			$this->Cell(23,6,'Package','LTB',0,'C',true);
			$this->Cell(23,6,'Status','LTB',0,'C',true);
			$this->Cell(13,6,'Actual Bill','LTB',0,'C',true); 
			$this->Cell(13,6,'Due','LTB',0,'C',true);
			$this->Cell(13,6,'Total Bill','LTB',0,'C',true);
			$this->Cell(13,6,'Total Pay','LTB',0,'C',true);
			$this->Cell(13,6,'Balance','LTBR',0,'C',true);
			$this->Ln();
			
		}
		function Footer()
				{
					//Position at 1.5 cm from bottom
					$this->SetY(-15);
					//Arial italic 8
					$this->SetFont('Helvetica','I',8);
					//Page number
					$this->Cell(0,10,'Page '.$this->PageNo().'/Discovery Internet','T',0,'C');
					$this->SetDrawColor(0, 0, 0);
					parent::Footer();
				}
		}

		//Connect to database
		include("connection.php");

		$pdf=new PDF();
		$pdf->AddPage('L', 'A4');
										
		$result = mysql_query("SELECT t1.c_id, t1.z_name, t1.p_name, t1.con_sts, t1.c_name, t1.note, t1.cell, t1.p_price, t2.dis, t2.bill, t3.bill_disc, t1.p_m, t3.pay FROM
								(
								SELECT c.c_id, c.c_name, c.con_sts, c.address, z.z_name, c.cell, c.p_id, p.p_price, c.note, c.p_m, p.p_name FROM clients AS c 
								LEFT JOIN package AS p ON c.p_id = p.p_id
								LEFT JOIN zone AS z on z.z_id = c.z_id
								WHERE c.sts = 0 AND c.mac_user = '0' AND c.p_m = '$p_m' AND c.z_id = '$z_id'
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
								ON t1.c_id = t3.c_id ORDER BY t1.con_sts");  							
		
			//$ActualBill = 0;
			//$DewsBill = 0;
			//$AdvanceBill = 0;
			//$TotalBill = 0;
			//$BillDiscount = 0;
			//$TotalPayment = 0;
			//$Balance = 0;
		$x=1;	
		while ($row=mysql_fetch_array($result)) {
				$date = $row['pay_date'];
				$c_id = $row['c_id'];
				$c_name = $row['c_name'];
				$z_name = $row['z_name'];
				$p_name = $row['p_name'];
				$cell = $row['cell'];
				$p_price = $row['p_price'];
				$discount = $row['dis'];
				$bill = $row['bill'];
				$bill_disc = $row['bill_disc'];
				$pay = $row['pay'];
				$note = $row['note'];
				$pa_m = $row['p_m'];
				$con_sts = $row['con_sts'];
				
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
				
				$total_payable = $balance + $dew;
				
			if($total_payable <= 0){
					
			}else{
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(7, 5);
				
				$pdf->Cell(8,5,$x,'LTB',0,'C',true);
				$pdf->Cell(45,5,$c_id,'LTB',0,'L',true);
				$pdf->Cell(45,5,$c_name,'LTB',0,'L',true);
				$pdf->Cell(50,5,$z_name,'LTBR',0,'L',true);
				$pdf->Cell(23,5,$cell,'LTB',0,'L',true);
				$pdf->Cell(23,5,$p_name,'LTB',0,'L',true);
				$pdf->Cell(23,5,$con_sts,'LTB',0,'L',true);
				$pdf->Cell(13,5,number_format($p_price,0),'LTB',0,'R',true); 
				$pdf->Cell(13,5,number_format($dews,0),'LTB',0,'R',true);
				$pdf->Cell(13,5,number_format($bill,0),'LTB',0,'R',true);
				$pdf->Cell(13,5,number_format($pay,0),'LTB',0,'R',true);
				$pdf->Cell(13,5,number_format($total_payable,0),'LTBR',0,'R',true);
				$pdf->Ln();
				$x++;
			}
			$TotalPpaid += $bill;
			$TotalPayment += $total_payable;
		}
		$pdf->SetX(7);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(243,6,'Total','LTB',0,'C',true);
			$pdf->Cell(13,6,number_format($TotalPpaid,0),'LTBR',0,'R',true);
			$pdf->Cell(13,6,'','LTBR',0,'R',true);
			$pdf->Cell(13,6,number_format($TotalPayment,0),'LTBR',0,'R',true);
			$pdf->Ln();
		
}}


if($z_id == 'all'){
	if($p_m == 'all'){
	class PDF extends PDF_MySQL_Table
		{
		function Header()
		{
			parent::Header();
			
			$this->SetFillColor(160, 160, 160);
			$this->SetTextColor(0,0,0);
			$this->SetDrawColor(0, 0, 0);
			$this->SetFont('Helvetica','',8);
			$this->SetX(7, 5);
			
			$this->Cell(8,6,'S/L','LTB',0,'C',true);
			$this->Cell(45,6,'PPPoe ID','LTB',0,'C',true);
			$this->Cell(45,6,'Client Name','LTB',0,'C',true);
			$this->Cell(50,6,'Zone','LTBR',0,'C',true);
			$this->Cell(23,6,'Cell No','LTB',0,'C',true);
			$this->Cell(23,6,'Package','LTB',0,'C',true);
			$this->Cell(23,6,'Status','LTB',0,'C',true);
			$this->Cell(13,6,'Actual Bill','LTB',0,'C',true); 
			$this->Cell(13,6,'Due','LTB',0,'C',true);
			$this->Cell(13,6,'Total Bill','LTB',0,'C',true);
			$this->Cell(13,6,'Total Pay','LTB',0,'C',true);
			$this->Cell(13,6,'Balance','LTBR',0,'C',true);
			$this->Ln();
			
		}
		function Footer()
				{
					//Position at 1.5 cm from bottom
					$this->SetY(-15);
					//Arial italic 8
					$this->SetFont('Helvetica','I',8);
					//Page number
					$this->Cell(0,10,'Page '.$this->PageNo().'/Discovery Internet','T',0,'C');
					$this->SetDrawColor(0, 0, 0);
					parent::Footer();
				}
		}

		//Connect to database
		include("connection.php");

		$pdf=new PDF();
		$pdf->AddPage('L', 'A4');
										
		$result = mysql_query("SELECT t1.c_id, t1.z_name, t1.p_name, t1.con_sts, t1.c_name, t1.note, t1.cell, t1.p_price, t2.dis, t2.bill, t3.bill_disc, t1.p_m, t3.pay FROM
								(
								SELECT c.c_id, c.c_name, c.con_sts, c.address, z.z_name, c.cell, c.p_id, p.p_price, c.note, c.p_m, p.p_name FROM clients AS c 
								LEFT JOIN package AS p ON c.p_id = p.p_id
								LEFT JOIN zone AS z on z.z_id = c.z_id
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
								ON t1.c_id = t3.c_id ORDER BY t1.con_sts");  							
		
		$x = 1;	
		while ($row=mysql_fetch_array($result)) {
				$date = $row['pay_date'];
				$c_id = $row['c_id'];
				$c_name = $row['c_name'];
				$z_name = $row['z_name'];
				$p_name = $row['p_name'];
				$cell = $row['cell'];
				$p_price = $row['p_price'];
				$discount = $row['dis'];
				$bill = $row['bill'];
				$bill_disc = $row['bill_disc'];
				$pay = $row['pay'];
				$note = $row['note'];
				$pa_m = $row['p_m'];
				$con_sts = $row['con_sts'];
				
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
				
				$total_payable = $balance + $dew;
				
			if($total_payable <= 0){
					
			}else{
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(7, 5);
				
				$pdf->Cell(8,5,$x,'LTB',0,'C',true);
				$pdf->Cell(45,5,$c_id,'LTB',0,'L',true);
				$pdf->Cell(45,5,$c_name,'LTB',0,'L',true);
				$pdf->Cell(50,5,$z_name,'LTBR',0,'L',true);
				$pdf->Cell(23,5,$cell,'LTB',0,'L',true);
				$pdf->Cell(23,5,$p_name,'LTB',0,'L',true);
				$pdf->Cell(23,5,$con_sts,'LTB',0,'L',true);
				$pdf->Cell(13,5,number_format($p_price,0),'LTB',0,'R',true); 
				$pdf->Cell(13,5,number_format($dews,0),'LTB',0,'R',true);
				$pdf->Cell(13,5,number_format($bill,0),'LTB',0,'R',true);
				$pdf->Cell(13,5,number_format($pay,0),'LTB',0,'R',true);
				$pdf->Cell(13,5,number_format($total_payable,0),'LTBR',0,'R',true);
				$pdf->Ln();
				$x++;
			}
			$TotalPpaid += $bill;
			$TotalPayment += $total_payable;
		}
		$pdf->SetX(7);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(243,6,'Total','LTB',0,'C',true);
			$pdf->Cell(13,6,number_format($TotalPpaid,0),'LTBR',0,'R',true);
			$pdf->Cell(13,6,'','LTBR',0,'R',true);
			$pdf->Cell(13,6,number_format($TotalPayment,0),'LTBR',0,'R',true);
			$pdf->Ln();
		
}}


$pdf->Output();
?>