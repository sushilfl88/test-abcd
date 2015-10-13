<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: ticket-assign.php
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

// Delete..
if (isset($_POST['delete']) && USER_DEL_PRIV=='yes') {
  $_POST['ticket'] = $_POST['id'];
  $MSTICKET->deleteTickets();
  $OK1 = true;
}

// Assign..
if (isset($_POST['users'])) {
  if (!empty($_POST['id'])) {
    $userNotify  = array();
	$tickets     = array();
    foreach ($_POST['id'] AS $ID) {
	  if (!empty($_POST['users'][$ID])) {
	    // Ticket information..
		$SUPTICK = mswGetTableData('tickets','id',$ID);
		// Array of ticket subjects assigned to users..
		foreach ($_POST['users'][$ID] AS $userID) {
		  $tickets[$userID][] = str_replace(
		   array('{id}','{subject}'),
		   array(
		    mswTicketNumber($ID),
		    $SUPTICK->subject
		   ),
		   $msg_assign7
		  );
		  $userNotify[] = $userID;
		}
        // Update ticket..
        $MSTICKET->ticketUserAssign(
		 $ID,
		 implode(',',$_POST['users'][$ID]),
		 $msg_ticket_history['assign']
		); 
	  }
	}
  }
  // Email users..
  if (!empty($userNotify) && !empty($tickets) && isset($_POST['mail'])) {
    $q       = mysql_query("SELECT `id`,`name`,`email`,`email2` FROM `".DB_PREFIX."users`
               WHERE `id` IN(".implode(',',$userNotify).")
			   GROUP BY `id`
               ORDER BY `name`
               ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    while ($USERS = mysql_fetch_object($q)) {
      $MSMAIL->addTag('{ASSIGNEE}', $MSTEAM->name);
      $MSMAIL->addTag('{NAME}', $USERS->name);
      $MSMAIL->addTag('{TICKETS}', trim(implode(mswDefineNewline(),$tickets[$USERS->id])));
	  // Send mail..
	  $MSMAIL->sendMSMail(
	   array(
	    'from_email' => ($MSTEAM->emailFrom ? $MSTEAM->emailFrom : $MSTEAM->email),
	    'from_name'  => ($MSTEAM->nameFrom ? $MSTEAM->nameFrom : $MSTEAM->name),
		'to_email'   => $USERS->email,
		'to_name'    => $USERS->name,
		'subject'    => str_replace(
		 array('{website}','{user}'),
		 array(
		  $SETTINGS->website,
		  $MSTEAM->name
		 ),
		 $emailSubjects['ticket-assign']
		),
		'replyto'    => array(
	     'name'      => $SETTINGS->website,
	     'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email) 
	    ),
		'template'   => LANG_PATH.'admin-ticket-assign.txt',
		'language'   => $SETTINGS->language,
		'alive'      => 'yes',
		'add-emails' => $USERS->email2
	   )
	  );
	}
  }
  $OK2 = true;
}
   
$title           = $msg_adheader32;
$loadJQAlertify  = true;
$loadJQNyroModal = true;

include(PATH.'templates/header.php');
include(PATH.'templates/system/tickets/tickets-assign.php');
include(PATH.'templates/footer.php');

?>