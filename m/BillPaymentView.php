<?php
$titel = "Billing";
$Billing = 'active';
include('include/hader.php');
$id = $_GET['id'];
extract($_POST); 
//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'Billing' AND $user_type = '1'");
$access1 = mysql_query("SELECT * FROM module_page WHERE parent_id = '11' AND $user_type = '1'");
if(mysql_num_rows($access) > 0 && mysql_num_rows($access1) > 0){
//---------- Permission -----------

$resultgg = mysql_query("SELECT c_id, c_name, cell, address, con_sts, b_date, payment_deadline, breseller, mac_user FROM clients WHERE c_id = '$id'");
$rowgg = mysql_fetch_array($resultgg);	

if($rowgg['mac_user'] == '0'){
$sql = mysql_query("SELECT a.bill_date AS date, a.p_name, a.p_price, a.p_discount, a.bill_amount, a.discount, a.payment, a.note, a.pay_idd, a.moneyreceiptno, a.c_id FROM
					(SELECT b.c_id, b.bill_date, p.p_name, p.p_price, b.bill_amount, b.discount AS P_Discount, '' AS Discount, '' AS Payment, '' AS note, '#' AS pay_idd, '' AS moneyreceiptno, bill_date_time AS pay_date_time
										FROM billing AS b
										LEFT JOIN package AS p ON p.p_id = b.p_id
										LEFT JOIN clients AS c ON c.c_id = b.c_id
										WHERE b.bill_amount != '0' AND b.c_id = '$id'
						UNION
							SELECT c_id, pay_date,'', '', '', '', SUM(bill_discount) AS bill_discount, SUM(pay_amount) AS pay_amount, pay_desc AS note, id AS pay_idd, moneyreceiptno, pay_date_time FROM payment
							WHERE c_id = '$id' GROUP BY pay_date_time 
					) AS a
					ORDER BY a.pay_date_time");

$result = mysql_query("SELECT c_id, c_name, cell, address FROM clients WHERE c_id = '$id'");
$row = mysql_fetch_array($result);	

$sql1 = mysql_query("SELECT pay_date, pay_amount, bill_discount FROM payment WHERE c_id = '$id' ORDER BY pay_date");

$sql2 = mysql_query("SELECT c.c_id, (b.bill-IFNULL(p.paid,0)) AS due FROM clients AS c
						LEFT JOIN
						(SELECT c_id, SUM(bill_amount) AS bill FROM billing GROUP BY c_id)b
						ON b.c_id = c.c_id
						LEFT JOIN
						(SELECT c_id, (SUM(pay_amount) + SUM(bill_discount)) AS paid FROM payment GROUP BY c_id)p
						ON p.c_id = c.c_id

						WHERE c.c_id = '$id'");
}
else{
	$sql = mysql_query("SELECT a.bill_date AS date, a.p_name, a.p_price, a.p_discount, a.bill_amount, a.discount, a.payment, a.note, a.pay_idd, a.moneyreceiptno, a.c_id FROM
					(SELECT b.c_id, b.bill_date, p.p_name, p.p_price, b.bill_amount, b.discount AS P_Discount, '' AS Discount, '' AS Payment, '' AS note, '#' AS pay_idd, '' AS moneyreceiptno, bill_date_time AS pay_date_time
										FROM billing_mac_client AS b
										LEFT JOIN package AS p ON p.p_id = b.p_id
										LEFT JOIN clients AS c ON c.c_id = b.c_id
										WHERE b.bill_amount != '0' AND b.c_id = '$id'
						UNION
							SELECT c_id, pay_date,'', '', '', '', SUM(bill_discount) AS bill_discount, SUM(pay_amount) AS pay_amount, pay_desc AS note, id AS pay_idd, moneyreceiptno, pay_date_time FROM payment_mac_client
							WHERE c_id = '$id' GROUP BY pay_date_time 
					) AS a
					ORDER BY a.pay_date_time");

$result = mysql_query("SELECT c_id, c_name, cell, address FROM clients WHERE c_id = '$id'");
$row = mysql_fetch_array($result);	

$sql1 = mysql_query("SELECT pay_date, pay_amount, bill_discount FROM payment_mac_client WHERE c_id = '$id' ORDER BY pay_date");

$sql2 = mysql_query("SELECT c.c_id, (b.bill-IFNULL(p.paid,0)) AS due FROM clients AS c
						LEFT JOIN
						(SELECT c_id, SUM(bill_amount) AS bill FROM billing_mac_client GROUP BY c_id)b
						ON b.c_id = c.c_id
						LEFT JOIN
						(SELECT c_id, (SUM(pay_amount) + SUM(bill_discount)) AS paid FROM payment_mac_client GROUP BY c_id)p
						ON p.c_id = c.c_id

						WHERE c.c_id = '$id'");
}
$rows = mysql_fetch_array($sql2);
$Dew = $rows['due'];
			
if($Dew > 0){
	$color = 'style="color:red;"';					
} else{
	$color = 'style="color:#000;"';
}
	?>

	<div class="box box-primary">
		<div class="box-header">
				<center><form action='PaymentAdd' method='post'><input type='hidden' name='id' value='<?php echo $id;?>'/><button class='btn col1' style='padding: 5px 30% 5px 30%;font-size: 15px;font-weight: bold;'>Make Payment</button></form></center><br/>
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
				<table class="table table-bordered responsive" style="width: 100% !important;float: left;">
                    <colgroup>
                        <col class="con0" />
                        <col class="con1" />
						<col class="con0" />
                        <col class="con1" />
						<col class="con0" />
                    </colgroup>
                    <thead>
                        <tr  class="newThead">
							<th class="head0" style="font-size: 9px;">Date</th>
							<th class="head1" style="font-size: 9px;">Pkg/Rate</th>
							<th class="head0" style="font-size: 9px;">Bill</th>
							<th class="head1" style="font-size: 9px;">Dis</th>
							<th class="head0" style="font-size: 9px;">Paid</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php		
								while( $rown = mysql_fetch_assoc($sql) )
								{
									
									
									$yrdata= strtotime($rown['date']);
									$month = date('d F, Y', $yrdata);
									echo
										"<tr class='gradeX'>
											<td style='font-size: 9px;font-weight: bold;'>{$month}</td>
											<td style='font-size: 9px;font-weight: bold;'>{$rown['p_name']}<br>{$rown['p_price']}</td>
											<td style='font-size: 9px;font-weight: bold;'>{$rown['bill_amount']}</td>
											<td style='font-size: 9px;font-weight: bold;'>{$rown['discount']}</td>
											<td style='font-size: 9px;font-weight: bold;'>{$rown['payment']}</td>
										</tr>\n ";
								}  
							?>
					</tbody>
				</table><br/>
				<center><form action='PaymentAdd' method='post'><input type='hidden' name='id' value='<?php echo $id;?>'/><button class='btn col1' style='padding: 5px 30% 5px 30%;font-size: 15px;font-weight: bold;'>Make Payment</button></form></center><br/>
		</div>
	</div>
<?php
}
else{
	include('include/index');
}
include('include/footer.php');
?>
