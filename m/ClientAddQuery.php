<?php
include("../web/conn/connection.php") ;
include("mk_api.php");
extract($_POST);

$new_id = str_replace(' ', '', $c_id);

$sqlc = mysql_query("SELECT id, c_id FROM clients WHERE c_id = '$new_id'");
$rowc = mysql_fetch_assoc($sqlc);

$sqlc1 = mysql_query("SELECT b_name FROM box WHERE box_id = '$box_id'");
$rowc1 = mysql_fetch_assoc($sqlc1);

$bname = $rowc1['b_name'];

if($rowc['c_id'] == ''){

$cellc = (int) filter_var($cell, FILTER_SANITIZE_NUMBER_INT);
$cellc1 = (int) filter_var($cell1, FILTER_SANITIZE_NUMBER_INT);
$cellc2 = (int) filter_var($cell2, FILTER_SANITIZE_NUMBER_INT);
$cellc3 = (int) filter_var($cell3, FILTER_SANITIZE_NUMBER_INT);
$cellc4 = (int) filter_var($cell4, FILTER_SANITIZE_NUMBER_INT);

$sqlq = mysql_query("SELECT id, Name, ServerIP, Username, Pass, Port, e_Md, secret_h, add_date_time, note FROM mk_con WHERE id = '$mk_id'");
$row2 = mysql_fetch_assoc($sqlq);

$Pass= openssl_decrypt($row2['Pass'], $row2['e_Md'], $row2['secret_h']);
$API = new routeros_api();
$API->debug = false;
if ($API->connect($row2['ServerIP'], $row2['Username'], $Pass, $row2['Port'])) {
	
	$pass = sha1($passid);
//	$new_id = $c_id;
		
//................IMAGE.......................//

$fileinfo = @getimagesize($_FILES["file-input"]["tmp_name"]);
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
    }    // Validate file input to check if is with valid extension
    else if (! in_array($file_extension, $allowed_image_extension)) {
        $response = array(
            "type" => "error",
            "message" => "Upload valiid images. Only PNG and JPEG are allowed."
        );
        echo $result;
    }    // Validate image file size
    else if (($_FILES["file-input"]["size"] > 1000000)) {
        $response = array(
            "type" => "error",
            "message" => "Image size exceeds 1MB"
        );
	}
//    else if ($width > "500" || $height > "500") {
//        $response = array(
//            "type" => "error",
//            "message" => "Image dimension should be within 500X500"
//        );
//    } 
	else {
        $uploadfile = "../web/emp_images/".$new_id."_". basename($_FILES["file-input"]["name"]);
        if (move_uploaded_file($_FILES["file-input"]["tmp_name"], $uploadfile)) {
            $response = array(
                "type" => "success",
                "message" => "Image uploaded successfully."
            );
		
		$imglink = "emp_images/".$new_id."_". basename($_FILES["file-input"]["name"]);
		
        } else {
            $response = array(
                "type" => "error",
                "message" => "Problem in uploading image files."
            );
		$imglink = "";
        }
    }
//................IMAGE.......................//
if($breseller == '1'){
		$nombre =  $new_id;
        $target = $ip;
		$maxlimit=    $raw_upload.''.'M/'.''.$raw_download.''.'M';
//		$local => '';
//		$remote => '';
        $API->comm("/queue/simple/add", array(
           "name"     => $nombre,
          "target" => $target,
          "max-limit"  => $maxlimit,
//		  "local-address" => $local,
//		  "remote-address" => $remote,
        ));
		
$API->disconnect();
	
			if($new_id != ''){
			
			$query = "insert into clients (c_id, com_id, c_name, z_id, mk_id, box_id, bill_man, technician, payment_deadline, termination_date, breseller, b_date, cell, cell1, cell2, cell3, cell4, email, address, thana, join_date, occupation, con_type, connectivity_type, ip, mac, req_cable, cable_type, con_sts, nid, p_m, signup_fee, note, entry_by, entry_date, entry_time, calculation, raw_download, raw_upload, youtube_bandwidth, total_bandwidth, bandwidth_price, youtube_price, total_price, father_name, old_address)
					  VALUES ('$new_id', '$com_id', '$c_name', '$z_id', '$mk_id', '$box_id', '$bill_man', '$technician', '$payment_deadline', '$termination_date', '$breseller', '$b_date', '$cellc', '$cellc1', '$cellc2', '$cellc3', '$cellc4', '$email', '$address', '$thana', '$join_date', '$occupation', '$con_type', '$connectivity_type', '$ip', '$mac', '$req_cable', '$cable_type', '$con_sts', '$nid', '$p_m', '$signup_fee', '$note','$entry_by', '$entry_date', '$entry_time', '$calculation', '$raw_download', '$raw_upload', '$youtube_bandwidth', '$total_bandwidth', '$bandwidth_price', '$youtube_price', '$bill_amount', '$father_name', '$old_address')";
			$result = mysql_query($query) or die("inser_query failed: " . mysql_error() . "<br />");
			if ($result)
			{
				$query1 = "insert into login (user_name, e_id, user_id, password, user_type, email, image, pw) VALUES ('$c_name', '$new_id', '$new_id', '$pass', 'breseller', '$email', '$imglink', '$passid')";
				$result1 = mysql_query($query1) or die("inser_query failed: " . mysql_error() . "<br />");
			
			}
			
			$query20 = "UPDATE app_config SET last_id = '$com_id'";
			if (!mysql_query($query20)){
				die('Error: ' . mysql_error());
			}
			
			if($calculation == 'Manual')
			{
			$query2 = "insert into billing (c_id, bill_date, p_price, bill_amount) VALUES ('$new_id', '$entry_date', '$bill_amount', '$bill_amount')";
			$result2 = mysql_query($query2) or die("inser_query failed: " . mysql_error() . "<br />");
			}
			
			if($calculation == 'Auto')
			{
			$todayy = date('d', time());
			$lastdayofthismonth = date('t');
			$aa = ($lastdayofthismonth - $todayy)+1;
			$onedaynewprice = $bill_amount / $lastdayofthismonth;
			$unusedday = $aa * $onedaynewprice;
			$discountt = $bill_amount - $unusedday;
				
			$query2 = "insert into billing (c_id, bill_date, p_price, bill_amount, day) VALUES ('$new_id', '$entry_date', '$bill_amount', '$unusedday', '$aa')";
	//		$query2 = "insert into breseller_billing (c_id, bill_date, raw_download, raw_upload, youtube_bandwidth, total_bandwidth, bandwidth_price, youtube_price, bill_amount, day) VALUES ('$c_id', '$entry_date', '$raw_download', '$raw_upload', '$youtube_bandwidth', '$total_bandwidth', '$bandwidth_price', '$youtube_price', '$unusedday', '$aa')";
			$result2 = mysql_query($query2) or die("inser_query failed: " . mysql_error() . "<br />");
			}

		}
		else{
			echo 'Invilade Id';
		}		
	
}
else{
$sqlqq = mysql_query("SELECT mk_profile, p_price FROM package WHERE p_id = '$p_id'");
$row22 = mysql_fetch_assoc($sqlqq);

//--------Add PPPoE in Mikrotik-------

		$nombre =  $new_id;
        $password = $passid;
        $service = 'pppoe';
        $profile = $row22['mk_profile'];
		$pprice = $row22['p_price'];
//		$remote = $ip;


//--------Add PPPoE in Mikrotik-------
		
		
		if($new_id != ''){
			
			$query = "insert into clients (c_id, com_id, c_name, z_id, mk_id, box_id, bill_man, technician, payment_deadline, termination_date, b_date, cell, cell1, cell2, cell3, cell4, opening_balance, email, address, thana, previous_isp, join_date, occupation, con_type, connectivity_type, ip, mac, req_cable, cable_type, con_sts, nid, p_id, p_m, signup_fee, discount, note, entry_by, entry_date, entry_time, father_name, old_address)
					  VALUES ('$new_id', '$com_id', '$c_name', '$z_id', '$mk_id', '$box_id', '$bill_man', '$technician', '$payment_deadline', '$termination_date', '$b_date', '$cellc', '$cellc1', '$cellc2', '$cellc3', '$cellc4', '$opening_balance', '$email', '$address', '$thana', '$previous_isp', '$join_date', '$occupation', '$con_type', '$connectivity_type', '$ip', '$mac', '$req_cable', '$cable_type', '$con_sts', '$nid', '$p_id', '$p_m', '$signup_fee', '$discount', '$note','$entry_by', '$entry_date', '$entry_time', '$father_name', '$old_address')";
			$result = mysql_query($query) or die("inser_query failed: " . mysql_error() . "<br />");
			if ($result)
			{
				$query1 = "insert into login (user_name, e_id, user_id, password, user_type, email, image, pw) VALUES ('$c_name', '$new_id', '$new_id', '$pass', '$u_type', '$email', '$imglink', '$passid')";
				$result1 = mysql_query($query1) or die("inser_query failed: " . mysql_error() . "<br />");

			}
			$query20 = "UPDATE app_config SET last_id = '$com_id'";
			if (!mysql_query($query20)){
				die('Error: ' . mysql_error());
			}
			if($qcalculation == 'Manual')
			{
			if ($discount == ''){
				$dis_price = $pprice;
				$query2 = "insert into billing (c_id, bill_date, p_id, p_price, bill_amount) VALUES ('$nombre', '$entry_date', '$p_id', '$pprice', '$pprice')";
				if (!mysql_query($query2)){
					die('Error: ' . mysql_error());
				}
			}
			else{
				$dis_price = $pprice - $discount;
				$query2 = "insert into billing (c_id, bill_date, p_id, p_price, discount, bill_amount) VALUES ('$nombre', '$entry_date', '$p_id', '$pprice', '$discount', '$dis_price')";
				if (!mysql_query($query2)){
					die('Error: ' . mysql_error());
				}
			}
			}
			
			if($qcalculation == 'Auto')
			{
			$todayy = date('d', time());
			$lastdayofthismonth = date('t');
			$aa = $lastdayofthismonth - $todayy;

			$onedaynewprice = $pprice / $lastdayofthismonth;
			$unusedday = $aa * $onedaynewprice;
			$discountt = $pprice - $unusedday;
			
			if ($discount == ''){
				$query2 = "insert into billing (c_id, bill_date, p_id, p_price, discount, bill_amount) VALUES ('$nombre', '$entry_date', '$p_id', '$pprice', '$discountt', '$unusedday')";
				if (!mysql_query($query2)){
					die('Error: ' . mysql_error());
				}
			}
			else{
				$dis_price = $unusedday - $discount;
				$query2 = "insert into billing (c_id, bill_date, p_id, p_price, discount, bill_amount) VALUES ('$nombre', '$entry_date', '$p_id', '$pprice', '$discount', '$dis_price')";
				if (!mysql_query($query2)){
					die('Error: ' . mysql_error());
				}
			}
			
			
			}
			if($ppoe_comment == '0'){
				$comment = $c_name.'-'.$cellc.'-'.$address.'-'.$bname.'-'.$join_date.'-'.$pprice.'TK';
			}
			else{
				$comment = '';
			}
			
        $API->comm("/ppp/secret/add", array(
          "name"     => $nombre,
          "password" => $password,
          "profile"  => $profile,
          "service"  => $service,
		  "comment"  => $comment,
//		  "remote-address" => $remote,
        ));
$API->disconnect();
		}
		else{
			echo 'Invilade Id';
		}
}
?>

<html>
<body>
     <form action="Success" method="post" name="cus_id">
       <input type="hidden" name="new_id" value="<?php echo $new_id; ?>">
	   <input type="hidden" name="passid" value="<?php echo $passid; ?>">
	   <input type="hidden" name="sentsms" value="<?php echo $sentsms; ?>">
	   <input type="hidden" name="mk_name" value="<?php echo $row2['Name']; ?>">
	   <input type="hidden" name="from_page" value="Add Client">
     </form>

     <script language="javascript" type="text/javascript">
		document.cus_id.submit();
     </script>
     <noscript><input type="submit" value="<? echo $new_id; ?>"></noscript>
</body>
</html>

<?php
}
else{
	echo 'Selected Network are not Connected';
}
}
else{
	echo 'Client ID Already Existed';
}
?>