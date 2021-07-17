<?php
$titel = "Payment";
$Billing = 'active';
include('include/hader.php');
 
$id = $_GET['id'];
extract($_POST);
ini_alter('date.timezone','Asia/Almaty');

//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'Billing' AND $user_type = '1'");
$access1 = mysql_query("SELECT * FROM module_page WHERE parent_id = '11' AND $user_type = '1'");
if(mysql_num_rows($access) > 0 && mysql_num_rows($access1) > 0){
//---------- Permission -----------

$result = mysql_query("SELECT c_id, c_name, cell, address, con_sts, b_date, payment_deadline, breseller, mac_user FROM clients WHERE c_id = '$id'");
$row = mysql_fetch_array($result);	

if($row['mac_user'] == '0'){
$resss = mysql_query("SELECT c_id FROM payment WHERE c_id = '$id'");
$rowsss = mysql_fetch_array($resss);
$c_idd = $rowsss['c_id'];

if($c_idd == '')
{
	$res = mysql_query("SELECT c_id, SUM(bill_amount) AS amt FROM billing WHERE c_id = '$id'");
	$rows = mysql_fetch_array($res);
	$pay = $rows['amt'];
	$Dew = $rows['amt'];
}

else{
$res = mysql_query("SELECT l.amt, t.dic, t.pay FROM
					(
						SELECT c_id, SUM(bill_amount) AS amt FROM billing WHERE c_id = '$id'
					)l
					LEFT JOIN
					(
						SELECT c_id, SUM(pay_amount) AS pay, SUM(bill_discount) AS dic FROM payment WHERE c_id = '$id'
					)t
					ON l.c_id = t.c_id");

$rows = mysql_fetch_array($res);
$Dew = 	$rows['amt'] - ($rows['pay'] + $rows['dic']);

if($Dew <= '0.99'){
	
	$pay = 'Alrady Paid';
}else{
	$pay = $Dew;
}
}

$resultsfsf = mysql_query("SELECT c_id, c_name, cell, address, con_sts, b_date, payment_deadline, breseller, agent_id, count_commission, com_percent FROM clients WHERE c_id = '$id'");
$rowsfsf = mysql_fetch_array($resultsfsf);	

$agentt_id = $rowsfsf['agent_id'];
$count_commission = $rowsfsf['count_commission'];
$client_com_percent = $rowsfsf['com_percent'];

 if($agentt_id != '0'){
 $sqlf = mysql_query("SELECT e_id, e_name, com_percent, e_cont_per FROM agent WHERE sts = '0' AND e_id = '$agentt_id'");

$rowaa = mysql_fetch_assoc($sqlf);
		$agent_id= $rowaa['e_id'];
		$agent_name= $rowaa['e_name'];
		$com_percent= $rowaa['com_percent'];
		$e_cont_per= $rowaa['e_cont_per'];
		
		if($count_commission == '1'){
			if($client_com_percent != '0.00'){
				$comission = ($pay/100)*$client_com_percent;
			}
			else{
				$comission = ($pay/100)*$com_percent;
			}
		}
		else{
			$comission = '0.00';
		}
 }
 else{}
}
else{
	
$resss = mysql_query("SELECT c_id FROM payment_mac_client WHERE c_id = '$id'");
$rowsss = mysql_fetch_array($resss);
$c_idd = $rowsss['c_id'];

if($c_idd == '')
{
	$res = mysql_query("SELECT c_id, SUM(bill_amount) AS amt FROM billing_mac_client WHERE c_id = '$id'");
	$rows = mysql_fetch_array($res);
	$pay = $rows['amt'];
	$Dew = $rows['amt'];
}

else{
$res = mysql_query("SELECT l.amt, t.dic, t.pay FROM
					(
						SELECT c_id, SUM(bill_amount) AS amt FROM billing_mac_client WHERE c_id = '$id'
					)l
					LEFT JOIN
					(
						SELECT c_id, SUM(pay_amount) AS pay, SUM(bill_discount) AS dic FROM payment_mac_client WHERE c_id = '$id'
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

}


if($user_type == 'admin' || $user_type == 'superadmin'){
	$Bank = mysql_query("SELECT * FROM bank WHERE sts = 0 ORDER BY bank_name");
}
else{
	$Bank = mysql_query("SELECT * FROM bank WHERE sts = 0 AND emp_id = '$e_id' ORDER BY bank_name");
}							
	$mathod = mysql_query("SELECT * FROM payment_mathod WHERE sts = 0 AND online = '0' ORDER BY name");
	
if($client_com_percent != '0.00'){ ?>
<script type="text/javascript">
function updatesum() {
document.form1.commission_amount.value = ((document.form1.payment.value -0)/100)*<?php echo $client_com_percent;?>;
}
</script>
<?php } else{?>
<script type="text/javascript">
function updatesum() {
document.form1.commission_amount.value = ((document.form1.payment.value -0)/100)*<?php echo $com_percent;?>;
}
</script>
<?php } ?>
	<div class="box box-primary">
		<div class="box-header">
			<div class="modal-content">
				<div class="modal-header">
					<h5>Add Payment</h5>
				</div>
				<form id="" name="form1" class="stdform" method="post" action="<?php if($row['mac_user'] == '0'){ ?>PaymentSave<?php } else{ ?>PaymentMacSave<?php } ?>">
				<input type="hidden" value="<?php echo $_SESSION['SESS_EMP_ID']; ?>" name="pay_ent_by" />
				<input type="hidden" value="<?php echo date('Y-m-d', time());?>" name="pay_ent_date" />
				<input type="hidden" name="cell" value="<?php echo $row['cell'];?>" />
				<input type="hidden" name="send_by" value="<?php echo $e_id;?>" />
				<input type="hidden" name="con_sts" value="<?php echo $row['con_sts']; ?>" />
				<input type="hidden" name="pay_mtd" value="CASH" />
				<input type="hidden" name="breseller" value="<?php echo $row['breseller']; ?>" />
				<input type="hidden" name="send_date" value="<?php echo date('Y-m-d', time());?>" />
				<input type="hidden" name="send_time" value="<?php echo date('H:i:s', time());?>" />
				<input type="hidden" name="pay_date" value="<?php echo date('Y-m-d');?>" />
					<div class="modal-body">
						<p>	
							<label>Client Info</label>
							<span class="field">
								<input type ="hidden" name="c_id" value="<?php echo $row['c_id']; ?>">
								<input type="text" name="" id="" class="input-xxlarge" value="<?php echo $row['c_id'].' | '.$row['cell'];?>" readonly />
							</span>
						</p>
						<?php if($row['mac_user'] == '1'){ ?>
						<input type="hidden" name="macz_id" value="<?php echo $macz_id; ?>" />
						<p>	
							<label>Payment Method</label>
							<span class="field"><select data-placeholder="Payment Method" name="pay_mtd" style="width:280px;" class="chzn-select">
								<option value="CASH">Cash</option>
								<option value="bKash">bKash</option>
								<option value="Rocket">Rocket</option>
								<option value="iPay">iPay</option>
								<option value="Card">Card</option>
								<option value="Bank">Bank</option>
								<option value="Corporate">Corporate</option>
							</select></span>
						</p>
						<?php } else{ if($agentt_id != '0'){ ?>
						<p>	
							<label>Agent Info</label>
							<span class="field">
								<input type ="hidden" name="agent_id" value="<?php echo $agentt_id; ?>">
								<input type ="hidden" name="com_percent" value="<?php if($client_com_percent != '0.00'){echo $client_com_percent;} else{echo $com_percent;} ?>">
								<input type="text" name="" id="" class="input-xxlarge" value="<?php echo $agent_name.' | '.$e_cont_per.' | '.$com_percent.'%';?>" readonly />
							</span>
						</p>
						<?php } ?>
						<p>	
							<label>Bank</label>
							<span class="field">
							<select data-placeholder="Select a Bank" name="bank" class="chzn-select" required="" style="width:100%">
								<?php while ($rowBank=mysql_fetch_array($Bank)) { ?>
									<option value="<?php echo $rowBank['id'] ?>"><?php echo $rowBank['bank_name']?></option>
								<?php } ?>
							</select>
							</span>
						</p>
						<?php } ?>
						<p>
							<label>Due Amount</label>							
							<span class="field">
							<input type="hidden" name="dew" id="" class="input-xxlarge" value="<?php echo $Dew;?>" readonly />
								<h3>৳ <?php $intotaldue=$Dew; echo number_format($intotaldue,2); ?></h2>	
							</span>							
						</p>
						<p>
							<label>Payment Amount</label>
							<span class="field"><input type="text" name="payment" id="" style="width:20%;" value="<?php echo $pay; ?>" placeholder="Payment in Tk" onChange="updatesum()" />
							<?php if($agentt_id != '0'){ if($count_commission == '1'){?><input type ="hidden" name="commission_sts" value="1"><input type="text" name="commission_amount" style="width:15%;" readonly value="<?php echo $comission; ?>" onChange="updatesum()"/><input type="text" name="" id="" style="width:20%; color:red;border-left: 0px solid;" value='<?php if($client_com_percent != '0.00'){ echo $client_com_percent; } else{echo $com_percent;}?>%' readonly /><?php } else{ ?><input type ="hidden" name="commission_sts" value="0"><?php }} ?></span>
						</p>
						<?php if($userr_typ == 'admin' || $userr_typ == 'superadmin'){?>
						<p>
							<label>Bill Discount</label>
							<span class="field"><input type="text" name="bill_disc" id="" class="input-xxlarge" style="width:120px;" value="0" placeholder="Discount in Tk" /></span>
						</p>
						<?php } else{ ?>
						<p>
							<label>Bill Discount</label>
							<span class="field"><input type="text" name="bill_disc" id="" class="input-xxlarge" readonly style="width:120px;" value="0" placeholder="Discount in Tk" /></span>
						</p>
						<?php } ?>
						<p>
							<label>Money Receipt No</label>
							<span class="field"><input type="text" name="moneyreceiptno" placeholder="Optional" id="" class="input-xxlarge" /></span>
						</p>
						<p>
							<label>Current Status</label>
							<span class="field"><input type="text" readonly class="input-xlarge" value="<?php echo $row['con_sts']; ?>" /></span>
						</p>
						<p>
                            <label>Auto Service Activation</label>
							<span class="formwrapper">
								<?php if($row['con_sts']== 'Inactive'){ ?>
								<input type="radio" name="auto_bill_check" value="Yes" checked="checked"> Yes &nbsp; &nbsp;
								<input type="radio" name="auto_bill_check" value="No"> No &nbsp; &nbsp;
                            </span>
							<?php } else{ ?><input type="radio" name="auto_bill_check" value="No" checked="checked"> No &nbsp; &nbsp;</span><?php } ?>
                        </p>
						<p>
                            <label>Send Invoice SMS</label>
								<span class="formwrapper">
								<input type="radio" name="sentsms" value="Yes" checked="checked"> Yes &nbsp; &nbsp;
								<input type="radio" name="sentsms" value="No"> No &nbsp; &nbsp;
                            </span>
                        </p>
						<p>
							<label>Notes</label>
							<span class="field"><textarea type="text" name="pay_dsc" id="" placeholder="Write Your Payment Note" class="input-xxlarge" /></textarea></span>
						</p>
					</div>
					<div class="modal-footer">
						<button type="reset" class="btn">Reset</button>
						<button class="btn btn-primary" type="submit">Submit</button>
					</div>
				</form>			
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