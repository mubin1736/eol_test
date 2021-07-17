<?php
$titel = "Add Client";
include('include/hader.php');
extract($_POST);

ini_alter('date.timezone','Asia/Almaty');
//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'Clients' AND $user_type = '1'");
if(mysql_num_rows($access) > 0){
//---------- Permission -----------

$query11="SELECT id, Name, ServerIP FROM mk_con WHERE sts = '0' ORDER BY id ASC";
$result11=mysql_query($query11);

if($userr_typ == 'mreseller') {
$result=mysql_query("SELECT z.z_id, z.z_name, e.e_name AS resellername, e.e_id AS resellerid, e.minimum_day, e.prefix FROM zone AS z LEFT JOIN emp_info AS e ON e.e_id = z.e_id WHERE z.status = '0' AND z.e_id = '$e_id'");
}
else{
$result=mysql_query("SELECT z.z_id, z.z_name, e.e_name AS resellername, e.e_id AS resellerid, e.minimum_day, e.prefix FROM zone AS z LEFT JOIN emp_info AS e ON e.e_id = z.e_id WHERE z.status = '0' AND z.e_id != '' AND z.z_id = '$z_id'");
}
 $row = mysql_fetch_assoc($result);

if($user_type == 'mreseller'){
$query1="SELECT p_id, p_name, p_price, bandwith, mk_profile FROM package WHERE z_id = '$macz_id' AND p_price != '0.00' AND status = '0' order by id ASC";
$sss1m = mysql_query("SELECT reseller_logo, billing_type FROM emp_info WHERE z_id ='$macz_id'");
$sssw1m = mysql_fetch_assoc($sss1m);
}
else{
$query1="SELECT p_id, p_name, p_price, bandwith, mk_profile FROM package WHERE z_id = '$z_id' AND status = '0' order by id ASC";
$sss1m = mysql_query("SELECT reseller_logo, billing_type FROM emp_info WHERE z_id ='$z_id'");
$sssw1m = mysql_fetch_assoc($sss1m);
}
$result1=mysql_query($query1);

if($user_type == 'mreseller'){
$sql2oo ="SELECT last_id, terminate, e_name FROM emp_info WHERE z_id = '$macz_id'";
}
else{
$sql2oo ="SELECT last_id, terminate, e_name FROM emp_info WHERE z_id = '$z_id'";
}
$query2oo = mysql_query($sql2oo);
$row2ff = mysql_fetch_assoc($query2oo);
$idzff = $row2ff['last_id'];
$reseller_terminate = $row2ff['terminate'];
$eeename = $row2ff['e_name'];
$com_id = $idzff + 1;

if($user_type == 'admin' || $user_type == 'superadmin'){
$sql1q = mysql_query("SELECT c_id, SUM(bill_amount) AS totbill FROM billing_mac WHERE z_id = '$macz_id'");
$rowq = mysql_fetch_array($sql1q);


$sql1z1 = mysql_query("SELECT p.id, SUM(p.pay_amount) AS repayment, SUM(p.discount) AS rediscount, (SUM(p.pay_amount) + SUM(p.discount)) AS retotalpayments FROM `payment_macreseller` AS p
						WHERE p.z_id = '$macz_id' AND p.sts = '0'");
$rowwz = mysql_fetch_array($sql1z1);

$aaaaaa = $rowwz['retotalpayments']-$rowq['totbill'];
}

$billing_typee = $sssw1m['billing_type'];
	?>
	<div class="box box-primary">
	<?php if($aaaa > 0 && $billing_typee == 'Prepaid' || $billing_typee == 'Postpaid'){ ?>
		<div class="span6">
			<div class="accordion accordion-inverse" style="padding: 5px 0px 5px 10px;font-size: 15px;font-weight: bold;border-bottom: 2px solid #bbb;">
				<a href="#">Add New Client (PPPoE)</a>
			</div>
		</div>
		<div class="box-header" style="padding: 0px 10px 0px 10px;">
					<form class="stdform" method="post" action="MACClientAddQuery" enctype="multipart/form-data">
						<input type="hidden" name="u_type" value="client" />
						<input type="hidden" name="mac_user" value="1" />
						<input type="hidden" name="sentsms" value="No" />
						<input type="hidden" name="line_typ" value="1" />
						<input type="hidden" name="entry_by" value="<?php echo $e_id; ?>" />
						<input type="hidden" name="entry_date" value="<?php echo date("Y-m-d");?>" />
						<input type="hidden" name="entry_time" value="<?php echo date("h:i a");?>" />
						<input type="hidden" name="qcalculation" value="Manual" />
						<input type="hidden" name="p_m" value="Home Cash" />
						<input type="hidden" name="con_type" value="Home" />
						<input type="hidden" name="connectivity_type" value="Shared" />
						<input type="hidden" name="req_cable" value="" />
						<input type="hidden" name="ip" value="" />
						<input type="hidden" name="mac" value="" />
						<input type="hidden" name="con_sts" value="Active" />
						<input type="hidden" name="cable_type" value="UTP" />
						<input type="hidden" name="join_date" value="<?php echo date("Y-m-d");?>" />
						<input type="hidden" name="nid" value="" />
						<input type="hidden" name="com_id" value="<?php echo $com_id;?>" />

						<?php if($userr_typ == 'mreseller') { ?>
							<input type="hidden" name="z_id" value="<?php echo $macz_id; ?>" />
						<?php } else{ ?>
							<input type="hidden" name="z_id" value="<?php echo $row['z_id']; ?>" />
						<?php } ?>
							<p>
								<label style="width: 100%;font-weight: bold;font-size: 10px;">Package*</label>
								<select data-placeholder="Choose a Package" class="chzn-select" style="width:100%;" name="p_id" id="p_id" required="" >
									<option value=""></option>
										<?php while ($row1=mysql_fetch_array($result1)) { ?>
									<option value="<?php echo $row1['p_id']?>"><?php echo $row1['p_name']; ?> (<?php echo $row1['p_price']; ?> - <?php echo $row1['bandwith']; ?>)</option>
										<?php } ?>
								</select>
								
							</p>
							<p>
							<span id="dayprice" style="font-weight: bold;"></span>
								<label style="width: 100%;font-weight: bold;font-size: 10px;">Activation Days*</label>
								<span class="field" style="margin-left: 0px;font-weight: bold;"><input type="text" name="duration" id="duration" style="width:80px;font-weight: bold;font-size: 20px;height: 30px;" placeholder="30" required=""></span>
							</p>
							<p>
								<span class="field" style="margin-left: 0px;color: red;font-weight: bold;">[Minimum <?php echo $row['minimum_day'];?> Days]</span>
							</p>
							<p id="result" style="font-weight: bold;"></p>
							<p>
								<label style="width: 100%;font-weight: bold;font-size: 10px;">Client Name*</label>
								<input type="text" name="c_name" placeholder="" style="width:100%;" class="input-xxlarge" required="" />
							</p>
							<p>
								<label style="width: 100%;font-weight: bold;font-size: 10px;">Client ID*</label>
								<input type="text" name="c_id" id="name" placeholder="User id must be at least 3 characters long" style="width:100%;" class="input-xxlarge" required="" />
							</p>
							<p>
								<label style="width: 100%;font-weight: bold;font-size: 10px;" class="control-label" for="passid">Login Password*</label>
								<input type="text" name="passid" class="input-xxlarge" size="12" style="width:100%;" required="" />
							</p>
							<p>
								<label style="width: 100%;font-weight: bold;font-size: 10px;">Cell No*</label>
								<input type="text" name="cell" style="width:100%;" placeholder="Must Use 8801XXXXXXXXX" required="" value = '88' id="" class="input-xxlarge" />
							</p>
							<p>
								<label style="width: 100%;">Permanent Discount</label>
								<span class="field" style="margin-left: 0px;"><input type="text" name="discount" style="width:30%;" value="0.00"/></span>
							</p>
							<p>
								<label style="width: 100%;">Permanent Extra Bill</label>
								<span class="field" style="margin-left: 0px;"><input type="text" name="extra_bill" style="width:30%;" value="0.00"/></span>
							</p>
							<p>
								<label style="width: 100%;font-size: 10px;">Alternative Cell No</label>
								<span class="field" style="margin-left: 0px;"><input type="text" style="width:100%;" name="cell1" placeholder="Alternative Cell No:1" id="" class="input-xxlarge" /></span>
							</p>
							<p>
								 <label style="width: 100%;font-size: 10px;">Present Address</label>
								<span class="field" style="margin-left: 0px;"><textarea type="text" style="width:100%;" name="address" id="" class="input-xxlarge"/></textarea></span>
							</p>
							<p>
								 <label style="width: 100%;font-size: 10px;">Thana</label>
								<span class="field" style="margin-left: 0px;"><input type="text" style="width:100%;" name="thana" placeholder="" id="" class="input-xxlarge" /></span>
							</p>

							<p>
								<label style="width: 100%;font-size: 10px;">Note</label>
								<span class="field" style="margin-left: 0px;"><textarea type="text" style="width:100%;" name="note" placeholder="Optional" id="" class="input-xxlarge" /></textarea></span>
							</p>
					<div class="modal-footer">
						<button type="reset" class="btn">Reset</button>
						<button class="btn btn-primary" type="submit">Submit</button>
					</div>
			</form>	
		</div>

<?php  } else{ ?>
		<div class="span6">
			<div class="accordion accordion-inverse" style="padding: 5px 0px 5px 10px;font-size: 15px;font-weight: bold;border-bottom: 2px solid #bbb;">
				<a href="#">Have not sufficient balance to add new client.</a>
			</div>
		</div>
<?php  }  ?>
</div>
<?php }
else{
	include('include/index');
}
include('include/footer.php');
?>
<script type="text/javascript">
$(document).ready(function()
{    
 $("#name").keyup(function()
 {  
  var name = $(this).val(); 
  
  if(name.length > 3)
  {  
   $("#result").html('checking...');
   $.ajax({
    
    type : 'POST',
    url  : 'username-check.php',
    data : $(this).serialize(),
    success : function(data)
        {
              $("#result").html(data);
           }
    });
    return false;
   
  }
  else
  {
   $("#result").html('');
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
				   $("#dayprice").html('checking...');

				   $.ajax({
					
					type : 'POST',
					url  : "duration-packageprice-calculation.php?p_id="+CatID1,
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
			}
        });
});
</script>
<style>
#frm-image-upload{
    padding: 0px;
    background-color: lightblue;
}

.form-row {
    padding: 20px;
    border-top: #8aacb7 1px solid;
}

.button-row {
    padding: 10px 20px;
    border-top: #8aacb7 1px solid;
}

#btn-submit {
    padding: 10px 40px;
    background: #586e75;
    border: #485c61 1px solid;
    color: #FFF;
    border-radius: 2px;
}

.file-input {
    background: #FFF;
    padding: 5px;
    margin-top: 5px;
    border-radius: 2px;
    border: #8aacb7 1px solid;
}

.response {
    padding: 10px;
    margin-top: 10px;
    border-radius: 2px;
}

.error {
    background: #fdcdcd;
    border: #ecc0c1 1px solid;
}

.success {
    background: #c5f3c3;
    border: #bbe6ba 1px solid;
}
</style>