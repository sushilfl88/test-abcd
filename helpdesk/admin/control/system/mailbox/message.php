<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: message.php
  Description: Mailbox System

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || !defined('MAILBOX_LOADER')) {
  $HEADERS->err403(true);
}

// Add new message..
if (isset($_POST['compose']) && $_POST['subject'] && $_POST['message'] && !empty($_POST['staff'])) {
  foreach ($_POST['staff'] AS $staffID) {
    $id = $MSMB->add(
     array(
      'staff'    => $MSTEAM->id,
	  'to'       => $staffID,
	  'subject'  => $_POST['subject'],
	  'message'  => $_POST['message']
     )
    );
    // Proceed if added ok..
    // Are we sending notification to staff mailbox?
    if ($id>0 && $MSTEAM->mailCopy=='yes') {
      $USR  = mswGetTableData('users','id',$staffID,'','`name`,`email`,`email2`,`notify`');
	  if (isset($USR->name) && $USR->notify=='yes') {
	    $MSMAIL->addTag('{NAME}', $USR->name);
	    $MSMAIL->addTag('{SENDER}', $MSTEAM->name);
        // Send mail..
	    $MSMAIL->sendMSMail(
	     array(
	      'from_email' => $SETTINGS->email,
		  'from_name'  => $SETTINGS->website,
		  'to_email'   => $USR->email,
		  'to_name'    => $USR->name,
		  'subject'    => str_replace(
		   array('{website}','{user}'),
		   array(
		    $SETTINGS->website,
		    $MSTEAM->name
		   ),
		   $emailSubjects['mailbox-notify']
		  ),
		  'replyto'    => array(
	       'name'      => $SETTINGS->website,
	       'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email) 
	      ),
		  'template'   => LANG_PATH.'mailbox-notification.txt',
		  'language'   => $SETTINGS->language,
		  'add-emails' => $USR->email2
	     )
	    );
      }	  
    }
  }
  $OK = true;
}

// Add reply..
if (isset($_POST['add-reply']) && $_POST['message'] && $MID>0) {
  // Get other person in message..
  $OT = mswGetTableData('mailassoc','mailID',$MID,'AND `staffID` != \''.$MSTEAM->id.'\'');
  if (isset($OT->staffID)) {
    $id = $MSMB->reply(
     array(
      'staff'    => $MSTEAM->id,
	  'to'       => $OT->staffID,
	  'id'       => $MID,
	  'message'  => $_POST['message']
     )
    );
    // Proceed if added ok..
    // Are we sending notification to staff mailbox?
    if ($id>0 && $MSTEAM->mailCopy=='yes') {
      $USR  = mswGetTableData('users','id',$OT->staffID,'','`name`,`email`,`email2`,`notify`');
	  if (isset($USR->name) && $USR->notify=='yes') {
	    $MSMAIL->addTag('{NAME}', $USR->name);
	    $MSMAIL->addTag('{SENDER}', $MSTEAM->name);
		$MSMAIL->addTag('{TOPIC}', $_POST['subject']);
        // Send mail..
	    $MSMAIL->sendMSMail(
	     array(
	      'from_email' => $SETTINGS->email,
		  'from_name'  => $SETTINGS->website,
		  'to_email'   => $USR->email,
		  'to_name'    => $USR->name,
		  'subject'    => str_replace(
		   array('{website}','{user}'),
		   array(
		    $SETTINGS->website,
		    $MSTEAM->name
		   ),
		   $emailSubjects['mailbox-notify']
		  ),
		  'replyto'    => array(
	       'name'      => $SETTINGS->website,
	       'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email) 
	      ),
		  'template'   => LANG_PATH.'mailbox-notification-reply.txt',
		  'language'   => $SETTINGS->language,
		  'add-emails' => $USR->email2
	     )
	    );
      }	  
    }
  }
  $OK2 = true;
}

?>