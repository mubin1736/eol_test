<?php
require('mysql_table.php');
require('connection.php');

$date=$_REQUEST['date'];

//$ro_id=$_REQUEST['ro_id'];

class PDF extends PDF_MySQL_Table
{
function Header()
{
	//Title
	$this->Image('logo.png',15,8,35);
	$this->Image('corporation.png',160,5,35);
	$this->SetFont('Helvetica','',15);
	$this->SetDrawColor(8, 102, 198);
	$this->SetLineWidth(.1);
	$this->Cell(0,15,'Hipro Distributor List','B',1,'C');
	
	$this->Ln(5);
	//Ensure table header is output
	parent::Header();
	
}
function Footer()
		{
			//Position at 1.5 cm from bottom
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Helvetica','I',8);
			//Page number
			$this->Cell(0,10,'Page '.$this->PageNo().'/iNet','T',0,'C');
			$this->SetDrawColor(8, 102, 198);
			parent::Footer();
		}
}

//Connect to database
include("connection.php");

$pdf=new PDF();
//$pdf->AddPage();
//First table: put all columns automatically
//$pdf->Table('select id, t_id, t_name, t_d_name from  territory_info order by id');
$pdf->AddPage();
//Second table: specify 4 columns
		$sql5 = mysql_query("SELECT COUNT(*) from order_phone_master where p_ord_date = '$date'");
		$row5 = mysql_fetch_assoc($sql5);
		$row_numb = $row5['COUNT(*)'];
		
		$pdf->SetFont('Helvetica','B',10);
		$pdf->Write(0, 'Date: ');
		$pdf->Write(0, $date);
		$pdf->SetXY(180, 30);
		$pdf->Write(0, 'Total: ');
		$pdf->Write(0, $row_numb);
$pdf->SetXY(30, 33);
		$pdf->AddCol('p_ord_no',55,'Order No','L');
		$pdf->AddCol('p_ord_by',40,'Order by','L');	
		$pdf->AddCol('p_ent_by',40,'Entry By','L');
		$pdf->AddCol('t_name',55,'Territory','L');
		//$pdf->AddCol('c_area',20,'Area','L');


		$prop=array('HeaderColor'=>array(8, 102, 198),
					'color1'=>array(255, 255, 255),
					'color2'=>array(223, 235, 255),
					'padding'=>1);
		//$pdf->Table("SELECT * FROM emp_info",$prop);
		$pdf->Table("SELECT m.p_ord_date, m.p_ord_no, m.p_ord_by, m.p_ent_by, m.p_tre_id, t.t_name
					FROM order_phone_master AS m, territory_info AS t
					WHERE m.p_tre_id = t.t_id
					AND m.p_ord_date = '$date' order by t.t_name",$prop);

$pdf->Output();
?>