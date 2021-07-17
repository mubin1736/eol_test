<?php
$titel = "Support";
$Support = 'active';
include('include/hader.php');

date_default_timezone_set('Etc/GMT-6');
$dateAndTime = date('Y-m-d G:i:s', time());

$status = $_GET['id'];

//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'Support' AND $user_type = '1'");
if(mysql_num_rows($access) > 0){
//---------- Permission -----------

$query = mysql_query("SELECT l.e_id, c.c_name, c.c_id, l.user_name FROM login AS l
						LEFT JOIN clients AS c
						ON c.c_id = l.e_id
						WHERE l.e_id = '$e_id'");
$row5 = mysql_fetch_assoc($query);
$c_name = $row5['c_name'];
$c_id = $row5['c_id'];


//------------------------

							if($user_type == 'admin' || $user_type == 'ets' || $user_type == 'superadmin') {
								if($status == 'pending' || $status == ''){
								$sql = mysql_query("SELECT m.ticket_no, count(l.reply) AS totrep, m.c_id, c.c_name, c.address, c.com_id, m.dept_id, d.dept_name, m.sub, m.massage, DATE_FORMAT(m.entry_date_time, '%D %M %Y %h:%i%p') AS entry_date_time, m.sts, l.reply, DATE_FORMAT(l.reply_date_time, '%D %M %Y %h:%i%p') AS reply_date_time FROM complain_master AS m
												LEFT JOIN department_info AS d
												ON d.dept_id = m.dept_id 
												LEFT JOIN clients AS c
												ON c.c_id = m.c_id 
												LEFT JOIN (SELECT id, ticket_no, reply, reply_date_time FROM complain_detail ORDER BY id DESC) AS l
												ON l.ticket_no = m.ticket_no
												WHERE m.sts = '0' GROUP BY m.ticket_no ORDER BY m.ticket_no");
								$sqls = mysql_query("SELECT id FROM complain_master WHERE sts = '0'");
								$pending = mysql_num_rows($sqls);
								$tit = "<div class='box-header'>
											<div class='hil'>Pending:  <i style='color: #317EAC'>{$pending}</i></div> 
										</div>";
								}
								
							}
							if($user_type == 'mreseller') {
								if($status == 'pending' || $status == ''){
								$sql = mysql_query("SELECT m.ticket_no, count(l.reply) AS totrep, m.c_id, c.c_name, c.address, c.com_id, m.dept_id, d.dept_name, m.sub, m.massage, DATE_FORMAT(m.entry_date_time, '%D %M %Y %h:%i%p') AS entry_date_time, m.sts, l.reply, DATE_FORMAT(l.reply_date_time, '%D %M %Y %h:%i%p') AS reply_date_time FROM complain_master AS m
												LEFT JOIN department_info AS d
												ON d.dept_id = m.dept_id 
												LEFT JOIN clients AS c
												ON c.c_id = m.c_id 
												LEFT JOIN (SELECT id, ticket_no, reply, reply_date_time FROM complain_detail ORDER BY id DESC) AS l
												ON l.ticket_no = m.ticket_no
												WHERE m.sts = '0' AND c.z_id = '$macz_id' AND c.mac_user = '1' GROUP BY m.ticket_no ORDER BY m.ticket_no");
												
								$sqls = mysql_query("SELECT m.id FROM complain_master AS m LEFT JOIN clients AS c ON c.c_id = m.c_id WHERE m.sts = '0' AND c.z_id = '$macz_id' AND c.mac_user = '1'");
								$pending = mysql_num_rows($sqls);
								$tit = "<div class='box-header'>
											<div class='hil'>Pending:  <i style='color: #317EAC'>{$pending}</i></div> 
										</div>";
								}
								
							}
							if($user_type == 'client') {
								$sql = mysql_query("SELECT m.ticket_no, m.c_id, c.com_id, count(l.reply) AS totrep, c.address, c.c_name, m.dept_id, d.dept_name, m.sub, m.massage, DATE_FORMAT(m.entry_date_time, '%D %M %Y %h:%i%p') AS entry_date_time, DATE_FORMAT(m.close_date_time, '%D %M %Y %h:%i%p') AS close_date_time, m.sts, l.reply, DATE_FORMAT(l.reply_date_time, '%D %M %Y %h:%i%p') AS reply_date_time FROM complain_master AS m
												LEFT JOIN department_info AS d
												ON d.dept_id = m.dept_id 
												LEFT JOIN clients AS c
												ON c.c_id = m.c_id 
												LEFT JOIN (SELECT id, ticket_no, reply, reply_date_time FROM complain_detail ORDER BY id DESC) AS l
												ON l.ticket_no = m.ticket_no
												WHERE m.c_id = '$e_id' GROUP BY m.ticket_no ORDER BY m.ticket_no DESC");			
							}
?>
	<div class="box box-primary">
		<div class="box-header">
			<h6 style="float: left;"><?php echo $tit; ?></h6> <a class="btn btn-neveblue" style="float: right;" href="AddTicket"><i class="iconfa-plus"></i></a>
		</div><br />
			<div class="box-body">
				<table id="dyntable" class="table table-bordered responsive">
                    <colgroup>
						<col class="con0" />
                        <col class="con1" />
                        <col class="con0" />
						<col class="con1" />
						<?php if($status == 'closed'){?><col class="con0" /><?php }?>
						<col class="con1" />
                    </colgroup>
                    <thead>
                        <tr  class="newThead">
                            <th class="head0">Ticket No</th>
							<th class="head1">Subject</th>
							<th class="head0">Open Time</th>
							<th class="head1">Status</th>
							<?php if($status == 'closed'){?><th class="head0">Close Time</th><?php }?>
                        </tr>
                    </thead>
                    <tbody>
						<?php
								while( $row = mysql_fetch_assoc($sql) )
								{
									if($row['sts'] == 0){
										$stss = 'Open';
									}
									if($row['sts'] == 1){
										$stss = 'Close';
									}
									if($row['close_date_time'] == '0000-00-00 00:00:00'){
										$close_date_times = '';
									}
									else{
										$close_date_times = $row['close_date_time'];
									}
									if($status == 'closed'){
									echo
										"<tr class='gradeX'>
											<td class='center'>
												<ul class='tooltipsample'>
													<li><a data-placement='top' data-rel='tooltip' href='SupportMassage?id=",$id,"{$row['ticket_no']}' data-original-title='View' class='btn col1'><i class='iconfa-eye-open'></i></a></li>
												</ul>
											</td>
											<td>{$row['c_id']}</td>
											<td>{$row['sub']}</td>
											<td>{$row['entry_date_time']}</td>
											<td>{$stss}</td>
											<td>{$close_date_times}</td>
											<td class='center'>
												<ul class='tooltipsample'>
													<li><a data-placement='top' data-rel='tooltip' href='SupportMassage?id=",$id,"{$row['ticket_no']}' data-original-title='View' class='btn col1'><i class='iconfa-eye-open'></i></a></li>
												</ul>
											</td>
										</tr>\n ";
								} else{
									echo
										"<tr class='gradeX'>
											<td class='center'>
												<ul class='tooltipsample'>
													<li><a href='SupportMassage?id=",$id,"{$row['ticket_no']}'>{$row['ticket_no']}</a><br/>{$row['c_id']}</li>
												</ul>
											</td>
											<td>{$row['sub']}</td>
											<td>{$row['entry_date_time']}</td>
											<td>{$stss}</td>
										</tr>\n";
								}
								}
							?>
					</tbody>
				</table>
				
			</div>			
	</div>

<!-- -------------------------------------------------------------Entry Data View------------------------------------------------------------ -->			
				
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
			"iDisplayLength": 100,
            "sPaginationType": "full_numbers",
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
<style>
#dyntable_length{display: none;}
</style>