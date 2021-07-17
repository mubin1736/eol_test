<?php
require('mysql_table.php');
require('connection.php');

$z_id = $_REQUEST['z_id'];
$e_id = $_REQUEST['e_id'];
$f_date = $_REQUEST['f_date'];
$t_date = $_REQUEST['t_date'];

$FastDate = date('F d, Y', strtotime($f_date));
$LastDate = date('F d, Y', strtotime($t_date));

if($z_id == 'all'){
	class PDF extends PDF_MySQL_Table
		{
		function Header()
		{
			parent::Header();
			
			$this->SetFillColor(160, 160, 160);
			$this->SetTextColor(0,0,0);
			$this->SetDrawColor(0, 0, 0);
			$this->SetFont('Helvetica','',8);
			$this->SetX(2); 
			$this->Ln();
			
		}
		function Footer()
				{
					//Position at 1.5 cm from bottom
					$this->SetY(-15);
					//Arial italic 8
					$this->SetFont('Helvetica','I',8);
					//Page number
					$this->Cell(0,10,'Page '.$this->PageNo().'/Inter Khulna Network','T',0,'C');
					$this->SetDrawColor(0, 0, 0);
					parent::Footer();
				}
		}

		$pdf=new PDF();
		$pdf->AddPage();
		
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFont('Helvetica','',8);
			$pdf->SetX(2);
			
			$pdf->Cell(205,6,'Collection Form '.$FastDate.' To '.$LastDate,'LTBR',0,'C',true);
			$pdf->Ln();
			$pdf->SetX(2);
			$pdf->Cell(15,6,'S/L','LTB',0,'C',true);
			$pdf->Cell(70,6,'Zone Name','LTB',0,'L',true);
			$pdf->Cell(40,6,'Total Collection','LTB',0,'R',true);
			$pdf->Cell(40,6,'Total Signup Charge','LTB',0,'R',true);
			$pdf->Cell(40,6,'Total','LTBR',0,'R',true);
			$pdf->Ln();
			
		$result = mysql_query("SELECT * FROM zone ORDER BY z_name");  							
		
			$ActualBill = 0;
			$TotalSignup = 0;
			$SubTotal = 0;
			$x = 1;
		while ($row=mysql_fetch_array($result)) {
				$ZoneId = $row['z_id'];
				$ZoneName = $row['z_name'];
				$ZoneNameBn = $row['z_bn_name'];
				
				if($e_id=='all'){
					
					$result1 = mysql_query("SELECT z.z_id, z.z_name, z.z_bn_name, SUM(p.pay_amount) AS TotalBill, SUM(p.bill_discount) AS TotalDiscount FROM payment AS p 
								LEFT JOIN clients AS c ON c.c_id = p.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND p.pay_date BETWEEN '$f_date' AND '$t_date'
								GROUP BY c.z_id"); 
					$row1 = mysql_fetch_array($result1);
				
					$result2 = mysql_query("SELECT z.z_id, z.z_name, SUM(b.amount) AS SignupAmount FROM bill_signup AS b 
								LEFT JOIN clients AS c ON c.c_id = b.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND b.pay_date BETWEEN '$f_date' AND '$t_date'
								GROUP BY c.z_id"); 
					$row2 = mysql_fetch_array($result2);
				}else{
					$result1 = mysql_query("SELECT z.z_id, z.z_name, z.z_bn_name, SUM(p.pay_amount) AS TotalBill, SUM(p.bill_discount) AS TotalDiscount FROM payment AS p 
								LEFT JOIN clients AS c ON c.c_id = p.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND p.pay_date BETWEEN '$f_date' AND '$t_date' AND p.pay_ent_by = '$e_id'
								GROUP BY c.z_id"); 
					$row1 = mysql_fetch_array($result1);
				
					$result2 = mysql_query("SELECT z.z_id, z.z_name, SUM(b.amount) AS SignupAmount FROM bill_signup AS b 
								LEFT JOIN clients AS c ON c.c_id = b.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND b.pay_date BETWEEN '$f_date' AND '$t_date' AND b.ent_by = '$e_id'
								GROUP BY c.z_id"); 
					$row2 = mysql_fetch_array($result2);
				}
				
				$TotalBill = $row1['TotalBill'];
				$SignupAmount = $row2['SignupAmount'];
				$Total = $TotalBill + $SignupAmount;
				
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(15,5,$x,'LTB',0,'C',true);
				$pdf->Cell(70,5,$ZoneName,'LTB',0,'L',true);
				$pdf->Cell(40,5,number_format($TotalBill,0),'LTB',0,'R',true);
				$pdf->Cell(40,5,number_format($SignupAmount,0),'LTB',0,'R',true);
				$pdf->Cell(40,5,number_format($Total,0),'LTBR',0,'R',true);
				$pdf->Ln();
				
			$ActualBill += $TotalBill;
			$TotalSignup += $SignupAmount;
			$SubTotal += $Total;
			$x++;
		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(85,6,'Total','LTB',0,'C',true);
			$pdf->Cell(40,6,number_format($ActualBill,0),'LTBR',0,'R',true);
			$pdf->Cell(40,6,number_format($TotalSignup,0),'LTBR',0,'R',true);
			$pdf->Cell(40,6,number_format($SubTotal,0),'LTBR',0,'R',true);
			
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
			$this->SetDrawColor(0, 0, 0);
			$this->SetFont('Helvetica','',8);
			$this->SetX(2); 
			$this->Ln();
			
		}
		function Footer()
				{
					//Position at 1.5 cm from bottom
					$this->SetY(-15);
					//Arial italic 8
					$this->SetFont('Helvetica','I',8);
					//Page number
					$this->Cell(0,10,'Page '.$this->PageNo().'/Inter Khulna Network','T',0,'C');
					$this->SetDrawColor(0, 0, 0);
					parent::Footer();
				}
		}

		$pdf=new PDF();
		$pdf->AddPage();
		
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFont('Helvetica','',8);
			$pdf->SetX(2);
			
			$pdf->Cell(205,6,'Collection Form '.$FastDate.' To '.$LastDate,'LTBR',0,'C',true);
			$pdf->Ln();
			$pdf->SetX(2);
			$pdf->Cell(15,6,'S/L','LTB',0,'C',true);
			$pdf->Cell(70,6,'Zone Name','LTB',0,'L',true);
			$pdf->Cell(40,6,'Total Collection','LTB',0,'R',true);
			$pdf->Cell(40,6,'Total Signup Charge','LTB',0,'R',true);
			$pdf->Cell(40,6,'Total','LTBR',0,'R',true);
			$pdf->Ln();
			
		$result = mysql_query("SELECT * FROM zone WHERE z_id = '$z_id' ORDER BY z_name");  							
		
			$ActualBill = 0;
			$TotalSignup = 0;
			$SubTotal = 0;
			$x = 1;
		while ($row=mysql_fetch_array($result)) {
				$ZoneId = $row['z_id'];
				$ZoneName = $row['z_name'];
				$ZoneNameBn = $row['z_bn_name'];
				
				if($e_id=='all'){
					$result1 = mysql_query("SELECT z.z_id, z.z_name, z.z_bn_name, SUM(p.pay_amount) AS TotalBill, SUM(p.bill_discount) AS TotalDiscount FROM payment AS p 
								LEFT JOIN clients AS c ON c.c_id = p.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND p.pay_date BETWEEN '$f_date' AND '$t_date'
								GROUP BY c.z_id"); 
					$row1 = mysql_fetch_array($result1);
				
					$result2 = mysql_query("SELECT z.z_id, z.z_name, SUM(b.amount) AS SignupAmount FROM bill_signup AS b 
								LEFT JOIN clients AS c ON c.c_id = b.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND b.pay_date BETWEEN '$f_date' AND '$t_date'
								GROUP BY c.z_id"); 
					$row2 = mysql_fetch_array($result2);
				}else{
					$result1 = mysql_query("SELECT z.z_id, z.z_name, z.z_bn_name, SUM(p.pay_amount) AS TotalBill, SUM(p.bill_discount) AS TotalDiscount FROM payment AS p 
								LEFT JOIN clients AS c ON c.c_id = p.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND p.pay_date BETWEEN '$f_date' AND '$t_date' AND p.pay_ent_by = '$e_id'
								GROUP BY c.z_id"); 
					$row1 = mysql_fetch_array($result1);
				
					$result2 = mysql_query("SELECT z.z_id, z.z_name, SUM(b.amount) AS SignupAmount FROM bill_signup AS b 
								LEFT JOIN clients AS c ON c.c_id = b.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND b.pay_date BETWEEN '$f_date' AND '$t_date' AND b.ent_by = '$e_id'
								GROUP BY c.z_id"); 
					$row2 = mysql_fetch_array($result2);
				}	
				
				
				$TotalBill = $row1['TotalBill'];
				$SignupAmount = $row2['SignupAmount'];
				$Total = $TotalBill + $SignupAmount;
				
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(15,5,$x,'LTB',0,'C',true);
				$pdf->Cell(70,5,$ZoneName,'LTB',0,'L',true);
				$pdf->Cell(40,5,number_format($TotalBill,0),'LTB',0,'R',true);
				$pdf->Cell(40,5,number_format($SignupAmount,0),'LTB',0,'R',true);
				$pdf->Cell(40,5,number_format($Total,0),'LTBR',0,'R',true);
				$pdf->Ln();
				
			$ActualBill += $TotalBill;
			$TotalSignup += $SignupAmount;
			$SubTotal += $Total;
			$x++;
		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(85,6,'Total','LTB',0,'C',true);
			$pdf->Cell(40,6,number_format($ActualBill,0),'LTBR',0,'R',true);
			$pdf->Cell(40,6,number_format($TotalSignup,0),'LTBR',0,'R',true);
			$pdf->Cell(40,6,number_format($SubTotal,0),'LTBR',0,'R',true);
			
			$pdf->Ln();
}
$pdf->Output();
?>