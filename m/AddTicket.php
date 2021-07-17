<?php
$titel = "Add Ticket";
include('include/hader.php');
$id = $_GET['id'];
//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'Support' AND $user_type = '1'");
if(mysql_num_rows($access) > 0){
//---------- Permission -----------

$queryd = mysql_query("SELECT l.e_id, c.c_name, c.c_id, l.user_name FROM login AS l
						LEFT JOIN clients AS c
						ON c.c_id = l.e_id
						WHERE l.e_id = '$e_id'");
$row5 = mysql_fetch_assoc($queryd);
$c_name = $row5['c_name'];
$c_id = $row5['c_id'];

date_default_timezone_set('Etc/GMT-6');
$dateAndTime = date('Y-m-d G:i:s', time());

$query1="SELECT dept_id, dept_name FROM department_info ORDER BY dept_id ASC";
$result1 = mysql_query($query1);

$result2 = mysql_query("SELECT id, subject FROM complain_subject ORDER BY subject ASC");

if($user_type == 'mreseller'){
	$queryr = mysql_query("SELECT c.c_id, c.c_name, c.com_id, z.z_name, c.z_id, c.cell, c.address FROM clients AS c 
									LEFT JOIN zone AS z
									ON z.z_id = c.z_id
									LEFT JOIN package AS p
									ON p.p_id = c.p_id WHERE c.sts = '0' AND c.z_id = '$macz_id' AND c.mac_user = '1'
									ORDER BY c.c_name ASC");
}
else{
	if($user_type == 'billing'){
		$queryr = mysql_query("SELECT c.c_id, c.c_name, c.cell, c.com_id, c.address, z.z_name FROM clients AS c LEFT JOIN zone AS z ON z.z_id = c.z_id WHERE c.sts = '0' AND c.mac_user = '0' AND z.emp_id = '$e_id' ORDER BY c.c_name");
	}
	else
	{
		$queryr = mysql_query("SELECT c.c_id, c.c_name, c.com_id, z.z_name, c.cell, c.address FROM clients AS c 
									LEFT JOIN zone AS z
									ON z.z_id = c.z_id
									LEFT JOIN package AS p
									ON p.p_id = c.p_id WHERE c.sts = '0'
									ORDER BY c.c_name ASC");	
	}
}
	?>

	<div class="box box-primary">
		<div class="box-header">
			<form class="stdform" method="post" action="SupportQuery">
			<input type="hidden" name="way" value="client" />
			<input type="hidden" name="entry_by" value="<?php echo $e_id;?>" />
			<input type="hidden" name="entry_date_time" value="<?php echo $dateAndTime; ?>" />

					<div class="modal-body">
						<div class="row">
							<?php if($user_type == 'client'){ ?>
							<div class="popdiv">
								<div class="col-2" style="width: 100%;"> 
									<input type="hidden" name="c_id" required="" value="<?php echo $c_id; ?>" />
									<input type="text" value="<?php echo $c_name; ?> - <?php echo $c_id; ?>" readonly style="width:100%;" />
								</div>
							</div>
							<?php } else{ ?>
							<div class="popdiv">
								<div class="col-2"> 
									<select data-placeholder="Choose Client" name="c_id" class="chzn-select"  style="width:80%;" required="">
										<option value=""></option>
										<?php while ($row2=mysql_fetch_array($queryr)) { ?>
										<option value="<?php echo $row2['c_id']?>"><?php echo $row2['c_name']; ?> || <?php echo $row2['c_id']; ?> || <?php echo $row2['z_name']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<?php } ?>
							<div class="popdiv">
								<div class="col-2"> 
									<select data-placeholder="Department" name="dept_id" class="chzn-select"  style="width:80%;" required="">
										<option value=""></option>
										<?php while ($row1=mysql_fetch_array($result1)) { ?>
										<option value="<?php echo $row1['dept_id']?>"><?php echo $row1['dept_name']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="popdiv">
								<div class="col-2">
									<select data-placeholder="Subject" name="sub" class="chzn-select"  style="width:80%;" required="">
										<option value=""></option>
										<?php while ($row1=mysql_fetch_array($result2)) { ?>
										<option value="<?php echo $row1['subject']?>"><?php echo $row1['subject']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="popdiv">
								<div class="col-2" style="width: 100%;"> 
									<textarea type="text" name="massage" placeholder="Write your Massege/Complain Here" required="" id="" style="width:100%;" /></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="reset" class="btn">Reset</button>
						<button class="btn btn-primary" type="submit">Submit</button>
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