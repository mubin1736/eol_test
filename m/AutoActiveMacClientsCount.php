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
			$arrID = $API->comm('/ppp/active/getall');
			$dd=0;
								foreach($arrID as $x => $x_value) {
									$aaaaa = $x_value['name'];
									$sql44 = mysql_query("SELECT c.c_name FROM clients AS c
												LEFT JOIN zone AS z ON z.z_id = c.z_id 
												WHERE c.c_id = '$aaaaa' AND c.mk_id = '$mkid'");
									$rows1 = mysql_fetch_assoc($sql44);
									if($rows1['c_name'] == ''){	} else{$dd++;}
								}
			 $API->disconnect();

			}
			
?>
Total Active Connictions: <i style='color: #317EAC'><?php echo "$dd"; ?></i>