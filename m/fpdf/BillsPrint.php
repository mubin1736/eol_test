<?php
require('mysql_table.php');
require('connection.php');
require('Function.php');
$z_id = $_GET['id'];
extract($_POST);


$todayy = date("Y-m-d");

$f_date = date('Y-m-01', strtotime($todayy));
$t_date = date('Y-m-t', strtotime($todayy));


$yrda= strtotime($todayy); 
$dates = date('M-d, y', $yrda);
$month = date('M, Y', $yrda);
$monthonly = date('M', $yrda);
		
class PDF extends PDF_MySQL_Table
{

}

$pdf=new PDF();
$pdf->AddPage();
if($z_id == 'all' && $p_m == ''){
$result = mysql_query("SELECT t1.c_id, t1.c_name, t1.address, t1.note, t1.cell, t1.p_price, t1.z_name, IFNULL(t2.dis,0.00) AS dis, IFNULL(t2.billl,0.00) AS bill, IFNULL(t3.bill_disc,0.00) AS bill_disc, t1.p_m, IFNULL(t3.pay, 0.00) AS pay FROM
								(
								SELECT c.c_id, c.c_name, c.address, c.cell, c.p_id, p.p_price, z.z_name, c.note, c.p_m FROM clients AS c 
								LEFT JOIN package AS p ON c.p_id = p.p_id
								LEFT JOIN zone AS z ON z.z_id = c.z_id WHERE c.mac_user = '0'
								)t1
								LEFT JOIN
								(
								SELECT b.c_id, SUM(b.bill_amount) AS billl, SUM(b.discount) AS dis FROM billing AS b
								WHERE MONTH(b.bill_date) = MONTH('$f_date')
								GROUP BY b.c_id
								)t2
								ON t1.c_id = t2.c_id
								LEFT JOIN
								(
								SELECT p.c_id, SUM(p.pay_amount) AS pay, SUM(p.bill_discount) AS bill_disc FROM payment AS p 
								WHERE p.pay_date BETWEEN '$f_date' AND '$t_date'
								GROUP BY p.c_id
								)t3
								ON t1.c_id = t3.c_id");  
$x = 0;							
}
if($z_id == 'all' && $p_m != ''){
$result = mysql_query("SELECT t1.c_id, t1.c_name, t1.address, t1.note, t1.cell, t1.p_price, t1.z_name, IFNULL(t2.dis,0.00) AS dis, IFNULL(t2.billl,0.00) AS bill, IFNULL(t3.bill_disc,0.00) AS bill_disc, t1.p_m, IFNULL(t3.pay, 0.00) AS pay FROM
								(
								SELECT c.c_id, c.c_name, c.address, c.cell, c.p_id, p.p_price, z.z_name, c.note, c.p_m FROM clients AS c 
								LEFT JOIN package AS p ON c.p_id = p.p_id
								LEFT JOIN zone AS z ON z.z_id = c.z_id WHERE c.mac_user = '0' AND c.p_m = '$p_m'
								)t1
								LEFT JOIN
								(
								SELECT b.c_id, SUM(b.bill_amount) AS billl, SUM(b.discount) AS dis FROM billing AS b
								WHERE MONTH(b.bill_date) = MONTH('$f_date')
								GROUP BY b.c_id
								)t2
								ON t1.c_id = t2.c_id
								LEFT JOIN
								(
								SELECT p.c_id, SUM(p.pay_amount) AS pay, SUM(p.bill_discount) AS bill_disc FROM payment AS p 
								WHERE p.pay_date BETWEEN '$f_date' AND '$t_date'
								GROUP BY p.c_id
								)t3
								ON t1.c_id = t3.c_id");  
$x = 0;							
}
if($z_id != 'all' && $p_m != ''){
$result = mysql_query("SELECT t1.c_id, t1.c_name, t1.address, t1.note, t1.cell, t1.p_price, t1.z_name, IFNULL(t2.dis,0.00) AS dis, IFNULL(t2.billl,0.00) AS bill, IFNULL(t3.bill_disc,0.00) AS bill_disc, t1.p_m, IFNULL(t3.pay, 0.00) AS pay FROM
								(
								SELECT c.c_id, c.c_name, c.address, c.cell, c.p_id, p.p_price, z.z_name, c.note, c.p_m FROM clients AS c 
								LEFT JOIN package AS p ON c.p_id = p.p_id
								LEFT JOIN zone AS z ON z.z_id = c.z_id WHERE c.mac_user = '0' AND c.p_m = '$p_m' AND c.z_id = '$z_id'
								)t1
								LEFT JOIN
								(
								SELECT b.c_id, SUM(b.bill_amount) AS billl, SUM(b.discount) AS dis FROM billing AS b
								WHERE MONTH(b.bill_date) = MONTH('$f_date')
								GROUP BY b.c_id
								)t2
								ON t1.c_id = t2.c_id
								LEFT JOIN
								(
								SELECT p.c_id, SUM(p.pay_amount) AS pay, SUM(p.bill_discount) AS bill_disc FROM payment AS p 
								WHERE p.pay_date BETWEEN '$f_date' AND '$t_date'
								GROUP BY p.c_id
								)t3
								ON t1.c_id = t3.c_id");  
$x = 0;							
}
if($z_id != 'all' && $p_m == ''){
$result = mysql_query("SELECT t1.c_id, t1.c_name, t1.address, t1.note, t1.cell, t1.p_price, t1.z_name, IFNULL(t2.dis,0.00) AS dis, IFNULL(t2.billl,0.00) AS bill, IFNULL(t3.bill_disc,0.00) AS bill_disc, t1.p_m, IFNULL(t3.pay, 0.00) AS pay FROM
								(
								SELECT c.c_id, c.c_name, c.address, c.cell, c.p_id, p.p_price, z.z_name, c.note, c.p_m FROM clients AS c 
								LEFT JOIN package AS p ON c.p_id = p.p_id
								LEFT JOIN zone AS z ON z.z_id = c.z_id WHERE c.mac_user = '0' AND c.z_id = '$z_id'
								)t1
								LEFT JOIN
								(
								SELECT b.c_id, SUM(b.bill_amount) AS billl, SUM(b.discount) AS dis FROM billing AS b
								WHERE MONTH(b.bill_date) = MONTH('$f_date')
								GROUP BY b.c_id
								)t2
								ON t1.c_id = t2.c_id
								LEFT JOIN
								(
								SELECT p.c_id, SUM(p.pay_amount) AS pay, SUM(p.bill_discount) AS bill_disc FROM payment AS p 
								WHERE p.pay_date BETWEEN '$f_date' AND '$t_date'
								GROUP BY p.c_id
								)t3
								ON t1.c_id = t3.c_id");  
$x = 0;							
}

while ($row=mysql_fetch_array($result)) {
				$date = $row['pay_date'];
				$c_id = $row['c_id'];
				$c_name = $row['c_name'];
				$address = $row['address'];
				$cell = $row['cell'];
				$p_price = $row['p_price'];
				$discount = $row['dis'];
				$bill = $row['bill'];
				$bill_disc = $row['bill_disc'];
				$pay = $row['pay'];
				$note = $row['note'];
				$pa_m = $row['p_m'];
				$z_name = $row['z_name'];
				
				$x++;
				
				$balance = $bill - ($bill_disc + $pay);
				
				$ress = mysql_query("SELECT b.id AS billno FROM billing AS b	WHERE b.c_id = '$c_id' ORDER BY b.id DESC LIMIT 1");
				$rowsss = mysql_fetch_array($ress);
				$billno = $rowsss['billno'];
				
				$results = mysql_query("SELECT t2.c_id, IFNULL(t2.dis, 0.00) AS dis, IFNULL(t2.bill, 0.00) AS bill, IFNULL(t3.bill_disc, 0.00) AS bill_disc, IFNULL(t3.pay, 0.00) AS pay FROM
										(
										SELECT b.c_id, SUM(b.bill_amount) AS bill, SUM(b.discount) AS dis FROM billing AS b
										WHERE b.bill_date < '$f_date' AND b.c_id = '$c_id'
										GROUP BY b.c_id
										)t2
										LEFT JOIN
										(
										SELECT p.c_id, SUM(p.pay_amount) AS pay, SUM(p.bill_discount) AS bill_disc FROM payment AS p 
										WHERE p.pay_date < '$f_date' AND p.c_id = '$c_id'
										GROUP BY p.c_id
										)t3
										ON t2.c_id = t3.c_id");
				$rows = mysql_fetch_array($results);					
				$dew = $rows['bill'] - ($rows['bill_disc'] + $rows['pay']);
				
				
				if($dew < 0){
					$dews = 0.00;
					$advs = abs($dew);
				}else{
					$dews = $dew;
					$advs = 0.00;
				}
				
				$total_payable = number_format(($balance + $dew),2);
				
		if($total_payable == '0.00'){
			
			$bill = '0.00';
		}		
		$aaa = ($bill - $bill_disc) - $advs;
		
		
		if($total_payable >= '1'){
			global $comp_name;
			global $copmaly_address;
		$inWord = floatval($total_payable);
		$pdf->SetX(5);
		$pdf->SetDrawColor(3, 3, 3);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(3,3,3);
		$pdf->SetFont('Helvetica','B',10);
		$pdf->Cell(2,5,'','0',0,'L',false);
		$pdf->Cell(15,5,$comp_name,'0',0,'L',false);
		$pdf->SetFont('Helvetica','',7);
		$pdf->SetTextColor(3,3,3);
		$pdf->Cell(65,5,'INV-BN'.$billno,'0',0,'R',false);
		$pdf->Cell(5,5,'','0',0,'L',false);
		$pdf->SetFont('Helvetica','B',10);
		$pdf->SetTextColor(3,3,3);
		$pdf->Cell(30,5,$comp_name,'0',0,'L',false);
		$pdf->SetFont('Helvetica','',7);
		$pdf->SetTextColor(3,3,3);
		$pdf->Cell(83,5,'INV-BN'.$billno,'0',0,'R',false);
		$pdf->Ln();
		
		$pdf->SetX(5);
		$pdf->SetTextColor(3,3,3);
		$pdf->SetFont('Helvetica','',7);
		$pdf->Cell(2,5,'','0',0,'L',false);
		$pdf->Cell(15,2,$copmaly_address,'0',0,'L',false);
		$pdf->Cell(65,2,$dates,'0',0,'R',false);
		$pdf->Cell(5,5,'','0',0,'L',false);
		$pdf->Cell(43,2,$copmaly_address,'0',0,'L',false);
		$pdf->Cell(70,2,$dates,'0',0,'R',false);
		$pdf->Ln(10);
		
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',7);
		$pdf->Cell(2,5,'','0',0,'L',false);
		$pdf->Cell(67,3,$c_name,'0',0,'L',false);
		$pdf->Cell(3,3,'Bill Amount : ','0',0,'R',false);
		$pdf->Cell(10,3,$bill,'0',0,'R',false);
		$pdf->Cell(5,5,'','0',0,'L',false);
		$pdf->Cell(10,3,$c_name,'0',0,'L',false);
		$pdf->Cell(93,3,'Bill Amount : ','0',0,'R',false);
		$pdf->Cell(10,3,$bill,'0',0,'R',false);
		$pdf->Ln();
		
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',7);
		$pdf->Cell(2,5,'','0',0,'L',false);
		$pdf->Cell(67,3,'User ID : '.$c_id,'0',0,'L',false);
		$pdf->Cell(3,3,'Discount : ','0',0,'R',false);
		$pdf->Cell(10,3,$discount,'0',0,'R',false);
		$pdf->Cell(5,5,'','0',0,'L',false);
		$pdf->Cell(23,3,'User ID : '.$c_id,'0',0,'L',false);
		$pdf->Cell(80,3,'Discount : ','0',0,'R',false);
		$pdf->Cell(10,3,$discount,'0',0,'R',false);
		$pdf->Ln();
		
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',7);
		$pdf->Cell(2,5,'','0',0,'L',false);
		$pdf->Cell(67,3,'Zone : '.$z_name,'0',0,'L',false);
		$pdf->Cell(3,3,'Advance : ','0',0,'R',false);
		$pdf->Cell(10,3,number_format($advs,2),'B',0,'R',false);
		$pdf->Cell(5,5,'','0',0,'L',false);
		$pdf->Cell(73,3,$address,'0',0,'L',false);
		$pdf->Cell(30,3,'Advance : ','0',0,'R',false);
		$pdf->Cell(10,3,number_format($advs,2),'B',0,'R',false);
		$pdf->Ln();
		
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',7);
		$pdf->Cell(2,5,'','0',0,'L',false);
		$pdf->Cell(67,4,$cell,'0',0,'L',false);
		$pdf->Cell(3,4,'Sum : ','0',0,'R',false);
		$pdf->Cell(10,4,number_format($aaa,2),'0',0,'R',false);
		$pdf->Cell(5,4,'','0',0,'L',false);
		$pdf->Cell(73,4,$cell,'0',0,'L',false);
		$pdf->Cell(30,4,'Sum : ','0',0,'R',false);
		$pdf->Cell(10,4,number_format($aaa,2),'0',0,'R',false);
		$pdf->Ln();
		
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',7);
		$pdf->Cell(2,5,'','0',0,'L',false);
		$pdf->Cell(67,3,'','0',0,'L',false);
		$pdf->Cell(3,3,'Vat : ','0',0,'R',false);
		$pdf->Cell(10,3,'0.00','B',0,'R',false);
		$pdf->Cell(5,3,'','0',0,'L',false);
		$pdf->Cell(73,3,'','0',0,'L',false);
		$pdf->Cell(30,3,'Vat : ','0',0,'R',false);
		$pdf->Cell(10,3,'0.00','B',0,'R',false);
		$pdf->Ln();
		
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',7);
		$pdf->Cell(2,5,'','0',0,'L',false);
		$pdf->Cell(67,4,'','0',0,'L',false);
		$pdf->Cell(3,4,'Sum With Vat : ','0',0,'R',false);
		$pdf->Cell(10,4,number_format($aaa,2),'0',0,'R',false);
		$pdf->Cell(5,4,'','0',0,'L',false);
		$pdf->Cell(73,4,'','0',0,'L',false);
		$pdf->Cell(30,4,'Sum With Vat : ','0',0,'R',false);
		$pdf->Cell(10,4,number_format($aaa,2),'0',0,'R',false);
		$pdf->Ln();
		
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',7);
		$pdf->Cell(2,5,'','0',0,'L',false);
		$pdf->Cell(67,3,'Month : '.$month,'0',0,'L',false);
		$pdf->Cell(3,3,'Previous Due : ','0',0,'R',false);
		$pdf->Cell(10,3,number_format($dews,2),'B',0,'R',false);
		$pdf->Cell(5,3,'','0',0,'L',false);
		$pdf->Cell(73,3,'Billing Month : '.$month,'0',0,'L',false);
		$pdf->Cell(30,3,'Previous Due : ','0',0,'R',false);
		$pdf->Cell(10,3,number_format($dews,2),'B',0,'R',false);
		$pdf->Ln();
		
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',7);
		$pdf->Cell(67,4,'','0',0,'L',false);
		$pdf->Cell(2,5,'','0',0,'L',false);
		$pdf->Cell(3,4,'Total : ','0',0,'R',false);
		$pdf->Cell(10,4,$total_payable,'0',0,'R',false);
		$pdf->Cell(5,4,'','0',0,'L',false);
		$pdf->Cell(73,4,'','0',0,'L',false);
		$pdf->Cell(30,4,'Total : ','0',0,'R',false);
		$pdf->Cell(10,4,$total_payable,'0',0,'R',false);
		$pdf->Ln();
		
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',7);
		$pdf->Cell(2,5,'','0',0,'L',false);
		$pdf->Cell(67,3,'In Words : '.convert_number_to_words($inWord).' Taka Only','0',0,'L',false);
		$pdf->Cell(3,3,'','0',0,'R',false);
		$pdf->Cell(10,3,'','',0,'R',false);
		$pdf->Cell(5,3,'','0',0,'L',false);
		$pdf->Cell(73,3,'In Words : '.convert_number_to_words($inWord).' Taka Only','0',0,'L',false);
		$pdf->Cell(30,3,'','0',0,'R',false);
		$pdf->Cell(10,3,'','0',0,'R',false);
		$pdf->Ln();
		
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',7);
		$pdf->Cell(2,5,'','0',0,'L',false);
		$pdf->Cell(67,3,'','0',0,'L',false);
		$pdf->Cell(3,3,'','0',0,'R',false);
		$pdf->Cell(10,3,'','',0,'R',false);
		$pdf->Cell(5,3,'','0',0,'L',false);
		$pdf->Cell(73,3,'','0',0,'L',false);
		$pdf->Cell(30,3,'','0',0,'R',false);
		$pdf->Cell(10,3,'','0',0,'R',false);
		$pdf->Ln();
		
		$pdf->SetX(5);
		$pdf->SetFont('Helvetica','',7);
		$pdf->Cell(2,5,'','0',0,'L',false);
		$pdf->Cell(67,3,'','0',0,'L',false);
		$pdf->Cell(3,3,'','0',0,'R',false);
		$pdf->Cell(10,3,'','',0,'R',false);
		$pdf->Cell(5,3,'','0',0,'L',false);
		$pdf->Cell(73,3,'Note: Please take money receipt while paying money.','0',0,'L',false);
		$pdf->Cell(30,3,'','0',0,'R',false);
		$pdf->Cell(10,3,'Signature','0',0,'R',false);

		$pdf->Ln(15);
		$pdf->SetX(0);
		$pdf->SetDrawColor(255,0,0);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(210,5,$x,'B',0,'L',true);
		$pdf->Ln(25);
		}
		else {
			
		}

}
$pdf->Output();
?>