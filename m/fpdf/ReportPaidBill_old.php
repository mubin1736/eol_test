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
			
			$this->Cell(20,6,'ID','LTB',0,'L',true);
			$this->Cell(30,6,'Clint Address','LTBR',0,'C',true);
			$this->Cell(30,6,'Cell No','LTB',0,'C',true);
			$this->Cell(20,6,'Actual Bill','LTB',0,'C',true); 
			$this->Cell(15,6,'Due','LTB',0,'C',true);
			$this->Cell(15,6,'Advance','LTB',0,'C',true);
			$this->Cell(20,6,'Total','LTBR',0,'C',true);
			$this->Cell(25,6,'Note','LTBR',0,'C',true);
			$this->Cell(15,6,'Payment','LTBR',0,'C',true);
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
					$this->SetDrawColor(8, 102, 198);
					parent::Footer();
				}
		}

		//Connect to database
		include("connection.php");

		$pdf=new PDF();
		$pdf->AddPage();
		
		$result = mysql_query("SELECT b.id, b.c_id, c.address, c.cell, c.note, p.p_price, c.discount, b.adv_bill, SUM(b.bill_amount) AS amt, SUM(b.pay_amount) AS pay, SUM(b.bill_discount) AS dsc FROM billing AS b
								LEFT JOIN package AS p ON p.p_id = b.p_id
								LEFT JOIN clients AS c ON c.c_id = b.c_id
								WHERE b.sts = 0 AND b.bill_sts = 1 AND b.bill_date BETWEEN '$f_date' AND '$t_date' 
								GROUP BY c_id ORDER BY c.address");
		$act_bill = 0;
		$due_bill = 0;
		$advanc_bill = 0;
		$tot_bill = 0;
		$pay_bill = 0;
		while ($row=mysql_fetch_array($result)) {
				$c_id = $row['c_id'];
				$address = $row['address'];
				$cell = $row['cell'];
				$p_price = $row['p_price'];
				$discount = $row['discount'];
				$adv_bill = $row['adv_bill'];
				$amt = $row['amt'];
				$pay = $row['pay'];
				$dsc = $row['dsc'];
				$note = $row['note'];
				$due1 = $amt - $p_price;
				$tot_pay = $pay + $adv_bill;
				
				if($due1 <= 0){
					$due = '0.00';
				}
				else{
					$due = $due1;
				}
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				
				$pdf->Cell(20,5,$c_id,'LTB',0,'L',true);
				$pdf->Cell(30,5,$address,'LTBR',0,'L',true);
				$pdf->Cell(30,5,$cell,'LTB',0,'L',true);
				$pdf->Cell(20,5,$p_price,'LTB',0,'R',true); 
				$pdf->Cell(15,5,$due,'LTB',0,'R',true);
				$pdf->Cell(15,5,$adv_bill,'LTB',0,'R',true);
				$pdf->Cell(20,5,$amt,'LTBR',0,'R',true);
				$pdf->Cell(25,5,$note,'LTBR',0,'L',true);
				$pdf->Cell(15,5,$tot_pay,'LTBR',0,'R',true);
				$pdf->Ln();
				
			$act_bill += $p_price;
			$due_bill += $due;
			$advanc_bill += $adv_bill;
			$tot_bill += $amt;
			$pay_bill += $tot_pay;
			
		}
			$pdf->SetX(10);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(80,6,'Total','LTB',0,'C',true);
			$pdf->Cell(20,6,number_format($act_bill,2),'LTB',0,'R',true); 
			$pdf->Cell(15,6,number_format($due_bill,2),'LTB',0,'R',true);
			$pdf->Cell(15,6,number_format($advanc_bill,2),'LTB',0,'R',true);
			$pdf->Cell(20,6,number_format($tot_bill,2),'LTBR',0,'R',true);
			$pdf->Cell(25,6,'','LTBR',0,'C',true);
			$pdf->Cell(15,6,number_format($pay_bill,2),'LTBR',0,'R',true);
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
			
			$this->Cell(20,6,'ID','LTB',0,'L',true);
			$this->Cell(30,6,'Clint Address','LTBR',0,'C',true);
			$this->Cell(30,6,'Cell No','LTB',0,'C',true);
			$this->Cell(20,6,'Actual Bill','LTB',0,'C',true); 
			$this->Cell(15,6,'Due','LTB',0,'C',true);
			$this->Cell(15,6,'Advance','LTB',0,'C',true);
			$this->Cell(20,6,'Total','LTBR',0,'C',true);
			$this->Cell(25,6,'Note','LTBR',0,'C',true);
			$this->Cell(15,6,'Payment','LTBR',0,'C',true);
			$this->Ln();
			
		}
		function Footer()
				{
					//Position at 1.5 cm from bottom
					$this->SetY(-15);
					//Arial italic 8
					$this->SetFont('Helvetica','I',8);
					//Page number
					$this->Cell(0,10,'Page '.$this->PageNo().'/Hipro','T',0,'C');
					$this->SetDrawColor(8, 102, 198);
					parent::Footer();
				}
		}

		//Connect to database
		include("connection.php");

		$pdf=new PDF();
		$pdf->AddPage();
		
		$result = mysql_query("SELECT b.id, b.c_id, c.address, c.cell, c.note, p.p_price, c.discount, b.adv_bill, SUM(b.bill_amount) AS amt, SUM(b.pay_amount) AS pay, SUM(b.bill_discount) AS dsc FROM billing AS b
								LEFT JOIN package AS p ON p.p_id = b.p_id
								LEFT JOIN clients AS c ON c.c_id = b.c_id
								WHERE b.sts = 0 AND b.bill_sts = 1 AND c.z_id = '$z_id' AND b.bill_date BETWEEN '$f_date' AND '$t_date'
								GROUP BY c_id ORDER BY c.address");
		$act_bill = 0;
		$due_bill = 0;
		$advanc_bill = 0;
		$tot_bill = 0;
		$pay_bill = 0;
		while ($row=mysql_fetch_array($result)) {
				$c_id = $row['c_id'];
				$address = $row['address'];
				$cell = $row['cell'];
				$p_price = $row['p_price'];
				$discount = $row['discount'];
				$adv_bill = $row['adv_bill'];
				$amt = $row['amt'];
				$pay = $row['pay'];
				$dsc = $row['dsc'];
				$note = $row['note'];
				$due1 = $amt - $p_price;
				$tot_pay = $pay + $adv_bill;
				
				if($due1 <= 0){
					$due = '0.00';
				}
				else{
					$due = $due1;
				}
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				
				$pdf->Cell(20,5,$c_id,'LTB',0,'L',true);
				$pdf->Cell(30,5,$address,'LTBR',0,'L',true);
				$pdf->Cell(30,5,$cell,'LTB',0,'L',true);
				$pdf->Cell(20,5,$p_price,'LTB',0,'R',true); 
				$pdf->Cell(15,5,$due,'LTB',0,'R',true);
				$pdf->Cell(15,5,$adv_bill,'LTB',0,'R',true);
				$pdf->Cell(20,5,$amt,'LTBR',0,'R',true);
				$pdf->Cell(25,5,$note,'LTBR',0,'L',true);
				$pdf->Cell(15,5,$tot_pay,'LTBR',0,'R',true);
				$pdf->Ln();
				
			$act_bill += $p_price;
			$due_bill += $due;
			$advanc_bill += $adv_bill;
			$tot_bill += $amt;
			$pay_bill += $tot_pay;	

		}
			$pdf->SetX(10);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(80,6,'Total','LTB',0,'C',true);
			$pdf->Cell(20,6,number_format($act_bill,2),'LTB',0,'R',true); 
			$pdf->Cell(15,6,number_format($due_bill,2),'LTB',0,'R',true);
			$pdf->Cell(15,6,number_format($advanc_bill,2),'LTB',0,'R',true);
			$pdf->Cell(20,6,number_format($tot_bill,2),'LTBR',0,'R',true);
			$pdf->Cell(25,6,'','LTBR',0,'C',true);
			$pdf->Cell(15,6,number_format($pay_bill,2),'LTBR',0,'R',true);
			$pdf->Ln();
}
$pdf->Output();
?>