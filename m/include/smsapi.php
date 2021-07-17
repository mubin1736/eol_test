<?php 
include('../web/company_info.php');
if($userr_typ == 'mreseller'){
$sql88 = ("SELECT s.id, s.link, s.username, s.password, s.status, e.e_cont_per FROM sms_setup AS s 
LEFT JOIN emp_info AS e ON e.z_id = s.z_id
WHERE s.status = '0' AND s.z_id = '$macz_id'");

$query88 = mysql_query($sql88);
$row88 = mysql_fetch_assoc($query88);
		$link= $row88['link'];
		$username= $row88['username'];
		$password= $row88['password'];
		$status= $row88['status'];
		$e_cont_per= $row88['e_cont_per'];
		
$sms_footer = 'Thanks
'.$comp_name.'
'.$e_cont_per.'';
}
else{
$sql88 = ("SELECT id, link, username, password, status FROM sms_setup WHERE status = '0' AND z_id = ''");

$query88 = mysql_query($sql88);
$row88 = mysql_fetch_assoc($query88);
		$link= $row88['link'];
		$username= $row88['username'];
		$password= $row88['password'];
		$status= $row88['status'];
		
$sms_footer = 'Thanks
'.$comp_name.'
'.$company_cell.'';
}
?>
