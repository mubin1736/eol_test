<?php
include("../web/conn/connection.php");
include('../web/company_info.php');
include('../web/include/smsapi.php');
include('../web/include/telegramapi.php');
include("mk_api.php");
mysql_query("SET CHARACTER SET utf8");
mysql_query("SET SESSION collation_connection ='utf8_general_ci'");
extract($_POST);

function bind_to_template($replacements, $sms_msg) {
	return preg_replace_callback('/{{(.+?)}}/', function($matches) use ($replacements) {
		return $replacements[$matches[1]];
	}, $sms_msg);
}

$sqlsdf = mysql_query("SELECT sms_msg, from_page FROM sms_msg WHERE id= '5'");
$rowsm = mysql_fetch_assoc($sqlsdf);
$sms_msg= $rowsm['sms_msg'];
$from_page= $rowsm['from_page'];

$paymentam = (int) filter_var($payment, FILTER_SANITIZE_NUMBER_INT);
ini_alter('date.timezone','Asia/Almaty');

$update_date = date('Y-m-d', time());
$update_time = date('H:i:s', time());

if (empty($_POST['pay_date']) || empty($_POST['c_id']))
{}
else
{
	$c_id = $_POST['c_id'];
	$con_sts = $_POST['con_sts'];
	$pay_date = $_POST['pay_date'];
	$pay_mode = $_POST['pay_mtd'];
	$payment = $_POST['payment'];
	$bill_disc = $_POST['bill_disc'];
	$pay_desc = $_POST['pay_dsc'];
	$pay_ent_by = $_POST['pay_ent_by'];
	$pay_ent_date = $_POST['pay_ent_date'];
	$agent_id = $_POST['agent_id'];
	$commission_sts = $_POST['commission_sts'];
	$com_percent = $_POST['com_percent'];
	$commission_amount = $_POST['commission_amount'];
	
	if($mode == 'online'){
	$senderno = $_POST['sender_no'];
	$trxidd = $_POST['trxid'];
	
	$sqlmkkkk = mysql_query("SELECT bank FROM payment_mathod WHERE sts = 0 AND online = '1' AND name = '$pay_mode'");
	$rowmkkkk = mysql_fetch_array($sqlmkkkk);
	$onlinebank = $rowmkkkk['bank'];
	
		$sql1 = mysql_query("INSERT INTO payment (c_id, bank, pay_date, pay_amount, pay_mode, bill_discount, pay_desc, sender_no, trxid, pay_ent_date, pay_ent_by, agent_id, commission_sts, com_percent, commission_amount) VALUES ('$c_id', '$onlinebank', '$pay_date', '$payment', '$pay_mode', '$bill_disc', '$pay_desc', '$senderno', '$trxidd', '$pay_ent_date', '$pay_ent_by', '$agent_id', '$commission_sts', '$com_percent', '$commission_amount')");
	}
	else{
		$sql1 = mysql_query("INSERT INTO payment (c_id, bank, pay_date, pay_amount, pay_mode, bill_discount, pay_desc, moneyreceiptno, pay_ent_date, pay_ent_by, agent_id, commission_sts, com_percent, commission_amount) VALUES ('$c_id', '$bank', '$pay_date', '$payment', '$pay_mode', '$bill_disc', '$pay_desc', '$moneyreceiptno', '$pay_ent_date', '$pay_ent_by', '$agent_id', '$commission_sts', '$com_percent', '$commission_amount')");
	}
	
	$sqlmqq = mysql_query("SELECT p.id AS mrno, z.z_name, c.cell, c.z_id, c.c_name, p.bill_discount, e.e_name as pay_ent_byname, p.pay_ent_by, c.c_id FROM payment as p
								LEFT JOIN emp_info as e on e.e_id = p.pay_ent_by 
								LEFT JOIN clients as c on c.c_id = p.c_id 
								LEFT JOIN zone as z on z.z_id = c.z_id 
								WHERE p.c_id = '$c_id' ORDER BY p.id DESC LIMIT 1");
		$rowmkqq = mysql_fetch_assoc($sqlmqq);
		$c_idd = $rowmkqq['c_id'];
		$mrno = $rowmkqq['mrno'];
		$pay_ent_byname = $rowmkqq['pay_ent_byname'];
		$pay_ent_by = $rowmkqq['pay_ent_by'];
		$z_name = $rowmkqq['z_name'];
		$z_id = $rowmkqq['z_id'];
		$celll = $rowmkqq['cell'];
		$c_namee = $rowmkqq['c_name'];
		$billdiscount = $rowmkqq['bill_discount'];
		$tttee = $dew-$payment;
		
if($commission_sts == '1'){
	$sql1com = mysql_query("INSERT INTO agent_commission (payment_id, c_id, agent_id, z_id, com_percent, payment_amount, amount, bill_date, bill_time, entry_by) VALUES ('$mrno', '$c_id', '$agent_id', '$z_id', '$com_percent', '$payment', '$commission_amount', '$pay_ent_date', '$update_time', '$pay_ent_by')");
}
//TELEGRAM Start....
if($tele_sts == '0' && $tele_add_payment_sts == '0'){
$telete_way = 'payment_add';
$msg_body='..::[Payment Received]::..
'.$c_namee.' ['.$c_id.'] ['.$z_name.']

Amount: '.$payment.' TK
Discount: '.$billdiscount.' TK
Balance: '.$tttee.' TK

By: '.$pay_ent_byname.'
'.$tele_footer.'';

include('../web/include/telegramapicore.php');
}
//TELEGRAM END....		

if($con_sts == 'Inactive' && $auto_bill_check == 'Yes'){
	$sql2 = mysql_query("SELECT c.c_id, (b.bill-p.paid) AS due, c.mk_id FROM clients AS c
						LEFT JOIN
						(SELECT c_id, SUM(bill_amount) AS bill FROM billing GROUP BY c_id)b
						ON b.c_id = c.c_id
						LEFT JOIN
						(SELECT c_id, (SUM(pay_amount) + SUM(bill_discount)) AS paid FROM payment GROUP BY c_id)p
						ON p.c_id = c.c_id

						WHERE c.c_id = '$c_id'");
$rows = mysql_fetch_array($sql2);
$mk_id = $rows['mk_id'];
$Deww = $rows['due'];

$sql233 = mysql_query("SELECT p_price, bill_amount FROM billing WHERE c_id = '$c_id' ORDER BY id DESC LIMIT 1");
$rows33 = mysql_fetch_array($sql233);
$billamount = $rows33['bill_amount'];
$pppprice = $rows33['p_price'];
			
			
if($Deww <= '10' && $billamount != '0'){
	$sqlmk = mysql_query("SELECT id, ServerIP, Username, Pass, Port, e_Md, secret_h FROM mk_con WHERE sts = '0' AND id = '$mk_id'");
	$rowmk = mysql_fetch_array($sqlmk);
	$ServerIP = $rowmk['ServerIP'];
	$Username = $rowmk['Username'];
	$Pass= openssl_decrypt($rowmk['Pass'], $rowmk['e_Md'], $rowmk['secret_h']);
	$Port = $rowmk['Port'];
								
	$API = new routeros_api();
	$API->debug = false;
		if ($API->connect($ServerIP, $Username, $Pass, $Port)) {
		if($breseller == '1'){
				$arrID = $API->comm("/queue/simple/getall", 
				array(".proplist"=> ".id","?name" => $c_id,));

				$API->comm("/queue/simple/set",
				array(".id" => $arrID[0][".id"],"disabled"  => "no",));
		}
		else{
				$arrID =	$API->comm("/ppp/secret/getall", 
				array(".proplist"=> ".id","?name" => $c_id,));

				$API->comm("/ppp/secret/set",
				array(".id" => $arrID[0][".id"],"disabled"  => "no",));
		}
	$API->disconnect();
	}

	$queryq ="UPDATE clients SET con_sts = 'Active', con_sts_date = '$update_date' WHERE c_id = '$c_id'";
					if (!mysql_query($queryq))
					{
					die('Error: ' . mysql_error());
					}
	$query1q ="INSERT INTO con_sts_log (c_id, con_sts, update_date, update_time, update_by) VALUES ('$c_id', 'Active', '$update_date', '$update_time', '$pay_ent_by')";
					if (!mysql_query($query1q))
					{
					die('Error: ' . mysql_error());
					}
					
if($sentsms=='Yes'){
	
//$from_page = 'Bill Payment';
	
$res = mysql_query("SELECT l.amt, t.dic, t.pay FROM
					(
						SELECT c_id, SUM(bill_amount) AS amt FROM billing WHERE c_id = '$c_id'
					)l
					LEFT JOIN
					(
						SELECT c_id, SUM(pay_amount) AS pay, SUM(bill_discount) AS dic FROM payment WHERE c_id = '$c_id'
					)t
					ON l.c_id = t.c_id");

$rows = mysql_fetch_array($res);
$Dew = 	$rows['amt'] - ($rows['pay'] + $rows['dic']);

$replacements = array(
	'c_id' => $c_idd,
	'c_name' => $c_namee,
	'PaymentAmount' => $payment,
	'PaymentDiscount' => $bill_disc,
	'PaymentMethod' => $pay_mtd,
	'TotalDue' => $Dew,
	'MoneyreceiptNo' => $moneyreceiptno,
	'TrxId' => $trxidd,
	'company_name' => $comp_name,
	'company_cell' => $company_cell
	);

$sms_body = bind_to_template($replacements, $sms_msg);
include('../web/include/smsapicore.php');
}
}
else{
if($sentsms=='Yes'){
	
//$from_page = 'Bill Payment';
	
$res = mysql_query("SELECT l.amt, t.dic, t.pay FROM
					(
						SELECT c_id, SUM(bill_amount) AS amt FROM billing WHERE c_id = '$c_id'
					)l
					LEFT JOIN
					(
						SELECT c_id, SUM(pay_amount) AS pay, SUM(bill_discount) AS dic FROM payment WHERE c_id = '$c_id'
					)t
					ON l.c_id = t.c_id");

$rows = mysql_fetch_array($res);
$Dew = 	$rows['amt'] - ($rows['pay'] + $rows['dic']);

$replacements = array(
	'c_id' => $c_idd,
	'c_name' => $c_namee,
	'PaymentAmount' => $payment,
	'PaymentDiscount' => $bill_disc,
	'PaymentMethod' => $pay_mtd,
	'TotalDue' => $Dew,
	'MoneyreceiptNo' => $moneyreceiptno,
	'TrxId' => $trxidd,
	'company_name' => $comp_name,
	'company_cell' => $company_cell
	);

$sms_body = bind_to_template($replacements, $sms_msg);
include('../web/include/smsapicore.php');
}
}
}
else{
if($sentsms=='Yes'){
	
//$from_page = 'Bill Payment';
	
$res = mysql_query("SELECT l.amt, t.dic, t.pay FROM
					(
						SELECT c_id, SUM(bill_amount) AS amt FROM billing WHERE c_id = '$c_id'
					)l
					LEFT JOIN
					(
						SELECT c_id, SUM(pay_amount) AS pay, SUM(bill_discount) AS dic FROM payment WHERE c_id = '$c_id'
					)t
					ON l.c_id = t.c_id");

$rows = mysql_fetch_array($res);
$Dew = 	$rows['amt'] - ($rows['pay'] + $rows['dic']);

$replacements = array(
	'c_id' => $c_idd,
	'c_name' => $c_namee,
	'PaymentAmount' => $payment,
	'PaymentDiscount' => $bill_disc,
	'PaymentMethod' => $pay_mtd,
	'TotalDue' => $Dew,
	'MoneyreceiptNo' => $moneyreceiptno,
	'TrxId' => $trxidd,
	'company_name' => $comp_name,
	'company_cell' => $company_cell
	);

$sms_body = bind_to_template($replacements, $sms_msg);
include('../web/include/smsapicore.php');
}?>
<html>
<body>
     <form action="fpdf/MRPrint" method="post" name="cus_id">
       <input type="hidden" name="mrno" value="<?php echo $mrno; ?>">
     </form>
     <script language="javascript" type="text/javascript">
		document.cus_id.submit();
     </script>
</body>
</html>
<?php } ?>
<html>
<body>
     <form action="fpdf/MRPrint" method="post" name="cus_id">
       <input type="hidden" name="mrno" value="<?php echo $mrno; ?>">
     </form>
     <script language="javascript" type="text/javascript">
		document.cus_id.submit();
     </script>
</body>
</html>
<?php 
}
mysql_close($con);
?>