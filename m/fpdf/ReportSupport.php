<?php
require('mysql_table.php');
require('connection.php');

$status = $_REQUEST['status'];
$f_date = $_REQUEST['f_date'];
$t_date = $_REQUEST['t_date'];

if($status == '0'){
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
			
			$this->Cell(26,6,'Date','LTB',0,'C',true);
			$this->Cell(16,6,'Ticket No','LTB',0,'C',true);
			$this->Cell(10,6,'C.ID','LTB',0,'C',true);
			$this->Cell(20,6,'Name','LTB',0,'C',true);
			$this->Cell(22,6,'Cell No','LTB',0,'C',true);
			$this->Cell(35,6,'Zone','LTB',0,'C',true);
			$this->Cell(30,6,'To','LTB',0,'C',true);
			$this->Cell(35,6,'Subject','LTB',0,'C',true);
			$this->Cell(10,6,'Replies','LTBR',0,'C',true);
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
		
		$result = mysql_query("SELECT m.ticket_no, count(l.reply) AS totrep, m.c_id, c.c_name, c.cell, z.z_name, c.address, c.com_id, m.dept_id, d.dept_name, m.sub, m.massage, DATE_FORMAT(m.close_date_time, '%D %M %Y %h:%i%p') AS close_date_time, DATE_FORMAT(m.entry_date_time, '%D %M %h:%i%p') AS entry_date_time, m.sts, l.reply, DATE_FORMAT(l.reply_date_time, '%D %M %Y %h:%i%p') AS reply_date_time FROM complain_master AS m
												LEFT JOIN department_info AS d
												ON d.dept_id = m.dept_id 
												LEFT JOIN clients AS c
												ON c.c_id = m.c_id 
												LEFT JOIN zone AS z
												ON z.z_id = c.z_id 
												LEFT JOIN (SELECT id, ticket_no, reply, reply_date_time FROM complain_detail ORDER BY id DESC) AS l
												ON l.ticket_no = m.ticket_no
												WHERE m.entry_date_time BETWEEN '$f_date' AND '$t_date' AND m.sts = '0' GROUP BY m.ticket_no ORDER BY m.ticket_no");  							
		$sql3 = mysql_query("SELECT id FROM complain_master WHERE sts = '0'");
		$total_complen = mysql_num_rows($sql3);

		
		while ($row=mysql_fetch_array($result)) {
				$entry_date_time = $row['entry_date_time'];
				$ticket_no = $row['ticket_no'];
				$c_id = $row['c_id'];
				$c_name = $row['c_name'];
				$cell = $row['cell'];
				$address = $row['address'];
				$z_name = $row['z_name'];
				$dept_name = $row['dept_name'];
				$sub = $row['sub'];
				$sts = $row['sts'];
				$totrep = $row['totrep'];
			
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(26,5,$entry_date_time,'LTB',0,'L',true);
				$pdf->Cell(16,5,$ticket_no,'LTB',0,'L',true);
				$pdf->Cell(10,5,$c_id,'LTB',0,'L',true);
				$pdf->Cell(20,5,$c_name,'LTB',0,'L',true);
				$pdf->Cell(22,5,$cell,'LTB',0,'L',true);
				$pdf->Cell(35,5,$z_name,'LTB',0,'L',true);
				$pdf->Cell(30,5,$dept_name,'LTB',0,'L',true);
				$pdf->Cell(35,5,$sub,'LTB',0,'L',true);
				$pdf->Cell(10,5,$totrep,'LTBR',0,'L',true);
				$pdf->Ln();
				
		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(80,6,'Total Open Ticket :  ','LTB',0,'R',true);
			$pdf->Cell(10,6,$total_complen,'TB',0,'L',true);
			$pdf->Cell(114,6,'','TBR',0,'L',true);
			
			$pdf->Ln();
}
if($status == '1'){
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
			
			$this->Cell(26,6,'Date','LTB',0,'C',true);
			$this->Cell(15,6,'Ticket No','LTB',0,'C',true);
			$this->Cell(25,6,'Clients','LTB',0,'C',true);
			$this->Cell(22,6,'Cell No','LTB',0,'C',true);
			$this->Cell(39,6,'Zone','LTB',0,'C',true);
			$this->Cell(34,6,'Subject','LTB',0,'C',true);
			$this->Cell(26,6,'Close Time','LTB',0,'C',true);
			$this->Cell(17,6,'Close By','LTBR',0,'C',true);
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
		
		$result = mysql_query("SELECT m.ticket_no, m.c_id, c.cell, count(l.reply) AS totrep, c.c_name, z.z_name, c.address, c.com_id, m.dept_id, d.dept_name, m.sub, m.massage, DATE_FORMAT(m.entry_date_time, '%D %M %h:%i%p') AS entry_date_time, DATE_FORMAT(m.close_date_time, '%D %M %h:%i%p') AS close_date_time, m.sts, l.reply, m.close_by, q.e_name AS closeby FROM complain_master AS m
												LEFT JOIN department_info AS d
												ON d.dept_id = m.dept_id 
												LEFT JOIN clients AS c
												ON c.c_id = m.c_id 
												LEFT JOIN zone AS z
												ON z.z_id = c.z_id 
												LEFT JOIN (SELECT id, ticket_no, reply, reply_date_time FROM complain_detail ORDER BY id DESC) AS l
												ON l.ticket_no = m.ticket_no
												LEFT JOIN emp_info AS q
												ON q.e_id = m.close_by
												WHERE m.entry_date_time BETWEEN '$f_date' AND '$t_date' AND m.sts = '1' GROUP BY m.ticket_no ORDER BY m.ticket_no");  							
		$sql3 = mysql_query("SELECT id FROM complain_master WHERE sts = '1'");
		$total_complen = mysql_num_rows($sql3);

		
		while ($row=mysql_fetch_array($result)) {
				$entry_date_time = $row['entry_date_time'];
				$ticket_no = $row['ticket_no'];
				$c_id = $row['c_id'];
				$c_name = $row['c_name'];
				$cell = $row['cell'];
				$address = $row['address'];
				$z_name = $row['z_name'];
				$dept_name = $row['dept_name'];
				$sub = $row['sub'];
				$sts = $row['sts'];
				$close_date_time = $row['close_date_time'];
				$closeby = $row['closeby'];
				
			
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(26,5,$entry_date_time,'LTB',0,'L',true);
				$pdf->Cell(15,5,$ticket_no,'LTB',0,'L',true);
				$pdf->Cell(25,5,$c_id.'-'.$c_name,'LTB',0,'L',true);
				$pdf->Cell(22,5,$cell,'LTB',0,'L',true);
				$pdf->Cell(39,5,$z_name,'LTB',0,'L',true);
				$pdf->Cell(34,5,$sub,'LTB',0,'L',true);
				$pdf->Cell(26,5,$close_date_time,'LTBR',0,'L',true);
				$pdf->Cell(17,5,$closeby,'LTBR',0,'L',true);
				$pdf->Ln();
				
		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(80,6,'Total Closed Ticket :  ','LTB',0,'R',true);
			$pdf->Cell(10,6,$total_complen,'TB',0,'L',true);
			$pdf->Cell(114,6,'','TBR',0,'L',true);
			
			$pdf->Ln();
}
if($status == 'all'){
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
			
			$this->Cell(26,6,'Date','LTB',0,'C',true);
			$this->Cell(15,6,'Ticket No','LTB',0,'C',true);
			$this->Cell(25,6,'Clients','LTB',0,'C',true);
			$this->Cell(22,6,'Cell No','LTB',0,'C',true);
			$this->Cell(39,6,'Zone','LTB',0,'C',true);
			$this->Cell(34,6,'Subject','LTB',0,'C',true);
			$this->Cell(26,6,'Close Time','LTB',0,'C',true);
			$this->Cell(17,6,'Close By','LTBR',0,'C',true);
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
		
		$result = mysql_query("SELECT m.ticket_no, m.c_id, c.cell, count(l.reply) AS totrep, c.c_name, z.z_name, c.address, c.com_id, m.dept_id, d.dept_name, m.sub, m.massage, DATE_FORMAT(m.entry_date_time, '%D %M %h:%i%p') AS entry_date_time, DATE_FORMAT(m.close_date_time, '%D %M %h:%i%p') AS close_date_time, m.sts, l.reply, m.close_by, q.e_name AS closeby FROM complain_master AS m
												LEFT JOIN department_info AS d
												ON d.dept_id = m.dept_id 
												LEFT JOIN clients AS c
												ON c.c_id = m.c_id 
												LEFT JOIN zone AS z
												ON z.z_id = c.z_id 
												LEFT JOIN (SELECT id, ticket_no, reply, reply_date_time FROM complain_detail ORDER BY id DESC) AS l
												ON l.ticket_no = m.ticket_no
												LEFT JOIN emp_info AS q
												ON q.e_id = m.close_by
												WHERE m.entry_date_time BETWEEN '$f_date' AND '$t_date' GROUP BY m.ticket_no ORDER BY m.ticket_no DESC");  							
		$sql3 = mysql_query("SELECT id FROM complain_master WHERE sts = '1'");
		$total_complen = mysql_num_rows($sql3);

		
		while ($row=mysql_fetch_array($result)) {
				$entry_date_time = $row['entry_date_time'];
				$ticket_no = $row['ticket_no'];
				$c_id = $row['c_id'];
				$c_name = $row['c_name'];
				$cell = $row['cell'];
				$address = $row['address'];
				$z_name = $row['z_name'];
				$dept_name = $row['dept_name'];
				$sub = $row['sub'];
				$sts = $row['sts'];
				$close_date_time = $row['close_date_time'];
				$closeby = $row['closeby'];
				
			
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(26,5,$entry_date_time,'LTB',0,'L',true);
				$pdf->Cell(15,5,$ticket_no,'LTB',0,'L',true);
				$pdf->Cell(25,5,$c_id.'-'.$c_name,'LTB',0,'L',true);
				$pdf->Cell(22,5,$cell,'LTB',0,'L',true);
				$pdf->Cell(39,5,$z_name,'LTB',0,'L',true);
				$pdf->Cell(34,5,$sub,'LTB',0,'L',true);
				$pdf->Cell(26,5,$close_date_time,'LTBR',0,'L',true);
				$pdf->Cell(17,5,$closeby,'LTBR',0,'L',true);
				$pdf->Ln();
				
		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(80,6,'Total Closed Ticket :  ','LTB',0,'R',true);
			$pdf->Cell(10,6,$total_complen,'TB',0,'L',true);
			$pdf->Cell(114,6,'','TBR',0,'L',true);
			
			$pdf->Ln();
}
$pdf->Output();
?>