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
	$this->Cell(0,15,'Hipro Customer List','B',1,'C');
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
$pdf->AddCol('t_name',20,'Territory','L');
$pdf->AddCol('c_id',20,'Customer Id','L');
$pdf->AddCol('c_name',30,'Name','L');
$pdf->AddCol('c_typ_id',25,'C. Type','L');
$pdf->AddCol('s_name',35,'Shop Name','L');
$pdf->AddCol('c_shop_add',35,'Shop Location','L');
//$pdf->AddCol('c_area',20,'Area','L');
$pdf->AddCol('c_cont_per',35,'Contact No','L');


$prop=array('HeaderColor'=>array(8, 102, 198),
			'color1'=>array(255, 255, 255),
			'color2'=>array(223, 235, 255),
			'padding'=>1);
$pdf->Table('select t.t_name, c.c_id, c.c_name, c.c_typ_id, c.s_name, c.c_shop_add, c.c_cont_per, c.c_area, c.id from customer_info AS c, territory_info AS t WHERE c.c_t_id = t.t_id order by t.t_name',$prop);
$pdf->Output();
?>