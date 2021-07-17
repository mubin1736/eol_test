<?php
$titel = "Servers";
$Servers = 'active';
include('include/hader.php');
extract($_POST);

//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'Servers' AND $user_type = '1'");
if(mysql_num_rows($access) > 0){
//---------- Permission -----------
$sqlsdf = mysql_query("SELECT id, text, category, show_sts FROM servers WHERE sts= '0' AND id = '$s_id'");
$rowsm = mysql_fetch_assoc($sqlsdf);
$ser_id = $rowsm['id'];
$text = $rowsm['text'];
$category = $rowsm['category'];
$show_sts = $rowsm['show_sts'];
?>
	<div class="box box-primary">
		<div class="box-header">
			<div class="modal-content">
				<div class="modal-header">
					<h5>FTP & TV Servers Edit</h5>
				</div>
					<div class="modal-body">
				<form id="" name="form1" class="stdform" method="post" action="ServersAddQuery">
					<input type="hidden" value="<?php echo $ser_id; ?>" name="ser_id" />
					<input type="hidden" value="edit" name="wayy" />
					<input type="hidden" value="<?php echo date('Y-m-d H:i:s'); ?>" name="enty_date" />
						<p>	
							<select data-placeholder="Select a Category" name="category" required="" class="chzn-select" required="" style="width:280px;">
								<option value="">Select Category</option>
								<option value="0" <?php if ('0' == $category) echo 'selected="selected"';?>>FTP Server</option>
								<option value="1" <?php if ('1' == $category) echo 'selected="selected"';?>>TV Server</option>
							</select>
						</p>
						<p>	
							<select data-placeholder="" name="show_sts" required="" class="chzn-select" required="" style="width:280px;">
								<option value="0" <?php if ('0' == $show_sts) echo 'selected="selected"';?>>Yes</option>
								<option value="1" <?php if ('1' == $show_sts) echo 'selected="selected"';?>>No</option>
							</select>
						</p>
						 <textarea id="elm1" name="text" rows="15" cols="80" style="width: 80%" class="tinymce">
						 <?php echo $text;?>
						</textarea>
					</div>
					<div class="modal-footer">
						<button type="reset" class="btn">Reset</button>
						<button class="btn btn-primary" type="submit">Submit</button>
					</div>
				</form>			
			</div>
		</div>
	</div>
	<?php
}
else{
	include('include/index');
}
include('include/footer.php');
?>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-fileupload.min.css" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-timepicker.min.css" type="text/css" />

<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/tinymce/jquery.tinymce.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/wysiwyg.js"></script>
