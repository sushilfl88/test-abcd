<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: global.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || !defined('MS_PERMISSIONS')) {
  $HEADERS->err403();
}

// Global template vars. Available in ALL .tpl.php files..
$tpl->assign('SETTINGS', $SETTINGS);
$tpl->assign('LOGGED_IN', (MS_PERMISSIONS!='guest' && isset($LI_ACC->id) ? 'yes' : 'no'));
$tpl->assign('USER_DATA', (isset($LI_ACC->id) ? $LI_ACC : ''));

?>
