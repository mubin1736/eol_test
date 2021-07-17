<?php
include("../web/conn/connection.php");
mysql_query("SET CHARACTER SET utf8");
mysql_query("SET SESSION collation_connection ='utf8_general_ci'");
extract($_POST);
if($wayy == 'add'){
				$query2q ="insert into servers (text, category, show_sts) VALUES ('$text', '$category', '$show_sts')";
					if (!mysql_query($query2q))
					{
					die('Error: ' . mysql_error());
					}
}
if($wayy == 'edit'){
			$query="UPDATE servers SET
				category = '$category',
				text = '$text',
				show_sts = '$show_sts'

				WHERE id = '$ser_id'";

		$result = mysql_query($query) or die("inser_query failed: " . mysql_error() . "<br />");
}

mysql_close($con);
?>

<html>
<body>
     <form action="Servers" method="post" name="ok">
       <input type="hidden" name="sts" value="add">
     </form>

     <script language="javascript" type="text/javascript">
		document.ok.submit();
     </script>
</body>
</html>