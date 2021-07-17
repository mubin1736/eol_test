<?php
$titel = "Servers";
$Servers = 'active';
include('include/hader.php');
extract($_POST);
//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'Servers' AND $user_type = '1'");
if(mysql_num_rows($access) > 0){
//---------- Permission -----------

?>
<div class="box box-primary">
			<?php if($user_type == 'client' || $user_type == 'breseller'){ ?>
			<div class="box-header">
				<h4>FTP Servers List</h4>
			</div>
			<div class="box-body">
			<?php $sqlfg = mysql_query("SELECT id, text, category, date_time, sts FROM servers WHERE category = '0' AND sts = '0' AND show_sts = '0'");
			$x = 1;
			while( $rowgc = mysql_fetch_assoc($sqlfg) )
						{?>
                        	<div class="row-fluid" style="width: 90%;padding-left: 2%;">
                            	<div class="span6" style="padding-left: 2%;">
                                   <b><?php echo $x;?>.</b> <?php echo $rowgc['text'];?>
								</div>
                            </div><br>
			<?php $x++;} ?>
			</div>
			<br>
			<div class="box-header">
				<h4>TV Servers List</h4>
			</div>
			<div class="box-body">
			<?php $sqlfg = mysql_query("SELECT id, text, category, date_time, sts FROM servers WHERE category = '1' AND sts = '0' AND show_sts = '0'");
			$a = 1;
			while( $rowgc = mysql_fetch_assoc($sqlfg) )
						{?>
                        	<div class="row-fluid" style="width: 90%;padding-left: 2%;">
                            	<div class="span6" style="padding-left: 2%;">
                                   <b><?php echo $a;?>.</b> <?php echo $rowgc['text'];?>
								</div>
                            </div><br>
			<?php $a++;} ?>
			</div>
			<?php } else {?>
			<div class="box-header">
				<h4>FTP & TV Servers List</h4>
			</div>
			<div class="box-body">
				<table id="dyntable" class="table table-bordered responsive">
                    <colgroup>
                        <col class="con0" />
                        <col class="con1" />
                        <col class="con0" />
                        <col class="con1" />
						<col class="con0" />
						<col class="con1" />
                    </colgroup>
                    <thead>
                        <tr  class="newThead">
							<th class="head0 center">ID</th>
                            <th class="head1 center">Text Contain</th>
							<th class="head0 center">Category</th>
							<th class="head1 center">Show?</th>
							<th class="head0 center">Date</th>
							<th class="head1 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php 
						$sql = mysql_query("SELECT id, text, category, show_sts, date_time, sts FROM servers WHERE sts = '0'");
								while( $row = mysql_fetch_assoc($sql) )
						{
									if($row['show_sts'] == '0'){
										$sghs = 'Yes';
									}
									else{
										$sghs = 'No';
									}
									if($row['category'] == '0'){
										$fdghg = 'FTP Server';
									}
									else{
										$fdghg = 'TV Server';
									}
									echo
										"<tr class='gradeX'>
											<td class='center'>{$row['id']}</td>
											<td style='padding-left: 2%;'>{$row['text']}</td>
											<td class='center'>{$fdghg}</td>
											<td class='center'>{$sghs}</td>
											<td class='center'>{$row['date_time']}</td>
											<td class='center'>
												<ul class='tooltipsample'>
													<li><form action='ServersEdit' method='post' data-placement='top' data-rel='tooltip' title='Edit'><input type='hidden' name='s_id' value='{$row['id']}' /><button class='btn col1'><i class='iconfa-edit'></i></button></form></li>
												</ul>
											</td>
										</tr>\n ";
								
						}
							?>
					</tbody>
				</table>
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
<script type="text/javascript">
    jQuery(document).ready(function(){
        // dynamic table
        jQuery('#dyntable').dataTable({
			"iDisplayLength": 20,
            "sPaginationType": "full_numbers",
            "aaSortingFixed": [[0,'desc']],
            "fnDrawCallback": function(oSettings) {
                jQuery.uniform.update();
            }
        });
    });
</script>
<script type="text/javascript">
    jQuery(document).ready(function(){
                                    
        //Replaces data-rel attribute to rel.
        //We use data-rel because of w3c validation issue
        jQuery('a[data-rel]').each(function() {
            jQuery(this).attr('rel', jQuery(this).data('rel'));
        });
        
        // tooltip sample
	if(jQuery('.tooltipsample').length > 0)
		jQuery('.tooltipsample').tooltip({selector: "a[rel=tooltip]"});
		
	jQuery('.popoversample').popover({selector: 'a[rel=popover]', trigger: 'hover'});
        
    });
</script>
<script language="JavaScript" type="text/javascript">function checkDelete(){    return confirm('Delete!!  Are you sure?');}</script>
<style>
#dyntable_length{display: none;}
</style>