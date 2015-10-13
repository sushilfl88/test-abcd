<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: ticket-dispute-users.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || (!isset($_GET['disputeUsers']) && !isset($_GET['changeState'])) || $SETTINGS->disputes=='no') {
  $HEADERS->err403(true);
}

// Access..
if (!in_array($cmd,$userAccess) && $MSTEAM->id!='1') {
  $HEADERS->err403(true);
}

// Enable/disable (Ajax)..
if (isset($_GET['changeState'])) {
  $MSTICKET->enableDisable();
  echo $JSON->encode(
   array('ok')
  ); 
  exit;
}

// Check digit..
mswCheckDigit($_GET['disputeUsers'],true);

// Load ticket data..
$SUPTICK = mswGetTableData('tickets','id',$_GET['disputeUsers']);

// Checks..
if (!isset($SUPTICK->id)) {
  $HEADERS->err404(true);
  exit;
}

// Load mail params
include(REL_PATH.'control/mail-data.php');

// Class..
include_once(PATH.'control/classes/class.accounts.php');
include_once(REL_PATH.'control/classes/class.accounts.php');
$MSACC               = new accounts();
$MSPORTAL            = new accountSystem();
$MSACC->settings     = $SETTINGS;
$MSPORTAL->settings  = $SETTINGS;

// Add users..
if (isset($_POST['add']) && isset($_GET['disputeUsers'])) {
  $count  = 0;
  $tickID = (int)$_GET['disputeUsers'];
  $TICKET = mswGetTableData('tickets','id',$tickID);
  $USER   = mswGetTableData('portal','id',$TICKET->visitorID);
  $new    = array();
  if (!empty($_POST['name']) && $tickID>0 && isset($TICKET->id) && isset($USER->id)) {
    // Batch loop visitors to be added..nuke duplicate emails..
	foreach (array_keys($_POST['email']) AS $k) {
	  $name  = $_POST['name'][$k];
	  $email = $_POST['email'][$k];
	  $send  = (isset($_POST['send'][$k]) ? 'yes' : 'no');
	  $priv  = (isset($_POST['priv'][$k]) ? 'yes' : 'no');
	  if ($name && mswIsValidEmail($email)) {
	    $PORTAL = mswGetTableData('portal','email',$email);
		// Does visitor exists? If not, add account..
		if (isset($PORTAL->id)) {
		  $pass   = '';
		  if ($PORTAL->language && file_exists(LANG_BASE_PATH.$PORTAL->language.'/mail-templates/admin-dispute-user-current.txt')) {
	        $mailT  = LANG_BASE_PATH.$PORTAL->language.'/mail-templates/admin-dispute-user-current.txt';
		    $pLang  = $PORTAL->language;
		  } else {
		    $mailT  = LANG_PATH.'admin-dispute-user-current.txt';
		  }
		  $userID = $PORTAL->id;
		} else {
		  $pass   = $MSPORTAL->ms_generate();
		  $mailT  = LANG_PATH.'admin-dispute-user-new.txt';
		  $userID = $MSACC->add(
		   array(
		    'name'     => $name,
			'email'    => $email,
			'userPass' => $pass,
			'enabled'  => 'yes',
			'timezone' => '',
			'ip'       => '',
			'notes'    => ''
		   )
		  );
		  $PORTAL        = new stdclass();
		  $PORTAL->email = $email;
		}
		// If this user isn`t in dispute already, add them..
		if ($PORTAL->email!=$USER->email && mswRowCount('disputes WHERE `ticketID` = \''.$tickID.'\' AND `visitorID` = \''.$userID.'\'')==0) {
		  $MSTICKET->addDisputeUser($tickID,$userID,$priv);
		  // Send notification if enabled..
		  if ($send=='yes') {
		    $MSMAIL->addTag('{NAME}', $name);
            $MSMAIL->addTag('{TITLE}', $TICKET->subject);
            $MSMAIL->addTag('{EMAIL}', $email);
            $MSMAIL->addTag('{PASSWORD}', $pass);
            $MSMAIL->addTag('{ID}', $tickID);
			$MSMAIL->addTag('{USER}', $USER->name);
			$MSMAIL->sendMSMail(
	         array(
	          'from_email' => $SETTINGS->email,
		      'from_name'  => $SETTINGS->website,
		      'to_email'   => $email,
		      'to_name'    => $name,
		      'subject'    => str_replace(
		       array('{website}','{ticket}'),
		       array($SETTINGS->website,mswTicketNumber($tickID)),
		       $emailSubjects['dispute']
		      ),
			  'replyto'    => array(
	           'name'      => $SETTINGS->website,
	           'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email) 
	          ),
		      'template'   => $mailT,
			  'language'   => (isset($pLang) ? $pLang : $SETTINGS->language),
			  'alive'      => 'yes'
	         )
	        );
		  }
		  $new[] = $name;
		  ++$count;
		}
	  }
	  // Add to ticket history log..
	  if ($count>0 && !empty($new)) {
	    $MSTICKET->historyLog(
		 $tickID,
		 str_replace(
		  array('{users}','{admin}'),
		  array(
		   implode(', ',$new),
		   $MSTEAM->name
		  ),
		  $msg_ticket_history['dis-user-add']
		 )
		);
	  }
	}
	// If something happened, lets inform the original ticket creator..
	if ($count>0 && !empty($new)) {
	  $pLang = '';
	  if ($USER->language && file_exists(LANG_BASE_PATH.$USER->language.'/mail-templates/html-wrapper.html')) {
	    $pLang  = $USER->language;
	  }
	  $MSMAIL->addTag('{NAME}', $USER->name);
      $MSMAIL->addTag('{TITLE}', $TICKET->subject);
      $MSMAIL->addTag('{PEOPLE}', implode(mswDefineNewline(),$new));
      $MSMAIL->addTag('{ID}', $tickID);
	  $MSMAIL->sendMSMail(
	   array(
	    'from_email' => $SETTINGS->email,
		'from_name'  => $SETTINGS->website,
		'to_email'   => $USER->email,
		'to_name'    => $USER->name,
		'subject'    => str_replace(
		 array('{website}','{ticket}'),
		 array($SETTINGS->website,mswTicketNumber($tickID)),
		 $emailSubjects['dispute-notify']
		),
		'replyto'    => array(
	     'name'      => $SETTINGS->website,
	     'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email) 
	    ),
		'template'   => LANG_PATH.'admin-dispute-notification.txt',
		'language'   => ($pLang ? $pLang : $SETTINGS->language),
		'alive'      => 'yes'
	   )
	  );
	}
  }
  $OK1 = true;
}

// Remove users..
if (isset($_POST['removeusers']) && isset($_GET['disputeUsers']) && USER_DEL_PRIV=='yes') {
  $MSTICKET->removeDisputeUsersFromTicket($msg_ticket_history['dis-user-rem']);
  $OK2 = true;
}
  
// Department check.. 
if (mswDeptPerms($MSTEAM->id,$SUPTICK->department,$userDeptAccess)=='fail') {
  $HEADERS->err403(true);
}
  
$title          = $msg_disputes8.' (#'.mswTicketNumber($_GET['disputeUsers']).')';
$loadJQAlertify = true;

include(PATH.'templates/header.php');
include(PATH.'templates/system/tickets/tickets-dispute-users.php');
include(PATH.'templates/footer.php');

?>
