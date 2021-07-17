<?php
require('mysql_table.php');
require('connection.php');

$z_id = $_REQUEST['z_id'];

$sts = $_REQUEST['sts'];

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
	$this->SetDrawColor(0, 0, 0);
	$this->SetLineWidth(.1);
	$this->Cell(0,10,'Expert Online Clients List','B',1,'C');
	
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
	if($sts == 'all' && $z_id != 'allzone'){
			$sql1 = mysql_query("SELECT * FROM zone WHERE z_id = '$z_id'");
			$row1 = mysql_fetch_array($sql1);
			$z_name = $row1['z_name'];
			
			$sql2 = mysql_query("SELECT id FROM clients WHERE join_date BETWEEN '$f_date' AND '$t_date' AND z_id = '$z_id' AND sts = '0'");
			$total = mysql_num_rows($sql2);
			
			$sql3 = mysql_query("SELECT id FROM clients WHERE join_date BETWEEN '$f_date' AND '$t_date' AND  z_id = '$z_id' AND con_sts = 'active' AND sts = '0'");
			$active = mysql_num_rows($sql3);
			
			$sql4 = mysql_query("SELECT id FROM clients WHERE join_date BETWEEN '$f_date' AND '$t_date' AND  z_id = '$z_id' AND con_sts = 'inactive' AND sts = '0'");
			$inactive = mysql_num_rows($sql4);

			$sql5 = mysql_query("SELECT c.id FROM clients AS c LEFT JOIN login AS l ON l.e_id = c.c_id WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND  l.log_sts = '0' AND z_id = '$z_id' AND c.sts = '0'");
			$unlocked = mysql_num_rows($sql5);
			
			$sql6 = mysql_query("SELECT c.id FROM clients AS c LEFT JOIN login AS l ON l.e_id = c.c_id WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND  l.log_sts = '1' AND z_id = '$z_id' AND c.sts = '0'");
			$locked = mysql_num_rows($sql6);
			
			$pdf->SetXY(8, 5);
			$pdf->SetFont('Helvetica','B',8);
			$pdf->Write(0, $z_name.' [ All Clients ]');
			$pdf->SetXY(90, 5);
			$pdf->Write(0, 'Active: ');
			$pdf->Write(0, $active);
			$pdf->Write(0, '  ||  ');
			$pdf->Write(0, 'Inactive: ');
			$pdf->Write(0, $inactive);
			$pdf->Write(0, '  ||  ');
			$pdf->Write(0, 'Locked: ');
			$pdf->Write(0, $locked);
			$pdf->Write(0, '  ||  ');
			$pdf->Write(0, 'Unlocked: ');
			$pdf->Write(0, $unlocked);
			$pdf->Write(0, '  ||  ');
			$pdf->Write(0, 'Total: ');
			$pdf->Write(0, $total);
			
			$pdf->SetXY(8, 5);
			$pdf->SetFont('Helvetica','B',8);
			$pdf->Write(0, $z_name.' [ All Clients ]');

			$pdf->SetXY(30, 8);
			$pdf->AddCol('c_id',20,'Name','L');
			$pdf->AddCol('con_type',13,'C.Type','L');
			$pdf->AddCol('join_date',15,'Join Date','L');
			$pdf->AddCol('bandwith',15,'Bandwith','L');
			$pdf->AddCol('connectivity_type',12,'Convty','L');
			$pdf->AddCol('address',35,'Address','L');
			$pdf->AddCol('thana',12,'Thana','L');
			$pdf->AddCol('cell',20,'Cell','L');
			$pdf->AddCol('email',20,'Email','L');
			$pdf->AddCol('ip',18,'IP Addrss','L');
			$pdf->AddCol('mac',25,'MAC Addrss','L');


			$prop=array('HeaderColor'=>array(160, 160, 160),
						'padding'=>1);
						
			$pdf->Table("SELECT c.c_id, c.c_name, c.con_type, c.join_date, p.bandwith, c.connectivity_type, c.address, c.thana, c.cell, c.email, c.ip, c.mac, z.z_name, c.con_sts FROM clients AS c
							LEFT JOIN package AS p
							ON p.p_id = c.p_id
							LEFT JOIN zone AS z
							ON z.z_id = c.z_id
							LEFT JOIN login AS l
							ON l.e_id = c.c_id
							WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND c.z_id = '$z_id' AND c.sts = '0'
							ORDER BY c.join_date ASC",$prop);
		
	}
	
if($sts == 'active' && $z_id != 'allzone'){

			$sql1 = mysql_query("SELECT * FROM zone WHERE z_id = '$z_id'");
			$row1 = mysql_fetch_array($sql1);
			$z_name = $row1['z_name'];
			
			
			$sql2 = mysql_query("SELECT id FROM clients WHERE join_date BETWEEN '$f_date' AND '$t_date' AND  z_id = '$z_id' AND con_sts = 'active' AND sts = '0'");
			$total = mysql_num_rows($sql2);
			
			$sql3 = mysql_query("SELECT id FROM clients WHERE join_date BETWEEN '$f_date' AND '$t_date' AND  z_id = '$z_id' AND con_sts = 'active' AND sts = '0'");
			$active = mysql_num_rows($sql3);

			$sql5 = mysql_query("SELECT c.id FROM clients AS c LEFT JOIN login AS l ON l.e_id = c.c_id WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND l.log_sts = '0' AND z_id = '$z_id' AND con_sts = 'active' AND c.sts = '0'");
			$unlocked = mysql_num_rows($sql5);
			
			$sql6 = mysql_query("SELECT c.id FROM clients AS c LEFT JOIN login AS l ON l.e_id = c.c_id WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND l.log_sts = '1' AND z_id = '$z_id' AND con_sts = 'active' AND c.sts = '0'");
			$locked = mysql_num_rows($sql6);
			
			$pdf->SetXY(8, 5);
			$pdf->SetFont('Helvetica','B',8);
			$pdf->Write(0, $z_name.' [ Active Clients ]');
			$pdf->SetXY(90, 5);
			$pdf->Write(0, 'Active: ');
			$pdf->Write(0, $active);
			$pdf->Write(0, '  ||  ');
			$pdf->Write(0, 'Locked: ');
			$pdf->Write(0, $locked);
			$pdf->Write(0, '  ||  ');
			$pdf->Write(0, 'Unlocked: ');
			$pdf->Write(0, $unlocked);
			$pdf->Write(0, '  ||  ');
			$pdf->Write(0, 'Total: ');
			$pdf->Write(0, $total);
			
			
			$pdf->SetXY(8, 5);
			$pdf->SetFont('Helvetica','B',8);
			$pdf->Write(0, $z_name.' [ Active Clients ]');
			
			$pdf->SetXY(30, 8);
			$pdf->AddCol('c_id',20,'Name','L');
			$pdf->AddCol('con_type',13,'C.Type','L');
			$pdf->AddCol('join_date',15,'Join Date','L');
			$pdf->AddCol('bandwith',15,'Bandwith','L');
			$pdf->AddCol('connectivity_type',12,'Convty','L');
			$pdf->AddCol('address',35,'Address','L');
			$pdf->AddCol('thana',12,'Thana','L');
			$pdf->AddCol('cell',20,'Cell','L');
			$pdf->AddCol('email',20,'Email','L');
			$pdf->AddCol('ip',18,'IP Addrss','L');
			$pdf->AddCol('mac',25,'MAC Addrss','L');


			$prop=array('HeaderColor'=>array(160, 160, 160),
						'padding'=>1);
						
			$pdf->Table("SELECT c.c_id, c.c_name, c.con_type, c.join_date, p.bandwith, c.connectivity_type, c.address, c.thana, c.cell, c.email, c.ip, c.mac, z.z_name, c.con_sts FROM clients AS c
							LEFT JOIN package AS p
							ON p.p_id = c.p_id
							LEFT JOIN zone AS z
							ON z.z_id = c.z_id
							LEFT JOIN login AS l
							ON l.e_id = c.c_id
							WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND z.z_id = '$z_id' AND c.con_sts = 'Active' AND c.sts = '0'
							ORDER BY c.join_date ASC",$prop);
		
	}

if($sts == 'inactive' && $z_id != 'allzone'){
			$sql1 = mysql_query("SELECT * FROM zone WHERE z_id = '$z_id'");
			$row1 = mysql_fetch_array($sql1);
			$z_name = $row1['z_name'];
			
			$sql2 = mysql_query("SELECT id FROM clients WHERE join_date BETWEEN '$f_date' AND '$t_date' AND z_id = '$z_id' AND con_sts = 'inactive' AND sts = '0'");
			$total = mysql_num_rows($sql2);

			$sql4 = mysql_query("SELECT id FROM clients WHERE join_date BETWEEN '$f_date' AND '$t_date' AND z_id = '$z_id' AND con_sts = 'inactive' AND sts = '0'");
			$inactive = mysql_num_rows($sql4);

			$sql5 = mysql_query("SELECT c.id FROM clients AS c LEFT JOIN login AS l ON l.e_id = c.c_id WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND l.log_sts = '0' AND z_id = '$z_id' AND con_sts = 'inactive' AND c.sts = '0'");
			$unlocked = mysql_num_rows($sql5);
			
			$sql6 = mysql_query("SELECT c.id FROM clients AS c LEFT JOIN login AS l ON l.e_id = c.c_id WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND l.log_sts = '1' AND z_id = '$z_id' AND con_sts = 'inactive' AND c.sts = '0'");
			$locked = mysql_num_rows($sql6);
			
			$pdf->SetXY(8, 5);
			$pdf->SetFont('Helvetica','B',8);
			$pdf->Write(0, $z_name.' [ Inactive Clients ]');
			$pdf->SetXY(90, 5);
			$pdf->Write(0, 'Inactive: ');
			$pdf->Write(0, $inactive);
			$pdf->Write(0, '  ||  ');
			$pdf->Write(0, 'Locked: ');
			$pdf->Write(0, $locked);
			$pdf->Write(0, '  ||  ');
			$pdf->Write(0, 'Unlocked: ');
			$pdf->Write(0, $unlocked);
			$pdf->Write(0, '  ||  ');
			$pdf->Write(0, 'Total: ');
			$pdf->Write(0, $total);
			
			$pdf->SetXY(8, 5);
			$pdf->SetFont('Helvetica','B',8);
			$pdf->Write(0, $z_name.' [ Inactive Clients ]');
			
			$pdf->SetXY(30, 8);
			$pdf->AddCol('c_id',20,'Name','L');
			$pdf->AddCol('con_type',13,'C.Type','L');
			$pdf->AddCol('join_date',15,'Join Date','L');
			$pdf->AddCol('bandwith',15,'Bandwith','L');
			$pdf->AddCol('connectivity_type',12,'Convty','L');
			$pdf->AddCol('address',35,'Address','L');
			$pdf->AddCol('thana',12,'Thana','L');
			$pdf->AddCol('cell',20,'Cell','L');
			$pdf->AddCol('email',20,'Email','L');
			$pdf->AddCol('ip',18,'IP Addrss','L');
			$pdf->AddCol('mac',25,'MAC Addrss','L');


			$prop=array('HeaderColor'=>array(160, 160, 160),
						'padding'=>1);
						
			$pdf->Table("SELECT c.c_id, c.c_name, c.con_type, c.join_date, p.bandwith, c.connectivity_type, c.address, c.thana, c.cell, c.email, c.ip, c.mac, z.z_name, c.con_sts FROM clients AS c
							LEFT JOIN package AS p
							ON p.p_id = c.p_id
							LEFT JOIN zone AS z
							ON z.z_id = c.z_id
							LEFT JOIN login AS l
							ON l.e_id = c.c_id
							WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND z.z_id = '$z_id' AND c.con_sts = 'Inactive' AND c.sts = '0'
							ORDER BY c.join_date ASC",$prop);
		
	}
	
if($sts == 'all' && $z_id == 'allzone'){
			$sql1 = mysql_query("SELECT * FROM zone WHERE z_id = '$z_id'");
			$row1 = mysql_fetch_array($sql1);
			$z_name = $row1['z_name'];
			
			$sql2 = mysql_query("SELECT id FROM clients WHERE join_date BETWEEN '$f_date' AND '$t_date' AND sts = '0'");
			$total = mysql_num_rows($sql2);
			
			$sql3 = mysql_query("SELECT id FROM clients WHERE join_date BETWEEN '$f_date' AND '$t_date' AND con_sts = 'active' AND sts = '0'");
			$active = mysql_num_rows($sql3);
			
			$sql4 = mysql_query("SELECT id FROM clients WHERE join_date BETWEEN '$f_date' AND '$t_date' AND con_sts = 'inactive' AND sts = '0'");
			$inactive = mysql_num_rows($sql4);

			$sql5 = mysql_query("SELECT c.id FROM clients AS c LEFT JOIN login AS l ON l.e_id = c.c_id WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND l.log_sts = '0' AND c.sts = '0'");
			$unlocked = mysql_num_rows($sql5);
			
			$sql6 = mysql_query("SELECT c.id FROM clients AS c LEFT JOIN login AS l ON l.e_id = c.c_id WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND l.log_sts = '1' AND c.sts = '0'");
			$locked = mysql_num_rows($sql6);
			
			$pdf->SetXY(8, 5);
			$pdf->SetFont('Helvetica','B',8);
			$pdf->Write(0, '[ All Zone and All Clients ]');
			$pdf->SetXY(90, 5);
			$pdf->Write(0, 'Active: ');
			$pdf->Write(0, $active);
			$pdf->Write(0, '  ||  ');
			$pdf->Write(0, 'Inactive: ');
			$pdf->Write(0, $inactive);
			$pdf->Write(0, '  ||  ');
			$pdf->Write(0, 'Locked: ');
			$pdf->Write(0, $locked);
			$pdf->Write(0, '  ||  ');
			$pdf->Write(0, 'Unlocked: ');
			$pdf->Write(0, $unlocked);
			$pdf->Write(0, '  ||  ');
			$pdf->Write(0, 'Total: ');
			$pdf->Write(0, $total);
			
			
			$pdf->SetXY(8, 5);
			$pdf->SetFont('Helvetica','B',8);
			$pdf->Write(0, '[ All Zone and All Clients ]');

			$pdf->SetXY(30, 8);
			$pdf->AddCol('c_id',20,'Name','L');
			$pdf->AddCol('con_type',13,'C.Type','L');
			$pdf->AddCol('join_date',15,'Join Date','L');
			$pdf->AddCol('bandwith',15,'Bandwith','L');
			$pdf->AddCol('connectivity_type',12,'Convty','L');
			$pdf->AddCol('address',35,'Address','L');
			$pdf->AddCol('thana',12,'Thana','L');
			$pdf->AddCol('cell',20,'Cell','L');
			$pdf->AddCol('email',20,'Email','L');
			$pdf->AddCol('ip',18,'IP Addrss','L');
			$pdf->AddCol('mac',25,'MAC Addrss','L');


			$prop=array('HeaderColor'=>array(160, 160, 160),
						'padding'=>1);
						
			$pdf->Table("SELECT c.c_id, c.c_name, c.con_type, c.join_date, p.bandwith, c.connectivity_type, c.address, c.thana, c.cell, c.email, c.ip, c.mac, z.z_name, c.con_sts FROM clients AS c
							LEFT JOIN package AS p
							ON p.p_id = c.p_id
							LEFT JOIN zone AS z
							ON z.z_id = c.z_id
							LEFT JOIN login AS l
							ON l.e_id = c.c_id
							WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND c.sts = '0' 
							ORDER BY c.join_date ASC",$prop);
		
	}
	
if($sts == 'active' && $z_id == 'allzone'){
	
			$sql2 = mysql_query("SELECT id FROM clients WHERE join_date BETWEEN '$f_date' AND '$t_date' AND con_sts = 'active' AND sts = '0'");
			$total = mysql_num_rows($sql2);
			
			$sql3 = mysql_query("SELECT id FROM clients WHERE join_date BETWEEN '$f_date' AND '$t_date' AND con_sts = 'active' AND sts = '0'");
			$active = mysql_num_rows($sql3);

			$sql5 = mysql_query("SELECT c.id FROM clients AS c LEFT JOIN login AS l ON l.e_id = c.c_id WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND l.log_sts = '0' AND c.con_sts = 'active' AND c.sts = '0'");
			$unlocked = mysql_num_rows($sql5);
			
			$sql6 = mysql_query("SELECT c.id FROM clients AS c LEFT JOIN login AS l ON l.e_id = c.c_id WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND l.log_sts = '1' AND c.con_sts = 'active' AND c.sts = '0'");
			$locked = mysql_num_rows($sql6);
			
			$pdf->SetXY(8, 5);
			$pdf->SetFont('Helvetica','B',8);
			$pdf->Write(0, '[ Active Clients in All Zones ]');
			$pdf->SetXY(90, 5);
			$pdf->Write(0, 'Active: ');
			$pdf->Write(0, $active);
			$pdf->Write(0, '  ||  ');
			$pdf->Write(0, 'Locked: ');
			$pdf->Write(0, $locked);
			$pdf->Write(0, '  ||  ');
			$pdf->Write(0, 'Unlocked: ');
			$pdf->Write(0, $unlocked);
			$pdf->Write(0, '  ||  ');
			$pdf->Write(0, 'Total: ');
			$pdf->Write(0, $total);
			$pdf->SetXY(8, 5);
			$pdf->SetFont('Helvetica','B',8);
			$pdf->Write(0, '[ Active Clients in All Zones ]');
			
			$pdf->SetXY(30, 8);
			$pdf->AddCol('c_id',20,'Name','L');
			$pdf->AddCol('con_type',13,'C.Type','L');
			$pdf->AddCol('join_date',15,'Join Date','L');
			$pdf->AddCol('bandwith',15,'Bandwith','L');
			$pdf->AddCol('connectivity_type',12,'Convty','L');
			$pdf->AddCol('address',35,'Address','L');
			$pdf->AddCol('thana',12,'Thana','L');
			$pdf->AddCol('cell',20,'Cell','L');
			$pdf->AddCol('email',20,'Email','L');
			$pdf->AddCol('ip',18,'IP Addrss','L');
			$pdf->AddCol('mac',25,'MAC Addrss','L');


			$prop=array('HeaderColor'=>array(160, 160, 160),
						'padding'=>1);
						
			$pdf->Table("SELECT c.c_id, c.c_name, c.con_type, c.join_date, p.bandwith, c.connectivity_type, c.address, c.thana, c.cell, c.email, c.ip, c.mac, z.z_name, c.con_sts FROM clients AS c
							LEFT JOIN package AS p
							ON p.p_id = c.p_id
							LEFT JOIN zone AS z
							ON z.z_id = c.z_id
							LEFT JOIN login AS l
							ON l.e_id = c.c_id
							WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND c.con_sts = 'active' AND c.sts = '0'
							ORDER BY c.join_date ASC",$prop);
		
	}
	
if($sts == 'inactive' && $z_id == 'allzone'){

			$sql2 = mysql_query("SELECT id FROM clients WHERE join_date BETWEEN '$f_date' AND '$t_date' AND con_sts = 'inactive' AND sts = '0'");
			$total = mysql_num_rows($sql2);
			
			$sql3 = mysql_query("SELECT id FROM clients WHERE join_date BETWEEN '$f_date' AND '$t_date' AND con_sts = 'inactive' AND sts = '0'");
			$inactive = mysql_num_rows($sql3);

			$sql5 = mysql_query("SELECT c.id FROM clients AS c LEFT JOIN login AS l ON l.e_id = c.c_id WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND l.log_sts = '0' AND c.con_sts = 'inactive' AND c.sts = '0'");
			$unlocked = mysql_num_rows($sql5);
			
			$sql6 = mysql_query("SELECT c.id FROM clients AS c LEFT JOIN login AS l ON l.e_id = c.c_id WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND l.log_sts = '1' AND c.con_sts = 'inactive' AND c.sts = '0'");
			$locked = mysql_num_rows($sql6);
			
			$pdf->SetXY(8, 5);
			$pdf->SetFont('Helvetica','B',8);
			$pdf->Write(0, '[ Inactive Clients in All Zones]');
			$pdf->SetXY(90, 5);
			$pdf->Write(0, 'Inactive: ');
			$pdf->Write(0, $inactive);
			$pdf->Write(0, '  ||  ');
			$pdf->Write(0, 'Locked: ');
			$pdf->Write(0, $locked);
			$pdf->Write(0, '  ||  ');
			$pdf->Write(0, 'Unlocked: ');
			$pdf->Write(0, $unlocked);
			$pdf->Write(0, '  ||  ');
			$pdf->Write(0, 'Total: ');
			$pdf->Write(0, $total);
			
			$pdf->SetXY(8, 5);
			$pdf->SetFont('Helvetica','B',8);
			$pdf->Write(0, '[ Inactive Clients in All Zones]');
			
			$pdf->AddCol('c_id',20,'Name','L');
			$pdf->AddCol('con_type',13,'C.Type','L');
			$pdf->AddCol('join_date',15,'Join Date','L');
			$pdf->AddCol('bandwith',15,'Bandwith','L');
			$pdf->AddCol('connectivity_type',12,'Convty','L');
			$pdf->AddCol('address',35,'Address','L');
			$pdf->AddCol('thana',12,'Thana','L');
			$pdf->AddCol('cell',20,'Cell','L');
			$pdf->AddCol('email',20,'Email','L');
			$pdf->AddCol('ip',18,'IP Addrss','L');
			$pdf->AddCol('mac',25,'MAC Addrss','L');


			$prop=array('HeaderColor'=>array(160, 160, 160),
						'padding'=>1);
						
			$pdf->Table("SELECT c.c_id, c.c_name, c.con_type, c.join_date, p.bandwith, c.connectivity_type, c.address, c.thana, c.cell, c.email, c.ip, c.mac, z.z_name, c.con_sts FROM clients AS c
							LEFT JOIN package AS p
							ON p.p_id = c.p_id
							LEFT JOIN zone AS z
							ON z.z_id = c.z_id
							LEFT JOIN login AS l
							ON l.e_id = c.c_id
							WHERE c.join_date BETWEEN '$f_date' AND '$t_date' AND c.con_sts = 'inactive' AND c.sts = '0'
							ORDER BY c.join_date ASC",$prop);
		
	}
$pdf->Output();
?>