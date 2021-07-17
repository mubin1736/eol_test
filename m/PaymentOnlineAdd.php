<?php
$titel = "Online Payment";
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

if($Dew <= 0){
	
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
$result = mysql_query("SELECT c_id, c_name, cell, address, con_sts, b_date, payment_deadline, breseller FROM clients WHERE c_id = '$id'");
$row = mysql_fetch_array($result);	
						
$mathod = mysql_query("SELECT * FROM payment_mathod AS p LEFT JOIN bank AS b ON b.id = p.bank WHERE p.sts = 0 AND p.online = '1' AND p.bank != '0' ORDER BY p.name");
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
					<h5>Add Online Payment</h5>
				</div>
				<form id="" name="form1" class="stdform" method="post" action="PaymentSave">
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
				<input type="hidden" name="bill_disc" value="0"/>
				<input type="hidden" name="mode" value="online" />
					<div class="modal-body">
						<p>	
							<label>Client Info</label>
							<span class="field">
								<input type ="hidden" name="c_id" value="<?php echo $row['c_id']; ?>">
								<input type="text" name="" id="" class="input-xxlarge" value="<?php echo $row['c_id'].' | '.$row['cell'];?>" readonly />
							</span>
						</p>
						<?php if($agentt_id != '0'){ ?>
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
							<label>Payment Method</label>
							<span class="field">
							<select data-placeholder="Payment Method" name="pay_mtd" style="width:100%;" class="chzn-select" required="" >
								<?php while ($rowmath=mysql_fetch_array($mathod)) { ?>
									<option value="<?php echo $rowmath['name'] ?>"><?php echo $rowmath['name']?> - <?php echo $rowmath['bank_name']?></option>
								<?php } ?>
							</select>
							</span>
						</p>
						<p>
							<label>Due Amount</label>							
							<span class="field">
							<input type="hidden" name="dew" id="" class="input-xxlarge" value="<?php echo number_format($Dew,2); ?>" readonly />
								<h3>৳ <?php $intotaldue=$Dew; echo number_format($intotaldue,2); ?></h2>	
							</span>							
						</p>
						<p>
							<label>Payment Amount</label>
							<span class="field"><input type="text" name="payment" id="" style="width:20%;" value="<?php echo $pay; ?>" placeholder="Payment in Tk" onChange="updatesum()" />
							<?php if($agentt_id != '0'){ if($count_commission == '1'){?><input type ="hidden" name="commission_sts" value="1"><input type="text" name="commission_amount" style="width:15%;" readonly value="<?php echo $comission; ?>" onChange="updatesum()"/><input type="text" name="" id="" style="width:20%; color:red;border-left: 0px solid;" value='<?php if($client_com_percent != '0.00'){ echo $client_com_percent; } else{echo $com_percent;}?>%' readonly /><?php } else{ ?><input type ="hidden" name="commission_sts" value="0"><?php }} ?></span>
						</p>
						<p>
							<label>Sender Mobile No</label>
							<span class="field"><input type="text" name="sender_no" id="" class="input-xlarge" placeholder="01711234567" required="" /></span>
						</p>
						<p>
							<label>Transaction ID</label>
							<span class="field"><input type="text" name="trxid" id="" class="input-xlarge" placeholder="" required="" /></span>
						</p>
						<p>
                            <label>Send Invoice SMS</label>
								<span class="formwrapper">
								<input type="radio" name="sentsms" value="Yes" checked="checked"> Yes &nbsp; &nbsp;
								<input type="radio" name="sentsms" value="No"> No &nbsp; &nbsp;
                            </span>
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