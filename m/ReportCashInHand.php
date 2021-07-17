<?php
$titel = "Support";
$Support = 'active';
include('include/hader.php');
extract($_POST);
date_default_timezone_set('Etc/GMT-6');

//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'Reports' AND $user_type = '1'");
if(mysql_num_rows($access) > 0){
//---------- Permission -----------
$userName = $_SESSION['SESS_FIRST_NAME'];
$Date = date('d/m/Y');

if($f_date == '' || $t_date == ''){
$f_date = date("Y-m-01");
$t_date = date("Y-m-d");}
?>
	<div class="box box-primary">
	<div class="box-header">
		<form id="" name="form" class="stdform" method="post" action="<?php echo $PHP_SELF;?>">
			<input type="text" name="f_date" id="" style="width:35%;text-align: center;" placeholder="From Date" class="surch_emp datepicker" value="<?php echo $f_date; ?>"/>
			<input type="text" name="t_date" id="" style="width:35%;text-align: center;" placeholder="To Date" class="surch_emp datepicker" value="<?php echo $t_date; ?>"/>
			<input type="hidden" name="bank" value="<?php echo $bank; ?>"/>
			<button class="btn col5" type="submit"><i class="iconsweets-magnifying iconsweets-white"></i></button>
		</form>
	</div>
			<div class="box-body">
					<?php if($bank !=''){
					$sqlop1 = mysql_query("SELECT SUM(transfer_amount) AS TFR FROM fund_transfer WHERE fund_received = '$bank' AND transfer_date < '$f_date'");
					$sqlop2 = mysql_query("SELECT SUM(amount) AS TLR FROM loan_receive WHERE bank = '$bank' AND loan_date < '$f_date'");
					$sqlop3 = mysql_query("SELECT SUM(pay_amount) AS TBC FROM payment WHERE bank = '$bank' AND pay_date  < '$f_date'");
					$sqlop400 = mysql_query("SELECT SUM(amount) AS OBS FROM bill_signup WHERE bank = '$bank' AND pay_date < '$f_date'");
					$sqlop600 = mysql_query("SELECT SUM(amount) AS EBS FROM bill_extra WHERE bank = '$bank' AND pay_date < '$f_date'");
					$sqlop500 = mysql_query("SELECT SUM(pay_amount) AS MRP FROM payment_macreseller WHERE sts = '0' AND bank = '$bank' AND pay_date < '$f_date'");

					$rowop1 = mysql_fetch_array($sqlop1);
					$fundReceivedOp = $rowop1['TFR'];
					
					$rowop2 = mysql_fetch_array($sqlop2);
					$loanReceiveOp = $rowop2['TLR'];
					
					$rowop3 = mysql_fetch_array($sqlop3);
					$billCollectionOp = $rowop3['TBC'];
					
					$rowop400 = mysql_fetch_array($sqlop400);
					$billSignupOp = $rowop400['OBS'];
					
					$rowop500 = mysql_fetch_array($sqlop500);
					$MacresellerPaymentOp = $rowop500['MRP'];
					
					$rowop600 = mysql_fetch_array($sqlop600);
					$ExtraBillOp = $rowop600['EBS'];
					
					
					$sqlopCr1 = mysql_query("SELECT SUM(transfer_amount) AS TFS FROM fund_transfer WHERE fund_send = '$bank' AND transfer_date < '$f_date'");
					$sqlopCr2 = mysql_query("SELECT SUM(amount) AS TLP FROM loan_payment WHERE bank = '$bank' AND payment_date < '$f_date'");
					$sqlopCr4 = mysql_query("SELECT SUM(amount) AS TP FROM expanse WHERE bank = '$bank' AND status = '2' AND ex_date < '$f_date'");
					$sqlopCr5 = mysql_query("SELECT SUM(amount) AS SAP FROM emp_salary_payment WHERE bank = '$bank' AND payment_date < '$f_date'");
					$sqlopCr6 = mysql_query("SELECT SUM(amount) AS VPP FROM vendor_payment WHERE bank = '$bank' AND sts = '0' AND payment_date < '$f_date'");
					
					$rowop7 = mysql_fetch_array($sqlopCr1);
					$fundSendOp = $rowop7['TFS'];
					
					$rowop8 = mysql_fetch_array($sqlopCr2);
					$loanPaidOp = $rowop8['TLP'];
					
					$rowop10 = mysql_fetch_array($sqlopCr4);
					$allPaymentOp = $rowop10['TP'];
					
					$rowop11 = mysql_fetch_array($sqlopCr5);
					$allSalaryOp = $rowop11['SAP'];
					
					$rowop12 = mysql_fetch_array($sqlopCr6);
					$allVpOp = $rowop12['VPP'];
					
					$openingRecived = $fundReceivedOp + $loanReceiveOp + $billSignupOp + $ExtraBillOp + $billCollectionOp + $MacresellerPaymentOp;
					$openingPayment = $fundSendOp + $loanPaidOp + $allPaymentOp + $allSalaryOp + $allVpOp;
					
					$openingBalance = $openingRecived - $openingPayment;
					
					$Bankkk = mysql_query("SELECT b.bank_name, b.id, e.e_id, e.e_name, e.e_cont_per, d.dept_name FROM bank AS b LEFT JOIN emp_info AS e ON e.e_id = b.emp_id LEFT JOIN department_info AS d ON d.dept_id = e.dept_id WHERE b.id = '$bank'");
					$Bnkkk=mysql_fetch_array($Bankkk);
					$Bankname = $Bnkkk['bank_name'];
					$Bankid = $Bnkkk['id'];
					$BanEmpName = $Bnkkk['e_name'];
					$BanDeptName = $Bnkkk['dept_name'];
					$BanEid = $Bnkkk['e_id'];
					$BanEmpCell = $Bnkkk['e_cont_per'];
					?>
			<div id="hd">
				<table style="width:100%;background: #eeeeee;font-size: 14px;height: 30px;">
					<tr>
						<td style="font-size: 10px;padding: 5px;"><?php echo $BanEmpName.' ('.$BanEid.')'; ?><br><?php echo $BanEmpCell; ?><br><?php echo $Bankname;?></td>
					</tr>	
				</table>
			</div>
					<table id="dyntable" class="table table-bordered responsive">
						<colgroup>
								<col class="con1" />
								<col class="con0" />
								<col class="con1" />
						</colgroup>
						<thead>
								<tr  class="newThead">
									<th class="head1" style="font-size: 10px;padding: 5px;text-align: center;">Particulars</th>
									<th class="head0" style="font-size: 10px;padding: 5px;text-align: center;">Received</th>
									<th class="head1" style="font-size: 10px;padding: 5px;text-align: center;">PAID</th>
								</tr>
						</thead>
					<tbody>
					<tr class='gradeX'>
						<td style="text-align: right;font-size: 10px;"><b>Opeaning Balance:</b></td>
						<td style="font-size: 10px;text-align: center;"><b><?php echo $openingBalance; ?></b></td>
						<td></td>
					</tr>
					<?php			
					$x = 1;
					$TotBalanceTransferRcv = 0;	
					$TotalLoanAmount = 0;
					$TotalBillCollection = 0;
					$TotaltransferAmountCr = 0;
					$TotalAmountCr = 0;
					$TotalExAmountCr = 0;
					$TotalSalaryCr = 0;
					$TotalVendCr = 0;
					$TotOtherAmounttt = 0;
					$TotMacPayAmounttt = 0;
					
		while (strtotime($f_date) <= strtotime($t_date)) {	
		//------------------------------------------------------------Received Amount---------------------------------------------------------------------------------	
			
			$sql = mysql_query("SELECT f.transfer_date, b.bank_name, f.transfer_amount, f.note FROM fund_transfer AS f
									LEFT JOIN bank AS b ON b.id = f.fund_send
									WHERE f.fund_received = '$bank' AND f.transfer_date = '$f_date'");
			$BalanceTransferRcv = 0;
			while ($row = mysql_fetch_array($sql)) {?>
				<tr class='gradeX'>
					<td style="font-size: 10px;"><?php echo 'Fund Received from '.$row['bank_name']; ?> at <?php echo $row['transfer_date']; ?></td>
					<td style="font-size: 10px;"><?php echo $row['transfer_amount']; ?></td>
					<td></td>
				</tr>
			<?php	$BalanceTransferRcv += $row['transfer_amount'];
			}
						
			$sql1 = mysql_query("SELECT l.loan_from, f.name, l.amount, l.loan_date, l.note FROM loan_receive AS l 
								LEFT JOIN loan_from AS f ON f.id = l.loan_from
								WHERE l.bank = '$bank' AND l.loan_date = '$f_date'");
			$TotLoanAmount = 0;
			while ($row1 = mysql_fetch_array($sql1)) {?>
				<tr class='gradeX'>
					<td style="font-size: 10px;"><?php echo 'Loan received from '.$row1['name']; ?> at <?php echo $row1['loan_date']; ?></td>
					<td style="font-size: 10px;text-align: center;"><?php echo $row1['amount']; ?></td>
					<td></td>
				</tr>
			<?php	$TotLoanAmount += $row1['amount'];
			}
			
			$sql1qqq = mysql_query("SELECT b.id, b.c_id, c.c_name, b.bill_type, b.amount, b.pay_date, b.bill_dsc, t.type, b.bank FROM bill_signup AS b
								LEFT JOIN bills_type AS t ON t.bill_type = b.bill_type
								LEFT JOIN clients AS c ON c.c_id = b.c_id
								WHERE b.bank = '$bank' AND b.pay_date = '$f_date'");
			$TotOtherAmount = 0;
			while ($row1qqq = mysql_fetch_array($sql1qqq)) {?>
				<tr class='gradeX'>
					<td style="font-size: 10px;"><?php echo 'Payment received from '.$row1qqq['c_name'].' ('.$row1qqq['c_id'].') for '.$row1qqq['type']; ?> at <?php echo $row1qqq['pay_date']; ?></td>
					<td style="font-size: 10px;text-align: center;"><?php echo $row1qqq['amount']; ?></td>
					<td></td>
				</tr>
			<?php	$TotOtherAmount += $row1qqq['amount'];
			}
			
			$sql1qqqeee = mysql_query("SELECT b.id, b.be_name, b.bill_type, b.amount, b.pay_date, b.bill_dsc, t.type, b.bank FROM bill_extra AS b
								LEFT JOIN bills_type AS t ON t.bill_type = b.bill_type
								WHERE b.bank = '$bank' AND b.pay_date = '$f_date'");
			$TotExtraAmount = 0;
			while ($row1qqqeee = mysql_fetch_array($sql1qqqeee)) {?>
				<tr class='gradeX'>
					<td style="font-size: 10px;"><?php echo 'Payment received from '.$row1qqqeee['be_name'].' for '.$row1qqqeee['type']; ?> at <?php echo $row1qqqeee['pay_date']; ?></td>
					<td style="font-size: 10px;text-align: center;"><?php echo $row1qqqeee['amount']; ?></td>
					<td></td>
				</tr>
			<?php	$TotExtraAmount += $row1qqqeee['amount'];
			}
			
			$sql1qqqmm = mysql_query("SELECT m.e_id, e.e_name, z.z_name, m.pay_date, m.pay_amount, m.discount, m.note FROM payment_macreseller AS m
									LEFT JOIN zone AS z ON z.z_id = m.z_id
									LEFT JOIN emp_info AS e ON e.e_id = m.e_id
									WHERE m.sts = '0' AND m.bank = '$bank' AND m.pay_date = '$f_date'");
			$TotMacPayAmount = 0;
			while ($row1qqqmm = mysql_fetch_array($sql1qqqmm)) {?>
				<tr class='gradeX'>
					<td style="font-size: 10px;"><?php echo 'Reseller payment received from '.$row1qqqmm['e_name'].' ('.$row1qqqmm['z_name'].')'; ?> at <?php echo $row1qqqmm['pay_date']; ?></td>
					<td style="font-size: 10px;text-align: center;"><?php echo $row1qqqmm['pay_amount']; ?></td>
					<td></td>
				</tr>
			<?php	$TotMacPayAmount += $row1qqqmm['pay_amount'];
			}
			
			$sql2 = mysql_query("SELECT p.c_id, c.c_name, p.pay_amount, p.pay_date, p.pay_desc FROM payment AS p LEFT JOIN clients AS c ON c.c_id = p.c_id WHERE p.bank = '$bank' AND p.pay_date = '$f_date'");
			$TotBillCollection = 0;
			while ($row2 = mysql_fetch_array($sql2)) {?>
				<tr class='gradeX'>
					<td style="font-size: 10px;"><?php echo 'Bill collection From '.$row2['c_name'].' ('.$row2['c_id'].')'; ?> at <?php echo $row2['pay_date']; ?></td>
					<td style="font-size: 10px;text-align: center;"><?php echo $row2['pay_amount']; ?></td>
					<td></td>
				</tr>
			<?php	$TotBillCollection += $row2['pay_amount'];
			}

	//------------------------------------------------------------Paid Amount---------------------------------------------------------------------------------
	
			$sqlCr = mysql_query("SELECT f.transfer_date, b.bank_name, f.transfer_amount, f.note FROM fund_transfer AS f
									LEFT JOIN bank AS b ON b.id = f.fund_received
									WHERE f.fund_send = '$bank' AND f.transfer_date = '$f_date'");
			$TottransferAmountCr = 0;
			while ($rowCr = mysql_fetch_array($sqlCr)) {?>
				<tr class='gradeX'>
					<td style="font-size: 10px;"><?php echo 'Fund send to '.$rowCr['bank_name']; ?> at <?php echo $rowCr['transfer_date']; ?></td>
					<td></td>
					<td style="font-size: 10px;text-align: center;"><?php echo $rowCr['transfer_amount']; ?></td>
				</tr>
			<?php	$TottransferAmountCr += $rowCr['transfer_amount'];
			}
			
			$sql1Cr = mysql_query("SELECT l.loan_payment_to, f.name, l.amount, l.payment_date, l.note FROM loan_payment AS l 
								LEFT JOIN loan_from AS f ON f.id = l.loan_payment_to
								WHERE l.bank = '$bank' AND l.payment_date = '$f_date'");
			$TotAmountCr = 0;
			while ($row1Cr = mysql_fetch_array($sql1Cr)) {?>
				<tr class='gradeX'>
					<td style="font-size: 10px;"><?php echo 'Loan paid to '.$row1Cr['name']; ?> at <?php echo $row1Cr['payment_date']; ?></td>
					<td></td>
					<td style="font-size: 10px;text-align: center;"><?php echo $row1Cr['amount']; ?></td>
				</tr>
			<?php	$TotAmountCr += $row1Cr['amount'];
			}
			
			$sql2Cr = mysql_query("SELECT e.ex_date, t.ex_type, e.amount, e.note FROM expanse AS e
									LEFT JOIN expanse_type AS t ON t.id = e.type
									WHERE e.bank = '$bank' AND e.status = '2' AND e.ex_date = '$f_date'");
			$TotExAmountCr = 0;
			while ($row2Cr = mysql_fetch_array($sql2Cr)) {?>
				<tr class='gradeX'>
					<td style="font-size: 10px;"><?php echo 'Paid to '.$row2Cr['ex_type']; ?> at <?php echo $row2Cr['ex_date']; ?></td>
					<td></td>
					<td style="font-size: 10px;text-align: center;"><?php echo $row2Cr['amount']; ?></td>
				</tr>
			<?php	$TotExAmountCr += $row2Cr['amount'];
			}
			
			$sql3Cr = mysql_query("SELECT p.payment_date, p.amount, p.note, e.e_name FROM emp_salary_payment AS p 
									LEFT JOIN emp_info AS e ON p.payment_to = e.e_id 
									WHERE p.bank = '$bank' AND p.payment_date = '$f_date'");
			$TotSalaryCr = 0;
			while ($row3Cr = mysql_fetch_array($sql3Cr)) {?>
				<tr class='gradeX'>
					<td style="font-size: 10px;"><?php echo 'Paid to employee salary ('.$row3Cr['e_name'].')'; ?> at <?php echo $row3Cr['payment_date']; ?></td>
					<td></td>
					<td style="font-size: 10px;text-align: center;"><?php echo $row3Cr['amount']; ?></td>
				</tr>
			<?php	$TotSalaryCr += $row3Cr['amount'];
				
			}
			
			$sql4Cr = mysql_query("SELECT p.id AS vp_id, p.payment_date, p.v_id, v.v_name, v.cell, v.location, p.note, p.bank, b.bank_name, p.amount, e.e_name, p.sts FROM vendor_payment AS p
											LEFT JOIN vendor AS v ON v.id = p.v_id
											LEFT JOIN bank AS b ON b.id = p.bank
											LEFT JOIN emp_info AS e ON e.e_id = p.ent_by
											WHERE p.bank = '$bank' AND p.sts = '0' AND p.payment_date = '$f_date'");
			$TotVendrCr = 0;
			while ($row4Cr = mysql_fetch_array($sql4Cr)) {?>
				<tr class='gradeX'>
					<td style="font-size: 10px;"><?php echo 'Paid to Vendor ('.$row4Cr['v_name'].'-'.$row4Cr['location'].')'; ?> at <?php echo $row4Cr['payment_date']; ?></td>
					<td></td>
					<td style="font-size: 10px;text-align: center;"><?php echo $row4Cr['amount']; ?></td>
				</tr>
			<?php	$TotVendrCr += $row4Cr['amount'];
				
			}

			$f_date = date ("Y-m-d", strtotime("+1 days", strtotime($f_date)));
			$x++;
			$TotBalanceTransferRcv += $BalanceTransferRcv;
			$TotalLoanAmount += $TotLoanAmount;
			$TotOtherAmounttt += $TotOtherAmount;
			$TotExtraAmounttt += $TotExtraAmount;
			$TotMacPayAmounttt += $TotMacPayAmount;
			$TotalBillCollection += $TotBillCollection;

			$TotaltransferAmountCr += $TottransferAmountCr;
			$TotalAmountCr += $TotAmountCr;
			$TotalExAmountCr += $TotExAmountCr;
			$TotalSalaryCr += $TotSalaryCr;
			$TotalVendCr += $TotVendrCr;
		}	

			$ReciveTotal = $TotBalanceTransferRcv + $TotalLoanAmount + $TotOtherAmounttt + $TotExtraAmounttt + $TotMacPayAmounttt + $TotalBillCollection;
			$PaidTotal = $TotaltransferAmountCr + $TotalAmountCr + $TotalExAmountCr + $TotalSalaryCr + $TotalVendCr;?>
				<tr class='gradeX'>
					<td style="font-size: 10px;text-align: right;"><b>OPEANING BALANCE<br>TOTAL RECEIVED<br>TOTAL PAID<br> ------------------- <br>BALANCE</b></td>
					<td style="font-size: 10px;text-align: center;" colspan="2"><b><?php echo number_format($openingBalance,2); ?><br><?php echo number_format($ReciveTotal,2); ?><br><?php echo number_format($PaidTotal,2); ?><br>------------------<br><?php echo number_format((($ReciveTotal + $openingBalance) - $PaidTotal),2); ?></b></td>
				</tr>
					</tbody>
						</table><br>
							<table class="table table-bordered table-invoice">
								<tr>
									<td class="width30" style="font-size: 10px;text-align: right;"><b>Bill Collection</b></td>
									<td class="width70" style="font-size: 10px;"><?php echo number_format($TotalBillCollection,2); ?></td>
								</tr>
								<tr>
									<td style="font-size: 10px;text-align: right;"><b>Balance Received</b></td>
									<td style="font-size: 10px;"><?php echo number_format($TotBalanceTransferRcv,2); ?></td>
								</tr>
								<tr>
									<td style="font-size: 10px;text-align: right;"><b>Reseller Payment</b></td>
									<td style="font-size: 10px;"><?php echo number_format($TotMacPayAmounttt,2); ?></td>
								</tr>
								<tr>
									<td style="font-size: 10px;text-align: right;"><b>Others Income</b></td>
									<td style="font-size: 10px;"><?php echo number_format($TotOtherAmounttt,2); ?></td>
								</tr>
								<tr>
									<td style="font-size: 10px;text-align: right;"><b>Outside Income</b></td>
									<td style="font-size: 10px;"><?php echo number_format($TotExtraAmounttt,2); ?></td>
								</tr>
								<tr>
									<td style="font-size: 10px;text-align: right;"><b>Loan Received</b></td>
									<td style="font-size: 10px;"><?php echo number_format($TotalLoanAmount,2); ?></td>
								</tr>
								<tr>
									<td style="text-align: right;font-size: 11px;"><b>Total Received</b></td>
									<td style="font-size: 11px;"><b><?php echo number_format($ReciveTotal,2); ?> ৳</b></td>
								</tr>
							</table>
							<table class="table table-bordered table-invoice">
								<tr>
									<td class="width30" style="font-size: 10px;text-align: right;"><b>Transfer Amount</b></td>
									<td class="width70" style="font-size: 10px;"><?php echo number_format($TotaltransferAmountCr,2); ?></td>
								</tr>
								<tr>
									<td style="font-size: 10px;text-align: right;"><b>Loan Paid</b></td>
									<td style="font-size: 10px;"><?php echo number_format($TotalAmountCr,2); ?></td>
								</tr>
								<tr>
									<td style="font-size: 10px;text-align: right;"><b>Expance</b></td>
									<td style="font-size: 10px;"><?php echo number_format($TotalExAmountCr,2); ?></td>
								</tr>
								<tr>
									<td style="font-size: 10px;text-align: right;"><b>Salary Paid</b></td>
									<td style="font-size: 10px;"><?php echo number_format($TotalSalaryCr,2); ?></td>
								</tr>
								<tr>
									<td style="font-size: 10px;text-align: right;"><b>Vendor Paid</b></td>
									<td style="font-size: 10px;"><?php echo number_format($TotalVendCr,2); ?></td>
								</tr>
								<tr>
									<td style="font-size: 10px;text-align: right;"><b>Others Paid</b></td>
									<td style="font-size: 10px;">0.00</td>
								</tr>
								<tr>
									<td style="text-align: right;font-size: 11px;"><b>Total Paid</b></td>
									<td style="font-size: 11px;"><b><?php echo number_format($PaidTotal,2); ?> ৳</b></td>
								</tr>
							</table>
					<?php } if($bank ==''){ ?>
						<table id="dyntable" class="table table-bordered responsive">
							<colgroup>
								<col class="con0" />
								<col class="con1" />
								<col class="con0" />
							</colgroup>
							<thead>
								<tr  class="newThead">
									<th class="head1" style="font-size: 10px;padding: 5px;text-align: center;">Bank</th>
									<th class="head0" style="font-size: 10px;padding: 5px;text-align: center;">Sum</th>
									<th class="head1" style="font-size: 10px;padding: 5px;text-align: center;">Action</th>
								</tr>
							</thead>
							<tbody>
				<?php $sqlBank = mysql_query("SELECT b.id, b.bank_name, b.sort_name, b.show_exp, b.emp_id, e.e_name, d.dept_name FROM bank AS b
										LEFT JOIN emp_info AS e	ON e.e_id = b.emp_id
										LEFT JOIN department_info AS d ON d.dept_id = e.dept_id WHERE b.sts = 0 ORDER BY b.bank_name");
				$x = 1;
				$TotBalance = 0;
				$ReceivedAmountt = 0;
				$PaymentAmountt = 0;
				while ($rowBank = mysql_fetch_array($sqlBank)) {
					$bankId = $rowBank['id'];
					$bankName = $rowBank['bank_name'];
					$empid = $rowBank['emp_id'];
					$ename = $rowBank['e_name'];
					$deptname = $rowBank['dept_name'];
					
					$sqlOp = mysql_query("SELECT * FROM 
										(
										SELECT id, bank_name FROM bank WHERE id = '$bankId'
										)a
										LEFT JOIN
										(
										SELECT fund_received, SUM(transfer_amount) AS transfer_amount FROM fund_transfer WHERE fund_received = '$bankId' AND transfer_date < '$f_date'
										)b ON a.id = b.fund_received
										LEFT JOIN
										(
										SELECT bank, SUM(amount) AS amount FROM loan_receive WHERE bank = '$bankId' AND loan_date < '$f_date'
										)c ON a.id = c.bank
										LEFT JOIN
										(
										SELECT bank, SUM(amount) AS otheramounts FROM bill_signup WHERE bank = '$bankId' AND pay_date < '$f_date'
										)e ON a.id = e.bank
										LEFT JOIN
										(
										SELECT bank, SUM(amount) AS extraamount FROM bill_extra WHERE bank = '$bankId' AND pay_date < '$f_date'
										)h ON a.id = h.bank
										LEFT JOIN
										(
										SELECT bank, SUM(pay_amount) AS paymentmacreseller FROM payment_macreseller WHERE sts = '0' AND bank = '$bankId' AND pay_date < '$f_date'
										)f ON a.id = f.bank
										LEFT JOIN
										(
										SELECT bank, SUM(pay_amount) AS collection FROM payment WHERE bank = '$bankId' AND pay_date < '$f_date'
										)d ON a.id = d.bank");
					$rowOp = mysql_fetch_array($sqlOp);		
					$ReceivedAmountOp = $rowOp['transfer_amount'] + $rowOp['amount'] + $rowOp['otheramounts'] + $rowOp['extraamount'] + $rowOp['collection'] + $rowOp['paymentmacreseller'];
					
					$sqlOp1 = mysql_query("SELECT * FROM 
										(
										SELECT id, bank_name FROM bank WHERE id = '$bankId'
										)a
										LEFT JOIN
										(
										SELECT fund_send, SUM(transfer_amount) AS transferAmount FROM fund_transfer WHERE fund_send = '$bankId' AND transfer_date < '$f_date'
										)b ON a.id = b.fund_send
										LEFT JOIN
										(
										SELECT bank, SUM(amount) AS Amounts FROM loan_payment WHERE bank = '$bankId' AND payment_date < '$f_date'
										)c ON a.id = c.bank
										LEFT JOIN
										(
										SELECT bank, SUM(amount) AS payAmount FROM expanse WHERE bank = '$bankId' AND status = '2' AND ex_date < '$f_date'
										)d ON a.id = d.bank
										LEFT JOIN
										(
										SELECT bank, SUM(amount) AS paySalary FROM emp_salary_payment WHERE bank = '$bankId' AND payment_date < '$f_date'
										)f ON a.id = f.bank
										LEFT JOIN
										(
										SELECT bank, SUM(amount) AS venPayemnt FROM vendor_payment WHERE bank = '$bankId' AND sts = '0' AND payment_date < '$f_date'
										)g ON a.id = g.bank");
					$rowOp1 = mysql_fetch_array($sqlOp1);		
					$PaymentAmountOp = $rowOp1['transferAmount'] + $rowOp1['Amounts'] + $rowOp1['payAmount'] + $rowOp1['paySalary'] + $rowOp1['venPayemnt'];
					$OpeningAmount = $ReceivedAmountOp - $PaymentAmountOp;
					
					$sql = mysql_query("SELECT * FROM 
										(
										SELECT id, bank_name FROM bank WHERE id = '$bankId'
										)a
										LEFT JOIN
										(
										SELECT fund_received, SUM(transfer_amount) AS transfer_amount FROM fund_transfer WHERE fund_received = '$bankId' AND transfer_date BETWEEN '$f_date' AND '$t_date'
										)b ON a.id = b.fund_received
										LEFT JOIN
										(
										SELECT bank, SUM(amount) AS amount FROM loan_receive WHERE bank = '$bankId' AND loan_date BETWEEN '$f_date' AND '$t_date'
										)c ON a.id = c.bank
										LEFT JOIN
										(
										SELECT bank, SUM(amount) AS otheramount FROM bill_signup WHERE bank = '$bankId' AND pay_date BETWEEN '$f_date' AND '$t_date'
										)e ON a.id = e.bank
										LEFT JOIN
										(
										SELECT bank, SUM(amount) AS extraamount FROM bill_extra WHERE bank = '$bankId' AND pay_date BETWEEN '$f_date' AND '$t_date'
										)h ON a.id = h.bank
										LEFT JOIN
										(
										SELECT bank, SUM(pay_amount) AS paymentmacreseller FROM payment_macreseller WHERE sts = '0' AND bank = '$bankId' AND pay_date BETWEEN '$f_date' AND '$t_date'
										)f ON a.id = f.bank
										LEFT JOIN
										(
										SELECT bank, SUM(pay_amount) AS collection FROM payment WHERE bank = '$bankId' AND pay_date BETWEEN '$f_date' AND '$t_date'
										)d ON a.id = d.bank");
					$row = mysql_fetch_array($sql);		
					$ReceivedAmount = $row['transfer_amount'] + $row['amount'] + $row['otheramount'] + $row['extraamount'] + $row['collection'] + $row['paymentmacreseller'];
					
					$sql1 = mysql_query("SELECT * FROM 
										(
										SELECT id, bank_name FROM bank WHERE id = '$bankId'
										)a
										LEFT JOIN
										(
										SELECT fund_send, SUM(transfer_amount) AS transferAmount FROM fund_transfer WHERE fund_send = '$bankId' AND transfer_date BETWEEN '$f_date' AND '$t_date'
										)b ON a.id = b.fund_send
										LEFT JOIN
										(
										SELECT bank, SUM(amount) AS Amounts FROM loan_payment WHERE bank = '$bankId' AND payment_date BETWEEN '$f_date' AND '$t_date'
										)c ON a.id = c.bank
										LEFT JOIN
										(
										SELECT bank, SUM(amount) AS payAmount FROM expanse WHERE bank = '$bankId' AND status = '2' AND ex_date BETWEEN '$f_date' AND '$t_date'
										)d ON a.id = d.bank
										LEFT JOIN
										(
										SELECT bank, SUM(amount) AS paySalary FROM emp_salary_payment WHERE bank = '$bankId' AND payment_date BETWEEN '$f_date' AND '$t_date'
										)f ON a.id = f.bank
										LEFT JOIN
										(
										SELECT bank, SUM(amount) AS venPayemnt FROM vendor_payment WHERE bank = '$bankId' AND sts = '0' AND payment_date BETWEEN '$f_date' AND '$t_date'
										)g ON a.id = g.bank");
					$row1 = mysql_fetch_array($sql1);
					$PaymentAmount = $row1['transferAmount'] + $row1['Amounts'] + $row1['payAmount'] + $row1['paySalary'] + $row1['venPayemnt'];
					$Balance = (($ReceivedAmount + $OpeningAmount) - $PaymentAmount);

					echo
												"<tr class='gradeX'>
													<td style='font-size: 10px;' class='center'>{$bankName}<br>{$ename}<br>{$empid}<br>{$deptname}</td>
													<td style='font-size: 10px;' class='center'>OB:{$OpeningAmount}<br>RA:{$ReceivedAmount}<br>PA:{$PaymentAmount}<br>------------------<br><b>BA:{$Balance}</b></td>
													<td style='font-size: 10px;' class='center'>
														<ul class='tooltipsample'>
															<li><form action='ReportCashInHand' method='post' style='padding-top: 25px;'><input type='hidden' name='bank' value='{$bankId}' /><button class='btn col1' style='padding: 5px 7px 5px 7px;font-size: 12px;font-weight: bold;border: 0px solid #bbb;' onClick='submit();'><i class='fa iconfa-eye-open'></i></button></form></li>
															<li><a data-placement='top' data-rel='tooltip' style='padding: 5px 7px 5px 7px;font-size: 12px;font-weight: bold;border: 0px solid #bbb;' href='#?id=",$id,"{$bankId}' data-original-title='Print' class='btn col5'><i class='iconfa-print'></i></a></li>
														</ul>
													</td>
												</tr>\n";
				
					$x++;
					$ReceivedAmountt += $ReceivedAmount;
					$PaymentAmountt += $PaymentAmount;
					$TotBalance += $Balance;
					
				}?>
												<td style="text-align: right;font-size: 10px;"><b>TOTAL RECEIVED<br>TOTAL PAID<br> ------------------- <br>TOTAL BALANCE</b></td>
												<td style="font-size: 10px;text-align: center;" colspan="2"><b><?php echo $ReceivedAmountt; ?><br><?php echo $PaymentAmountt; ?><br> ------------- <br><?php echo $TotBalance; ?></b></td>
							</tbody>
						</table>
					<?php } ?>
			</div>
	</div>

<!-- -------------------------------------------------------------Entry Data View------------------------------------------------------------ -->			
				
<?php
}
else{
	include('include/index');
}
include('include/footer.php');
?>