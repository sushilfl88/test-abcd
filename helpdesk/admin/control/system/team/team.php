<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: team.php
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

// Include relevant classes..
include_once(REL_PATH.'control/classes/class.accounts.php');
$MSPORTAL = new accountSystem();

// Check email..
if (isset($_POST['checkEntered'])) {
  $msg = '';
  if (!mswIsValidEmail($_POST['checkEntered'])) {
    $ret = 'exists';
  } else {
    $ret = $MSUSERS->check();
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

if (isset($_POST['process'])) {
  if (trim($_POST['name']) && mswIsValidEmail($_POST['email'])) {
    if ($_POST['accpass']=='') {
	  $_POST['accpass'] = $MSPORTAL->generate();
	}
    $MSUSERS->add();
    // Send mail..
    if (isset($_POST['welcome'])) {
	  // Message tags..
	  $MSMAIL->addTag('{NAME}', mswCleanData($_POST['name']));
	  $MSMAIL->addTag('{EMAIL}', $_POST['email']);
	  $MSMAIL->addTag('{PASSWORD}', $_POST['accpass']);
	  // Send..
	  $MSMAIL->sendMSMail(
	   array(
	    'from_email' => $SETTINGS->email,
		'from_name'  => mswCleanData($SETTINGS->website),
		'to_email'   => $_POST['email'],
		'to_name'    => $_POST['name'],
		'subject'    => str_replace(
		 array('{website}'),
		 array($SETTINGS->website
		 ),
		 $emailSubjects['team-account']
        ),
		'replyto'    => array(
	     'name'      => $SETTINGS->website,
	     'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email) 
	    ),
		'template'   => LANG_PATH.'admin-new-team.txt',
		'language'   => $SETTINGS->language
	   )
	  );
    }
    $OK1 = true;
  }
}
  
if (isset($_POST['update'])) {
  if (trim($_POST['name']) && mswIsValidEmail($_POST['email'])) {
    // Check edit for global user..
    if ($_GET['edit']=='1' && $MSTEAM->id!='1') {
      $HEADERS->err403(true);
    }
    $MSUSERS->update($MSTEAM->id);
    $OK2 = true;
  }
}
  
$title            = (isset($_GET['edit']) ? $msg_user14 : $msg_adheader57);
$loadJQNyroModal  = true;
  
include(PATH.'templates/header.php');
include(PATH.'templates/system/team/team.php');
include(PATH.'templates/footer.php');

?>