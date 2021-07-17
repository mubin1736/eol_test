<?php 
//session_cache_expire( 20 );
session_start(); // NEVER FORGET TO START THE SESSION!!!

    //Check whether the session variable SESS_MEMBER_ID is present or not
	if($_SESSION['SESS_USER_TYPE'] == '') {
		header("location: index");
		exit();
	}
include("../web/conn/connection.php");
include('../web/company_info.php');
include("mk_api.php");
include("Function.php");
ini_alter('date.timezone','Asia/Almaty');

mysql_query("SET CHARACTER SET utf8");
mysql_query("SET SESSION collation_connection ='utf8_general_ci'");

$e_id = $_SESSION['SESS_EMP_ID'];

$userr_typ = $_SESSION['SESS_USER_TYPE'];

if($userr_typ == 'mreseller') {
$sss1 = mysql_query("SELECT z_id, z_name, e_id FROM zone WHERE e_id ='$e_id'");
$sssw1 = mysql_fetch_assoc($sss1);

$macz_id = $sssw1['z_id'];

$sss1m = mysql_query("SELECT reseller_logo, billing_type FROM emp_info WHERE e_id ='$e_id'");
$sssw1m = mysql_fetch_assoc($sss1m);

$billing_typee = $sssw1m['billing_type'];

if($sssw1m['reseller_logo'] != 'emp_images/'){
$logo = $weblink.$sssw1m['reseller_logo'];
}
else{
$logo = $weblink.'images/logo.png';
}}
else{
$logo = $weblink.'images/logo.png';
}

$s1 = mysql_query('SELECT * FROM app_config');
$sw1 = mysql_fetch_assoc($s1);

$CompanyName = $sw1['name'];
$CompanyEmail = $sw1['email'];
$CompanyAddress = $sw1['address'];
$CompanyPostalCode = $sw1['postal_code'];
$CompanyFax = $sw1['fax'];
$CompanyPhone = $sw1['phone'];
$CompanyWebsite = $sw1['website'];
$CompanyCurrency = $sw1['currency'];
$CompanyLogo = $sw1['logo'];

$acce_arry = mysql_fetch_assoc(mysql_query("SELECT GROUP_CONCAT(id SEPARATOR ',') AS page_access FROM module_page WHERE $userr_typ = '1'"));
$access_arry = explode(',',$acce_arry['page_access']);

$PageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);  

//$userr_typ = $_SESSION['SESS_userr_typ'];
date_default_timezone_set('Etc/GMT-6');
$dateTime = date('Y-m-d', time());
$y_dateTime = date('Y-m-d',strtotime("-1 days"));

