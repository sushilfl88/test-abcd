<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: account-login.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || !defined('MS_PERMISSIONS')) {
  $HEADERS->err403();
}

// Logout..
if (isset($_GET['lo'])) {
  $_SESSION[md5(SECRET_KEY).'_msw_support'] = '';
  unset($_SESSION[md5(SECRET_KEY).'_msw_support'],$_SESSION['portalEmail']);
  header("Location: ?p=login");
  exit;
}

if (MS_PERMISSIONS!='guest') {
  header("Location: ?p=dashboard");
  exit;
}

$title = $msg_public_login;

include(PATH.'control/header.php'); 

// Show..
$tpl  = new Savant3();
$tpl->assign('TXT',
 array(
  $msg_public_login,
  $msg_public_login2,
  $msg_main3,
  $msg_main4,
  $msg_public_login3,
  $msg_main9,
  $msg_main5
 )
);

// Global vars..
include(PATH.'control/lib/global.php');

// Load template..
$tpl->display('content/'.MS_TEMPLATE_SET.'/account-login.tpl.php');

include(PATH.'control/footer.php');  

?>
