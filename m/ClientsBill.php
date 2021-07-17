<?php
$titel = "My Bill";
$ClientsBill = 'active';
include('include/hader.php');
$id = $_SESSION['SESS_EMP_ID'];
//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'ClientsBill' AND $user_type = '1'");
if(mysql_num_rows($access) > 0){
//---------- Permission -----------

$sql = mysql_query("SELECT b.bill_date, p.p_name, p.p_price, b.bill_amount, b.discount
					FROM billing AS b
					LEFT JOIN package AS p ON p.p_id = b.p_id
					LEFT JOIN clients AS c ON c.c_id = b.c_id
					WHERE b.c_id = '$id' ORDER BY b.bill_date");
$result = mysql_query("SELECT c.c_id, c.c_name, c.cell, c.address, p.p_name, p.p_price FROM clients AS c
						LEFT JOIN package AS p
						ON p.p_id = c.p_id WHERE c_id = '$id'");
$row = mysql_fetch_array($result);	

$sql1 = mysql_query("SELECT pay_date, pay_amount, bill_discount FROM payment WHERE c_id = '$id' ORDER BY pay_date");

$sqlcl = mysql_query("SELECT a.bill_date AS date, a.p_name, a.p_price, a.p_discount, a.bill_amount, a.discount, a.payment, a.moneyreceiptno, a.pay_mode, a.trxid FROM
					(SELECT b.c_id, b.bill_date, p.p_name, p.p_price, b.bill_amount, b.discount AS P_Discount, '' AS Discount, '' AS Payment, '' AS moneyreceiptno, '' AS trxid, '' AS pay_mode
										FROM billing AS b
										LEFT JOIN package AS p ON p.p_id = b.p_id
										LEFT JOIN clients AS c ON c.c_id = b.c_id
										WHERE b.bill_amount != '0' AND b.c_id = '$e_id'
						UNION
							SELECT c_id, pay_date,'', '', '', '', SUM(bill_discount) AS bill_discount, SUM(pay_amount) AS pay_amount, moneyreceiptno, trxid, pay_mode FROM payment
							WHERE c_id = '$e_id' GROUP BY pay_date
					) AS a
					ORDER BY a.bill_date");
					
$sql2 = mysql_query("SELECT c.c_id, (b.bill-IFNULL(p.paid,0)) AS due FROM clients AS c
						LEFT JOIN
						(SELECT c_id, SUM(bill_amount) AS bill FROM billing GROUP BY c_id)b
						ON b.c_id = c.c_id
						LEFT JOIN
						(SELECT c_id, (SUM(pay_amount) + SUM(bill_discount)) AS paid FROM payment GROUP BY c_id)p
						ON p.c_id = c.c_id

						WHERE c.c_id = '$e_id'");
$rows = mysql_fetch_array($sql2);
$Dew = $rows['due'];

if($Dew > 0){
	$color = 'style="color:red;text-align: left;font-size: 12px;"';					
} else{
	$color = 'style="color:#000;text-align: left;font-size: 12px;"';
}

	?>

	<div class="box box-primary">
			<div class="box-body" style="padding: 0px 10px 0 10px;">
			<div class="pageheader" style="padding: 0px 10px 0 10px;">
        <div class="pagetitle">
		<div class="navbar">
            <div class="navbar-inner">
				<div class="deadlol">[<b>Client ID :</b><?php echo $row['c_id']; ?>]</div>
				<div class="deadlol">[<b>Name :</b><?php echo $row['c_name']; ?>]</div>
				<div class="deadlol">[<b>Cell :</b><?php echo $row['cell']; ?>]</div>
				<div class="deadlol">[<b>Package :</b><?php echo $row['p_name']; ?>]</div>
				<div class="deadlol">[<b>Rate :</b><?php echo $row['p_price']; ?>]</div>
				<div class="deadlol" <?php echo $color; ?>>[<b>Total Due :</b><?php echo number_format($Dew,2); ?>]</div>	    
			</div><!--navbar-inner-->
        </div><!--navbar--><br/>
		<?php if($Dew > '9'){if(in_array(1, $online_getway)){?>
							<a href="PaymentOnline?gateway=bKash"><img src="<?php echo $weblink;?>imgs/bk_rbttn.png" title="bKash" style="width: 40px;padding: 0px;margin-top: -3px;"></a>
						<?php } if(in_array(4, $online_getway)){?>
							<a href="PaymentOnline?gateway=Rocket"><img src="<?php echo $weblink;?>imgs/rocket_s.png" title="Rocket" style="width: 40px;padding: 0px;margin-top: -3px;border-radius: 10px;"></a>
						<?php } if(in_array(5, $online_getway)){?>
							<a href="PaymentOnline?gateway=Nagad"><img src="<?php echo $weblink;?>imgs/nagad_s.png" title="Nagad" style="width: 40px;padding: 0px;margin-top: -3px;border-radius: 10px;"></a>
						<?php } if(in_array(2, $online_getway)){?>
							<a href="PaymentOnline?gateway=iPay"><img src="<?php echo $weblink;?>imgs/ip_rbttn.png" title="iPay" style="width: 40px;padding: 0px;margin-top: -3px;"></a>
						<?php } if(in_array(3, $online_getway)){?>
							<a href="PaymentOnline?gateway=SSLCommerz"><img src="<?php echo $weblink;?>imgs/ssl.png" title="SSLCommerz" style="width: 40px;padding: 0px;margin-top: -3px;border-radius: 10px;"></a>
						<?php }} ?>
							<br/>
							<br/>
			<div class="row">
						<div style="width: 100%;">
						<div <?php echo $color; ?>><b>Total Due: &nbsp; &nbsp; <?php echo number_format($Dew,2).'tk'; ?></b></div>
					<table class="table table-bordered table-invoice" style="width: 100%;font-weight: bold;">
                    <colgroup>
						<col class="con1" />
                        <col class="con0" />
                        <col class="con1" />
						<col class="con0" />
                        <col class="con1" />
						<col class="con0" />
                    </colgroup>
                    <thead>
                        <tr>
							<th class="head1" style="font-size: 10px;padding: 4px;">Date</th>
							<th class="head1" style="font-size: 10px;padding: 4px;">Bill</th>
							<th class="head0" style="font-size: 10px;padding: 4px;">Discount</th>
							<th class="head1" style="font-size: 10px;padding: 4px;">Paid</th>
							<th class="head0" style="font-size: 10px;padding: 4px;">Method</th>
							<th class="head1" style="font-size: 10px;padding: 4px;">MR/Trx</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php		
								while( $rown = mysql_fetch_assoc($sqlcl) )
								{
									
									
									$yrdata= strtotime($rown['date']);
									$month = date('d F, Y', $yrdata);
									echo
										"<tr class='gradeX'>
											<td style='font-size: 9px;padding: 4px;'>{$month}</td>
											<td style='font-size: 9px;padding: 4px;'>{$rown['bill_amount']}</td>
											<td style='font-size: 9px;padding: 4px;'>{$rown['discount']}</td>
											<td style='font-size: 9px;padding: 4px;'>{$rown['payment']}</td>
											<td style='font-size: 9px;padding: 4px;'>{$rown['pay_mode']}</td>
											<td style='font-size: 9px;padding: 4px;'>{$rown['moneyreceiptno']}{$rown['trxid']}</td>
										</tr>\n ";
								}  
							?>
					</tbody>
				</table>
						</div><!--col-md-6-->
					</div>
		</div>
	</div>
			</div>
	</div>
<?php
}
else{
	include('include/index');
}
include('include/footer.php');
?>
