<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: footer.php
  Description: Footer

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || !defined('MS_PERMISSIONS')) {
  $HEADERS->err404();
}

// Footer..
$footer  = '<b>'.$msg_script3.'</b>: <a href="http://www.'.SCRIPT_URL.'" title="'.SCRIPT_NAME.'" onclick="window.open(this);return false">'.SCRIPT_NAME.'</a> ';
$footer .= '&copy;2005-'.date('Y',$MSDT->mswTimeStamp()).' <a href="http://www.maianscriptworld.co.uk" onclick="window.open(this);return false" title="Maian Script World">Maian Script World</a>. '.$msg_script12.'.';

// Commercial version..
if (LICENCE_VER=='unlocked') {
  $footer = $SETTINGS->publicFooter;
  if ($footer=='') {
    $footer = $msg_script34;
  }
}

$tpl  = new Savant3();
$tpl->assign('FOOTER', $footer);

// Global vars..
include(PATH.'control/lib/global.php');

// Load template..
$tpl->display('content/'.MS_TEMPLATE_SET.'/footer.tpl.php');

?>