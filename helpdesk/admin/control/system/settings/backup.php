<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: backup.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  $HEADERS->err403(true);
}

include(REL_PATH.'control/classes/class.backup.php');

// Access..
if (!in_array($cmd,$userAccess) && $MSTEAM->id!='1') {
  $HEADERS->err403(true);
} 

// Load mail params
include(REL_PATH.'control/mail-data.php');

// Classes..
include_once(REL_PATH.'control/classes/class.download.php');
$MSDL = new msDownload();

// Backup..
if (isset($_POST['process'])) {
  if (!is_writeable(REL_PATH.'backups') || !is_dir(REL_PATH.'backups')) {
    die ('"<b>'.REL_PATH.'backups'.'</b>" folder must exist and be writeable. Please check directory and permissions..');
  }
  $time      = date('H:i:s',$MSDT->mswTimeStamp());
  $download  = (isset($_POST['download']) ? 'yes' : 'no');
  $compress  = (isset($_POST['compress']) ? 'yes' : 'no');
  // Force download if off and no emails..
  if ($download=='no' && $_POST['emails']=='') {
    $download = 'yes';
  }
  // File path..
  if ($compress=='yes') {
    $filepath  = REL_PATH.'backups/'.$msg_script33.'-'.date('dMY',$MSDT->mswTimeStamp()).'-'.date('His',$MSDT->mswTimeStamp()).'.gz';
  } else {
    $filepath  = REL_PATH.'backups/'.$msg_script33.'-'.date('dMY',$MSDT->mswTimeStamp()).'-'.date('His',$MSDT->mswTimeStamp()).'.sql';
  }
  // Save backup..
  $BACKUP            = new dbBackup($filepath,($compress=='yes' ? true : false));
  $BACKUP->settings  = $SETTINGS;
  $BACKUP->doDump();
  // Copy email addresses if set..
  if (trim($_POST['emails']) && file_exists($filepath)) {
    // Update backup emails..
	$MSSET->updateBackupEmails();
	// Check how many emails we have..
    $emails = array();
    if (strpos($_POST['emails'],',')!==false) {
      $emails   = array_map('trim',explode(',',$_POST['emails']));
    } else {
      $emails[] = $_POST['emails'];
    }
	// Message tags..
	$MSMAIL->addTag('{HELPDESK}',mswCleanData($SETTINGS->website));
	$MSMAIL->addTag('{DATE_TIME}',$MSDT->mswDateTimeDisplay($MSDT->mswTimeStamp(),$SETTINGS->dateformat).' @ '.$MSDT->mswDateTimeDisplay($MSDT->mswTimeStamp(),$SETTINGS->timeformat));
	$MSMAIL->addTag('{VERSION}',SCRIPT_VERSION);
	$MSMAIL->addTag('{FILE}',basename($filepath));
	$MSMAIL->addTag('{SCRIPT}',SCRIPT_NAME);
	$MSMAIL->addTag('{SIZE}',mswFileSizeConversion(@filesize($filepath)));
	// Send emails..
    foreach ($emails AS $recipient) {
	  $MSMAIL->attachments[$filepath] = basename($filepath);
	  $MSMAIL->sendMSMail(
	   array(
	    'from_email' => $SETTINGS->email,
		'from_name'  => $SETTINGS->website,
		'to_email'   => $recipient,
		'to_name'    => $recipient,
		'subject'    => str_replace(
		 array('{website}','{date}','{time}'),
		 array(
		  $SETTINGS->website,
		  $MSDT->mswDateTimeDisplay($MSDT->mswTimeStamp(),$SETTINGS->dateformat),
		  $time
		 ),
		 $emailSubjects['db-backup']
		),
		'replyto'    => array(
	     'name'      => $SETTINGS->website,
	     'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email) 
	    ),
		'template'   => LANG_PATH.'backup.txt',
		'language'   => $SETTINGS->language,
		'alive'      => 'yes'
	   )
	  );
	}
  }
  // Download file if applicable..
  if ($download=='yes' && file_exists($filepath)) {
    $MSDL->dl($filepath,'text/plain');
  } else {
    // Clear file from server..
    if (file_exists($filepath)) {
	  @unlink($filepath);
	}
  }
  $OK = true;
}
     
$title = $msg_adheader30;
  
include(PATH.'templates/header.php');
include(PATH.'templates/system/settings/backup.php');
include(PATH.'templates/footer.php');

?>