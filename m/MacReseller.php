<?php
$titel = "Billing";
$Billing = 'active';
include('include/hader.php');
$type = $_GET['id'];
extract($_POST); 

//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'Billing' AND $user_type = '1'");
if(mysql_num_rows($access) > 0){
//---------- Permission -----------

$sql = mysql_query("SELECT e.id, e.e_id, e.e_name, e.billing_type, e.minimum_day, m.Name AS mkname, e.pre_address, e.e_cont_per, e.e_j_date, z.z_id, z.z_name, COUNT(c.c_id) AS totalclient, l.totalbill AS totalbills, t.totalpayments AS totalpay, t.totaldiscount AS totaldiscounts, (IFNULL(t.totalpayment,0) - l.totalbill) AS totaldue FROM emp_info AS e
													LEFT JOIN clients AS c ON e.z_id = c.z_id
													LEFT JOIN
													(SELECT z_id, IFNULL(SUM(bill_amount),0) AS totalbill FROM billing_mac GROUP BY z_id)l
													ON e.z_id = l.z_id
													LEFT JOIN
													(SELECT e_id, z_id, SUM(discount) AS totaldiscount, SUM(pay_amount) AS totalpayments, (SUM(discount)+SUM(pay_amount)) AS totalpayment FROM payment_macreseller GROUP BY e_id)t
													ON e.z_id = t.z_id
													LEFT JOIN zone AS z	ON z.z_id = e.z_id
													LEFT JOIN mk_con AS m ON m.id = e.mk_id
													WHERE e.dept_id = '0' AND e.`status` = '0' AND e.z_id != '' GROUP BY e.z_id ORDER BY totalclient ASC");
													
							$total_reseller = mysql_num_rows($sql);
							
							$sqlssss = mysql_query("SELECT c_id FROM clients WHERE sts = '0' AND mac_user = '1'");
							$total_reseller_client = mysql_num_rows($sqlssss);
							
							$sqlssssff = mysql_query("SELECT c_id FROM clients WHERE sts = '0' AND mac_user = '1' AND con_sts = 'Active'");
							$total_reseller_active_client = mysql_num_rows($sqlssssff);
							
							$total_reseller_inactive_client = $total_reseller_client - $total_reseller_active_client;
							
							$tit = "<div class='box-header'>
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Total Reseller:  <i style='color: #317EAC'>{$total_reseller}</i></div> 
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Total Clients:  <i style='color: #317EAC'>{$total_reseller_client}</i></div> 
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Active:  <i style='color: #30ad23'>{$total_reseller_active_client}</i></div> 
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Inactive: <i style='color: #e3052e'>{$total_reseller_inactive_client}</i></div> 
									</div>";

$resulterer=mysql_query("SELECT z.z_id, z.z_name, e.e_name AS resellername, e.e_id AS resellerid, m.Name AS mkname FROM zone AS z LEFT JOIN emp_info AS e ON e.e_id = z.e_id LEFT JOIN mk_con AS m ON m.id = e.mk_id WHERE z.status = '0' AND z.e_id != '' order by z.z_name");
$query11="SELECT id, Name, ServerIP FROM mk_con WHERE sts = '0' ORDER BY id ASC";
$result11=mysql_query($query11);

?>
	<div class="box box-primary">
			<?php if($sts == 'paid') {?>
		<div class="alert alert-success" style="padding: 5px 0px 5px 10px;margin: 3px 5px 0px 5px;">
			<button data-dismiss="alert" style="margin-right: 10px;" class="close" type="button">&times;</button>
			<strong style="font-size: 13px;font-weight: normal;padding: 0 0 0 5px;">Payment Success!!</strong>.
		</div>
		<?php }?>
	<div class="box-header">
			<h6 style="float: left;"><?php echo $tit; ?></h6> 
		</div><br />
			<div class="box-body">
			<?php if($sts == 'add'){?>
			<div class="alert alert-success" style="padding: 5px;font-size: 12px;font-weight: bold;">
				<button data-dismiss="alert" class="close" type="button">&times;</button>
				<strong>Success!!</strong> Payment Successfully Added.
			</div><!--alert-->
		<?php } ?>

				<table id="dyntable" class="table table-bordered responsive">
                    <colgroup>
						<col class="con0" />
                        <col class="con1" />
                        <col class="con0" />
                    </colgroup>
                    <thead>
                        <tr  class="newThead">
                            <th class="head0" style="font-size: 10px;padding: 5px;text-align: center;">Reseller</th>
							<th class="head1" style="font-size: 10px;padding: 5px;text-align: center;">Type/Day</th>
							<th class="head0" style="font-size: 10px;padding: 5px;text-align: center;">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
								while( $row = mysql_fetch_assoc($sql) )
								{
									if($row['totaldue'] == ''){
									$billz=$row['totalbills'];								
								}
								else{
									$billz=$row['totaldue'];
								}
								if($row['billing_type'] == 'Postpaid'){
									$colorsdf = ' label-important';
								}
								else{
									$colorsdf = ' label-success';
								}
								if($row['terminate'] == '1'){
									$colorrrr = 'style="color: red;"'; 							
								}
								else{
									$colorrrr = ''; 	
								}
								if($billz > 0){
										$color = 'style="color:blue; font-weight: bold;font-size: 12px;"';					
									} 
								if($billz < 0){
										$color = 'style="color:red; font-weight: bold;font-size: 12px;"';					
									}
								if($billz == 0){
										$color = 'style="color:green; font-weight: bold;font-size: 12px;"';					
									}
									
								if($user_type == 'admin' || $user_type == 'billing'|| $user_type == 'superadmin' || $user_type == 'accounts'){
									echo
										"<tr class='gradeX'>
											<td class='center'>
												<ul class='tooltipsample' style='margin-bottom: 0px !important;'>
													<li style='font-size: 10px;font-weight: bold;'>{$row['e_name']}<br/>{$row['e_id']}<br/>{$row['e_cont_per']}</li>
												</ul>
											</td>
											<td class='center'>
												<ul class='tooltipsample' style='margin-bottom: 0px !important;'>
													<li style='font-size: 10px;font-weight: bold;'>{$row['billing_type']}<br/>{$row['minimum_day']}</li>
												</ul>
											</td>
											<td class='center' $color>
												<ul class='tooltipsample'>
													<li>{$billz}</li><br/>
													<li><a data-placement='top' data-rel='tooltip' style='padding: 3px 5px 3px 5px;font-size: 10px;font-weight: bold;border: 1px solid #bbb;' href='MacResellerPayment?id=",$id,"{$row['z_id']}' data-original-title='Payment' class='btn col1' onclick='return checksts()'><i class='iconfa-money'></i></a></li>
												</ul>
											</td>
										</tr>\n ";
								}
								
								
								}
							?>
					</tbody>
				</table>
			
			</div>	
	</div>

<!-- -------------------------------------------------------------Entry Data View------------------------------------------------------------ -->			
				
<?php
}
else{
	include('include/index');
}
include('include/footer.php');
?>