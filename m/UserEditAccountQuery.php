<?php
include("../web/conn/connection.php");
extract($_POST);

if($way == 'passchange'){
if ($_POST['pass1']!= $_POST['pass2'])
 {
     echo("Oops! Password did not match! Try again. ");
 }
 else{
	$cpass = sha1($pass2);
	if($user_type == 'client' || $user_type == 'breseller'){
		$query ="update login set password = '$cpass', pw = '$pass2' WHERE id = '$idssss'";
	}
	else{
		$query ="update login set password = '$cpass' WHERE id = '$idssss'";
	}
	$result = mysql_query($query) or die("inser_query failed: " . mysql_error() . "<br />");
	if ($result){ ?>
	
<html>
	<body>
		<form action="UserEditAccount" method="post" name="ok">
			<input type="hidden" name="sts" value="changepass">
		</form>
		<script language="javascript" type="text/javascript">
			document.ok.submit();
		</script>
	</body>
</html>
			<?php }
		else{
				echo 'Something Wrong... Try again...';
			}
 }
}

if($way == 'picchange'){
	
//................IMAGE.......................//

//$fileinfo = @getimagesize($_FILES["file-input"]["tmp_name"]);
//    $width = $fileinfo[0];
//    $height = $fileinfo[1];
	
    $allowed_image_extension = array(
        "png",
        "jpg",
        "jpeg"
    );
    
    // Get image file extension
    $file_extension = pathinfo($_FILES["file-input"]["name"], PATHINFO_EXTENSION);
    
    // Validate file input to check if is not empty
    if (! file_exists($_FILES["file-input"]["tmp_name"])) {
        $response = array(
            "type" => "error",
            "message" => "Choose image file to upload."
        );
		$errorrr = 'Yes';
		$message = "Choose image file to upload.";
    }    // Validate file input to check if is with valid extension
    else if (! in_array($file_extension, $allowed_image_extension)) {
        $response = array(
            "type" => "error",
            "message" => "Upload valid images. Only PNG, JPG and JPEG are allowed."
        );
		$errorrr = 'Yes';
		$message = "Upload valid images. Only PNG, JPG and JPEG are allowed.";
    }    // Validate image file size
    else if (($_FILES["file-input"]["size"] > 500000)) {
        $response = array(
            "type" => "error",
            "message" => "Image size exceeds 500KB"
        );
		$errorrr = 'Yes';
		$message = "Image size exceds 500KB";
	}
//    else if ($width > "500" || $height > "500") {
//        $response = array(
//            "type" => "error",
//            "message" => "Image dimension should be within 500X500"
//        );
//    } 
	else {
		if($imgway == 'main_img'){
			$uploadfile = "emp_images/".$idssss."_". basename($_FILES["file-input"]["name"]);
			if (move_uploaded_file($_FILES["file-input"]["tmp_name"], $uploadfile)) {
				$response = array(
					"type" => "success",
					"message" => "Image uploaded successfully."
				);
				
			$imglink = "emp_images/".$idssss."_". basename($_FILES["file-input"]["name"]);
			
			$errorrr = 'No';
			$message = "Image uploaded successfully.";
			} else {
				$response = array(
					"type" => "error",
					"message" => "Problem in uploading image files."
				);
			$imglink = "";
			$errorrr = 'Yes';
			$message = "Problem in uploading image files.";
			}
		}
		if($imgway == 'nid_fond'){
			$uploadfile = "emp_images/".$idssss."_f_nid_". basename($_FILES["file-input"]["name"]);
			if (move_uploaded_file($_FILES["file-input"]["tmp_name"], $uploadfile)) {
				$response = array(
					"type" => "success",
					"message" => "Image uploaded successfully."
				);
				
			$imglink1 = "emp_images/".$idssss."_f_nid_". basename($_FILES["file-input"]["name"]);
			$errorrr = 'No';
			$message = "Image uploaded successfully.";
			} else {
				$response = array(
					"type" => "error",
					"message" => "Problem in uploading image files."
				);
			$imglink1 = "";
			$errorrr = 'Yes';
			$message = "Problem in uploading image files.";
			}
		}
		if($imgway == 'nid_back'){
			$uploadfile = "emp_images/".$idssss."_f_nid_". basename($_FILES["file-input"]["name"]);
			if (move_uploaded_file($_FILES["file-input"]["tmp_name"], $uploadfile)) {
				$response = array(
					"type" => "success",
					"message" => "Image uploaded successfully."
				);
				
			$imglink2 = "emp_images/".$idssss."_f_nid_". basename($_FILES["file-input"]["name"]);
			$errorrr = 'No';
			$message = "Image uploaded successfully.";
			} else {
				$response = array(
					"type" => "error",
					"message" => "Problem in uploading image files."
				);
			$imglink2 = "";
			$errorrr = 'Yes';
			$message = "Problem in uploading image files.";
			}
		}
    }
//................IMAGE.......................//
if($imgway == 'main_img' && $errorrr == 'No'){
$query ="update login set image = '$imglink' WHERE id = '$idssss'";
$result = mysql_query($query) or die("inser_query failed: " . mysql_error() . "<br />");
}
if($imgway == 'nid_fond' && $errorrr == 'No'){
$query ="update login set nid_fond = '$imglink1' WHERE id = '$idssss'";
$result = mysql_query($query) or die("inser_query failed: " . mysql_error() . "<br />");
}
if($imgway == 'nid_back' && $errorrr == 'No'){
$query ="update login set nid_back = '$imglink2' WHERE id = '$idssss'";
$result = mysql_query($query) or die("inser_query failed: " . mysql_error() . "<br />");
}
 if($backway == ''){
if($result){ ?>
	
<html>
	<body>
		<form action="UserEditAccount" method="post" name="ok">
			<input type="hidden" name="sts" value="picchange">
			<input type="hidden" name="response" value="<?php echo $errorrr;?>">
			<input type="hidden" name="message" value="<?php echo $message;?>">
		</form>
		<script language="javascript" type="text/javascript">
			document.ok.submit();
		</script>
	</body>
</html>

<?php }
else{ ?>
<html>
	<body>
		<form action="UserEditAccount" method="post" name="ok">
			<input type="hidden" name="sts" value="picchange">
			<input type="hidden" name="response" value="<?php echo $errorrr;?>">
			<input type="hidden" name="message" value="<?php echo $message;?>">
		</form>
		<script language="javascript" type="text/javascript">
			document.ok.submit();
		</script>
	</body>
</html>
<?php	}
 }
 else{ if($result){ ?>
	
<html>
	<body>
		<form action="ClientEdit" method="post" name="ok">
			<input type="hidden" name="sts" value="picchange">
			<input type="hidden" name="response" value="<?php echo $errorrr;?>">
			<input type="hidden" name="message" value="<?php echo $message;?>">
			<input type="hidden" name="c_id" value="<?php echo $c_id;?>">
		</form>
		<script language="javascript" type="text/javascript">
			document.ok.submit();
		</script>
	</body>
</html>

<?php }
else{ ?>
<html>
	<body>
		<form action="ClientEdit" method="post" name="ok">
			<input type="hidden" name="sts" value="picchange">
			<input type="hidden" name="response" value="<?php echo $errorrr;?>">
			<input type="hidden" name="message" value="<?php echo $message;?>">
			<input type="hidden" name="c_id" value="<?php echo $c_id;?>">
		</form>
		<script language="javascript" type="text/javascript">
			document.ok.submit();
		</script>
	</body>
</html>
<?php	}}}

