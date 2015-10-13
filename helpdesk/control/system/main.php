<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  This File: main.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || !defined('MS_PERMISSIONS')) {
  $HEADERS->err403();
}

// Show BBCode help..
if (isset($_GET['bbcode'])) {

$tpl  = new Savant3();
$tpl->assign('CHARSET', $msg_charset);
$tpl->assign('LANG', $html_lang);
$tpl->assign('DIR', $lang_dir);
$tpl->assign('TITLE', ($title ? mswSpecialChars($title).': ' : '').$msg_bbcode.': '.str_replace('{website}',mswCleanData($SETTINGS->website),$msg_header).(mswCheckBetaVersion()=='yes' ? ' - BETA VERSION' : ''));
$tpl->assign('TOP_BAR_TITLE', str_replace('{website}',mswCleanData($SETTINGS->website),$msg_header));

// Global vars..
include(PATH.'control/lib/global.php');

// Load template..
$tpl->display('content/'.MS_TEMPLATE_SET.'/bb-code-help.tpl.php');

} else {

include(PATH.'control/header.php');

$tpl  = new Savant3();
$tpl->assign('TXT',array(
  $msg_public_main,
  str_replace('{name}',mswCleanData($SETTINGS->website),$msg_public_main2),
  str_replace('{count}',$SETTINGS->popquestions,$msg_main10),
  str_replace('{count}',$SETTINGS->popquestions,$msg_public_main3)
 )
);
$tpl->assign('POPULAR', ($SETTINGS->kbase=='yes' ? $FAQ->questions(0,0,$SETTINGS,array(),'`'.DB_PREFIX.'faq`.`kviews` DESC','GROUP BY `'.DB_PREFIX.'faqassign`.`question`') : ''));
$tpl->assign('LATEST', ($SETTINGS->kbase=='yes' ? $FAQ->questions(0,0,$SETTINGS,array(),'`'.DB_PREFIX.'faq`.`ts` DESC','GROUP BY `'.DB_PREFIX.'faqassign`.`question`') : ''));

// Global vars..
include(PATH.'control/lib/global.php');

// Load template..
$tpl->display('content/'.MS_TEMPLATE_SET.'/main.tpl.php');

include(PATH.'control/footer.php');

}

?>