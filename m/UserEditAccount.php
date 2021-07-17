<?php
$titel = "Edit Profile";
include('include/hader.php');
$id = $_SESSION['SESS_MEMBER_ID'];
extract($_POST); 

$sql = ("SELECT id, user_name, e_id, user_id, pw, password, email, user_type, image FROM login WHERE id = '$id'");
$query = mysql_query($sql);
$row = mysql_fetch_assoc($query);
		$idssss= $row['id'];
		$user_name= $row['user_name'];
		$e_id = $row['e_id'];
		$user_id = $row['user_id'];
		$password = sha1($row['password']);
		$emaill= $row['email'];
		$user_type= $row['user_type'];
		$image= $row['image'];
		if($image==''){
			$image = '../web/emp_images/no_img.jpg';
		}
if($user_type == 'client' || $user_type == 'breseller'){
	$sqlf = mysql_query("SELECT c.c_name, l.id, c.z_id, b.b_name, c.edit_sts, c.terms, l.nid_fond, l.nid_back, c.father_name, c.bill_man, c.old_address, z.z_name, c.technician, c.termination_date,
	c.box_id, c.raw_download, c.calculation, c.breseller, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, 
	l.e_id AS userid, l.pw, c.b_date, c.mk_id, m.Name AS mk_name, m.ServerIP, c.cell, cell1, cell2, cell3, cell4, mac_user, payment_deadline, c.occupation, c.email, 
	p_m, c.address, c.thana, c.join_date, DATE_FORMAT(c.join_date, '%D %M %Y') AS joinn, c.opening_balance, c.con_type, c.connectivity_type, c.ip, c.mac, c.cable_sts, c.discount, c.con_sts, c.req_cable, c.cable_type, 
	c.nid, c.p_id, p.p_name, p.p_price, p.bandwith, c.signup_fee, c.note FROM clients AS c
		LEFT JOIN zone AS z
		ON z.z_id = c.z_id
		LEFT JOIN box AS b
		ON b.box_id = c.box_id
		LEFT JOIN package AS p
		ON p.p_id = c.p_id 
		LEFT JOIN mk_con AS m
		ON m.id = c.mk_id
		LEFT JOIN login AS l
		ON l.e_id = c.c_id
		WHERE c.c_id ='$e_id' ");
		
$rowsdfs = mysql_fetch_assoc($sqlf);
		$c_name= $rowsdfs['c_name'];
		$z_id= $rowsdfs['z_id'];
		$z_name = $rowsdfs['z_name'];
		$b_name = $rowsdfs['b_name'];
		$father_name = $rowsdfs['father_name'];
		$occupation = $rowsdfs['occupation'];
		if($rowsdfs['nid_fond']==''){
			$nid_fondd = 'images/no_nid.png';
		}
		else{
			$nid_fondd = $rowsdfs['nid_fond'];
		}
		if($rowsdfs['nid_back']==''){
			$nid_backk = 'images/no_nid.png';
		}
		else{
			$nid_backk = $rowsdfs['nid_back'];
		}
		$cell= $rowsdfs['cell'];
		$cell1= $rowsdfs['cell1'];
		$cell2= $rowsdfs['cell2'];
		$cell3= $rowsdfs['cell3'];
		$cell4= $rowsdfs['cell4'];
		$opening_balance = $rowsdfs['opening_balance'];
		$email= $rowsdfs['email'];
		$address= $rowsdfs['address'];
		$old_address= $rowsdfs['old_address'];
		$thana= $rowsdfs['thana'];
		$previous_isp= $rowsdfs['previous_isp'];
		$join_date= $rowsdfs['joinn'];
		$con_type= $rowsdfs['con_type'];
		$mac_user = $rowsdfs['mac_user'];
		$connectivity_type= $rowsdfs['connectivity_type'];
		$ip= $rowsdfs['ip'];
		$mac= $rowsdfs['mac'];
		$cable_sts= $rowsdfs['cable_sts'];
		$con_sts= $rowsdfs['con_sts'];
		$req_cable= $rowsdfs['req_cable'];
		$cable_type= $rowsdfs['cable_type'];
		$nid= $rowsdfs['nid'];
		$p_id= $rowsdfs['p_id'];
		$signup_fee= $rowsdfs['signup_fee'];
		$note= $rowsdfs['note'];
		$discount = $rowsdfs['discount'];
		$p_m = $rowsdfs['p_m'];
		$p_name = $rowsdfs['p_name'];
		$p_price = $rowsdfs['p_price'];
		$bandwith = $rowsdfs['bandwith'];
		$mk_name = $rowsdfs['mk_name'];
		$ServerIP = $rowsdfs['ServerIP'];
		$payment_deadline = $rowsdfs['payment_deadline'];
		$b_date = $rowsdfs['b_date'];
		$pw = $rowsdfs['pw'];
		$mk_id = $rowsdfs['mk_id'];
		$lid = $rowsdfs['id'];
		$raw_download = $rowsdfs['raw_download'];
		$youtube_bandwidth = $rowsdfs['youtube_bandwidth'];
		$raw_upload = $rowsdfs['raw_upload'];
		$total_bandwidth = $rowsdfs['total_bandwidth'];
		$bandwidth_price = $rowsdfs['bandwidth_price'];
		$youtube_price = $rowsdfs['youtube_price'];
		$total_price = $rowsdfs['total_price'];
		$breseller = $rowsdfs['breseller'];
		$box_id = $rowsdfs['box_id'];
		$technician = $rowsdfs['technician'];
		$bill_man = $rowsdfs['bill_man'];
		$termination_date = $rowsdfs['termination_date'];
		$edit_sts = $rowsdfs['edit_sts'];
		$terms = $rowsdfs['terms'];
}
		
?>
<div aria-hidden="false" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide fade in" id="myModal101">
	<form class="stdform" method="post" action="UserEditAccountQuery" name="form" enctype="multipart/form-data">
		<input type="hidden" name="idssss" value="<?php echo $idssss;?>"/>
		<input type="hidden" name="user_type" value="<?php echo $user_type;?>"/>
		<input type="hidden" name="way" value="picchange" />
		<input type="hidden" name="imgway" value="main_img" />
		<input type="hidden" name="backway" value="" />
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="h5"> Change Picture</h5>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="popdiv">
							<div class="col-1">Choose Image</div>
							<div class="col-2">
								<div class="fileupload fileupload-new" data-provides="fileupload">
									<div class="input-append">
										<div class="uneditable-input span2" style="height: 18px;">
											<i class="iconfa-file fileupload-exists"></i>
											<span class="fileupload-preview"></span>
										</div>
										<span class="btn btn-file">
											<span class="fileupload-new">Select Image</span>
											<span class="fileupload-exists">Change</span>
											<input type="file" class="file-input" name="file-input" onchange="readURL(this);">
										</span>
										<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
									</div>
								</div>
							</div>
						</div>
						<div class="popdiv">
							<div class="col-1"></div>
							<div class="col-2" style="text-align: center;"><img id="blah" src="<?php echo $image;?>" alt="" style="width: 150px;height: 150px;"/></div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="reset" class="btn">Reset</button>
					<button class="btn btn-primary" type="submit">Submit</button>
				</div>
			</div>
		</div>	
	</form>	
</div><!--#myModal-->
	<?php if('useredit' == (isset($_POST['sts']) ? $_POST['sts'] : '')) {?>
			<div class="alert alert-success" style="padding: 5px 0px 5px 10px;margin: 3px 5px 0px 5px;">
				<button data-dismiss="alert" style="margin-right: 10px;" class="close" type="button">&times;</button>
				<strong style="font-size: 13px;font-weight: normal;padding: 0 0 0 5px;">Success!!</strong> Profile Successfully Updated.
			</div><!--alert-->
	<?php } if('changepass' == (isset($_POST['sts']) ? $_POST['sts'] : '')) {?>
			<div class="alert alert-success" style="padding: 5px 0px 5px 10px;margin: 3px 5px 0px 5px;">
				<button data-dismiss="alert" style="margin-right: 10px;" class="close" type="button">&times;</button>
				<strong style="font-size: 13px;font-weight: normal;padding: 0 0 0 5px;">Success!!</strong> Password Successfully Updated.
			</div><!--alert-->
	<?php } if('picchange' == (isset($_POST['sts']) ? $_POST['sts'] : '') && 'No' == (isset($_POST['response']) ? $_POST['response'] : '')) {?>
			<div class="alert alert-success" style="padding: 5px 0px 5px 10px;margin: 3px 5px 0px 5px;">
				<button data-dismiss="alert" style="margin-right: 10px;" class="close" type="button">&times;</button>
				<strong style="font-size: 13px;font-weight: normal;padding: 0 0 0 5px;">Success!!</strong> New Picture Successfully Added.
			</div><!--alert-->
	<?php } if('picchange' == (isset($_POST['sts']) ? $_POST['sts'] : '') && 'Yes' == (isset($_POST['response']) ? $_POST['response'] : '')) {?>
			<div class="alert alert-error" style="padding: 5px 0px 5px 10px;margin: 3px 5px 0px 5px;">
				<button data-dismiss="alert" style="margin-right: 10px;" class="close" type="button">&times;</button>
				<strong style="font-size: 13px;font-weight: normal;padding: 0 0 0 5px;">Error!!</strong> <?php echo $message;?>
			</div><!--alert-->
	<?php } if('photodelete' == (isset($_POST['sts']) ? $_POST['sts'] : '')) {?>
			<div class="alert alert-success" style="padding: 5px 0px 5px 10px;margin: 3px 5px 0px 5px;">
				<button data-dismiss="alert" style="margin-right: 10px;" class="close" type="button">&times;</button>
				<strong style="font-size: 13px;font-weight: normal;padding: 0 0 0 5px;">Success!!</strong> Picture Successfully Deleted.
			</div><!--alert-->
	<?php } ?>

            <div class="maincontentinner">
	<div class="box box-primary">
		<div class="box-header">
				 <div class="modal-body">
                    	<div class="row profile-left">
						<?php if($user_type == 'client' || $user_type == 'breseller'){?>
                                        <table class="table table-bordered table-invoice">
											<tr>
												<td style="text-align: right;font-weight: bold;">Client ID</td>
												<td><?php echo $e_id;?></td>
											</tr>
											<tr>
												<td style="text-align: right;font-weight: bold;">Package</td>
												<td><?php echo $p_name;?>-<?php echo $bandwith;?> (<?php echo $p_price;?>TK)</td>
											</tr>
											<tr>
												<td style="text-align: right;font-weight: bold;">User Type</td>
												<td><?php echo $con_type;?> (<?php echo $connectivity_type;?>)</td>
											</tr>
											<tr>
												<td class="width30" style="text-align: right;font-weight: bold;">Zone & area</td>
												<td class="width70" style="font-family: gorgia;"><?php echo $z_name.' ('.$b_name.')'; ?></td>
											</tr>
											<tr>
												<td style="text-align: right;font-weight: bold;">Joining Date</td>
												<td><?php echo $join_date;?></td>
											</tr>
											<tr>
												<td style="text-align: right;font-weight: bold;">Line Status</td>
												<?php if($con_sts = 'Active'){?>
												<td style="color: green;font-weight: bold;">Active</td>
												<?php } else{ ?>
												<td style="color: red;font-weight: bold;">Inactive</td>
												<?php } ?>
											</tr>
										</table>
						<?php } ?>
                        </div><!--span4-->
                        <div class="row profile-left">
							<form class="stdform" method="post" action="UserEditAccountQuery">
							<input type="hidden" name="idssss" value="<?php echo $idssss;?>" />
							<input type="hidden" name="updateway" value="not_client" />
                                        <table class="table table-bordered table-invoice">
											<tr>
												<td class="width30" style="text-align: right;font-weight: bold;"><div style="margin-top: 2px;">Name*</div></td>
												<td class="width70"><div class="col-2" style="width: 100%;"><input type="text" name="user_name" <?php if($edit_sts == '1'){ ?> readonly <?php } ?> style="width: 100%;" value="<?php echo $user_name;?>" required=""/></div></td>
											</tr>
											<?php if($user_type == 'client' || $user_type == 'breseller'){?>
											<input type="hidden" name="c_id" value="<?php echo $e_id;?>" />
											<input type="hidden" name="edit_by" value="<?php echo $e_id;?>" />
											<input type="hidden" name="edit_date" value="<?php echo date('Y-m-d', time());?>" />
											<input type="hidden" name="edit_time" value="<?php echo date('H:i:s', time());?>" />
											<tr>
												<td style="text-align: right;font-weight: bold;"><div style="margin-top: 2px;">Father's Name*</div></td>
												<td><div class="col-2" style="width: 100%;"><input type="text" name="father_name" style="width: 100%;" <?php if($edit_sts == '1'){?> readonly <?php } ?> value="<?php echo $father_name;?>" required="" /></div></td>
											</tr>
											<tr>
												<td style="text-align: right;font-weight: bold;"><div style="margin-top: 2px;">Cell No*</div></td>
												<td><div class="col-2" style="width: 100%;"><input type="text" name="cell" style="width: 100%;" <?php if($edit_sts == '1'){?> readonly <?php } ?> value="<?php echo $cell;?>" required="" /></div></td>
											</tr>
											<tr>
												<td style="text-align: right;font-weight: bold;"><div style="margin-top: 2px;">Cell No 2</div></td>
												<td><div class="col-2" style="width: 100%;"><input type="text" name="cell1" style="width: 100%;" <?php if($edit_sts == '1'){?> readonly <?php } ?> value="<?php echo $cell1;?>" /></div></td>
											</tr>
											<tr>
												<td style="text-align: right;font-weight: bold;"><div style="margin-top: 2px;">E-mail</div></td>
												<td><div class="col-2" style="width: 100%;"><input type="text" name="email" style="width: 100%;" <?php if($edit_sts == '1'){?> readonly <?php } ?> value="<?php echo $email;?>" /></div></td>
											</tr>
											<tr>
												<td style="text-align: right;font-weight: bold;"><div style="margin-top: 2px;">Occupation</div></td>
												<td><div class="col-2" style="width: 100%;"><input type="text" name="occupation" style="width: 100%;" value="<?php echo $occupation;?>" /></div></td>
											</tr>
											<tr>
												<td style="text-align: right;font-weight: bold;"><div style="margin-top: 2px;">National ID No*</div></td>
												<td><div class="col-2" style="width: 100%;"><input type="text" name="nid" style="width: 100%;" <?php if($edit_sts == '1'){?> readonly <?php } ?> value="<?php echo $nid;?>" required="" /></div></td>
											</tr>
											<tr>
												<td style="text-align: right;font-weight: bold;"><div style="margin-top: 2px;">Previous ISP</div></td>
												<td><div class="col-2" style="width: 100%;"><input type="text" name="previous_isp" style="width: 100%;" value="<?php echo $previous_isp;?>"/></div></td>
											</tr>
											<tr>
												<td style="text-align: right;font-weight: bold;"><div style="margin-top: 2px;">Thana*</div></td>
												<td><div class="col-2" style="width: 100%;"><input type="text" name="thana" style="width: 100%;" <?php if($edit_sts == '1'){?> readonly <?php } ?> value="<?php echo $thana;?>" required="" /></div></td>
											</tr>
											<tr>
												<td style="text-align: right;font-weight: bold;"><div style="margin-top: 2px;">Present Address*</div></td>
												<td><div class="col-2" style="width: 100%;"><textarea type="text" name="address" style="width: 100%;" <?php if($edit_sts == '1'){?> readonly <?php } ?> required="" /><?php echo $address;?></textarea></div></td>
											</tr>
											<tr>
												<td style="text-align: right;font-weight: bold;"><div style="margin-top: 2px;">Permanent Address</div></td>
												<td><div class="col-2" style="width: 100%;"><textarea type="text" name="old_address" style="width: 100%;"/><?php echo $old_address;?></textarea></div></td>
											</tr>
											<?php } ?>
										</table>
								<ul class="taglist">
                                    <table class="table table-bordered table-invoice">
										<tr>
											<td style="text-align: right;font-weight: bold;">Photo</td>
											<td><a href='<?php echo $weblink.$image;?>' target='_blank' /><img src="<?php echo $weblink.$image;?>" height="60px" width="60px"  alt="" class="img-polaroid" /></a>
											<?php if($edit_sts == '0' or $user_type != 'client' or $user_type == 'breseller'){?>
												<div class="btn-group" style="float: right;">
													<li><input type="file" class="input-small" name="main_image" onchange="readURL(this);" /></li>
													<li><form action='' method='post'><input type='hidden' name='way' value='newsignup' /><button class='btn col5' style="padding: 3px 7px;"><i class='iconfa-trash'></i></button></form></li>
												</div>
											<?php } ?>
											</td>
										</tr>
										<tr>
											<td class="width30" style="text-align: right;font-weight: bold;">NID Front Photo</td>
											<td>
												<a href='<?php echo $weblink.$nid_fondd;?>' target='_blank'/><img src="<?php echo $weblink.$nid_fondd;?>" height="60px" width="100px"  alt="" class="img-polaroid" /></a>
											<?php if($edit_sts == '0' or $user_type != 'client' or $user_type == 'breseller'){?>
												<div class="btn-group" style="float: right;">
													<li><form action='' method='post'><input type='hidden' name='way' value='newsignup' /><input type='hidden' name='signup_id'/><button class='btn col1' style="padding: 3px 6px;"><i class='iconfa-edit'></i></button></form></li>
												</div>
											<?php } ?>
											</td>
										</tr>
										<tr>
											<td style="text-align: right;font-weight: bold;">NID Back Photo</td>
											<td>
												<a href='<?php echo $weblink.$nid_backk;?>' target='_blank'/><img src="<?php echo $weblink.$nid_backk;?>" height="60px" width="100px"  alt="" class="img-polaroid" /></a>
											<?php if($edit_sts == '0' or $user_type != 'client' or $user_type == 'breseller'){?>
												<div class="btn-group" style="float: right;">
													<li><form action='' method='post'><input type='hidden' name='way' value='newsignup' /><input type='hidden' name='signup_id'/><button class='btn col1' style="padding: 3px 6px;"><i class='iconfa-edit'></i></button></form></li>
												</div>
											<?php } ?>
											</td>
										</tr>
										<tr>
											<td style="text-align: right;font-weight: bold;"><div style="margin-top: 2px;">Terms & Conditions</div></td>
											<td><input type="checkbox" name="terms" value="1" <?php if($terms=='1'){echo 'checked';}?> required="" /> <a href="">Agree With Our Terms and Conditions</a></td>
										</tr>
									</table>
                                </ul>
                               <div class="modal-footer">
									<button class="btn btn-primary" type="submit">Submit</button>
								</div>
                            </form>
                        </div><!--span8-->
                    </div><!--row-fluid-->
		</div>
	</div>
   </div><!--row-fluid-->


<?php

include('include/footer.php');
?>
<script language="JavaScript" type="text/javascript">
function checkDelete(){
    return confirm('Remove Photo!!  Are you sure?');
}

     function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(150)
                        .height(150);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
		
     function readURL1(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah1')
                        .attr('src', e.target.result)
                        .width(180)
                        .height(100);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
		
     function readURL2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah2')
                        .attr('src', e.target.result)
                        .width(180)
                        .height(100);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
</script>