<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: faq-search.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

// Check var and parent load..
if (!defined('PARENT') || !isset($_GET['q']) || !defined('MS_PERMISSIONS') || $SETTINGS->kbase=='no') {
  $HEADERS->err403();
}

// Load the skip words array..
include(PATH.'control/skipwords.php');

// Variables..
$limitvalue    = $page * $SETTINGS->quePerPage - ($SETTINGS->quePerPage);
$pageNumbers   = '';
$html          = '';
$title         = $msg_pkbase;
$dataCount     = 0;

// Build search query..
$SQL = '';
if ($_GET['q']) {
  $chop = array_map('trim',explode(' ',$_GET['q']));
  if (!empty($chop)) {
    foreach ($chop AS $word) {
	  if (!in_array($word,$searchSkipWords)) {
	    $SQL .= (!$SQL ? 'WHERE (' : 'OR (')."`question` LIKE '%".mswCleanData(mswSafeImportString($word))."%' OR `answer` LIKE '%".mswCleanData(mswSafeImportString($word))."%')";
	  }
	}
  }
  // Are we searching for anything..
  if ($SQL) {
    $html      = $FAQ->questions(0,$limitvalue,$SETTINGS,array($SQL,'no'));
	$dataCount = $FAQ->questions(0,$limitvalue,$SETTINGS,array($SQL,'yes'));
  }
}

// Pagination..
if ($dataCount>$SETTINGS->quePerPage) {
  define('PER_PAGE',$SETTINGS->quePerPage);
  $PTION       = new pagination($dataCount,'?q='.urlencode($_GET['q']).mswQueryParams(array('q','p','next')).'&amp;next=');
  $pageNumbers = $PTION->display();
}

// Header..
include(PATH.'control/header.php');

// Template initialisation..
$tpl  = new Savant3();
$tpl->assign('TXT',
 array(
  $msg_pkbase,
  $msg_header4,
  $msg_kbase53
 )
);
$tpl->assign('SCH_TXT', $msg_header4);
$tpl->assign('FAQ',$html);
$tpl->assign('RESULTS', $dataCount);
$tpl->assign('MSDT', $MSDT);
$tpl->assign('PAGES', $pageNumbers);

// Global vars..
include(PATH.'control/lib/global.php');

// Global vars..
$tpl->display('content/'.MS_TEMPLATE_SET.'/faq-search.tpl.php');

// Footer..
include(PATH.'control/footer.php');

?>