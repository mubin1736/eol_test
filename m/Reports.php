<?php
$titel = "Reports";
$Reports = 'active';
include('include/hader.php');
extract($_POST);
//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'Reports' AND $user_type = '1'");
if(mysql_num_rows($access) > 0){
//---------- Permission -----------
?>
	<div class="box box-primary">
			<div class="box-body">
								<?php if(in_array(155, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportCashInHand;?>" href="ReportCashInHand" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; Cash In Hand Statement </a>
				<?php } if(in_array(156, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportIncomeExpanceStatement;?>" href="ReportIncomeExpanceStatement" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; Income Statement </a>
				<?php } if(in_array(157, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportExpenceHead;?>" href="ReportExpenceHead" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; Expense </a>
				<?php } if(in_array(158, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportCollection;?>" href="ReportCollection" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; Collection </a>
				<?php } if(in_array(159, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportCollectionSum;?>" href="ReportCollectionSum" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; Collection Summary</a>
				<?php } if(in_array(160, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportBillPrint;?>" href="ReportBillPrint" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; Due Bills </a>
				<?php } if(in_array(161, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportDew;?>" href="ReportDew" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; Due Bill Summary</a>
				<?php } if(in_array(162, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportInvoice;?>" href="ReportInvoice" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; Invoice (Due Bills)</a>
				<?php } if(in_array(163, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportClients;?>" href="ReportClients" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; Clients </a>
				<?php } if(in_array(180, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportResellerCollection;?>" href="ReportResellerCollection" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; Reseller Collection </a>
				<?php } if(in_array(179, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportClientsNew;?>" href="ReportClientsNew" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; New Clients </a>
				<?php } if(in_array(164, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportDiactivationClients;?>" href="ReportDiactivationClients" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; Diactivation Clients </a>
				<?php } if(in_array(165, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportSupport;?>" href="ReportSupport" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; Clients Support</a>
				<?php } if(in_array(166, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportClientLaser;?>" href="ReportClientLaser" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; Client Ledger </a>
				<?php } if(in_array(169, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportStoreInstruments;?>" href="ReportStoreInstruments" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; Store (Instrument)</a>
				<?php } if(in_array(172, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportStoreCable;?>" href="ReportStoreCable" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; Store (Cable)</a>
				<?php } if(in_array(173, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportOthersBillCollection;?>" href="ReportOthersBillCollection" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; Others Collection </a>
				<?php } if(in_array(178, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportRevenue;?>" href="ReportRevenue" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; Revenue </a>
				<?php } if(in_array(174, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportAgentLedger;?>" href="ReportAgentLedger" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; Agent Ledger </a>
				<?php } if(in_array(175, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportVendorLedger;?>" href="ReportVendorLedger" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; Vendor Ledger </a>
				<?php } if(in_array(176, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportLoanLedger;?>" href="ReportLoanLedger" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; Loan Ledger </a>
				<?php } if(in_array(177, $access_arry)){?>
					<a class="list-group-item <?php echo $ReportBtrc;?>" href="ReportBtrc" style="font-weight: bold;"><i class="iconfa-chevron-right"></i> &nbsp; BTRC Report </a>
				<?php } ?>
			</div>			
	</div>	
				
<?php
}
else{
	include('include/index');
}
include('include/footer.php');
?>