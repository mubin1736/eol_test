<?php
session_start();
$strJsonFileContents = file_get_contents("js/config.json");
$array = json_decode($strJsonFileContents, true);

$paymentID=$_GET['paymentID'];
    
$url = curl_init($array["executeURL"].$paymentID);

$header=array(
		'Content-Type:application/json',
		'authorization:'.$array["token"],
		'x-app-key:'.$array["app_key"]);
		curl_setopt($url,CURLOPT_HTTPHEADER, $header);
		curl_setopt($url,CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($url,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($url,CURLOPT_FOLLOWLOCATION, 1);
		$resultdatax=curl_exec($url);
		curl_close($url);
	echo $resultdatax;
function writeLog($logName, $logData){
	file_put_contents($logName.'.log',$logData,FILE_APPEND);
}
?>