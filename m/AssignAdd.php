<?php
include("../web/conn/connection.php") ;
extract($_POST);

date_default_timezone_set('Etc/GMT-6');
$close_date_time = date('Y-m-d G:i:s', time());

if($stss == 'Assign'){
	$query="UPDATE complain_master SET assign = '$assign', assign_by = '$assign_by' WHERE ticket_no = '$ticket_no'";
	$sql = mysql_query($query);	
	if ($sql)
		{
			if($way == 'client'){
			header("location: SupportMassage$back");
			}
			if($way == 'internal'){
			header("location: SupportMassageInternal$back");
			}
		}
	else
		{
			echo 'Error, Please try again';
		}

mysql_close($con);
}

if($stss == 'closs'){
	$query="UPDATE complain_master SET ticket_sts = '$ticket_sts' WHERE ticket_no = '$ticket_no'";
	$sql = mysql_query($query);	
	if ($sql)
		{
			if($way == 'client'){
			header("location: SupportMassage$back");
			}
			if($way == 'internal'){
			header("location: SupportMassageInternal$back");
			}
		}
	else
		{
			echo 'Error, Please try again';
		}

mysql_close($con);
}

if($stss == 'end'){
	$query="UPDATE complain_master SET sts = '1', close_date_time = '$close_date_time', close_by = '$close_by' WHERE ticket_no = '$ticket_no'";
	$sql = mysql_query($query);	
	if ($sql)
		{
			if($way == 'client'){
			header("location: SupportMassage$back");
			}
			if($way == 'internal'){
			header("location: SupportMassageInternal$back");
			}
		}
	else
		{
			echo 'Error, Please try again';
		}

mysql_close($con);
}
?>