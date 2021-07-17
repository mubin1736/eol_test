<?php
$titel = "Online Payment";
$gateway=$_GET['gateway'];
$wayyy=$_GET['wayyy'];
extract($_POST);
ini_alter('date.timezone','Asia/Almaty');
$date_time = date('Y-m-d H:i:s', time());
include_once '../web/company_info.php';

//---------- bKash -----------
if($gateway == 'bKash' && in_array(1, $online_getway)){
include('include/hader.php');

$paymentID=$_GET['paymentID'];
$trxID=$_GET['trxID'];
$createTime=$_GET['createTime'];
$updateTime=$_GET['updateTime'];
$transactionStatus=$_GET['transactionStatus'];
$intent=$_GET['intent'];
$merchantInvoiceNumber=$_GET['merchantInvoiceNumber'];
$refundAmount=$_GET['refundAmount'];
$dewamount=$_GET['dewamount'];
$amount=$_GET['amount'];

if($wayyy == 'reseller'){
$MacResellerBillHistory = 'active';
$reseller_id = $e_id;
$query222 =mysql_query("SELECT agent_id, count_commission, com_percent, e_cont_per, z_id, e_name FROM emp_info WHERE e_id = '$reseller_id'");
$row22 = mysql_fetch_assoc($query222);

$agentt_id = $row22['agent_id'];
$count_commission = $row22['count_commission'];
$client_com_percent = $row22['com_percent'];

 if($agentt_id != '0'){ 
 $sqlf = mysql_query("SELECT e_id, e_name, com_percent FROM agent WHERE sts = '0' AND e_id = '$agentt_id'");

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
	//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'MacResellerBillHistory' AND $user_type = '1'"); 
if(mysql_num_rows($access) > 0){
//---------- Permission -----------
?>

<html>
<body>
<h2>Please Wait. Payment Processing....</h2>
     <form action="PaymentOnlineMacSuccess?gateway=<?php echo $gateway;?>" method="post" name="ok">
				<input type="hidden" value="<?php echo $reseller_id;?>" name="pay_ent_by" />
				<input type="hidden" value="<?php echo date('Y-m-d', time());?>" name="pay_ent_date" />
				<input type="hidden" name="cell" value="<?php echo $row22['e_cont_per'];?>" />
				<input type="hidden" name="e_name" value="<?php echo $row22['e_name'];?>" />
				<input type="hidden" name="z_id" value="<?php echo $row22['z_id'];?>"/>
				<input type="hidden" name="user_type" value="<?php echo $user_type;?>" />
				<input type="hidden" name="wayyy" value="<?php echo $wayyy;?>" />
				<?php if($agent_id != ''){?>
				<input type="hidden" name="agent_id" value="<?php echo $agent_id;?>" />
				<input type="hidden" name="commission_sts" value="<?php echo $count_commission;?>" />
				<input type="hidden" name="com_percent" value="<?php echo $percent_count;?>" />
				<input type="hidden" name="commission_amount" value="<?php echo $comission;?>" />
				 <?php } ?>
				<input type="hidden" name="pay_amount" value="<?php echo $dewamount;?>" />
				<input type="hidden" name="e_id" value="<?php echo $reseller_id;?>" />
				<input type="hidden" name="sentsms" value="Yes" />
				<input type="hidden" name="send_date" value="<?php echo date('Y-m-d', time());?>" />
				<input type="hidden" name="send_time" value="<?php echo date('H:i:s', time());?>" />
				<input type="hidden" name="pay_date" value="<?php echo date('Y-m-d', time());?>" />
				<input type="hidden" name="pay_time" value="<?php echo date('H:i:s', time());?>" />
				<input type="hidden" name="bill_disc" value="0"/>
				
		<input type="hidden" name="date_time" value="<?php echo $date_time; ?>">
		<input type="hidden" name="paymentID" value="<?php echo $paymentID; ?>">
		<input type="hidden" name="createTime" value="<?php echo $createTime; ?>">
		<input type="hidden" name="updateTime" value="<?php echo $updateTime; ?>">
		<input type="hidden" name="trxID" value="<?php echo $trxID; ?>">
		<input type="hidden" name="transactionStatus" value="<?php echo $transactionStatus; ?>">
		<input type="hidden" name="amount" value="<?php echo $amount; ?>">
		<input type="hidden" name="currency" value="<?php echo $currency; ?>">
		<input type="hidden" name="intent" value="<?php echo $intent; ?>">
		<input type="hidden" name="merchantInvoiceNumber" value="<?php echo $merchantInvoiceNumber; ?>">
		<input type="hidden" name="refundAmount" value="<?php echo $refundAmount; ?>">
     </form>
     <script language="javascript" type="text/javascript">
		document.ok.submit();
     </script>
</body>
</html>
<?php
}
else{
	include('include/index');
}
include('include/footer.php');
}
else{
$client_id = $e_id;
$query222 =mysql_query("SELECT c_id, c_name, cell, address, con_sts, b_date, payment_deadline, breseller, agent_id, count_commission, com_percent FROM clients WHERE c_id = '$client_id' ");
$row22 = mysql_fetch_assoc($query222);

$agentt_id = $row22['agent_id'];
$count_commission = $row22['count_commission'];
$client_com_percent = $row22['com_percent'];	

 if($agentt_id != '0'){ 
 $sqlf = mysql_query("SELECT e_id, e_name, com_percent FROM agent WHERE sts = '0' AND e_id = '$agentt_id'");

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
 
$ClientsBill = 'active';
//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'ClientsBill' AND $user_type = '1'");
if(mysql_num_rows($access) > 0){
//---------- Permission -----------
?>
<html>
<body>
<h2>Payment Processing....</h2>
     <form action="PaymentOnlineSuccess?gateway=<?php echo $gateway;?>&wayyy=<?php echo $wayyy;?>" method="post" name="ok">
				<input type="hidden" value="<?php echo $row22['c_id'];?>" name="pay_ent_by" />
				<input type="hidden" value="<?php echo date('Y-m-d', time());?>" name="pay_ent_date" />
				<input type="hidden" name="pay_amount" value="<?php echo $dewamount;?>" />
				<input type="hidden" name="wayyy" value="<?php echo $wayyy;?>" />
				<input type="hidden" name="sentsms" value="Yes" />
				<input type="hidden" name="con_sts" value="<?php echo $row22['con_sts'];?>" />
				<?php if($agent_id != ''){?>
				<input type="hidden" name="agent_id" value="<?php echo $agent_id;?>" />
				<input type="hidden" name="commission_sts" value="<?php echo $count_commission;?>" />
				<input type="hidden" name="com_percent" value="<?php echo $percent_count;?>" />
				<input type="hidden" name="commission_amount" value="<?php echo $comission;?>" />
				 <?php } if($row22['con_sts']== 'Inactive'){ ?>
				<input type="hidden" name="auto_bill_check" value="Yes" />
				<?php } else{ ?>
				<input type="hidden" name="auto_bill_check" value="No" />
				<?php } ?>
				<input type="hidden" name="breseller" value="<?php echo $row22['breseller'];?>" />
				<input type="hidden" name="bill_disc" value="0"/>
				
		<input type="hidden" name="c_id" value="<?php echo $row22['c_id'];?>">
		<input type="hidden" name="date_time" value="<?php echo $date_time;?>">
		<input type="hidden" name="paymentID" value="<?php echo $paymentID;?>">
		<input type="hidden" name="createTime" value="<?php echo $createTime;?>">
		<input type="hidden" name="updateTime" value="<?php echo $updateTime;?>">
		<input type="hidden" name="trxID" value="<?php echo $trxID;?>">
		<input type="hidden" name="transactionStatus" value="<?php echo $transactionStatus;?>">
		<input type="hidden" name="amount" value="<?php echo $amount;?>">
		<input type="hidden" name="currency" value="<?php echo $currency;?>">
		<input type="hidden" name="intent" value="<?php echo $intent;?>">
		<input type="hidden" name="merchantInvoiceNumber" value="<?php echo $merchantInvoiceNumber;?>">
		<input type="hidden" name="refundAmount" value="<?php echo $refundAmount;?>">
     </form>
     <script language="javascript" type="text/javascript">
		document.ok.submit();
     </script>
</body>
</html>

<?php
}
else{
	include('include/index');
}
include('include/footer.php');
}}

//---------- bKash END-----------
//---------- iPay Start -----------
if($gateway == 'iPay' && in_array(2, $online_getway)){

//---iPay
$ipayyy=mysql_query("SELECT app_key FROM payment_online_setup WHERE id = '2'");
$rowipay=mysql_fetch_array($ipayyy);
$ipayapp_key=$rowipay['app_key'];

if($wayyy == 'reseller'){
	$successCallbackUrll = $mlink.'PaymentOnlineQuery2?gateway='.$gateway.'&referenceId='.$invoice.$dewamount.'&wayyy='.$wayyy.'&reseller_id='.$reseller_id;
	$failureCallbackUrll = $mlink.'MacResellerPayment?gateway='.$gateway.'&sts=faild&wayyy='.$wayyy;
	$cancelCallbackUrll = $mlink.'MacResellerPayment?gateway='.$gateway.'&sts=canceled&wayyy='.$wayyy;
}else{
	$successCallbackUrll = $mlink.'PaymentOnlineQuery2.php?gateway='.$gateway.'&referenceId='.$invoice.$dewamount.'&wayyy='.$wayyy.'&client_id='.$c_id;
	$failureCallbackUrll = $mlink.'PaymentOnline?gateway='.$gateway.'&sts=faild&wayyy='.$wayyy;
	$cancelCallbackUrll = $mlink.'PaymentOnline?gateway='.$gateway.'&sts=canceled&wayyy='.$wayyy;
}
	$post_token=array(
		'amount'=>$payment_amount,
		'referenceId'=>$invoice,
		'description'=>$description,
		'successCallbackUrl'=>$successCallbackUrll,
		'failureCallbackUrl'=>$failureCallbackUrll,
		'cancelCallbackUrl'=>$cancelCallbackUrll
		);	
		   
		$url=curl_init('https://app.ipay.com.bd/api/pg/order');
		
		$posttoken=json_encode($post_token);
		$header=array(
		        'Content-Type:application/json','Authorization:Bearer '.$ipayapp_key);
				curl_setopt($url,CURLOPT_HTTPHEADER, $header);
				curl_setopt($url,CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($url,CURLOPT_RETURNTRANSFER, true);
				curl_setopt($url,CURLOPT_POSTFIELDS, $posttoken);
				curl_setopt($url,CURLOPT_FOLLOWLOCATION, 1);
				$resultdata=curl_exec($url);
				curl_close($url);
				
$arr = json_decode($resultdata, true);
$paymentUrl = $arr["paymentUrl"];

if($paymentUrl !=''){
    header("Location:$paymentUrl");
}
else
{
    header("Location:$failureCallbackUrll");
}
}

if($gateway == 'SSLCommerz' && in_array(3, $online_getway)){

//---SSLCommerz
$sslll=mysql_query("SELECT password, store_id FROM payment_online_setup WHERE id = '3'");
$rowssl=mysql_fetch_array($sslll);
$sslstore_id=$rowssl['store_id'];
$sslpassword=$rowssl['password'];

$post_data = array();
$post_data['store_id'] = $sslstore_id;
$post_data['store_passwd'] = $sslpassword;
$post_data['total_amount'] = $payment_amount;
$post_data['currency'] = "BDT";
$post_data['tran_id'] = "SSLCZ_".uniqid();

if($wayyy == 'reseller'){
	$post_data['success_url'] = $mlink.'PaymentOnlineQuery2?gateway='.$gateway.'&wayyy=reseller&reseller_id='.$reseller_id;
	$post_data['fail_url'] = $mlink.'PaymentOnline?gateway='.$gateway.'&sts=faild&dewamount='.$dewamount.'&wayyy=reseller';
	$post_data['cancel_url'] = $mlink.'PaymentOnline?gateway='.$gateway.'&sts=canceled&dewamount='.$dewamount.'&wayyy=reseller';
	$post_data['cus_name'] = $reseller_id;
	$post_data['value_b'] = $reseller_id;
}
else{
	$post_data['success_url'] = $mlink.'PaymentOnlineQuery2?gateway='.$gateway.'&wayyy=client&client_id='.$c_id;
	$post_data['fail_url'] = $mlink.'PaymentOnline?gateway='.$gateway.'&sts=faild&wayyy=client';
	$post_data['cancel_url'] = $mlink.'PaymentOnline?gateway='.$gateway.'&sts=canceled&wayyy=client';
	$post_data['cus_name'] = $c_id;
	$post_data['value_b'] = $c_id;
}
# $post_data['multi_card_name'] = "mastercard,visacard,amexcard";  # DISABLE TO DISPLAY ALL AVAILABLE

# EMI INFO
$post_data['emi_option'] = "1";
$post_data['emi_max_inst_option'] = "9";
$post_data['emi_selected_inst'] = "9";

# CUSTOMER INFORMATION
$post_data['cus_email'] = "test@test.com";
$post_data['cus_add1'] = "Dhaka";
$post_data['cus_add2'] = "Dhaka";
$post_data['cus_city'] = "Dhaka";
$post_data['cus_state'] = "Dhaka";
$post_data['cus_postcode'] = "1000";
$post_data['cus_country'] = "Bangladesh";
$post_data['cus_phone'] = $cell;
$post_data['cus_fax'] = $cell;

# SHIPMENT INFORMATION
$post_data['ship_name'] = "Bill Payment";
$post_data['ship_add1 '] = $address;
$post_data['ship_add2'] = "Dhaka";
$post_data['ship_city'] = "Dhaka";
$post_data['ship_state'] = "Dhaka";
$post_data['ship_postcode'] = "1000";
$post_data['ship_country'] = "Bangladesh";

# OPTIONAL PARAMETERS
$post_data['value_a'] = $dewamount;
$post_data['value_c'] = $invoice;
$post_data['value_d'] = "ref004";

# CART PARAMETERS
$post_data['product_amount'] = $dewamount;
$post_data['vat'] = "0.00";
$post_data['discount_amount'] = "0.00";
$post_data['convenience_fee'] = "0.00";

# REQUEST SEND TO SSLCOMMERZ
$direct_api_url = "https://securepay.sslcommerz.com/gwprocess/v4/api.php";

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $direct_api_url );
curl_setopt($handle, CURLOPT_TIMEOUT, 30);
curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($handle, CURLOPT_POST, 1 );
curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, true); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC


$content = curl_exec($handle );

$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

if($code == 200 && !( curl_errno($handle))) {
	curl_close( $handle);
	$sslcommerzResponse = $content;
} else {
	curl_close( $handle);
	echo "FAILED TO CONNECT WITH SSLCOMMERZ API";
	exit;
}

# PARSE THE JSON RESPONSE
$sslcz = json_decode($sslcommerzResponse, true );

if(isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL']!="" ) {
        # THERE ARE MANY WAYS TO REDIRECT - Javascript, Meta Tag or Php Header Redirect or Other
         echo "<script>window.location.href = '". $sslcz['GatewayPageURL'] ."';</script>";
#	echo "<meta http-equiv='refresh' content='0;url=".$sslcz['GatewayPageURL']."'>";
 #header("Location: ". $sslcz['GatewayPageURL']);
	exit;
} else {
	echo "JSON Data parsing error!";
}
	
}
?>