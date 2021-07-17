<?php
$titel = "Active Clients";
$Clients = 'active';
include('include/hader.php');
include("mk_api.php");
if($userr_typ == 'mreseller'){
	$idz = $macz_id;
}
else{
$idz = $_GET['id'];
}
extract($_POST); 

ini_alter('date.timezone','Asia/Almaty');
$todaydate = date('Y-m-d', time());
$thismonth = date('M-Y', time());

//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'Clients' AND $user_type = '1'");
if(mysql_num_rows($access) > 0){
//---------- Permission -----------

$sql1z1 = mysql_query("SELECT e.mk_id, e.e_name AS reseller_name, z.z_name FROM `zone` AS z
						LEFT JOIN emp_info AS e ON e.e_id = z.e_id
						WHERE z.z_id = '$idz' AND z.status = '0' ");
$rowwz = mysql_fetch_array($sql1z1);

$mkid = $rowwz['mk_id'];
$reseller_name = $rowwz['reseller_name'];
$znamee = $rowwz['z_name'];
?>
	<div class="box box-primary">
			<div class="box-body">
			<form id="form2" class="stdform" method="post" action="<?php echo $PHP_SELF;?>" style="margin-bottom: 2px;">
				<select name="user_status" style="font-weight: bold;font-size: 10px;width:100%;text-align: center;" onchange="submit();">
					<option value=""<?php if($user_status == '') echo 'selected="selected"';?>>All Clients</option>
					<option value="Online"<?php if($user_status == 'Online') echo 'selected="selected"';?> style="color: green;">Online Clients</option>
					<option value="Offline"<?php if($user_status == 'Offline') echo 'selected="selected"';?> style="color: red;">Offline Clients</option>
				</select>
			</form>
				<table id="dyntable" class="table table-bordered responsive">
                    <colgroup>
						<col class="con0" />
                        <col class="con1" />
                        <col class="con0" />
                    </colgroup>
                    <thead>
                        <tr  class="newThead">
                            <th class="head0" style="font-size: 9px;padding: 5px;text-align: center;">SL</th>
                            <th class="head0" style="font-size: 9px;padding: 5px;text-align: center;">ID/Name/Cell</th>
							<th class="head1" style="font-size: 9px;padding: 5px;text-align: center;">Mac/IP/Uptime/Logout</th>
							<th class="head1" style="font-size: 9px;padding: 5px;text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php		
							$sqlmk1 = mysql_query("SELECT id, ServerIP, Username, Pass, Port, e_Md, secret_h FROM mk_con WHERE sts = '0' AND id = '$mkid'");
						$rowmk1 = mysql_fetch_assoc($sqlmk1);
						
						$ServerIP1 = $rowmk1['ServerIP'];
						$Username1 = $rowmk1['Username'];
						$Pass1= openssl_decrypt($rowmk1['Pass'], $rowmk1['e_Md'], $rowmk1['secret_h']);
						$Port1 = $rowmk1['Port'];
						$API = new routeros_api();
						$API->debug = false;
						if ($API->connect($ServerIP1, $Username1, $Pass1, $Port1)) {
								$arrID = $API->comm('/ppp/active/getall');								
								foreach($arrID as $x => $x_value) {
									$aaaaa[] = $x_value['name'];
								}				
								$idList = implode("','",$aaaaa);
	$sqlcount = mysql_query("SELECT id FROM clients WHERE z_id = '$idz' AND sts = '0' AND c_id IN ('$idList')");
	$activ_count = mysql_num_rows($sqlcount);
	
	$sqlcounty = mysql_query("SELECT id FROM clients WHERE z_id = '$idz' AND sts = '0'");
	$inactiv_count = mysql_num_rows($sqlcounty);
								
	$sql44 = mysql_query("SELECT c.c_name, c.c_id, c.cell, c.note, c.note_auto, c.con_sts FROM clients AS c
												LEFT JOIN zone AS z ON z.z_id = c.z_id 
												WHERE z.z_id = '$idz' AND c.sts = '0' ORDER BY c.c_id ASC");

$x = 1;	
	while( $rows1 = mysql_fetch_assoc($sql44) ){
		
		$idss = $rows1['c_id'];
		$API->write('/ppp/active/print', false);
		$API->write('?name='.$idss);
		$res=$API->read(true);

		$ppp_mac = $res[0]['caller-id'];
		$ppp_ip = $res[0]['address'];
		$ppp_uptime = $res[0]['uptime'];
		$ppp_ip = $res[0]['address'];
		
		if($user_status == ''){
			if($ppp_uptime == ''){
				$online_sts =  "<a data-placement='top' data-rel='tooltip' style='font-weight: bold;font-size: 10px;' class='btn inact'>Offline</a>";
				
			$API->write('/ppp/secret/getall', false);
			$API->write('?name='.$idss);
			$resuu=$API->read(true);
			
			$lastloggedout = $resuu[0]['last-logged-out'];
			$hideeeee = "style='font-size: 10px;'";
			$bbb = "<a data-placement='top' data-rel='tooltip' style='padding: 3px 5px 0px 5px;font-size: 10px;font-weight: bold;' href='ClientView?id=".$idss."' class=''>".$idss."</a>";
			}
			else{
				$online_sts =  "<a data-placement='top' data-rel='tooltip' style='font-weight: bold;font-size: 10px;' class='btn act'>Online</a>";
				$lastloggedout = "";
				$hideeeee = "style='font-size: 10px;'";
				$bbb = "<a data-placement='top' data-rel='tooltip' style='padding: 3px 5px 0px 5px;font-size: 10px;font-weight: bold;' href='ClientView?id=".$idss."' class=''>".$idss."</a>";
			}
		}
		if($user_status == 'Online'){
			if($ppp_uptime != ''){
				$online_sts =  "<a data-placement='top' data-rel='tooltip' style='font-weight: bold;font-size: 10px;' class='btn act'>Online</a>";
				$lastloggedout = "";
				$hideeeee = "style='font-size: 10px;'";
				$bbb = "<a data-placement='top' data-rel='tooltip' style='padding: 3px 5px 0px 5px;font-size: 10px;font-weight: bold;' href='ClientView?id=".$idss."' class=''>".$idss."</a>";

			}
			else{
				$online_sts =  "";
				$lastloggedout = "";
				$hideeeee = "style='display: none;'";
				$bbb = "";

			}
		}
		if($user_status == 'Offline'){
			if($ppp_uptime == ''){
				$online_sts =  "<a data-placement='top' data-rel='tooltip' style='font-weight: bold;font-size: 10px;' class='btn inact'>Offline</a>";
				$hideeeee = "style='font-size: 10px;'";
				
			$API->write('/ppp/secret/getall', false);
			$API->write('?name='.$idss);
			$resuu=$API->read(true);
			
			$lastloggedout = $resuu[0]['last-logged-out'];
			$bbb = "<a data-placement='top' data-rel='tooltip' style='padding: 3px 5px 0px 5px;font-size: 10px;font-weight: bold;' href='ClientView?id=".$idss."' class=''>".$idss."</a>";

			}
			else{
				$online_sts =  "";
				$lastloggedout = "";
				$hideeeee = "style='display: none;'";
				$bbb = "";

			}
		}
		echo
										"<tr class='gradeX'>
											<td $hideeeee class='center'><b>{$x}</b></td>
											<td $hideeeee class='center'><b>{$bbb}</b><br><b>{$rows1['c_name']}</b><br>{$rows1['cell']}</td>
											<td $hideeeee class='center'><b>{$ppp_ip}<br>{$ppp_uptime}{$lastloggedout}<br>{$ppp_mac}</b></td>
											<td $hideeeee class='center'>{$online_sts}</td>
										</tr>\n ";
										$x++;
	}
$API->disconnect();
						}
						else{echo 'Selected Network are not Connected.';}
							?>
					</tbody>
				</table>
				<div style="font-size: 17px;font-weight: bold;text-align: center;padding: 0px;border: 1px solid #ddd;"><a style="color: green;padding-right: 11px;">Online: <?php echo $activ_count;?></a>     ||     <a style="color: red;padding-left: 11px;">Offline: <?php echo $inactiv_count - $activ_count;?></a></div>
			</div>	
	</div>
				
<?php
}
else{
	include('include/index');
}
include('include/footer.php');
?>