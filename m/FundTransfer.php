<?php
$titel = "Fund Transfer";
include('include/hader.php');
extract($_POST); 

//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'FundTransfer' AND $user_type = '1'");
if(mysql_num_rows($access) > 0){
//---------- Permission -----------

?>
	<div class="box box-primary">
		<div class="box-header">
		<?php if($userr_typ == 'mreseller'){ } else{ ?>
			<a class="btn btn-neveblue" style="float: right;margin-right: 2px;" href="FundTransferAdd"><i class="iconfa-plus" style="font-size: 9px;"></i></a>
		<?php } ?>
		</div><br />
			<div class="box-body">
			<?php if($sts == 'add'){?>
			<div class="alert alert-success" style="padding: 5px;font-size: 12px;font-weight: bold;">
				<button data-dismiss="alert" class="close" type="button">&times;</button>
				<strong>Success!!</strong> Fund Transfer Success to <?php echo $fundreceived; ?>.
			</div><!--alert-->
			<?php } ?>
				<table id="dyntable" class="table table-bordered responsive">
                    <colgroup>
						<col class="con0" />
                        <col class="con1" />
                        <col class="con0" />
						<col class="con1" />
                    </colgroup>
                    <thead>
                        <tr  class="newThead">
                            <th class="head0" style="font-size: 10px;padding: 5px;text-align: center;">Date</th>
							<th class="head1" style="font-size: 10px;padding: 5px;text-align: center;">Sender</th>
							<th class="head0" style="font-size: 10px;padding: 5px;text-align: center;">Reciever</th>
							<th class="head1" style="font-size: 10px;padding: 5px;text-align: center;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
							$sql = mysql_query("SELECT f.id, c.bank_name AS send, c.emp_id AS senderid, d.emp_id AS reseverid, d.bank_name AS rec, f.transfer_amount, f.transfer_date, f.note FROM fund_transfer AS f
													LEFT JOIN bank AS c ON c.id = f.fund_send
													LEFT JOIN bank AS d ON d.id = f.fund_received
													ORDER BY f.id DESC LIMIT 200");
								while($row = mysql_fetch_assoc($sql))
								{
								if($user_type == 'admin' || $user_type == 'superadmin' || $user_type == 'accounts'){
									echo
										"<tr class='gradeX'>
											<td style='font-size: 10px;font-weight: bold;'>{$row['transfer_date']}</td>
											<td style='font-size: 10px;font-weight: bold;'>{$row['send']}<br>{$row['senderid']}</td>
											<td style='font-size: 10px;font-weight: bold;'>{$row['rec']}<br>{$row['reseverid']}</td>
											<td style='font-size: 10px;font-weight: bold;'>{$row['transfer_amount']}</td>
										</tr>\n ";
								}
								}
							?>
					</tbody>
				</table>
				
			</div>	
	</div>
				
<?php
}
else{
	include('include/index');
}
include('include/footer.php');
?>