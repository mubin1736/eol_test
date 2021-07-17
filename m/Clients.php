<?php
$titel = "Clients";
$Clients = 'active';
include('include/hader.php');
$type = $_GET['id'];
extract($_POST); 

$dateTimeee = date('Y-m-d', time());
$dateMonth = date('F Y', time());
//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'Clients' AND $user_type = '1'");
$access1 = mysql_query("SELECT * FROM module_page WHERE parent_id = '8' AND $user_type = '1'");
if(mysql_num_rows($access) > 0 && mysql_num_rows($access1) > 0){
//---------- Permission -----------
			if($user_type == 'admin' || $user_type == 'superadmin' || $user_type == 'support' || $user_type == 'accounts' || $user_type == 'billing_manager' || $user_type == 'support_manager' || $user_type == 'ets'){
							if($type == 'all' || $type == '' && in_array(114, $access_arry)){
							$sql = mysql_query("SELECT c.c_name, l.pw, c.com_id, c.onu_mac, e.e_name AS technician, c.c_id, c.payment_deadline, m.Name, c.mac_user, c.breseller, c.p_id, p.p_name, p.bandwith, c.raw_download, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, c.address, z.z_name, c.cell, c.note, c.note_auto, c.con_sts, DATE_FORMAT(c.join_date, '%D %M %Y') AS join_date, c.p_id, l.log_sts FROM clients AS c
												LEFT JOIN package AS p ON p.p_id = c.p_id
												LEFT JOIN zone AS z ON z.z_id = c.z_id 
												LEFT JOIN login AS l ON l.e_id = c.c_id 
												LEFT JOIN mk_con AS m ON m.id = c.mk_id 
                                                LEFT JOIN emp_info AS e ON e.e_id = c.technician
												WHERE c.sts = '0' AND mac_user !='1' ORDER BY c.id DESC");
												
							$sqls = mysql_query("SELECT c_id FROM clients WHERE sts = '0' AND con_sts = 'Active' AND mac_user !='1'");
							$sqlss = mysql_query("SELECT c_id FROM clients AS c	LEFT JOIN login AS l ON l.e_id = c.c_id WHERE c.sts = '0' AND l.log_sts = '1' AND mac_user !='1'");
							$tot = mysql_num_rows($sql);
							$act = mysql_num_rows($sqls);
							$loc = mysql_num_rows($sqlss);
							$inact = $tot - $act;
							
							$tit = "<div class='box-header'>
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Total:  <i style='color: #317EAC'>{$tot}</i></div> 
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Active:  <i style='color: #30ad23'>{$act}</i></div> 
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Inactive: <i style='color: #e3052e'>{$inact}</i></div> 
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Lock: <i style='color: #f66305'>{$loc}</i></div>
									</div>";
							}
							if($type == 'macclient' && in_array(115, $access_arry)){
							$sql = mysql_query("SELECT c.c_name, c.com_id, c.onu_mac, c.termination_date, e.e_name AS technician, c.b_date, c.c_id, c.payment_deadline, c.raw_download, c.breseller, c.mac_user, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, m.Name, c.breseller, c.p_id, p.p_name, p.bandwith, c.address, z.z_name, c.cell, c.note, c.note_auto, c.con_sts, DATE_FORMAT(c.join_date, '%D %M %Y') AS join_date, c.p_id FROM clients AS c
												LEFT JOIN package AS p ON p.p_id = c.p_id
												LEFT JOIN zone AS z ON z.z_id = c.z_id 
												LEFT JOIN mk_con AS m ON m.id = c.mk_id 
												LEFT JOIN emp_info AS e ON e.e_id = c.technician
												WHERE c.sts = '0' AND mac_user ='1' ORDER BY c.id DESC");
												
							$sqls = mysql_query("SELECT c_id FROM clients WHERE sts = '0' AND con_sts = 'Active' AND mac_user ='1'");
							$sqlss = mysql_query("SELECT c_id FROM clients AS c	LEFT JOIN login AS l ON l.e_id = c.c_id WHERE c.sts = '0' AND l.log_sts = '1' AND mac_user ='1'");
							$tot = mysql_num_rows($sql);
							$act = mysql_num_rows($sqls);
							$loc = mysql_num_rows($sqlss);
							$inact = $tot - $act;
							
							$tit = "<div class='box-header'>
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Total:  <i style='color: #317EAC'>{$tot}</i></div> 
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Active:  <i style='color: #30ad23'>{$act}</i></div> 
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Inactive: <i style='color: #e3052e'>{$inact}</i></div> 
									</div>";
							}
							if($type == 'active' && in_array(112, $access_arry)){
							$sql = mysql_query("SELECT c.c_name, l.pw, c.onu_mac, c.com_id, e.e_name AS technician, c.c_id, c.payment_deadline, m.Name, c.breseller, c.mac_user, c.raw_download, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, c.p_id, p.p_name, p.bandwith, c.address, z.z_name, c.cell, c.note, c.note_auto, c.con_sts, DATE_FORMAT(c.join_date, '%D %M %Y') AS join_date, c.p_id, l.log_sts FROM clients AS c
												LEFT JOIN package AS p ON p.p_id = c.p_id
												LEFT JOIN zone AS z ON z.z_id = c.z_id 
												LEFT JOIN login AS l ON l.e_id = c.c_id 
												LEFT JOIN mk_con AS m ON m.id = c.mk_id 
												LEFT JOIN emp_info AS e ON e.e_id = c.technician
												WHERE c.sts = '0' AND c.con_sts = 'Active' AND mac_user !='1' ORDER BY c.id DESC");
							$tot = mysql_num_rows($sql);
							$tit = "<div class='box-header'>
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Total Active Clints:  <i style='color: #30ad23'>{$tot}</i></div>
									</div>";
							}
							if($type == 'inactive' && in_array(113, $access_arry)){
							$sql = mysql_query("SELECT c.c_name, l.pw, c.onu_mac, c.com_id, e.e_name AS technician, c.c_id, c.payment_deadline, m.Name, c.breseller, c.mac_user, c.raw_download, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, c.p_id, p.p_name, p.bandwith, c.address, z.z_name, c.cell, c.note, c.note_auto, c.con_sts, DATE_FORMAT(c.join_date, '%D %M %Y') AS join_date, c.p_id, l.log_sts FROM clients AS c
												LEFT JOIN package AS p ON p.p_id = c.p_id
												LEFT JOIN zone AS z ON z.z_id = c.z_id 
												LEFT JOIN login AS l ON l.e_id = c.c_id 
												LEFT JOIN mk_con AS m ON m.id = c.mk_id 
												LEFT JOIN emp_info AS e ON e.e_id = c.technician
												WHERE c.sts = '0' AND c.con_sts = 'Inactive' AND mac_user !='1' ORDER BY c.id DESC");
							$inact = mysql_num_rows($sql);
							$tit = "<div class='box-header'>
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Total Inactive Clints: <i style='color: #e3052e'>{$inact}</i></div> 
									</div>";
							}
							if($type == 'lock' && in_array(117, $access_arry)){
							$sql = mysql_query("SELECT c.c_name, l.pw, c.onu_mac, c.com_id, e.e_name AS technician, c.c_id, c.payment_deadline, m.Name, c.breseller, c.mac_user, c.raw_download, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, c.p_id, p.p_name, p.bandwith, c.address, z.z_name, c.cell, c.note, c.note_auto, c.con_sts, DATE_FORMAT(c.join_date, '%D %M %Y') AS join_date, c.p_id, l.log_sts FROM clients AS c
												LEFT JOIN package AS p ON p.p_id = c.p_id
												LEFT JOIN zone AS z ON z.z_id = c.z_id 
												LEFT JOIN login AS l ON l.e_id = c.c_id 
												LEFT JOIN mk_con AS m ON m.id = c.mk_id 
												LEFT JOIN emp_info AS e ON e.e_id = c.technician
												WHERE c.sts = '0' AND l.log_sts = '1' AND mac_user !='1' ORDER BY c.id DESC");
							$locks = mysql_num_rows($sql);
							$tit = "<div class='box-header'>
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Total Lock Clints: <i style='color: #f66305'>{$locks}</i></div>
									</div>";
							}
							if($type == 'auto' && in_array(116, $access_arry)){
							$sql = mysql_query("SELECT c.c_name, l.pw, c.onu_mac, c.com_id, e.e_name AS technician, c.c_id, c.payment_deadline, m.Name, c.breseller, c.mac_user, c.p_id, p.p_name, c.raw_download, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, p.bandwith, c.address, z.z_name, c.cell, c.note, c.note_auto, c.con_sts, DATE_FORMAT(c.join_date, '%D %M %Y') AS join_date, c.p_id, l.log_sts FROM clients AS c
												LEFT JOIN package AS p ON p.p_id = c.p_id
												LEFT JOIN zone AS z ON z.z_id = c.z_id 
												LEFT JOIN login AS l ON l.e_id = c.c_id 
												LEFT JOIN mk_con AS m ON m.id = c.mk_id 
												LEFT JOIN con_sts_log AS o ON o.c_id = c.c_id 
												LEFT JOIN emp_info AS e ON e.e_id = c.technician
												WHERE c.sts = '0' AND c.con_sts = 'Inactive' AND mac_user !='1' AND o.update_by = 'Auto' ORDER BY o.update_date DESC LIMIT 200");
							$locks = mysql_num_rows($sql);
							$tit = "<div class='box-header'>
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'>Auto Inactive Clients for Due Bill</div>
									</div>";
							}
							if($type == 'new'){
							$sql = mysql_query("SELECT c.c_name, l.pw, c.onu_mac, c.com_id, e.e_name AS technician, c.c_id, c.payment_deadline, m.Name, c.p_id, p.p_name, p.bandwith, c.address, z.z_name, c.cell, c.note, c.note_auto, c.con_sts, DATE_FORMAT(c.join_date, '%D %M %Y') AS join_date, c.p_id, l.log_sts FROM clients AS c
												LEFT JOIN package AS p ON p.p_id = c.p_id
												LEFT JOIN zone AS z ON z.z_id = c.z_id 
												LEFT JOIN login AS l ON l.e_id = c.c_id 
												LEFT JOIN mk_con AS m ON m.id = c.mk_id 
												LEFT JOIN emp_info AS e ON e.e_id = c.technician
												WHERE c.sts = '0' AND MONTH(c.join_date) = MONTH('$dateTimeee') AND YEAR(c.join_date) = YEAR('$dateTimeee') AND mac_user !='1' ORDER BY c.id DESC");
							$locks = mysql_num_rows($sql);
							$tit = "<div class='box-header'>
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'>$dateMonth New Clients.</div>
									</div>";
							}
							$queryrgg = mysql_query("SELECT c_id, c_name, cell, address FROM clients WHERE sts = '0' AND mac_user = '0' ORDER BY c_name");
							
						$query11="SELECT id, Name, ServerIP FROM mk_con WHERE sts = '0' ORDER BY id ASC";
						$result11=mysql_query($query11);
						}
					if($user_type == 'billing')
						{
							if($type == 'all' || $type == '' && in_array(114, $access_arry)){
							$sql = mysql_query("SELECT c.c_name, l.pw, c.com_id, c.onu_mac, e.e_name AS technician, c.c_id, c.payment_deadline, m.Name, c.breseller, c.mac_user, c.raw_download, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, c.p_id, p.p_name, p.bandwith, c.address, z.z_name, c.cell, c.note, c.note_auto, c.con_sts, DATE_FORMAT(c.join_date, '%D %M %Y') AS join_date, c.p_id, l.log_sts FROM clients AS c
												LEFT JOIN package AS p ON p.p_id = c.p_id
												LEFT JOIN zone AS z ON z.z_id = c.z_id 
												LEFT JOIN login AS l ON l.e_id = c.c_id 
												LEFT JOIN mk_con AS m ON m.id = c.mk_id 
												LEFT JOIN emp_info AS e ON e.e_id = c.technician
												WHERE c.sts = '0' AND mac_user !='1' AND z.emp_id = '$e_id' ORDER BY c.id DESC");
							$sqls = mysql_query("SELECT c.c_id FROM clients AS c LEFT JOIN zone AS z ON z.z_id = c.z_id WHERE c.sts = '0' AND z.emp_id = '$e_id' AND c.con_sts = 'Active' AND c.mac_user !='1'");
							$sqlss = mysql_query("SELECT c_id FROM clients AS c	LEFT JOIN login AS l ON l.e_id = c.c_id LEFT JOIN zone AS z ON z.z_id = c.z_id WHERE c.sts = '0' AND z.emp_id = '$e_id' AND l.log_sts = '1' AND mac_user !='1'");
							$tot = mysql_num_rows($sql);
							$act = mysql_num_rows($sqls);
							$loc = mysql_num_rows($sqlss);
							$inact = $tot - $act;
							
							$tit = "<div class='box-header'>
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Total:  <i style='color: #317EAC'>{$tot}</i></div> 
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Active:  <i style='color: #30ad23'>{$act}</i></div> 
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Inactive: <i style='color: #e3052e'>{$inact}</i></div> 
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Lock: <i style='color: #f66305'>{$loc}</i></div>
									</div>";
							}
							if($type == 'active' && in_array(112, $access_arry)){
							$sql = mysql_query("SELECT c.c_name, l.pw, c.com_id, c.onu_mac, e.e_name AS technician, c.c_id, c.payment_deadline, c.raw_download, c.breseller, c.mac_user, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, m.Name, c.p_id, p.p_name, p.bandwith, c.address, z.z_name, c.cell, c.note, c.note_auto, c.con_sts, DATE_FORMAT(c.join_date, '%D %M %Y') AS join_date, c.p_id, l.log_sts FROM clients AS c
												LEFT JOIN package AS p ON p.p_id = c.p_id
												LEFT JOIN zone AS z ON z.z_id = c.z_id 
												LEFT JOIN login AS l ON l.e_id = c.c_id 
												LEFT JOIN mk_con AS m ON m.id = c.mk_id 
												LEFT JOIN emp_info AS e ON e.e_id = c.technician
												WHERE c.sts = '0' AND c.con_sts = 'Active' AND mac_user !='1' AND z.emp_id = '$e_id' ORDER BY c.id DESC");
							$tot = mysql_num_rows($sql);
							$tit = "<div class='box-header'>
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Total Active Clints:  <i style='color: #30ad23'>{$tot}</i></div>
									</div>";
							}
							if($type == 'inactive' && in_array(113, $access_arry)){
							$sql = mysql_query("SELECT c.c_name, l.pw, c.com_id, c.onu_mac, e.e_name AS technician, c.c_id, c.payment_deadline, c.raw_download, c.breseller, c.mac_user, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, m.Name, c.p_id, p.p_name, p.bandwith, c.address, z.z_name, c.cell, c.note, c.note_auto, c.con_sts, DATE_FORMAT(c.join_date, '%D %M %Y') AS join_date, c.p_id, l.log_sts FROM clients AS c
												LEFT JOIN package AS p ON p.p_id = c.p_id
												LEFT JOIN zone AS z ON z.z_id = c.z_id 
												LEFT JOIN login AS l ON l.e_id = c.c_id 
												LEFT JOIN mk_con AS m ON m.id = c.mk_id 
												LEFT JOIN emp_info AS e ON e.e_id = c.technician
												WHERE c.sts = '0' AND c.con_sts = 'Inactive' AND mac_user !='1' AND z.emp_id = '$e_id' ORDER BY c.id DESC");
							$inact = mysql_num_rows($sql);
							$tit = "<div class='box-header'>
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Total Inactive Clints: <i style='color: #e3052e'>{$inact}</i></div> 
									</div>";
							}
							if($type == 'lock' && in_array(117, $access_arry)){
							$sql = mysql_query("SELECT c.c_name, l.pw, c.com_id, c.onu_mac, e.e_name AS technician, c.c_id, c.payment_deadline, c.raw_download, c.breseller, c.mac_user, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, m.Name, c.p_id, p.p_name, p.bandwith, c.address, z.z_name, c.cell, c.note, c.note_auto, c.con_sts, DATE_FORMAT(c.join_date, '%D %M %Y') AS join_date, c.p_id, l.log_sts FROM clients AS c
												LEFT JOIN package AS p ON p.p_id = c.p_id
												LEFT JOIN zone AS z ON z.z_id = c.z_id 
												LEFT JOIN login AS l ON l.e_id = c.c_id 
												LEFT JOIN mk_con AS m ON m.id = c.mk_id 
												LEFT JOIN emp_info AS e ON e.e_id = c.technician
												WHERE c.sts = '0' AND l.log_sts = '1' AND mac_user !='1' AND z.emp_id = '$e_id' ORDER BY c.id DESC");
							$locks = mysql_num_rows($sql);
							$tit = "<div class='box-header'>
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Total Lock Clints: <i style='color: #f66305'>{$locks}</i></div>
									</div>";
							}
							if($type == 'auto' && in_array(116, $access_arry)){
							$sql = mysql_query("SELECT c.c_name, l.pw, c.com_id, c.onu_mac, e.e_name AS technician, c.c_id, c.payment_deadline, c.raw_download, c.breseller, c.mac_user, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, m.Name, c.p_id, p.p_name, p.bandwith, c.address, z.z_name, c.cell, c.note, c.note_auto, c.con_sts, DATE_FORMAT(c.join_date, '%D %M %Y') AS join_date, c.p_id, l.log_sts FROM clients AS c
												LEFT JOIN package AS p ON p.p_id = c.p_id
												LEFT JOIN zone AS z ON z.z_id = c.z_id 
												LEFT JOIN login AS l ON l.e_id = c.c_id 
												LEFT JOIN mk_con AS m ON m.id = c.mk_id 
												LEFT JOIN con_sts_log AS o ON o.c_id = c.c_id 
												LEFT JOIN emp_info AS e ON e.e_id = c.technician
												WHERE c.sts = '0' AND c.con_sts = 'Inactive' AND mac_user !='1' AND o.update_by = 'Auto' AND z.emp_id = '$e_id' ORDER BY o.update_date DESC LIMIT 200");
							$locks = mysql_num_rows($sql);
							$tit = "<div class='box-header'>
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'>Auto Inactive Clients for Due Bill</div>
									</div>";
							}
							if($type == 'new'){
							$sql = mysql_query("SELECT c.c_name, l.pw, c.com_id, c.onu_mac, e.e_name AS technician, c.c_id, c.payment_deadline, m.Name, c.p_id, p.p_name, p.bandwith, c.address, z.z_name, c.cell, c.note, c.note_auto, c.con_sts, DATE_FORMAT(c.join_date, '%D %M %Y') AS join_date, c.p_id, l.log_sts FROM clients AS c
												LEFT JOIN package AS p ON p.p_id = c.p_id
												LEFT JOIN zone AS z ON z.z_id = c.z_id 
												LEFT JOIN login AS l ON l.e_id = c.c_id 
												LEFT JOIN mk_con AS m ON m.id = c.mk_id 
												LEFT JOIN emp_info AS e ON e.e_id = c.technician
												WHERE c.sts = '0' AND MONTH(c.join_date) = MONTH('$dateTimeee') AND YEAR(c.join_date) = YEAR('$dateTimeee') AND mac_user !='1' AND z.emp_id = '$e_id' ORDER BY c.id DESC");
							$locks = mysql_num_rows($sql);
							$tit = "<div class='box-header'>
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'>$dateMonth New Clients.</div>
									</div>";
							}
							$queryrgg = mysql_query("SELECT c.c_id, c.c_name, c.cell, c.address FROM clients AS c LEFT JOIN zone AS z ON z.z_id = c.z_id WHERE c.sts = '0' AND c.mac_user = '0' AND z.emp_id = '$e_id' ORDER BY c.c_name");

						}
						
					if($user_type == 'mreseller')
						{
							if($type == '' || $type == 'all'){
							$sql = mysql_query("SELECT c.c_name, l.pw, c.com_id, c.onu_mac, c.termination_date, e.e_name AS technician, c.c_id, c.payment_deadline, c.raw_download, c.breseller, c.mac_user, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, m.Name, c.breseller, c.p_id, p.p_name, p.bandwith, c.address, z.z_name, c.cell, c.note, c.note_auto, c.con_sts, DATE_FORMAT(c.join_date, '%D %M %Y') AS join_date, c.p_id, l.log_sts FROM clients AS c
												LEFT JOIN package AS p ON p.p_id = c.p_id
												LEFT JOIN zone AS z ON z.z_id = c.z_id 
												LEFT JOIN login AS l ON l.e_id = c.c_id 
												LEFT JOIN mk_con AS m ON m.id = c.mk_id 
												LEFT JOIN emp_info AS e ON e.e_id = c.technician
												WHERE c.sts = '0' AND z.z_id = '$macz_id' ORDER BY c.termination_date ASC");
												
							$sqls = mysql_query("SELECT c_id FROM clients WHERE sts = '0' AND con_sts = 'Active' AND mac_user = '1' AND z_id = '$macz_id'");
							$sqlss = mysql_query("SELECT c_id FROM clients AS c	LEFT JOIN login AS l ON l.e_id = c.c_id WHERE c.sts = '0' AND l.log_sts = '1' AND c.mac_user = '1' AND c.z_id = '$macz_id'");
							$tot = mysql_num_rows($sql);
							$act = mysql_num_rows($sqls);
							$loc = mysql_num_rows($sqlss);
							$inact = $tot - $act;
							
							$tit = "<div class='box-header'>
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Total:  <i style='color: #317EAC'>{$tot}</i></div> 
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Active:  <i style='color: #30ad23'>{$act}</i></div> 
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Inactive: <i style='color: #e3052e'>{$inact}</i></div>
									</div>";
							}
							if($type == 'active'){
							$sql = mysql_query("SELECT c.c_name, l.pw, c.com_id, c.onu_mac, c.termination_date, e.e_name AS technician, c.c_id, c.payment_deadline, c.raw_download, c.breseller, c.mac_user, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, m.Name, c.breseller, c.p_id, p.p_name, p.bandwith, c.address, z.z_name, c.cell, c.note, c.note_auto, c.con_sts, DATE_FORMAT(c.join_date, '%D %M %Y') AS join_date, c.p_id, l.log_sts FROM clients AS c
												LEFT JOIN package AS p ON p.p_id = c.p_id
												LEFT JOIN zone AS z ON z.z_id = c.z_id 
												LEFT JOIN login AS l ON l.e_id = c.c_id 
												LEFT JOIN mk_con AS m ON m.id = c.mk_id 
												LEFT JOIN emp_info AS e ON e.e_id = c.technician
												WHERE c.sts = '0' AND c.con_sts = 'Active' AND z.z_id = '$macz_id' ORDER BY c.termination_date ASC");
							$tot = mysql_num_rows($sql);
							$tit = "<div class='box-header'>
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Total Active Clints:  <i style='color: #30ad23'>{$tot}</i></div>
									</div>";
							}
							if($type == 'inactive'){
							$sql = mysql_query("SELECT c.c_name, l.pw, c.com_id, c.onu_mac, c.termination_date, e.e_name AS technician, c.c_id, c.payment_deadline, c.raw_download, c.breseller, c.mac_user, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, m.Name, c.breseller, c.p_id, p.p_name, p.bandwith, c.address, z.z_name, c.cell, c.note, c.note_auto, c.con_sts, DATE_FORMAT(c.join_date, '%D %M %Y') AS join_date, c.p_id, l.log_sts FROM clients AS c
												LEFT JOIN package AS p ON p.p_id = c.p_id
												LEFT JOIN zone AS z ON z.z_id = c.z_id 
												LEFT JOIN login AS l ON l.e_id = c.c_id 
												LEFT JOIN mk_con AS m ON m.id = c.mk_id 
												LEFT JOIN emp_info AS e ON e.e_id = c.technician
												WHERE c.sts = '0' AND c.con_sts = 'Inactive' AND z.z_id = '$macz_id' ORDER BY c.termination_date ASC");
							$inact = mysql_num_rows($sql);
							$tit = "<div class='box-header'>
										<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Total Inactive Clints: <i style='color: #e3052e'>{$inact}</i></div> 
									</div>";
							}
							$queryrgg = mysql_query("SELECT c_id, c_name, cell, address FROM clients WHERE sts = '0' AND mac_user = '1' AND z_id = '$macz_id' ORDER BY c_name");
							
						$queryaaaa = mysql_query("SELECT mk_id FROM emp_info WHERE e_id = '$e_id'");
									$rowaaaa = mysql_fetch_assoc($queryaaaa);
									$mkid = $rowaaaa['mk_id'];
						}

?>
	<div class="box box-primary">
		<div class="box-header">
		<?php if($userr_typ == 'mreseller'){ if($aaaa > 0 && $billing_typee == 'Prepaid' || $billing_typee == 'Postpaid'){ if($limit_accs == 'Yes'){?>
			<a class="btn btn-primary" style="float: right;margin-right: 2px;font-weight: bold;" href="MACClientAdd"><i class="iconfa-plus" style="font-size: 11px;"></i></a>
			<?php } else{ ?> 
				<a style='font-size: 10px;font-weight: bold;color: red;font-family: Monaco,Menlo,Consolas,"Courier New",monospace;'>[Contact Admin]</a>			
			<?php }}?>
			<a class="btn btn-red" style="float: right;margin-right: 2px;font-size: 9px;font-weight: bold;" href="Clients?id=inactive">Inactive</a>
			<a class="btn btn-green" style="float: right;margin-right: 2px;font-size: 9px;font-weight: bold;" href="Clients?id=active">Active</a> 
			<a class="btn btn-primary" style="float: right;margin-right: 2px;font-size: 9px;font-weight: bold;" href="MacResellerActiveClients">Active Connections</a> 
		<?php } if($userr_typ == 'admin' || $userr_typ == 'superadmin' || $userr_typ == 'support' || $userr_typ == 'accounts' || $userr_typ == 'billing' || $userr_typ == 'billing_manager' || $userr_typ == 'support_manager' || $userr_typ == 'ets'){ ?>
		<?php if(in_array(110, $access_arry)){ if($limit_accs == 'Yes'){ ?>
			<a class="btn btn-danger" style="float: right;margin-right: 2px;font-weight: bold;" href="ClientAdd"><i class="iconfa-plus" style="font-size: 11px;"></i></a>
		<?php } else{ ?> 
			<a style='font-size: 10px;font-weight: bold;color: red;font-family: Monaco,Menlo,Consolas,"Courier New",monospace;'>[User Limit Exceeded]</a>
		<?php }} if(in_array(115, $access_arry)){ ?>
			<a class="btn btn-neveblue" style="float: right;margin-right: 2px;font-size: 9px;font-weight: bold;" href="Clients?id=macclient">MAC Clients</a>
		<?php } if(in_array(113, $access_arry)){ ?>
			<a class="btn btn-red" style="float: right;margin-right: 2px;font-size: 9px;font-weight: bold;" href="Clients?id=inactive">Inactive</a>
		<?php } if(in_array(112, $access_arry)){ ?>
			<a class="btn btn-green" style="float: right;margin-right: 2px;font-size: 9px;font-weight: bold;" href="Clients?id=active">Active</a> 
		<?php } if(in_array(114, $access_arry)){ ?>
			<a class="btn btn-neveblue" style="float: right;margin-right: 2px;font-size: 9px;font-weight: bold;" href="Clients?id=all">All</a>
		<?php }} if($mk_id != ''){?>
			
			<?php } else{?>
			<h6 style="float: left;"><?php echo $tit; ?></h6> 
			<?php } ?>
		</div><br />
			<div class="box-body" style="padding: 2px;">
			<?php if($user_type == 'admin' || $user_type == 'superadmin'){ if($mk_id != ''){?>
			<form id="form2" class="stdform" method="post" action="<?php echo $PHP_SELF;?>">
				<select data-placeholder="Realtime Active" name="mk_id" class="chzn-select"  style="width:95%;text-align: center;font-size: 12px;font-weight: bold;" onchange="submit();">
						<option value=""></option>
						<?php while ($row11=mysql_fetch_array($result11)) { ?>
							<option value="<?php echo $row11['id']?>" <?php if($mk_id==$row11['id']) echo 'selected="selected"';?>><?php echo $row11['Name']; ?> (<?php echo $row11['ServerIP']; ?>)</option>
						<?php } ?>
				</select>
			</form>
			<?php } else{?>
			<form id="form2" class="stdform" method="post" action="<?php echo $PHP_SELF;?>">
				<select data-placeholder="Realtime Active Connections" name="mk_id" class="chzn-select"  style="width:100%;text-align: center;font-size: 12px;font-weight: bold;" onchange="submit();">
						<option value=""></option>
						<?php while ($row11=mysql_fetch_array($result11)) { ?>
							<option value="<?php echo $row11['id']?>" <?php if($mk_id==$row11['id']) echo 'selected="selected"';?>><?php echo $row11['Name']; ?> (<?php echo $row11['ServerIP']; ?>)</option>
						<?php } ?>
				</select>
			</form>
			<?php if(in_array(104, $access_arry)){?>
			<form id="form2" class="stdform" method="post" action="ClientView">
				<div class="modal-body" style="padding: 0;">
					<p>
						<select data-placeholder="Search Client" name="ids" class="chzn-select"  style="width:100%;text-align: center;font-size: 12px;font-weight: bold;" required="" onchange='this.form.submit()'>
							<option value=""></option>
							<?php while ($row2gg=mysql_fetch_array($queryrgg)) { ?>
							<option value="<?php echo $row2gg['c_id']?>"><?php echo $row2gg['c_name']; ?> | <?php echo $row2gg['c_id']; ?> | <?php echo $row2gg['cell']; ?></option>
							<?php } ?>
						</select>
					</p>
				</div>
			</form>
			<?php }}} if($user_type == 'mreseller'){?> 
			<form id="form2" class="stdform" method="post" action="ClientView">
				<div class="modal-body" style="padding: 0;">
					<p>
						<select data-placeholder="Search Client" name="ids" class="chzn-select"  style="width:100%;text-align: center;font-size: 12px;font-weight: bold;" required="" onchange='this.form.submit()'>
							<option value=""></option>
							<?php while ($row2gg=mysql_fetch_array($queryrgg)) { ?>
							<option value="<?php echo $row2gg['c_id']?>"><?php echo $row2gg['c_name']; ?> | <?php echo $row2gg['c_id']; ?> | <?php echo $row2gg['cell']; ?></option>
							<?php } ?>
						</select>
					</p>
				</div>
			</form>
			<?php } ?>
			
			<?php if($mk_id != ''){?>
			<div id='responsecontainer1' style="font-size: 12px;font-weight: bold;text-align: center;padding: 0px;border-left: 1px solid #ddd;border-right: 1px solid #ddd;"></div>
					<table id="dyntable" class="table table-bordered responsive">
				 <colgroup>
                        <col class="con0" />
                        <col class="con1" />
                        <col class="con0" />
                        <col class="con1" />
                    </colgroup>
                    <thead>
                        <tr  class="newThead">
							<th class="head0" style="font-size: 10px;padding: 5px;text-align: center;">ID/Name/Cell</th>
							<th class="head1" style="font-size: 10px;padding: 5px;text-align: center;">IP/Uptime/Mac</th>
							<th class="head0 center" style="font-size: 10px;padding: 5px;text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
		include("mk_api.php");	
		$sqlmk1 = mysql_query("SELECT id, ServerIP, Username, Pass, Port, e_Md, secret_h FROM mk_con WHERE sts = '0' AND id = '$mk_id'");
		$rowmk1 = mysql_fetch_assoc($sqlmk1);
		
		$ServerIP1 = $rowmk1['ServerIP'];
		$Username1 = $rowmk1['Username'];
		$Pass1= openssl_decrypt($rowmk1['Pass'], $rowmk1['e_Md'], $rowmk1['secret_h']);
		$Port1 = $rowmk1['Port'];
		$API = new routeros_api();
		$API->debug = false;
						if ($API->connect($ServerIP1, $Username1, $Pass1, $Port1)) {
								$arrID = $API->comm('/ppp/active/getall');
								
								echo "<div class='' id='responsecontainer'></div>";
								foreach($arrID as $x => $x_value) {
									
									$aaaaa = $x_value['name'];
									$sql44 = mysql_query("SELECT c.c_name, c.c_id, c.payment_deadline, m.Name, c.p_id, p.p_name, p.bandwith, c.address, z.z_name, c.cell, c.note, c.note_auto, c.con_sts, DATE_FORMAT(c.join_date, '%D %M %Y') AS join_date, c.p_id, l.log_sts FROM clients AS c
												LEFT JOIN package AS p ON p.p_id = c.p_id
												LEFT JOIN zone AS z ON z.z_id = c.z_id 
												LEFT JOIN login AS l ON l.e_id = c.c_id 
												LEFT JOIN mk_con AS m ON m.id = c.mk_id 
												WHERE c.c_id = '$aaaaa' ORDER BY c.id DESC");
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
										$bbbcell = "";
										} 
										else{
											$bbb = "<a data-placement='top' data-rel='tooltip' style='padding: 3px 5px 3px 5px;font-size: 10px;font-weight: bold;' href='ClientView?id=".$aaaaa."' class=''>".$aaaaa."</a>";
											$bbbcell = "<a data-placement='top' data-rel='tooltip' style='padding: 3px 5px 3px 5px;font-size: 10px;font-weight: bold;' href='' class=''>".$rows1['cell']."</a>";
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
												}
											}
											
								if($user_type == 'admin' || $user_type == 'superadmin'){
									echo "<tr class='gradeX' style='line-height: 1.2;'>
											<td class='center' $colorrrr>".$bbb."<br>". $rows1['c_name'] ."<br> ".$bbbcell."</td>
											<td class='center' $colorrrr><b style='line-height: 1.3;'>" . $x_value['address'] ."<br>" . $x_value['uptime'] ."<br>" . $x_value['caller-id'] ."</b></td>
											<td class='center' style='padding: 8px 0px 2px 2px;'>
												<ul class='tooltipsample' style='margin-bottom: 0px !important;'>
													<li $colorrrr><b style='line-height: 1.3;'>".$wwwws."".$bbbbb."<br>".$dddd."</b></li>
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

				<?php } else{ ?>
				
				<table id="dyntable" class="table table-bordered responsive">
                    <colgroup>
						<col class="con0" />
                        <col class="con1" />
                        <col class="con0" />
                    </colgroup>
                    <thead>
                        <tr  class="newThead">
						<?php if($userr_typ == 'mreseller'){?>
                            <th class="head0" style="font-size: 10px;padding: 5px;text-align: center;">PPPoE ID/Termination</th>
							<th class="head1" style="font-size: 10px;padding: 5px;text-align: center;">Left</th>
							<th class="head1" style="font-size: 10px;padding: 5px;text-align: center;">ACTION</th>
						<?php } else{if($type == 'macclient'){?>
							<th class="head0" style="font-size: 10px;padding: 5px;text-align: center;">PPPoE ID/Termination</th>
							<th class="head1" style="font-size: 10px;padding: 5px;text-align: center;">Left</th>
							<th class="head1" style="font-size: 10px;padding: 5px;text-align: center;">ACTION</th>
						<?php } else{?>
							<th class="head0" style="font-size: 10px;padding: 5px;text-align: center;">Client ID</th>
							<th class="head1" style="font-size: 10px;padding: 5px;text-align: center;">Mobile</th>
							<th class="head1" style="font-size: 10px;padding: 5px;text-align: center;">ACTION</th>
						<?php }} ?>
                        </tr>
                    </thead>
                    <tbody>
						<?php
								while( $row = mysql_fetch_assoc($sql) )
								{
								if($row['con_sts'] == 'Active'){
										$clss = 'act';
										$dd = 'Inactive';
										$ee = 'Active';
									}
									if($row['con_sts'] == 'Inactive'){
										$clss = 'inact';
										$dd = 'Active';
										$ee = 'Inactive';
									}
									if($row['log_sts'] == '0'){
										$aa = 'btn col2';
										$bb = "<i class='iconfa-unlock'></i>";
										$cc = 'Lock';
									}
									if($row['log_sts'] == '1'){
										$aa = 'btn col3';
										$bb = "<i class='iconfa-lock pad4'></i>";
										$cc = 'Unlock';
									}
									$yrdata1= strtotime($row['termination_date']);
									$enddate = date('d-M, y', $yrdata1);
									
									$diff = abs(strtotime($row['termination_date']) - strtotime($dateTimeee))/86400;
									if($row['termination_date'] < $dateTimeee){ $diff = '0';}
									if($diff <= '7'){
										$colorrrr = 'style="color: red;font-size: 15px;font-weight: bold;"'; 
									}
									else{
										$colorrrr = 'style="font-size: 15px;font-weight: bold;"'; 
									}
									
								if($user_type == 'admin' || $user_type == 'superadmin' || $user_type == 'support' || $user_type == 'accounts' || $user_type == 'billing' || $user_type == 'billing_manager' || $user_type == 'support_manager' || $user_type == 'ets'){
									if($type == 'macclient'){
									echo
										"<tr class='gradeX'>
											<td class='center' rowspan='2'>
												<ul class='tooltipsample' style='margin-bottom: 0px !important;'>
													<li style='font-size: 10px;padding: 0 5px 0 0;'><a href='ClientView?id={$row['c_id']}'>{$row['c_id']}</a><br/>{$row['pw']}<br/>{$enddate}</li>
												</ul>
											</td>
											<td rowspan='2' class='center' $colorrrr>{$diff}</td>
											<td class='center' style='padding: 0;'>\n"; 
											if(in_array(103, $access_arry)){?>
												<a data-placement='top' data-rel='tooltip' style='font-size: 13px;font-weight: bold;width: 100%;border-radius: 0;padding: 10px 0px 10px 0;' href='ClientStatus?id=<?php echo $row['c_id'];?>' data-original-title='<?php echo $dd;?>' class='btn <?php echo $clss;?>' onclick='return checksts()'><?php echo $ee;?></a>
											<?php } else{?>
												<a data-placement='top' data-rel='tooltip' style='font-size: 13px;font-weight: bold;width: 100%;border-radius: 0;padding: 10px 0px 10px 0;' data-original-title='<?php echo $dd;?>' class='btn <?php echo $clss;?>'><?php echo $ee;?></a>
											<?php }
											echo "</td>
										</tr>
										<tr>
											<td class='center' style='padding: 0;'>
												<form action='ClientsRecharge' method='post' target='_blank'><input type='hidden' name='c_id' value='{$row['c_id']}' /><button class='btn' style='font-size: 10px;font-weight: bold;width: 100%;border-radius: 0;padding: 10px 0px 10px 0;color: #337ab7;background: #ffeaf3;'>Recharge</button></form>
											</td>
										</tr>\n";
									}
									else{
									echo
										"<tr class='gradeX'>
											<td class='center'>
												<ul class='tooltipsample' style='margin-bottom: 0px !important;'>
													<li style='font-size: 10px;padding: 0 5px 0 0;'><a href='ClientView?id={$row['c_id']}'>{$row['c_id']}</a><br/>{$row['c_name']}</li>
												</ul>
											</td>
											<td class='center'>
												<ul class='tooltipsample' style='margin-bottom: 0px !important;'>
													<li style='font-size: 10px;padding: 0 5px 0 0;'><a href='tel:{$row['cell']}'>{$row['cell']}</a><br/>{$row['z_name']}</li>
												</ul>
											</td>
											<td class='center'>
												<ul class='tooltipsample' style='margin-bottom: 0px !important;'>\n"; 
											if(in_array(103, $access_arry)){?>
													<li><a data-placement='top' data-rel='tooltip' style='padding: 3px 5px 3px 5px;font-size: 10px;font-weight: bold;border: 1px solid #bbb;' href='ClientStatus?id=<?php echo $row['c_id'];?>' data-original-title='<?php echo $dd;?>' class='btn <?php echo $clss;?>' onclick="return checksts()"><?php echo $ee;?></a></li>
											<?php } else{?>
													<li><a data-placement='top' data-rel='tooltip' style='padding: 3px 5px 3px 5px;font-size: 10px;font-weight: bold;border: 1px solid #bbb;' data-original-title='<?php echo $dd;?>' class='btn <?php echo $clss;?>'><?php echo $ee;?></a></li>
											<?php }
										echo "</ul>
											</td>
										</tr>\n ";
									}}
								if($userr_typ == 'mreseller'){
									echo
										"<tr class='gradeX'>
											<td class='center' rowspan='2'>
												<ul class='tooltipsample' style='margin-bottom: 0px !important;'>
													<li style='font-size: 10px;padding: 0 5px 0 0;'><a href='ClientView?id={$row['c_id']}'>{$row['c_id']}</a><br/>{$row['pw']}<br/>{$enddate}</li>
												</ul>
											</td>
											<td rowspan='2' class='center' $colorrrr>{$diff}</td>
											<td class='center' style='padding: 0;'>
												<a data-placement='top' data-rel='tooltip' style='font-size: 13px;font-weight: bold;width: 100%;border-radius: 0;padding: 10px 0px 10px 0;' href='ClientStatus?id={$row['c_id']}' data-original-title='{$dd}' class='btn {$clss}' onclick='return checksts()'>{$ee}</a>
											</td>
										</tr>
										<tr>
											<td class='center' style='padding: 0;'>
												<form action='ClientsRecharge' method='post' target='_blank'><input type='hidden' name='c_id' value='{$row['c_id']}' /><button class='btn' style='font-size: 10px;font-weight: bold;width: 100%;border-radius: 0;padding: 10px 0px 10px 0;color: #337ab7;background: #ffeaf3;'>Recharge</button></form>
											</td>
										</tr>\n ";
									
								}
								}
							?>
					</tbody>
				</table>
				<?php } ?>
			</div>	
	</div>
				
<?php
}
else{
	include('include/index');
}
include('include/footer.php');
?>
<script type="text/javascript">
    jQuery(document).ready(function(){
        // dynamic table
        jQuery('#dyntable').dataTable({
			"iDisplayLength": 100,
            "sPaginationType": "full_numbers",
            "aaSortingFixed": [[0,'asc']],
            "fnDrawCallback": function(oSettings) {
                jQuery.uniform.update();
            }
        });
    });
</script>
<script language="JavaScript" type="text/javascript">
function checkDelete(){
    return confirm('Delete!!  Are you sure?');
}

function checksts(){
    return confirm('Change connection status!!  Are you sure?');
}

function checkLock(){
    return confirm('Are you sure?  Do u know what are you doing?');
}
</script>

<?php if($mk_id != ''){?>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
 $(document).ready(function() {
 	 $("#responsecontainer1").load("AutoActiveClientsCount.php?ids=<?php echo $mk_id;?>");
   var refreshId = setInterval(function() {
	  $("#responsecontainer1").load('AutoActiveClientsCount.php?ids=<?php echo $mk_id;?>');
   }, 50);
   $.ajaxSetup({ cache: false });
});
</script>
<?php } ?>