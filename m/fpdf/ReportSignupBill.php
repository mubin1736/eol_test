<?php
require('mysql_table.php');
require('connection.php');

$bill_type = $_REQUEST['bill_type'];
$f_date = $_REQUEST['f_date'];
$t_date = $_REQUEST['t_date'];

if($bill_type == 'all'){
	class PDF extends PDF_MySQL_Table
		{
		function Header()
		{
			parent::Header();
			
			$this->SetFillColor(160, 160, 160);
			$this->SetTextColor(0,0,0);
			$this->SetDrawColor(8, 102, 198);
			$this->SetFont('Helvetica','',8);
			
			$this->Cell(8,6,'S/L','LTB',0,'L',true);
			$this->Cell(20,6,'Date','LTB',0,'L',true);
			$this->Cell(27,6,'Clint ID','LTBR',0,'C',true);
			$this->Cell(50,6,'Clint Address','LTB',0,'C',true);
			$this->Cell(30,6,'Bill Type','LTB',0,'C',true); 
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
		
		$result = mysql_query("SELECT b.id, c.c_id, c.address, b.bill_type, c.c_name, b.pay_date, b.amount, b.bill_dsc FROM bill_signup AS b
							LEFT JOIN clients AS c ON b.c_id = c.c_id 
							WHERE b.pay_date BETWEEN '$f_date' AND '$t_date'");
		
		
		$tot_bill = 0;
		$x = 1;
		while ($row=mysql_fetch_array($result)) {
			
				$amount = $row['amount'];
				
				if($row['bill_type'] == '1'){$type = 'Signup Bill';}									
				if($row['bill_type'] == '2'){$type = 'Reactivation Charge';}									
				if($row['bill_type'] == '3'){$type = 'Others Bill';}
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				
				$pdf->Cell(8,5,$x,'LTB',0,'L',true);
				$pdf->Cell(20,5,$row['pay_date'],'LTB',0,'L',true);
				$pdf->Cell(27,5,$row['c_id'],'LTBR',0,'L',true);
				$pdf->Cell(50,5,$row['address'],'LTB',0,'L',true);
				$pdf->Cell(30,5,$type,'LTB',0,'L',true);
				$pdf->Cell(20,5,number_format($amount,0),'LTB',0,'R',true); 
				$pdf->Cell(35,5,$row['bill_dsc'],'LTBR',0,'L',true);
				$pdf->Ln();
				
			
			$tot_bill += $amount;
			$x++;

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
			
			$this->Cell(8,6,'S/L','LTB',0,'L',true);
			$this->Cell(20,6,'Date','LTB',0,'L',true);
			$this->Cell(27,6,'Clint ID','LTBR',0,'C',true);
			$this->Cell(50,6,'Clint Address','LTB',0,'C',true);
			$this->Cell(30,6,'Bill Type','LTB',0,'C',true); 
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
		
		$result = mysql_query("SELECT b.id, c.c_id, c.address, b.bill_type, c.c_name, b.pay_date, b.amount, b.bill_dsc FROM bill_signup AS b
							LEFT JOIN clients AS c ON b.c_id = c.c_id 
							WHERE b.pay_date BETWEEN '$f_date' AND '$t_date' AND b.bill_type = '$bill_type'");
		
		
		$tot_bill = 0;
		$x = 1;
		while ($row=mysql_fetch_array($result)) {
			
				$amount = $row['amount'];
				
				if($row['bill_type'] == '1'){$type = 'Signup Bill';}									
				if($row['bill_type'] == '2'){$type = 'Reactivation Charge';}									
				if($row['bill_type'] == '3'){$type = 'Others Bill';}
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				
				$pdf->Cell(8,5,$x,'LTB',0,'L',true);
				$pdf->Cell(20,5,$row['pay_date'],'LTB',0,'L',true);
				$pdf->Cell(27,5,$row['c_id'],'LTBR',0,'L',true);
				$pdf->Cell(50,5,$row['address'],'LTB',0,'L',true);
				$pdf->Cell(30,5,$type,'LTB',0,'L',true);
				$pdf->Cell(20,5,number_format($amount,0),'LTB',0,'R',true); 
				$pdf->Cell(35,5,$row['bill_dsc'],'LTBR',0,'L',true);
				$pdf->Ln();
				
			
			$tot_bill += $amount;
			$x++;

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
}
$pdf->Output();
?>