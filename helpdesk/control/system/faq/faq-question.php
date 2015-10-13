<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: faq-question.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

// Check var and parent load..
if (!defined('PARENT') || !isset($_GET['a']) || !defined('MS_PERMISSIONS') || $SETTINGS->kbase=='no') {
  $HEADERS->err403();
}

// Security check..
mswCheckDigit($_GET['a']);

$QUE = mswGetTableData('faq','id',(int)$_GET['a'],'AND `enFaq` = \'yes\'','*');

if (!isset($QUE->question)) {
  $HEADERS->err404();
}

// Variables..
$title = $QUE->question.' - '.$msg_adheader17;
$subt  = $msg_header8;
$cky   = array();

// Check for category/search params..
if (isset($_GET['c']) && (int)$_GET['c']>0) {
  $CAT = mswGetTableData('categories','id',(int)$_GET['c'],'AND `enCat` = \'yes\'','`name`,`subcat`');
  if (isset($CAT->name)) {
    if (isset($CAT->subcat) && $CAT->subcat>0) {
      define('IS_SUB',$CAT->subcat);
    }
    $subt = $msg_header8.': '.$CAT->name;
  }
} else {
  if (isset($_GET['q'])) {
    $subt = $msg_header8.': '.$msg_pkbase3;
  }
}

// Header..
include(PATH.'control/header.php');

// Cookie set..
if (isset($_COOKIE[md5(SECRET_KEY).COOKIE_NAME])) {
  $cky = unserialize($_COOKIE[md5(SECRET_KEY).COOKIE_NAME]);
}

// Template initialisation..
$tpl  = new Savant3();
$tpl->assign('TXT',
 array(
  $subt,
  $msg_kbase52,
  $msg_kbase54,
  $msg_pkbase18
 )
);
$tpl->assign('SCH_TXT', $msg_header4);
$tpl->assign('ANSWER', (array)$QUE);
$tpl->assign('ANSWER_TXT', $MSPARSER->mswTxtParsingEngine($QUE->answer));
$tpl->assign('MSDT', $MSDT);
$tpl->assign('ATTACHMENTS', $FAQ->attachments($SETTINGS));
$tpl->assign('FAQ_COOKIE_SET', (in_array($_GET['a'],$cky) ? 'yes' : 'no'));

// Global vars..
include(PATH.'control/lib/global.php');

// Load template..
$tpl->display('content/'.MS_TEMPLATE_SET.'/faq-question.tpl.php');

// Footer..
include(PATH.'control/footer.php');

?>