if($way == 'photodelete'){
	
$query ="update login set image = '' WHERE id = '$idssss'";
$result = mysql_query($query) or die("inser_query failed: " . mysql_error() . "<br />");

if($result){ ?>
	
<html>
	<body>
		<form action="UserEditAccount" method="post" name="ok">
			<input type="hidden" name="sts" value="photodelete">
		</form>
		<script language="javascript" type="text/javascript">
			document.ok.submit();
		</script>
	</body>
</html>

<?php }
else{
	echo 'Something Wrong... Try again...';
	}
}

if($updateway == 'client'){
$cellc = (int) filter_var($cell, FILTER_SANITIZE_NUMBER_INT);
$cellc1 = (int) filter_var($cell1, FILTER_SANITIZE_NUMBER_INT);
$query="UPDATE clients SET
				father_name = '$father_name',
				old_address = '$old_address',
				c_name = '$c_name',
				occupation = '$occupation',
				cell = '$cellc',
				cell1 = '$cellc1',
				email = '$email',
				thana = '$thana',
				previous_isp = '$previous_isp',
				nid = '$nid',
				address = '$address',
				edit_by = '$edit_by',
				edit_date = '$edit_date',
				edit_time = '$edit_time',
				terms = '$terms'
				WHERE c_id = '$c_id'";

		$result = mysql_query($query) or die("inser_query failed: " . mysql_error() . "<br />");
?>
<html>
	<body>
		<form action="UserEditAccount" method="post" name="ok">
			<input type="hidden" name="sts" value="useredit">
		</form>
		<script language="javascript" type="text/javascript">
			document.ok.submit();
		</script>
	</body>
</html>
<?php }
if($updateway == 'not_client'){
$query22="UPDATE login SET user_name = '$user_name', email = '$email'	WHERE id = '$idssss'";
		$result22 = mysql_query($query22) or die("inser_query failed: " . mysql_error() . "<br />");
?>
<html>
	<body>
		<form action="UserEditAccount" method="post" name="ok">
			<input type="hidden" name="sts" value="useredit">
		</form>
		<script language="javascript" type="text/javascript">
			document.ok.submit();
		</script>
	</body>
</html>
<?php
}
mysql_close($con);
?>