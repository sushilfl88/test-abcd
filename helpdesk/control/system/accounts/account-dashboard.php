<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: account-dashboard.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || !defined('MS_PERMISSIONS')) {
  $HEADERS->err403();
}

// Check log in..
if (MS_PERMISSIONS=='guest' || !isset($LI_ACC->id)) {
  header("Location:index.php?p=login");
  exit;
}

$title = $msg_header3;
$tz    = ($LI_ACC->timezone ? $LI_ACC->timezone : $SETTINGS->timezone);

include(PATH.'control/header.php'); 

// Show..
$tpl  = new Savant3();
$tpl->assign('TXT',
 array(
  $msg_header13,
  $MSDT->mswDateTimeDisplay(strtotime(date('Y-m-d',$MSDT->mswUTC())),$SETTINGS->dateformat,$tz),
  $msg_public_dashboard1,
  $msg_public_dashboard2,
  $msg_public_dashboard3,
  $msg_public_dashboard4,
  $msg_public_dashboard5,
  str_replace('{name}',mswSpecialChars($LI_ACC->name),$msg_public_dashboard11),
  $msg_public_dashboard12
 )
); 
$tpl->assign('TICKETS', $MSTICKET->ticketList(MS_PERMISSIONS,array(0,99999),false,'AND `ticketStatus` = \'open\''));
$tpl->assign('DISPUTES', $MSTICKET->disputeList(MS_PERMISSIONS,$LI_ACC->id,array(0,99999),false,'AND `ticketStatus` = \'open\''));

// Global vars..
include(PATH.'control/lib/global.php');

// Load template..
$tpl->display('content/'.MS_TEMPLATE_SET.'/account-dashboard.tpl.php');

include(PATH.'control/footer.php');  

?>
