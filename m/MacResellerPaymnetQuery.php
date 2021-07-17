<?php
include("../web/conn/connection.php");
include('../web/company_info.php');
include('../web/include/smsapi.php');
mysql_query("SET CHARACTER SET utf8");
mysql_query("SET SESSION collation_connection ='utf8_general_ci'");
extract($_POST);

date_default_timezone_set('Etc/GMT-6');
$send_date = date('Y-m-d', time());
$send_time = date('H:i:s', time());

$sqlsdf = mysql_query("SELECT sms_msg FROM sms_msg WHERE id= '10'");
$rowsm = mysql_fetch_assoc($sqlsdf);
$sms_msg= $rowsm['sms_msg'];

function bind_to_template($replacements, $sms_msg) {
	return preg_replace_callback('/{{(.+?)}}/', function($matches) use ($replacements) {
		return $replacements[$matches[1]];
	}, $sms_msg);
}

if($pay_amount >= '0' && $z_id != '' && $e_id != ''){ 
	$opening_balance = $_POST['opening_balance'];
	$z_id = $_POST['z_id'];
	$entry_by = $_POST['entry_by'];
	$pay_date = $_POST['pay_date'];
	$pay_time = $_POST['pay_time'];
	$date_time = $_POST['date_time'];
	$e_id = $_POST['e_id'];
	$pay_amount = $_POST['pay_amount'];
	$discount = $_POST['discount'];
	$pay_mode = $_POST['pay_mode'];
	$agent_id = $_POST['agent_id'];
	$commission_sts = $_POST['commission_sts'];
	$com_percent = $_POST['com_percent'];
	$commission_amount = $_POST['commission_amount'];
	$note = $_POST['note'];
	$closing_balance =  $opening_balance + ($pay_amount + $discount);

	$sql1 = mysql_query("INSERT INTO payment_macreseller (e_id, z_id, bank, pay_date, pay_time, pay_mode, discount, pay_amount, opening_balance, closing_balance, entry_by, date_time, note, agent_id, commission_sts, com_percent, commission_amount) VALUES ('$e_id', '$z_id', '$bank', '$pay_date', '$pay_time', '$pay_mode', '$discount', '$pay_amount', '$opening_balance', '$closing_balance', '$entry_by', '$date_time', '$note', '$agent_id', '$commission_sts', '$com_percent', '$commission_amount')");

$sqlmqq = mysql_query("SELECT id AS mrno, e_id, z_id FROM payment_macreseller WHERE e_id = '$e_id' ORDER BY id DESC LIMIT 1");
		$rowmkqq = mysql_fetch_assoc($sqlmqq);
		$mrno = $rowmkqq['mrno'];

if($commission_sts == '1'){
	$sql1com = mysql_query("INSERT INTO agent_commission (payment_id, reseller_id, agent_id, z_id, com_percent, payment_amount, amount, bill_date, bill_time, entry_by) VALUES ('$mrno', '$e_id', '$agent_id', '$z_id', '$com_percent', '$pay_amount', '$commission_amount', '$pay_date', '$pay_time', '$entry_by')");
}
	
if($sentsms=='Yes'){
	
$from_page = 'Reseller Payment';
	
$replacements = array(
	'ResellerName' => $e_name,
	'PaymentAmount' => $pay_amount,
	'PaymentDiscount' => $discount,
	'PaymentMethod' => $pay_mode,
	'OpeningBalance' => $opening_balance,
	'ClosingBalance' => $closing_balance,
	'company_name' => $comp_name,
	'company_cell' => $company_cell
	);

$sms_body = bind_to_template($replacements, $sms_msg);
$c_idd = $e_id;
$send_by = $entry_by;
include('../web/include/smsapicore.php');
} ?>
<html><body>     <form action="MacResellerPayment?id=<?php echo $z_id; ?>" method="post" name="ok">       <input type="hidden" name="sts" value="add">     </form>     <script language="javascript" type="text/javascript">		document.ok.submit();     </script></body></html>
<?php

}
else{echo 'Wrong Data. Not possible to add.';}

mysql_close($con);
?>