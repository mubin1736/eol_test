<?php
require('mysql_table.php');
require('connection.php');

$z_id = $_REQUEST['z_id'];
$f_date = $_REQUEST['f_date'];
$t_date = $_REQUEST['t_date'];

class PDF extends PDF_MySQL_Table
{
/*
function Header()
{
	//Title
	$this->Image('logo.png',4,0,35);
	$this->SetFont('Helvetica','',12);
	$this->SetDrawColor(8, 102, 198);
	$this->SetLineWidth(.1);
	$this->Cell(0,10,'Expert Online Bill Vs Payment Report','B',1,'C');
	
	$this->Ln(5);
	//Ensure table header is output
	parent::Header();
	
}
*/
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

$pdf=new PDF();
$pdf->AddPage();

if($z_id == 'allzone')
{
			$sql2 = mysql_query("SELECT c.c_id FROM clients AS c WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND c.sts = '0'");
			$total = mysql_num_rows($sql2);
			
			$pdf->SetXY(5, 5);
			$pdf->SetFont('Helvetica','B',7);
			$pdf->Write(0, 'Date: ');
			$pdf->Write(0, $f_date);
			$pdf->Write(0, ' To ');
			$pdf->Write(0, $t_date);
			$pdf->Write(0, ' [ All Zone ]');
			$pdf->SetXY(185, 5);
			$pdf->Write(0, 'Total: ');
			$pdf->Write(0, $total);
			
			$pdf->SetXY(30, 8);
			$pdf->AddCol('join_date',20,'Joining Date','L');
			$pdf->AddCol('c_id',12,'Clint ID','L');
			$pdf->AddCol('c_name',30,'Clint Name','L');
			$pdf->AddCol('z_name',38,'Zone','L');
			$pdf->AddCol('address',45,'Address','L');
			$pdf->AddCol('cell',20,'Cell','L');
			$pdf->AddCol('p_name',20,'Package','L');
			$pdf->AddCol('bandwith',15,'Bandwith','L');


			$prop=array('HeaderColor'=>array(160, 160, 160),
						'padding'=>1);
						
			$pdf->Table("SELECT c.c_id, c.c_name, z.z_name, c.cell, c.address, c.join_date, p.p_name, p.bandwith, c.con_sts FROM clients AS c
							LEFT JOIN zone AS z
							ON z.z_id = c.z_id
							LEFT JOIN package AS p
							ON p.p_id = c.p_id
							WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND c.sts = '0'
							ORDER BY c.join_date DESC",$prop);

}
else
{
			$sql2 = mysql_query("SELECT c.c_id FROM clients AS c WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND c.z_id = '$z_id' AND c.sts = '0'");
			$total = mysql_num_rows($sql2);
			
			$sql1 = mysql_query("SELECT * FROM zone WHERE z_id = '$z_id'");
			$row1 = mysql_fetch_array($sql1);
			$z_name = $row1['z_name'];
			
			$pdf->SetXY(5, 5);
			$pdf->SetFont('Helvetica','B',7);
			$pdf->Write(0, 'Date: ');
			$pdf->Write(0, $f_date);
			$pdf->Write(0, ' To ');
			$pdf->Write(0, $t_date);
			$pdf->Write(0, '  [ '.$z_name.' ]');
			$pdf->SetXY(185, 5);
			$pdf->Write(0, 'Total: ');
			$pdf->Write(0, $total);
			
			$pdf->SetXY(30, 8);
			$pdf->AddCol('join_date',20,'Joining Date','L');
			$pdf->AddCol('c_id',12,'Clint ID','L');
			$pdf->AddCol('c_name',30,'Clint Name','L');
			$pdf->AddCol('z_name',38,'Zone','L');
			$pdf->AddCol('address',45,'Address','L');
			$pdf->AddCol('cell',20,'Cell','L');
			$pdf->AddCol('p_name',20,'Package','L');
			$pdf->AddCol('bandwith',15,'Bandwith','L');


			$prop=array('HeaderColor'=>array(160, 160, 160),
						'padding'=>1);
						
			$pdf->Table("SELECT c.c_id, c.c_name, z.z_name, c.cell, c.address, c.join_date, p.p_name, p.bandwith, c.con_sts FROM clients AS c
							LEFT JOIN zone AS z
							ON z.z_id = c.z_id
							LEFT JOIN package AS p
							ON p.p_id = c.p_id
							WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND c.z_id = '$z_id' AND c.sts = '0'
							ORDER BY c.join_date DESC",$prop);
	
}
$pdf->Output();
?>