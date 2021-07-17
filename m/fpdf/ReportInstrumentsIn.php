<?php
require('mysql_table.php');
require('connection.php');

$p_id = $_REQUEST['p_id'];
$e_id = $_REQUEST['e_id'];
$f_date = $_REQUEST['f_date'];
$t_date = $_REQUEST['t_date'];

if($p_id == 'all'){
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
			$this->Cell(13,6,'V.No','LTB',0,'L',true);
			$this->Cell(18,6,'Purchaser','LTBR',0,'C',true);
			$this->Cell(25,6,'Vendor','LTB',0,'C',true);
			$this->Cell(20,6,'S/L No','LTB',0,'C',true);
			$this->Cell(25,6,'Item','LTB',0,'C',true);
			$this->Cell(11,6,'Price','LTB',0,'C',true);
			$this->Cell(28,6,'In Time','LTB',0,'C',true);
			$this->Cell(17,6,'Status','LTB',0,'C',true);
			$this->Cell(32,6,'Note','LTBR',0,'C',true);
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
		if($e_id == 'all'){
		$result = mysql_query("SELECT i.id, i.purchase_date, i.voucher_no, i.purchase_by, e.e_name AS purchaseby, i.vendor, v.v_name, i.p_sts, i.p_sl_no, i.p_id, p.pro_name, i.brand, i.quantity, i.price, i.rimarks, i.entry_by, a.e_name AS entryby, i.entry_time, i.sts FROM store_in_instruments AS i
													LEFT JOIN emp_info AS e
													ON e.e_id = i.purchase_by
													LEFT JOIN vendor AS v
													ON v.id = i.vendor
													LEFT JOIN product AS p
													ON p.id = i.p_id
													LEFT JOIN emp_info AS a
													ON a.e_id = i.entry_by
													WHERE i.purchase_date BETWEEN '$f_date' AND '$t_date' AND i.status = '0'
													ORDER BY i.purchase_date ASC");  	
							}
		else{
			$result = mysql_query("SELECT i.id, i.purchase_date, i.voucher_no, i.purchase_by, e.e_name AS purchaseby, i.vendor, v.v_name, i.p_sts, i.p_sl_no, i.p_id, p.pro_name, i.brand, i.quantity, i.price, i.rimarks, i.entry_by, a.e_name AS entryby, i.entry_time, i.sts FROM store_in_instruments AS i
													LEFT JOIN emp_info AS e
													ON e.e_id = i.purchase_by
													LEFT JOIN vendor AS v
													ON v.id = i.vendor
													LEFT JOIN product AS p
													ON p.id = i.p_id
													LEFT JOIN emp_info AS a
													ON a.e_id = i.entry_by
													WHERE i.purchase_date BETWEEN '$f_date' AND '$t_date' AND i.status = '0' AND i.purchase_by = '$e_id'
													ORDER BY i.purchase_date ASC");
		}
		while ($row=mysql_fetch_array($result)) {
			
			if($row['sts'] == '0'){
										$aa = 'Available';
									}
									if($row['sts'] == '1'){
										$aa = 'Unavailable';
									}
			
				$purchase_date = $row['purchase_date'];
				$voucher_no = $row['voucher_no'];
				$purchaseby = $row['purchaseby'];
				$v_name = $row['v_name'];
				$p_sl_no = $row['p_sl_no'];
				$pro_name = $row['pro_name'];
				$price = $row['price'];
				$entryby = $row['entryby'];
				$entry_time = $row['entry_time'];
				$rimarks = $row['rimarks'];
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(17,5,$purchase_date,'LTB',0,'L',true);
				$pdf->Cell(13,5,$voucher_no,'LTB',0,'L',true);
				$pdf->Cell(18,5,$purchaseby,'LTBR',0,'L',true);
				$pdf->Cell(25,5,$v_name,'LTB',0,'L',true);
				$pdf->Cell(20,5,$p_sl_no,'LTB',0,'L',true);
				$pdf->Cell(25,5,$pro_name,'LTB',0,'L',true);
				$pdf->Cell(11,5,number_format($price,0),'LTB',0,'R',true);
				$pdf->Cell(28,5,$entry_time,'LTBR',0,'L',true);
				$pdf->Cell(17,5,$aa,'LTBR',0,'L',true);
				$pdf->Cell(32,5,$rimarks,'LTBR',0,'L',true);
				$pdf->Ln();
				
			$TotalPayment += $price;

		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(118,6,'Total','LTB',0,'C',true);
			$pdf->Cell(11,6,number_format($TotalPayment,0),'LTBR',0,'R',true);
			$pdf->Cell(77,6,'','LTBR',0,'L',true);
			
			$pdf->Ln();
}
else{	class PDF extends PDF_MySQL_Table
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
			$this->Cell(13,6,'V.No','LTB',0,'L',true);
			$this->Cell(18,6,'Purchaser','LTBR',0,'C',true);
			$this->Cell(25,6,'Vendor','LTB',0,'C',true);
			$this->Cell(20,6,'S/L No','LTB',0,'C',true);
			$this->Cell(25,6,'Item','LTB',0,'C',true);
			$this->Cell(11,6,'Price','LTB',0,'C',true);
			$this->Cell(28,6,'In Time','LTB',0,'C',true);
			$this->Cell(17,6,'Status','LTB',0,'C',true);
			$this->Cell(32,6,'Note','LTBR',0,'C',true);
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
		if($e_id == 'all'){
		$result = mysql_query("SELECT i.id, i.purchase_date, i.voucher_no, i.purchase_by, e.e_name AS purchaseby, i.vendor, v.v_name, i.p_sts, i.p_sl_no, i.p_id, p.pro_name, i.brand, i.quantity, i.price, i.rimarks, i.entry_by, a.e_name AS entryby, i.entry_time, i.sts FROM store_in_instruments AS i
													LEFT JOIN emp_info AS e
													ON e.e_id = i.purchase_by
													LEFT JOIN vendor AS v
													ON v.id = i.vendor
													LEFT JOIN product AS p
													ON p.id = i.p_id
													LEFT JOIN emp_info AS a
													ON a.e_id = i.entry_by
													WHERE i.purchase_date BETWEEN '$f_date' AND '$t_date' AND i.status = '0' AND i.p_id = '$p_id'
													ORDER BY i.purchase_date ASC");  	
							}
		else{
			$result = mysql_query("SELECT i.id, i.purchase_date, i.voucher_no, i.purchase_by, e.e_name AS purchaseby, i.vendor, v.v_name, i.p_sts, i.p_sl_no, i.p_id, p.pro_name, i.brand, i.quantity, i.price, i.rimarks, i.entry_by, a.e_name AS entryby, i.entry_time, i.sts FROM store_in_instruments AS i
													LEFT JOIN emp_info AS e
													ON e.e_id = i.purchase_by
													LEFT JOIN vendor AS v
													ON v.id = i.vendor
													LEFT JOIN product AS p
													ON p.id = i.p_id
													LEFT JOIN emp_info AS a
													ON a.e_id = i.entry_by
													WHERE i.purchase_date BETWEEN '$f_date' AND '$t_date' AND i.status = '0' AND i.p_id = '$p_id' AND i.purchase_by = '$e_id'
													ORDER BY i.purchase_date ASC");
		}
		while ($row=mysql_fetch_array($result)) {
			
			if($row['sts'] == '0'){
										$aa = 'Available';
									}
									if($row['sts'] == '1'){
										$aa = 'Unavailable';
									}
			
				$purchase_date = $row['purchase_date'];
				$voucher_no = $row['voucher_no'];
				$purchaseby = $row['purchaseby'];
				$v_name = $row['v_name'];
				$p_sl_no = $row['p_sl_no'];
				$pro_name = $row['pro_name'];
				$price = $row['price'];
				$entryby = $row['entryby'];
				$entry_time = $row['entry_time'];
				$rimarks = $row['rimarks'];
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(17,5,$purchase_date,'LTB',0,'L',true);
				$pdf->Cell(13,5,$voucher_no,'LTB',0,'L',true);
				$pdf->Cell(18,5,$purchaseby,'LTBR',0,'L',true);
				$pdf->Cell(25,5,$v_name,'LTB',0,'L',true);
				$pdf->Cell(20,5,$p_sl_no,'LTB',0,'L',true);
				$pdf->Cell(25,5,$pro_name,'LTB',0,'L',true);
				$pdf->Cell(11,5,number_format($price,0),'LTB',0,'R',true);
				$pdf->Cell(28,5,$entry_time,'LTBR',0,'L',true);
				$pdf->Cell(17,5,$aa,'LTBR',0,'L',true);
				$pdf->Cell(32,5,$rimarks,'LTBR',0,'L',true);
				$pdf->Ln();
				
			$TotalPayment += $price;

		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(118,6,'Total','LTB',0,'C',true);
			$pdf->Cell(11,6,number_format($TotalPayment,0),'LTBR',0,'R',true);
			$pdf->Cell(77,6,'','LTBR',0,'L',true);
			
			$pdf->Ln();
}
$pdf->Output();

?>