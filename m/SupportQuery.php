<?php
include("../web/conn/connection.php") ;
extract($_POST);
$mass = mysql_real_escape_string($massage);

$y = date("Y");
$m = date("m");
$dat = $y.$m;

		$sql = ("SELECT id FROM complain_master ORDER BY id DESC LIMIT 1");
		$query2 = mysql_query($sql);
		$row = mysql_fetch_assoc($query2);
				$old_id = $row['id'];
		if($old_id == ''){
			$new_id = $dat.'1';
		}
		else{
			$new = $old_id + 1;
			$new_id = $dat.$new;
		}
		
		if($new_id != ''){
			$query = "insert into complain_master (ticket_no, c_id, dept_id, sub, massage, entry_by, entry_date_time)
					  VALUES ('$new_id', '$c_id', '$dept_id', '$sub', '$mass', '$entry_by', '$entry_date_time')";
			$result = mysql_query($query) or die("inser_query failed: " . mysql_error() . "<br />");
		}
		if ($result)
		if ($way=='client'){
		{
			header("location: Support");
		}
			}
			
mysql_close($con);
?>

