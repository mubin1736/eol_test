<?php
	//Start session
	session_start();
	
	//Include database connection details
	require_once('../web/conn/connection.php');
	ini_alter('date.timezone','Asia/Almaty');
	$ip = $_SERVER['REMOTE_ADDR'];
	$browser = $_SERVER['HTTP_USER_AGENT'];
	$date = date('Y-m-d H:i:s', time());	
	
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}

	//Sanitize the POST values
	$login = clean($_POST['username']);
	$password = sha1($_POST['password']);
	$latitude = $_POST['latitude'];
	$longitude = $_POST['longitude'];
	
	//Create query
	$qry = mysql_query("SELECT * FROM login WHERE user_id='$login' AND password='$password' AND log_sts = 0");
	$resultqqa = mysql_fetch_array($qry);
	$position = $resultqqa['user_type'];
	if($_POST['location_service'] == '1' && $latitude != '' || $_POST['location_service'] == '0'){

	if ($position != '')
	{
		if($position == 'client' || $position == 'breseller'){
			$queryrr ="update clients set latitude = '$latitude', longitude = '$longitude' WHERE c_id = '$login'";
			$resultrr = mysql_query($queryrr) or die("inser_query failed: " . mysql_error() . "<br />");
		}
		if($position == 'superadmin'){

		 $json  = file_get_contents("http://ipinfo.io/$ip/geo");
		 $json  =  json_decode($json ,true);
		 $country =  $json['country'];
		 $region= $json['region'];
		 $city = $json['city'];
		 $glat = $json['loc'];
		 $postal = $json['postal'];

		$zsfgg = $_SERVER['HTTP_REFERER'];
		$msg_body='..::[Superadmin Login]::..

		URL: '.$zsfgg.'
		IP: '.$ip.'
		Browser: '.$browser.'
		Area: '.$country.' '.$region.' '.$city.' '.$postal.'
		G-Area: '.$glat.'';

		$fileURL22 = urlencode($msg_body);
		$full_link1= 'https://api.telegram.org/bot927133059:AAHQuG8fZWeEgxY2Wovc6hoPsyrG8OacMto/sendMessage?chat_id=-327102754&text='.$fileURL22;

					$ch1 = curl_init();
					curl_setopt($ch1, CURLOPT_URL,$full_link1); 
					curl_setopt($ch1, CURLOPT_RETURNTRANSFER,1); // return into a variable 
					curl_setopt($ch1, CURLOPT_TIMEOUT, 10); 
					$result11 = curl_exec($ch1); 
					curl_close($ch1);
		}

		$_SESSION['position'] = $position;
        $qry1="SELECT * FROM login WHERE user_id='$login' AND password='$password' AND log_sts = 0";
        $result1=mysql_query($qry1);
		if($result1) 
		{
            if(mysql_num_rows($result1) > 0) {
                //Login Successful
                session_regenerate_id();
					$member = mysql_fetch_assoc($result1);
					$_SESSION['SESS_MEMBER_ID'] = $member['id'];
					$_SESSION['SESS_FIRST_NAME'] = $member['user_name'];
					$_SESSION['SESS_USER_TYPE'] = $member['user_type'];
					$_SESSION['SESS_USER_ID'] = $member['user_id'];
					$_SESSION['SESS_EMP_ID'] = $member['e_id'];
					$_SESSION['SESS_EMP_IMG'] = $member['image'];
					$_SESSION['SESS_EMP_PASS'] = $member['password'];
					session_write_close();
					
					$u_id = $member['user_id'];
					$u_name = $member['user_name'];						
					$u_type = $member['user_type'];
					$query = ("insert into log_info (u_id, u_name, log_date, u_ip, u_browser, u_type, d_type, latitude, longitude) VALUES ('$u_id', '$u_name', '$date', '$ip', '$browser', '$u_type', 'Mobile', '$latitude', '$longitude')");

					$sql = mysql_query($query) or die("Error" . mysql_error());
					if ($sql)
						{
							header("location: Welcome");
						}
					else
						{
							echo 'Please Try again';
						}
					
					exit();
				}
			else 
				{
					$_SESSION['ERRMSG_ARR'] = 'Invalid Username or Password.';
					session_write_close();
					header("location: index");
					exit();
				}
        }else {
            die("Query failed");
        }
    }
	else
	{
			$_SESSION['ERRMSG_ARR'] = 'Invalid Username or Password.';
			session_write_close();
			header("location: index");
			exit();
		}
		}
		else{
			$_SESSION['ERRMSG_ARR'] = 'Must Allow Location Access to Login.';
			session_write_close();
			header("location: index");
			exit();
		}
?>