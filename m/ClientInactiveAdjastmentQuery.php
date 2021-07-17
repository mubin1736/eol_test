<?php
include("../web/conn/connection.php");
include('../web/include/telegramapi.php');
include("mk_api.php");
extract($_POST);

$durationn = floor($duration);

$sqlqqmm = mysql_query("SELECT minimum_day, billing_type FROM emp_info WHERE z_id = '$z_id'");
$row22m = mysql_fetch_assoc($sqlqqmm);
$minimum_day = $row22m['minimum_day'];
$billing_type = $row22m['billing_type'];

$sql1q = mysql_query("SELECT c_id, SUM(bill_amount) AS totbill FROM billing_mac WHERE z_id = '$macz_id'");
$rowq = mysql_fetch_array($sql1q);

$sql1z1 = mysql_query("SELECT p.id, SUM(p.pay_amount) AS repayment, SUM(p.discount) AS rediscount, (SUM(p.pay_amount) + SUM(p.discount)) AS retotalpayments FROM `payment_macreseller` AS p
						WHERE p.z_id = '$macz_id' AND p.sts = '0'");
$rowwz = mysql_fetch_array($sql1z1);

$aaaa = $rowwz['retotalpayments']-$rowq['totbill'];

ini_alter('date.timezone','Asia/Almaty');
$update_date = date('Y-m-d', time());
$update_time = date('H:i:s', time());

$sqlqqww = mysql_query("SELECT mk_profile, p_price, p_price_reseller FROM package WHERE p_id = '$p_id'");
$row22ww = mysql_fetch_assoc($sqlqqww);
$p_price = $row22ww['p_price'];
$mk_profile = $row22ww['mk_profile'];
$p_price_reseller = $row22ww['p_price_reseller'];

$sqlmk = mysql_query("SELECT id, ServerIP, Username, Pass, Port, e_Md, secret_h FROM mk_con WHERE sts = '0' AND id = '$mk_id'");
$rowmk = mysql_fetch_assoc($sqlmk);
								
$ServerIP = $rowmk['ServerIP'];
$Username = $rowmk['Username'];
$Pass= openssl_decrypt($rowmk['Pass'], $rowmk['e_Md'], $rowmk['secret_h']);
$Port = $rowmk['Port'];

$todayy = date('d', time()); //17
$lastdayofthismonth = date('t'); //30

$todayyyy = date('Y-m-d', time());
$todayyyy_time = date('H:i:s', time());

$packageoneday = $p_price/30;
$daycost = $durationn*$packageoneday;
			
$checkbalance = $aaaa-$daycost;
//if($checkbalance < 0){echo 'Have not sufficient balance.';}else{
	
$API = new routeros_api();
$API->debug = false;
if ($API->connect($ServerIP, $Username, $Pass, $Port)) {

		$API->write('/ppp/secret/print', false);
		$API->write('?name='.$c_id);
		$res=$API->read(true);

		$ppp_cid = $res[0]['name'];
}else{echo 'Selected Network are not Connected.';}

$sqlqqrr = mysql_query("SELECT c.c_id, c.com_id, c.c_name, c.join_date, c.cell, IFNULL(l.pw,112233) AS pw, l.user_id, z.z_name FROM clients AS c
						LEFT JOIN login AS l ON l.user_id = c.c_id LEFT JOIN zone AS z ON z.z_id = c.z_id WHERE c.c_id = '$c_id'");
$row2255 = mysql_fetch_assoc($sqlqqrr);
$password = $row2255['pw'];
$passwordd = sha1($password);
$com_id = $row2255['com_id'];
$c_name = $row2255['c_name'];
$join_date = $row2255['join_date'];
$cell = $row2255['cell'];
$user_id = $row2255['user_id'];
$service = 'pppoe';
$z_namee = $row2255['z_name'];
$comment = $join_date.'-'.$c_name.'-'.$com_id.'-'.$cell;
						

if($id != ''){
					if($macuser == '1'){
						if($checkbalance > 0 && $billing_type == 'Prepaid' || $billing_type == 'Postpaid'){
							if($durationn >= $minimum_day){
							$new_end_date = date('Y-m-d', strtotime($end_date . " + ".$durationn." day"));
							$new_start_date = date('Y-m-d', strtotime($end_date . " + 1 day"));
							$termination_date = $new_end_date;

							$packageoneday = $p_price/30;
							$daycost = $durationn*$packageoneday;
							
						$sqlqqrrm = mysql_query("SELECT id FROM billing_mac WHERE c_id = '$c_id' AND start_date = '$end_date'");
						if(mysql_num_rows($sqlqqrrm)<=0){
							
								$query2bb = "insert into billing_mac (c_id, z_id, p_id, start_date, start_time, end_date, days, p_price, bill_amount, entry_by, entry_date, entry_time) VALUES ('$c_id', '$z_id', '$p_id', '$end_date', '$update_time', '$new_end_date', '$durationn', '$p_price', '$daycost', '$ee_id', '$update_date', '$update_time')";
								if (!mysql_query($query2bb))
								{
								die('Error: ' . mysql_error());
								}
								
								$sqlqqrruu = mysql_query("SELECT id, bill_date, p_id, p_price, discount, bill_amount FROM billing_mac_client WHERE c_id = '$c_id' ORDER BY id DESC LIMIT 1");
								$row225566 = mysql_fetch_assoc($sqlqqrruu);
								$bill_date = $row225566['bill_date'];
								$macp_price = $row225566['p_price'];
								$macp_discount = $row225566['discount'];
								$macpbill_amount = $row225566['bill_amount'];
								$macpbill_id = $row225566['id'];
								
								if($macpbill_amount == '0.00' && $macp_price == '0.00'){
								$queryqtt ="UPDATE billing_mac_client SET p_id = '$p_id', p_price = '$p_price_reseller', bill_amount = '$p_price_reseller', bill_date = '$update_date' WHERE id = '$macpbill_id'";
								if (!mysql_query($queryqtt))
								{
								die('Error: ' . mysql_error());
								}
								}
								
								$query1q ="INSERT INTO con_sts_log (c_id, con_sts, update_date, update_time, update_by) VALUES ('$c_id', 'Active', '$update_date', '$update_time', '$ee_id')";
								if (!mysql_query($query1q))
								{
								die('Error: ' . mysql_error());
								}
								$queryq ="UPDATE clients SET con_sts = 'Active', p_id = '$p_id', termination_date = '$termination_date', con_sts_date = '$update_date' WHERE c_id = '$c_id'";
								if (!mysql_query($queryq))
								{
								die('Error: ' . mysql_error());
								}
						}
							$hhh = 'ok';
							$a = 'Inactive';
							
							if($ppp_cid != '' && $user_id != ''){
									$API = new routeros_api();
									$API->debug = false;
									if ($API->connect($ServerIP, $Username, $Pass, $Port)) {
										

									   $arrID =	$API->comm("/ppp/secret/getall", 
												  array(".proplist"=> ".id","?name" => $c_id,));

												$API->comm("/ppp/secret/set",
												array(".id" => $arrID[0][".id"],"disabled"  => "no","profile" => $mk_profile));
										$API->disconnect();
									}
									
							}
							
							if($ppp_cid == '' && $user_id != ''){
									$API = new routeros_api();
									$API->debug = false;
									if ($API->connect($ServerIP, $Username, $Pass, $Port)) {
									
									   $API->comm("/ppp/secret/add", array(
										  "name"     => $c_id,
										  "password" => $password,
										  "profile"  => $mk_profile,
										  "service"  => $service,
										  "comment"  => $comment,
										));
										$API->disconnect();
									}
							}
							
							if($ppp_cid == '' && $user_id == ''){
							
									$API = new routeros_api();
									$API->debug = false;
									if ($API->connect($ServerIP, $Username, $Pass, $Port)) {

									   $API->comm("/ppp/secret/add", array(
										  "name"     => $c_id,
										  "password" => $password,
										  "profile"  => $mk_profile,
										  "service"  => $service,
										  "comment"  => $comment,
										));
										$API->disconnect();
									}
								$query1lll = "INSERT INTO login (user_name, e_id, user_id, password, user_type, pw) VALUES ('$c_name', '$c_id', '$c_id', '$passwordd', 'client', '$password')";
								if (!mysql_query($query1lll))
								{
								die('Error: ' . mysql_error());
								}
								
							}
							if($ppp_cid != '' && $user_id == ''){
							
									$API = new routeros_api();
									$API->debug = false;
									if ($API->connect($ServerIP, $Username, $Pass, $Port)) {
									
									   $arrID =	$API->comm("/ppp/secret/getall", 
												  array(".proplist"=> ".id","?name" => $c_id,));

												$API->comm("/ppp/secret/set",
												array(".id" => $arrID[0][".id"],"disabled"  => "no", "profile" => $mk_profile));
										$API->disconnect();
									}
									
						$query1lll = "INSERT INTO login (user_name, e_id, user_id, password, user_type, pw) VALUES ('$c_name', '$c_id', '$c_id', '$passwordd', 'client', '$password')";
								if (!mysql_query($query1lll))
								{
								die('Error: ' . mysql_error());
								}
							}
//----Telegream---------------------
$sqlmqq = mysql_query("SELECT b.c_id, c.c_name, c.cell, b.z_id, b.p_id, b.start_date, b.start_time, b.end_date, b.days, b.bill_amount, e.e_name AS entryby, b.entry_date, b.entry_time FROM billing_mac AS b
						LEFT JOIN emp_info AS e ON e.e_id = b.entry_by
						LEFT JOIN clients AS c ON c.c_id = b.c_id
						WHERE b.c_id = '$c_id' ORDER BY b.id DESC LIMIT 1");
						
		$rowmkqq = mysql_fetch_assoc($sqlmqq);
		$cname = $rowmkqq['c_name'];
		$ccell = $rowmkqq['cell'];
		$update_datetime = $rowmkqq['entry_date'].' '.$rowmkqq['entry_time'];
		$entrybyyyyy = $rowmkqq['entryby'];
		$dayssss = $rowmkqq['days'];
		$billamountttt = $rowmkqq['bill_amount'];

if($tele_sts == '0' && $tele_client_recharge_sts == '0'){
$telete_way = 'client_recharge';
$msg_body='..::[Reseller Client Recharge]::..
'.$cname.' ['.$c_id.'] ['.$ccell.']

Cost: '.$billamountttt.' TK
Days: '.$dayssss.'

By: '.$entrybyyyyy.' at '.$update_datetime.'
'.$tele_footer.'';

include('include/telegramapicore.php');
}
//-----------------Telegram-------------------------------
							
							}
							else{ $hhh = 'wrong';}
						}
						else{echo 'Have not sufficient balance.'; $hhh = 'wrong';}
					}
					else{
						
					if($ppp_cid != '' && $user_id != ''){

								$API = new routeros_api();
								$API->debug = false;
								if ($API->connect($ServerIP, $Username, $Pass, $Port)) {
									
								if($breseller == '1'){
									 $arrID =	$API->comm("/queue/simple/getall", 
											  array(".proplist"=> ".id","?name" => $c_id,));

											$API->comm("/queue/simple/set",
											array(".id" => $arrID[0][".id"],"disabled"  => "no", "profile" => $mk_profile));
									$API->disconnect();
								}else{
								   $arrID =	$API->comm("/ppp/secret/getall", 
											  array(".proplist"=> ".id","?name" => $c_id,));

											$API->comm("/ppp/secret/set",
											array(".id" => $arrID[0][".id"],"disabled"  => "no", "profile" => $mk_profile));
									$API->disconnect();
								}}
								
					$query2q ="UPDATE billing SET p_price = '$p_price', discount = '$discount', extra_bill = '$extra_bill', bill_amount = '$bill_amount' WHERE id = '$id'";
					if (!mysql_query($query2q))
					{
					die('Error: ' . mysql_error());
					}
					$query1q ="INSERT INTO con_sts_log (c_id, con_sts, update_date, update_time, update_by) VALUES ('$c_id', 'Active', '$update_date', '$update_time', '$ee_id')";
					if (!mysql_query($query1q))
					{
					die('Error: ' . mysql_error());
					}
					$queryq ="UPDATE clients SET con_sts = 'Active', payment_deadline = '$payment_deadline', termination_date = '$termination_date', con_sts_date = '$update_date' WHERE c_id = '$c_id'";
					if (!mysql_query($queryq))
					{
					die('Error: ' . mysql_error());
					}
					}
					
					if($ppp_cid == '' && $user_id != ''){
								$API = new routeros_api();
								$API->debug = false;
								if ($API->connect($ServerIP, $Username, $Pass, $Port)) {
								
								   $API->comm("/ppp/secret/add", array(
									  "name"     => $c_id,
									  "password" => $password,
									  "profile"  => $mk_profile,
									  "service"  => $service,
									  "comment"  => $comment,
									));
									$API->disconnect();
								}

					$query2q ="UPDATE billing SET p_price = '$p_price', discount = '$discount', extra_bill = '$extra_bill', bill_amount = '$bill_amount' WHERE id = '$id'";
							if (!mysql_query($query2q))
							{
							die('Error: ' . mysql_error());
							}
					$query1q ="INSERT INTO con_sts_log (c_id, con_sts, update_date, update_time, update_by) VALUES ('$c_id', 'Active', '$update_date', '$update_time', '$ee_id')";
							if (!mysql_query($query1q))
							{
							die('Error: ' . mysql_error());
							}
					$queryq ="UPDATE clients SET con_sts = 'Active', payment_deadline = '$payment_deadline', termination_date = '$termination_date', con_sts_date = '$update_date' WHERE c_id = '$c_id'";
							if (!mysql_query($queryq))
							{
							die('Error: ' . mysql_error());
							}
					}
					
					
					if($ppp_cid == '' && $user_id == ''){
						
								$API = new routeros_api();
								$API->debug = false;
								if ($API->connect($ServerIP, $Username, $Pass, $Port)) {
									
								if($breseller == '1'){
									 $arrID =	$API->comm("/queue/simple/getall", 
											  array(".proplist"=> ".id","?name" => $c_id,));

											$API->comm("/queue/simple/set",
											array(".id" => $arrID[0][".id"],"disabled"  => "no", "profile" => $mk_profile));
									$API->disconnect();
								}else{
								   $API->comm("/ppp/secret/add", array(
									  "name"     => $c_id,
									  "password" => $password,
									  "profile"  => $mk_profile,
									  "service"  => $service,
									  "comment"  => $comment,
									));
									$API->disconnect();
								}}
					$query1lll = "INSERT INTO login (user_name, e_id, user_id, password, user_type, pw) VALUES ('$c_name', '$c_id', '$c_id', '$passwordd', 'client', '$password')";
							if (!mysql_query($query1lll))
							{
							die('Error: ' . mysql_error());
							}
							
					$query2q ="UPDATE billing SET p_price = '$p_price', discount = '$discount', extra_bill = '$extra_bill', bill_amount = '$bill_amount' WHERE id = '$id'";
							if (!mysql_query($query2q))
							{
							die('Error: ' . mysql_error());
							}
					$query1q ="INSERT INTO con_sts_log (c_id, con_sts, update_date, update_time, update_by) VALUES ('$c_id', 'Active', '$update_date', '$update_time', '$ee_id')";
							if (!mysql_query($query1q))
							{
							die('Error: ' . mysql_error());
							}
					$queryq ="UPDATE clients SET con_sts = 'Active', payment_deadline = '$payment_deadline', termination_date = '$termination_date', con_sts_date = '$update_date' WHERE c_id = '$c_id'";
							if (!mysql_query($queryq))
							{
							die('Error: ' . mysql_error());
							}
					}
					
					if($ppp_cid != '' && $user_id == ''){
						
								$API = new routeros_api();
								$API->debug = false;
								if ($API->connect($ServerIP, $Username, $Pass, $Port)) {
									
								if($breseller == '1'){
									 $arrID =	$API->comm("/queue/simple/getall", 
											  array(".proplist"=> ".id","?name" => $c_id,));

											$API->comm("/queue/simple/set",
											array(".id" => $arrID[0][".id"],"disabled"  => "no",));
									$API->disconnect();
								}else{
								   $arrID =	$API->comm("/ppp/secret/getall", 
											  array(".proplist"=> ".id","?name" => $c_id,));

											$API->comm("/ppp/secret/set",
											array(".id" => $arrID[0][".id"],"disabled"  => "no",));
									$API->disconnect();
								}}
								
					$query1lll = "INSERT INTO login (user_name, e_id, user_id, password, user_type, pw) VALUES ('$c_name', '$c_id', '$c_id', '$passwordd', 'client', '$password')";
							if (!mysql_query($query1lll))
							{
							die('Error: ' . mysql_error());
							}
							
					$query2q ="UPDATE billing SET p_price = '$p_price', discount = '$discount', extra_bill = '$extra_bill', bill_amount = '$bill_amount' WHERE id = '$id'";
							if (!mysql_query($query2q))
							{
							die('Error: ' . mysql_error());
							}
					$query1q ="INSERT INTO con_sts_log (c_id, con_sts, update_date, update_time, update_by) VALUES ('$c_id', 'Active', '$update_date', '$update_time', '$ee_id')";
							if (!mysql_query($query1q))
							{
							die('Error: ' . mysql_error());
							}
					$queryq ="UPDATE clients SET con_sts = 'Active', payment_deadline = '$payment_deadline', termination_date = '$termination_date', con_sts_date = '$update_date' WHERE c_id = '$c_id'";
							if (!mysql_query($queryq))
							{
							die('Error: ' . mysql_error());
							}
					}
					
//----Telegream---------------------
if($tele_sts == '0' && $tele_client_status_sts == '0'){
$telete_way = 'client_status';
$sqlmqq = mysql_query("SELECT c.c_name, c.cell, e.`con_sts`, e.`update_date`, e.`update_time`, q.e_name AS updatebyy, e.`update_date_time` FROM con_sts_log AS e 
						LEFT JOIN emp_info AS q ON q.e_id = e.update_by
						LEFT JOIN clients AS c ON c.c_id = e.c_id
						WHERE e.c_id = '$c_id' ORDER BY e.id DESC LIMIT 1");
						
		$rowmkqq = mysql_fetch_assoc($sqlmqq);
		$cname = $rowmkqq['c_name'];
		$ccell = $rowmkqq['cell'];
		$update_datetime = $rowmkqq['update_date_time'];
		$updatebyy = $rowmkqq['updatebyy'];
		
$msg_body='..::[Client Activated]::..
'.$cname.' ['.$c_id.'] ['.$ccell.']

Bill Adjust: '.$bill_amount.' TK
Deadline: '.$payment_deadline.'

By: '.$updatebyy.' at '.$update_datetime.'
'.$tele_footer.'';

include('../web/include/telegramapicore.php');
}
//----Telegream---------------------
					}

if($hhh == 'wrong'){echo 'Invilade Submission!! Please Check All Inputs. Yor Minimum Recharge Day is '.$minimum_day.'.';}	else{	
?>

<html>
	<body>
	<form action="Clients" method="post" name="ok">
		<input type="hidden" name="sts" value="Status<?php echo $a; ?>">
	</form>
	<script language="javascript" type="text/javascript">
		document.ok.submit();
	</script>
	</body>
</html>

<?php 
								}}
else
{ 
	echo "Error!! No Connected Network Found.";
} 

mysql_close($con);
//}

?>