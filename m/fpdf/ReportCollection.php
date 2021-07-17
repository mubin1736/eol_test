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
			$this->SetFont('Helvetica','',8);
			$this->SetX(2);
			
			$this->Cell(8,6,'S/L','LTB',0,'L',true);
			$this->Cell(17,6,'Date','LTB',0,'L',true);
			$this->Cell(30,6,'PPoE ID','LTB',0,'L',true);
			$this->Cell(25,6,'Cell No','LTB',0,'C',true);
			$this->Cell(13,6,'Bill','LTB',0,'C',true);
			$this->Cell(13,6,'Discount','LTB',0,'C',true);
			$this->Cell(13,6,'Amount','LTB',0,'C',true);
			$this->Cell(30,6,'Recive By','LTB',0,'C',true);
			$this->Cell(16,6,'MR No','LTBR',0,'C',true);
			$this->Cell(30,6,'Bank','LTBR',0,'C',true); 
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
		
		$result = mysql_query("SELECT p.pay_date, c.c_id, p.moneyreceiptno, c.address, b.id AS b_id, b.bank_name, c.cell, pk.p_price, SUM(p.pay_amount) AS pay, SUM(p.bill_discount) AS bill_disc, p.pay_ent_by, e.e_name, c.note
								FROM payment AS p 
								LEFT JOIN clients AS c ON c.c_id = p.c_id
								LEFT JOIN package AS pk ON c.p_id = pk.p_id
								LEFT JOIN emp_info AS e ON e.e_id = p.pay_ent_by
								LEFT JOIN bank AS b ON b.id = p.bank
								WHERE p.pay_date BETWEEN '$f_date' AND '$t_date' AND c. mac_user = '0'
								GROUP BY p.c_id ORDER BY c.address");  							
		
			$ActualBill = 0;
			$BillDiscount = 0;
			$TotalPayment = 0;
			$x = 1;
		while ($row=mysql_fetch_array($result)) {
				$date = $row['pay_date'];
				$ent_by = $row['e_name'];
				$c_id = $row['c_id'];
				$address = $row['address'];
				$cell = $row['cell'];
				$p_price = $row['p_price'];
				$bill_disc = $row['bill_disc'];
				$pay = $row['pay'];
				$mrno = $row['moneyreceiptno'];
				$b_id = $row['b_id'];
				$bank_name = $row['bank_name'];
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(8,5,$x,'LTB',0,'L',true);
				$pdf->Cell(17,5,$date,'LTB',0,'L',true);
				$pdf->Cell(30,5,$c_id,'LTB',0,'L',true);
				$pdf->Cell(25,5,$cell,'LTB',0,'L',true);
				$pdf->Cell(13,5,number_format($p_price,0),'LTB',0,'R',true); 
				$pdf->Cell(13,5,number_format($bill_disc,0),'LTB',0,'R',true);
				$pdf->Cell(13,5,number_format($pay,0),'LTB',0,'R',true);
				$pdf->Cell(30,5,$ent_by,'LTB',0,'L',true);
				$pdf->Cell(16,5,$mrno,'LTBR',0,'L',true);
				$pdf->Cell(30,5,$bank_name.' ('.$b_id.')','LTBR',0,'L',true);
				$pdf->Ln();
				
			$ActualBill += $p_price;
			$BillDiscount += $bill_disc;
			$TotalPayment += $pay;
			$x++;

		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(93,6,'Total','LTB',0,'C',true);
			$pdf->Cell(13,6,number_format($BillDiscount,0),'LTBR',0,'R',true);
			$pdf->Cell(13,6,number_format($TotalPayment,0),'LTBR',0,'R',true);
			$pdf->Cell(76,6,'','LTBR',0,'L',true);
			
			
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
			$this->SetFont('Helvetica','',8);
			$this->SetX(2);
			
			
			$this->Cell(8,6,'S/L','LTB',0,'L',true);
			$this->Cell(17,6,'Date','LTB',0,'L',true);
			$this->Cell(30,6,'PPoE ID','LTB',0,'L',true);
			$this->Cell(25,6,'Cell No','LTB',0,'C',true);
			$this->Cell(13,6,'Bill','LTB',0,'C',true);
			$this->Cell(13,6,'Discount','LTB',0,'C',true);
			$this->Cell(13,6,'Amount','LTB',0,'C',true);
			$this->Cell(30,6,'Recive By','LTB',0,'C',true);
			$this->Cell(16,6,'MR No','LTBR',0,'C',true);
			$this->Cell(30,6,'Bank','LTBR',0,'C',true); 
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
		
		$result = mysql_query("SELECT p.pay_date, c.c_id, p.moneyreceiptno, c.address, b.id AS b_id, b.bank_name, c.cell, pk.p_price, SUM(p.pay_amount) AS pay, SUM(p.bill_discount) AS bill_disc, p.pay_ent_by, e.e_name, c.note
								FROM payment AS p 
								LEFT JOIN clients AS c ON c.c_id = p.c_id
								LEFT JOIN package AS pk ON c.p_id = pk.p_id
								LEFT JOIN emp_info AS e ON e.e_id = p.pay_ent_by
								LEFT JOIN bank AS b ON b.id = p.bank
								WHERE p.pay_date BETWEEN '$f_date' AND '$t_date' AND c.z_id = '$z_id' AND c. mac_user = '0'
								GROUP BY p.c_id ORDER BY c.address");  							
		
			$ActualBill = 0;
			$BillDiscount = 0;
			$TotalPayment = 0;
			$x = 1;
			
		while ($row=mysql_fetch_array($result)) {
				$date = $row['pay_date'];
				$ent_by = $row['e_name'];
				$c_id = $row['c_id'];
				$address = $row['address'];
				$cell = $row['cell'];
				$p_price = $row['p_price'];
				$bill_disc = $row['bill_disc'];
				$pay = $row['pay'];
				$mrno = $row['moneyreceiptno'];
				$b_id = $row['b_id'];
				$bank_name = $row['bank_name'];
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(8,5,$x,'LTB',0,'L',true);
				$pdf->Cell(17,5,$date,'LTB',0,'L',true);
				$pdf->Cell(30,5,$c_id,'LTB',0,'L',true);
				$pdf->Cell(25,5,$cell,'LTB',0,'L',true);
				$pdf->Cell(13,5,number_format($p_price,0),'LTB',0,'R',true); 
				$pdf->Cell(13,5,number_format($bill_disc,0),'LTB',0,'R',true);
				$pdf->Cell(13,5,number_format($pay,0),'LTB',0,'R',true);
				$pdf->Cell(30,5,$ent_by,'LTB',0,'L',true);
				$pdf->Cell(16,5,$mrno,'LTBR',0,'L',true);
				$pdf->Cell(30,5,$bank_name.' ('.$b_id.')','LTBR',0,'L',true);
				$pdf->Ln();
				
			$ActualBill += $p_price;
			$BillDiscount += $bill_disc;
			$TotalPayment += $pay;
			$x++;
		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(93,6,'Total','LTB',0,'C',true);
			$pdf->Cell(13,6,number_format($BillDiscount,0),'LTBR',0,'R',true);
			$pdf->Cell(13,6,number_format($TotalPayment,0),'LTBR',0,'R',true);
			$pdf->Cell(76,6,'','LTBR',0,'L',true);
			
			$pdf->Ln();
}
$pdf->Output();
?>