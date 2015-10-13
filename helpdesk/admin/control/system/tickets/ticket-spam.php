<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: ticket-spam.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  $HEADERS->err403(true);
}

// Access..
if (!in_array($cmd,$userAccess) && $MSTEAM->id!='1') {
  $HEADERS->err403(true);
}
  
// Department check for filter.. 
if (isset($_GET['dept'])) {
  if (mswDeptPerms($MSTEAM->id,$_GET['dept'],$userDeptAccess)=='fail') {
    $HEADERS->err403(true);
  }
}

// Load the b8 class..
include(REL_PATH.'control/lib/b8/call_b8.php');

// Load mail params
include(REL_PATH.'control/mail-data.php');

if (isset($_POST['delete']) && USER_DEL_PRIV=='yes') {
  $_POST['ticket'] = $_POST['id'];
  if ($B8_CFG->learning=='yes') {
    $MSTICKET->spamLearning('spam',$MSB8);
  }
  $MSTICKET->deleteTickets();
  $OK1             = true;
}
  
if (isset($_POST['accept'])) {
  $_POST['ticket'] = $_POST['id'];
  if ($B8_CFG->learning=='yes') {
    $MSTICKET->spamLearning('ham',$MSB8);
  }
  $rows            = $MSTICKET->notSpam();
  // If rows were affected, write log for each ticket..
  if ($rows>0) {
    foreach ($_POST['ticket'] AS $tID) {
	  $replyToAddr = '';
	  $MSTICKET->historyLog(
	   $tID,
	   str_replace(
	    array('{user}'),
	    array($MSTEAM->name),
	    $msg_ticket_history['ticket-spam-accept']
	   )
	  );
	  // Load data..
	  $ST     = mswGetTableData('tickets','id',$tID);
	  $PORTAL = mswGetTableData('portal','id',$ST->visitorID);
	  // Mail tags..
	  $MSMAIL->addTag('{ACC_NAME}', $PORTAL->name);
	  $MSMAIL->addTag('{ACC_EMAIL}', $PORTAL->email);
	  $MSMAIL->addTag('{SUBJECT}', $MSBB->cleaner($ST->subject));
	  $MSMAIL->addTag('{TICKET}', mswTicketNumber($tID));
	  $MSMAIL->addTag('{DEPT}', $MSYS->department($ST->department,$msg_script30));
	  $MSMAIL->addTag('{PRIORITY}', $MSYS->levels($ST->priority));
	  $MSMAIL->addTag('{STATUS}', $msg_showticket23);
	  $MSMAIL->addTag('{COMMENTS}', $MSBB->cleaner($ST->comments));
	  $MSMAIL->addTag('{ATTACHMENTS}', $MSTICKET->attachList($tID));
	  $MSMAIL->addTag('{ID}', $tID);
	  $MSMAIL->addTag('{CUSTOM}', 'N/A');
	  // Is this ticket going to be assigned?
	  if ($ST->assignedto!='waiting') {
	    $qU = mysql_query("SELECT `".DB_PREFIX."users`.`name` AS `teamName`,`email`,`email2` FROM `".DB_PREFIX."userdepts`
              LEFT JOIN `".DB_PREFIX."departments`
              ON `".DB_PREFIX."userdepts`.`deptID`  = `".DB_PREFIX."departments`.`id`
              LEFT JOIN `".DB_PREFIX."users`
              ON `".DB_PREFIX."userdepts`.`userID`  = `".DB_PREFIX."users`.`id`
              WHERE `deptID`  = '{$ST->department}'
              AND `userID`   != '1'
              AND `notify`    = 'yes'
              GROUP BY `email`
			  ORDER BY `".DB_PREFIX."users`.`name`
              ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	    while ($STAFF = mysql_fetch_object($qU)) {
		 $MSMAIL->addTag('{NAME}', $STAFF->teamName);
		 $MSMAIL->sendMSMail(
	      array(
		   'from_email' => ($MSTEAM->emailFrom ? $MSTEAM->emailFrom : $MSTEAM->email),
	       'from_name'  => ($MSTEAM->nameFrom ? $MSTEAM->nameFrom : $MSTEAM->name),
		   'to_email'   => $STAFF->email,
		   'to_name'    => $STAFF->teamName,
		   'subject'    => str_replace(
		    array('{website}','{ticket}'),
		    array(
		     $SETTINGS->website,
			 mswTicketNumber($tID)
		    ),
		    $emailSubjects['new-ticket']
		   ),
		   'replyto'    => array(
	        'name'      => $SETTINGS->website,
	        'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email) 
	       ),
		   'template'   => REL_PATH.'content/language/'.$SETTINGS->language.'/mail-templates/new-ticket-staff.txt',
		   'language'   => $SETTINGS->language,
		   'alive'      => 'yes',
		   'add-emails' => $STAFF->email2
	      )
	     );
        }
	  }
	  // Send to admin if not admin logged in..
	  if ($MSTEAM->id!='1') {
	    $GLOBAL = mswGetTableData('users','id',1,'AND `notify` = \'yes\'','`name`,`email`,`email2`');
		if (isset($GLOBAL->name)) {
		  $MSMAIL->addTag('{NAME}', $GLOBAL->name);
		  $MSMAIL->sendMSMail(
	       array(
	        'from_email' => ($MSTEAM->emailFrom ? $MSTEAM->emailFrom : $MSTEAM->email),
	        'from_name'  => ($MSTEAM->nameFrom ? $MSTEAM->nameFrom : $MSTEAM->name),
		    'to_email'   => $GLOBAL->email,
		    'to_name'    => $GLOBAL->name,
		    'subject'    => str_replace(
		     array('{website}','{ticket}'),
		     array(
		      $SETTINGS->website,
			  mswTicketNumber($tID)
		     ),
		     $emailSubjects['new-ticket']
		    ),
		    'replyto'    => array(
	         'name'      => $SETTINGS->website,
	         'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email) 
	        ),
		    'template'   => REL_PATH.'content/language/'.$SETTINGS->language.'/mail-templates/new-ticket-admin.txt',
		    'language'   => $SETTINGS->language,
		    'alive'      => 'yes',
			'add-emails' => $GLOBAL->email2
	       )
	      );
		}
	  }
	  // Notify visitor..
	  $IDEPT = mswGetTableData('imap','im_dept',$ST->department,'','`im_email`');
      if (isset($IDEPT->im_email) && $IDEPT->im_email) {
        $replyToAddr = $IDEPT->im_email;
      }
	  if (file_exists(REL_PATH.'content/language/'.$PORTAL->language.'/mail-templates/new-ticket-visitor.txt')) {
	    $mailT  = REL_PATH.'content/language/'.$PORTAL->language.'/mail-templates/new-ticket-visitor.txt';
		$pLang  = $PORTAL->language;
	  } else {
	    $mailT  = REL_PATH.'content/language/'.$SETTINGS->language.'/mail-templates/new-ticket-visitor.txt';
      }
	  $MSMAIL->addTag('{NAME}', $PORTAL->name);
	  $MSMAIL->sendMSMail(
	   array(
	    'from_email' => ($MSTEAM->emailFrom ? $MSTEAM->emailFrom : $MSTEAM->email),
	    'from_name'  => ($MSTEAM->nameFrom ? $MSTEAM->nameFrom : $MSTEAM->name),
		'to_email'   => $PORTAL->email,
		'to_name'    => $PORTAL->name,
		'subject'    => str_replace(
		 array('{website}','{ticket}'),
		 array($SETTINGS->website,mswTicketNumber($tID)),
		 $emailSubjects['new-ticket-vis']
		),
		'replyto'    => array(
	     'name'      => $SETTINGS->website,
	     'email'     => ($replyToAddr ? $replyToAddr : ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email))
	    ),
		'template'   => $mailT,
		'language'   => (isset($pLang) ? $pLang : $SETTINGS->language)
	   )
	  );
	}
  }
  $OK2 = true;
}
 
// Call relevant classes..
include_once(REL_PATH.'control/classes/class.tickets.php');
$MSPTICKETS            = new tickets();
$MSPTICKETS->settings  = $SETTINGS;
$MSPTICKETS->datetime  = $MSDT;
$title                 = $msg_adheader63;
$loadJQAlertify        = true;
$loadJQNyroModal       = true;

include(PATH.'templates/header.php');
include(PATH.'templates/system/tickets/tickets-spam.php');
include(PATH.'templates/footer.php');

?>
