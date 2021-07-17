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
			
			$this->Cell(7,6,'S/L','LTB',0,'L',true);
			$this->Cell(18,6,'Date','LTB',0,'L',true);
			$this->Cell(45,6,'Cable Info','LTB',0,'L',true);
			$this->Cell(20,6,'Serial No','LTB',0,'C',true);
			$this->Cell(12,6,'Size','LTB',0,'C',true);
			$this->Cell(12,6,'Out Qty','LTB',0,'C',true);
			$this->Cell(20,6,'Out by','LTB',0,'C',true);
			$this->Cell(20,6,'Responsible','LTB',0,'C',true);
			$this->Cell(50,6,'Note','LTBR',0,'C',true);
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
		$result = mysql_query("SELECT o.id, i.brand, i.fiber_id, i.p_id, f.pro_name, i.p_sl_no, i.fibertotal, i.status, o.qty, o.out_date, e.e_name AS outby, z.e_name AS receiveby, o.note FROM store_out_fiber AS o
													LEFT JOIN store_in_out_fiber AS i
													ON i.id = o.st_id
													LEFT JOIN fiber AS f
													ON f.id = i.p_id
													LEFT JOIN emp_info AS e
													ON e.e_id = o.out_by
													LEFT JOIN emp_info AS z
													ON z.e_id = o.receive_by
													WHERE o.out_date BETWEEN '$f_date' AND '$t_date' AND i.status = '0'
													ORDER BY o.out_date DESC");  	
							}
		else{
			$result = mysql_query("SELECT o.id, i.brand, i.fiber_id, i.p_id, f.pro_name, i.p_sl_no, i.fibertotal, i.status, o.qty, o.out_date, e.e_name AS outby, z.e_name AS receiveby, o.note FROM store_out_fiber AS o
													LEFT JOIN store_in_out_fiber AS i
													ON i.id = o.st_id
													LEFT JOIN fiber AS f
													ON f.id = i.p_id
													LEFT JOIN emp_info AS e
													ON e.e_id = o.out_by
													LEFT JOIN emp_info AS z
													ON z.e_id = o.receive_by
													WHERE o.out_date BETWEEN '$f_date' AND '$t_date' AND i.status = '0' AND o.receive_by = '$e_id'
													ORDER BY o.out_date DESC");
		}
		$x='1';
		while($row=mysql_fetch_array($result)){
				$brand = $row['brand'];
				$fiber_id = $row['fiber_id'];
				$pro_name = $row['pro_name'];
				$p_sl_no = $row['p_sl_no'];
				$fibertotal = $row['fibertotal'];
				$qty = $row['qty'];
				$out_date = $row['out_date'];
				$outby = $row['outby'];
				$receiveby = $row['receiveby'];
				$note = $row['note'];
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(7,5,$x,'LTB',0,'L',true);
				$pdf->Cell(18,5,$out_date,'LTB',0,'L',true);
				$pdf->Cell(45,5,$pro_name.' - '.$fiber_id.' - '.$brand,'LTBR',0,'L',true);
				$pdf->Cell(20,5,$p_sl_no,'LTB',0,'L',true);
				$pdf->Cell(12,5,number_format($fibertotal,0),'LTB',0,'R',true);
				$pdf->Cell(12,5,number_format($qty,0),'LTB',0,'R',true);
				$pdf->Cell(20,5,$outby,'LTB',0,'L',true);
				$pdf->Cell(20,5,$receiveby,'LTB',0,'L',true);
				$pdf->Cell(50,5,$note,'LTBR',0,'L',true);
				$pdf->Ln();

			$Totalqty += $qty;
			$x++;

		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(102,6,'Total','LTB',0,'C',true);
			$pdf->Cell(12,6,number_format($Totalqty,0),'LTBR',0,'R',true);
			$pdf->Cell(90,6,'','LTBR',0,'L',true);
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
			
			$this->Cell(7,6,'S/L','LTB',0,'L',true);
			$this->Cell(18,6,'Date','LTB',0,'L',true);
			$this->Cell(45,6,'Cable Info','LTB',0,'L',true);
			$this->Cell(20,6,'Serial No','LTB',0,'C',true);
			$this->Cell(12,6,'Size','LTB',0,'C',true);
			$this->Cell(12,6,'Out Qty','LTB',0,'C',true);
			$this->Cell(20,6,'Out by','LTB',0,'C',true);
			$this->Cell(20,6,'Responsible','LTB',0,'C',true);
			$this->Cell(50,6,'Note','LTBR',0,'C',true);
			$this->Ln();
		}
		function Footer()
				{
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
		$result = mysql_query("SELECT o.id, i.brand, i.fiber_id, i.p_id, f.pro_name, i.p_sl_no, i.fibertotal, i.status, o.qty, o.out_date, e.e_name AS outby, z.e_name AS receiveby, o.note FROM store_out_fiber AS o
													LEFT JOIN store_in_out_fiber AS i
													ON i.id = o.st_id
													LEFT JOIN fiber AS f
													ON f.id = i.p_id
													LEFT JOIN emp_info AS e
													ON e.e_id = o.out_by
													LEFT JOIN emp_info AS z
													ON z.e_id = o.receive_by
													WHERE o.out_date BETWEEN '$f_date' AND '$t_date' AND i.status = '0' AND i.p_id = '$p_id'
													ORDER BY o.out_date DESC");  	
							}
		else{
			$result = mysql_query("SELECT o.id, i.brand, i.fiber_id, i.p_id, f.pro_name, i.p_sl_no, i.fibertotal, i.status, o.qty, o.out_date, e.e_name AS outby, z.e_name AS receiveby, o.note FROM store_out_fiber AS o
													LEFT JOIN store_in_out_fiber AS i
													ON i.id = o.st_id
													LEFT JOIN fiber AS f
													ON f.id = i.p_id
													LEFT JOIN emp_info AS e
													ON e.e_id = o.out_by
													LEFT JOIN emp_info AS z
													ON z.e_id = o.receive_by
													WHERE o.out_date BETWEEN '$f_date' AND '$t_date' AND i.status = '0' AND o.receive_by = '$e_id' AND i.p_id = '$p_id'
													ORDER BY o.out_date DESC");
		}
		$x='1';
		while($row=mysql_fetch_array($result)){
				$brand = $row['brand'];
				$fiber_id = $row['fiber_id'];
				$pro_name = $row['pro_name'];
				$p_sl_no = $row['p_sl_no'];
				$fibertotal = $row['fibertotal'];
				$qty = $row['qty'];
				$out_date = $row['out_date'];
				$outby = $row['outby'];
				$receiveby = $row['receiveby'];
				$note = $row['note'];
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(7,5,$x,'LTB',0,'L',true);
				$pdf->Cell(18,5,$out_date,'LTB',0,'L',true);
				$pdf->Cell(45,5,$pro_name.' - '.$fiber_id.' - '.$brand,'LTBR',0,'L',true);
				$pdf->Cell(20,5,$p_sl_no,'LTB',0,'L',true);
				$pdf->Cell(12,5,number_format($fibertotal,0),'LTB',0,'R',true);
				$pdf->Cell(12,5,number_format($qty,0),'LTB',0,'R',true);
				$pdf->Cell(20,5,$outby,'LTB',0,'L',true);
				$pdf->Cell(20,5,$receiveby,'LTB',0,'L',true);
				$pdf->Cell(50,5,$note,'LTBR',0,'L',true);
				$pdf->Ln();

			$Totalqty += $qty;
			$x++;

		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(102,6,'Total','LTB',0,'C',true);
			$pdf->Cell(12,6,number_format($Totalqty,0),'LTBR',0,'R',true);
			$pdf->Cell(90,6,'','LTBR',0,'L',true);
			$pdf->Ln();
}
$pdf->Output();
?>