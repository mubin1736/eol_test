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

if($userr_typ == 'mreseller'){
	$result1=mysql_query("SELECT p_id, p_name, p_price, bandwith, sts, status FROM package WHERE z_id = '$macz_id' order by p_name");
}
else{
	$result1=mysql_query("SELECT p_id, p_name, p_price, bandwith, sts, status FROM package order by p_name");
}
?>
	<div class="box box-primary">
			<div class="box-body aasas"> 
				<h4> Clients List </h4>
				<form id="" name="form" class="stdform" method="post" action="fpdf/ClientsList" target="_blank">
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
										<input type="text" style="width:345px;text-align: center;" readonly value="<?php echo $zo_name; ?>" />
										<input type="hidden" name="z_id" id="" readonly value="<?php echo $macz_id; ?>" />
									<?php } else{?>
										<select data-placeholder="Choose a Zone" name="z_id" class="chzn-select"  style="width:345px;">
											<option value="all"> All Zone </option>
												<?php while ($row=mysql_fetch_array($result)) { ?>
											<option value="<?php echo $row['z_id']?>"><?php echo $row['z_name']; ?> (<?php echo $row['z_bn_name']; ?>)</option>
												<?php } ?>
										</select>
										<?php } ?>
									</div>
									<div class="inputwrapper animate_cus2">
										<select data-placeholder="Choose a Zone" name="p_id" class="chzn-select" style="width:345px;">
											<option value="all"> All Packages </option>
												<?php while ($row=mysql_fetch_array($result1)) { ?>
											<option value="<?php echo $row['p_id']?>"><?php echo $row['p_name']; ?> (<?php echo $row['bandwith']; ?>)</option>
												<?php } ?>
										</select>
									</div>
									<div class="inputwrapper animate_cus3">
										<div id="custdiv">
											<select data-placeholder="Choose a Area" name="con_sts" class="chzn-select" style="width:345px;">
												<option value="all"> All Clients </option>
												<option value="active">Active Clients</option>
												<option value="inactive">Inactive Clients</option>
											</select>
										</div>
									</div>
									<div class="inputwrapper animate_cus4">
										<select data-placeholder="Payment Method" name="p_m" class="chzn-select" style="width:345px;">
											<option value="all">All Payment Method</option>
											<option value="Cash">Cash</option>
											<option value="bKash">bKash</option>
											<option value="Bank">Bank</option>
											<option value="Corporate">Corporate</option>
										</select>
									</div>
									<div class="inputwrapper animate_cus5">
										<select data-placeholder="Choose a Packages" name="df_date" class="chzn-select"  style="width:145px;">
											<option value="all"> From Any Deadline </option>
											<option value="01"> 1st<?php echo date(' M Y', time());?></option>
											<option value="02"> 2nd<?php echo date(' M Y', time());?></option>
											<option value="03"> 3rd<?php echo date(' M Y', time());?></option>
											<option value="04"> 4th<?php echo date(' M Y', time());?></option>
											<option value="05"> 5th<?php echo date(' M Y', time());?></option>
											<option value="06"> 6th<?php echo date(' M Y', time());?></option>
											<option value="07"> 7th<?php echo date(' M Y', time());?></option>
											<option value="08"> 8th<?php echo date(' M Y', time());?></option>
											<option value="09"> 9th<?php echo date(' M Y', time());?></option>
											<option value="10"> 10th<?php echo date(' M Y', time());?></option>
											<option value="11"> 11th<?php echo date(' M Y', time());?></option>
											<option value="12"> 12th<?php echo date(' M Y', time());?></option>
											<option value="13"> 13th<?php echo date(' M Y', time());?></option>
											<option value="14"> 14th<?php echo date(' M Y', time());?></option>
											<option value="15"> 15th<?php echo date(' M Y', time());?></option>
											<option value="16"> 16th<?php echo date(' M Y', time());?></option>
											<option value="17"> 17th<?php echo date(' M Y', time());?></option>
											<option value="18"> 18th<?php echo date(' M Y', time());?></option>
											<option value="19"> 19th<?php echo date(' M Y', time());?></option>
											<option value="20"> 20th<?php echo date(' M Y', time());?></option>
											<option value="21"> 21th<?php echo date(' M Y', time());?></option>
											<option value="22"> 22th<?php echo date(' M Y', time());?></option>
											<option value="23"> 23th<?php echo date(' M Y', time());?></option>
											<option value="24"> 24th<?php echo date(' M Y', time());?></option>
											<option value="25"> 25th<?php echo date(' M Y', time());?></option>
											<option value="26"> 26th<?php echo date(' M Y', time());?></option>
											<option value="27"> 27th<?php echo date(' M Y', time());?></option>
											<option value="28"> 28th<?php echo date(' M Y', time());?></option>
											<option value="29"> 29th<?php echo date(' M Y', time());?></option>
											<option value="30"> 30th<?php echo date(' M Y', time());?></option>
											<option value="31"> 31th<?php echo date(' M Y', time());?></option>
										</select>
										<select data-placeholder="Choose a Packages" name="dt_date" class="chzn-select"  style="width:145px;">
											<option value="all"> To Any Deadline </option>
											<option value="01"> 1st<?php echo date(' M Y', time());?></option>
											<option value="02"> 2nd<?php echo date(' M Y', time());?></option>
											<option value="03"> 3rd<?php echo date(' M Y', time());?></option>
											<option value="04"> 4th<?php echo date(' M Y', time());?></option>
											<option value="05"> 5th<?php echo date(' M Y', time());?></option>
											<option value="06"> 6th<?php echo date(' M Y', time());?></option>
											<option value="07"> 7th<?php echo date(' M Y', time());?></option>
											<option value="08"> 8th<?php echo date(' M Y', time());?></option>
											<option value="09"> 9th<?php echo date(' M Y', time());?></option>
											<option value="10"> 10th<?php echo date(' M Y', time());?></option>
											<option value="11"> 11th<?php echo date(' M Y', time());?></option>
											<option value="12"> 12th<?php echo date(' M Y', time());?></option>
											<option value="13"> 13th<?php echo date(' M Y', time());?></option>
											<option value="14"> 14th<?php echo date(' M Y', time());?></option>
											<option value="15"> 15th<?php echo date(' M Y', time());?></option>
											<option value="16"> 16th<?php echo date(' M Y', time());?></option>
											<option value="17"> 17th<?php echo date(' M Y', time());?></option>
											<option value="18"> 18th<?php echo date(' M Y', time());?></option>
											<option value="19"> 19th<?php echo date(' M Y', time());?></option>
											<option value="20"> 20th<?php echo date(' M Y', time());?></option>
											<option value="21"> 21th<?php echo date(' M Y', time());?></option>
											<option value="22"> 22th<?php echo date(' M Y', time());?></option>
											<option value="23"> 23th<?php echo date(' M Y', time());?></option>
											<option value="24"> 24th<?php echo date(' M Y', time());?></option>
											<option value="25"> 25th<?php echo date(' M Y', time());?></option>
											<option value="26"> 26th<?php echo date(' M Y', time());?></option>
											<option value="27"> 27th<?php echo date(' M Y', time());?></option>
											<option value="28"> 28th<?php echo date(' M Y', time());?></option>
											<option value="29"> 29th<?php echo date(' M Y', time());?></option>
											<option value="30"> 30th<?php echo date(' M Y', time());?></option>
											<option value="31"> 31th<?php echo date(' M Y', time());?></option>
										</select>
									</div>
									<div class="inputwrapper animate_cus6">
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