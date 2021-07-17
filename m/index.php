<?php
session_start();
include('../web/company_info.php');
unlink('error_log');
if($com_sts == '0'){
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo $comp_name;?></title>
<link rel="stylesheet" href="css/login.css" type="text/css" />
<link rel="stylesheet" href="css/style.shinyblue.css" type="text/css" />
<link rel="icon" type="images/png" href="images/favicon.png"/>

<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#login').submit(function(){
            var u = jQuery('#username').val();
            var p = jQuery('#password').val();
            if(u == '' && p == '') {
                jQuery('.login-alert').fadeIn();
                return false;
            }
        });
    });
</script>
</head>
<body class="login-page" style='background: url("images/back.png") repeat;'>
	<div class="login-box" style="width: 270px !important;">
		<div class="login-logo" style="background: white;padding: 7px 0px 0px 0px;border-bottom: 1px solid;font-size: 40px;border-radius: 5px 5px 0 0;">
			<img src="images/logo.png" height="55px" width="200px" />
		</div>
		<?php if(isset($_SESSION['ERRMSG_ARR']) != ''){ ?>
		<div class="alert alert-success">
			<button data-dismiss="alert" class="close" type="button">&times;</button>
			<strong><?php echo $_SESSION['ERRMSG_ARR'];?></strong>
		</div>
		<?php } ?>
		<div class="login-box-body" style="background: white;height: 190px !important;padding-top: 20px;">
			<form id="login" action="login_exec.php" method="post">
				<input type="hidden" name="location_service" value="<?php echo $location_service;?>"/>
				<p id="location"></p>
				<div class="form-group">
					<input type="text" style="background: white;border-bottom: 3px solid;box-shadow: 0 0 0 0 !important;" class="form-control" name="username" id="login" placeholder="User ID" />
				</div>
				<div class="form-group">
					<input type="password" style="background: white;border-bottom: 3px solid;box-shadow: 0 0 0 0 !important;" class="form-control" name="password" id="password" placeholder="Password" />
				</div>
				<div class="form-group">
					<input class="btn btn-info form-control" type="submit" value="Login" style="pointer-events: all; cursor: pointer;background: #2db9ed !important;color: white !important;border: white 1px solid !important;border-radius: 5px !important;">
				</div>
			</form><br />
		</div><!--loginpanelinner-->
		<div class="login-footer" style="background: white;border-top: 1px solid #697687;border-radius: 0 0 5px 5px;">
			<?php echo $footer; ?>
		</div>
	</div><!--loginpanel-->
<?php
// remove all session variables
session_unset();

// destroy the session
session_destroy();
?>
</body>
</html>
<?php } else{ ?>
<div style="font-size: 30px;color: blue;font-weight: bold;text-align: center;margin-top: 70px;">Account has been</div> <div style="font-size: 40px;color: red;font-weight: bold;text-align: center;margin-top: 10px;">Terminated</div><div style="font-size: 20px;font-weight: bold;text-align: center;margin-top: 10px;">by</div> <div style="font-size: 25px;color: green;font-weight: bold;text-align: center;margin-top: 10px;">Asthatec</div><div style="font-size: 17px;color: green;font-weight: bold;text-align: center;margin-top: 10px;">[Contact: 01717561922]</div>
<?php } if($location_service == '1'){?>
<script>
var x = document.getElementById("location");

function getLocation() {
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
} else { 
    x.innerHTML = "Geolocation is not supported by this browser.";
}
}

function showPosition(position) {
x.innerHTML = "<input type='hidden' name='latitude' value='" + position.coords.latitude + "'><input type='hidden' name='longitude' value='" + position.coords.longitude + "'>";
}


window.onload=getLocation();
</script>
<?php } ?>