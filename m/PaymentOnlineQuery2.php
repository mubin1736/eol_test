<?php
$gateway=$_GET['gateway'];
ini_alter('date.timezone','Asia/Almaty');
$date_time = date('Y-m-d H:i:s', time());

if($gateway == 'iPay'){
$titel = "Online Payment";
include('include/hader.php');
$wayyy=$_GET['wayyy'];
$referenceId=$_GET['referenceId'];
$dewamount=$_GET['dewamount'];
$amount=$_GET['amount'];

if($wayyy == 'reseller'){
	$ghhsghsg = 'MacResellerBillHistory';
	$MacResellerBillHistory = 'active';
}
else{
	$ghhsghsg = "ClientsBill";
	$ClientsBill = 'active';
}

$ipayyy=mysql_query("SELECT app_key FROM payment_online_setup WHERE id = '2'");
$rowipay=mysql_fetch_array($ipayyy);
$ipayapp_key=$rowipay['app_key'];

//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = '$ghhsghsg' AND $user_type = '1'");
if(mysql_num_rows($access) > 0){
//---------- Permission -----------

$url=curl_init('https://app.ipay.com.bd/api/pg/order/referenceId/'.$referenceId.'/status');
$header=array(
		'Content-Type:application/json','Authorization:Bearer '.$ipayapp_key);
		curl_setopt($url,CURLOPT_HTTPHEADER, $header);
		curl_setopt($url,CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($url,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($url,CURLOPT_FOLLOWLOCATION, 1);
		$resultdatax=curl_exec($url);
		curl_close($url); 
    
$arr = json_decode($resultdatax, true);
$statusCode = $arr["statusCode"];
$status = $arr["status"];
$orderId = $arr["orderId"];
$updateTime = $arr["updateTime"];
$trxID = $arr["transactionId"];
$createTime = $arr["transactionTime"];


if($wayyy == 'reseller'){
if($statusCode == '200'){

$reseller_id=$_GET['reseller_id'];

$query222 =mysql_query("SELECT z.z_id, z.z_name, z.e_id, COUNT(c.c_id) AS totalclients, e.e_name, e.e_cont_per, e.pre_address, e.agent_id, e.count_commission, e.com_percent FROM zone AS z
						LEFT JOIN emp_info AS e
						ON e.e_id = z.e_id
						LEFT JOIN clients AS c
						ON c.z_id = z.z_id
						WHERE e.e_id = '$reseller_id'");
$row22 = mysql_fetch_assoc($query222);

$agentt_id = $row22['agent_id'];
$count_commission = $row22['count_commission'];
$client_com_percent = $row22['com_percent'];

 if($agentt_id != '0'){ 
 $sqlf = mysql_query("SELECT e_id, e_name, com_percent, e_cont_per FROM agent WHERE sts = '0' AND e_id = '$agentt_id'");

$rowaa = mysql_fetch_assoc($sqlf);
		$agent_id= $rowaa['e_id'];
		$agent_name= $rowaa['e_name'];
		$com_percent= $rowaa['com_percent'];
		
		if($count_commission == '1'){
			if($client_com_percent != '0.00'){
				$comission = ($dewamount/100)*$client_com_percent;
			}
			else{
				$comission = ($dewamount/100)*$com_percent;
			}
		}
		else{
			$comission = '0.00';
		}
 }
?>
<html>
<body>
<h2>Payment Processing....</h2>
     <form action="MacResellerOnlinePaymentSuccess.php" method="post" name="ok">
	 <input type="hidden" value="<?php echo $reseller_id; ?>" name="pay_ent_by" />
				<input type="hidden" value="<?php echo date('Y-m-d', time());?>" name="pay_ent_date" />
				<input type="hidden" name="cell" value="<?php echo $row22['e_cont_per'];?>" />
				<input type="hidden" name="e_name" value="<?php echo $row22['e_name'];?>" />
				<input type="hidden" name="user_type" value="<?php echo $user_type;?>" />
				<?php if($agent_id != ''){?>
				<input type="hidden" name="agent_id" value="<?php echo $agent_id;?>" />
				<input type="hidden" name="commission_sts" value="<?php echo $count_commission;?>" />
				<input type="hidden" name="com_percent" value="<?php echo $com_percent;?>" />
				<input type="hidden" name="commission_amount" value="<?php echo $comission;?>" />
				 <?php } ?>
				<input type="hidden" name="pay_amount" value="<?php echo $dewamount;?>" />
				<input type="hidden" name="send_by" value="<?php echo $reseller_id;?>" />
				<input type="hidden" name="e_id" value="<?php echo $reseller_id;?>" />
				<input type="hidden" name="sentsms" value="Yes" />
				<input type="hidden" name="send_date" value="<?php echo date('Y-m-d', time());?>" />
				<input type="hidden" name="send_time" value="<?php echo date('H:i:s', time());?>" />
				<input type="hidden" name="pay_date" value="<?php echo date('Y-m-d', time());?>" />
				<input type="hidden" name="pay_time" value="<?php echo date('H:i:s', time());?>" />
				<input type="hidden" name="z_id" value="<?php echo $row22['z_id'];?>"/>
				<input type="hidden" name="bank" value="11">
				<input type="hidden" name="pay_mode" value="iPay">
				<input type="hidden" name="pay_way" value="online">
				
		<input type="hidden" name="date_time" value="<?php echo $date_time; ?>">
		<input type="hidden" name="paymentID" value="<?php echo $orderId; ?>">
		<input type="hidden" name="createTime" value="<?php echo $createTime; ?>">
		<input type="hidden" name="updateTime" value="<?php echo $createTime; ?>">
		<input type="hidden" name="trxID" value="<?php echo $trxID; ?>">
		<input type="hidden" name="transactionStatus" value="<?php echo $status; ?>">
		<input type="hidden" name="amount" value="<?php echo $amount; ?>">
		<input type="hidden" name="currency" value="BDT">
		<input type="hidden" name="intent" value="Bill">
		<input type="hidden" name="merchantInvoiceNumber" value="<?php echo $referenceId; ?>">
		<input type="hidden" name="refundAmount" value="0">
     </form>
     <script language="javascript" type="text/javascript">
		document.ok.submit();
     </script>
</body>
</html>

<?php
}else
{
    header("Location:MacResellerPayment?sts=faild");
}
}
else{
$result111 = mysql_query("SELECT c_id, c_name, cell, address, con_sts, b_date, payment_deadline, breseller FROM clients WHERE c_id = '$e_id'");
$row1111 = mysql_fetch_array($result111);

$client_id=$_GET['client_id'];
$query222 =mysql_query("SELECT agent_id, count_commission, com_percent FROM clients WHERE c_id = '$e_id'");
$row22 = mysql_fetch_assoc($query222);

$agentt_id = $row22['agent_id'];
$count_commission = $row22['count_commission'];
$client_com_percent = $row22['com_percent'];	

 if($agentt_id != '0'){ 
 $sqlf = mysql_query("SELECT e_id, e_name, com_percent, e_cont_per FROM agent WHERE sts = '0' AND e_id = '$agentt_id'");

$rowaa = mysql_fetch_assoc($sqlf);
		$agent_id= $rowaa['e_id'];
		$agent_name= $rowaa['e_name'];
		$com_percent= $rowaa['com_percent'];
		
		if($count_commission == '1'){
			if($client_com_percent != '0.00'){
				$comission = ($dewamount/100)*$client_com_percent;
				$percent_count = $client_com_percent;
			}
			else{
				$comission = ($dewamount/100)*$com_percent;
				$percent_count = $com_percent;
			}
		}
		else{
			$comission = '0.00';
		}
 }
if($statusCode == '200'){
?>
<html>
<body>
<h2>Payment Processing....</h2>
     <form action="PaymentOnlineSuccess?gateway=<?php echo $gateway;?>" method="post" name="ok">
				<input type="hidden" value="<?php echo $e_id; ?>" name="pay_ent_by" />
				<input type="hidden" value="<?php echo date('Y-m-d', time());?>" name="pay_ent_date" />
				<input type="hidden" name="pay_amount" value="<?php echo $dewamount;?>" />
				<input type="hidden" name="sentsms" value="Yes" />
				<input type="hidden" name="con_sts" value="<?php echo $row1111['con_sts']; ?>" />
				<?php if($agent_id != ''){?>
				<input type="hidden" name="agent_id" value="<?php echo $agent_id;?>" />
				<input type="hidden" name="commission_sts" value="<?php echo $count_commission;?>" />
				<input type="hidden" name="com_percent" value="<?php echo $percent_count;?>" />
				<input type="hidden" name="commission_amount" value="<?php echo $comission;?>" />
				 <?php } if($row1111['con_sts']== 'Inactive'){ ?>
				<input type="hidden" name="auto_bill_check" value="Yes" />
				<?php } else{ ?>
				<input type="hidden" name="auto_bill_check" value="No" />
				<?php } ?>
				<input type="hidden" name="breseller" value="<?php echo $row1111['breseller']; ?>" />
				<input type="hidden" name="bill_disc" value="0"/>
				
		<input type="hidden" name="c_id" value="<?php echo $e_id; ?>">
		<input type="hidden" name="date_time" value="<?php echo $date_time; ?>">
		<input type="hidden" name="paymentID" value="<?php echo $orderId; ?>">
		<input type="hidden" name="createTime" value="<?php echo $createTime; ?>">
		<input type="hidden" name="updateTime" value="<?php echo $createTime; ?>">
		<input type="hidden" name="trxID" value="<?php echo $trxID; ?>">
		<input type="hidden" name="transactionStatus" value="<?php echo $status; ?>">
		<input type="hidden" name="amount" value="<?php echo $amount; ?>">
		<input type="hidden" name="currency" value="BDT">
		<input type="hidden" name="intent" value="Bill">
		<input type="hidden" name="merchantInvoiceNumber" value="<?php echo $referenceId; ?>">
		<input type="hidden" name="refundAmount" value="0">
     </form>
     <script language="javascript" type="text/javascript">
		document.ok.submit();
     </script>
</body>
</html>
	
	
<?php }else
{
	$dfgdfgh = 'PaymentOnline?gateway='.$gateway.'&sts=faild';
    header("Location:$dfgdfgh");
}

}
}
else{
	include('index');
}
include('include/footer.php');
}

if($gateway == 'SSLCommerz'){
include("../web/conn/connection.php");
$sslll=mysql_query("SELECT password, store_id FROM payment_online_setup WHERE id = '3'");
$rowssl=mysql_fetch_array($sslll);
$sslstore_id=$rowssl['store_id'];
$sslpassword=$rowssl['password'];

$tran_id=urlencode($_POST['tran_id']);
$store_id=urlencode($sslstore_id);
$store_passwd=urlencode($sslpassword);
$requested_url = ("https://securepay.sslcommerz.com/validator/api/merchantTransIDvalidationAPI.php?tran_id=".$tran_id."&store_id=".$store_id."&store_passwd=".$store_passwd."&v=1&format=json");

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $requested_url);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false); # IF YOU RUN FROM LOCAL PC
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); # IF YOU RUN FROM LOCAL PC

$result = curl_exec($handle);

$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

$whether_give_the_service = false;

$t_status = "";

if($code == 200 && !( curl_errno($handle))) {

	# JSON CONVERT T
	$result = json_decode($result, true);
	if(isset($result['APIConnect']) && $result['APIConnect']=='DONE') {
		if(isset($result['element'])) {

			foreach($result['element'] as $t) {

				// var_dump($t);

				$tran_date = $t['tran_date'];

				$tran_id = $t['tran_id'];
				$val_id = $t['val_id'];
				$amount = $t['amount'];
				$ssl_amount = $t['store_amount'];
				$dewamount = $t['value_a'];
				$c_id = $t['value_b'];
				$invoiceno = $t['value_c'];
				$bank_gw = $t['bank_gw'];
				$createTime = $t['validated_on'];
				$card_type = $t['card_type'];
				$card_no = $t['card_no'];
				$card_issuer = $t['card_issuer'];
				$card_brand = $t['card_brand'];
				$card_issuer_country = $t['card_issuer_country'];

				$card_issuer_country_code = $t['card_issuer_country_code'];
				$status = $t['status'];
				$error = $t['error'];
				$risk_title = $t['risk_title'];
				$risk_level = $t['risk_level'];

				# TAKE LATEST STATUS
				$t_status = $status;
				
				if($status=='VALID') {	# CHECK CONDITIONS
					$t_status = "Successful Transaction, Please check AMOUNT with your System. ";
					if($risk_level=='1') {
						$t_status = "Payment is Risky";
					}
					break;

				} else if ($status=='VALIDATED') {
					$t_status = "Successful Transaction already validated by you, Please check AMOUNT with your System.";
					if($risk_level=='1') {
						$t_status = "Payment is Risky";
					}
					break;
				}
			}
		} else {	# NO SUCCESSFUL RECORD
			$t_status = "No Record Found";
		}
	} else {	# INVALID STORE ID AND PASSWORD
		$t_status = "API Connection Failed";
	}
} else {	# UNABLE TO CONNECT WITH SSLCOMMERZ
	$t_status = "Failed to connect with SSLCOMMERZ";
}

$result111 = mysql_query("SELECT c_id, c_name, cell, address, con_sts, b_date, payment_deadline, breseller FROM clients WHERE c_id = '$c_id'");
$row1111 = mysql_fetch_array($result111);

$query222 =mysql_query("SELECT agent_id, count_commission, com_percent FROM clients WHERE c_id = '$c_id' ");
$row22 = mysql_fetch_assoc($query222);

$agentt_id = $row22['agent_id'];
$count_commission = $row22['count_commission'];
$client_com_percent = $row22['com_percent'];	

 if($agentt_id != '0'){ 
 $sqlf = mysql_query("SELECT e_id, e_name, com_percent, e_cont_per FROM agent WHERE sts = '0' AND e_id = '$agentt_id'");

$rowaa = mysql_fetch_assoc($sqlf);
		$agent_id= $rowaa['e_id'];
		$agent_name= $rowaa['e_name'];
		$com_percent= $rowaa['com_percent'];
		
		if($count_commission == '1'){
			if($client_com_percent != '0.00'){
				$comission = ($dewamount/100)*$client_com_percent;
				$percent_count = $client_com_percent;
			}
			else{
				$comission = ($dewamount/100)*$com_percent;
				$percent_count = $com_percent;
			}
		}
		else{
			$comission = '0.00';
		}
 }

if($ssl_amount != ''){
?>

<html>
<body>
<h2>Payment Processing....</h2>
     <form action="PaymentOnlineSuccess?gateway=<?php echo $gateway;?>" method="post" name="ok">
				<input type="hidden" value="<?php echo $c_id; ?>" name="pay_ent_by" />
				<input type="hidden" value="<?php echo date('Y-m-d', time());?>" name="pay_ent_date" />
				<input type="hidden" name="pay_amount" value="<?php echo $dewamount;?>" />
				<input type="hidden" name="sentsms" value="Yes" />
				<input type="hidden" name="con_sts" value="<?php echo $row1111['con_sts']; ?>" />
				<?php if($agent_id != ''){?>
				<input type="hidden" name="agent_id" value="<?php echo $agent_id;?>" />
				<input type="hidden" name="commission_sts" value="<?php echo $count_commission;?>" />
				<input type="hidden" name="com_percent" value="<?php echo $percent_count;?>" />
				<input type="hidden" name="commission_amount" value="<?php echo $comission;?>" />
				 <?php } if($row1111['con_sts']== 'Inactive'){ ?>
				<input type="hidden" name="auto_bill_check" value="Yes" />
				<?php } else{ ?>
				<input type="hidden" name="auto_bill_check" value="No" />
				<?php } ?>
				<input type="hidden" name="breseller" value="<?php echo $row1111['breseller']; ?>" />
				<input type="hidden" name="bill_disc" value="0"/>
				
		<input type="hidden" name="c_id" value="<?php echo $row1111['c_id'];?>">
		<input type="hidden" name="date_time" value="<?php echo $date_time;?>">
		<input type="hidden" name="paymentID" value="<?php echo $val_id;?>">
		<input type="hidden" name="createTime" value="<?php echo $createTime;?>">
		<input type="hidden" name="updateTime" value="<?php echo $createTime;?>">
		<input type="hidden" name="tran_id" value="<?php echo $tran_id;?>">
		<input type="hidden" name="trxID" value="<?php echo $invoiceno;?>">
		<input type="hidden" name="transactionStatus" value="<?php echo $status;?>">
		<input type="hidden" name="amount" value="<?php echo $amount;?>">
		<input type="hidden" name="currency" value="BDT">
		<input type="hidden" name="intent" value="Bill">
		<input type="hidden" name="merchantInvoiceNumber" value="<?php echo $invoiceno;?>">
		<input type="hidden" name="card_type" value="<?php echo $card_type;?>">
		<input type="hidden" name="bank_gw" value="<?php echo $bank_gw;?>">
		<input type="hidden" name="card_no" value="<?php echo $card_no;?>">
		<input type="hidden" name="card_issuer" value="<?php echo $card_issuer;?>">
		<input type="hidden" name="card_brand" value="<?php echo $card_brand;?>">
		<input type="hidden" name="ssl_amount" value="<?php echo $ssl_amount;?>">
     </form>
     <script language="javascript" type="text/javascript">
		document.ok.submit();
     </script>
</body>
</html>
<?php
}else {
	$dfgdfgh = 'PaymentOnline?gateway='.$gateway.'&sts=faild';
    header("Location:$dfgdfgh");
	}
}
?>