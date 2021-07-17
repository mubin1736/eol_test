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

$query1="SELECT e_id, e_name, e_des FROM emp_info WHERE status = '0' ORDER BY e_id ASC";
$result1=mysql_query($query1);

$query="SELECT id, ex_type FROM expanse_type WHERE status = '0' ORDER BY ex_type ASC";
$result=mysql_query($query);
?>
	<div class="box box-primary">
			<div class="box-body aasas"> 
				<h4> Expence Report </h4>
				<form id="" name="form" class="stdform" method="post" action="fpdf/ReportExpenceHead" target="_blank">
					<div class="inputwrapper animate_cus1">
						<select data-placeholder="Choose a Expence Head" name="type" class="chzn-select"  style="width:345px;">
							<option value="all"> All Expence Head </option>
						<?php while ($row=mysql_fetch_array($result)) { ?>
							<option value="<?php echo $row['id'] ?>"><?php echo $row['ex_type']?></option>
						<?php } ?>
						</select>
					</div>
					<div class="inputwrapper animate_cus2">
						<select data-placeholder="Choose a Employee" name="e_id" class="chzn-select"  style="width:345px;">
							<option value="all"> All Employee </option>
							<?php while ($row=mysql_fetch_array($result1)) { ?>
								<option value="<?php echo $row['e_id'] ?>"><?php echo $row['e_name']?> - <?php echo $row['e_des']?> - <?php echo $row['e_id']?></option>
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