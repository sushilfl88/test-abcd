<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: disabled.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || !defined('MS_PERMISSIONS')) {
  $HEADERS->err403();
}

// Auto enable..
$now = $MSDT->mswDateTimeDisplay(strtotime(date('Y-m-d',$MSDT->mswUTC())),'Y-m-d',$SETTINGS->timezone);
if ($SETTINGS->autoenable!='0000-00-00' && $SETTINGS->autoenable<=$now) {
  mysql_query("UPDATE `".DB_PREFIX."settings` SET
  `sysstatus`   = 'yes',
  `autoenable`  = '0000-00-00'
  ");
  header("Location: index.php");
  exit;
}

$title = $msg_offline;

include(PATH.'control/header.php');

$tpl  = new Savant3();
$tpl->assign('CHARSET', $msg_charset);
$tpl->assign('TITLE', ($title ? mswSpecialChars($title).': ' : '').str_replace('{website}',mswCleanData($SETTINGS->website),$msg_header).(LICENCE_VER!='unlocked' ? ' ('.$msg_script18.' '.$msg_script.')' : '').(LICENCE_VER!='unlocked' ? ' - Free Version' : '').(mswCheckBetaVersion()=='yes' ? ' - BETA VERSION' : ''));
$tpl->assign('TXT', array(
  $msg_offline,
  mswCleanData($SETTINGS->offlineReason)
 )
);

// Global vars..
include(PATH.'control/lib/global.php');

// Load template..
$tpl->display('content/'.MS_TEMPLATE_SET.'/system-disabled.tpl.php');

include(PATH.'control/footer.php');

?>