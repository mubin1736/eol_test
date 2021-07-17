<?php
$titel = "Payment";
$Payment = 'active';
include('include/hader.php');
extract($_POST);
//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'Payment' AND $user_type = '1'");
if(mysql_num_rows($access) > 0){
//---------- Permission -----------
$queryr = mysql_query("SELECT c_id, c_name, cell, address FROM clients ORDER BY c_name");
?>
	<div class="box box-primary">
			<div class="box-body">
		<?php if($sts == 'add') {?>
			<div class="alert alert-success">
				<button data-dismiss="alert" class="close" type="button">&times;</button>
				<strong>Success!!</strong> Payment Successfully Added.
			</div><!--alert-->
		<?php } ?>

				<form id="form2" class="stdform" method="GET" action="PaymentAdd">
					<div>Add Client Payment:</div>
					<div class=""> 
						<select data-placeholder="Choose Client" name="id" class="chzn-select" style="width:200px;" required="" onchange='this.form.submit()'>
							<option value=""></option>
							<?php while ($row2=mysql_fetch_array($queryr)) { ?>
							<option value="<?php echo $row2['c_id']?>"><?php echo $row2['c_name']; ?> | <?php echo $row2['c_id']; ?> | <?php echo $row2['address']; ?></option>
							<?php } ?>
						</select>
					</div>
				</form>	
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