<?php
require('mysql_table.php');
require('connection.php');

//$bill_type = $_REQUEST['bill_type'];
$f_date = $_REQUEST['f_date'];
$t_date = $_REQUEST['t_date'];

	class PDF extends PDF_MySQL_Table
		{
		function Header()
		{
			parent::Header();
			
			$this->SetFillColor(160, 160, 160);
			$this->SetTextColor(0,0,0);
			$this->SetDrawColor(8, 102, 198);
			$this->SetFont('Helvetica','',8);
			
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
		
			$sql = mysql_query("SELECT SUM(p_price) AS price, SUM(discount) AS discounts, SUM(bill_discount) AS bill_discounts, SUM(bill_amount) AS bill_amounts FROM billing WHERE bill_date BETWEEN '$f_date' AND '$t_date'");
			$row = mysql_fetch_array($sql);
			$TotalActualBill = $row['price'];
			$Discount = $row['discounts'];
			$BillDiscount = $row['bill_discounts'];
			$TotalDiscount = $Discount + $BillDiscount;
			$TotalBillAmount = $row['bill_amounts'];
			
			$sql1 = mysql_query("SELECT SUM(pay_amount) AS pay_amounts, SUM(adv_bill) AS adv_bills FROM billing WHERE pay_date BETWEEN '$f_date' AND '$t_date'");
			$row1 = mysql_fetch_array($sql1);
			$TotalPaidAmount = $row1['pay_amounts'];
			$TotalAdvance = $row1['adv_bills'];
			
			$TotalDue = $TotalBillAmount - ($TotalPaidAmount + $BillDiscount);
			
			
			$sql2 = mysql_query("SELECT SUM(t.SignupBill) AS SignupBills, SUM(t.ReactivationCharge) AS ReactivationCharges, SUM(t.OthersBill) AS OthersBills FROM
								(
								SELECT 
								CASE WHEN bill_type = 1 THEN amount ELSE '0' END AS SignupBill,
								CASE WHEN bill_type = 2 THEN amount ELSE '0' END AS ReactivationCharge,
								CASE WHEN bill_type = 3 THEN amount ELSE '0' END AS OthersBill
								FROM bill_signup
								WHERE pay_date BETWEEN '$f_date' AND '$t_date'
								)t");
			$row2 = mysql_fetch_array($sql2);		
			$TotalSignUp = $row2['SignupBills'];
			$TotalReactive = $row2['ReactivationCharges'];
			$TotalOthersIncome = $row2['OthersBills'];
			
			$sql3 = mysql_query("SELECT SUM(b.amount) AS new_adv FROM bill_advance AS b
							LEFT JOIN clients AS c ON b.c_id = c.c_id 
							WHERE b.adv_date BETWEEN '$f_date' AND '$t_date'");
			$row3 = mysql_fetch_array($sql3);
			$NewAdvance = $row3['new_adv'];
			
			$GrandTotal = $TotalPaidAmount + $TotalAdvance + $TotalSignUp + $TotalReactive + $TotalOthersIncome + $NewAdvance;
			
			$pdf->SetX(50);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(120,6,'Bill Summary ( From '.$f_date.' To '.$t_date.' )','LTBR',0,'C',true);
			$pdf->Ln();
			
			$pdf->SetX(50);
			$pdf->SetFillColor(160, 160, 160);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(10,6,'S/L','LTB',0,'C',true);
			$pdf->Cell(60,6,'Particular','LTBR',0,'L',true);
			$pdf->Cell(50,6,'Amount BDT','LTBR',0,'L',true);
			$pdf->Ln();
			
			$pdf->SetX(50);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(10,6,'1','LTB',0,'C',true);
			$pdf->Cell(60,6,'Total Actual Bill','LTBR',0,'L',true);
			$pdf->Cell(50,6,number_format($TotalActualBill,0),'LTBR',0,'R',true);
			$pdf->Ln();
			$pdf->SetX(50);
			$pdf->Cell(10,6,'2','LTB',0,'C',true);
			$pdf->Cell(60,6,'Total Discount','LTBR',0,'L',true);
			$pdf->Cell(50,6,number_format($TotalDiscount,0),'LTBR',0,'R',true);
			$pdf->Ln();
			$pdf->SetX(50);
			$pdf->Cell(10,6,'3','LTB',0,'C',true);
			$pdf->Cell(60,6,'Total Bill Amount','LTBR',0,'L',true);
			$pdf->Cell(50,6,number_format($TotalBillAmount,0),'LTBR',0,'R',true);
			$pdf->Ln();
			$pdf->SetX(50);
			$pdf->Cell(10,6,'4','LTB',0,'C',true);
			$pdf->Cell(60,6,'Total Paid Amount','LTBR',0,'L',true);
			$pdf->Cell(50,6,number_format($TotalPaidAmount,0),'LTBR',0,'R',true);
			$pdf->Ln();
			$pdf->SetX(50);
			$pdf->Cell(10,6,'5','LTB',0,'C',true);
			$pdf->Cell(60,6,'Total Advance','LTBR',0,'L',true);
			$pdf->Cell(50,6,number_format($TotalAdvance,0),'LTBR',0,'R',true);
			$pdf->Ln();
			$pdf->SetX(50);
			$pdf->Cell(10,6,'5','LTB',0,'C',true);
			$pdf->Cell(60,6,'Total Advance New','LTBR',0,'L',true);
			$pdf->Cell(50,6,number_format($NewAdvance,0),'LTBR',0,'R',true);
			$pdf->Ln();
			$pdf->SetX(50);
			$pdf->Cell(10,6,'6','LTB',0,'C',true);
			$pdf->Cell(60,6,'Total Due','LTBR',0,'L',true);
			$pdf->Cell(50,6,number_format($TotalDue,0),'LTBR',0,'R',true);
			$pdf->Ln();
			$pdf->SetX(50);
			$pdf->Cell(10,6,'7','LTB',0,'C',true);
			$pdf->Cell(60,6,'Total Sign Up','LTBR',0,'L',true);
			$pdf->Cell(50,6,number_format($TotalSignUp,0),'LTBR',0,'R',true);
			$pdf->Ln();
			$pdf->SetX(50);
			$pdf->Cell(10,6,'8','LTB',0,'C',true);
			$pdf->Cell(60,6,'Total Reactive','LTBR',0,'L',true);
			$pdf->Cell(50,6,number_format($TotalReactive,0),'LTBR',0,'R',true);
			$pdf->Ln();
			$pdf->SetX(50);
			$pdf->Cell(10,6,'9','LTB',0,'C',true);
			$pdf->Cell(60,6,'Total Others Income','LTBR',0,'L',true);
			$pdf->Cell(50,6,number_format($TotalOthersIncome,0),'LTBR',0,'R',true);
			$pdf->Ln();
			$pdf->SetX(50);
			$pdf->Cell(10,6,'10','LTB',0,'C',true);
			$pdf->Cell(60,6,'Grand Total','LTBR',0,'L',true);
			$pdf->Cell(50,6,number_format($GrandTotal,0),'LTBR',0,'R',true);
			$pdf->Ln();
			
		
$pdf->Output();
?>