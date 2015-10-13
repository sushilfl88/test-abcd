<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: create-ticket.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || !defined('MS_PERMISSIONS')) {
  $HEADERS->err403();
}

// Check log in..
if ($SETTINGS->createPref=='yes' && MS_PERMISSIONS=='guest') {
  $_SESSION['redirectPage'] = 'open';
  header("Location:index.php?p=login");
  exit;
}

// Check smtp..
if ($SETTINGS->smtp_host=='') {
  die('SMTP not enabled in settings. In the admin control panel, go to "Settings &amp; Tools > Settings > Other Options > SMTP". Enter valid details, then refresh this page.');
}

// Load account globals..
include(PATH.'control/system/accounts/account-global.php');

// Reset captcha if we are logged in..
if ($SETTINGS->enCapLogin=='yes' && MS_PERMISSIONS!='guest' && isset($LI_ACC->name)) {
  $SETTINGS->recaptchaPublicKey  = '';
  $SETTINGS->recaptchaPrivateKey = '';
}

// Create ticket..
if (isset($_POST['process'])) {
  define('T_PERMS',1);
  include(PATH.'control/system/accounts/account-ticket-create.php');
}

$title = $msg_main2;

include(PATH.'control/header.php');

$tpl  = new Savant3();
$tpl->assign('TXT',
 array(
  $msg_main2,
  $msg_main17,
  $msg_newticket3,
  $msg_newticket4,
  $msg_newticket15,
  $msg_newticket6,
  $msg_newticket8,
  $msg_newticket5,
  $msg_viewticket78,
  $msg_newticket37,
  $msg_newticket38,
  $attachRestrictions,
  $msg_main2,
  $msg_newticket43,
  $msg_viewticket101,
  $msg_public_ticket4,
  $msg_public_ticket5,
  $msg_public_ticket9,
  $msg_public_ticket10,
  $bb_code_buttons,
  $msg_public_create11
 )
);
$tpl->assign('RECAPTCHA', ($SETTINGS->recaptchaPublicKey && $SETTINGS->recaptchaPrivateKey ? $MSYS->recaptcha() : ''));
$tpl->assign('DEPARTMENTS', $MSYS->ticketDepartments((isset($_POST['dept']) ? (int)$_POST['dept'] : '')));
$tpl->assign('PRIORITY_LEVELS', $ticketLevelSel);
$tpl->assign('LOGGED_IN', (MS_PERMISSIONS!='guest' && isset($LI_ACC->name) ? 'yes' : 'no'));
$tpl->assign('SYSTEM_MESSAGE', (!empty($eFields) ? str_replace('{count}',count($eFields),$msg_public_ticket8) : ''));

// Post fields..will populate on refresh..
$tpl->assign('POST', array(
  'name'     => (isset($_POST['name']) ? mswSpecialChars($_POST['name']) : ''),
  'email'    => (isset($_POST['email']) ? mswSpecialChars($_POST['email']) : ''),
  'subject'  => (isset($_POST['subject']) ? mswSpecialChars($_POST['subject']) : ''),
  'priority' => (isset($_POST['priority']) ? mswSpecialChars($_POST['priority']) : ''),
  'comments' => (isset($_POST['comments']) ? mswSpecialChars($_POST['comments']) : '')
 )
); 

// Custom fields for form refresh..
$tpl->assign('CFIELDS', (isset($_POST['dept']) && $_POST['dept']>0 ? $MSFIELDS->build('ticket',$_POST['dept']) : ''));

// Field flags for errors..
$tpl->assign('EFIELDS', $eFields);

// Global vars..
include(PATH.'control/lib/global.php');

// Load template..
$tpl->display('content/'.MS_TEMPLATE_SET.'/account-create-ticket.tpl.php');

include(PATH.'control/footer.php'); 

?>