<?php
$titel = "Billing";
$Billing = 'active';
include('include/hader.php');
$type = $_GET['id'];
extract($_POST); 

//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'Billing' AND $user_type = '1'");
$access1 = mysql_query("SELECT * FROM module_page WHERE parent_id = '11' AND $user_type = '1'");
if(mysql_num_rows($access) > 0 && mysql_num_rows($access1) > 0){
//---------- Permission -----------

						if($user_type == 'admin' || $user_type == 'superadmin' || $user_type == 'support' || $user_type == 'accounts' || $user_type == 'billing_manager' || $user_type == 'support_manager' || $user_type == 'ets' || $user_type == 'billing'){
								if($type == '' || $type == 'all' && in_array(135, $access_arry)){
									if($user_type == 'billing'){
									$sql3 = mysql_query("SELECT l.c_id, l.c_name, l.payment_deadline, l.b_date, l.address, l.emp_id, l.raw_download, l.breseller, l.mac_user, l.raw_upload, l.youtube_bandwidth, l.total_bandwidth, l.bandwidth_price, l.youtube_price, l.total_price, l.join_date, l.cell, l.p_name, l.p_price, l.per_discount, l.discount, l.amt, IFNULL(t.dic, 0.00) AS dic, l.con_sts, l.con_sts_date, IFNULL(t.pay, 0.00) AS pay, l.note, l.note_auto, (l.amt - (IFNULL(t.pay, 0.00)+IFNULL(t.dic, 0.00))) AS payable FROM
												(SELECT x.amt, x.c_id, x.c_name, x.payment_deadline, x.b_date, x.emp_id, x.address, x.raw_download, x.breseller, x.mac_user, x.raw_upload, x.youtube_bandwidth, x.total_bandwidth, x.bandwidth_price, x.youtube_price, x.total_price, x.join_date, x.discount, x.cell, x.p_name, x.con_sts, x.con_sts_date, x.p_price, x.note, x.note_auto, x.per_discount FROM
													(SELECT b.c_id, c.c_name, c.address, z.emp_id, c.payment_deadline, c.b_date, c.raw_download, c.breseller, c.mac_user, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, c.join_date, c.discount, c.cell, p.p_name, c.con_sts, c.con_sts_date, p.p_price, SUM(b.bill_amount) AS amt, c.note, c.note_auto, SUM(b.discount) AS per_discount
													FROM billing AS b
													LEFT JOIN clients AS c ON c.c_id = b.c_id
													LEFT JOIN package AS p ON p.p_id = c.p_id
													LEFT JOIN zone AS z ON z.z_id = c.z_id
													WHERE b.sts = '0' AND c.sts = '0' AND c.mac_user = '0' GROUP BY b.c_id) AS x
												)l
												LEFT JOIN
												(
													SELECT c_id, SUM(pay_amount) AS pay, SUM(bill_discount) AS dic FROM payment
													GROUP BY c_id
												)t
												ON l.c_id = t.c_id WHERE l.amt - (IFNULL(t.pay, 0.00)+IFNULL(t.dic, 0.00)) <= '0' AND l.emp_id = '$e_id'  AND l.con_sts = 'Active' ORDER BY l.c_id");
												
									$sql = mysql_query("SELECT l.c_id, l.c_name, l.emp_id, l.z_name, l.address, l.raw_download, l.breseller, l.mac_user, l.raw_upload, l.youtube_bandwidth, l.total_bandwidth, l.bandwidth_price, l.youtube_price, l.total_price, l.join_date, l.cell, l.p_name, l.p_price, l.per_discount, l.discount, l.amt, IFNULL(t.dic, 0.00) AS dic, l.con_sts, l.con_sts_date, IFNULL(t.pay, 0.00) AS pay, l.note, l.note_auto, (l.amt - (IFNULL(t.pay, 0.00)+IFNULL(t.dic, 0.00))) AS payable FROM
													(SELECT x.amt, x.c_id, x.emp_id, x.z_name, x.c_name, x.address, x.raw_download, x.breseller, x.mac_user, x.raw_upload, x.youtube_bandwidth, x.total_bandwidth, x.bandwidth_price, x.youtube_price, x.total_price, x.join_date, x.discount, x.cell, x.p_name, x.con_sts, x.con_sts_date, x.p_price, x.note, x.note_auto, x.per_discount FROM
													(SELECT b.c_id, c.c_name, c.address, z.emp_id, z.z_name, c.raw_download, c.breseller, c.mac_user, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, c.join_date, c.discount, c.cell, p.p_name, c.con_sts, c.con_sts_date, p.p_price, SUM(b.bill_amount) AS amt, c.note, c.note_auto, SUM(b.discount) AS per_discount
													FROM billing AS b
													LEFT JOIN clients AS c ON c.c_id = b.c_id
													LEFT JOIN package AS p ON p.p_id = c.p_id
													LEFT JOIN zone AS z ON z.z_id = c.z_id
													WHERE b.sts = '0' AND c.sts = '0' AND c.mac_user = '0' GROUP BY b.c_id) AS x
												)l
												LEFT JOIN
												(
													SELECT c_id, SUM(pay_amount) AS pay, SUM(bill_discount) AS dic FROM payment
													GROUP BY c_id
												)t
												ON l.c_id = t.c_id WHERE l.amt - (IFNULL(t.pay, 0.00)+IFNULL(t.dic, 0.00)) > '0' AND l.emp_id = '$e_id'  ORDER BY l.c_id");
												
									$tot_allbills = mysql_num_rows($sql);
									$tot_allpaid = mysql_num_rows($sql3);
									$tot_allunpaid = $tot_allbills - $tot_allpaid;
									
									
									
									$tit = "<div class='box-header'>
												<div class='hil'> Due Bills : <i style='color: #e3052e'>{$tot_allbills}</i></div> 
											</div>";
									}
									
									else{
										$sql3 = mysql_query("SELECT l.c_id, l.c_name, l.payment_deadline, l.b_date, l.address, l.raw_download, l.breseller, l.mac_user, l.raw_upload, l.youtube_bandwidth, l.total_bandwidth, l.bandwidth_price, l.youtube_price, l.total_price, l.join_date, l.cell, l.p_name, l.p_price, l.per_discount, l.discount, l.amt, IFNULL(t.dic, 0.00) AS dic, l.con_sts, l.con_sts_date, IFNULL(t.pay, 0.00) AS pay, l.note, l.note_auto, (l.amt - (IFNULL(t.pay, 0.00)+IFNULL(t.dic, 0.00))) AS payable FROM
												(SELECT x.amt, x.c_id, x.c_name, x.payment_deadline, x.b_date, x.address, x.raw_download, x.breseller, x.mac_user, x.raw_upload, x.youtube_bandwidth, x.total_bandwidth, x.bandwidth_price, x.youtube_price, x.total_price, x.join_date, x.discount, x.cell, x.p_name, x.con_sts, x.con_sts_date, x.p_price, x.note, x.note_auto, x.per_discount FROM
													(SELECT b.c_id, c.c_name, c.address, c.raw_download, c.payment_deadline, c.b_date, c.breseller, c.mac_user, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, c.join_date, c.discount, c.cell, p.p_name, c.con_sts, c.con_sts_date, p.p_price, SUM(b.bill_amount) AS amt, c.note, c.note_auto, SUM(b.discount) AS per_discount
													FROM billing AS b
													LEFT JOIN clients AS c ON c.c_id = b.c_id
													LEFT JOIN package AS p ON p.p_id = c.p_id
													WHERE b.sts = '0' AND c.sts = '0' AND c.mac_user = '0' GROUP BY b.c_id) AS x
												)l
												LEFT JOIN
												(
													SELECT c_id, SUM(pay_amount) AS pay, SUM(bill_discount) AS dic FROM payment
													GROUP BY c_id
												)t
												ON l.c_id = t.c_id WHERE l.amt - (IFNULL(t.pay, 0.00)+IFNULL(t.dic, 0.00)) <= '0' AND l.con_sts = 'Active' ORDER BY l.c_id");
										
										$sql = mysql_query("SELECT l.c_id, l.c_name, l.z_name, l.emp_id, l.address, l.raw_download, l.breseller, l.mac_user, l.raw_upload, l.youtube_bandwidth, l.total_bandwidth, l.bandwidth_price, l.youtube_price, l.total_price, l.join_date, l.cell, l.p_name, l.p_price, l.per_discount, l.discount, l.amt, IFNULL(t.dic, 0.00) AS dic, l.con_sts, l.con_sts_date, IFNULL(t.pay, 0.00) AS pay, l.note, l.note_auto, (l.amt - (IFNULL(t.pay, 0.00)+IFNULL(t.dic, 0.00))) AS payable FROM
													(SELECT x.amt, x.c_id, x.emp_id, x.z_name, x.c_name, x.address, x.raw_download, x.breseller, x.mac_user, x.raw_upload, x.youtube_bandwidth, x.total_bandwidth, x.bandwidth_price, x.youtube_price, x.total_price, x.join_date, x.discount, x.cell, x.p_name, x.con_sts, x.con_sts_date, x.p_price, x.note, x.note_auto, x.per_discount FROM
													(SELECT b.c_id, c.c_name, c.address, z.emp_id, z.z_name, c.raw_download, c.breseller, c.mac_user, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, c.join_date, c.discount, c.cell, p.p_name, c.con_sts, c.con_sts_date, p.p_price, SUM(b.bill_amount) AS amt, c.note, c.note_auto, SUM(b.discount) AS per_discount
													FROM billing AS b
													LEFT JOIN clients AS c ON c.c_id = b.c_id
													LEFT JOIN package AS p ON p.p_id = c.p_id
													LEFT JOIN zone AS z ON z.z_id = c.z_id
													WHERE b.sts = '0' AND c.sts = '0' AND c.mac_user = '0' GROUP BY b.c_id) AS x
												)l
												LEFT JOIN
												(
													SELECT c_id, SUM(pay_amount) AS pay, SUM(bill_discount) AS dic FROM payment
													GROUP BY c_id
												)t
												ON l.c_id = t.c_id ORDER BY l.c_id");
												
									$tot_allbills = mysql_num_rows($sql);
									$tot_allpaid = mysql_num_rows($sql3);
									$tot_allunpaid = $tot_allbills - $tot_allpaid;
									
									
									
									$tit = "<div class='box-header'>
												<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'>  Total Bill :  <i style='color: #317EAC'>{$tot_allbills}</i></div> 
												<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'>  Paid :  <i style='color: #30ad23'>{$tot_allpaid}</i></div> 
												<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'>  Due Bills : <i style='color: #e3052e'>{$tot_allunpaid}</i></div> 
											</div>";
									}
								}
								
								if($type == 'unpaid' && in_array(137, $access_arry)){
									if($user_type == 'billing'){
									$sql = mysql_query("SELECT l.c_id, l.c_name, l.com_id, l.payment_deadline, l.b_date, l.emp_id, l.z_name, l.address, l.raw_download, l.breseller, l.mac_user, l.raw_upload, l.youtube_bandwidth, l.total_bandwidth, l.bandwidth_price, l.youtube_price, l.total_price, l.join_date, l.cell, l.p_name, l.p_price, l.per_discount, l.discount, l.amt, IFNULL(t.dic, 0.00) AS dic, l.con_sts, l.con_sts_date, IFNULL(t.pay, 0.00) AS pay, l.note, l.note_auto, (l.amt - (IFNULL(t.pay, 0.00)+IFNULL(t.dic, 0.00))) AS payable FROM
													(SELECT x.amt, x.com_id, x.payment_deadline, x.b_date, x.c_id, x.emp_id, x.z_name, x.c_name, x.address, x.raw_download, x.breseller, x.mac_user, x.raw_upload, x.youtube_bandwidth, x.total_bandwidth, x.bandwidth_price, x.youtube_price, x.total_price, x.join_date, x.discount, x.cell, x.p_name, x.con_sts, x.con_sts_date, x.p_price, x.note, x.note_auto, x.per_discount FROM
													(SELECT b.c_id, c.c_name, c.com_id, c.address, c.payment_deadline, c.b_date, z.emp_id, z.z_name, c.raw_download, c.breseller, c.mac_user, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, c.join_date, c.discount, c.cell, p.p_name, c.con_sts, c.con_sts_date, p.p_price, SUM(b.bill_amount) AS amt, c.note, c.note_auto, SUM(b.discount) AS per_discount
													FROM billing AS b
													LEFT JOIN clients AS c ON c.c_id = b.c_id
													LEFT JOIN package AS p ON p.p_id = c.p_id
													LEFT JOIN zone AS z ON z.z_id = c.z_id
													WHERE b.sts = '0' AND c.sts = '0' AND c.mac_user = '0' GROUP BY b.c_id) AS x
												)l
												LEFT JOIN
												(
													SELECT c_id, SUM(pay_amount) AS pay, SUM(bill_discount) AS dic FROM payment
													GROUP BY c_id
												)t
												ON l.c_id = t.c_id WHERE l.amt - (IFNULL(t.pay, 0.00)+IFNULL(t.dic, 0.00)) > '1' AND l.emp_id = '$e_id'  ORDER BY l.com_id asc");
									}
									else{
									$sql = mysql_query("SELECT l.c_id, l.com_id, l.z_name, l.c_name, l.payment_deadline, l.b_date, l.address, l.raw_download, l.breseller, l.mac_user, l.raw_upload, l.youtube_bandwidth, l.total_bandwidth, l.bandwidth_price, l.youtube_price, l.total_price, l.join_date, l.cell, l.p_name, l.p_price, l.per_discount, l.discount, l.amt, IFNULL(t.dic, 0.00) AS dic, l.con_sts, l.con_sts_date, IFNULL(t.pay, 0.00) AS pay, l.note, l.note_auto, (l.amt - (IFNULL(t.pay, 0.00)+IFNULL(t.dic, 0.00))) AS payable FROM
													(SELECT x.com_id, x.z_name, x.amt, x.payment_deadline, x.b_date, x.c_id, x.c_name, x.address, x.raw_download, x.breseller, x.mac_user, x.raw_upload, x.youtube_bandwidth, x.total_bandwidth, x.bandwidth_price, x.youtube_price, x.total_price, x.join_date, x.discount, x.cell, x.p_name, x.con_sts, x.con_sts_date, x.p_price, x.note, x.note_auto, x.per_discount FROM
													(SELECT b.c_id, c.com_id, c.c_name, z.z_name, c.address, c.payment_deadline, c.b_date, c.raw_download, c.breseller, c.mac_user, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, c.join_date, c.discount, c.cell, p.p_name, c.con_sts, c.con_sts_date, p.p_price, SUM(b.bill_amount) AS amt, c.note, c.note_auto, SUM(b.discount) AS per_discount
													FROM billing AS b
													LEFT JOIN clients AS c ON c.c_id = b.c_id
													LEFT JOIN package AS p ON p.p_id = c.p_id
													LEFT JOIN zone AS z ON z.z_id = c.z_id
													WHERE b.sts = '0' AND c.sts = '0' AND c.mac_user = '0' GROUP BY b.c_id) AS x
												)l
												LEFT JOIN
												(
													SELECT c_id, SUM(pay_amount) AS pay, SUM(bill_discount) AS dic FROM payment
													GROUP BY c_id
												)t
												ON l.c_id = t.c_id WHERE l.amt - (IFNULL(t.pay, 0.00)+IFNULL(t.dic, 0.00)) > '1' ORDER BY l.com_id asc");
									
									}
									$tot_allbills = mysql_num_rows($sql);
									
									$tit = "<div class='box-header'>
												<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Due Bills: <i style='color: #e3052e'>{$tot_allbills}</i></div> 
											</div>";
									
								}
								
								if($type == 'paid' && in_array(136, $access_arry)){
									if($user_type == 'billing'){
									$sql = mysql_query("SELECT l.c_id, l.com_id, l.c_name, l.payment_deadline, l.b_date, l.emp_id, l.z_name, l.address, l.raw_download, l.breseller, l.mac_user, l.raw_upload, l.youtube_bandwidth, l.total_bandwidth, l.bandwidth_price, l.youtube_price, l.total_price, l.join_date, l.cell, l.p_name, l.p_price, l.per_discount, l.discount, l.amt, IFNULL(t.dic, 0.00) AS dic, l.con_sts, l.con_sts_date, IFNULL(t.pay, 0.00) AS pay, l.note, l.note_auto, (l.amt - (IFNULL(t.pay, 0.00)+IFNULL(t.dic, 0.00))) AS payable FROM
													(SELECT x.com_id, x.amt, x.payment_deadline, x.b_date, x.emp_id, x.z_name, x.c_id, x.c_name, x.address, x.raw_download, x.breseller, x.mac_user, x.raw_upload, x.youtube_bandwidth, x.total_bandwidth, x.bandwidth_price, x.youtube_price, x.total_price, x.join_date, x.discount, x.cell, x.p_name, x.con_sts, x.con_sts_date, x.p_price, x.note, x.note_auto, x.per_discount FROM
													(SELECT b.c_id, c.com_id, c.c_name, z.emp_id, c.payment_deadline, c.b_date, z.z_name, c.address, c.raw_download, c.breseller, c.mac_user, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, c.join_date, c.discount, c.cell, p.p_name, c.con_sts, c.con_sts_date, p.p_price, SUM(b.bill_amount) AS amt, c.note, c.note_auto, SUM(b.discount) AS per_discount
													FROM billing AS b
													LEFT JOIN clients AS c ON c.c_id = b.c_id
													LEFT JOIN package AS p ON p.p_id = c.p_id
													LEFT JOIN zone AS z ON z.z_id = c.z_id
													
													WHERE b.sts = '0' AND c.sts = '0' AND c.mac_user = '0' GROUP BY b.c_id) AS x
												)l
												LEFT JOIN
												(
													SELECT c_id, SUM(pay_amount) AS pay, SUM(bill_discount) AS dic FROM payment
													GROUP BY c_id
												)t
												ON l.c_id = t.c_id WHERE l.amt - (IFNULL(t.pay, 0.00)+IFNULL(t.dic, 0.00)) <= '0' AND l.emp_id = '$e_id' AND l.con_sts = 'Active' ORDER BY l.com_id asc");
									
									}
									else{
										$sql = mysql_query("SELECT l.c_id, l.com_id, l.c_name, l.z_name, l.payment_deadline, l.b_date, l.address, l.raw_download, l.breseller, l.mac_user, l.raw_upload, l.youtube_bandwidth, l.total_bandwidth, l.bandwidth_price, l.youtube_price, l.total_price, l.join_date, l.cell, l.p_name, l.p_price, l.per_discount, l.discount, l.amt, IFNULL(t.dic, 0.00) AS dic, l.con_sts, l.con_sts_date, IFNULL(t.pay, 0.00) AS pay, l.note, l.note_auto, (l.amt - (IFNULL(t.pay, 0.00)+IFNULL(t.dic, 0.00))) AS payable FROM
													(SELECT x.com_id, x.z_name, x.amt, x.payment_deadline, x.b_date, x.c_id, x.c_name, x.address, x.raw_download, x.breseller, x.mac_user, x.raw_upload, x.youtube_bandwidth, x.total_bandwidth, x.bandwidth_price, x.youtube_price, x.total_price, x.join_date, x.discount, x.cell, x.p_name, x.con_sts, x.con_sts_date, x.p_price, x.note, x.note_auto, x.per_discount FROM
													(SELECT b.c_id, c.com_id, z.z_name, c.c_name, c.address, c.payment_deadline, c.b_date, c.raw_download, c.breseller, c.mac_user, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, c.join_date, c.discount, c.cell, p.p_name, c.con_sts, c.con_sts_date, p.p_price, SUM(b.bill_amount) AS amt, c.note, c.note_auto, SUM(b.discount) AS per_discount
													FROM billing AS b
													LEFT JOIN clients AS c ON c.c_id = b.c_id
													LEFT JOIN package AS p ON p.p_id = c.p_id
													LEFT JOIN zone AS z ON z.z_id = c.z_id
													
													WHERE b.sts = '0' AND c.sts = '0' AND c.mac_user = '0' GROUP BY b.c_id) AS x
												)l
												LEFT JOIN
												(
													SELECT c_id, SUM(pay_amount) AS pay, SUM(bill_discount) AS dic FROM payment
													GROUP BY c_id
												)t
												ON l.c_id = t.c_id WHERE l.amt - (IFNULL(t.pay, 0.00)+IFNULL(t.dic, 0.00)) <= '0' AND l.con_sts = 'Active' ORDER BY l.com_id asc");
									
									}
									$query_date = date("Y-m-d");

									$f_date = date('Y-m-01', strtotime($query_date));
									$t_date = date('Y-m-t', strtotime($query_date));
									
									
												
									$sqlkk = mysql_query("SELECT p.c_id, SUM(p.pay_amount) AS paym, SUM(p.bill_discount) AS bill_discc FROM payment AS p 
															WHERE p.pay_date BETWEEN '$f_date' AND '$t_date'");
									
									$rowkk = mysql_fetch_array($sqlkk);					
									$paym = $rowkk['paym'];
									$bill_discc = $rowkk['bill_discc'];
									$totalpayment = $paym + $bill_discc;
												
									$tot_allbills = mysql_num_rows($sql);
									$tit = "<div class='box-header'>
												<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Paid :  <i style='color: #30ad23'>{$tot_allbills}</i></div> 

											</div>";
												
								}
							
							}
if($userr_typ == 'mreseller'){
if($type == 'all' || $type == ''){
									$sql = mysql_query("SELECT l.c_id, l.z_id, l.com_id, l.pw, l.c_name, l.address, l.join_date, l.cell, l.p_name, l.p_price, l.per_discount, l.discount, l.amt, t.dic, l.con_sts, l.con_sts_date, t.pay, l.note, (l.amt - (IFNULL(t.pay, 0.00)+IFNULL(t.dic, 0.00))) AS payable FROM
														(
															SELECT b.c_id, c.com_id, w.pw, c.z_id, c.c_name, c.address, c.join_date, c.discount, c.cell, p.p_name, c.con_sts, c.con_sts_date, p.p_price_reseller AS p_price, SUM(b.bill_amount) AS amt, c.note, SUM(b.discount) AS per_discount
															FROM billing_mac_client AS b
															LEFT JOIN clients AS c ON c.c_id = b.c_id
															LEFT JOIN package AS p ON p.p_id = c.p_id
															LEFT JOIN login AS w ON w.e_id = c.c_id
															LEFT JOIN zone AS z ON z.z_id = c.z_id
															WHERE b.sts = 0 AND c.sts = '0' AND c.z_id = '$macz_id' AND c.mac_user = '1' GROUP BY b.c_id
														)l
														LEFT JOIN
														(
															SELECT c_id, SUM(pay_amount) AS pay, SUM(bill_discount) AS dic FROM payment_mac_client
															GROUP BY c_id
														)t
														ON l.c_id = t.c_id ORDER BY l.com_id");
									
									$sql3 = mysql_query("SELECT x.amt, x.c_id, x.c_name, x.pw, x.address, x.raw_download, x.breseller, x.mac_user, x.raw_upload, x.youtube_bandwidth, x.total_bandwidth, x.bandwidth_price, x.youtube_price, x.total_price, x.join_date, x.discount, x.cell, x.p_name, x.con_sts, x.con_sts_date, x.p_price, x.note, x.note_auto, x.per_discount, (x.amt - (IFNULL(t.pay, 0.00)+IFNULL(t.dic, 0.00))) AS payable FROM
															(SELECT b.c_id, c.c_name, w.pw, c.address, c.raw_download, c.breseller, c.mac_user, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, c.join_date, c.discount, c.cell, p.p_name, c.con_sts, c.con_sts_date, p.p_price, SUM(b.bill_amount) AS amt, c.note, c.note_auto, SUM(b.discount) AS per_discount
															FROM billing_mac_client AS b
															LEFT JOIN clients AS c ON c.c_id = b.c_id
															LEFT JOIN package AS p ON p.p_id = c.p_id
															LEFT JOIN login AS w ON w.e_id = c.c_id
															WHERE b.sts = '0' AND c.sts = '0' AND c.mac_user = '1' AND c.z_id = '$macz_id' GROUP BY b.c_id) AS x
														LEFT JOIN
														(
															SELECT c_id, SUM(pay_amount) AS pay, SUM(bill_discount) AS dic FROM payment_mac_client
															GROUP BY c_id
														)t
														ON x.c_id = t.c_id WHERE x.amt - (IFNULL(t.pay, 0.00)+IFNULL(t.dic, 0.00)) <= '1' ORDER BY x.c_id");
														
											$tot_allbills = mysql_num_rows($sql);
											$tot_allpaid = mysql_num_rows($sql3);
											$tot_allunpaid = $tot_allbills - $tot_allpaid;
											
									$tit = "<div class='box-header'>
												<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Total :  <i style='color: #317EAC'>{$tot_allbills}</i></div> 
												<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Paid :  <i style='color: #30ad23'>{$tot_allpaid}</i></div> 
												<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Due : <i style='color: #e3052e'>{$tot_allunpaid}</i></div> 
											</div>";
								}	
								if($type == 'unpaid'){
										$sql = mysql_query("SELECT l.c_id, l.com_id, l.pw, l.z_name, l.c_name, l.payment_deadline, l.b_date, l.address, l.raw_download, l.breseller, l.mac_user, l.raw_upload, l.youtube_bandwidth, l.total_bandwidth, l.bandwidth_price, l.youtube_price, l.total_price, l.join_date, l.cell, l.p_name, l.p_price, l.per_discount, l.discount, l.amt, IFNULL(t.dic, 0.00) AS dic, l.con_sts, l.con_sts_date, IFNULL(t.pay, 0.00) AS pay, l.note, l.note_auto, (l.amt - (IFNULL(t.pay, 0.00)+IFNULL(t.dic, 0.00))) AS payable FROM
													(SELECT x.com_id, x.z_name, x.amt, x.pw, x.payment_deadline, x.b_date, x.c_id, x.c_name, x.address, x.raw_download, x.breseller, x.mac_user, x.raw_upload, x.youtube_bandwidth, x.total_bandwidth, x.bandwidth_price, x.youtube_price, x.total_price, x.join_date, x.discount, x.cell, x.p_name, x.con_sts, x.con_sts_date, x.p_price, x.note, x.note_auto, x.per_discount FROM
													(SELECT b.c_id, c.com_id, w.pw, c.c_name, z.z_name, c.address, c.payment_deadline, c.b_date, c.raw_download, c.breseller, c.mac_user, c.raw_upload, c.youtube_bandwidth, c.total_bandwidth, c.bandwidth_price, c.youtube_price, c.total_price, c.join_date, c.discount, c.cell, p.p_name, c.con_sts, c.con_sts_date, p.p_price, SUM(b.bill_amount) AS amt, c.note, c.note_auto, SUM(b.discount) AS per_discount
													FROM billing_mac_client AS b
													LEFT JOIN clients AS c ON c.c_id = b.c_id
													LEFT JOIN package AS p ON p.p_id = c.p_id
													LEFT JOIN login AS w ON w.e_id = c.c_id
													LEFT JOIN zone AS z ON z.z_id = c.z_id
													WHERE b.sts = '0' AND c.sts = '0' AND c.mac_user = '1' AND c.z_id = '$macz_id' GROUP BY b.c_id) AS x
												)l
												LEFT JOIN
												(
													SELECT c_id, SUM(pay_amount) AS pay, SUM(bill_discount) AS dic FROM payment_mac_client
													GROUP BY c_id
												)t
												ON l.c_id = t.c_id WHERE l.amt - (IFNULL(t.pay, 0.00)+IFNULL(t.dic, 0.00)) > '1' ORDER BY l.com_id asc");
										
									
									$tot_allbills = mysql_num_rows($sql);
									
									$tit = "<div class='box-header'>
												<div class='hil' style='font-size: 10px;padding: 0 5px 0 0;'> Due Bills: <i style='color: #e3052e'>{$tot_allbills}</i></div> 
											</div>";
								}
	
	
$queryrgg = mysql_query("SELECT c_id, c_name, cell, address FROM clients WHERE sts = '0' AND mac_user = '1' AND z_id = '$macz_id' ORDER BY c_name");
}
else{
$queryrgg = mysql_query("SELECT c_id, c_name, cell, address FROM clients WHERE sts = '0' AND mac_user = '0' ORDER BY c_name");
}


?>
	<div class="box box-primary">
		<?php if($sts == 'paid') {?>
		<div class="alert alert-success" style="padding: 5px 0px 5px 10px;margin: 3px 5px 0px 5px;">
			<button data-dismiss="alert" style="margin-right: 10px;" class="close" type="button">&times;</button>
			<strong style="font-size: 13px;font-weight: normal;padding: 0 0 0 5px;">Payment Success!!</strong>.
		</div>
		<?php }?>
	<div class="box-header">
<?php if($user_type == 'admin' || $user_type == 'superadmin' || $user_type == 'support' || $user_type == 'accounts' || $user_type == 'billing_manager' || $user_type == 'support_manager' || $user_type == 'ets' || $user_type == 'billing' || $user_type == 'mreseller'){ ?>
		<?php if(in_array(135, $access_arry)){?>
			<a class="btn btn-neveblue" style="float: right;margin-right: 2px;font-size: 9px;" href="Billing?id=all">All Bills </a>
		<?php } if(in_array(136, $access_arry)){?>
			<a class="btn btn-green" style="float: right;margin-right: 2px;font-size: 9px;" href="Billing?id=paid">Paid</a>
		<?php } if(in_array(137, $access_arry)){?>
			<a class="btn btn-red" style="float: right;margin-right: 2px;font-size: 9px;" href="Billing?id=unpaid">Dues </a>
<?php }} ?>
			<h6 style="float: left;"><?php echo $tit; ?></h6> 
		</div><br />
			<div class="box-body">
			<?php if($sts == 'add'){?>
			<div class="alert alert-success" style="padding: 5px;font-size: 12px;font-weight: bold;">
				<button data-dismiss="alert" class="close" type="button">&times;</button>
				<strong>Success!!</strong> Payment Successfully Added.
			</div><!--alert-->
		<?php } if(in_array(130, $access_arry)){?>
		<form id="form2" class="stdform" method="POST" action="BillPaymentView">
				<div class="modal-body" style="padding: 0;">
					<p>
						<select data-placeholder="Search Client" name="id" class="chzn-select"  style="width:100%;" required="" onchange='this.form.submit()'>
							<option value=""></option>
							<?php while ($row2gg=mysql_fetch_array($queryrgg)) { ?>
							<option value="<?php echo $row2gg['c_id']?>"><?php echo $row2gg['c_name']; ?> | <?php echo $row2gg['c_id']; ?> | <?php echo $row2gg['cell']; ?></option>
							<?php } ?>
						</select>
					</p>
				</div>
		</form>
		<?php } ?>
				<table id="dyntable" class="table table-bordered responsive">
                    <colgroup>
						<col class="con0" />
                        <col class="con1" />
                        <col class="con0" />
                    </colgroup>
                    <thead>
                        <tr  class="newThead">
                            <th class="head0" style="font-size: 10px;padding: 5px;text-align: center;">Client ID</th>
							<th class="head1" style="font-size: 10px;padding: 5px;text-align: center;">Mobile</th>
							<th class="head0" style="font-size: 10px;padding: 5px;text-align: center;">Due</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
								while( $row = mysql_fetch_assoc($sql) )
								{
									$payable = number_format($row['payable'], 2);
									
									if($payable > 0){
										$color = 'style="color:red; font-weight: bold;font-size: 10px;"';					
									} 
									if($payable < 0){
										$color = 'style="color:blue; font-weight: bold;font-size: 10px;"';					
									}
									if($payable == 0){
										$color = 'style="color:green; font-weight: bold;font-size: 10px;"';					
									}
									
									$sts_date = $row['update_date'];
									if($sts_date=='')
									{
										$sts_date = $row['join_date'];
									}
									if ($row['con_sts']=='Inactive')
									{
										$colo = 'style="color:red; width: 80px;font-size: 10px;width: 100%;"';	
									}
									else
									{
										$colo = 'style="color:green; width: 80px;font-size: 10px;width: 100%;"';
									}
						if($user_type == 'admin' || $user_type == 'superadmin' || $user_type == 'support' || $user_type == 'accounts' || $user_type == 'billing_manager' || $user_type == 'support_manager' || $user_type == 'ets' || $user_type == 'billing'){
									echo
										"<tr class='gradeX'>
											<td class='center'>
												<ul class='tooltipsample' style='margin-bottom: 0px !important;'>
													<li style='font-size: 10px;'><a>{$row['c_id']}</a><br/>{$row['c_name']}</li>
												</ul>
											</td>
											<td class='center'>
												<ul class='tooltipsample' style='margin-bottom: 0px !important;'>
													<li style='font-size: 10px;'><a href='tel:{$row['cell']}'>{$row['cell']}</a><br/>{$row['z_name']}</li>
												</ul>
											</td>\n";
								}
								if($user_type == 'mreseller'){
									echo
										"<tr class='gradeX'>
											<td class='center'>
												<ul class='tooltipsample' style='margin-bottom: 0px !important;'>
													<li style='font-size: 10px;'><a>{$row['c_id']}</a><br/>{$row['c_name']}</li>
												</ul>
											</td>
											<td class='center'>
												<ul class='tooltipsample' style='margin-bottom: 0px !important;'>
													<li style='font-size: 10px;'><a href='tel:{$row['cell']}'>{$row['cell']}</a><br/>{$row['z_name']}</li>
												</ul>
											</td>\n";
								}?>
										<td class='center' <?php echo $color;?>>
												<ul class='tooltipsample'>
													<li><?php echo $payable;?></li><br/>
												<?php if(in_array(128, $access_arry)){?>
													<li><form action='PaymentAdd' method='post' target='_blank'><input type='hidden' name='id' value='<?php echo $row['c_id'];?>'/><button class='btn col1' style='padding: 3px 5px 3px 5px;font-size: 10px;font-weight: bold;border: 1px solid #bbb;'><i class='iconfa-money'></i></button></form></a></li>
												<?php } if(in_array(129, $access_arry)){?>
													<li><form action='PaymentOnlineAdd' method='post' target='_blank'><input type='hidden' name='id' value='<?php echo $row['c_id'];?>'/><button class='btn col2' style='padding: 3px 5px 3px 5px;font-size: 10px;font-weight: bold;border: 1px solid #bbb;'><i class='iconfa-shopping-cart'></i></button></form></a></li>
												<?php } if(in_array(104, $access_arry)){?>
													<li><form action='ClientView' method='post' target='_blank'><input type='hidden' name='ids' value='<?php echo $row['c_id'];?>'/><button class='btn col4' style='padding: 3px 5px 3px 5px;font-size: 10px;font-weight: bold;border: 1px solid #bbb;'><i class='iconfa-eye-open'></i></button></form></a></li>
												<?php }	?>
												</ul>
											</td>
										</tr>
								
								<?php }	?>
					</tbody>
				</table>
			
			</div>	
	</div>

<!-- -------------------------------------------------------------Entry Data View------------------------------------------------------------ -->			
				
<?php
}
else{
	include('include/index');
}
include('include/footer.php');
?>