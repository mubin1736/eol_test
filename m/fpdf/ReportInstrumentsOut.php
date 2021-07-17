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
			$this->Cell(50,6,'Item','LTB',0,'L',true);
			$this->Cell(10,6,'Qty','LTBR',0,'C',true);
			$this->Cell(25,6,'Out by','LTB',0,'C',true);
			$this->Cell(30,6,'Use by','LTB',0,'C',true);
			$this->Cell(30,6,'Out Date Time','LTB',0,'C',true);
			$this->Cell(42,6,'Note','LTBR',0,'C',true);
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
		$result = mysql_query("SELECT o.id, o.st_id, p.pro_name, w.p_sl_no, o.qty, o.out_date, e.e_name AS out_by, o.receive_by, k.e_name AS receive_by, o.note, o.out_date_time FROM store_out_instruments AS o
													LEFT JOIN store_in_instruments AS i
													ON i.id = o.st_id
													LEFT JOIN product AS p
													ON p.id = i.p_id
													LEFT JOIN emp_info AS e
													ON e.e_id = o.out_by
													LEFT JOIN emp_info AS k
													ON k.e_id = o.receive_by 
													LEFT JOIN store_in_instruments AS w
													ON w.id = o.st_id
													WHERE o.out_date BETWEEN '$f_date' AND '$t_date' AND o.status = '0' ORDER BY o.out_date_time DESC");  	
							}
		else{
			$result = mysql_query("SELECT o.id, o.st_id, p.pro_name, w.p_sl_no, o.qty, o.out_date, e.e_name AS out_by, o.receive_by, k.e_name AS receive_by, o.note, o.out_date_time FROM store_out_instruments AS o
													LEFT JOIN store_in_instruments AS i
													ON i.id = o.st_id
													LEFT JOIN product AS p
													ON p.id = i.p_id
													LEFT JOIN emp_info AS e
													ON e.e_id = o.out_by
													LEFT JOIN emp_info AS k
													ON k.e_id = o.receive_by 
													LEFT JOIN store_in_instruments AS w
													ON w.id = o.st_id
													WHERE o.out_date BETWEEN '$f_date' AND '$t_date' AND o.status = '0' AND o.receive_by = '$e_id' ORDER BY o.out_date_time DESC");
		}
		while ($row=mysql_fetch_array($result)) {
				$out_date = $row['out_date'];
				$pro_name = $row['pro_name'];
				$p_sl_no = $row['p_sl_no'];
				$qty = $row['qty'];
				$out_date_time = $row['out_date_time'];
				$out_by = $row['out_by'];
				$receive_by = $row['receive_by'];
				$note = $row['note'];
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(17,5,$out_date,'LTB',0,'L',true);
				$pdf->Cell(50,5,$pro_name.' - '.$p_sl_no,'LTB',0,'L',true);
				$pdf->Cell(10,5,$qty,'LTBR',0,'L',true);
				$pdf->Cell(25,5,$out_by,'LTB',0,'L',true);
				$pdf->Cell(30,5,$receive_by,'LTB',0,'L',true);
				$pdf->Cell(30,5,$out_date_time,'LTB',0,'L',true);
				$pdf->Cell(42,5,$note,'LTBR',0,'L',true);
				$pdf->Ln();
				
			$TotalPayment += $qty;
		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(67,6,'Total','LTB',0,'C',true);
			$pdf->Cell(10,6,number_format($TotalPayment,0),'LTBR',0,'R',true);
			$pdf->Cell(127,6,'','LTBR',0,'L',true);
			
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
			$this->Cell(50,6,'Item','LTB',0,'L',true);
			$this->Cell(10,6,'Qty','LTBR',0,'C',true);
			$this->Cell(25,6,'Out by','LTB',0,'C',true);
			$this->Cell(30,6,'Use by','LTB',0,'C',true);
			$this->Cell(30,6,'Out Date Time','LTB',0,'C',true);
			$this->Cell(42,6,'Note','LTBR',0,'C',true);
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
		$result = mysql_query("SELECT o.id, o.st_id, p.pro_name, i.p_id, w.p_sl_no, o.qty, o.out_date, e.e_name AS out_by, o.receive_by, k.e_name AS receive_by, o.note, o.out_date_time FROM store_out_instruments AS o
													LEFT JOIN store_in_instruments AS i
													ON i.id = o.st_id
													LEFT JOIN product AS p
													ON p.id = i.p_id
													LEFT JOIN emp_info AS e
													ON e.e_id = o.out_by
													LEFT JOIN emp_info AS k
													ON k.e_id = o.receive_by 
													LEFT JOIN store_in_instruments AS w
													ON w.id = o.st_id
													WHERE o.out_date BETWEEN '$f_date' AND '$t_date' AND o.status = '0' AND i.p_id = '$p_id' ORDER BY o.out_date_time DESC");  	
							}
		else{
			$result = mysql_query("SELECT o.id, o.st_id, i.p_id, p.pro_name, w.p_sl_no, o.qty, o.out_date, e.e_name AS out_by, o.receive_by, k.e_name AS receive_by, o.note, o.out_date_time FROM store_out_instruments AS o
													LEFT JOIN store_in_instruments AS i
													ON i.id = o.st_id
													LEFT JOIN product AS p
													ON p.id = i.p_id
													LEFT JOIN emp_info AS e
													ON e.e_id = o.out_by
													LEFT JOIN emp_info AS k
													ON k.e_id = o.receive_by 
													LEFT JOIN store_in_instruments AS w
													ON w.id = o.st_id
													WHERE o.out_date BETWEEN '$f_date' AND '$t_date' AND o.status = '0' AND i.p_id = '$p_id' AND o.receive_by = '$e_id' ORDER BY o.out_date_time DESC");
		}
		while ($row=mysql_fetch_array($result)) {
				$out_date = $row['out_date'];
				$pro_name = $row['pro_name'];
				$p_sl_no = $row['p_sl_no'];
				$qty = $row['qty'];
				$out_date_time = $row['out_date_time'];
				$out_by = $row['out_by'];
				$receive_by = $row['receive_by'];
				$note = $row['note'];
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(17,5,$out_date,'LTB',0,'L',true);
				$pdf->Cell(50,5,$pro_name.' - '.$p_sl_no,'LTB',0,'L',true);
				$pdf->Cell(10,5,$qty,'LTBR',0,'L',true);
				$pdf->Cell(25,5,$out_by,'LTB',0,'L',true);
				$pdf->Cell(30,5,$receive_by,'LTB',0,'L',true);
				$pdf->Cell(30,5,$out_date_time,'LTB',0,'L',true);
				$pdf->Cell(42,5,$note,'LTBR',0,'L',true);
				$pdf->Ln();
				
			$TotalPayment += $qty;
		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(67,6,'Total','LTB',0,'C',true);
			$pdf->Cell(10,6,number_format($TotalPayment,0),'LTBR',0,'R',true);
			$pdf->Cell(127,6,'','LTBR',0,'L',true);
			
			$pdf->Ln();
}
$pdf->Output();

?>