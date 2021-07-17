<?php 
//session_cache_expire( 20 );
session_start(); // NEVER FORGET TO START THE SESSION!!!

    //Check whether the session variable SESS_MEMBER_ID is present or not
	if($_SESSION['SESS_USER_TYPE'] == '') {
		header("location: index");
		exit();
	}
include("../web/conn/connection.php");
include('../web/company_info.php');
include("Function.php");
ini_alter('date.timezone','Asia/Almaty');

mysql_query("SET CHARACTER SET utf8");
mysql_query("SET SESSION collation_connection ='utf8_general_ci'");

$e_id = $_SESSION['SESS_EMP_ID'];

$userr_typ = $_SESSION['SESS_USER_TYPE'];

if($userr_typ == 'mreseller') {
$sss1 = mysql_query("SELECT z_id, z_name, e_id FROM zone WHERE e_id ='$e_id'");
$sssw1 = mysql_fetch_assoc($sss1);

$macz_id = $sssw1['z_id'];

$sss1m = mysql_query("SELECT reseller_logo, billing_type FROM emp_info WHERE e_id ='$e_id'");
$sssw1m = mysql_fetch_assoc($sss1m);

$billing_typee = $sssw1m['billing_type'];

if($sssw1m['reseller_logo'] != 'emp_images/'){
$logo = $weblink.$sssw1m['reseller_logo'];
}
else{
$logo = $weblink.'images/logo.png';
}}
else{
$logo = $weblink.'images/logo.png';
}

$s1 = mysql_query('SELECT * FROM app_config');
$sw1 = mysql_fetch_assoc($s1);

$CompanyName = $sw1['name'];
$CompanyEmail = $sw1['email'];
$CompanyAddress = $sw1['address'];
$CompanyPostalCode = $sw1['postal_code'];
$CompanyFax = $sw1['fax'];
$CompanyPhone = $sw1['phone'];
$CompanyWebsite = $sw1['website'];
$CompanyCurrency = $sw1['currency'];
$CompanyLogo = $sw1['logo'];

$acce_arry = mysql_fetch_assoc(mysql_query("SELECT GROUP_CONCAT(id SEPARATOR ',') AS page_access FROM module_page WHERE $userr_typ = '1'"));
$access_arry = explode(',',$acce_arry['page_access']);

$PageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);  

if($userr_typ == 'mreseller') {
$sql1q = mysql_query("SELECT c_id, SUM(bill_amount) AS totbill FROM billing_mac WHERE z_id = '$macz_id'");
$rowq = mysql_fetch_array($sql1q);


$sql1z1 = mysql_query("SELECT p.id, SUM(p.pay_amount) AS repayment, SUM(p.discount) AS rediscount, (SUM(p.pay_amount) + SUM(p.discount)) AS retotalpayments FROM `payment_macreseller` AS p
						WHERE p.z_id = '$macz_id' AND p.sts = '0'");
$rowwz = mysql_fetch_array($sql1z1);

$aaaa = $rowwz['retotalpayments']-$rowq['totbill'];

if($aaaa < 0){
	$color = 'color:red;';		
	$bcolor = 'background-color: red !important;';	
	$bdcolor = 'border: 1px solid red;';
} else{
	$color = 'color:#555555;';
	$bcolor = 'background-color: #0866c6cc !important;';	
	$bdcolor = 'border: 1px solid #0866c6cc !important;';
}
}
?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN'
  'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8" />
<title><?php echo $titel.' || '. $comp_name; ?></title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-fileupload.min.css" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-timepicker.min.css" type="text/css" />
<link rel="stylesheet" href="css/prettify.css" type="text/css" />
<link rel="icon" type="images/png" href="images/favicon.png"/>

<script type="text/javascript" src="js/jquery.m.min.js"></script>
<script type="text/javascript" src="js/bootstrap.m.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap-fileupload.min.js"></script>
<script type="text/javascript" src="js/bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>

<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.tagsinput.min.js"></script>
<script type="text/javascript" src="js/jquery.autogrow-textarea.js"></script>
<script type="text/javascript" src="js/charCount.js"></script>
<script type="text/javascript" src="js/colorpicker.js"></script>
<script type="text/javascript" src="js/ui.spinner.min.js"></script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/prettify.js"></script>
<?php if($PageName == 'ClientsInactive.php' || $PageName == 'ClientsRecharge.php' || $PageName == 'MACClientAdd.php'){?>

<?php } else{?>
<script type="text/javascript" src="js/forms.js"></script>
<script type="text/javascript" src="js/elements.js"></script>
<?php } ?>

<script type="text/javascript" src="js/jquery.jgrowl.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/dy_add_input.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/jquery.smartWizard.min.js"></script>

<!-- print script start-->
<script>
function myFunction()
{
window.print();
}
</script>
<!--print script end -->

</head>
 
<body>
<nav class="navbar navbar-inverse" style="background-color: white !important;">
  <div class="container-fluid">
    <div class="navbar-header">
            <a href="Welcome"><img src="<?php echo $logo;?>" alt="<?php echo $comp_name; ?>" height="40px" width="120px" style="margin-top: 5px;" class="marggg"/></a> 
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav" >
	  <li><a href="Welcome">Home</a></li><!--  class="active" --->
       <?php
						$sqlq = mysql_query("SELECT `position`, `module_name`, `desc`, `icon` FROM module WHERE $userr_typ = '1' AND m = '1' order by position");
						while( $rowaa = mysql_fetch_assoc($sqlq) )
						{ 
									echo "<li class='${$rowaa['module_name']}'>
											<a href='{$rowaa['module_name']}'>
												<span class='title'>{$rowaa['desc']}</span>
												<span class='selected'></span>
											</a>
										</li>";
						}
						?>
		</ul>	
		<ul class="nav navbar-nav navbar-right">
		 <?php if($userr_typ == 'mreseller') { ?>
			<li><a style="font-weight: bold;<?php echo $color; ?>"><b>Balance: <?php echo number_format($aaaa,2);?>৳</b></a></li>
		<?php } ?>
			<li><a href="#"><b><?php echo $_SESSION['SESS_FIRST_NAME']; ?></b></a></li>
			<li><a href="index" style="color:#333;">[Logout]</a></li>
		</ul>
    </div>
  </div>
</nav>
<div class="maincontent">
            <div class="maincontentinner">
			<div class="row-fluid">