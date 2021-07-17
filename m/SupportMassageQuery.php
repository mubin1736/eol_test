<?php
include("../web/conn/connection.php") ;
extract($_POST);
$replys = mysql_real_escape_string($reply);

date_default_timezone_set('Etc/GMT-6');
$reply_date_time = date('Y-m-d G:i:s', time());

if ($way=='internal'){

if( empty($_POST['reply']))	{ }

else{
	$query="insert into complain_detail_internal (rep_by, ticket_no, reply, reply_date_time) VALUES ('$e_id', '$ticket_no', '$replys', '$reply_date_time')";
	$sql = mysql_query($query);	
	if ($sql)
		{
			header("location: SupportMassageInternal$back");
		}
	else
		{
			echo 'Error, Please try again';
		}
}
	
}
if ($way=='client'){
	
if( empty($_POST['reply']))	{ }

else{
	$query="insert into complain_detail (rep_by, ticket_no, reply, reply_date_time) VALUES ('$e_id', '$ticket_no', '$replys', '$reply_date_time')";
	$sql = mysql_query($query);	
	if ($sql)
		{
			header("location: SupportMassage$back");
		}
	else
		{
			echo 'Error, Please try again';
		}
}
}
mysql_close($con);
?>