if($userr_typ == 'client'){
$ids = $_SESSION['SESS_EMP_ID'];
//$_SESSION['SESS_EMP_ID'] = $member['e_id'];
$result = mysql_query("SELECT c.c_id, c.c_name, c.mk_id, l.log_sts, c.cell, c.address, c.join_date, c.con_type, c.con_sts, c.discount, c.extra_bill, p.p_name, p.p_price, l.pw, c.mac_user, c.termination_date FROM clients AS c
						LEFT JOIN package AS p
						ON p.p_id = c.p_id 
						LEFT JOIN login AS l 
						ON l.e_id = c.c_id 
						WHERE c_id = '$ids'");
$row = mysql_fetch_array($result);	

$mk_id = $row['mk_id'];

if($row['mac_user'] == '0'){
$sqlggg = mysql_query("SELECT a.bill_date AS date, a.p_name, a.p_price, a.p_discount, a.bill_amount, a.discount, a.payment, a.moneyreceiptno, a.pay_mode FROM
					(SELECT b.c_id, b.bill_date, p.p_name, p.p_price, b.bill_amount, b.discount AS p_discount, '' AS Discount, '' AS Payment, '' AS moneyreceiptno, '' AS pay_mode
										FROM billing AS b
										LEFT JOIN package AS p ON p.p_id = b.p_id
										LEFT JOIN clients AS c ON c.c_id = b.c_id
										WHERE b.bill_amount != '0' AND b.c_id = '$ids'
						UNION
							SELECT c_id, pay_date,'', '', '', '', SUM(bill_discount) AS bill_discount, SUM(pay_amount) AS pay_amount, moneyreceiptno, pay_mode FROM payment
							WHERE c_id = '$ids' GROUP BY pay_date
					) AS a
					ORDER BY a.bill_date");
}
else{
$sqlggg = mysql_query("SELECT a.bill_date AS date, a.p_name, a.p_price, a.p_discount, a.bill_amount, a.discount, a.payment, a.moneyreceiptno, a.pay_mode FROM
					(SELECT b.c_id, b.bill_date, p.p_name, p.p_price_reseller AS p_price, b.bill_amount, b.discount AS p_discount, '' AS Discount, '' AS Payment, '' AS moneyreceiptno, '' AS pay_mode
										FROM billing_mac_client AS b
										LEFT JOIN package AS p ON p.p_id = b.p_id
										LEFT JOIN clients AS c ON c.c_id = b.c_id
										WHERE b.bill_amount != '0' AND b.c_id = '$ids'
						UNION
							SELECT c_id, pay_date,'', '', '', '', SUM(bill_discount) AS bill_discount, SUM(pay_amount) AS pay_amount, moneyreceiptno, pay_mode FROM payment_mac_client
							WHERE c_id = '$ids' GROUP BY pay_date
					) AS a
					ORDER BY a.bill_date");
}
$sql360 = mysql_query("SELECT s.c_id, s.bank, b.type, s.amount, s.pay_date, s.bill_dsc, e.e_name FROM bill_signup AS s
					LEFT JOIN bills_type AS b ON b.bill_type = s.bill_type
					LEFT JOIN emp_info AS e ON e.e_id = s.ent_by
					WHERE c_id = '$ids' ORDER BY s.pay_date DESC");
					
$sql36 = mysql_query("SELECT p.id, p.c_id, a.p_name AS old_package, a.p_price AS old_price, q.p_name AS nw_package, q.p_price AS nw_price, DATE_FORMAT(p.up_date, '%D %M %Y') AS up_date FROM package_change AS p
					LEFT JOIN package AS a
					ON a.p_id = p.c_package
					LEFT JOIN package AS q
					ON q.p_id = p.new_package
					WHERE c_id = '$ids' ORDER BY p.id DESC");

$sql35 = mysql_query("SELECT m.id, m.ticket_no, m.c_id, d.dept_name, m.sub, m.massage, DATE_FORMAT(m.entry_date_time, '%D %M %Y %h:%i%p') AS entry_date_time, m.ticket_sts, DATE_FORMAT(m.close_date_time, '%D %M %Y %h:%i%p') AS close_date_time, e.e_name, m.sts FROM complain_master AS m 
					LEFT JOIN department_info AS d
					ON d.dept_id = m.dept_id
					LEFT JOIN emp_info AS e
					ON e.e_id = m.close_by

					WHERE c_id = '$ids' ORDER BY m.ticket_no DESC");

$sql34 = mysql_query("SELECT s.id, s.c_id, c.c_name, s.con_sts, s.update_date, s.update_time, s.update_date_time, s.update_by, e.e_name AS updateby FROM con_sts_log AS s
					LEFT JOIN clients AS c
					ON c.c_id = s.c_id
					LEFT JOIN emp_info AS e ON e.e_id = s.update_by
					WHERE s.c_id = '$ids' ORDER BY s.id DESC");
					

$sql1 = mysql_query("SELECT pay_date, pay_amount, bill_discount FROM payment WHERE c_id = '$ids' ORDER BY pay_date");

$sql2 = mysql_query("SELECT l.amt, t.dic, t.pay FROM
					(
					SELECT c_id, SUM(bill_amount) AS amt FROM billing WHERE c_id = '$ids'

					)l
					LEFT JOIN
					(
					SELECT c_id, SUM(pay_amount) AS pay, SUM(bill_discount) AS dic FROM payment WHERE c_id = '$ids'
					)t
					ON l.c_id = t.c_id");
$rows = mysql_fetch_array($sql2);
$Dew = 	$rows['amt'] - ($rows['pay'] + $rows['dic']);				
if($Dew > 0){
	$color = 'style="color:red;text-align: right;padding-right: 20px;"';					
	$color1 = 'color:red;';
	$dueornot = 'TOTAL BALANCE (DUE)';
} else{
	$color = 'style="color:#555555;text-align: right;padding-right: 20px;"';
	$color1 = 'color:#555555;';
	$dueornot = 'TOTAL BALANCE';
}

if($row['con_sts'] == 'Active'){
	$clss = 'col2';
	$dd = 'Inactive';
	$ee = "<i class='iconfa-play'></i>";
}
if($row['con_sts'] == 'Inactive'){
	$clss = 'col3';
	$dd = 'Active';
	$ee = "<i class='iconfa-pause'></i>";
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

$sqlq = mysql_query("SELECT id, Name, ServerIP, Username, Pass, Port, e_Md, secret_h, add_date_time, note FROM mk_con WHERE id = '$mk_id'");
$row2 = mysql_fetch_assoc($sqlq);

$passs = openssl_decrypt($row2['Pass'], $row2['e_Md'], $row2['secret_h']);
$interid = 'pppoe-'.$ids;

$API = new routeros_api();
$API->debug = false;
if ($API->connect($row2['ServerIP'], $row2['Username'], $passs, $row2['Port'])) {
	$API->write('/ppp/active/print', false);
		$API->write('?name='.$ids);
		$res=$API->read(true);

		$ppp_name = $res[0]['name'];
		$ppp_mac = $res[0]['caller-id'];
		$ppp_ip = $res[0]['address'];
		$ppp_uptime = $res[0]['uptime'];
		
		$API->write('/ppp/secret/print', false);
		$API->write('?name='.$ids);
		$ress=$API->read(true);
		
		$ppp_lastloggedout = $ress[0]['last-logged-out'];
		
		$API->write('/interface/print', false);
		$API->write('from=<pppoe-'.$ids.'> stats-detail');
		$ressi=$API->read(true);
		
		$int_name = $ressi[0]['name'];
		$int_rx = $ressi[0]['rx-byte'];
		$int_tx = $ressi[0]['tx-byte'];
		
		$download_speed = $int_tx;
		$upload_speed = $int_rx;
	}
else{
	echo 'Selected Network are not Connected';
}

if($ppp_mac != ''){
		$ppp_mac_replace = str_replace(":","-",$ppp_mac);
		$ppp_mac_replace_8 = substr($ppp_mac_replace, 0, 8);
		
	$macsearch = mysql_query("SELECT mac, info FROM mac_device WHERE mac = '$ppp_mac_replace_8'");
	$macsearchaa = mysql_fetch_assoc($macsearch);
	$response = $macsearchaa['info'];
}
else{
	$response = '';
}

$queryfdgh = "DELETE FROM realtime_speed WHERE c_id = '$ids'";
if(!mysql_query($queryfdgh)){die('Error: ' . mysql_error());}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8" />
<title><?php echo $comp_name; ?></title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-fileupload.min.css" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-timepicker.min.css" type="text/css" />
<link rel="stylesheet" href="css/prettify.css" type="text/css" />
<link rel="icon" type="images/png" href="images/favicon.png"/>

<link rel="stylesheet" href="css/responsive-tables.css">
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.resize.min.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
</head>

<body>
<nav class="navbar navbar-inverse" style="background-color: white !important;">
  <div class="container-fluid">
    <div class="navbar-header">
            <a href="Welcome"><img src="<?php echo $logo;?>" alt="<?php echo $comp_name; ?>" height="40px" width="120px" style="margin-top: 5px;" class="marggg"/></a> 
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav" >
	  <li><a href="Welcome">Home</a></li><!--  class="active" --->
       <?php
						$sqlq = mysql_query("SELECT `position`, `module_name`, `desc`, `icon` FROM module WHERE $userr_typ = '1' AND m = '1' order by position");
						while( $rowaa = mysql_fetch_assoc($sqlq) )
						{ 
									echo "<li class='${$rowaa['module_name']}'>
											<a href='{$rowaa['module_name']}'>
												<span class='title'>{$rowaa['desc']}</span>
												<span class='selected'></span>
											</a>
										</li>";
						}
						?>
		</ul>	
		<ul class="nav navbar-nav navbar-right">
		 <?php if($userr_typ == 'mreseller') { ?>
			<li><a style="font-weight: bold;<?php echo $color; ?>"><b>Balance: <?php echo number_format($aaaa,2);?>৳</b></a></li>
		<?php } ?>
			<li><a href="#"><b><?php echo $_SESSION['SESS_FIRST_NAME']; ?></b></a></li>
			<li><a href="index" style="color:#333;">[Logout]</a></li>
		</ul>
    </div>
  </div>
</nav>
<div class="maincontent">
            <div class="maincontentinner">
			<div class="row-fluid">
                 <div class="pagetitle" style="padding-top: 3px;">
						<div style="padding-left: 3px;padding-right: 3px; width: 100%;">
							<table class="table" style="width: 100%;font-weight: bold;background: white;font-family: 'RobotoRegular', 'Helvetica Neue', Helvetica, sans-serif;">
								<tr>
									<td style="width: 100%;border-radius: 0;font-size: 10px;font-weight: bold;text-align: left;"><span style="font-size: 12px;color: #337ab7;"><?php echo $dueornot;?></span><br/>BDT. <a style='font-size: 15px;<?php echo $color1;?>'><?php echo number_format($Dew,2); ?></a></td>
									<td class="">
										<a href="#" class="btn btn-neveblue" style="border: 3px solid;border-radius: 10px;padding: 3px 7px 3px 7px;">
											<span style="text-align: center;">View</span>
										</a>
									</td>
								</tr>
								<tr>
									<td style="width: 100%;border-radius: 0;font-size: 10px;font-weight: bold;text-align: left;">Monthly Bill<br/><a style='padding-top: 10px;font-size: 10px;color: #555555;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;'><?php echo ($row['p_price'] + $row['extra_bill']) - $row['discount'];?>৳</a></td>
									<td><a class="btn btn-danger">History</a></td>
								</tr>
							</table>
							<?php if($ppp_mac != ''){?>
								<div id='Client_graph'></div><br>
							<?php }?>
						</div><!--col-md-6-->
                        <div class="tabbedwidget tab-primary">
                            <ul>
                                <li><a href="#tabs-1"><span class="iconfa-user"></span></a></li>
                                <li><a href="#tabs-2"><span class="iconfa-star"></span></a></li>
                                <li><a href="#tabs-3"><span class="iconfa-comments"></span></a></li>
                            </ul>
                            <div id="tabs-1" class="nopadding">
                                <h5 class="tabtitle">Last Logged In Users</h5>
                                <ul class="userlist">
                                    <li>
                                        <div>
                                            <img src="images/photos/thumb1.png" alt="" class="pull-left" />
                                            <div class="uinfo">
                                                <h5>Draniem Daamul</h5>
                                                <span class="pos">Software Engineer</span>
                                                <span>Last Logged In: 04/20/2013 8:40PM</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img src="images/photos/thumb2.png" alt="" class="pull-left" />
                                            <div class="uinfo">
                                                <h5>Therineka Chonpe</h5>
                                                <span class="pos">Regional Manager</span>
                                                <span>Last Logged In: 04/20/2013 3:30PM</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img src="images/photos/thumb3.png" alt="" class="pull-left" />
                                            <div class="uinfo">
                                                <h5>Zaham Sindilmaca</h5>
                                                <span class="pos">Chief Technical Officer</span>
                                                <span>Last Logged In: 04/19/2013 1:30AM</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img src="images/photos/thumb4.png" alt="" class="pull-left" />
                                            <div class="uinfo">
                                                <h5>Annie Cerona</h5>
                                                <span class="pos">Engineering Manager</span>
                                                <span>Last Logged In: 04/19/2013 11:30AM</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img src="images/photos/thumb5.png" alt="" class="pull-left" />
                                            <div class="uinfo">
                                                <h5>Delher Carasbong</h5>
                                                <span class="pos">Software Engineer</span>
                                                <span>Last Logged In: 04/19/2013 11:00AM</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div id="tabs-2" class="nopadding">
                                <h5 class="tabtitle">Favorites</h5>
                                <ul class="userlist userlist-favorites">
                                                                        <li>
                                        <div>
                                            <img src="images/photos/thumb3.png" alt="" class="pull-left" />
                                            <div class="uinfo">
                                                <h5>Zaham Sindilmaca</h5>
                                                <p class="link">
                                                    <a href=""><i class="iconfa-envelope"></i> Message</a>
                                                    <a href=""><i class="iconfa-phone"></i> Call</a>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img src="images/photos/thumb4.png" alt="" class="pull-left" />
                                            <div class="uinfo">
                                                <h5>Annie Cerona</h5>
                                                <p class="link">
                                                    <a href=""><i class="iconfa-envelope"></i> Message</a>
                                                    <a href=""><i class="iconfa-phone"></i> Call</a>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img src="images/photos/thumb5.png" alt="" class="pull-left" />
                                            <div class="uinfo">
                                                <h5>Delher Carasbong</h5>
                                                <p class="link">
                                                    <a href=""><i class="iconfa-envelope"></i> Message</a>
                                                    <a href=""><i class="iconfa-phone"></i> Call</a>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img src="images/photos/thumb1.png" alt="" class="pull-left" />
                                            <div class="uinfo">
                                                <h5>Draniem Daamul</h5>
                                                <p class="link">
                                                    <a href=""><i class="iconfa-envelope"></i> Message</a>
                                                    <a href=""><i class="iconfa-phone"></i> Call</a>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img src="images/photos/thumb2.png" alt="" class="pull-left" />
                                            <div class="uinfo">
                                                <h5>Therineka Chonpe</h5>
                                                <p class="link">
                                                    <a href=""><i class="iconfa-envelope"></i> Message</a>
                                                    <a href=""><i class="iconfa-phone"></i> Call</a>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div id="tabs-3" class="nopadding">
                                <h5 class="tabtitle">Top Comments</h5>
                                <ul class="userlist">
                                    <li>
                                        <div>
                                            <img src="images/photos/thumb4.png" alt="" class="pull-left" />
                                            <div class="uinfo">
                                                <h5>Annie Cerona</h5>
                                                <p class="par">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididun</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img src="images/photos/thumb5.png" alt="" class="pull-left" />
                                            <div class="uinfo">
                                                <h5>Delher Carasbong</h5>
                                                <p class="par">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididun</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img src="images/photos/thumb1.png" alt="" class="pull-left" />
                                            <div class="uinfo">
                                                <h5>Draniem Daamul</h5>
                                                <p class="par">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididun</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img src="images/photos/thumb2.png" alt="" class="pull-left" />
                                            <div class="uinfo">
                                                <h5>Therineka Chonpe</h5>
                                                <p class="par">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididun</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img src="images/photos/thumb3.png" alt="" class="pull-left" />
                                            <div class="uinfo">
                                                <h5>Zaham Sindilmaca</h5>
                                                <p class="par">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididun</p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div><!--tabbedwidget-->
                </div>
<?php
include('include/footer.php');
?>


    
<script type="text/javascript">
    jQuery(document).ready(function() {
        // tabbed widget
        jQuery('.tabbedwidget').tabs();
    });
</script>
</body>
</html>

