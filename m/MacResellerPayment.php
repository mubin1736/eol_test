<?php
$titel = "Re-connect";
$Client = 'active';
include('include/hader.php');
extract($_POST);
$zz_id = $_GET['id'];
$user_type = $_SESSION['SESS_USER_TYPE'];

$query222 =mysql_query("SELECT z.z_id, z.z_name, z.e_id, COUNT(c.c_id) AS totalclients, e.e_name, e.e_cont_per, e.pre_address, e.agent_id, e.count_commission, e.com_percent FROM zone AS z
						LEFT JOIN emp_info AS e
						ON e.e_id = z.e_id
						LEFT JOIN clients AS c
						ON c.z_id = z.z_id
						WHERE z.z_id = '$zz_id'");
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

$query2223 =mysql_query("SELECT p.id, p.z_id, p.pay_date, p.pay_mode, p.note, p.pay_amount, p.discount, q.e_name, (p.pay_amount + p.discount) AS totalpayments FROM `payment_macreseller` AS p
							LEFT JOIN emp_info AS q
							ON q.e_id = p.entry_by
							WHERE p.z_id = '$zz_id' AND p.sts = '0' ORDER BY p.id DESC");
							

$sql7q7 = mysql_query("SELECT a.bill_date, a.pay_time, a.totalbill, a.opening_balance, a.pay_amount, a.pay_mode, a.discount, a.closing_balance, a.pay_idd FROM
						(SELECT b.entry_date AS bill_date, FORMAT(SUM(b.bill_amount), 2) AS totalbill, '-' AS pay_time, '-' AS pay_mode, '-' AS discount, '-' AS opening_balance, '-' AS closing_balance, '-' AS pay_amount, '#' AS pay_idd FROM billing_mac AS b
						LEFT JOIN clients AS c ON c.c_id = b.c_id
						WHERE c.mac_user = '1' AND b.z_id = '$zz_id' GROUP BY MONTH(b.entry_date), YEAR(b.entry_date), DATE(b.entry_date)
						UNION
						SELECT p.pay_date, '-', p.pay_time, p.pay_mode, p.discount, p.opening_balance, p.closing_balance, FORMAT(p.pay_amount, 2) AS pay_amount, p.id AS pay_idd FROM payment_macreseller AS p
						WHERE p.z_id = '$zz_id' AND p.sts = '0' GROUP BY p.pay_date, p.pay_time) AS a
						ORDER BY a.bill_date, a.pay_time ASC");
						
$sql2 ="SELECT COUNT(c_id) AS activeclients FROM clients WHERE z_id = '$zz_id' AND con_sts = 'Active'";
$query2 = mysql_query($sql2);
$row2 = mysql_fetch_assoc($query2);

$sql111 = mysql_query("SELECT SUM(b.bill_amount) AS totbill FROM billing_mac AS b
						WHERE b.z_id = '$zz_id'");

$rowww = mysql_fetch_array($sql111);

$sql1z1 = mysql_query("SELECT p.id, SUM(p.pay_amount) AS repayment, SUM(p.discount) AS rediscount, (SUM(p.pay_amount) + SUM(p.discount)) AS retotalpayments FROM `payment_macreseller` AS p
						WHERE p.z_id = '$zz_id' AND p.sts = '0'");
$rowwz = mysql_fetch_array($sql1z1);

$aaaa = $rowwz['retotalpayments']-$rowww['totbill'];

if($user_type == 'admin' || $user_type == 'superadmin' || $user_type == 'accounts'){
	$Bank = mysql_query("SELECT * FROM bank WHERE sts = 0 ORDER BY bank_name");
}
else{
	$Bank = mysql_query("SELECT * FROM bank WHERE sts = 0 AND emp_id = '$e_id' ORDER BY bank_name");
}

if($aaaa < '0.00'){
	$colllllor = "style='color: red;'";
}
else{
	$colllllor = "style='color: green;'";
}
if($client_com_percent != '0.00'){ ?>
<script type="text/javascript">
function updatesum() {
document.form.commission_amount.value = ((document.form.pay_amount.value -0)/100)*<?php echo $client_com_percent;?>;
}
</script>
<?php } else{?>
<script type="text/javascript">
function updatesum() {
document.form.commission_amount.value = ((document.form.pay_amount.value -0)/100)*<?php echo $com_percent;?>;
}
</script>
<?php } ?>
	<div class="box box-primary">
		<?php if($sts == 'add') {?>
		<div class="alert alert-success" style="padding: 5px 0px 5px 10px;margin: 3px 5px 0px 5px;">
			<button data-dismiss="alert" style="margin-right: 10px;" class="close" type="button">&times;</button>
			<strong style="font-size: 13px;font-weight: normal;padding: 0 0 0 5px;">Payment Add Success!!</strong>.
		</div>
		<?php }?>
		<div class="box-header">
			<div class="modal-content">
				<div class="modal-header" style="padding: 0 0 0 10px;">
					<h5>Mac Reseller Payment Add</h5>
				</div>
				<form class="stdform" method="post" action="MacResellerPaymnetQuery" name="form" enctype="multipart/form-data">
					<input type="hidden" name="opening_balance" value="<?php $intotaldue=$rowwz['retotalpayments']-$rowww['totbill']; echo $intotaldue;?>" />
					<input type="hidden" name="z_id" value="<?php echo $zz_id;?>" />
					<input type="hidden" name="entry_by" value="<?php echo $e_id;?>" />
					<input type="hidden" name="pay_date" value="<?php echo date("Y-m-d");?>" />
					<input type="hidden" name="pay_time" value="<?php echo date("H:i:s");?>" />
					<input type="hidden" name="date_time" value="<?php echo date("Y-m-d H:i:s");?>" />
					<input type="hidden" name="e_id" value="<?php echo $row22['e_id'];?>" />
					<input type="hidden" name="cell" value="<?php echo $row22['e_cont_per'];?>" />
					<input type="hidden" name="e_name" value="<?php echo $row22['e_name'];?>" />
					<div class="modal-body" style="min-height: 180px;padding: 1px;">
					<div class="span6">
                        <table class="table table-bordered table-invoice">
							<tr>
								<td colspan="2" style="font-size: 14px;font-weight: bold;text-align: center;"><?php echo $row22['e_name']; ?></td>
								</tr>
								<tr>
									<td colspan="2" style="font-size: 12px;text-align: center;"><?php echo $row22['z_name'];?> - <?php echo $row22['e_id'];?><br><?php echo $row22['e_cont_per'];?></td>
								</tr>
								<tr>
									<td colspan="2" style="font-size: 14px;font-weight: bold;text-align: center;"><a>Total Clients: <?php echo $row22['totalclients'];?></a>   ||   <a <?php echo $colllllor;?>>Balance:  <?php $intotaldue=$aaaa; echo number_format($intotaldue,2);?> ৳</a></td>
								</tr>
								<?php if($agentt_id != '0'){ ?>
								<input type ="hidden" name="agent_id" value="<?php echo $agentt_id; ?>">
								<input type ="hidden" name="com_percent" value="<?php if($client_com_percent != '0.00'){echo $client_com_percent;} else{echo $com_percent;} ?>">
								<tr>
									<td colspan="2" style="font-size: 14px;font-weight: bold;text-align: center;"><a><?php echo $agent_name.' | '.$e_cont_per.' | '.$com_percent.'%';?></a></td>
								</tr>
								<tr>
									<td class="width40" style="font-weight: bold;font-size: 12px;text-align: right;padding: 12px 10px 0 0;">Commission</td>
									<td class="width40" style="font-weight: bold;"><?php if($agentt_id != '0'){ if($count_commission == '1'){?><input type ="hidden" name="commission_sts" value="1"><input type="text" name="commission_amount" style="width:45%;font-weight: bold;text-align: center;font-size: 14px;" readonly value="<?php echo $comission; ?>" onChange="updatesum()"/><input type="text" name="" id="" style="width:30%;font-weight: bold;text-align: center;font-size: 14px; color:red;border-left: 0px solid;" value='<?php if($client_com_percent != '0.00'){ echo $client_com_percent; } else{echo $com_percent;}?>%' readonly /><?php } else{ ?><input type ="hidden" name="commission_sts" value="0"><?php }} ?></td>
								</tr>
								<?php } ?>
								<tr>
									<td class="width40" style="font-weight: bold;font-size: 12px;text-align: right;padding: 12px 10px 0 0;">Cash Amount</td>
									<td class="width40" style="font-weight: bold;"><input type="text" name="pay_amount" id="" style="width:90%;font-weight: bold;text-align: center;font-size: 14px" required="" onChange="updatesum()">&nbsp; ৳</td>
								</tr>
								<tr>
									<td class="width40" style="font-weight: bold;font-size: 12px;text-align: right;padding: 12px 10px 0 0;">Discount</td>
									<td class="width40" style="font-weight: bold;"><input type="text" name="discount" value="0.00" style="width:90%;font-weight: bold;text-align: center;font-size: 14px">&nbsp; ৳</td>
								</tr>
								<tr>
									<td colspan="2" style="font-weight: bold;text-align: center">
										<select data-placeholder="Choose a bank" class="chzn-select" style="width:100%;text-align: center;" name="bank" required="" />
													<option value=""></option>
												<?php while ($rowBank=mysql_fetch_array($Bank)) { ?>
													<option value="<?php echo $rowBank['id'] ?>"><?php echo $rowBank['bank_name']?></option>
												<?php } ?>
										</select>
									</td>
								</tr>
								<tr>
									<td colspan="2" style="font-weight: bold;text-align: center">
										<select data-placeholder="Choose a pay_mode" class="chzn-select" style="width:100%;text-align: center;" name="pay_mode" required="" />
											<option value="Cash">CASH</option>
											<option value="bKash">bKash</option>
											<option value="CHQ">CHQ</option>
											<option value="Credit Card">Credit Card</option>
											<option value="Deposit">Deposit</option>
										</select>
									</td>
								</tr>

							<tr>
								<td colspan="2" style="font-size: 12px;font-weight: bold;text-align: center;bold;"><textarea type="text" name="note" style="width: 97%;" placeholder="Note"/></textarea></td>
							</tr>
							<tr>
								<td class="width40" style="font-weight: bold;font-size: 14px;text-align: right;padding: 10px 10px 0 0;">Send Invoice SMS</td>
								<td class="width40"><div class=""><input type="radio" name="sentsms" value="Yes"> Yes &nbsp; &nbsp;<input type="radio" name="sentsms" value="No" checked="checked" style="margin-top: 5px;"> No &nbsp; &nbsp;</div></td>
                            </tr>
                        </table>
                    </div><!--span6-->
					</div>
					<div class="modal-footer">
						<button class="btn btn-primary" type="submit">Submit</button>
					</div>
				</form>			
			</div>
		</div>
	</div>

<?php 
include('include/footer.php');
?>
<style>
#uniform-undefined{display: inline-block;margin-top: 10px;}
</style>