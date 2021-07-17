<?php
require('mysql_table.php');
require('connection.php');

$z_id = $_REQUEST['z_id'];
$bill_month = $_REQUEST['bill_month'];
$billyear = date('Y', strtotime($bill_month));
$billmonth = date('m', strtotime($bill_month));
$billmonthh = date('M', strtotime($bill_month));
$billingmonth = date('F d, Y', strtotime($bill_month));


$resultz = mysql_query("SELECT z.z_id, z.z_name, z.e_id, e.e_name, e.e_cont_per FROM zone AS z
						LEFT JOIN emp_info AS e
						ON e.z_id = z.z_id
						WHERE z.z_id = '$z_id'");  							
$rowz = mysql_fetch_assoc($resultz);

$e_cont_per = $rowz['e_cont_per'];
$z_name = $rowz['z_name'];
$e_name = $rowz['e_name'];

if($z_id != ''){
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
		
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			$pdf->SetX(2);
			
			$pdf->Cell(170,6,'Zone: '.$z_name.'        Owner: '.$e_name.'         Cell: '.$e_cont_per,'LTB',0,'L',true);
			$pdf->Cell(35,6,'Billing Month: '.$billmonthh.', '.$billyear,'TBR',0,'R',true);
			$pdf->Ln();
			$pdf->SetX(2);
			$pdf->Cell(7,6,'S/L','LTB',0,'C',true);
			$pdf->Cell(95,6,'Package','LTB',0,'C',true);
			$pdf->Cell(25,6,'Bandwith','LTB',0,'C',true);
			$pdf->Cell(25,6,'Price (TK)','LTB',0,'C',true);
			$pdf->Cell(15,6,'Clients','LTB',0,'C',true);
			$pdf->Cell(38,6,'Total Bill','LTBR',0,'R',true);
			$pdf->Ln();
			
		$result = mysql_query("SELECT p.p_id, p.p_name, p.bandwith, p.p_price, SUM(b.bill_amount) AS billamount, COUNT(b.c_id) AS clints FROM package AS p
											LEFT JOIN billing AS b
											ON b.p_id = p.p_id
											WHERE p.z_id = '$z_id' AND MONTH(b.bill_date) = '$billmonth' AND YEAR(b.bill_date) = '$billyear' GROUP BY p.p_id ORDER BY clints DESC");  							
		
			$totalbillamounts = 0;
			$totalclintss = 0;
			$x = 1;
			
		while ($row1=mysql_fetch_array($result)) {
			
				$totalbillamount = $row1['billamount'];
				$totalclints = $row1['clints'];
				$p_name = $row1['p_name'];
				$bandwith = $row1['bandwith'];
				$p_price = $row1['p_price'];
				
				$yourtext = iconv('UTF-8', 'windows-1252', $ZoneNameBn);
				
				
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Helvetica','',8);
				$pdf->SetX(2);
				
				$pdf->Cell(7,5,$x,'LTB',0,'C',true);
				$pdf->Cell(95,5,$p_name,'LTB',0,'L',true);
				$pdf->Cell(25,5,$bandwith,'LTB',0,'L',true);
				$pdf->Cell(25,5,$p_price,'LTB',0,'L',true);
				$pdf->Cell(15,5,number_format($totalclints,0),'LTB',0,'C',true);
				$pdf->Cell(38,5,number_format($totalbillamount,0),'LTBR',0,'R',true);
				$pdf->Ln();
				
			$totalbillamounts += $totalbillamount;
			$totalclintss += $totalclints;
			$x++;
		}
			$pdf->SetX(2);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(152,6,'Total    ','LTB',0,'R',true);
			$pdf->Cell(15,6,number_format($totalclintss,0),'LTBR',0,'C',true);
			$pdf->Cell(38,6,number_format($totalbillamounts,0).' TK','LTBR',0,'R',true);
			
			$pdf->Ln();
}
else{
	echo 'ERROR!!! Please select one reseller.';
}
$pdf->Output();
?>