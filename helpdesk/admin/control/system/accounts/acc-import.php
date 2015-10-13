<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: acc-import.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  $HEADERS->err403(true);
}

// Access..
if (!in_array($cmd,$userAccess) && $MSTEAM->id!='1') {
  $HEADERS->err403(true);
}

// Load mail params
include(REL_PATH.'control/mail-data.php');

// Class..
include_once(PATH.'control/classes/class.accounts.php');
$MSACC            = new accounts();
$MSACC->settings  = $SETTINGS;
$MSACC->timezones = $timezones;

// Import..
if (isset($_POST['process'])) {
  $lines  = ($_POST['lines'] ? str_replace(array('.',','),array(),$_POST['lines']) : '0');
  $del    = ($_POST['delimiter'] ? $_POST['delimiter'] : ',');
  $enc    = ($_POST['enclosed'] ? $_POST['enclosed'] : '"');
  $data   = $MSACC->import($lines,$del,$enc);
  // Send emails..
  if (count($data)>0 && isset($_POST['welcome'])) {
    foreach ($data AS $k => $v) {
      // Message tags..
	  $MSMAIL->addTag('{NAME}', $v[0]);
	  $MSMAIL->addTag('{EMAIL}', $v[1]);
	  $MSMAIL->addTag('{PASSWORD}', $v[2]);
	  // Send..
	  $MSMAIL->sendMSMail(
	   array(
	    'from_email' => $SETTINGS->email,
	    'from_name'  => $SETTINGS->website,
	    'to_email'   => $v[1],
	    'to_name'    => $v[0],
	    'subject'    => str_replace(
	    array('{website}'),
	    array($SETTINGS->website),
	     $emailSubjects['add']
	    ),
		'replyto'    => array(
	     'name'      => $SETTINGS->website,
	     'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email) 
	    ),
	    'template'   => LANG_PATH.'admin-add-account.txt',
		'language'   => $SETTINGS->language
	   )
	  );
	}
  }
  $count  = count($data);
  $OK     = true;
}

$title = $msg_adheader59;

include(PATH.'templates/header.php');
include(PATH.'templates/system/accounts/import.php');
include(PATH.'templates/footer.php');

?>