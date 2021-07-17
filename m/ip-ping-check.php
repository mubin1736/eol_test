<?php
include("../web/conn/connection.php");
include("mk_api.php");
$ip = $_GET['ip'];
$mk_id = $_GET['mk_id'];
//echo $ip;
$sqlmk = mysql_query("SELECT id, ServerIP, Username, Pass, Port, e_Md, secret_h FROM mk_con WHERE sts = '0' AND id = '$mk_id'");
		$rowmk = mysql_fetch_assoc($sqlmk);
		
		$ServerIP = $rowmk['ServerIP'];
		$Username = $rowmk['Username'];
		$Pass= openssl_decrypt($rowmk['Pass'], $rowmk['e_Md'], $rowmk['secret_h']);
		$Port = $rowmk['Port'];
$API = new routeros_api();
$API->debug = false;

	   $ip     = strip_tags($ip);
//	   $ip     = strip_tags('172.10.10.23');
    if ($API->connect($ServerIP, $Username, $Pass, $Port)) {

$arrID = $API->comm("/ping", array(
            "address" => $ip,
            "arp-ping" => "no",
            "count" => "5",
            "interval" => "300ms"
        ));

		?>
		<table id='' class='' style="width: 100%; float: left;font-size: 10px;">
				<thead style="line-height: 0px;">
					<tr>
						<th>IP Address</th>
						<th class='center'>Time</th>
						<th class='center'>Size</th>
						<th class='center'>TTL</th>
						<th class='center'>Status</th>
					</tr>
                </thead>
        <tbody>
<?php
		$x = 1;	
		foreach($arrID as $x => $x_value) {
			$received = $x_value['received'];
			$host = $x_value['host'];
//			$avg = $x_value['avg-rtt'];
			$size = $x_value['size'];
			$ttl = $x_value['ttl'];
			$time = $x_value['time'];
//			$loss = $x_value['packet-loss'];
			$status = $x_value['status'];
			if($status == 'timeout'){
				$statuss = 'request timed out';
				$count = '1';
				$okcount = '0';
			}
			else{
				$statuss = 'OK';
				$count = '0';
				$okcount = '1';
			}
			$x++;
			$ss += $count;
			$okcountt += $okcount;
			echo 
				"<tr style='line-height: 0px;border-bottom: 1px solid #ccc;'>
					<td><b>" . $host . "</b></td>
					<td class='center'><b>" . $time . "</b></td>
					<td class='center'><b>" . $size ."</b></td>
					<td class='center'><b>" . $ttl ."</b></td>
					<td class='center'><b>" . $statuss . "</b></td>
				</tr>";
				
		}
		$dfheh = $ss*100/$x;
		$fghfghf = number_format($dfheh,2);
				?>
			<tr style='line-height: 0px;border-bottom: 1px solid #ccc;'>
				<td colspan="6">
				<?php echo 'Ping: '.$x.', ';?>
				<?php echo 'Ok: '.$okcountt.', ';?>
				<?php echo 'Timeout: '.$ss.', ';?>
				<?php echo 'Loss: '.number_format($dfheh,2).'%';?>
				</td>
			</tr>
			</tbody>
		</table>
	<?php
	}else{echo 'Selected Network are not Connected.';}

?>