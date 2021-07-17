<?php
include("../web/conn/connection.php");
include('../web/company_info.php');
//include('../web/include/smsapi.php');
extract($_POST);

$sql88 = ("SELECT id, link, username, password, status FROM sms_setup WHERE status = '0' AND z_id = '$macz_id'");
		
$query88 = mysql_query($sql88);
$row88 = mysql_fetch_assoc($query88);
		$link= $row88['link'];
		$username= $row88['username'];
		$password= $row88['password'];

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
	
	if($mode == 'online'){
	$senderno = $_POST['sender_no'];
	$trxidd = $_POST['trxid'];
	
		$sql1 = mysql_query("INSERT INTO payment_mac_client (c_id, pay_date, pay_amount, pay_mode, bill_discount, pay_desc, sender_no, trxid, pay_ent_date, pay_ent_by) VALUES ('$c_id', '$pay_date', '$payment', '$pay_mode', '$bill_disc', '$pay_desc', '$senderno', '$trxidd', '$pay_ent_date', '$pay_ent_by')");
	}
	else{
		$sql1 = mysql_query("INSERT INTO payment_mac_client (c_id, pay_date, pay_amount, pay_mode, bill_discount, pay_desc, moneyreceiptno, pay_ent_date, pay_ent_by) VALUES ('$c_id', '$pay_date', '$payment', '$pay_mode', '$bill_disc', '$pay_desc', '$moneyreceiptno', '$pay_ent_date', '$pay_ent_by')");
	}
	
		$sqlmqq = mysql_query("SELECT id AS mrno FROM payment_mac_client WHERE c_id = '$c_id' ORDER BY id DESC LIMIT 1");
		$rowmkqq = mysql_fetch_assoc($sqlmqq);
		$mrno = $rowmkqq['mrno'];

if($sentsms=='Yes'){
	
$from_page = 'Bill Payment';
	
$res = mysql_query("SELECT l.amt, t.dic, t.pay FROM
					(
						SELECT c_id, SUM(bill_amount) AS amt FROM billing_mac_client WHERE c_id = '$c_id'
					)l
					LEFT JOIN
					(
						SELECT c_id, SUM(pay_amount) AS pay, SUM(bill_discount) AS dic FROM payment_mac_client WHERE c_id = '$c_id'
					)t
					ON l.c_id = t.c_id");

$rows = mysql_fetch_array($res);
$Dew = 	$rows['amt'] - ($rows['pay'] + $rows['dic']);

$sms_body = 'Dear '.$c_id.',
Your Payment '.$payment.' TK has been received by '.$pay_mtd.'.
Discount: '.$bill_disc.' TK.
Total Due: '.$Dew.'TK.
MR/Trxid: '.$moneyreceiptno.''.$trxidd.'

'.$sms_footer.'';

$fileURL2 = urlencode($sms_body);
$full_link= $link.$username.'&type=text&contacts='.$cell.'&senderid='.$password.'&msg='.$fileURL2;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$full_link); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable 
curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
$result = curl_exec($ch); 
curl_close($ch);

//$query2 ="insert into sms_send (c_id , c_cell, sms_body, send_by, send_date, send_time, api_id, from_page) VALUES ('$c_id', '$cell', '$sms_body', '$send_by', '$send_date', '$send_time', '$result', '$from_page')";
//$result2 = mysql_query($query2) or die("inser_query failed: " . mysql_error() . "<br />");
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
<?php 
}
mysql_close($con);
?>