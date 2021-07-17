<?php
$titel = "Active Clients";
$Clients = 'active';
include('include/hader.php');
include("mk_api.php");
extract($_POST); 

ini_alter('date.timezone','Asia/Almaty');
$todaydate = date('Y-m-d', time());
$thismonth = date('M-Y', time());

//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'Clients' AND $user_type = '1'");
if(mysql_num_rows($access) > 0){
//---------- Permission -----------

?>
	<div class="box box-primary">
			<div class="box-body">
						<div id='responsecontainer1' style="font-size: 12px;font-weight: bold;text-align: center;padding: 0px;border: 1px solid #ddd;"></div>
				<table id="dyntable" class="table table-bordered responsive">
                    <colgroup>
						<col class="con0" />
                        <col class="con1" />
                        <col class="con0" />
                    </colgroup>
                    <thead>
                        <tr  class="newThead">
                            <th class="head0" style="font-size: 9px;padding: 5px;text-align: center;">ID/Name/Cell</th>
							<th class="head1" style="font-size: 9px;padding: 5px;text-align: center;">Mac/IP</th>
							<th class="head1" style="font-size: 9px;padding: 5px;text-align: center;">Uptime</th>
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
								$dd=0;
								foreach($arrID as $x => $x_value) {
									$aaaaa = $x_value['name'];
									$sql44 = mysql_query("SELECT c.c_name, c.c_id, c.payment_deadline, m.Name, c.p_id, p.p_name, p.bandwith, c.address, z.z_name, c.cell, c.note, c.note_auto, c.con_sts, DATE_FORMAT(c.join_date, '%D %M %Y') AS join_date, c.p_id, l.log_sts FROM clients AS c
												LEFT JOIN package AS p ON p.p_id = c.p_id
												LEFT JOIN zone AS z ON z.z_id = c.z_id 
												LEFT JOIN login AS l ON l.e_id = c.c_id 
												LEFT JOIN mk_con AS m ON m.id = c.mk_id 
												WHERE c.c_id = '$aaaaa' AND c.z_id='$macz_id' AND c.mk_id = '$mkid'");
									$rows1 = mysql_fetch_assoc($sql44);
									
									
									$sqlcon = mysql_query("SELECT s.c_id, s.con_sts, DATE_FORMAT(s.update_date, '%D %M %Y') AS update_date, s.update_time, s.update_by, e.e_name FROM con_sts_log AS s 
									LEFT JOIN emp_info AS e ON e.e_id = s.update_by
									WHERE s.c_id = '$aaaaa' AND s.con_sts = 'inactive' ORDER BY s.id DESC LIMIT 1");
									$rowcon = mysql_fetch_assoc($sqlcon);
									
									if($rows1['c_name'] == ''){
										$colorrrr = 'style="color: red;font-size: 10px;padding: 3px 5px 3px 5px;font-weight: bold;line-height: 1.3;"'; 
										$qqqqq = 'ID Not Matched'; 
										$bbbbb = '';
										$wwww = 'Not found';
										$wwwws = 'Not found';
										$dddd = "";
										$bbb = $aaaaa;
										$hide = 'style="display: none;"';
										} 
										else{
											$bbb = "<a data-placement='top' data-rel='tooltip' style='padding: 3px 5px 0px 5px;font-size: 10px;font-weight: bold;' href='ClientView?id=".$aaaaa."' class=''>".$aaaaa."</a>";
											$colorrrr = 'style="font-size: 10px;padding: 0;line-height: 1.3;"'; 
											$qqqqq = "<a data-placement='top' data-rel='tooltip' href='ClientView?id=".$rows1['c_id']."' data-original-title='View Client' target='_blank' class='btn col1'><i class='fa iconfa-eye-open'></i></a>";
												if($rows1['con_sts'] == 'Active'){
													$clss = 'act';
													$ee = 'Active';
													$wwww = '-';
													$colorrrr = 'style="font-size: 10px;padding: 0;line-height: 1.3;"';
													$bbbbb = "<a data-placement='top' data-rel='tooltip' style='padding: 3px 5px 3px 5px;font-size: 10px;font-weight: bold;border: 1px solid #bbb;' class='btn {$clss}'>{$ee}</a>";
													$dddd = "";
													$wwwws = '';
													$hide = '';
												}
												if($rows1['con_sts'] == 'Inactive'){
													$clss = 'inact';
													$ee = 'Inactive';
													$colorrrr = 'style="color: red;padding: 0;font-size: 10px;font-weight: bold;line-height: 1.3;"'; 
													if($rowcon['update_by'] == 'Auto'){$empname = 'Auto';} else{$empname = $rowcon['e_name'];}
													$wwww = $ee.' in application but Active in Mikrotik Since '.$rowcon['update_date'].' by '.$empname;
													$bbbbb = "<a data-placement='top' data-rel='tooltip' style='padding: 3px 5px 3px 5px;font-size: 10px;font-weight: bold;border: 1px solid #bbb;line-height: 1;' href='NetworkActiveTOInactive?id=".$aaaaa."' class='btn {$clss}' onclick='return checksts1()'>Inactive<br>him<br>in<br>Mikrotik</a>";
													$dddd = "OR<br><a data-placement='top' data-rel='tooltip' style='padding: 3px 5px 3px 5px;font-size: 10px;font-weight: bold;border: 1px solid #bbb;color: green;line-height: 1;' href='ClientStatus?id=".$aaaaa."' class='btn {$clss}' onclick='return checksts()'>Active<br>him<br>in<br>App</a>";
													$wwwws = '';
													$hide = '';
												}
											}
											
								if($user_type == 'mreseller'){
									echo "<tr class='gradeX' ".$hide." style='line-height: 1.2;'>
											<td class='center' $colorrrr>".$bbb."<br>". $rows1['c_name'] ."<br> ".$rows1['cell']."</td>
											<td class='center' $colorrrr><b style='line-height: 1.2;'>" . $x_value['address'] ."<br>" . $x_value['uptime'] ."<br>" . $x_value['caller-id'] ."</b></td>
											<td class='center' style='padding: 8px 0px 2px 2px;'>
												<ul class='tooltipsample' style='margin-bottom: 0px !important;'>
													<li $colorrrr><b style='line-height: 1.2;'>".$wwwws."".$bbbbb."<br>".$dddd."</b></li>
												</ul>
											</td>
										</tr>";
								}
								}
								 $API->disconnect();

						}
						else{echo 'Selected Network are not Connected.';}
							?>
					</tbody>
				</table>
			</div>	
	</div>
				
<?php
}
else{
	include('include/index');
}
include('include/footer.php');
?>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
 $(document).ready(function() {
 	 $("#responsecontainer1").load("AutoActiveMacClientsCount.php?ids=<?php echo $mkid;?>");
   var refreshId = setInterval(function() {
	  $("#responsecontainer1").load('AutoActiveMacClientsCount.php?ids=<?php echo $mkid;?>');
   }, 50);
   $.ajaxSetup({ cache: false });
});
</script>