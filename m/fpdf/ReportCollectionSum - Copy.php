<?php
require('mysql_table.php');
require('connection.php');

$z_id = $_REQUEST['z_id'];
$e_id = $_REQUEST['e_id'];
$f_date = $_REQUEST['f_date'];
$t_date = $_REQUEST['t_date'];

//$FastDate = date('F d, Y', strtotime($f_date));
//$LastDate = date('F d, Y', strtotime($t_date));

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
			$pdf->Cell(10,6,'S/L','LTB',0,'C',true);
			$pdf->Cell(15,6,'Zone','LTB',0,'L',true);
			$pdf->Cell(30,6,'Cash','LTB',0,'R',true);
			$pdf->Cell(30,6,'bKash','LTB',0,'R',true);
			$pdf->Cell(30,6,'SignUp','LTB',0,'R',true);
			$pdf->Cell(30,6,'Reactivation','LTB',0,'R',true);
			$pdf->Cell(30,6,'Others','LTB',0,'R',true);
			$pdf->Cell(30,6,'Total Collection','LTBR',0,'R',true);
			$pdf->Ln();
			
		$result = mysql_query("SELECT * FROM zone ORDER BY z_name");  							
		
			$ActualBill = 0;
			$BillbKash = 0;
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
								WHERE c.z_id = '$ZoneId' AND p.pay_date BETWEEN '$f_date' AND '$t_date' AND p.pay_mode = 'CASH'
								GROUP BY c.z_id"); 
					$row1 = mysql_fetch_array($result1);
					
					$result18 = mysql_query("SELECT z.z_id, z.z_name, z.z_bn_name, SUM(p.pay_amount) AS TotalBill, SUM(p.bill_discount) AS TotalDiscount FROM payment AS p 
								LEFT JOIN clients AS c ON c.c_id = p.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND p.pay_date BETWEEN '$f_date' AND '$t_date' AND p.pay_mode = 'bKash'
								GROUP BY c.z_id"); 
					$row18 = mysql_fetch_array($result18);
				
					$result2 = mysql_query("SELECT z.z_id, z.z_name, SUM(b.amount) AS SignupAmount FROM bill_signup AS b 
								LEFT JOIN clients AS c ON c.c_id = b.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND b.pay_date BETWEEN '$f_date' AND '$t_date' AND b.bill_type = '1'
								GROUP BY c.z_id"); 
					$row2 = mysql_fetch_array($result2);
					
					$result20 = mysql_query("SELECT z.z_id, z.z_name, SUM(b.amount) AS SignupAmount FROM bill_signup AS b 
								LEFT JOIN clients AS c ON c.c_id = b.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND b.pay_date BETWEEN '$f_date' AND '$t_date' AND b.bill_type = '2'
								GROUP BY c.z_id"); 
					$row20 = mysql_fetch_array($result20);
					
					$result21 = mysql_query("SELECT z.z_id, z.z_name, SUM(b.amount) AS SignupAmount FROM bill_signup AS b 
								LEFT JOIN clients AS c ON c.c_id = b.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND b.pay_date BETWEEN '$f_date' AND '$t_date' AND b.bill_type = '3'
								GROUP BY c.z_id"); 
					$row21 = mysql_fetch_array($result21);
				}else{
					$result1 = mysql_query("SELECT z.z_id, z.z_name, z.z_bn_name, SUM(p.pay_amount) AS TotalBill, SUM(p.bill_discount) AS TotalDiscount FROM payment AS p 
								LEFT JOIN clients AS c ON c.c_id = p.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND p.pay_date BETWEEN '$f_date' AND '$t_date' AND p.pay_ent_by = '$e_id' AND p.pay_mode = 'CASH'
								GROUP BY c.z_id"); 
					$row1 = mysql_fetch_array($result1);
					
					$result18 = mysql_query("SELECT z.z_id, z.z_name, z.z_bn_name, SUM(p.pay_amount) AS TotalBill, SUM(p.bill_discount) AS TotalDiscount FROM payment AS p 
								LEFT JOIN clients AS c ON c.c_id = p.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND p.pay_date BETWEEN '$f_date' AND '$t_date' AND p.pay_ent_by = '$e_id' AND p.pay_mode = 'bKash'
								GROUP BY c.z_id"); 
					$row18 = mysql_fetch_array($result18);
				
					$result2 = mysql_query("SELECT z.z_id, z.z_name, SUM(b.amount) AS SignupAmount FROM bill_signup AS b 
								LEFT JOIN clients AS c ON c.c_id = b.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND b.pay_date BETWEEN '$f_date' AND '$t_date' AND b.ent_by = '$e_id' AND b.bill_type = '1'
								GROUP BY c.z_id"); 
					$row2 = mysql_fetch_array($result2);
					
					$result20 = mysql_query("SELECT z.z_id, z.z_name, SUM(b.amount) AS SignupAmount FROM bill_signup AS b 
								LEFT JOIN clients AS c ON c.c_id = b.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND b.pay_date BETWEEN '$f_date' AND '$t_date' AND b.ent_by = '$e_id' AND b.bill_type = '2'
								GROUP BY c.z_id"); 
					$row20 = mysql_fetch_array($result20);
					
					$result21 = mysql_query("SELECT z.z_id, z.z_name, SUM(b.amount) AS SignupAmount FROM bill_signup AS b 
								LEFT JOIN clients AS c ON c.c_id = b.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND b.pay_date BETWEEN '$f_date' AND '$t_date' AND b.ent_by = '$e_id' AND b.bill_type = '3'
								GROUP BY c.z_id"); 
					$row21 = mysql_fetch_array($result21);
				}
				
				$TotalBillbKash = $row18['TotalBill'];
				$TotalBill = $row1['TotalBill'];
				$SignupAmount = $row2['SignupAmount'];
				$ReactivationAmount = $row20['SignupAmount'];
				$OthersAmount = $row21['SignupAmount'];
				$Total = $TotalBill + $SignupAmount + $TotalBillbKash + $ReactivationAmount + $OthersAmount;
				
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(10,5,$x,'LTB',0,'C',true);
				$pdf->Cell(15,5,$ZoneName,'LTB',0,'L',true);
				$pdf->Cell(30,5,number_format($TotalBill,0),'LTB',0,'R',true);
				$pdf->Cell(30,5,number_format($TotalBillbKash,0),'LTB',0,'R',true);
				$pdf->Cell(30,5,number_format($SignupAmount,0),'LTB',0,'R',true);
				$pdf->Cell(30,5,number_format($ReactivationAmount,0),'LTB',0,'R',true);
				$pdf->Cell(30,5,number_format($OthersAmount,0),'LTB',0,'R',true);
				$pdf->Cell(30,5,number_format($Total,0),'LTBR',0,'R',true);
				$pdf->Ln();
				
			$ActualBill += $TotalBill;
			$BillbKash += $TotalBillbKash;
			$TotalSignup += $SignupAmount;
			$TotalReactivation += $ReactivationAmount;
			$TotalOthers += $OthersAmount;
			$SubTotal += $Total;
			$x++;
		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(25,6,'Total','LTB',0,'C',true);
			$pdf->Cell(30,6,number_format($ActualBill,0),'LTBR',0,'R',true);
			$pdf->Cell(30,6,number_format($BillbKash,0),'LTBR',0,'R',true);
			$pdf->Cell(30,6,number_format($TotalSignup,0),'LTBR',0,'R',true);
			$pdf->Cell(30,6,number_format($TotalReactivation,0),'LTBR',0,'R',true);
			$pdf->Cell(30,6,number_format($TotalOthers,0),'LTBR',0,'R',true);
			$pdf->Cell(30,6,number_format($SubTotal,0),'LTBR',0,'R',true);
			
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
			$pdf->Cell(10,6,'S/L','LTB',0,'C',true);
			$pdf->Cell(15,6,'Zone','LTB',0,'L',true);
			$pdf->Cell(30,6,'Cash','LTB',0,'R',true);
			$pdf->Cell(30,6,'bKash','LTB',0,'R',true);
			$pdf->Cell(30,6,'SignUp','LTB',0,'R',true);
			$pdf->Cell(30,6,'Reactivation','LTB',0,'R',true);
			$pdf->Cell(30,6,'Others','LTB',0,'R',true);
			$pdf->Cell(30,6,'Total Collection','LTBR',0,'R',true);
			$pdf->Ln();
			
		$result = mysql_query("SELECT * FROM zone WHERE z_id = '$z_id' ORDER BY z_name");  							
		
			$ActualBill = 0;
			$BillbKash = 0;
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
								WHERE c.z_id = '$ZoneId' AND p.pay_date BETWEEN '$f_date' AND '$t_date' AND p.pay_mode = 'CASH'
								GROUP BY c.z_id"); 
					$row1 = mysql_fetch_array($result1);
					
					$result18 = mysql_query("SELECT z.z_id, z.z_name, z.z_bn_name, SUM(p.pay_amount) AS TotalBill, SUM(p.bill_discount) AS TotalDiscount FROM payment AS p 
								LEFT JOIN clients AS c ON c.c_id = p.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND p.pay_date BETWEEN '$f_date' AND '$t_date' AND p.pay_mode = 'bKash'
								GROUP BY c.z_id"); 
					$row18 = mysql_fetch_array($result18);
				
					$result2 = mysql_query("SELECT z.z_id, z.z_name, SUM(b.amount) AS SignupAmount FROM bill_signup AS b 
								LEFT JOIN clients AS c ON c.c_id = b.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND b.pay_date BETWEEN '$f_date' AND '$t_date' AND b.bill_type = '1'
								GROUP BY c.z_id"); 
					$row2 = mysql_fetch_array($result2);
					
					$result20 = mysql_query("SELECT z.z_id, z.z_name, SUM(b.amount) AS SignupAmount FROM bill_signup AS b 
								LEFT JOIN clients AS c ON c.c_id = b.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND b.pay_date BETWEEN '$f_date' AND '$t_date' AND b.bill_type = '2'
								GROUP BY c.z_id"); 
					$row20 = mysql_fetch_array($result20);
					
					$result21 = mysql_query("SELECT z.z_id, z.z_name, SUM(b.amount) AS SignupAmount FROM bill_signup AS b 
								LEFT JOIN clients AS c ON c.c_id = b.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND b.pay_date BETWEEN '$f_date' AND '$t_date' AND b.bill_type = '3'
								GROUP BY c.z_id"); 
					$row21 = mysql_fetch_array($result21);
				}else{
					$result1 = mysql_query("SELECT z.z_id, z.z_name, z.z_bn_name, SUM(p.pay_amount) AS TotalBill, SUM(p.bill_discount) AS TotalDiscount FROM payment AS p 
								LEFT JOIN clients AS c ON c.c_id = p.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND p.pay_date BETWEEN '$f_date' AND '$t_date' AND p.pay_ent_by = '$e_id' AND p.pay_mode = 'CASH'
								GROUP BY c.z_id"); 
					$row1 = mysql_fetch_array($result1);
					
					$result18 = mysql_query("SELECT z.z_id, z.z_name, z.z_bn_name, SUM(p.pay_amount) AS TotalBill, SUM(p.bill_discount) AS TotalDiscount FROM payment AS p 
								LEFT JOIN clients AS c ON c.c_id = p.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND p.pay_date BETWEEN '$f_date' AND '$t_date' AND p.pay_ent_by = '$e_id' AND p.pay_mode = 'bKash'
								GROUP BY c.z_id"); 
					$row18 = mysql_fetch_array($result18);
				
					$result2 = mysql_query("SELECT z.z_id, z.z_name, SUM(b.amount) AS SignupAmount FROM bill_signup AS b 
								LEFT JOIN clients AS c ON c.c_id = b.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND b.pay_date BETWEEN '$f_date' AND '$t_date' AND b.ent_by = '$e_id' AND b.bill_type = '1'
								GROUP BY c.z_id"); 
					$row2 = mysql_fetch_array($result2);
					
					$result20 = mysql_query("SELECT z.z_id, z.z_name, SUM(b.amount) AS SignupAmount FROM bill_signup AS b 
								LEFT JOIN clients AS c ON c.c_id = b.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND b.pay_date BETWEEN '$f_date' AND '$t_date' AND b.ent_by = '$e_id' AND b.bill_type = '2'
								GROUP BY c.z_id"); 
					$row20 = mysql_fetch_array($result20);
					
					$result21 = mysql_query("SELECT z.z_id, z.z_name, SUM(b.amount) AS SignupAmount FROM bill_signup AS b 
								LEFT JOIN clients AS c ON c.c_id = b.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE c.z_id = '$ZoneId' AND b.pay_date BETWEEN '$f_date' AND '$t_date' AND b.ent_by = '$e_id' AND b.bill_type = '3'
								GROUP BY c.z_id"); 
					$row21 = mysql_fetch_array($result21);
				}	
				
				
				$TotalBillbKash = $row18['TotalBill'];
				$TotalBill = $row1['TotalBill'];
				$SignupAmount = $row2['SignupAmount'];
				$ReactivationAmount = $row20['SignupAmount'];
				$OthersAmount = $row21['SignupAmount'];
				$Total = $TotalBill + $SignupAmount + $TotalBillbKash + $ReactivationAmount + $OthersAmount;
				
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(10,5,$x,'LTB',0,'C',true);
				$pdf->Cell(15,5,$ZoneName,'LTB',0,'L',true);
				$pdf->Cell(30,5,number_format($TotalBill,0),'LTB',0,'R',true);
				$pdf->Cell(30,5,number_format($TotalBillbKash,0),'LTB',0,'R',true);
				$pdf->Cell(30,5,number_format($SignupAmount,0),'LTB',0,'R',true);
				$pdf->Cell(30,5,number_format($ReactivationAmount,0),'LTB',0,'R',true);
				$pdf->Cell(30,5,number_format($OthersAmount,0),'LTB',0,'R',true);
				$pdf->Cell(30,5,number_format($Total,0),'LTBR',0,'R',true);
				$pdf->Ln();
				
			$ActualBill += $TotalBill;
			$BillbKash += $TotalBillbKash;
			$TotalSignup += $SignupAmount;
			$TotalReactivation += $ReactivationAmount;
			$TotalOthers += $OthersAmount;
			$SubTotal += $Total;
			$x++;
		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(25,6,'Total','LTB',0,'C',true);
			$pdf->Cell(30,6,number_format($ActualBill,0),'LTBR',0,'R',true);
			$pdf->Cell(30,6,number_format($BillbKash,0),'LTBR',0,'R',true);
			$pdf->Cell(30,6,number_format($TotalSignup,0),'LTBR',0,'R',true);
			$pdf->Cell(30,6,number_format($TotalReactivation,0),'LTBR',0,'R',true);
			$pdf->Cell(30,6,number_format($TotalOthers,0),'LTBR',0,'R',true);
			$pdf->Cell(30,6,number_format($SubTotal,0),'LTBR',0,'R',true);
			
			$pdf->Ln();
			
			
			
			
			
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFont('Helvetica','',8);
			$pdf->SetX(2);
			
			$pdf->Cell(205,6,'Collection Form '.$FastDate.' To '.$LastDate,'LTBR',0,'C',true);
			$pdf->Ln();
			$pdf->SetX(2);
			$pdf->Cell(10,6,'S/L','LTB',0,'C',true);
			$pdf->Cell(15,6,'Date','LTB',0,'L',true);
			$pdf->Cell(15,6,'ID','LTB',0,'R',true);
			$pdf->Cell(20,6,'Name','LTB',0,'R',true);
			$pdf->Cell(15,6,'Packeg','LTB',0,'R',true);
			$pdf->Cell(15,6,'Bill Type','LTB',0,'R',true);
			$pdf->Cell(15,6,'Amount','LTB',0,'R',true);
			$pdf->Cell(15,6,'Mode','LTBR',0,'R',true);
			$pdf->Cell(20,6,'Entry By','LTBR',0,'R',true);
			$pdf->Cell(30,6,'Note','LTBR',0,'R',true);
			$pdf->Ln();
			
		$result = mysql_query("SELECT * FROM zone WHERE z_id = '$z_id' ORDER BY z_name");  							
		
			$TotalOthersBill = 0;
			$TotalMonthlyBill = 0;
			$SubTotal = 0;
			$x = 1;
		while ($row=mysql_fetch_array($result)) {
				$ZoneId = $row['z_id'];
				$ZoneName = $row['z_name'];
				$ZoneNameBn = $row['z_bn_name'];
				
				if($e_id=='all'){
					$result1 = mysql_query("SELECT a.pay_date, a.c_id, c.c_name, w.p_name, a.Bill_typ, a.pay_amount, a.pay_mode, e.e_name, a.pay_desc, a.others, a.monthly FROM
											(SELECT p.c_id, p.pay_amount, p.pay_date, 'Monthly Bill' AS Bill_typ, p.id, p.pay_mode, p.pay_ent_by, p.pay_desc, '0' AS others, p.pay_amount AS monthly FROM payment AS p

											WHERE p.pay_amount != '0' AND p.pay_date BETWEEN '$f_date' AND '$t_date'

											UNION

											SELECT b.c_id, b.amount, b.pay_date, t.type, b.id, '', b.ent_by, b.bill_dsc, b.amount AS others, '0' FROM bill_signup AS b
											LEFT JOIN bills_type AS t
											ON t.bill_type = b.bill_type

											WHERE b.pay_date BETWEEN '$f_date' AND '$t_date'
											) AS a

											LEFT JOIN clients AS c
											ON c.c_id = a.c_id
											LEFT JOIN zone AS z
											ON z.z_id = c.z_id
											LEFT JOIN emp_info AS e
											ON e.e_id = a.pay_ent_by
											LEFT JOIN package AS w
											ON w.p_id = c.p_id
											WHERE z.z_id = '$ZoneId'

											ORDER BY a.pay_date"); 
					$row1 = mysql_fetch_array($result1);
					
					
				}else{
					$result1 = mysql_query("SELECT a.pay_date, a.c_id AS Client, c.c_name AS cl_name, w.p_name, a.Bill_typ, a.pay_amount, a.pay_mode, e.e_name, a.pay_desc, a.others, a.monthly FROM
											(SELECT p.c_id, p.pay_amount, p.pay_date, 'Monthly Bill' AS Bill_typ, p.id, p.pay_mode, p.pay_ent_by, p.pay_desc, '0' AS others, p.pay_amount AS monthly FROM payment AS p

											WHERE p.pay_amount != '0' AND p.pay_date BETWEEN '$f_date' AND '$t_date'

											UNION

											SELECT b.c_id, b.amount, b.pay_date, t.type, b.id, '', b.ent_by, b.bill_dsc, b.amount AS others, '0' FROM bill_signup AS b
											LEFT JOIN bills_type AS t
											ON t.bill_type = b.bill_type

											WHERE b.pay_date BETWEEN '$f_date' AND '$t_date'
											) AS a

											LEFT JOIN clients AS c
											ON c.c_id = a.c_id
											LEFT JOIN zone AS z
											ON z.z_id = c.z_id
											LEFT JOIN emp_info AS e
											ON e.e_id = a.pay_ent_by
											LEFT JOIN package AS w
											ON w.p_id = c.p_id
											WHERE z.z_id = '$ZoneId' AND e.e_id = '$e_id'

											ORDER BY a.pay_date"); 
					$row1 = mysql_fetch_array($result1);
				}	
				
				$pay_date = $row1['pay_date'];
				$Client = $row1['Client'];
				$cl_name = $row1['cl_name'];
				$p_name = $row1['p_name'];
				$Bill_typ = $row1['Bill_typ'];
				$pay_mode = $row1['pay_mode'];
				$e_name = $row1['e_name'];
				$pay_desc = $row1['pay_desc'];
				
				$TotalBill = $row1['pay_amount'];
				$TotalOthers = $row1['others'];
				$TotalMonthly = $row1['monthly'];
				$Total = $TotalOthers + $TotalMonthly;
				
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(10,5,$x,'LTB',0,'C',true);
				$pdf->Cell(15,5,$pay_date,'LTB',0,'L',true);
				$pdf->Cell(15,5,$Client,'LTB',0,'L',true);
				$pdf->Cell(20,5,$cl_name,'LTB',0,'L',true);
				$pdf->Cell(15,5,$p_name,'LTB',0,'L',true);
				$pdf->Cell(15,5,$Bill_typ,'LTB',0,'L',true);
				$pdf->Cell(15,5,number_format($TotalBill,0),'LTB',0,'R',true);
				$pdf->Cell(15,5,$pay_mode,'LTB',0,'L',true);
				$pdf->Cell(20,5,$e_name,'LTB',0,'L',true);
				$pdf->Cell(30,5,$pay_desc,'LTBR',0,'L',true);
				$pdf->Ln();
				
			$SubTotal += $TotalBill;
			$TotalOthersBill += $TotalOthers;
			$TotalMonthlyBill += $TotalMonthly;
			$x++;
		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(25,6,'Total','LTB',0,'C',true);
			$pdf->Cell(60,6,number_format($SubTotal,0),'LTBR',0,'R',true);
			$pdf->Cell(60,6,number_format($TotalOthersBill,0),'LTBR',0,'R',true);
			$pdf->Cell(60,6,number_format($TotalMonthlyBill,0),'LTBR',0,'R',true);
			
			$pdf->Ln();
			
			
}
$pdf->Output();
?>