<?php
$titel = "Re-connect";
$Client = 'active';
include("../web/conn/connection.php");
include('../web/include/telegramapi.php');
include("mk_api.php");
include('include/hader.php');
extract($_POST);
$user_type = $_SESSION['SESS_USER_TYPE'];

$e_id = $_SESSION['SESS_EMP_ID'];
ini_alter('date.timezone','Asia/Almaty');
$update_date = date('Y-m-d', time());
$update_time = date('H:i:s', time());

$que = mysql_query("SELECT id, con_sts, breseller, mac_user FROM clients WHERE c_id = '$c_id'");
$rowuuu = mysql_fetch_assoc($que);
$con_sts = $rowuuu['con_sts'];		
$breseller = $rowuuu['breseller'];		
$macuserrr = $rowuuu['mac_user'];		
$clientid = $rowuuu['id'];		

if($breseller == '1'){
$ques = mysql_query("SELECT b.id, b.c_id, c.mac_user, c.mk_id, c.c_name, c.payment_deadline, c.termination_date, b.day, c.discount as cdis, b.bill_date, b.p_price as bp_price, c.discount, c.extra_bill, b.bill_amount, c.total_bandwidth, c.total_price FROM billing AS b
						LEFT JOIN clients AS c
						ON c.c_id = b.c_id
						WHERE c.c_id = '$c_id' ORDER BY b.bill_date DESC LIMIT 1");
$roww = mysql_fetch_assoc($ques);
$idq = $roww['id'];
$cl_id = $roww['c_id'];
$c_name = $roww['c_name'];
$bill_date = $roww['bill_date'];
$p_name = $roww['total_bandwidth'].' mb';
$bandwith = $roww['total_price'].' tk';
$p_price = $roww['total_price'];
$discount = $roww['discount'];
$extra_bill = $roww['extra_bill'];
$bill_amount = $roww['bill_amount'];
$c_dis = $roww['cdis'];
$payment_deadline = $roww['payment_deadline'];
$macuser = $roww['mac_user'];
$bp_price = $roww['bp_price'];
$day = $roww['day'];
$mk_id = $roww['mk_id'];
$termination_date = $roww['termination_date'];
$quesee = mysql_query("SELECT * FROM billing WHERE c_id = '$c_id' ORDER BY id DESC LIMIT 1");
$rowwee = mysql_fetch_assoc($quesee);

$priceinavtive = $rowwee['p_price'];
$billamount = $rowwee['bill_amount'];

if($priceinavtive == '0.00' && $billamount == '0.00'){
$todayy = date('d', time());
$lastdayofthismonth = date('t');
$aa = $lastdayofthismonth - $todayy;

$onedaynewprice = $p_price / $lastdayofthismonth;
$unusedday = $aa * $onedaynewprice;
}
else{
//	$unusedday =$p_price - $c_dis;
	$unusedday =$billamount;
}
}
else{
if($macuserrr == '1'){
$todayssss = strtotime(date('Y-m-d', time()));
	$ques = mysql_query("SELECT b.id, b.c_id, c.cell, c.address, c.z_id, c.con_sts, c.mac_user, c.mk_id, c.c_name, c.payment_deadline, c.termination_date, b.days, b.start_date, c.extra_bill, c.discount, b.start_time, b.end_date, b.p_id, p.p_name, p.bandwith, p.p_price, b.bill_amount FROM billing_mac AS b
						LEFT JOIN package AS p
						ON p.p_id = b.p_id
						LEFT JOIN clients AS c
						ON c.c_id = b.c_id
						WHERE c.c_id = '$c_id' ORDER BY b.id DESC LIMIT 1");
$roww = mysql_fetch_assoc($ques);
$idq = $roww['id'];
$cl_idsss = $roww['c_id'];
$c_name = $roww['c_name'];
$cell = $roww['cell'];
$start_date = strtotime($roww['start_date']);
$startdate = $roww['start_date'];
$start_time = strtotime($roww['start_time']);
$starttime = $roww['start_time'];
$end_date = strtotime($roww['end_date']);
$enddate = $roww['end_date'];
$p_id = $roww['p_id'];
$p_name = $roww['p_name'];
$bandwith = $roww['bandwith'];
$p_price = $roww['p_price'];
$bill_amount = $roww['bill_amount'];
$macuser = $roww['mac_user'];
$day = $roww['days'];
$mk_id = $roww['mk_id'];
$termination_date = $roww['termination_date'];
$z_id = $roww['z_id'];
$con_sts = $roww['con_sts'];
$address = $roww['address'];
$extra_bill = $roww['extra_bill'];
$discount = $roww['discount'];

if($user_type == 'mreseller'){
$query1="SELECT p_id, p_name, p_price, bandwith FROM package WHERE z_id = '$macz_id' AND p_price != '0.00' order by id ASC";
}
else{
$query1="SELECT p_id, p_name, p_price, bandwith FROM package WHERE z_id = '$z_id' order by id ASC";
}
$result1=mysql_query($query1);

$sqlmk = mysql_query("SELECT id, ServerIP, Username, Pass, Port, e_Md, secret_h FROM mk_con WHERE sts = '0' AND id = '$mk_id'");
$rowmk = mysql_fetch_assoc($sqlmk);
$ServerIP = $rowmk['ServerIP'];
$Username = $rowmk['Username'];
$Pass= openssl_decrypt($rowmk['Pass'], $rowmk['e_Md'], $rowmk['secret_h']);
$Port = $rowmk['Port'];

$sqlqqmm = mysql_query("SELECT minimum_day, terminate FROM emp_info WHERE z_id = '$z_id'");
$row22m = mysql_fetch_assoc($sqlqqmm);
$minimum_day = $row22m['minimum_day'];
$terminate = $row22m['terminate'];
}
else{
$ques = mysql_query("SELECT b.id, b.c_id, c.mac_user, c.mk_id, c.address, c.c_name, c.payment_deadline, c.termination_date, b.day, c.extra_bill, c.discount as cdis, b.bill_date, b.p_price as bp_price, b.p_id, p.p_name, p.bandwith, p.p_price, c.discount, b.bill_amount FROM billing AS b
						LEFT JOIN package AS p
						ON p.p_id = b.p_id
						LEFT JOIN clients AS c
						ON c.c_id = b.c_id
						WHERE c.c_id = '$c_id' ORDER BY b.bill_date DESC LIMIT 1");
$roww = mysql_fetch_assoc($ques);
$idq = $roww['id'];
$cl_id = $roww['c_id'];
$cl_idsss = $roww['c_id'];
$c_name = $roww['c_name'];
$bill_date = $roww['bill_date'];
$p_id = $roww['p_id'];
$p_name = $roww['p_name'];
$bandwith = $roww['bandwith'];
$p_price = $roww['p_price'];
$discount = $roww['discount'];
$extra_bill = $roww['extra_bill'];
$bill_amount = $roww['bill_amount'];
$c_dis = $roww['cdis'];
$payment_deadline = $roww['payment_deadline'];
$macuser = $roww['mac_user'];
$bp_price = $roww['bp_price'];
$day = $roww['day'];
$mk_id = $roww['mk_id'];
$termination_date = $roww['termination_date'];
$address = $roww['address'];

$quesee = mysql_query("SELECT * FROM billing WHERE c_id = '$c_id' ORDER BY id DESC LIMIT 1");
$rowwee = mysql_fetch_assoc($quesee);
$priceinavtive = $rowwee['p_price'];
$billamount = $rowwee['bill_amount'];

if($priceinavtive == '0.00' && $billamount == '0.00'){
$todayy = date('d', time());
$lastdayofthismonth = date('t');
$aa = $lastdayofthismonth - $todayy;

$onedaynewprice = (($p_price + $extra_bill) - $c_dis) / $lastdayofthismonth;
	$unusedday = $aa * $onedaynewprice;
}
else{
	//$unusedday = ($p_price + $extra_bill) - $c_dis;
	$unusedday = $billamount;
}
}
}

$todayyyy = date('Y-m-d', time());

$Date2 = date('Y-m-d', strtotime($todayyyy . " + ".$minimum_day." day"));
$yrdata= strtotime($Date2);
$dateee = date('F d, Y', $yrdata);

$resultwww = mysql_query("SELECT p_id, p_price FROM package WHERE p_id = '$p_id' AND status = '0'");
$rowprice = mysql_fetch_assoc($resultwww);
$old_p_price= $rowprice['p_price'];

$packageoneday = $old_p_price/30;
$daycost = $minimum_day*$packageoneday;

if($idq != ''){
?>
	<?php  if($macuserrr != '1'){ ?>
	<div class="pageheader">
        <div class="searchbar">
			<a class="btn btn-primary" href="Billing"><i class="iconfa-arrow-left"></i>  Back</a>
        </div>
        <div class="pageicon"><i class="iconfa-folder-open"></i></div>
        <div class="pagetitle">
            <h1>Re-connection Bill Adjustment</h1>
        </div>
    </div><!--pageheader-->
	<div class="box box-primary">
		<div class="box-header">
			<div class="modal-content">
				<div class="modal-header">
					<h5>Adjastment</h5>
				</div>
				<form id="" name="form1" class="stdform" method="post" action="ClientInactiveAdjastmentQuery">
				<input type ="hidden" name="id" value="<?php echo $idq; ?>">
				<input type ="hidden" name="c_id" value="<?php echo $cl_idsss; ?>">
				<input type ="hidden" name="ee_id" value="<?php echo $e_id; ?>">
				<input type ="hidden" name="p_price" value="<?php echo $p_price; ?>">
				<input type ="hidden" name="p_id" value="<?php echo $p_id; ?>">
				<input type ="hidden" name="discount" value="<?php echo $c_dis; ?>">
				<input type ="hidden" name="extra_bill" value="<?php echo $extra_bill; ?>">
				<input type="hidden" name="bill_date" value="<?php echo $update_date;?>" />
				<input type="hidden" name="mk_id" value="<?php echo $mk_id;?>" />
				<input type="hidden" name="breseller" value="<?php echo $breseller;?>" />
				<input type="hidden" name="macz_id" value="<?php echo $macz_id;?>" />
					<div class="modal-body">
						<p>	
							<label>Client Info</label>
							<span class="field">
								<input type="text" name="" id="" class="input-xxlarge" value="<?php echo $cl_idsss;?> | <?php echo $p_name;?> | <?php echo $bandwith;?>" readonly />
							</span>
						</p>
						<p>
							<label>Generated Bill Date</label>
							<span class="field"><input type="text" name="" id="" class="input-xxlarge" value="<?php echo date('Y-m-d');?>" readonly /></span>
						</p>
						<?php if($user_type == 'admin' || $user_type == 'superadmin') {?>
						<p>
							<label>Last Bill Adjastment</label>
							<span class="field"><input type="text" name="bill_amount" id="" class="input-xxlarge" value="<?php echo $unusedday;?>" /></span>
						</p>
						<?php } else{?>
						<p>
							<label>Last Bill Adjastment</label>
							<span class="field"><input type="text" name="bill_amount" id="" readonly class="input-xxlarge" value="<?php echo $unusedday;?>" /></span>
						</p>
						<?php } if($user_type == 'admin' || $user_type == 'superadmin' || $user_type == 'accounts' || $user_type == 'support') {?>
						<p>
							<label>Payment Deadline</label>
							<span class="field"><input type="text" name="payment_deadline" placeholder="Date in every month like: 7" id="" class="input-xxlarge" value="<?php echo $payment_deadline;?>"></span>
						</p>
						<p>
							<label>Termination Date</label>
							<span class="field"><input type="text" name="termination_date" placeholder="Client Discount Date (Optional)" id="" class="input-xxlarge datepicker" value="<?php echo $termination_date;?>">
							<br><a style="color: red;"><?php if($termination_note != '') {echo 'Last '.$termination_note;}?></a></span>
						</p>
						<?php } else{?>
						<p>
							<label>Payment Deadline</label>
							<span class="field"><input type="text" name="payment_deadline" placeholder="Date in every month like: 7" readonly id="" class="input-xxlarge" value="<?php echo $payment_deadline;?>"></span>
						</p>
						<p>
							<label>Termination Date</label>
							<span class="field"><input type="text" name="termination_date" placeholder="Client Discount Date (Optional)" readonly id="" class="input-xxlarge" value="<?php echo $termination_date;?>">
							<br><a style="color: red;"><?php if($termination_note != '') {echo 'Last '.$termination_note;}?></a></span>
						</p>
						<?php } ?>
					</div>
					<div class="modal-footer">
						<button type="reset" class="btn">Reset</button>
						<button class="btn btn-primary" type="submit">Submit</button>
					</div>
				</form>			
			</div>
		</div>
	</div>
<?php } else{ if($terminate == '0'){ if($todayssss >= $start_date && $todayssss < $end_date || $todayssss <= $start_date && $todayssss < $end_date) {
	
								$API = new routeros_api();
								$API->debug = false;
								if ($API->connect($ServerIP, $Username, $Pass, $Port)) {
									

								   $arrID =	$API->comm("/ppp/secret/getall", 
											  array(".proplist"=> ".id","?name" => $c_id,));

											$API->comm("/ppp/secret/set",
											array(".id" => $arrID[0][".id"],"disabled"  => "no",));
								
	
					$queryq ="UPDATE clients SET con_sts = 'Active', con_sts_date = '$update_date' WHERE c_id = '$c_id'";
							if (!mysql_query($queryq))
							{
							die('Error: ' . mysql_error());
							}
					$query1q ="INSERT INTO con_sts_log (c_id, con_sts, update_date, update_time, update_by) VALUES ('$c_id', 'Active', '$update_date', '$update_time', '$ee_id')";
							if (!mysql_query($query1q))
							{
							die('Error: ' . mysql_error());
							}
							
		$API->disconnect();

//----Telegream---------------------
$sqlmqq = mysql_query("SELECT c.c_name, c.cell, e.`con_sts`, e.`update_date`, e.`update_time`, q.e_name AS updatebyy, e.`update_date_time` FROM con_sts_log AS e 
						LEFT JOIN emp_info AS q ON q.e_id = e.update_by
						LEFT JOIN clients AS c ON c.c_id = e.c_id
						WHERE e.c_id = '$c_id' ORDER BY e.id DESC LIMIT 1");
										
$rowmkqq = mysql_fetch_assoc($sqlmqq);
$cname = $rowmkqq['c_name'];
$ccell = $rowmkqq['cell'];
$update_datetime = $rowmkqq['update_date_time'];
$updatebyy = $rowmkqq['updatebyy'];

if($tele_sts == '0' && $tele_client_status_sts == '0'){
$telete_way = 'client_status';
$msg_body='..::[Reseller Client Activated]::..
'.$cname.' ['.$c_id.'] ['.$ccell.']

Cost & Days: Already Recharged

By: '.$updatebyy.' at '.$update_datetime.'
'.$tele_footer.'';

include('../web/include/telegramapicore.php');
}
//----Telegream---------------------		
		
		
		?>
<html>
	<body>
	<form action="Clients" method="post" name="ok">
		<input type="hidden" name="sts" value="StatusInactive">
	</form>
	<script language="javascript" type="text/javascript">
		document.ok.submit();
	</script>
	</body>
</html>
		
							<?php	}else{
								echo 'Not Pssible to Reactive.';
							}
	?>

<?php } else{ 


$quemmc = mysql_query("SELECT con_sts, update_date, update_time, DATE_FORMAT(update_date, '%D %M %Y') AS update_date FROM con_sts_log WHERE c_id = '$c_id' ORDER BY id DESC LIMIT 1");
$rowumc = mysql_fetch_assoc($quemmc);

$yrata= strtotime($rowumc['update_date']);
$date_mon = date('d F, Y', $yrata);

$yrata1= strtotime($termination_date);
$date_t = date('d F, Y', $yrata1);

if($con_sts == 'Active'){
	$texttt = $con_sts.' Till '.$date_t;
	$colorrr = "style='font-size: 13px;text-align: center;color: darkgreen;font-weight: bold'";
}
else{
	$texttt = $con_sts.' Since '.$date_mon;
	$colorrr = "style='font-size: 13px;text-align: center;color: red;font-weight: bold'";
}

?>
	<div class="box box-primary">
		<div class="box-header">
			<div class="modal-content">
				<div class="modal-header" style="padding: 0 0 0 10px;">
					<h5>New Recharge/Extend</h5>
				</div>
				<form id="" name="form1" class="stdform" method="post" action="ClientInactiveAdjastmentQuery">
				<input type ="hidden" name="id" value="<?php echo $idq; ?>">
				<input type ="hidden" name="c_id" value="<?php echo $c_id; ?>">
				<input type ="hidden" name="z_id" value="<?php echo $z_id; ?>">
				<input type ="hidden" name="start_date" value="<?php echo $startdate; ?>">
				<input type ="hidden" name="end_date" value="<?php echo $update_date; ?>">
				<input type="hidden" name="mk_id" value="<?php echo $mk_id;?>" />
				<input type="hidden" name="breseller" value="<?php echo $breseller;?>" />
				<input type="hidden" name="ee_id" value="<?php echo $e_id;?>" />
				<input type="hidden" name="macuser" value="1" />
				<input type="hidden" name="macz_id" value="<?php echo $macz_id;?>" />
					<div class="modal-body" style="min-height: 180px;padding: 1px;">
						<div class="span6">
							<table class="table table-bordered table-invoice">
								<tr>
									<td colspan="2" style="font-size: 12px;font-weight: bold;text-align: center;"><?php echo $c_id;?></td>
								</tr>
								<tr>
									<td colspan="2" style="font-size: 12px;text-align: center;"><?php echo $c_name;?> - <?php echo $cell;?><br><?php echo $address;?></td>
								</tr>
								<tr>
									<td colspan="2" style="font-size: 12px;font-weight: bold;text-align: center;"><?php echo $p_name; ?> || <?php echo $bandwith; ?> || <?php echo $p_price; ?>৳</td>
								</tr>
								<tr>
									<td colspan="2" style="font-weight: bold;">
									<select data-placeholder="Choose a Package" class="chzn-select" style="width:100%;text-align: center;" name="p_id" id="p_id" required="" onchange="myFunctionnn(event)"/>
												<option value=""></option>
													<?php while ($row1=mysql_fetch_array($result1)) { ?>
												<option value="<?php echo $row1['p_id']?>"<?php if($p_id == $row1['p_id']) echo 'selected="selected"';?>><?php echo $row1['p_name']; ?> (<?php echo $row1['p_price']; ?> - <?php echo $row1['bandwith']; ?>)</option>
													<?php } ?>
									</select>
									</td>
								</tr>
								<tr>
									<td colspan="2" style="font-size: 12px;font-weight: bold;text-align: center;bold;color: maroon;">Old Termination Date: <?php $yrdata= strtotime($termination_date); $dateee = date('d-F, Y', $yrdata); echo $dateee; ?></td>
								</tr>
								<tr>
									<td colspan="2" style="font-size: 13px;font-weight: bold;text-align: center;"><div id="dayprice1" style="font-weight: bold;margin-left: 0px;" class="field"><div id="dayprice" style="font-weight: bold;margin-left: 0px;" class="field"><a style="color: #f95a5a;font-size: 13px;">Cost: <?php echo number_format($daycost,2); ?>৳</a> <a style="color: green;font-size: 13px;">  Till <?php echo $dateee; ?></a></div></div></td>
								</tr>
								<tr>
									<td class="width40" style="font-weight: bold;font-size: 14px;text-align: right;padding: 10px 10px 0 0;">Recharge Days</td>
									<td class="width40" style="font-weight: bold;"><input type="text" name="duration" value="<?php echo $minimum_day;?>" id="duration" style="width:70%;font-weight: bold;" placeholder="<?php echo $minimum_day;?>" required=""></td>
								</tr>
								<tr>
									<td colspan="2" style="font-size: 13px;font-weight: bold;text-align: center;"><a class="field" style="color: red;font-size: 10px;font-weight: bold;">[Minimum <?php echo $minimum_day;?> Days]</a></td>
								</tr>
							</table>
						</div><!--span6-->
					</div>
					<div class="modal-footer">
						<button type="reset" class="btn">Reset</button>
						<button class="btn btn-primary" type="submit">Submit</button>
					</div>
				</form>			
			</div>
		</div>
	</div>

<?php }
}
else{
	echo '<br><h3 style="margin-left: 20px;color: red;">Your Account Has Been Terminated. Not Possible to Active This Client.</h3><br>';
}
}}
else{
	echo 'You must Generate Bill fast then try again';
	
//$query ="UPDATE clients SET con_sts = 'Active' WHERE c_id = '$c_id'";
//$query1 ="insert into con_sts_log (c_id, con_sts, update_date, update_time, update_by) VALUES ('$c_id', 'Active', '$update_date', '$update_time', '$e_id')";
//$result = mysql_query($query) or die("inser_query failed: " . mysql_error() . "<br />");
//$result1 = mysql_query($query1) or die("inser_query failed: " . mysql_error() . "<br />");
?>
<!--
<html>
	<body>
	<form action="Clients" method="post" name="ok">
		<input type="hidden" name="sts" value="Status<?php echo $a; ?>">
	</form>
	<script language="javascript" type="text/javascript">
		document.ok.submit();
	</script>
	</body>
</html>
-->
<?php 
	}
include('include/footer.php');
?>
<script type="text/javascript">
$(document).ready(function()
{    
 $("#duration").keyup(function()
 {  
  var name = $(this).val(); 
  
  if(name.length > 0)
  {  
   $("#dayprice").html('checking...');
   $.ajax({
    
    type : 'POST',
	url  : "durationdayextend.php?clientid="+<?php echo $clientid;?>,
    data : $(this).serialize(),
    success : function(data)
        {
              $("#dayprice").html(data);
           }
    });
    return false;
   
  }
  else
  {
   $("#dayprice").html('');
  }
 });
 
});
</script>
<script type="text/javascript">
jQuery(document).ready(function ()
{
        jQuery('select[name="p_id"]').on('change',function(){
           var CatID1 = jQuery(this).val();
           if(CatID1)
			{
				$(document).ready(function()
				{    
				 $("#duration").keyup(function()
				 {  
				  var name = $(this).val(); 
				  if(name.length > 0)
				  {  
				   $("#dayprice1").html('checking...');

				   $.ajax({
					
					type : 'POST',
					url  : "duration-packageprice-calculation.php?p_id="+CatID1,
					data : $(this).serialize(),
					success : function(data)
						{
							  $("#dayprice1").html(data);
						   }
					});
					return false;
				  }
				  else
				  {
				   $("#dayprice1").html('');
				  }
				 });
				});
			}
        });
});

function myFunctionnn() {
    document.getElementById("duration").value = ''
}
</script>

<style>
.chzn-container{width: 100% !important;}
</style>