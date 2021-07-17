<?php
require('mysql_table.php');
require('connection.php');

$z_id = $_REQUEST['z_id'];
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
			
			$pdf->Cell(205,6,'Due Form '.$FastDate.' To '.$LastDate,'LTBR',0,'C',true);
			$pdf->Ln();
			$pdf->SetX(2);
			$pdf->Cell(15,6,'S/L','LTB',0,'C',true);
			$pdf->Cell(150,6,'Zone Name','LTB',0,'L',true);
			$pdf->Cell(40,6,'Total Due','LTBR',0,'R',true);
			$pdf->Ln();
			
		$result = mysql_query("SELECT l.z_name, l.TotalBill, t.TotalBills, t.TotalDiscount1 FROM
								(
								SELECT z.z_id, z.z_name, SUM(b.bill_amount) AS TotalBill, SUM(b.discount) AS TotalDiscount FROM billing AS b 
								LEFT JOIN clients AS c ON c.c_id = b.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE b.bill_date BETWEEN '$f_date' AND '$t_date' AND c. mac_user = '0'
								GROUP BY c.z_id
								)l
								LEFT JOIN
								(
								SELECT z.z_id, z.z_name, SUM(p.pay_amount) AS TotalBills, SUM(p.bill_discount) AS TotalDiscount1 FROM payment AS p 
								LEFT JOIN clients AS c ON c.c_id = p.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE p.pay_date BETWEEN '$f_date' AND '$t_date' AND c. mac_user = '0'
								GROUP BY c.z_id
								)t
								ON l.z_id = t.z_id");  							
		
			$ActualBill = 0;
			$TotalSignup = 0;
			$SubTotal = 0;
			$x = 1;
		while ($row=mysql_fetch_array($result)) {
				$ZoneName = $row['z_name'];
				$ZoneNameBn = $row['z_bn_name'];
				$TotalBill1 = $row['TotalBill'];
				$TotalDiscount = $row['TotalDiscount1'];
				$TotalBill2 = $row['TotalBills'];
				$TotalBill = ($TotalBill1 - ($TotalBill2 + $TotalDiscount));
				$yourtext = iconv('UTF-8', 'windows-1252', $ZoneNameBn);
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(15,5,$x,'LTB',0,'C',true);
				//$pdf->Cell(70,5,$ZoneName.' ('.$yourtext.')','LTB',0,'L',true);
				$pdf->Cell(150,5,$ZoneName,'LTB',0,'L',true);
				$pdf->Cell(40,5,number_format($TotalBill,0),'LTBR',0,'R',true);
				$pdf->Ln();
				
			$ActualBill += $TotalBill;
			$TotalSignup += $SignupAmount;
			$SubTotal += $Total;
			$x++;
		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(165,6,'Total','LTB',0,'C',true);
			$pdf->Cell(40,6,number_format($ActualBill,0),'LTBR',0,'R',true);
			
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
			
			$pdf->Cell(205,6,'Due Form '.$FastDate.' To '.$LastDate,'LTBR',0,'C',true);
			$pdf->Ln();
			$pdf->SetX(2);
			$pdf->Cell(15,6,'S/L','LTB',0,'C',true);
			$pdf->Cell(150,6,'Zone Name','LTB',0,'L',true);
			$pdf->Cell(40,6,'Total Due','LTBR',0,'R',true);
			$pdf->Ln();
			
		$result = mysql_query("SELECT l.z_name, l.TotalBill, t.TotalBills, t.TotalDiscount1 FROM
								(
								SELECT z.z_id, z.z_name, SUM(b.bill_amount) AS TotalBill, SUM(b.discount) AS TotalDiscount FROM billing AS b 
								LEFT JOIN clients AS c ON c.c_id = b.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE b.bill_date BETWEEN '$f_date' AND '$t_date' AND c.z_id = '$z_id' AND c. mac_user = '0'
								GROUP BY c.z_id
								)l
								LEFT JOIN
								(
								SELECT z.z_id, z.z_name, SUM(p.pay_amount) AS TotalBills, SUM(p.bill_discount) AS TotalDiscount1 FROM payment AS p 
								LEFT JOIN clients AS c ON c.c_id = p.c_id 
								LEFT JOIN zone AS z ON z.z_id = c.z_id
								WHERE p.pay_date BETWEEN '$f_date' AND '$t_date' AND c.z_id = '$z_id' AND c. mac_user = '0'
								GROUP BY c.z_id
								)t
								ON l.z_id = t.z_id");  							
		
			$ActualBill = 0;
			$TotalSignup = 0;
			$SubTotal = 0;
			$x = 1;
		while ($row=mysql_fetch_array($result)) {
				$ZoneName = $row['z_name'];
				$ZoneNameBn = $row['z_bn_name'];
				$TotalBill1 = $row['TotalBill'];
				$TotalDiscount = $row['TotalDiscount1'];
				$TotalBill2 = $row['TotalBills'];
				$TotalBill = ($TotalBill1 - ($TotalBill2 + $TotalDiscount));
				$yourtext = iconv('UTF-8', 'windows-1252', $ZoneNameBn);
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(15,5,$x,'LTB',0,'C',true);
				//$pdf->Cell(70,5,$ZoneName.' ('.$yourtext.')','LTB',0,'L',true);
				$pdf->Cell(150,5,$ZoneName,'LTB',0,'L',true);
				$pdf->Cell(40,5,number_format($TotalBill,0),'LTBR',0,'R',true);
				$pdf->Ln();
				
			$ActualBill += $TotalBill;
			$TotalSignup += $SignupAmount;
			$SubTotal += $Total;
			$x++;
		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(165,6,'Total','LTB',0,'C',true);
			$pdf->Cell(40,6,number_format($ActualBill,0),'LTBR',0,'R',true);
			
			$pdf->Ln();
}
$pdf->Output();
?>