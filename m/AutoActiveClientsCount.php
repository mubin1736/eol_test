<?php
		include("../web/conn/connection.php");
		include("mk_api.php");	
		$mkid = $_GET['ids'];
		$sqlmk = mysql_query("SELECT id, ServerIP, Username, Pass, Port, e_Md, secret_h FROM mk_con WHERE sts = '0' AND id = '$mkid'");
		$rowmk = mysql_fetch_assoc($sqlmk);
		
		$ServerIP = $rowmk['ServerIP'];
		$Username = $rowmk['Username'];
		$Pass= openssl_decrypt($rowmk['Pass'], $rowmk['e_Md'], $rowmk['secret_h']);
		$Port = $rowmk['Port'];
		$API = new routeros_api();
		$API->debug = false;
		if ($API->connect($ServerIP, $Username, $Pass, $Port)) {
			$arrID1 = $API->comm('/ppp/active/getall');
			$ssss = count($arrID1);
			 $API->disconnect();

			}
			
?>
Total Active Connictions: <i style='color: #317EAC'><?php echo "$ssss"; ?></i>