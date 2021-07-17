<?php 
$clientid=$_GET['clientid'];
include("../web/conn/connection.php");
ini_alter('date.timezone','Asia/Almaty');
$todayy = date('Y-m-d', time());

$quesww = mysql_query("SELECT b.id, b.c_id, c.cell, c.mac_user, c.mk_id, c.c_name, c.payment_deadline, c.termination_date, b.days, b.start_date, b.start_time, b.end_date, b.p_id, p.p_name, p.bandwith, p.p_price, b.bill_amount FROM billing_mac AS b
						LEFT JOIN package AS p
						ON p.p_id = b.p_id
						LEFT JOIN clients AS c
						ON c.c_id = b.c_id
						WHERE c.id = '$clientid' ORDER BY b.id DESC LIMIT 1");
$rowwww = mysql_fetch_assoc($quesww);
$idq = $rowwww['id'];
$start_date = $rowwww['start_date'];
$start_time = $rowwww['start_time'];
$enddate = $rowwww['end_date'];
$p_price = $rowwww['p_price'];

if($enddate < $todayy){
	$aaaa = $todayy;
}
else{
	$aaaa = $enddate;
}


$durations = strip_tags($_POST['duration']);
$Date2 = date('Y-m-d', strtotime($aaaa . " + ".$durations." day"));
$yrdata= strtotime($Date2);
$dateee = date('d-F, Y', $yrdata);

$packageoneday = $p_price/30;
$daycost = $durations*$packageoneday;

?>
<a style="color: #f95a5a;font-size: 13px;">Cost: <?php echo number_format($daycost,2); ?>৳</a> <a style="color: green;font-size: 15px;">  Till <?php echo $dateee; ?></a>
<?php// echo 'Cost: '.$daycost.' Till: '.$Date2; ?>

