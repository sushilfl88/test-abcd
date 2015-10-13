<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: accounts.php
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

// Check email..
if (isset($_POST['checkEntered'])) {
  $msg = '';
  if (!mswIsValidEmail($_POST['checkEntered'])) {
    $ret = 'exists';
	$msg = $msg_main13;
  } else {
    $ret = $MSACC->check();
	if ($ret=='exists') {
	  $msg = $msg_portal32;
	}
  }
  echo $JSON->encode(
   array(
    'response' => $ret,
	'message'  => $msg
   )
  ); 
  exit;
}

// Add..
if (isset($_POST['process'])) {
  if (trim($_POST['name'])) {
    $MSACC->add();
	// Send welcome email?
	if (isset($_POST['welcome'])) {
	  // Message tags..
	  $MSMAIL->addTag('{NAME}', $_POST['name']);
	  $MSMAIL->addTag('{EMAIL}', $_POST['email']);
	  $MSMAIL->addTag('{PASSWORD}', $_POST['userPass']);
	  // Send..
	  $MSMAIL->sendMSMail(
	   array(
	    'from_email' => $SETTINGS->email,
		'from_name'  => $SETTINGS->website,
		'to_email'   => $_POST['email'],
		'to_name'    => $_POST['name'],
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
		'language'   => $_POST['language']
	   )
	  );
	}
    $OK1 = true;
  }
}
   
// Update..
if (isset($_POST['update'])) {
  if (trim($_POST['name'])) {
    $MSACC->update();
	// Anything to move?
	if (isset($_POST['dest_email']) && mswIsValidEmail($_POST['dest_email'])) {
	  $MSACC->move($_POST['old_email'],$_POST['dest_email']);
	}
    $OK2 = true;
  }
}

$title          = (isset($_GET['edit']) ? $msg_accounts6 : $msg_adheader39);
$loadJQAlertify = true;
  
include(PATH.'templates/header.php');
include(PATH.'templates/system/accounts/accounts.php');
include(PATH.'templates/footer.php');

?>