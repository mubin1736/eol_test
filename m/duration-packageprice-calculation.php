<?php $pid=$_GET['p_id'];
include("../web/conn/connection.php");
ini_alter('date.timezone','Asia/Almaty');
$todayyyy = date('Y-m-d', time());

$durations = strip_tags($_POST['duration']);
$Date2 = date('Y-m-d', strtotime($todayyyy . " + ".$durations." day"));
$yrdata= strtotime($Date2);
$dateee = date('d-F, Y', $yrdata);

$resultwww = mysql_query("SELECT p_id, p_price FROM package WHERE p_id = '$pid' AND status = '0'");
$rowprice = mysql_fetch_assoc($resultwww);
$p_price= $rowprice['p_price'];

$packageoneday = $p_price/30;
$daycost = $durations*$packageoneday;

?>
<a style="color: #f95a5a;font-size: 13px;">Cost: <?php echo number_format($daycost,2); ?>৳</a> <a style="color: green;font-size: 13px;">  Till <?php echo $dateee; ?></a>
<?php// echo 'Cost: '.$daycost.' Till: '.$Date2; ?>

