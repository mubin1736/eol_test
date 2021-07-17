<?php
include("../web/conn/connection.php");
extract($_POST);

	if(empty($_POST['fund_send']) || empty($_POST['fund_received']) || empty($_POST['transfer_amount']) || empty($_POST['transfer_date']))
		{
			echo 'Please Complete All Filed';
		}
		else
		{
			$query="insert into fund_transfer (fund_send, fund_received, transfer_amount, transfer_date, note, ent_date, ent_by)
					VALUES ('$fund_send', '$fund_received', '$transfer_amount', '$transfer_date', '$note', '$enty_date', '$entry_by')";

			$result = mysql_query($query) or die("inser_query failed: " . mysql_error() . "<br />");

			if ($result)
				{ ?>
					<html>
						<body>
							<form action="FundTransfer" method="post" name="ok">
								<input type="hidden" name="sts" value="add">
								<input type="hidden" name="fundreceived" value="<?php echo $fund_received;?>">
							</form>
							<script language="javascript" type="text/javascript">
									document.ok.submit();
							</script>
						</body>
					</html>			
				<?php }
			else{
					echo 'Error, Please try again';
				}
		}

mysql_close($con);
?>
