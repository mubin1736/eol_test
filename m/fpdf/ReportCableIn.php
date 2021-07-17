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
			
			$this->Cell(16,6,'Date','LTB',0,'L',true);
			$this->Cell(12,6,'V.No','LTB',0,'L',true);
			$this->Cell(15,6,'Purchaser','LTBR',0,'C',true);
			$this->Cell(25,6,'Vendor','LTB',0,'C',true);
			$this->Cell(15,6,'S/L No','LTB',0,'C',true);
			$this->Cell(25,6,'Item','LTB',0,'C',true);
			$this->Cell(14,6,'Cable ID','LTB',0,'C',true);
			$this->Cell(10,6,'Total','LTB',0,'C',true);
			$this->Cell(10,6,'Price','LTB',0,'C',true);
			$this->Cell(16,6,'Condition','LTB',0,'C',true);
			$this->Cell(20,6,'Status','LTB',0,'C',true);
			$this->Cell(27,6,'Note','LTBR',0,'C',true);
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
		$result = mysql_query("SELECT i.id, i.purchase_date, i.voucher_no, i.purchase_by, e.e_name AS purchase_by, v.v_name AS vendor, i.p_sts, i.p_sl_no, p.pro_name, i.brand, IF(i.fiber_id=0,NULL,i.fiber_id) AS fiber_id, IF(i.fibertotal=0,NULL,i.fibertotal) AS fibertotal, i.price, a.e_name AS entry_by, i.entry_time, CASE WHEN i.sts = 0 THEN 'Ready To Out' ELSE 'Already Out' END AS sts, i.rimarks FROM store_in_out_fiber AS i
													LEFT JOIN emp_info AS e
													ON e.e_id = i.purchase_by
													LEFT JOIN vendor AS v
													ON v.id = i.vendor
													LEFT JOIN fiber AS p
													ON p.id = i.p_id
													LEFT JOIN emp_info AS a
													ON a.e_id = i.entry_by WHERE i.purchase_date BETWEEN '$f_date' AND '$t_date' AND i.status = '0'
													ORDER BY i.purchase_date ASC");  	
							}
		else{
			$result = mysql_query("SELECT i.id, i.purchase_date, i.voucher_no, i.purchase_by, e.e_name AS purchase_by, v.v_name AS vendor, i.p_sts, i.p_sl_no, p.pro_name, i.brand, IF(i.fiber_id=0,NULL,i.fiber_id) AS fiber_id, IF(i.fibertotal=0,NULL,i.fibertotal) AS fibertotal, i.price, a.e_name AS entry_by, i.entry_time, CASE WHEN i.sts = 0 THEN 'Ready To Out' ELSE 'Already Out' END AS sts, i.rimarks FROM store_in_out_fiber AS i
													LEFT JOIN emp_info AS e
													ON e.e_id = i.purchase_by
													LEFT JOIN vendor AS v
													ON v.id = i.vendor
													LEFT JOIN fiber AS p
													ON p.id = i.p_id
													LEFT JOIN emp_info AS a
													ON a.e_id = i.entry_by 
													WHERE i.purchase_date BETWEEN '$f_date' AND '$t_date' AND i.status = '0' AND i.purchase_by = '$e_id'
													ORDER BY i.purchase_date ASC");
		}
		while ($row=mysql_fetch_array($result)) {

				$p_sl_no = $row['p_sl_no'];
				$voucher_no = $row['voucher_no'];
				$purchase_date = $row['purchase_date'];
				$pro_name = $row['pro_name'];
				$brand = $row['brand'];
				$p_sts = $row['p_sts'];
				$price = $row['price'];
				$purchase_by = $row['purchase_by'];
				$vendor = $row['vendor'];
				$fiber_id = $row['fiber_id'];
				$fibertotal = $row['fibertotal'];
				$sts = $row['sts'];
				$entry_time = $row['entry_time'];
				$rimarks = $row['rimarks'];
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(16,5,$purchase_date,'LTB',0,'L',true);
				$pdf->Cell(12,5,$voucher_no,'LTB',0,'L',true);
				$pdf->Cell(15,5,$purchase_by,'LTBR',0,'L',true);
				$pdf->Cell(25,5,$vendor,'LTB',0,'L',true);
				$pdf->Cell(15,5,$p_sl_no,'LTB',0,'L',true);
				$pdf->Cell(25,5,$pro_name,'LTB',0,'L',true);
				$pdf->Cell(14,5,$fiber_id,'LTB',0,'L',true);
				$pdf->Cell(10,5,$fibertotal,'LTB',0,'L',true);
				$pdf->Cell(10,5,number_format($price,0),'LTB',0,'R',true);
				$pdf->Cell(16,5,$p_sts,'LTBR',0,'L',true);
				$pdf->Cell(20,5,$sts,'LTBR',0,'L',true);
				$pdf->Cell(27,5,$rimarks,'LTBR',0,'L',true);
				$pdf->Ln();
				
			$TotalCable += $fibertotal;
			$TotalPayment += $price;

		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(122,6,'Total','LTB',0,'C',true);
			$pdf->Cell(10,6,$TotalCable,'LTBR',0,'R',true);
			$pdf->Cell(10,6,number_format($TotalPayment,0),'LTBR',0,'R',true);
			$pdf->Cell(63,6,'','LTBR',0,'L',true);
			
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
			
			$this->Cell(16,6,'Date','LTB',0,'L',true);
			$this->Cell(12,6,'V.No','LTB',0,'L',true);
			$this->Cell(15,6,'Purchaser','LTBR',0,'C',true);
			$this->Cell(25,6,'Vendor','LTB',0,'C',true);
			$this->Cell(15,6,'S/L No','LTB',0,'C',true);
			$this->Cell(25,6,'Item','LTB',0,'C',true);
			$this->Cell(14,6,'Cable ID','LTB',0,'C',true);
			$this->Cell(10,6,'Total','LTB',0,'C',true);
			$this->Cell(10,6,'Price','LTB',0,'C',true);
			$this->Cell(16,6,'Condition','LTB',0,'C',true);
			$this->Cell(20,6,'Status','LTB',0,'C',true);
			$this->Cell(27,6,'Note','LTBR',0,'C',true);
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
		$result = mysql_query("SELECT i.id, i.purchase_date, i.voucher_no, i.purchase_by, i.p_id, e.e_name AS purchase_by, v.v_name AS vendor, i.p_sts, i.p_sl_no, p.pro_name, i.brand, IF(i.fiber_id=0,NULL,i.fiber_id) AS fiber_id, IF(i.fibertotal=0,NULL,i.fibertotal) AS fibertotal, i.price, a.e_name AS entry_by, i.entry_time, CASE WHEN i.sts = 0 THEN 'Ready To Out' ELSE 'Already Out' END AS sts, i.rimarks FROM store_in_out_fiber AS i
													LEFT JOIN emp_info AS e
													ON e.e_id = i.purchase_by
													LEFT JOIN vendor AS v
													ON v.id = i.vendor
													LEFT JOIN fiber AS p
													ON p.id = i.p_id
													LEFT JOIN emp_info AS a
													ON a.e_id = i.entry_by WHERE i.purchase_date BETWEEN '$f_date' AND '$t_date' AND i.status = '0' AND i.p_id = '$p_id'
													ORDER BY i.purchase_date ASC");  	
							}
		else{
			$result = mysql_query("SELECT i.id, i.purchase_date, i.voucher_no, i.p_id, i.purchase_by, e.e_name AS purchase_by, v.v_name AS vendor, i.p_sts, i.p_sl_no, p.pro_name, i.brand, IF(i.fiber_id=0,NULL,i.fiber_id) AS fiber_id, IF(i.fibertotal=0,NULL,i.fibertotal) AS fibertotal, i.price, a.e_name AS entry_by, i.entry_time, CASE WHEN i.sts = 0 THEN 'Ready To Out' ELSE 'Already Out' END AS sts, i.rimarks FROM store_in_out_fiber AS i
													LEFT JOIN emp_info AS e
													ON e.e_id = i.purchase_by
													LEFT JOIN vendor AS v
													ON v.id = i.vendor
													LEFT JOIN fiber AS p
													ON p.id = i.p_id
													LEFT JOIN emp_info AS a
													ON a.e_id = i.entry_by 
													WHERE i.purchase_date BETWEEN '$f_date' AND '$t_date' AND i.status = '0' AND i.p_id = '$p_id' AND i.purchase_by = '$e_id'
													ORDER BY i.purchase_date ASC");
		}
		while ($row=mysql_fetch_array($result)) {

				$p_sl_no = $row['p_sl_no'];
				$voucher_no = $row['voucher_no'];
				$purchase_date = $row['purchase_date'];
				$pro_name = $row['pro_name'];
				$brand = $row['brand'];
				$p_sts = $row['p_sts'];
				$price = $row['price'];
				$purchase_by = $row['purchase_by'];
				$vendor = $row['vendor'];
				$fiber_id = $row['fiber_id'];
				$fibertotal = $row['fibertotal'];
				$sts = $row['sts'];
				$entry_time = $row['entry_time'];
				$rimarks = $row['rimarks'];
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(16,5,$purchase_date,'LTB',0,'L',true);
				$pdf->Cell(12,5,$voucher_no,'LTB',0,'L',true);
				$pdf->Cell(15,5,$purchase_by,'LTBR',0,'L',true);
				$pdf->Cell(25,5,$vendor,'LTB',0,'L',true);
				$pdf->Cell(15,5,$p_sl_no,'LTB',0,'L',true);
				$pdf->Cell(25,5,$pro_name,'LTB',0,'L',true);
				$pdf->Cell(14,5,$fiber_id,'LTB',0,'L',true);
				$pdf->Cell(10,5,$fibertotal,'LTB',0,'L',true);
				$pdf->Cell(10,5,number_format($price,0),'LTB',0,'R',true);
				$pdf->Cell(16,5,$p_sts,'LTBR',0,'L',true);
				$pdf->Cell(20,5,$sts,'LTBR',0,'L',true);
				$pdf->Cell(27,5,$rimarks,'LTBR',0,'L',true);
				$pdf->Ln();
				
			$TotalPayment += $price;

		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(122,6,'Total','LTB',0,'C',true);
			$pdf->Cell(10,6,number_format($TotalPayment,0),'LTBR',0,'R',true);
			$pdf->Cell(73,6,'','LTBR',0,'L',true);
			
			$pdf->Ln();
}
$pdf->Output();

?>