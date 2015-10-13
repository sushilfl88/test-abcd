<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: account-suspended.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || !defined('MS_PERMISSIONS')) {
  $HEADERS->err403();
}

$title = $msg_public_login5;

include(PATH.'control/header.php');

$tpl  = new Savant3();
$tpl->assign('CHARSET', $msg_charset);
$tpl->assign('TITLE', $msg_public_login5);
$tpl->assign('TXT', array(
  $msg_public_login5,
  mswCleanData($LI_ACC->reason)
 )
);

// Global vars..
include(PATH.'control/lib/global.php');

// Load template..
$tpl->display('content/'.MS_TEMPLATE_SET.'/account-suspended.tpl.php');

include(PATH.'control/footer.php');

?>