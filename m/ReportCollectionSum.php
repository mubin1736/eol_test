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

$result1=mysql_query("SELECT id, e_id, e_name FROM emp_info order by e_name");
?>
	<div class="box box-primary">
			<div class="box-body aasas"> 
				<h4> Collection Report </h4>
				<form id="" name="form" class="stdform" method="post" action="fpdf/ReportCollectionSum" target="_blank">
					<div class="inputwrapper animate_cus1">
						<select data-placeholder="Choose a Zone" name="z_id" class="chzn-select"  style="width:345px;">
							<option value="all"> All Zone </option>
						<?php while ($row=mysql_fetch_array($result)) { ?>
							<option value="<?php echo $row['z_id']?>"><?php echo $row['z_name']; ?> (<?php echo $row['z_bn_name']; ?>)</option>
						<?php } ?>
						</select>
					</div>
					<div class="inputwrapper animate_cus2">
						<select data-placeholder="Choose a Zone" name="e_id" class="chzn-select"  style="width:345px;">
							<option value="all"> All Employee </option>
						<?php while ($row1=mysql_fetch_array($result1)) { ?>
							<option value="<?php echo $row1['e_id']?>"><?php echo $row1['e_name']; ?></option>
						<?php } ?>
						</select>
					</div>
					<div class="inputwrapper1 animate_cus3">
						<div id="custdiv">
							<input type="text" name="f_date" id="" class="surch_emp datepicker" placeholder="From Date"/>
							<input type="text" name="t_date" id="" class="surch_emp datepicker" placeholder="To Date"/>
						</div>
					</div>
					<div class="inputwrapper animate_cus4">
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