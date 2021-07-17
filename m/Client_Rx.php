<?php
include("../web/conn/connection.php");
include("mk_api.php");
$mk_id = $_GET['mk_id'];
$c_id = $_GET['c_id'];

$sqlcli = mysql_query("SELECT breseller, ip FROM clients WHERE c_id = '$c_id'");
$rowcal = mysql_fetch_assoc($sqlcli);
$breseller = $rowcal['breseller'];
$ip = $rowcal['ip'];

$sqlmk = mysql_query("SELECT id, ServerIP, Username, Pass, Port, e_Md, secret_h FROM mk_con WHERE sts = '0' AND id = '$mk_id'");
		$rowmk = mysql_fetch_assoc($sqlmk);
		
		$ServerIP = $rowmk['ServerIP'];
		$Username = $rowmk['Username'];
		$Pass= openssl_decrypt($rowmk['Pass'], $rowmk['e_Md'], $rowmk['secret_h']);
		$Port = $rowmk['Port'];
$API = new routeros_api();
$API->debug = false;

if ($API->connect($ServerIP, $Username, $Pass, $Port)) {
if($breseller == '0'){
		$API->write('/interface/print',false);
		$API->write('=from=<pppoe-'.$c_id.'>',false);
		$API->write('=stats-detail');
		$ressi=$API->read(true);
		
		$int_rx = $ressi[0]['rx-byte'];		
		$upload_speedd = $int_rx/1000000;
		$upload_speed = number_format($upload_speedd,3);
		
		    
$API->write('/interface/monitor-traffic',false);
$API->write('=interface=<pppoe-'.$c_id.'>',false);
$API->write('=once');
$ARRAY = $API->read();
		
$rx_bits = $ARRAY['0']['rx-bits-per-second'] / 1000;
   $API->disconnect();
   
if($rx_bits >= 1024){
	$rx_bitss1 = $rx_bits / 1000;
	$rx_bitss = number_format($rx_bitss1,2).' mbps';
}
else{
	$rx_bitss = number_format($rx_bits,2).' kbps';
}

	?>

			<a style="color: #444;"><?php echo $upload_speed; ?> mb</a> | <a style="color: green;"><?php echo $rx_bitss;?></a>
<?php }
else{
	$API->write('/queue/simple/print', false);
	$API->write('?target=172.16.150.1/32');
	$ressi=$API->read(true);
	$API->disconnect();
		
		$queueUp = explode('/',$ressi[0]['rate'])[0];
		$mk_upload = $queueUp / 1000;
if($mk_upload >= 1024){
	$mk_upload1 = $mk_upload / 1000;
	$q_upload = number_format($mk_upload1,2).' mbps';
}
else{
	$q_upload = number_format($mk_upload,2).' kbps';
}

?>
<a style="color: green;"><?php echo $q_upload;?></a>
<?php
}
}else{echo 'Selected Network are not Connected.';}
  
?>