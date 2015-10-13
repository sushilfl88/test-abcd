<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: faq-cat.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

// Check var and parent load..
if (!defined('PARENT') || !defined('MS_PERMISSIONS') || $SETTINGS->kbase=='no') {
  $HEADERS->err403();
}

// Download attachment..
if (isset($_GET['fattachment'])) {
  include(PATH.'control/classes/class.download.php');
  $D = new msDownload();
  $D->faqAttachment((int)$_GET['fattachment'],$SETTINGS);
  exit;
}

// Voting system..
if ($SETTINGS->enableVotes=='yes' && isset($_GET['v']) && isset($_GET['vote'])) {
  $FAQ->vote($SETTINGS);
  echo $MSJSON->encode(
   array(
    'response' => $msg_kbase55
   )
  );
  exit;
}

// Check var and parent load..
if (!isset($_GET['c']) || !defined('MS_PERMISSIONS')) {
  $HEADERS->err403();
}

// Security check..
mswCheckDigit($_GET['c']);

// Load category..
$CAT = mswGetTableData('categories','id',(int)$_GET['c'],'AND `enCat` = \'yes\'');

// 404 if not found..
if (!isset($CAT->name)) {
  $HEADERS->err404();
}

// Variables..
$limitvalue    = $page * $SETTINGS->quePerPage - ($SETTINGS->quePerPage);
$pageNumbers   = '';
$title         = $CAT->name.' - '.$msg_adheader17;
$dataCount     = mswRowCount('faqassign LEFT JOIN `'.DB_PREFIX.'faq` ON `'.DB_PREFIX.'faq`.`id` = `'.DB_PREFIX.'faqassign`.`question` 
	             WHERE `itemID` = \''.(int)$_GET['c'].'\' AND `desc` = \'category\' AND `'.DB_PREFIX.'faq`.`enFaq` = \'yes\'');

// Check if sub category..
if ($CAT->subcat>0) {
  $SUB = mswGetTableData('categories','id',$CAT->subcat);
  if (isset($SUB->name)) {
    define('IS_SUB',$CAT->subcat);
    $title  = mswCleanData($CAT->name).' ('.mswCleanData($SUB->name).') - '.$msg_adheader17;
  }
}

// Pagination..
if ($dataCount>$SETTINGS->quePerPage) {
  define('PER_PAGE',$SETTINGS->quePerPage);
  $PTION       = new pagination($dataCount,'?c='.(int)$_GET['c'].mswQueryParams(array('c','p','next')).'&amp;next=');
  $pageNumbers = $PTION->display();
}

// Header..
include(PATH.'control/header.php');

// Template initialisation..
$tpl  = new Savant3();
$tpl->assign('TXT',
 array(
  $msg_header8,
  $msg_header4
 )
);
$tpl->assign('SCH_TXT', $msg_header4);
$tpl->assign('FAQ',$FAQ->questions($CAT->id,$limitvalue,$SETTINGS));
$tpl->assign('PARENT',(array)$CAT);
$tpl->assign('MSDT', $MSDT);
$tpl->assign('CHILD',(isset($SUB->id) ? (array)$SUB : array()));
$tpl->assign('PAGES', $pageNumbers);

// Global vars..
include(PATH.'control/lib/global.php');

// Load template..
$tpl->display('content/'.MS_TEMPLATE_SET.'/faq-cat.tpl.php');

// Footer..
include(PATH.'control/footer.php');

?>