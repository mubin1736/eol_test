<?php
include("../web/conn/connection.php") ;
include("mk_api.php");
$mk_id = $_GET['mk_id'];
$sqlmk = mysql_query("SELECT id, ServerIP, Username, Pass, Port, e_Md, secret_h FROM mk_con WHERE sts = '0' AND id = '$mk_id'");
		$rowmk = mysql_fetch_assoc($sqlmk);
		
		$ServerIP = $rowmk['ServerIP'];
		$Username = $rowmk['Username'];
		$Pass= openssl_decrypt($rowmk['Pass'], $rowmk['e_Md'], $rowmk['secret_h']);
		$Port = $rowmk['Port'];
$API = new routeros_api();
$API->debug = false;



  if($_POST) 
  {
	   $p_id     = strip_tags($_POST['p_id']);
	   
$result1zz=mysql_query("SELECT p_id, mk_profile FROM package WHERE status = '0' AND z_id = '' AND p_id = '$p_id'");
$rows1 = mysql_fetch_assoc($result1zz);
$mk_profile = $rows1['mk_profile'];

    if ($API->connect($ServerIP, $Username, $Pass, $Port)) {

		$API->write('/ppp/profile/print', false);
		$API->write('?name='.$mk_profile);
		$res=$API->read(true);

		$remoteaddress = $res[0]['local-address']; 
		echo $remoteaddress;

	}else{echo 'Selected Network are not Connected.';}
  }
?>