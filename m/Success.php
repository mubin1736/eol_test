<?php
$titel = "Success!!";
$Clients = 'active';
include('include/hader.php');
include("../web/conn/connection.php");
include("../web/company_info.php");
include('../web/include/smsapi.php');
include('../web/include/telegramapi.php');
mysql_query("SET CHARACTER SET utf8");
mysql_query("SET SESSION collation_connection ='utf8_general_ci'");
extract($_POST);

function bind_to_template($replacements, $sms_msg) {
	return preg_replace_callback('/{{(.+?)}}/', function($matches) use ($replacements) {
		return $replacements[$matches[1]];
	}, $sms_msg);
}

$sqlsdf = mysql_query("SELECT sms_msg FROM sms_msg WHERE id= '1'");
$rowsm = mysql_fetch_assoc($sqlsdf);
$sms_msg= $rowsm['sms_msg'];

$SQLSECS = mysql_query("SELECT c.c_id, c.c_name, z.z_name, c.address, c.cell, c.con_type, l.user_id, l.password, p.p_name, c.payment_deadline, c.mac_user, e.e_name FROM clients AS c
						LEFT JOIN login AS l
						ON l.e_id = c.c_id
						LEFT JOIN zone AS z
						ON z.z_id = c.z_id
						LEFT JOIN package AS p
						ON p.p_id = c.p_id
                        LEFT JOIN emp_info AS e
						ON e.e_id = c.entry_by
						WHERE c_id = '$new_id'");

$ROWSECS = mysql_fetch_assoc($SQLSECS);

$c_idd = $ROWSECS['c_id'];
$c_name = $ROWSECS['c_name'];
$z_name = $ROWSECS['z_name'];
$address = $ROWSECS['address'];
$cell = $ROWSECS['cell'];
$con_type = $ROWSECS['con_type'];
$user_id = $ROWSECS['user_id'];
$p_name = $ROWSECS['p_name'];
$payment_deadline = $ROWSECS['payment_deadline'];
$mac_user = $ROWSECS['mac_user'];

if ($SQLSECS){

//TELEGRAM Start....
if($tele_sts == '0' && $tele_add_user_sts == '0'){
$msg_body='..::[New Client Added]::..

Name: '.$c_name.' 
ID: '.$c_idd.'
Zone: '.$z_name.'
Package: '.$p_name.'

By: '.$e_name.'';

include('../web/include/telegramapicore.php');
}
//TELEGRAM END....

//SMS Start....
if($sentsms=='Yes'){
if($mac_user=='1'){
$from_page = 'Add MAC Client';
}
else{ $from_page = 'Add Client'; }

$replacements = array(
	'user_id' => $user_id,
	'password' => $passid,
	'package' => $p_name,
	'deadline' => $payment_deadline,
	'company_name' => $comp_name,
	'company_cell' => $company_cell
	);

$sms_body = bind_to_template($replacements, $sms_msg);

$send_by = $_SESSION['SESS_EMP_ID'];
include('../web/include/smsapicore.php');
}
?>

	<div class="box box-primary">
		<div class="box-header">
			<div class="modal-content">
				<div class="alert alert-success succs">
				<button data-dismiss="alert" class="close" type="button">&times;</button>
					<center><h3>Success!!</h3><br />
					New Client has been <strong style="font-weight: 100;">Successfully</strong> added. <br />
					In Mikrotik &nbsp; <a class="succ" style="font-size: 14px;">[<?php echo $mk_name; ?>]</a>&nbsp;	Zone &nbsp; <a class="terr" style="font-size: 14px;"><?php echo $z_name; ?></a>.</center>
				</div>
				<div class="alert alert-success succs">
					<button data-dismiss="alert" class="close" type="button">&times;</button>
					<center>
						Client Name : <strong style="font-weight: 100;"><?php echo $c_name; ?></strong><br />
						Connection Type : <strong style="font-weight: 100;"><?php echo $con_type; ?></strong> & <strong style="font-weight: 100;"><?php echo $p_name; ?></strong><br />
						Address : <strong style="font-weight: 100;"><?php echo $address; ?></strong><br />
						Contact No : <strong style="font-weight: 100;"><?php echo $cell; ?></strong><br />
						Loging Username : <strong style="font-weight: 100;font-size: 14px;" class="succ"><?php echo $user_id; ?></strong><br />
						Loging Password : <strong style="font-weight: 100;font-size: 14px;" class="succ"><?php echo $passid; ?></strong><br />
						<?php if($sentsms=='Yes'){?>
						Login information Send to <strong style="font-weight: 100;"><?php echo $cell; ?></strong><br />
						<?php } ?><br />
						<a class="btn btn-primary btn-large" href="Clients">Back To Clients</a> 
					</center>
				</div>
			</div>
		</div>
	</div>

<?php
	}
else
	{
		echo "Error: " . $query . "<br>" . mysql_error($con);
	}
include('include/footer.php');
?>