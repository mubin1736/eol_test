<?php
include("../web/conn/connection.php");
include("mk_api.php");
extract($_POST);

$durationn = floor($duration);

$sqlqqmm = mysql_query("SELECT minimum_day, billing_type FROM emp_info WHERE z_id = '$z_id'");
$row22m = mysql_fetch_assoc($sqlqqmm);
$minimum_day = $row22m['minimum_day'];
$billing_type = $row22m['billing_type'];

if($durationn < $minimum_day){echo 'Invilade Submission!! Please Check All Inputs. Yor Minimum Recharge Day is '.$minimum_day.'.';}else{
$sql1q = mysql_query("SELECT c_id, SUM(bill_amount) AS totbill FROM billing_mac WHERE z_id = '$z_id'");
$rowq = mysql_fetch_array($sql1q);

$sql1z1 = mysql_query("SELECT p.id, SUM(p.pay_amount) AS repayment, SUM(p.discount) AS rediscount, (SUM(p.pay_amount) + SUM(p.discount)) AS retotalpayments FROM `payment_macreseller` AS p
						WHERE p.z_id = '$z_id' AND p.sts = '0'");
$rowwz = mysql_fetch_array($sql1z1);

$aaaa = $rowwz['retotalpayments']-$rowq['totbill'];

ini_alter('date.timezone','Asia/Almaty');
$update_date = date('Y-m-d', time());
$update_time = date('H:i:s', time());

$sqlqq = mysql_query("SELECT mk_profile, p_price FROM package WHERE p_id = '$p_id'");
$row22 = mysql_fetch_assoc($sqlqq);
$p_price = $row22['p_price'];
$mk_profile = $row22['mk_profile'];

$sqlmk = mysql_query("SELECT id, ServerIP, Username, Pass, Port, e_Md, secret_h FROM mk_con WHERE sts = '0' AND id = '$mk_id'");
$rowmk = mysql_fetch_assoc($sqlmk);
								
$ServerIP = $rowmk['ServerIP'];
$Username = $rowmk['Username'];
$Pass= openssl_decrypt($rowmk['Pass'], $rowmk['e_Md'], $rowmk['secret_h']);
$Port = $rowmk['Port'];

$API = new routeros_api();
$API->debug = false;
						
$todayy = date('d', time()); //17
$lastdayofthismonth = date('t'); //30

$todayyyy = date('Y-m-d', time());
$todayyyy_time = date('H:i:s', time());

$packageoneday = $p_price/30;
$daycost = $durationn*$packageoneday;
			
$checkbalance = $aaaa-$daycost;

if($checkbalance > 0 && $billing_type == 'Prepaid' || $billing_type == 'Postpaid'){
//if($checkbalance < 0){echo 'Have not sufficient balance.';}else{

if ($API->connect($ServerIP, $Username, $Pass, $Port)) {

		$API->write('/ppp/secret/print', false);
		$API->write('?name='.$c_id);
		$res=$API->read(true);

		$ppp_cid = $res[0]['name']; 
}else{echo 'Selected Network are not Connected.';}


if($p_id != '' || $durationn != ''){
	
$sqlqqrr = mysql_query("SELECT c.c_id, c.com_id, c.c_name, c.join_date, c.cell, IFNULL(l.pw,112233) AS pw, l.user_id FROM clients AS c
						LEFT JOIN login AS l ON l.user_id = c.c_id WHERE c.c_id = '$c_id'");
$row2255 = mysql_fetch_assoc($sqlqqrr);
$password = $row2255['pw'];
$passwordd = sha1($password);
$com_id = $row2255['com_id'];
$c_name = $row2255['c_name'];
$join_date = $row2255['join_date'];
$cell = $row2255['cell'];
$user_id = $row2255['user_id'];
$service = 'pppoe';
$profile = $mk_profile;
$comment = $join_date.'-'.$c_name.'-'.$com_id.'-'.$cell.'-End: '.$end_date;
						if($durationn > '0'){
						$new_end_date = date('Y-m-d', strtotime($end_date . " + ".$durationn." day"));
						$new_start_date = date('Y-m-d', strtotime($end_date . " + 1 day"));
						$termination_date = $new_end_date;

						$packageoneday = $p_price/30;
						$daycost = $durationn*$packageoneday;
					$sqlqqrrm = mysql_query("SELECT id FROM billing_mac WHERE c_id = '$c_id' AND start_date = '$end_date'");
					if(mysql_num_rows($sqlqqrrm)<=0){
						if($con_sts == 'Inactive'){
						if($ppp_cid == ''){
							if ($API->connect($ServerIP, $Username, $Pass, $Port)) {
							$API->comm("/ppp/secret/add", array(
									  "name"     => $c_id,
									  "password" => $password,
									  "profile"  => $profile,
									  "service"  => $service,
									  "comment"  => $comment,
									));
							$API->disconnect();
							
							$query1q ="INSERT INTO con_sts_log (c_id, con_sts, update_date, update_time, update_by) VALUES ('$c_id', 'Active', '$update_date', '$update_time', '$entry_by')";
							if (!mysql_query($query1q))
							{
							die('Error: ' . mysql_error());
							}
							$queryq ="UPDATE clients SET con_sts = 'Active', p_id = '$p_id', termination_date = '$termination_date', con_sts_date = '$update_date' WHERE c_id = '$c_id'";
							if (!mysql_query($queryq))
							{
							die('Error: ' . mysql_error());
							}
							
							$query2bb = "INSERT INTO billing_mac (c_id, z_id, p_id, start_date, start_time, end_date, days, p_price, bill_amount, entry_by, entry_date, entry_time) VALUES ('$c_id', '$z_id', '$p_id', '$end_date', '$update_time', '$new_end_date', '$durationn', '$p_price', '$daycost', '$entry_by', '$update_date', '$update_time')";
							if (!mysql_query($query2bb))
							{
							die('Error: ' . mysql_error());
							}
							
							if($user_id == ''){
							$query1lll = "INSERT INTO login (user_name, e_id, user_id, password, user_type, log_sts, pw) VALUES ('$c_name', '$c_id', '$c_id', '$passwordd', 'client', '1', '$password')";
							if (!mysql_query($query1lll))
							{
							die('Error: ' . mysql_error());
							}
							}
							
							}else{echo 'Network are not Connected.';}
						}
						else{
								if ($API->connect($ServerIP, $Username, $Pass, $Port)) {
								   $arrID =	$API->comm("/ppp/secret/getall", 
											  array(".proplist"=> ".id","?name" => $c_id,));

											$API->comm("/ppp/secret/set",
											array(".id" => $arrID[0][".id"],"disabled"  => "no", "profile" => $mk_profile));
									$API->disconnect();
							
							$query1q ="INSERT INTO con_sts_log (c_id, con_sts, update_date, update_time, update_by) VALUES ('$c_id', 'Active', '$update_date', '$update_time', '$entry_by')";
							if (!mysql_query($query1q))
							{
							die('Error: ' . mysql_error());
							}
							$queryq ="UPDATE clients SET con_sts = 'Active', p_id = '$p_id', termination_date = '$termination_date', con_sts_date = '$update_date' WHERE c_id = '$c_id'";
							if (!mysql_query($queryq))
							{
							die('Error: ' . mysql_error());
							}
							
							$query2bb = "insert into billing_mac (c_id, z_id, p_id, start_date, start_time, end_date, days, p_price, bill_amount, entry_by, entry_date, entry_time) VALUES ('$c_id', '$z_id', '$p_id', '$end_date', '$update_time', '$new_end_date', '$durationn', '$p_price', '$daycost', '$entry_by', '$update_date', '$update_time')";
							if (!mysql_query($query2bb))
							{
							die('Error: ' . mysql_error());
							}
							
							if($user_id == ''){
							$query1lll = "INSERT INTO login (user_name, e_id, user_id, password, user_type, log_sts, pw) VALUES ('$c_name', '$c_id', '$c_id', '$passwordd', 'client', '1', '$password')";
							if (!mysql_query($query1lll))
							{
							die('Error: ' . mysql_error());
							}
							}
							
						}else{echo 'Network are not Connected.';}
						}
						}
						else{
							$queryq ="UPDATE clients SET termination_date = '$termination_date', con_sts_date = '$update_date' WHERE c_id = '$c_id'";
							if (!mysql_query($queryq))
							{
							die('Error: ' . mysql_error());
							}
							
							$query2bb = "insert into billing_mac (c_id, z_id, p_id, start_date, start_time, end_date, days, p_price, bill_amount, entry_by, entry_date, entry_time) VALUES ('$c_id', '$z_id', '$p_id', '$end_date', '$update_time', '$new_end_date', '$durationn', '$p_price', '$daycost', '$entry_by', '$update_date', '$update_time')";
							if (!mysql_query($query2bb))
							{
							die('Error: ' . mysql_error());
							}
						}
					}
//-----------------Telegram-------------------------------
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
						$hhh = 'ok';
						$a = 'RechargeDone';
						}
						else{ $hhh = 'wrong';}
					
				
if($hhh == 'wrong'){echo 'Wrong Entry!!!';}	else{	
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
	echo "Wrong Entry";
}

mysql_close($con);
}else{echo 'Have not sufficient balance.';}
}
?>