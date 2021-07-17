<?php
require('mysql_table.php');
require('connection.php');
require('Function.php');
extract($_POST);

$todayy = date("Y-m-d");

$f_date = date('Y-m-01', strtotime($todayy));
$t_date = date('Y-m-t', strtotime($todayy));


$yrda= strtotime($todayy); 
$dates = date('jS M Y', $yrda);
$month = date('M, Y', $yrda);
$monthonly = date('M', $yrda);
		
class PDF extends PDF_MySQL_Table
{

}

$pdf=new FPDF('P','mm',array(58,115));
$pdf->AddPage();
					

$sql = mysql_query("SELECT p.id, c.c_name, p.c_id, p.pay_date, p.pay_date_time, e.e_name AS pay_ent_byname, e.e_cont_per, p.pay_amount, p.bill_discount, p.pay_amount AS paymentamount FROM payment AS p 
						LEFT JOIN clients AS c ON c.c_id = p.c_id
						LEFT JOIN zone AS z ON z.z_id = c.z_id
						LEFT JOIN emp_info AS e ON e.e_id = p.pay_ent_by
						WHERE p.id = '$mrno'");
			$row1 = mysql_fetch_array($sql);

$idd = $row1['id']; 
$cid = $row1['c_id'];
$cname = $row1['c_name'];
$paydate = $row1['pay_date'];
$pay_datetime = $row1['pay_date_time'];
$payamount = $row1['pay_amount'];
$paymentamount = $row1['paymentamount'];
$pay_ent_byname = $row1['pay_ent_byname'];
$econt_per = $row1['e_cont_per'];
$billdiscount = $row1['bill_discount'];


$date = strtotime($pay_datetime);
$date1 = strtotime("+1 minute", $date);
$date2 = date('Y-m-d H:i:s', $date1);

$yrdasdf= strtotime($pay_datetime); 
$paymentdate = date('M-j, Y g:ia', $yrdasdf);

				$results = mysql_query("SELECT t2.c_id, IFNULL(t2.dis, 0.00) AS dis, IFNULL(t2.bill, 0.00) AS bill, IFNULL(t3.bill_disc, 0.00) AS bill_disc, IFNULL(t3.pay, 0.00) AS pay FROM
										(
										SELECT b.c_id, sum(b.bill_amount) AS bill, b.discount AS dis FROM billing AS b
										WHERE b.bill_date_time < '$pay_datetime' AND b.c_id = '$cid'
										GROUP BY b.c_id
										)t2
										LEFT JOIN
										(
										SELECT p.c_id, sum(p.pay_amount) AS pay, sum(p.bill_discount) AS bill_disc FROM payment AS p 
										WHERE p.pay_date_time < '$pay_datetime' AND p.c_id = '$cid'
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
				
//				$total_payable = number_format(($balance + $dew),2);
				
		if($total_payable == '0.00'){
			
			$bill = '0.00';
		}		
		$aaa = ($bill - $bill_disc) - $advs;
		
		$intotal = $dew - $paymentamount;
		
		global $comp_name;
		global $copmaly_address;
		global $company_cell;
		$inWord = floatval($paymentamount);
		$pdf->SetX(10);
		$pdf->SetDrawColor(3, 3, 3);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(3,3,3);
		$pdf->SetFont('Helvetica','',18);
		$pdf->Cell(0,0,'Expert Online','0',0,'C',false);
		$pdf->Ln(5);
		
		$pdf->SetX(10);
		$pdf->SetFont('Helvetica','',10);
		$pdf->Cell(0,0,'R: 12, H: 44, DIT Project','0',0,'C',false);
		$pdf->Ln(5);
		
		$pdf->SetX(10);
		$pdf->SetFont('Helvetica','',10);
		$pdf->Cell(0,0,'MR-'.$idd.' / '.$paymentdate,'0',0,'C',false);
		$pdf->Ln(5);
		
		$pdf->SetX(10);
		$pdf->SetFont('Helvetica','',10);
		$pdf->Cell(0,0,$cname.' ('.$cid.')','0',0,'C',false);
		$pdf->Ln(10);
		
		$pdf->SetX(11);
		$pdf->SetFont('Helvetica','',12);
		$pdf->Cell(1,0,'Total Bill','0',0,'C',false);
		$pdf->Ln(0);
		
		$pdf->SetX(0);
		$pdf->SetFont('Helvetica','',12);
		$pdf->Cell(55,0,number_format($dew,2),'0',0,'R',false);
		$pdf->Ln(7);
		
		$pdf->SetX(10);
		$pdf->SetFont('Helvetica','',12);
		$pdf->Cell(11,0,'Paid Amount','0',0,'C',false);
		$pdf->Ln(0);
		
		$pdf->SetX(0);
		$pdf->SetFont('Helvetica','',12);
		$pdf->Cell(55,0,number_format($paymentamount,2),'0',0,'R',false);
		$pdf->Ln(5);
		
		$pdf->SetX(6);
		$pdf->SetFont('Helvetica','',12);
		$pdf->Cell(11,0,'Discount','0',0,'C',false);
		$pdf->Ln(0);
		
		$pdf->SetX(0);
		$pdf->SetFont('Helvetica','',12);
		$pdf->Cell(55,0,number_format($billdiscount,2),'0',0,'R',false);
		$pdf->Ln(5);
		
		$pdf->SetX(35);
		$pdf->SetFont('Helvetica','',12);
		$pdf->Cell(20,0,'','B',0,'L',false);
		$pdf->Ln(4);
		
		$pdf->SetX(9);
		$pdf->SetFont('Helvetica','',12);
		$pdf->Cell(11,0,'Total Due','0',0,'C',false);
		$pdf->Ln(0);
		
		$pdf->SetX(15);
		$pdf->SetFont('Helvetica','',12);
		$pdf->Cell(40,0,number_format($intotal,2),'0',0,'R',false);
		$pdf->Ln(8);
		
		$pdf->SetX(10);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(0,0,convert_number_to_words($inWord).' Taka.','0',0,'C',false);
		$pdf->Ln(7);
		
		$pdf->SetX(10);
		$pdf->SetFont('Helvetica','',10);
		$pdf->Cell(0,0,'Thank You','0',0,'C',false);
		$pdf->Ln(5);
		
		$pdf->SetX(7);
		$pdf->SetFont('Helvetica','',12);
		$pdf->Cell(0,0,'HELP - '.$company_cell,'0',0,'C',false);
		$pdf->Ln(4);
		
		$pdf->SetX(10);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(0,0,'[Entry By - '.$pay_ent_byname.']','0',0,'C',false);
		$pdf->Ln(8);
		
    	$pdf->SetX(10);
		$pdf->SetFont('Helvetica','',12);
		$pdf->Cell(0,0,'','0',0,'C',false);
		$pdf->Ln(5);
		
		$pdf->SetX(10);
		$pdf->SetFont('Helvetica','',12);
		$pdf->Cell(0,0,'Loundry 30% Discount','0',0,'C',false);
		$pdf->Ln();

$pdf->Output();
?>