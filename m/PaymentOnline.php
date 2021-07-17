<?php
$titel = "Payment";
$ClientsBill = 'active';
include('include/hader.php');
$gateway=$_GET['gateway'];
$sts=$_GET['sts'];
extract($_POST); 
ini_alter('date.timezone','Asia/Almaty');
$y = date("Y");
$m = date("m");
$dat = $y.$m;

//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'ClientsBill' AND $user_type = '1'");
if(mysql_num_rows($access) > 0){
//---------- Permission -----------
if($gateway == 'bKash' && in_array(1, $online_getway)){
//---bKash

$error = $_GET['error'];
$bkkk=mysql_query("SELECT `id`,`getway_name`,`getway_name_details`, `merchant_number`, `app_key`,`app_secret`,`username`,`password`,`bank`,`charge`,`charge_sts`,`show_sts`,`sts` FROM payment_online_setup WHERE id = '1'");
$rowbk=mysql_fetch_array($bkkk);
$bkid=$rowbk['id'];
$bkgetway_name=$rowbk['getway_name'];
$bkgetway_name_details=$rowbk['getway_name_details'];
$bkmerchant_number=$rowbk['merchant_number'];
$bkapp_key=$rowbk['app_key'];
$bkapp_secret=$rowbk['app_secret'];
$bkusername=$rowbk['username'];
$bkpassword=$rowbk['password'];
$bkbank=$rowbk['bank'];
$bkcharge=$rowbk['charge'];
$bkcharge_sts=$rowbk['charge_sts'];
$bkshow_sts=$rowbk['show_sts'];
$bksts=$rowbk['sts'];

if($bkcharge_sts == '1'){
	$ext_charge = $bkcharge;
}
else{
	$ext_charge = '0.00';
}

$paygrant = 'ok';
$shortcode = 'BK-';
}

elseif($gateway == 'iPay' && in_array(2, $online_getway)){
//---iPay
$ipayyy=mysql_query("SELECT `id`,`getway_name`,`getway_name_details`, `merchant_number`,`app_key`,`bank`,`charge`,`charge_sts`,`show_sts`,`sts` FROM payment_online_setup WHERE id = '2'");
$rowipay=mysql_fetch_array($ipayyy);
$ipayid=$rowipay['id'];
$ipaygetway_name=$rowipay['getway_name'];
$ipaygetway_getway_name_details=$rowipay['getway_name_details'];
$ipaymerchant_number=$rowipay['merchant_number'];
$ipayapp_key=$rowipay['app_key'];
$ipaybank=$rowipay['bank'];
$ipaycharge=$rowipay['charge'];
$ipaycharge_sts=$rowipay['charge_sts'];
$ipayshow_sts=$rowipay['show_sts'];
$ipaysts=$rowipay['sts'];

if($ipaycharge_sts == '1'){
	$ext_charge = $ipaycharge;
}
else{
	$ext_charge = '0.00';
}

$paygrant = 'ok';
$shortcode = 'IPAY-';
}

elseif($gateway == 'SSLCommerz' && in_array(3, $online_getway)){
//---SSLCommerz
$sslll=mysql_query("SELECT `id`,`getway_name`,`getway_name_details`, `merchant_number`,`password`,`store_id`,`bank`,`charge`,`charge_sts`,`show_sts`,`sts` FROM payment_online_setup WHERE id = '3'");
$rowssl=mysql_fetch_array($sslll);
$sslid=$rowssl['id'];
$sslgetway_name=$rowssl['getway_name'];
$sslgetway_name_details=$rowssl['getway_name_details'];
$sslmerchant_number=$rowssl['merchant_number'];
$sslstore_id=$rowssl['store_id'];
$sslpassword=$rowssl['password'];
$sslbank=$rowssl['bank'];
$sslcharge=$rowssl['charge'];
$sslcharge_sts=$rowssl['charge_sts'];
$sslshow_sts=$rowssl['show_sts'];
$sslsts=$rowssl['sts'];

if($sslcharge_sts == '1'){
	$ext_charge = $sslcharge;
}
else{
	$ext_charge = '0.00';
}

$paygrant = 'ok';
$shortcode = 'SSL-';
}

elseif($gateway == 'Rocket' && in_array(4, $online_getway)){
//---Rocket
$rocketll=mysql_query("SELECT `id`,`getway_name`,`getway_name_details`, `merchant_number`,`password`,`store_id`,`bank`,`charge`,`charge_sts`,`show_sts`,`sts` FROM payment_online_setup WHERE id = '4'");
$rowrocket=mysql_fetch_array($rocketll);
$rocketid=$rowrocket['id'];
$rocketgetway_name=$rowrocket['getway_name'];
$rocketgetway_name_details=$rowrocket['getway_name_details'];
$rocketmerchant_number=$rowrocket['merchant_number'];
$rocketstore_id=$rowrocket['store_id'];
$rocketpassword=$rowrocket['password'];
$rocketbank=$rowrocket['bank'];
$rocketcharge=$rowrocket['charge'];
$rocketcharge_sts=$rowrocket['charge_sts'];
$rocketshow_sts=$rowrocket['show_sts'];
$rocketsts=$rowrocket['sts'];

if($rocketcharge_sts == '1'){
	$ext_charge = $rocketcharge;
}
else{
	$ext_charge = '0.00';
}

$paygrant = 'ok';
$shortcode = 'RKT-';
}

elseif($gateway == 'Nagad' && in_array(5, $online_getway)){
//---Nagad
$nagadll=mysql_query("SELECT `id`,`getway_name`,`getway_name_details`, `merchant_number`,`password`,`store_id`,`bank`,`charge`,`charge_sts`,`show_sts`,`sts` FROM payment_online_setup WHERE id = '5'");
$rownagad=mysql_fetch_array($nagadll);
$nagadid=$rownagad['id'];
$nagadgetway_name=$rownagad['getway_name'];
$nagadgetway_name_details=$rownagad['getway_name_details'];
$nagadmerchant_number=$rownagad['merchant_number'];
$nagadstore_id=$rownagad['store_id'];
$nagadpassword=$rownagad['password'];
$nagadbank=$rownagad['bank'];
$nagadcharge=$rownagad['charge'];
$nagadcharge_sts=$rownagad['charge_sts'];
$nagadshow_sts=$rownagad['show_sts'];
$nagadsts=$rownagad['sts'];

if($nagadcharge_sts == '1'){
	$ext_charge = $nagadcharge;
}
else{
	$ext_charge = '0.00';
}

$paygrant = 'ok';
$shortcode = 'NGD-';
}
else{
$paygrant = 'no';
$shortcode = '';
}

if($paygrant == 'ok'){
	if($wayyy == 'reseller'){
		$Dew = $dewamount;
		$pytk = ($Dew*$ext_charge)/100;
		$payment_amount = $Dew + $pytk;
		
$respy = mysql_query("SELECT `id` FROM `payment_macreseller` ORDER BY id DESC LIMIT 1");
$rowspy = mysql_fetch_array($respy);
	if($rowspy['id'] == ''){
			$invo_no = $shortcode.$dat.'10';
		}
		else{
			$new = $rowspy['id'] + 10;
			$invo_no = $shortcode.$dat.$new;
		}

$result = mysql_query("SELECT e.e_id AS resellerid, e.e_name AS reseller_name, e.e_cont_per AS reseller_cell, z.z_name AS reseller_zone FROM emp_info AS e LEFT JOIN zone AS z ON z.z_id = e.z_id WHERE e.e_id = '$e_id'");
$row = mysql_fetch_array($result);
	}
	else{
$resss = mysql_query("SELECT c_id FROM payment WHERE c_id = '$e_id'");
$rowsss = mysql_fetch_array($resss);
$c_idd = $rowsss['c_id'];

if($c_idd == '')
{
	$res = mysql_query("SELECT c_id, SUM(bill_amount) AS amt FROM billing WHERE c_id = '$e_id'");
	$rows = mysql_fetch_array($res);
	$pay = $rows['amt'];
	$Dew = $rows['amt'];
}

else{
$res = mysql_query("SELECT l.amt, t.dic, t.pay FROM
					(
						SELECT c_id, SUM(bill_amount) AS amt FROM billing WHERE c_id = '$e_id'
					)l
					LEFT JOIN
					(
						SELECT c_id, SUM(pay_amount) AS pay, SUM(bill_discount) AS dic FROM payment WHERE c_id = '$e_id'
					)t
					ON l.c_id = t.c_id");

$rows = mysql_fetch_array($res);
$Dew = 	$rows['amt'] - ($rows['pay'] + $rows['dic']);

if($Dew <= 0){
	
	$pay = 'Alrady Paid';
}else{
	$pay = $Dew;
}
}

$pytk = ($Dew*$ext_charge)/100;

$payment_amount = $Dew + $pytk;

$respy = mysql_query("SELECT `id` FROM `payment` ORDER BY id DESC LIMIT 1");
$rowspy = mysql_fetch_array($respy);
	if($rowspy['id'] == ''){
			$invo_no = $shortcode.$dat.'1';
		}
		else{
			$new = $rowspy['id'] + 25;
			$invo_no = $shortcode.$dat.$new;
		}

	
$result = mysql_query("SELECT c_id, c_name, cell, address, con_sts, b_date, payment_deadline, breseller FROM clients WHERE c_id = '$e_id'");
$row = mysql_fetch_array($result);	
}}
?>

<?php if($gateway == 'bKash' && in_array(1, $online_getway)){ ?>
	<script src="https://scripts.pay.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout.js"></script>
    <meta name="viewport" content="width=device-width" , initial-scale=1.0/>
<?php } else {} ?>

	<?php if($sts == 'faild'){?>
			<div class="alert alert-error">
				<button data-dismiss="alert" class="close" type="button">&times;</button>
				<strong>Payment has been Faild!!</strong> Please try again.
			</div><!--alert-->
	<?php } if($sts == 'canceled'){?>
			<div class="alert alert-error">
				<button data-dismiss="alert" class="close" type="button">&times;</button>
				<strong>Payment has been canceled!!</strong>.
			</div><!--alert-->
	<?php } if($sts == 'success'){?>
			<div class="alert alert-error">
				<button data-dismiss="alert" class="close" type="button">&times;</button>
				<strong>Payment Successful!!</strong> Thanks for your payment.
			</div><!--alert-->
	<?php } ?>
		<div class="box box-primary">
			<div class="modal-content">
				<div class="modal-body">
				<?php if($gateway == 'bKash' && in_array(1, $online_getway)){ ?>
						<div class="row">
							<table class="table table-bordered table-invoice" style="width: 100%;font-family: gorgia;">
										<tr>
											<td class="width30" style="font-size: 10px;font-weight: bold;text-align: right;">Invoice No</td>
											<td class="width70"><strong style="font-size: 10px;font-weight: bold;font-family: gorgia;"><?php echo $invo_no;?></strong></td>
										</tr>
										<tr>
											<td style="font-size: 13px;font-weight: bold;text-align: right;">PPoE User</td>
											<td style="font-size: 13px;"><?php if($wayyy == 'reseller'){echo $row['reseller_name'].' | '.$row['resellerid'].' | '.$row['reseller_cell'].' | '.$row['reseller_zone'];} else{echo $row['c_name'].' | '.$row['c_id'].' | '.$row['cell'].' | '.$row['address'];}?></td>
										</tr>
										<tr>
											<td style="font-size: 10px;font-weight: bold;text-align: right;">Due Amount</td>
											<td style="font-size: 10px;font-weight: bold;">৳ <?php echo number_format($Dew,2); ?></td>
										</tr>
										<tr>
											<td style="font-size: 10px;font-weight: bold;text-align: right;">Service Charge</td>
											<td style="font-size: 10px;font-weight: bold;">৳ <?php echo number_format($pytk,2); ?></td>
										</tr>
										<tr>
											<td style="font-size: 10px;font-weight: bold;text-align: right;">Payable</td>
											<td style="font-size: 14px;font-weight: bold;color: firebrick;">৳ <?php echo number_format($payment_amount,2); ?></td>
										</tr>
							</table>
						</div>
						<div class="modal-footer">
						<?php if($Dew < '9'){?> <h3>You have not any due.</h3> <?php } else{ ?>
						<button class="btn btn-primary" id="bKash_button" type="submit">Pay With <?php echo $gateway;?></button>
						<?php } ?>
						</div>
				<?php } elseif($gateway == 'iPay' && in_array(2, $online_getway)){ ?>
					<form name="form1" class="stdform" method="post" action="PaymentOnlineQuery?gateway=<?php echo $gateway;?>" enctype="multipart/form-data">
					<input type="hidden" name="invoice" value="<?php echo $invo_no;?>">
					<input type="hidden" name="wayyy" value="<?php echo $wayyy;?>">
					<?php if($wayyy == 'reseller'){?>
					<input type ="hidden" name="reseller_id" value="<?php echo $e_id; ?>">
					<?php } else{?>
					<input type ="hidden" name="c_id" value="<?php echo $row['c_id']; ?>">
					<?php } ?>
					<input type ="hidden" name="description" value="Internet Bill Pay">
					<input type ="hidden" name="payment_amount" value="<?php echo $payment_amount;?>">
					<input type ="hidden" name="dewamount" value="<?php echo '&dewamount='.$Dew.'&amount='.$payment_amount;?>">
						<div class="row">
							<table class="table table-bordered table-invoice" style="width: 100%;font-family: gorgia;">
										<tr>
											<td class="width30" style="font-size: 15px;font-weight: bold;text-align: right;">Invoice No</td>
											<td class="width70"><strong style="font-size: 15px;font-weight: bold;font-family: gorgia;"><?php echo $invo_no;?></strong></td>
										</tr>
										<tr>
											<td style="font-size: 13px;font-weight: bold;text-align: right;">PPoE User</td>
											<td style="font-size: 13px;"><?php if($wayyy == 'reseller'){echo $row['reseller_name'].' | '.$row['resellerid'].' | '.$row['reseller_cell'].' | '.$row['reseller_zone'];} else{echo $row['c_name'].' | '.$row['c_id'].' | '.$row['cell'].' | '.$row['address'];}?></td>
										</tr>
										<tr>
											<td style="font-size: 13px;font-weight: bold;text-align: right;">Due Amount</td>
											<td style="font-size: 13px;font-weight: bold;">৳ <?php echo number_format($Dew,2); ?></td>
										</tr>
										<tr>
											<td style="font-size: 13px;font-weight: bold;text-align: right;">Service Charge</td>
											<td style="font-size: 13px;font-weight: bold;">৳ <?php echo number_format($pytk,2); ?></td>
										</tr>
										<tr>
											<td style="font-size: 17px;font-weight: bold;text-align: right;">Total Payment Amount</td>
											<td style="font-size: 17px;font-weight: bold;color: firebrick;">৳ <?php echo number_format($payment_amount,2); ?></td>
										</tr>
							</table>
						</div>
						<div class="modal-footer">
						<?php if($Dew < '9'){?> <h3>You hant any due.</h3> <?php } else{ ?>
							<button class="btn btn-primary" type="submit">Pay With <?php echo $gateway;?></button>
						<?php } ?>
						</div>
					</form>
				<?php } elseif($gateway == 'SSLCommerz' && in_array(3, $online_getway)){ ?>
					<form name="form1" class="stdform" method="post" action="PaymentOnlineQuery?gateway=<?php echo $gateway;?>" enctype="multipart/form-data">
						<input type="hidden" name="invoice" value="<?php echo $invo_no;?>">
						<input type="hidden" name="wayyy" value="<?php echo $wayyy;?>">
						
						<?php if($wayyy == 'reseller'){?>
						<input type ="hidden" name="reseller_id" value="<?php echo $e_id; ?>">
						<input type ="hidden" name="cell" value="<?php echo $row['reseller_cell']; ?>">
						<input type="hidden" name="address" value="<?php echo $row['reseller_zone']; ?>">
						<?php } else{?>
						<input type ="hidden" name="c_id" value="<?php echo $row['c_id'];?>">
						<input type="hidden" name="cell" value="<?php echo $row['cell'];?>">
						<input type="hidden" name="address" value="<?php echo $row['address']; ?>">
						<?php } ?>
						
						<input type="hidden" name="description" value="Internet Bill Pay">
						<input type="hidden" name="payment_amount" value="<?php echo $payment_amount;?>">
						<input type="hidden" name="dewamount" value="<?php echo $Dew;?>">
						<input type="hidden" name="amount" value="<?php echo $payment_amount;?>">
							<div class="row">
								<table class="table table-bordered table-invoice" style="width: 100%;font-family: gorgia;">
											<tr>
												<td class="width30" style="font-size: 15px;font-weight: bold;text-align: right;">Invoice No</td>
												<td class="width70"><strong style="font-size: 15px;font-weight: bold;font-family: gorgia;"><?php echo $invo_no;?></strong></td>
											</tr>
											<tr>
												<td style="font-size: 13px;font-weight: bold;text-align: right;">PPoE User</td>
												<td style="font-size: 13px;"><?php if($wayyy == 'reseller'){echo $row['reseller_name'].' | '.$row['resellerid'].' | '.$row['reseller_cell'].' | '.$row['reseller_zone'];} else{echo $row['c_name'].' | '.$row['c_id'].' | '.$row['cell'].' | '.$row['address'];}?></td>
											</tr>
											<tr>
												<td style="font-size: 13px;font-weight: bold;text-align: right;">Due Amount</td>
												<td style="font-size: 13px;font-weight: bold;">৳ <?php echo number_format($Dew,2); ?></td>
											</tr>
											<tr>
												<td style="font-size: 13px;font-weight: bold;text-align: right;">Service Charge</td>
												<td style="font-size: 13px;font-weight: bold;">৳ <?php echo number_format($pytk,2); ?></td>
											</tr>
											<tr>
												<td style="font-size: 17px;font-weight: bold;text-align: right;">Total Payment Amount</td>
												<td style="font-size: 17px;font-weight: bold;color: firebrick;">৳ <?php echo number_format($payment_amount,2); ?></td>
											</tr>
								</table>
							</div>
						<div class="modal-footer">
						<?php if($Dew < '9'){?> <h3>You have not any due.</h3> <?php } else{ ?>
						<button class="btn btn-primary" type="submit">Pay With <?php echo $gateway;?></button>
						<?php } ?>
						</div>
					</form>
				<?php } else{}?>
			</div>
		</div>
	</div>
<?php
}
else{
	include('include/index');
}
include('include/footer.php');
if($gateway == 'bKash' && in_array(1, $online_getway)){
?>
<code class="language-javascript">
<script type="text/javascript">
    var accessToken='';
    $(document).ready(function(){
        $.ajax({
            url: "token.php",
            type: 'POST',
            contentType: 'application/json',
            success: function (data) {
                console.log('got data from token  ..');
				console.log(JSON.stringify(data));
				
                accessToken=JSON.stringify(data);
            },
			error: function(){
						console.log('error');
                        
            }
        });

        var paymentConfig={
            createCheckoutURL:"createpayment.php",
            executeCheckoutURL:"executepayment.php",
        };

		
        var paymentRequest;
        paymentRequest = { amount: <?php echo number_format($payment_amount, 2, '.', ''); ?>, intent: 'bill' };
		console.log(JSON.stringify(paymentRequest));

        bKash.init({
            paymentMode: 'checkout',
            paymentRequest: paymentRequest,
            createRequest: function(request){
                console.log('=> createRequest (request) :: ');
                console.log(request);
                
                $.ajax({
                    url: paymentConfig.createCheckoutURL+"?amount="+paymentRequest.amount,
                    type:'GET',
                    contentType: 'application/json',
                    success: function(data) {
                        console.log('got data from create  ..');
                        console.log('data ::=>');
                        console.log(JSON.stringify(data));
                        
                        var obj = JSON.parse(data);
                        
                        if(data && obj.paymentID != null){
                            paymentID = obj.paymentID;
                            bKash.create().onSuccess(obj);
                        }
                        else {
							console.log('error');
                            bKash.create().onError();
                        }
                    },
                    error: function(){
						console.log('error');
                        bKash.create().onError();
                    }
                });
            },
            
            executeRequestOnAuthorization: function(){
                console.log('=> executeRequestOnAuthorization');
                $.ajax({
                    url: paymentConfig.executeCheckoutURL+"?paymentID="+paymentID,
                    type: 'GET',
                    contentType:'application/json',
                    success: function(data){
                        console.log('got data from execute  ..');
                        console.log('data ::=>');
                        console.log(JSON.stringify(data));
                        
                        data = JSON.parse(data);

                        if(data && data.paymentID != null){
                            window.location.href = "PaymentOnlineQuery.php?paymentID="+data.paymentID+"&trxID="+data.trxID+"&createTime="+data.createTime+"&updateTime="+data.updateTime+"&transactionStatus="+data.transactionStatus+"&intent="+data.intent+"&merchantInvoiceNumber="+data.merchantInvoiceNumber+"&amount="+data.amount+"&gateway=bKash&wayyy=client&dewamount="+<?php echo $Dew;?>;
                        }
                        else {
							alert(data.errorMessage);
                            window.location.href = "PaymentOnline?error="+data.errorMessage;
                        }
                    },
                    error: function(){
                        bKash.execute().onError();
                    }
                });
            }
        });
		console.log("Right after init ");
    });
	
	function callReconfigure(val){
        bKash.reconfigure(val);
    }

    function clickPayButton(){
        $("#bKash_button").trigger('click');
    }
</script>
</code>
<?php } else{}?>