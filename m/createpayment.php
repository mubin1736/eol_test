<?php
session_start();
$strJsonFileContents = file_get_contents("js/config.json");
$array = json_decode($strJsonFileContents, true);
$user_type = $_SESSION['SESS_USER_TYPE'];
include("../web/conn/connection.php");
ini_alter('date.timezone','Asia/Almaty');
$y = date("Y");
$m = date("m");
$dat = $y.$m;

if($user_type == 'mreseller'){
$respy = mysql_query("SELECT `id` FROM `payment_macreseller` ORDER BY id DESC LIMIT 1");
$rowspy = mysql_fetch_array($respy);
	if($rowspy['id'] == ''){
			$invo_no = 'BK-'.$dat.'1';
		}
		else{
			$new = $rowspy['id'] + 1;
			$invo_no = 'BK-'.$dat.$new;
		}
}
else{
$respy = mysql_query("SELECT `id` FROM `payment` ORDER BY id DESC LIMIT 1");
$rowspy = mysql_fetch_array($respy);
	if($rowspy['id'] == ''){
			$invo_no = 'BK-'.$dat.'1';
		}
		else{
			$new = $rowspy['id'] + 1;
			$invo_no = 'BK-'.$dat.$new;
		}
}
$amount=$_GET['amount'];
$invoice=$invo_no;//must be unique
$intent = "sale";

	   $createpaybody=array(
	       'amount'=>$amount,
		   'currency'=>'BDT',
		   'intent'=>$intent,
		   'merchantInvoiceNumber'=>$invoice
		   );	
		
		$url = curl_init($array["createURL"]);
		
		$createpaybodyx=json_encode($createpaybody);
		$header=array(
		        'Content-Type:application/json',
				'authorization:'.$array["token"],
				'x-app-key:'.$array["app_key"]);
				curl_setopt($url,CURLOPT_HTTPHEADER, $header);
				curl_setopt($url,CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($url,CURLOPT_RETURNTRANSFER, true);
				curl_setopt($url,CURLOPT_POSTFIELDS, $createpaybodyx);
				curl_setopt($url,CURLOPT_FOLLOWLOCATION, 1);
				$resultdata=curl_exec($url);
				curl_close($url);
				echo $resultdata;
function writeLog($logName, $logData){
	file_put_contents($logName.'.log',$logData,FILE_APPEND);
}
?>