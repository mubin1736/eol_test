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
				<h4> Due Bill Report </h4>
				<form id="" name="form" class="stdform" method="post" action="fpdf/ReportAdvance" target="_blank">
									<div class="inputwrapper animate_cus1">
										<select data-placeholder="Choose a Zone" name="z_id" class="chzn-select"  style="width:345px;">
											<option value="all"> All Zone </option>
												<?php while ($row=mysql_fetch_array($result)) { ?>
											<option value="<?php echo $row['z_id']?>"><?php echo $row['z_name']; ?> (<?php echo $row['z_bn_name']; ?>)</option>
												<?php } ?>
										</select>
									</div>
									<div class="inputwrapper1 animate_cus4">
										<input type="text" name="f_date" id="" class="datepicker" style="width: 47%;" placeholder="From Date"/>
										<input type="text" name="t_date" id="" class="datepicker" style="width: 47%;" placeholder="To Date"/>
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