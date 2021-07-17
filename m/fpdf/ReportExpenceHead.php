<?php
require('mysql_table.php');
require('connection.php');

$type = $_REQUEST['type'];
$e_id = $_REQUEST['e_id'];
$f_date = $_REQUEST['f_date'];
$t_date = $_REQUEST['t_date'];

if($type == 'all'){
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
			$this->Cell(20,6,'Voucher No','LTB',0,'L',true);
			$this->Cell(20,6,'Expence By','LTBR',0,'C',true);
			$this->Cell(42,6,'Expence Head','LTB',0,'C',true);
			$this->Cell(20,6,'Amount','LTB',0,'C',true);
			$this->Cell(20,6,'Entry By','LTB',0,'C',true);
			$this->Cell(30,6,'Entry Time','LTB',0,'C',true);
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
		if($e_id == 'all'){
		$result = mysql_query("SELECT e.id, e.voucher, e.ex_date, e.ex_by, a.e_name AS exby, e.type, q.ex_type AS head, e.amount, e.entry_by, w.e_name AS entryby, e.enty_date, e.note, e.status FROM expanse AS e
								LEFT JOIN emp_info AS a
								ON a.e_id = e.ex_by
								LEFT JOIN expanse_type AS q
								ON q.id = e.type
								LEFT JOIN emp_info AS w
								ON w.e_id = e.entry_by
								WHERE e.ex_date BETWEEN '$f_date' AND '$t_date' AND e.status = '0' ORDER BY e.ex_date ASC");  	
							}
		else{
			$result = mysql_query("SELECT e.id, e.voucher, e.ex_date, e.ex_by, a.e_name AS exby, e.type, q.ex_type AS head, e.amount, e.entry_by, w.e_name AS entryby, e.enty_date, e.note, e.status FROM expanse AS e
								LEFT JOIN emp_info AS a
								ON a.e_id = e.ex_by
								LEFT JOIN expanse_type AS q
								ON q.id = e.type
								LEFT JOIN emp_info AS w
								ON w.e_id = e.entry_by
								WHERE e.ex_date BETWEEN '$f_date' AND '$t_date' AND e.ex_by = '$e_id' AND e.status = '0' ORDER BY e.ex_date ASC");
		}
		while ($row=mysql_fetch_array($result)) {
				$ex_date = $row['ex_date'];
				$voucher = $row['voucher'];
				$exby = $row['exby'];
				$head = $row['head'];
				$amount = $row['amount'];
				$entryby = $row['entryby'];
				$enty_date = $row['enty_date'];
				$note = $row['note'];
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(17,5,$ex_date,'LTB',0,'L',true);
				$pdf->Cell(20,5,$voucher,'LTB',0,'L',true);
				$pdf->Cell(20,5,$exby,'LTBR',0,'L',true);
				$pdf->Cell(42,5,$head,'LTB',0,'L',true);
				$pdf->Cell(20,5,number_format($amount,0),'LTB',0,'R',true); 
				$pdf->Cell(20,5,$entryby,'LTB',0,'L',true);
				$pdf->Cell(30,5,$enty_date,'LTB',0,'L',true);
				$pdf->Cell(35,5,$note,'LTBR',0,'L',true);
				$pdf->Ln();
				
			$TotalPayment += $amount;

		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(99,6,'Total','LTB',0,'C',true);
			$pdf->Cell(20,6,number_format($TotalPayment,0),'LTBR',0,'R',true);
			$pdf->Cell(85,6,'','LTBR',0,'L',true);
			
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
			$this->Cell(20,6,'Voucher No','LTB',0,'L',true);
			$this->Cell(20,6,'Expence By','LTBR',0,'C',true);
			$this->Cell(42,6,'Expence Head','LTB',0,'C',true);
			$this->Cell(20,6,'Amount','LTB',0,'C',true);
			$this->Cell(20,6,'Entry By','LTB',0,'C',true);
			$this->Cell(30,6,'Entry Time','LTB',0,'C',true);
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
		if($e_id == 'all'){
		$result = mysql_query("SELECT e.id, e.voucher, e.ex_date, e.ex_by, a.e_name AS exby, e.type, q.ex_type AS head, e.amount, e.entry_by, w.e_name AS entryby, e.enty_date, e.note, e.status FROM expanse AS e
								LEFT JOIN emp_info AS a
								ON a.e_id = e.ex_by
								LEFT JOIN expanse_type AS q
								ON q.id = e.type
								LEFT JOIN emp_info AS w
								ON w.e_id = e.entry_by
								WHERE e.ex_date BETWEEN '$f_date' AND '$t_date' AND e.type = '$type' AND e.status = '0' ORDER BY e.ex_date ASC");  	
							}
		else{
			$result = mysql_query("SELECT e.id, e.voucher, e.ex_date, e.ex_by, a.e_name AS exby, e.type, q.ex_type AS head, e.amount, e.entry_by, w.e_name AS entryby, e.enty_date, e.note, e.status FROM expanse AS e
								LEFT JOIN emp_info AS a
								ON a.e_id = e.ex_by
								LEFT JOIN expanse_type AS q
								ON q.id = e.type
								LEFT JOIN emp_info AS w
								ON w.e_id = e.entry_by
								WHERE e.ex_date BETWEEN '$f_date' AND '$t_date' AND e.type = '$type' AND e.ex_by = '$e_id' AND e.status = '0' ORDER BY e.ex_date ASC");
		}
		while ($row=mysql_fetch_array($result)) {
				$ex_date = $row['ex_date'];
				$voucher = $row['voucher'];
				$exby = $row['exby'];
				$head = $row['head'];
				$amount = $row['amount'];
				$entryby = $row['entryby'];
				$enty_date = $row['enty_date'];
				$note = $row['note'];
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(17,5,$ex_date,'LTB',0,'L',true);
				$pdf->Cell(20,5,$voucher,'LTB',0,'L',true);
				$pdf->Cell(20,5,$exby,'LTBR',0,'L',true);
				$pdf->Cell(42,5,$head,'LTB',0,'L',true);
				$pdf->Cell(20,5,number_format($amount,0),'LTB',0,'R',true); 
				$pdf->Cell(20,5,$entryby,'LTB',0,'L',true);
				$pdf->Cell(30,5,$enty_date,'LTB',0,'L',true);
				$pdf->Cell(35,5,$note,'LTBR',0,'L',true);
				$pdf->Ln();
				
			$TotalPayment += $amount;

		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(99,6,'Total','LTB',0,'C',true);
			$pdf->Cell(20,6,number_format($TotalPayment,0),'LTBR',0,'R',true);
			$pdf->Cell(85,6,'','LTBR',0,'L',true);
			
			$pdf->Ln();

}
$pdf->Output();

?>