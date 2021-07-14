<?php
session_start();

//include file
include_once 'user_agent.php';
include_once 'web/company_info.php';

//create an instance of UserAgent class
$ua = new UserAgent();

//if site is accessed from mobile, then redirect to the mobile site.
if($ua->is_mobile()){
    header("Location:$mlink");
    exit;
}
else
{
    header("Location:$weblink");
}
// remove all session variables
session_unset();

// destroy the session
session_destroy();
?>
