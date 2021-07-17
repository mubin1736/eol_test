<?php
$titel = "Re-connect";
$Client = 'active';
include('include/hader.php');
extract($_POST);
$user_type = $_SESSION['SESS_USER_TYPE'];

$e_id = $_SESSION['SESS_EMP_ID'];
ini_alter('date.timezone','Asia/Almaty');
$update_date = date('Y-m-d', time());
$update_time = date('H:i:s', time());


$que = mysql_query("SELECT id, con_sts, breseller, mac_user FROM clients WHERE c_id = '$c_id'");
$row = mysql_fetch_assoc($que);
$con_sts = $row['con_sts'];		
$breseller = $row['breseller'];		
$macuserrr = $row['mac_user'];		
$clientid = $row['id'];		

$todayssss = strtotime(date('Y-m-d', time()));
$ques = mysql_query("SELECT b.id, b.c_id, c.cell, c.address, c.z_id, c.con_sts, c.mac_user, c.mk_id, c.c_name, c.payment_deadline, c.termination_date, b.days, b.start_date, b.start_time, b.end_date, b.p_id, p.p_name, p.bandwith, p.p_price, b.bill_amount FROM billing_mac AS b
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

$sqlqqmm = mysql_query("SELECT minimum_day, terminate FROM emp_info WHERE z_id = '$z_id'");
$row22m = mysql_fetch_assoc($sqlqqmm);
$minimum_day = $row22m['minimum_day'];
$terminate = $row22m['terminate'];

$query1="SELECT p_id, p_name, p_price, bandwith FROM package WHERE z_id = '$z_id' AND p_price != '0.00' order by id ASC";
$result1=mysql_query($query1);

$quemmc = mysql_query("SELECT con_sts, update_date, update_time, DATE_FORMAT(update_date, '%D %M %Y') AS update_date FROM con_sts_log WHERE c_id = '$c_id' ORDER BY id DESC LIMIT 1");
$rowumc = mysql_fetch_assoc($quemmc);

$yrata= strtotime($rowumc['update_date']);
$date_mon = date('d-F, Y', $yrata);

$yrata1= strtotime($termination_date);
$date_t = date('d-F, Y', $yrata1);

if($con_sts == 'Active'){
	$texttt = 'Recharged Till '.$date_t;
	$colorrr = "style='font-size: 13px;text-align: center;color: darkgreen;font-weight: bold'";
}
else{
	$texttt = $con_sts.' Since '.$date_mon;
	$colorrr = "style='font-size: 13px;text-align: center;color: red;font-weight: bold'";
}

//--------------------------------------------------------------------------------------------------------------------------------------------------

if($terminate == '0'){
$todayyyy = date('Y-m-d', time());

//$durations = strip_tags($_POST['duration']);

$Date2ww = date('Y-m-d', strtotime($termination_date . " + ".$minimum_day." day"));
$yrdatayy= strtotime($Date2ww);
$newdateee = date('F d, Y', $yrdatayy);

$Date2 = date('Y-m-d', strtotime($todayyyy . " + ".$minimum_day." day"));
$yrdata= strtotime($Date2);
$dateee = date('F d, Y', $yrdata);

$resultwww = mysql_query("SELECT p_id, p_price FROM package WHERE p_id = '$p_id' AND status = '0'");
$rowprice = mysql_fetch_assoc($resultwww);
$old_p_price= $rowprice['p_price'];

$packageoneday = $old_p_price/30;
$daycost = $minimum_day*$packageoneday;

if($todayssss > $start_date && $todayssss < $end_date || $todayssss <= $start_date && $todayssss <= $end_date) {?>
	<div class="box box-primary">
		<div class="box-header">
			<div class="modal-content">
				<div class="modal-header" style="padding: 0 0 0 10px;">
					<h5>New Recharge/Extend</h5>
				</div>
				<form id="" name="form1" class="stdform" method="post" action="ClientsRechargeQuery">
				<input type ="hidden" name="c_id" value="<?php echo $c_id; ?>">
				<input type ="hidden" name="z_id" value="<?php echo $z_id; ?>">
				<input type ="hidden" name="p_id" value="<?php echo $p_id; ?>">
				<input type ="hidden" name="start_date" value="<?php echo $startdate; ?>">
				<input type ="hidden" name="start_time" value="<?php echo $starttime; ?>">
				<input type ="hidden" name="end_date" value="<?php echo $enddate; ?>">
				<input type="hidden" name="mk_id" value="<?php echo $mk_id;?>" />
				<input type="hidden" name="entry_by" value="<?php echo $e_id;?>" />
				<input type="hidden" name="con_sts" value="<?php echo $con_sts;?>" />
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
								<td colspan="2" style="font-size: 12px;font-weight: bold;text-align: center;bold;color: maroon;">Old Termination Date: <?php $yrdata= strtotime($termination_date); $dateee = date('d-F, Y', $yrdata); echo $dateee; ?><br><a <?php echo $colorrr;?> class="field">[<?php echo $texttt;?>]</a></td>
							</tr>
							<tr>
								<td colspan="2" style="font-size: 13px;font-weight: bold;text-align: center;"><div id="dayprice" style="font-weight: bold;margin-left: 0px;" class="field"><a style="color: #f95a5a;font-size: 13px;">Cost: <?php echo number_format($daycost,2); ?>৳</a> <a style="color: green;font-size: 13px;">  Till <?php echo $dateee; ?></a></div></td>
							</tr>
							<tr>
								<td class="width40" style="font-weight: bold;font-size: 14px;text-align: right;padding: 10px 10px 0 0;">Recharge Days</td>
								<td class="width40" style="font-weight: bold;"><input type="text" name="duration" id="duration" value="<?php echo $minimum_day;?>" style="width:70%;font-weight: bold;" placeholder="<?php echo $minimum_day;?>" required=""></td>
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
<?php } else{ ?>
	<div class="box box-primary">
		<div class="box-header">
			<div class="modal-content">
				<div class="modal-header" style="padding: 0 0 0 10px;">
					<h5>New Recharge</h5>
				</div>
				<form id="" name="form1" class="stdform" method="post" action="ClientsRechargeQuery">
				<input type ="hidden" name="c_id" value="<?php echo $c_id; ?>">
				<input type ="hidden" name="z_id" value="<?php echo $z_id; ?>">
				<input type ="hidden" name="start_date" value="<?php echo $startdate; ?>">
				<input type ="hidden" name="start_time" value="<?php echo $starttime; ?>">
				<input type ="hidden" name="end_date" value="<?php echo $update_date; ?>">
				<input type="hidden" name="mk_id" value="<?php echo $mk_id;?>" />
				<input type="hidden" name="entry_by" value="<?php echo $e_id;?>" />
				<input type="hidden" name="con_sts" value="<?php echo $con_sts;?>" />
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
									<select data-placeholder="Choose a Package" class="chzn-select" style="width:100%;text-align: center;" name="p_id" id="p_id" required="" onchange="myFunctionnn(event)" />
												<option value=""></option>
													<?php while ($row1=mysql_fetch_array($result1)) { ?>
												<option value="<?php echo $row1['p_id']?>"<?php if($p_id == $row1['p_id']) echo 'selected="selected"';?>><?php echo $row1['p_name']; ?> (<?php echo $row1['p_price']; ?> - <?php echo $row1['bandwith']; ?>)</option>
													<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="2" style="font-size: 12px;font-weight: bold;text-align: center;bold;color: maroon;">Old Termination Date: <?php $yrdata= strtotime($termination_date); $dateee = date('d-F, Y', $yrdata); echo $dateee; ?><br><a <?php echo $colorrr;?> class="field"><?php echo $texttt;?></a></td>
							</tr>
							<tr>
								<td colspan="2" style="font-size: 13px;font-weight: bold;text-align: center;"><div id="dayprice1" style="font-weight: bold;margin-left: 0px;" class="field"><div id="dayprice" style="font-weight: bold;margin-left: 0px;" class="field"><a style="color: #f95a5a;font-size: 13px;">Cost: <?php echo number_format($daycost,2); ?>৳</a> <a style="color: green;font-size: 13px;">  Till <?php echo $dateee; ?></a></div></div></td>
							</tr>
							<tr>
								<td class="width40" style="font-weight: bold;font-size: 14px;text-align: right;padding: 10px 10px 0 0;">Recharge Days</td>
								<td class="width40" style="font-weight: bold;"><input type="text" name="duration" id="duration" value="<?php echo $minimum_day;?>" style="width:70%;font-weight: bold;" placeholder="<?php echo $minimum_day;?>" required=""></td>
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

<?php }}
else{
	echo '<br><h3 style="margin-left: 10px;color: red;">Your Account Has Been Terminated. Not Possible to Recharge.</h3><br>';
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
</script>
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
	url  : "duration-packageprice-calculation.php?p_id="+<?php echo $p_id;?>,
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

function myFunctionnn() {
    document.getElementById("duration").value = ''
}
</script>