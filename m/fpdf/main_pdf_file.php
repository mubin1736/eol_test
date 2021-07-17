<?php
require('mysql_table.php');

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
	$this->Cell(0,15,'BDmaxOnline','B',1,'C');
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
			$this->Cell(0,10,'Page '.$this->PageNo().'/BDmaxOnline','T',0,'C');
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
$pdf->AddCol('id',20,'ID','L');
$pdf->AddCol('t_id',40,'Territory id','L');
$pdf->AddCol('t_name',60,'Territory Name','L');
$pdf->AddCol('t_d_name',60,'Territory Deistic Name','L');
$prop=array('HeaderColor'=>array(8, 102, 198),
			'color1'=>array(255, 255, 255),
			'color2'=>array(223, 235, 255),
			'padding'=>5);
$pdf->Table('select id, t_id, t_name, t_d_name from  territory_info order by id',$prop);
$pdf->Output();
?>
