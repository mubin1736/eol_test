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
			
			$this->Cell(17,6,'Date','LTB',0,'L',true);
			$this->Cell(20,6,'ID','LTB',0,'L',true);
			$this->Cell(37,6,'Clint Address','LTBR',0,'C',true);
			$this->Cell(23,6,'Cell No','LTB',0,'C',true);
			$this->Cell(15,6,'Actual Bill','LTB',0,'C',true); 
			$this->Cell(15,6,'Due','LTB',0,'C',true);
			$this->Cell(15,6,'Total','LTB',0,'C',true);
			$this->Cell(15,6,'Discount','LTB',0,'C',true);
			$this->Cell(15,6,'Advance','LTB',0,'C',true);
			$this->Cell(15,6,'Paid','LTBR',0,'C',true);
			$this->Cell(20,6,'Note','LTBR',0,'C',true); 
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
		
		/* $result = mysql_query("SELECT b.id, b.c_id, c.address, c.cell, c.note, sum(b.p_price) AS p_price, sum(b.discount) AS discount, 
							    sum(b.adv_bill) AS adv_bill, SUM(b.bill_amount) AS amt, SUM(b.pay_amount) AS pay, SUM(b.bill_discount) AS dsc 
								FROM billing AS b
								LEFT JOIN clients AS c ON c.c_id = b.c_id
								WHERE b.sts = 0 AND b.pay_date BETWEEN '$f_date' AND '$t_date' AND b.p_id != '16' AND b.pay_amount != '0.00' OR bill_discount != '0.00'
								GROUP BY b.c_id ORDER BY c.address"); */
							
		$result = mysql_query("SELECT b.pay_date, b.id, b.c_id, c.address, c.cell, c.note, sum(b.p_price) AS p_price, sum(b.discount) AS discount, 
							    sum(b.adv_bill) AS adv_bill, SUM(b.bill_amount) AS amt, SUM(b.pay_amount) AS pay, SUM(b.bill_discount) AS dsc 
								FROM billing AS b
								LEFT JOIN clients AS c ON c.c_id = b.c_id
								WHERE b.sts = 0 AND b.pay_date BETWEEN '$f_date' AND '$t_date' AND b.p_id != '13'
								GROUP BY b.c_id ORDER BY c.address");  
		
			$act_bill = 0;
			$due_bill = 0;
			$advanc_bill = 0;
			$tot_dsc = 0;
			$tot_bill = 0;
			$pay_bill = 0;
		while ($row=mysql_fetch_array($result)) {
				$date = $row['pay_date'];
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
				
				$tot_pay = $pay;
				$payable = $amt - $tot_pay;
				$discountAll = $dsc;
				$total = $amt;
				
				//$due1 = ($amt - ($pay+$dsc));
				
				$due1 = $amt - $p_price;
			
				
				if($due1 <= 0){
					$due = '0.00';
				}
				else{
					$due = $due1;
				}
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(17,5,$date,'LTB',0,'L',true);
				$pdf->Cell(20,5,$c_id,'LTB',0,'L',true);
				$pdf->Cell(37,5,$address,'LTBR',0,'L',true);
				$pdf->Cell(23,5,$cell,'LTB',0,'L',true);
				$pdf->Cell(15,5,number_format($p_price,0),'LTB',0,'R',true); 
				$pdf->Cell(15,5,number_format($due,0),'LTB',0,'R',true);
				$pdf->Cell(15,5,number_format($total,0),'LTB',0,'R',true);
				$pdf->Cell(15,5,number_format($dsc,0),'LTB',0,'R',true);
				$pdf->Cell(15,5,number_format($adv_bill,0),'LTB',0,'R',true);
				$pdf->Cell(15,5,number_format($pay,0),'LTBR',0,'R',true);
				$pdf->Cell(20,5,$note,'LTBR',0,'L',true);
				$pdf->Ln();
				
			$act_bill += $p_price;
			$due_bill += $due;
			$advanc_bill += $adv_bill;
			$tot_dsc += $dsc;
			$tot_bill += $total;
			$pay_bill += $pay;

		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(97,6,'Total','LTB',0,'C',true);
			$pdf->Cell(15,6,number_format($act_bill,0),'LTB',0,'R',true); 
			$pdf->Cell(15,6,number_format($due_bill,0),'LTB',0,'R',true);
			$pdf->Cell(15,6,number_format($tot_bill,0),'LTBR',0,'R',true);
			$pdf->Cell(15,6,number_format($tot_dsc,0),'LTB',0,'R',true);
			$pdf->Cell(15,6,number_format($advanc_bill,0),'LTBR',0,'R',true);
			$pdf->Cell(15,6,number_format($pay_bill,0),'LTBR',0,'R',true);
			$pdf->Cell(20,6,$note,'LTBR',0,'C',true);
			
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
			
			$this->Cell(17,6,'Date','LTB',0,'L',true);
			$this->Cell(20,6,'ID','LTB',0,'L',true);
			$this->Cell(37,6,'Clint Address','LTBR',0,'C',true);
			$this->Cell(23,6,'Cell No','LTB',0,'C',true);
			$this->Cell(15,6,'Actual Bill','LTB',0,'C',true); 
			$this->Cell(15,6,'Due','LTB',0,'C',true);
			$this->Cell(15,6,'Total','LTB',0,'C',true);
			$this->Cell(15,6,'Discount','LTB',0,'C',true);
			$this->Cell(15,6,'Advance','LTB',0,'C',true);
			$this->Cell(15,6,'Paid','LTBR',0,'C',true);
			$this->Cell(20,6,'Note','LTBR',0,'C',true); 
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
		
		/* $result = mysql_query("SELECT b.id, b.c_id, c.address, c.cell, c.note, sum(b.p_price) AS p_price, sum(b.discount) AS discount, 
							    sum(b.adv_bill) AS adv_bill, SUM(b.bill_amount) AS amt, SUM(b.pay_amount) AS pay, SUM(b.bill_discount) AS dsc 
								FROM billing AS b
								LEFT JOIN clients AS c ON c.c_id = b.c_id
								WHERE b.sts = 0 AND c.z_id = '$z_id' AND b.pay_date BETWEEN '$f_date' AND '$t_date' AND b.p_id != '16' AND b.pay_amount != '0.00' OR bill_discount != '0.00'
								GROUP BY b.c_id ORDER BY c.address"); */
								
		$result = mysql_query("SELECT b.pay_date, b.id, b.c_id, c.address, c.cell, c.note, sum(b.p_price) AS p_price, sum(b.discount) AS discount, 
							    sum(b.adv_bill) AS adv_bill, SUM(b.bill_amount) AS amt, SUM(b.pay_amount) AS pay, SUM(b.bill_discount) AS dsc 
								FROM billing AS b
								LEFT JOIN clients AS c ON c.c_id = b.c_id
								WHERE b.sts = 0 AND c.z_id = '$z_id' AND b.pay_date BETWEEN '$f_date' AND '$t_date' AND b.p_id != '13'
								GROUP BY b.c_id ORDER BY c.address");
		
			$act_bill = 0;
			$due_bill = 0;
			$advanc_bill = 0;
			$tot_dsc = 0;
			$tot_bill = 0;
			$pay_bill = 0;
		while ($row=mysql_fetch_array($result)) {
				$date = $row['pay_date'];
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
				
				$tot_pay = $pay;
				$payable = $amt - $tot_pay;
				$discountAll = $dsc;
				$total = $amt;
				
				//$due1 = ($amt - ($pay+$dsc));
				
				$due1 = $amt - $p_price;
			
				
				if($due1 <= 0){
					$due = '0.00';
				}
				else{
					$due = $due1;
				}
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(17,5,$date,'LTB',0,'L',true);
				$pdf->Cell(20,5,$c_id,'LTB',0,'L',true);
				$pdf->Cell(37,5,$address,'LTBR',0,'L',true);
				$pdf->Cell(23,5,$cell,'LTB',0,'L',true);
				$pdf->Cell(15,5,number_format($p_price,0),'LTB',0,'R',true); 
				$pdf->Cell(15,5,number_format($due,0),'LTB',0,'R',true);
				$pdf->Cell(15,5,number_format($total,0),'LTB',0,'R',true);
				$pdf->Cell(15,5,number_format($dsc,0),'LTB',0,'R',true);
				$pdf->Cell(15,5,number_format($adv_bill,0),'LTB',0,'R',true);
				$pdf->Cell(15,5,number_format($pay,0),'LTBR',0,'R',true);
				$pdf->Cell(20,5,$note,'LTBR',0,'L',true);
				$pdf->Ln();
				
			$act_bill += $p_price;
			$due_bill += $due;
			$advanc_bill += $adv_bill;
			$tot_dsc += $dsc;
			$tot_bill += $total;
			$pay_bill += $pay;

		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(97,6,'Total','LTB',0,'C',true);
			$pdf->Cell(15,6,number_format($act_bill,0),'LTB',0,'R',true); 
			$pdf->Cell(15,6,number_format($due_bill,0),'LTB',0,'R',true);
			$pdf->Cell(15,6,number_format($tot_bill,0),'LTBR',0,'R',true);
			$pdf->Cell(15,6,number_format($tot_dsc,0),'LTB',0,'R',true);
			$pdf->Cell(15,6,number_format($advanc_bill,0),'LTBR',0,'R',true);
			$pdf->Cell(15,6,number_format($pay_bill,0),'LTBR',0,'R',true);
			$pdf->Cell(20,6,$note,'LTBR',0,'C',true);
			
			$pdf->Ln();
}
$pdf->Output();
?>