<?php
$titel = "Support";
$Support = 'active';
include('include/hader.php');
$id = $_GET['id'];

//---------- Permission -----------
$user_type = $_SESSION['SESS_USER_TYPE'];
$access = mysql_query("SELECT * FROM module WHERE module_name = 'Support' AND $user_type = '1'");
if(mysql_num_rows($access) > 0){
//---------- Permission -----------
$queryq = mysql_query("SELECT user_name FROM login WHERE user_id = '$e_id'");
$row9 = mysql_fetch_assoc($queryq);
$u_name = $row9['user_name'];

$query = mysql_query("SELECT m.ticket_no, y.user_name, m.c_id, c.c_name, c.address, c.cell, c.z_id, m.assign, e.e_name AS assgn, z.z_name, m.dept_id, d.dept_name, m.sub, m.massage, m.entry_date_time, m.date_time, l.image, m.ticket_sts, m.sts, x.e_name AS close_by, m.close_date_time FROM complain_master AS m
						LEFT JOIN department_info AS d
						ON d.dept_id = m.dept_id 
						LEFT JOIN clients AS c
						ON c.c_id = m.c_id
						LEFT JOIN zone AS z
						ON z.z_id = c.z_id
						LEFT JOIN login AS l
						ON l.e_id = m.c_id
						LEFT JOIN emp_info AS e
						ON e.e_id = m.assign
						LEFT JOIN emp_info AS x
						ON x.e_id = m.close_by
						LEFT JOIN login AS y
						ON y.e_id = m.entry_by
						WHERE m.ticket_no = '$id'");
$row5 = mysql_fetch_assoc($query);
$ticket_no = $row5['ticket_no'];
$c_id = $row5['c_id'];
$address = $row5['address'];
$cell = $row5['cell'];
$c_name = $row5['c_name'];
$z_name = $row5['z_name'];
$dept_id = $row5['dept_id'];
$dept_name = $row5['dept_name'];
$subject = $row5['sub'];
$massage = $row5['massage'];
$date_time = $row5['date_time'];
$entry_date = $row5['entry_date'];
$entry_time = $row5['entry_time'];
$entry_date_time = $row5['entry_date_time'];
$image = $row5['image'];
$assign = $row5['assign'];
$assgn = $row5['assgn'];
$sts = $row5['sts'];
$ticket_sts = $row5['ticket_sts'];
$user_name = $row5['user_name'];
$close_by = $row5['close_by'];
$close_date_time = $row5['close_date_time'];


$queryr = mysql_query("SELECT e_id, e_name FROM emp_info ORDER BY e_name ASC");

//image take from session
if($_SESSION['SESS_EMP_IMG'] == '')
{$imgss = 'images/no_img.jpg';} 
else 
{$imgss = $_SESSION['SESS_EMP_IMG'];}

if ($image == ''){$image = 'images/no_img.jpg';}
?>
	<div class="box box-primary">
			<div class="box-body">
				<div class="messageview" style="border-bottom: medium none;">
				<?php if ($sts == '1') {?>
					<span class="wsasw">Closed by <?php echo $close_by; ?></span>
				<?php } else { if ($user_type == 'admin' || $user_type == 'superadmin' || $user_type == 'ets') { if($ticket_sts == 'Solved') {?>
					<div class="btn-group pull-right">
						<form id="form1" name="form1" method="post" action="AssignAdd">
						<input type='hidden' name='stss' value = 'end' />
						<input type='hidden' name='way' value = 'client' />
						<input type='hidden' name='close_by' value = '<?php echo $e_id; ?>' />
						<input type='hidden' name='ticket_no' value = '<?php echo $ticket_no; ?>'/>
						<input type='hidden' name='back' value = '?id=<?php echo $id; ?>'/>
							<button class="btn" type="submit" style = "height: 33px;">Close Ticket</button>
						</form>
					</div>
				<?php }
				else{}
				}}?>
							<?php if ($user_type == 'admin' || $user_type == 'superadmin' || $user_type == 'ets') { if($ticket_sts == 'Solved') {?>
								<span class="wsasw">Status : <?php echo $ticket_sts; ?></span>
							<?php } else { ?>
							<div class="btn-group pull-right">
								<form id="form1" name="form1" method="post" action="AssignAdd">
								<input type='hidden' name='stss' value = 'closs' />
								<input type='hidden' name='way' value = 'client' />
								<input type='hidden' name='ticket_no' value = '<?php echo $ticket_no; ?>'/>
								<input type='hidden' name='back' value = '?id=<?php echo $id; ?>'/>
									<select name="ticket_sts" class="chzn-select" onchange="submit();" style="width:180px;">	
											<option>No Action</option>							
											<option value="Solved" <?php if ('Solved' == $ticket_sts) echo 'selected="selected"';?>>Solved</option>
											<option value="Under Process" <?php if ('Under Process' == $ticket_sts) echo 'selected="selected"';?>>Under Process</option>
											<option value="Complicated" <?php if ('Complicated' == $ticket_sts) echo 'selected="selected"';?>>Complicated</option>
									</select>
								</form>
							</div>
							<?php }} if ($user_type == 'client' && $sts == '1') {?>
								<span class="wsasw">Status : <?php if ($ticket_sts == ''){echo "No Action Taken";} else{echo $ticket_sts;} ?></span>
							<?php } if ($user_type == 'client' && $sts == '0') {?>
								<span class="wsasw">Ticket Status : <?php if ($ticket_sts == ''){echo "Under Process";} else{echo $ticket_sts;} ?></span>
							<?php } if ($assign == '') { if ($user_type == 'admin' || $user_type == 'superadmin' || $user_type == 'ets') {?>
							
							<div class="btn-group pull-right">
								<form id="form1" name="form1" method="post" action="AssignAdd">
								<input type='hidden' name='stss' value = 'Assign' />
								<input type='hidden' name='way' value = 'client' />
								<input type='hidden' name='ticket_no' value = '<?php echo $ticket_no; ?>'/>
								<input type='hidden' name='assign_by' value='<?php echo $e_id; ?>'/>
								<input type='hidden' name='back' value = '?id=<?php echo $id; ?>'/>
									<select name="assign" class="chzn-select" onchange="submit();" style="width:180px;">	
											<option>Assign Person</option>								
											<?php
											while ($row2=mysql_fetch_array($queryr)) { ?>	
												<option value="<?php echo $row2['e_id']?>"><?php echo $row2['e_name'];?> || <?php echo $row2['e_id'];?></option>		
											<?php } ?>
									</select>
								</form>
							</div>
							<?php }} else {?>
								<span class="wsasw">Assign : <?php echo $assgn; ?></span>
							<?php }?>
                                <h1 class="subject"><?php echo $subject; ?> (#<?php echo $ticket_no; ?>)</h1>
                                <div class="msgauthor">
                                    <div class="thumb"><img src="<?php echo $image; ?>" style="height: 57px;margin: 5px 0px 0 0;" /></div>
                                    <div class="authorinfo">
                                        <span class="date pull-right"><?php $yrda= strtotime($entry_date_time); $dates = date('F d, Y', $yrda); $times = date('H:i', $yrda); echo $dates.' at '.$times; ?></span>
                                        <h5><strong><?php echo $c_id; ?></strong>, <span><?php echo $address; ?></span>, <span><?php echo $cell; ?> </span><span style="color: #317EAC;"> TT Open By <?php echo $user_name; ?> </span></h5> 
                                        <span class="to">To <?php echo $dept_name; ?> Depertment</span>
										<?php echo $massage; ?>
                                    </div>
                                </div>
                                <?php
								 $sql = mysql_query("SELECT l.user_name, d.reply, d.date_time, d.reply_date_time, l.image  FROM complain_detail AS d
													LEFT JOIN login AS l ON l.e_id = d.rep_by
													WHERE d.ticket_no = '$id' ORDER BY d.id");
													
												
								while( $row = mysql_fetch_assoc($sql) ){
										$yrdata= strtotime($row['reply_date_time']);
										$date = date('F d, Y', $yrdata);
										$time = date('H:i', $yrdata);
										$imgg=$row['image'];
										if($imgg==''){$imgg='images/no_img.jpg';}
									echo "<div class='msgauthor' style='border-width: 0 0 1px;'>
											<div class='thumb'><img src='{$imgg}' style='height: 57px;margin: 10px 2px 0 0;' /></div>
											<div class='authorinfo'>
												<span class='date pull-right'>{$date} at {$time}</span>
												<h5><strong>{$row['user_name']}</strong> <span></span></h5>
												{$row['reply']}
											</div>
										</div>";
									
								}
							
								?>
								<?php if ($sts == '1') {?>
								<br><br>
								<div class="alert alert-block">
								  <button data-dismiss="alert" class="close" type="button">&times;</button>
								  <strong>Ticket Has Been Closed!!</strong> Our Staff <strong><?php echo $close_by; ?></strong> Close this Ticket at <?php echo $close_date_time; ?>.
								</div><!--alert-->
								<?php } else {?>
								<div class='msgreply'>
									<div class='thumb'><img src='<?php echo $imgss; ?>' style="height: 57px;margin: 5px 0px 0 0;"/></div>
										<div class='reply'>
											<form class='stdform' id='form2' method='post' action='SupportMassageQuery'>
											<input type='hidden' name='way' value = 'client'/>
											<input type='hidden' name='ticket_no' value = '<?php echo $ticket_no; ?>'/>
											<input type='hidden' name='e_id' value='<?php echo $e_id; ?>'/>
											<input type='hidden' name='back' value = '?id=<?php echo $id; ?>'/>
												<div class=''><textarea name='reply' placeholder='Type something here to reply' rows='3' col='3' style="width: 80%;;"></textarea>
												<button class='btn btn-success btn-large sub_reply'>Reply</button></div>
											</form>
										</div>
								</div>
								<?php if ($user_type == 'admin' || $user_type == 'superadmin' || $user_type == 'ets'){ if ($sts == '0'){?>
								<br><br>
								<div class="alert alert-block">
								  <button data-dismiss="alert" class="close" type="button">&times;</button>
								  <strong>Dear <a style="text-transform: uppercase;"><?php echo $_SESSION['SESS_FIRST_NAME']; ?>, </a>Only when you select Action as Solved, You will get the Ticket Closed Button. Thank You...</strong>
								</div><!--alert-->
								<?php }}}?>
                 </div>
							

<?php //if ($user_type == 'client') {echo SupportMassageQueryClient;} else {echo SupportMassageQueryStuff;}?>
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
