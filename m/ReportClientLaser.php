<?php
$titel = "Reports";
$Reports = 'active';
include('include/hader.php');
extract($_POST);
//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'Reports' AND $user_type = '1'");
if(mysql_num_rows($access) > 0){
//---------- Permission -----------

$e_id = $_SESSION['SESS_EMP_ID'];

$result=mysql_query("SELECT * FROM zone WHERE status = '0' order by z_name");
?>
	<div class="box box-primary">
			<div class="box-body aasas"> 
				<h4>Client Ledger</h4>
				<form id="" name="form" class="stdform" method="post" action="fpdf/ReportClientLaser" target="_blank">
									<div class="inputwrapper animate_cus1">
									<?php if($userr_typ == 'mreseller'){
									$sql2 ="SELECT z.z_id, z.z_name, z.z_bn_name, e.e_name, z.e_id FROM zone AS z
												LEFT JOIN emp_info AS e
												ON e.e_id = z.e_id
												WHERE z.z_id = '$macz_id'";
												$query2 = mysql_query($sql2);
												$row2 = mysql_fetch_assoc($query2);
												$zo_name = $row2['z_name'];
									 ?>
										<input type="text" style="width:100%;text-align: center;" readonly value="<?php echo $zo_name; ?>" />
										<input type="hidden" name="z_id" id="" readonly value="<?php echo $macz_id; ?>" />
									<?php } else{?>
										<select data-placeholder="Choose a Zone" name="z_id" class="chzn-select"  style="width:100%;">
											<option value="all"> All Zone </option>
												<?php while ($row=mysql_fetch_array($result)) { ?>
											<option value="<?php echo $row['z_id']?>"><?php echo $row['z_name']; ?> (<?php echo $row['z_bn_name']; ?>)</option>
												<?php } ?>
										</select>
										<?php } ?>
									</div>
									<div class="inputwrapper1 animate_cus4">
										<input type="text" name="f_date" id="" class="datepicker" style="width: 48%;" placeholder="From Date"/>
										<input type="text" name="t_date" id="" class="datepicker" style="width: 48%;" placeholder="To Date"/>
									</div>
									<div class="inputwrapper animate_cus3">
										<button class="btn btn-primary" type="submit"><i class="iconsweets-magnifying iconsweets-white"></i></button>
									</div>
								</form>
			</div>			
	</div>	
				
<?php
}
else{
	include('include/index');
}
include('include/footer.php');
?>
<style>
.aasas{}
</style>