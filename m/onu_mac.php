<?php 
include("conn/connection.php");
$cable_type=$_GET['cable_type'];

if($cable_type == 'FIBER'){
?>


<p>
	<label style="width: 130px;">ONU MAC*</label>
	<span class="field" style="margin-left: 0px;"><input type="text" style="width:240px;" name="onu_mac" class="input-xlarge" required=""/></span>
</p>

<?php 
}else{?>
	<input type="hidden" name="onu_mac" class="input-xlarge" value="" required=""/>
<?php }
?>