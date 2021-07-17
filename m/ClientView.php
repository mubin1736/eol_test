<?php
$titel = "Client Profile";
$Clients = 'active';
include('include/hader.php');
include("mk_api.php");
$ids = $_GET['id'];
extract($_POST); 

//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'Clients' AND $user_type = '1'");
$access1 = mysql_query("SELECT * FROM module_page WHERE parent_id = '8' AND $user_type = '1'");
if(mysql_num_rows($access) > 0 && mysql_num_rows($access1) > 0 && in_array(104, $access_arry)){
//---------- Permission -----------

$result = mysql_query("SELECT c.c_id, c.c_name, c.mk_id, l.log_sts, c.cell, c.address, c.join_date, c.con_type, c.con_sts, c.discount, p.p_name, p.p_price,l.pw, c.mac_user, c.termination_date FROM clients AS c
						LEFT JOIN package AS p
						ON p.p_id = c.p_id 
						LEFT JOIN login AS l 
						ON l.e_id = c.c_id 
						WHERE c_id = '$ids'");
$row = mysql_fetch_array($result);	

$mk_id = $row['mk_id'];

if($row['mac_user'] == '0'){
$sqlggg = mysql_query("SELECT a.bill_date AS date, a.p_name, a.p_price, a.p_discount, a.bill_amount, a.discount, a.payment, a.moneyreceiptno, a.pay_mode FROM
					(SELECT b.c_id, b.bill_date, p.p_name, p.p_price, b.bill_amount, b.discount AS p_discount, '' AS Discount, '' AS Payment, '' AS moneyreceiptno, '' AS pay_mode
										FROM billing AS b
										LEFT JOIN package AS p ON p.p_id = b.p_id
										LEFT JOIN clients AS c ON c.c_id = b.c_id
										WHERE b.bill_amount != '0' AND b.c_id = '$ids'
						UNION
							SELECT c_id, pay_date,'', '', '', '', SUM(bill_discount) AS bill_discount, SUM(pay_amount) AS pay_amount, moneyreceiptno, pay_mode FROM payment
							WHERE c_id = '$ids' GROUP BY pay_date
					) AS a
					ORDER BY a.bill_date");

$sql2 = mysql_query("SELECT c.c_id, (b.bill-IFNULL(p.paid,0)) AS due FROM clients AS c
						LEFT JOIN
						(SELECT c_id, SUM(bill_amount) AS bill FROM billing GROUP BY c_id)b
						ON b.c_id = c.c_id
						LEFT JOIN
						(SELECT c_id, (SUM(pay_amount) + SUM(bill_discount)) AS paid FROM payment GROUP BY c_id)p
						ON p.c_id = c.c_id

						WHERE c.c_id = '$ids'");
}
else{
$sqlggg = mysql_query("SELECT a.bill_date AS date, a.p_name, a.p_price, a.p_discount, a.bill_amount, a.discount, a.payment, a.moneyreceiptno, a.pay_mode FROM
					(SELECT b.c_id, b.bill_date, p.p_name, p.p_price_reseller AS p_price, b.bill_amount, b.discount AS p_discount, '' AS Discount, '' AS Payment, '' AS moneyreceiptno, '' AS pay_mode
										FROM billing_mac_client AS b
										LEFT JOIN package AS p ON p.p_id = b.p_id
										LEFT JOIN clients AS c ON c.c_id = b.c_id
										WHERE b.bill_amount != '0' AND b.c_id = '$ids'
						UNION
							SELECT c_id, pay_date,'', '', '', '', SUM(bill_discount) AS bill_discount, SUM(pay_amount) AS pay_amount, moneyreceiptno, pay_mode FROM payment_mac_client
							WHERE c_id = '$ids' GROUP BY pay_date
					) AS a
					ORDER BY a.bill_date");

$sql2 = mysql_query("SELECT c.c_id, (b.bill-IFNULL(p.paid,0)) AS due FROM clients AS c
						LEFT JOIN
						(SELECT c_id, SUM(bill_amount) AS bill FROM billing_mac_client GROUP BY c_id)b
						ON b.c_id = c.c_id
						LEFT JOIN
						(SELECT c_id, (SUM(pay_amount) + SUM(bill_discount)) AS paid FROM payment_mac_client GROUP BY c_id)p
						ON p.c_id = c.c_id

						WHERE c.c_id = '$ids'");
}
$sql360 = mysql_query("SELECT s.c_id, s.bank, b.type, s.amount, s.pay_date, s.bill_dsc, e.e_name FROM bill_signup AS s
					LEFT JOIN bills_type AS b ON b.bill_type = s.bill_type
					LEFT JOIN emp_info AS e ON e.e_id = s.ent_by
					WHERE c_id = '$ids' ORDER BY s.pay_date DESC");
					
$sql36 = mysql_query("SELECT p.id, p.c_id, a.p_name AS old_package, a.p_price AS old_price, q.p_name AS nw_package, q.p_price AS nw_price, DATE_FORMAT(p.up_date, '%D %M %Y') AS up_date FROM package_change AS p
					LEFT JOIN package AS a
					ON a.p_id = p.c_package
					LEFT JOIN package AS q
					ON q.p_id = p.new_package
					WHERE c_id = '$ids' ORDER BY p.id DESC");

$sql35 = mysql_query("SELECT m.id, m.ticket_no, m.c_id, d.dept_name, m.sub, m.massage, DATE_FORMAT(m.entry_date_time, '%D %M %Y %h:%i%p') AS entry_date_time, m.ticket_sts, DATE_FORMAT(m.close_date_time, '%D %M %Y %h:%i%p') AS close_date_time, e.e_name, m.sts FROM complain_master AS m 
					LEFT JOIN department_info AS d
					ON d.dept_id = m.dept_id
					LEFT JOIN emp_info AS e
					ON e.e_id = m.close_by

					WHERE c_id = '$ids' ORDER BY m.ticket_no DESC");

$sql34 = mysql_query("SELECT s.id, s.c_id, c.c_name, s.con_sts, s.update_date, s.update_time, s.update_date_time, s.update_by, e.e_name AS updateby FROM con_sts_log AS s
					LEFT JOIN clients AS c
					ON c.c_id = s.c_id
					LEFT JOIN emp_info AS e ON e.e_id = s.update_by
					WHERE s.c_id = '$ids' ORDER BY s.id DESC");
					

$sql1 = mysql_query("SELECT pay_date, pay_amount, bill_discount FROM payment WHERE c_id = '$ids' ORDER BY pay_date");


$rows = mysql_fetch_array($sql2);
$Dew = $rows['due'];				
if($Dew > 0){
	$color = 'style="color:red;text-align: right;padding-right: 20px;"';					
} else{
	$color = 'style="color:#555555;text-align: right;padding-right: 20px;"';
}

if($row['con_sts'] == 'Active'){
	$clss = 'col2';
	$dd = 'Inactive';
	$ee = "<i class='iconfa-play'></i>";
}
if($row['con_sts'] == 'Inactive'){
	$clss = 'col3';
	$dd = 'Active';
	$ee = "<i class='iconfa-pause'></i>";
}

if($row['log_sts'] == '0'){
	$aa = 'btn col2';
	$bb = "<i class='iconfa-unlock'></i>";
	$cc = 'Lock';
}
if($row['log_sts'] == '1'){
	$aa = 'btn col3';
	$bb = "<i class='iconfa-lock pad4'></i>";
	$cc = 'Unlock';
}

$sqlq = mysql_query("SELECT id, Name, ServerIP, Username, Pass, Port, e_Md, secret_h, add_date_time, note FROM mk_con WHERE id = '$mk_id'");
$row2 = mysql_fetch_assoc($sqlq);

$passs = openssl_decrypt($row2['Pass'], $row2['e_Md'], $row2['secret_h']);
$interid = 'pppoe-'.$ids;

$API = new routeros_api();
$API->debug = false;
if ($API->connect($row2['ServerIP'], $row2['Username'], $passs, $row2['Port'])) {
	$API->write('/ppp/active/print', false);
		$API->write('?name='.$ids);
		$res=$API->read(true);

		$ppp_name = $res[0]['name'];
		$ppp_mac = $res[0]['caller-id'];
		$ppp_ip = $res[0]['address'];
		$ppp_uptime = $res[0]['uptime'];
		
		$API->write('/ppp/secret/print', false);
		$API->write('?name='.$ids);
		$ress=$API->read(true);
		
		$ppp_lastloggedout = $ress[0]['last-logged-out'];
		
		$API->write('/interface/print', false);
		$API->write('from=<pppoe-'.$ids.'> stats-detail');
		$ressi=$API->read(true);
		
		$int_name = $ressi[0]['name'];
		$int_rx = $ressi[0]['rx-byte'];
		$int_tx = $ressi[0]['tx-byte'];
		
		$download_speed = $int_tx;
		$upload_speed = $int_rx;
	}
else{
	echo 'Selected Network are not Connected';
}

if($ppp_mac != ''){
		$ppp_mac_replace = str_replace(":","-",$ppp_mac);
		$ppp_mac_replace_8 = substr($ppp_mac_replace, 0, 8);
		
	$macsearch = mysql_query("SELECT mac, info FROM mac_device WHERE mac = '$ppp_mac_replace_8'");
	$macsearchaa = mysql_fetch_assoc($macsearch);
	$response = $macsearchaa['info'];
}
else{
	$response = '';
}

$queryfdgh = "DELETE FROM realtime_speed WHERE c_id = '$ids'";
if(!mysql_query($queryfdgh)){die('Error: ' . mysql_error());}
	?>

<div class="box box-primary">
<div class="modal-content">
<?php if($row['mac_user'] == '0'){?>
<table style="margin: 5px 0px 0 5px;">
		<tr>
			<th><a data-placement='top' data-rel='tooltip' style='padding: 3px 5px 3px 5px;font-size: 14px;font-weight: bold;border: 1px solid #bbb;' href='PaymentAdd?id=<?php echo $row['c_id']; ?>' title='Add Payment' class='btn col2'><i class='iconfa-money'></i></a>
				<a data-placement='top' data-rel='tooltip' style='padding: 3px 5px 3px 5px;font-size: 14px;font-weight: bold;border: 1px solid #bbb;' href='PaymentOnlineAdd?id=<?php echo $row['c_id']; ?>' title='Online Payment' class='btn col2'><i class='iconfa-shopping-cart'></i></a>
			</th>
		</tr>	
</table>
<?php } if($row['mac_user'] == '1'){?>
<table style="margin: 5px 0px 0 10px;">
		<tr>
			<th>
				<form action='ClientsRecharge' method='post'><input type='hidden' name='c_id' value="<?php echo $row['c_id']; ?>" /><button class='btn' style='padding: 3px 5px 3px 5px;font-size: 9px;font-weight: bold;border: 1px solid #bbb;color: #337ab7;'>Recharge</button></form>
			</th>
		</tr>	
</table>
<?php } ?>
				<div class="box-header" style="padding-top: 0px;">
					<div class="row" style="margin-top: 8px;">
						<div style="padding-left: 5px; width: 100%;">
							<table class="table table-bordered table-invoice" style="width: 98%;font-weight: bold;">
								<tr>
									<td class="btn btn-info btn-rounded" style="width: 100%;border-radius: 0;border: 1px solid white;font-size: 10px;font-weight: bold;text-align: right;"><a href=''>Status</a></td>
									<?php if($ppp_mac != ''){?>
										<td class="" style='padding-top: 10px;font-size: 14px;color: green;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;width: 78%;'>ONLINE</td>
									<?php } else{ ?>
										<td class="" style='padding-top: 10px;font-size: 14px;color: red;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;width: 78%;'>OFFLINE</td>
									<?php } ?>
								</tr>
								<tr>
									<td class="btn btn-info btn-rounded" style="width: 100%;border-radius: 0;border: 1px solid white;font-size: 10px;font-weight: bold;text-align: right;"><a href='#'>PPPoE ID</a></td>
									<td style='padding-top: 10px;font-size: 10px;color: #555555;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;width: 78%;'><?php echo $row['c_id']; ?></td>
								</tr>
								<tr>
									<td class="btn btn-info btn-rounded" style="width: 100%;border-radius: 0;border: 1px solid white;font-size: 10px;font-weight: bold;text-align: right;"><a href='#'>Password</a></td>
									<td style='padding-top: 10px;font-size: 10px;color: #555555;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;width: 78%;'><?php echo $row['pw']; ?></td>
								</tr>
								<tr>
									<td class="btn btn-info btn-rounded" style="width: 100%;border-radius: 0;border: 1px solid white;font-size: 10px;font-weight: bold;text-align: right;"><a href='#'>Name</a></td>
									<td style='padding-top: 10px;font-size: 10px;color: #555555;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;width: 78%;'><?php echo $row['c_name']; ?></td>
								</tr>
								<tr>
									<td class="btn btn-info btn-rounded" style="width: 100%;border-radius: 0;border: 1px solid white;font-size: 10px;font-weight: bold;text-align: right;"><a href='#'>cell</a></td>
									<td style='padding-top: 10px;font-size: 10px;color: #555555;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;width: 78%;'><a href='#'><?php echo $row['cell']; ?></a></td>
								</tr>
								<tr>
									<td class="btn btn-info btn-rounded" style="width: 100%;border-radius: 0;border: 1px solid white;font-size: 10px;font-weight: bold;text-align: right;"><a href='#'>Address</a></td>
									<td style='padding-top: 10px;font-size: 10px;color: #555555;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;width: 78%;'><?php echo $row['address']; ?></td>
								</tr>
								<tr>
									<td class="btn btn-info btn-rounded" style="width: 100%;border-radius: 0;border: 1px solid white;font-size: 10px;font-weight: bold;text-align: right;"><a href='#'>Joining</a></td>
									<td style='padding-top: 10px;font-size: 10px;color: #555555;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;width: 78%;'><?php echo $row['join_date']; ?></td>
								</tr>
								<tr>
									<td class="btn btn-info btn-rounded" style="width: 100%;border-radius: 0;border: 1px solid white;font-size: 10px;font-weight: bold;text-align: right;"><a href='#'>IP Address</a></td>
									<td style='padding-top: 10px;font-size: 10px;color: #555555;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;width: 78%;'><a type="submit" title='Click For Ping' class="clickMe"><input type="hidden" name="ip" value="<?php echo $ppp_ip;?>"><div id="growl00"><?php echo $ppp_ip;?></div></a></td>
								</tr>
								<tr>
									<td class="btn btn-info btn-rounded" style="width: 100%;border-radius: 0;border: 1px solid white;font-size: 10px;font-weight: bold;text-align: right;"><a href='#'>MAC Address</a></td>
									<td style='padding-top: 10px;font-size: 10px;color: #555555;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;width: 78%;'><?php echo $ppp_mac;?></td>
								</tr>
								<tr>
									<td class="btn btn-info btn-rounded" style="width: 100%;border-radius: 0;border: 1px solid white;font-size: 10px;font-weight: bold;text-align: right;"><a href='#'>UPTIME</a></td>
									<td style='padding-top: 10px;font-size: 10px;color: #555555;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;width: 78%;'><?php echo $ppp_uptime;?></td>
								</tr>
								<tr>
									<td class="btn btn-info btn-rounded" style="width: 100%;border-radius: 0;border: 1px solid white;font-size: 10px;font-weight: bold;text-align: right;"><a href='#'>Last Logout</a></td>
									<td class="" style='padding-top: 10px;font-size: 10px;color: #555555;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;width: 78%;'><?php echo $ppp_lastloggedout;?></td>
								</tr>
								<tr>
									<td class="btn btn-info btn-rounded" style="width: 100%;border-radius: 0;border: 1px solid white;font-size: 10px;font-weight: bold;text-align: right;"><a href=''>Device</a></td>
									<td class="" style='padding-top: 10px;font-size: 10px;color: #555555;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;width: 78%;'><?php echo $response; ?></td>
								</tr>
								<tr>
									<td class="btn btn-info btn-rounded" style="width: 100%;border-radius: 0;border: 1px solid white;font-size: 10px;font-weight: bold;text-align: right;"><a href=''>Termination</a></td>
									<td class="" style='padding-top: 10px;font-size: 10px;color: #555555;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;width: 78%;'><?php echo $row['termination_date']; ?></td>
								</tr>
								<tr>
									<td class="btn btn-info btn-rounded" style="width: 100%;border-radius: 0;border: 1px solid white;font-size: 10px;font-weight: bold;text-align: right;"><a href=''>Upload</a></td>
									<td class="" style='padding-top: 10px;font-size: 10px;color: #555555;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;width: 78%;'><div id='responsecontainer_rx'></div></td>
								</tr>
								<tr>
									<td class="btn btn-info btn-rounded" style="width: 100%;border-radius: 0;border: 1px solid white;font-size: 10px;font-weight: bold;text-align: right;"><a href=''>Download</a></td>
									<td class="" style='padding-top: 10px;font-size: 10px;color: #555555;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;width: 78%;'><div id='responsecontainer_tx'></div></td>
								</tr>
							</table>
							<?php if($ppp_mac != ''){?>
								<div id='Client_graph'></div><br>
							<?php }?>
						</div><!--col-md-6-->
					</div>
				</div>

<div class="modal-body" style="padding: 0;">
					<table style="width:100%;background: #eeeeee;font-size: 10px;">
						<tr>
							<th style="text-align:left;padding-left: 10px;"><b>Billing Information</b></th>
							<td <?php echo $color; ?>> <b>Total Due:</b> &nbsp; &nbsp; <?php echo number_format($Dew,2).'tk'; ?></td>
						</tr>	
					</table>
					<table id="dyntable" class="table table-bordered responsive" style="width: 100% !important;float: left;margin-right: 10px;">
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
								while( $rown = mysql_fetch_assoc($sqlggg) )
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
				</table>
			
				<table style="width:100%;background: #eeeeee;font-size: 10px;">
					<tr>
						<th style="text-align:left;padding-left: 10px;"><b>Others Bill History</b></th>
					</tr>	
				</table>
				<table id="dyntable" class="table table-bordered responsive" style="width: 100% !important;float: left;margin-right: 10px;">
                    <colgroup>
                        <col class="con0" />
                        <col class="con1" />
						<col class="con0" />
                        <col class="con1" />
						<col class="con0" />
                    </colgroup>
                    <thead>
                        <tr  class="newThead">
							<th class="head1" style="font-size: 9px;">Date</th>
							<th class="head0" style="font-size: 9px;">Type</th>
							<th class="head1" style="font-size: 9px;">Amount</th>
							<th class="head1" style="font-size: 9px;">Entry</th>
                        </tr>
                    </thead>
                        </tr>
                    </thead>
                    <tbody>
						<?php		
								while( $rowwqq = mysql_fetch_assoc($sql360) )
								{
									$yrdataa= strtotime($rowwqq['pay_date']);
									$months = date('d F, Y', $yrdataa);
									echo
										"<tr class='gradeX'>
											<td style='font-size: 9px;font-weight: bold;'>{$months}</td>
											<td style='font-size: 9px;font-weight: bold;'>{$rowwqq['type']}</td>
											<td style='font-size: 9px;font-weight: bold;'>{$rowwqq['amount']}</td>
											<td style='font-size: 9px;font-weight: bold;'>{$rowwqq['e_name']}</td>
										</tr>\n ";
								} 
						?>
					</tbody>
				</table>

				<table style="width:100%;background: #eeeeee;font-size: 10px;">
					<tr>
						<th style="text-align:left;padding-left: 10px;"><b>Package Change History</b></th>
					</tr>	
				</table>
				<table id="dyntable" class="table table-bordered responsive" style="width: 100% !important;float: left;margin-right: 10px;">
                    <colgroup>
						<col class="con0" />
                        <col class="con1" />
						<col class="con0" />
                    </colgroup>
                    <thead>
                        <tr  class="newThead">
							<th class="head0" style="font-size: 9px;">Date</th>
							<th class="head0" style="font-size: 9px;">Old Pkg</th>
							<th class="head1" style="font-size: 9px;">New Pkg</th>
                        </tr>
                    </thead>
                        </tr>
                    </thead>
                    <tbody>
						<?php		
								$x = 1;				
								while( $rowwq = mysql_fetch_assoc($sql36) )
								{
									echo
										"<tr class='gradeX'>
											<td style='font-size: 9px;font-weight: bold;'>{$rowwq['up_date']}</td>
											<td style='font-size: 9px;font-weight: bold;'>{$rowwq['old_package']} [{$rowwq['old_price']}TK]</td>
											<td style='font-size: 9px;font-weight: bold;'>{$rowwq['nw_package']} [{$rowwq['nw_price']}TK]</td>
										</tr>\n ";
									$x++;	
								} 
						?>
					</tbody>
				</table>
				
				<table style="width:100%;background: #eeeeee;font-size: 10px;">
					<tr>
						<th style="text-align:left;padding-left: 10px;"><b>Complain History</b></th>
					</tr>	
				</table>
				<table id="dyntable" class="table table-bordered responsive" style="width: 100% !important;float: left;margin-right: 10px;">
                    <colgroup>
                        <col class="con0" />
                        <col class="con1" />
						<col class="con0" />
                        <col class="con1" />
						<col class="con0" />
						 <col class="con1" />
                    </colgroup>
                    <thead>
                        <tr  class="newThead">
							<th class="head1" style="font-size: 9px;">Date</th>
							<th class="head0" style="font-size: 9px;">Tkt_No</th>
							<th class="head1" style="font-size: 9px;">Subject</th>
							<th class="head1" style="font-size: 9px;">Massage</th>
							<th class="head0" style="font-size: 9px;">Status</th>
                        </tr>
                    </thead>
                        </tr>
                    </thead>
                    <tbody>
						<?php		
								$x = 1;				
								while( $rowws = mysql_fetch_assoc($sql35) )
								{
									if($rowws['sts'] == 0){
										$stss = 'Open';
									}
									if($rowws['sts'] == 1){
										$stss = 'Close';
									}
									echo
										"<tr class='gradeX'>
											<td style='font-size: 9px;font-weight: bold;'>{$rowws['entry_date_time']}</td>
											<td style='font-size: 9px;font-weight: bold;'>{$rowws['ticket_no']}</td>
											<td style='font-size: 9px;font-weight: bold;'>{$rowws['sub']}</td>
											<td style='font-size: 9px;font-weight: bold;'>{$rowws['massage']}</td>
											<td style='font-size: 9px;font-weight: bold;'>{$stss}</td>
											
										</tr>\n ";
									$x++;	
								}
						?>
					</tbody>
				</table>

				
				<table style="width:100%;background: #eeeeee;font-size: 10px;">
					<tr>
						<th style="text-align:left;padding-left: 10px;"><b>Connection History</b></th>
					</tr>	
				</table>
				<table id="dyntable" class="table table-bordered responsive" style="width: 100% !important;float: left;margin-right: 10px;">
                    <colgroup>
                        <col class="con0" />
                        <col class="con1" />
						<col class="con0" />
                        <col class="con1" />
						<col class="con0" />
                    </colgroup>
                    <thead>
                        <tr  class="newThead">
							<th class="head1" style="font-size: 9px;">Date</th>
							<th class="head1" style="font-size: 9px;">Time</th>
							<th class="head1" style="font-size: 9px;">Status</th>
							<th class="head1" style="font-size: 9px;">By</th>
                        </tr>
                    </thead>
                        </tr>
                    </thead>
                    <tbody>
						<?php		
								$x = 1;				
								while( $roww = mysql_fetch_assoc($sql34) )
								{
									if($roww['update_by'] == 'Auto'){
										$updatebyy = 'Auto';
									}
									else{
										$updatebyy = $roww['updateby'];
									}
									$yrata= strtotime($roww['update_date']);
									$date_mon = date('d F, Y', $yrata);
									echo
										"<tr class='gradeX'>
											<td style='font-size: 9px;font-weight: bold;'>{$date_mon}</td>
											<td style='font-size: 9px;font-weight: bold;'>{$roww['update_time']}</td>
											<td style='font-size: 9px;font-weight: bold;'>{$roww['con_sts']}</td>
											<td style='font-size: 9px;font-weight: bold;'>{$updatebyy}</td>
										</tr>\n ";
									$x++;	
								}  
						?>
					</tbody>
				</table>
				
		
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
<script language="JavaScript" type="text/javascript">
function checkDelete(){
    return confirm('Delete!!  Are you sure?');
}

function checksts(){
    return confirm('Change connection status!!  Are you sure?');
}

function checkLock(){
    return confirm('Are you sure?  Do u know what are you doing?');
}

 $(document).ready(function() {
 	 $("#responsecontainer_tx").load("Client_Tx.php?mk_id=<?php echo $mk_id;?>&c_id=<?php echo $ids;?>");
 	 $("#responsecontainer_rx").load("Client_Rx.php?mk_id=<?php echo $mk_id;?>&c_id=<?php echo $ids;?>");
 	 $("#Client_graph").load("Client_graph.php?c_id=<?php echo $ids;?>");
   var refreshId = setInterval(function() {
      $("#responsecontainer_tx").load('Client_Tx.php?mk_id=<?php echo $mk_id;?>&c_id=<?php echo $ids;?>');
      $("#responsecontainer_rx").load('Client_Rx.php?mk_id=<?php echo $mk_id;?>&c_id=<?php echo $ids;?>');
      $("#Client_graph").load('Client_graph.php?c_id=<?php echo $ids;?>');
   }, 3000);
   $.ajaxSetup({ cache: false });
   
	
	$('.clickMe').on('click', function()
{
    $('#growl00').load('ip-ping-check.php?ip=<?php echo $ppp_ip;?>&mk_id=<?php echo $mk_id;?>');
});
});
</script>