<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: ticket-reply.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || !defined('TICKET_REPLY')) {
  $HEADERS->err403(true);
}

// Merge reload time..
define('MERGE_RELOAD_TIME', 5);

// Load mail params
include(REL_PATH.'control/mail-data.php');

// Call the relevant classes..
include_once(REL_PATH.'control/classes/class.tickets.php');
include_once(PATH.'control/classes/class.accounts.php');
include_once(REL_PATH.'control/classes/class.fields.php');
$MSACC                 = new accounts();
$MSPTICKETS            = new tickets();
$MSCFMAN               = new customFieldManager();
$MSACC->settings       = $SETTINGS;
$MSPTICKETS->settings  = $SETTINGS;
$MSPTICKETS->datetime  = $MSDT;

// Add only if comments are added..
if (trim($_POST['comments'])) {
  $replyToAddr = '';
  $isDispute   = ($SETTINGS->disputes=='yes' && $_POST['isDisputed']=='yes' ? 'yes' : 'no');
  // Add reply..
  // $ret[0] = yes/no for merge
  // $ret[1] = Ticket ID
  // $ret[2] = Merged ticket subject
  // $ret[3] = Reply ID
  $ret     = $MSTICKET->addTicketReply();
  // Get merged parent ticket or current ticket..
  $TICKET  = mswGetTableData('tickets','id',$ret[1]);
  // Visitor Info..
  $PORTAL  = mswGetTableData('portal','id',$TICKET->visitorID);
  // Add attachments..
  $attString = array();
  if (!empty($_FILES['attachment']['tmp_name'])) {
    for ($i=0; $i<count($_FILES['attachment']['tmp_name']); $i++) {
      $name  = $_FILES['attachment']['name'][$i];
      $temp  = $_FILES['attachment']['tmp_name'][$i];
      $size  = $_FILES['attachment']['size'][$i];
	  $mime  = $_FILES['attachment']['type'][$i];
      if ($name && $temp && $size>0) {
	    $atID = $MSPTICKETS->addAttachment(
			     array(
			      'temp'  => $temp,
				  'name'  => $name,
				  'size'  => $size,
				  'mime'  => $mime,
				  'tID'   => $TICKET->id,
				  'rID'   => $ret[3],
				  'dept'  => $TICKET->department,
				  'incr'  => $i
			     )
			    );
	    $attString[] = $SETTINGS->scriptpath.'/?attachment='.$atID;
      }
    }
  }
  // Write history if enabled..
  if (isset($_POST['history'])) {
    $MSTICKET->historyLog(
	 $TICKET->id,
	 str_replace(
	  array('{user}','{id}','{from}','{to}'),
	  array(
	   $MSTEAM->name,
	   $ret[3],
	   ($ret[0]=='yes' ? mswTicketNumber($_GET['id']) : ''),
	   ($ret[0]=='yes' ? mswTicketNumber(ltrim($_POST['mergeid'],'0')) : '')
	  ),
	  $msg_ticket_history['team-reply-add'.($ret[0]=='yes' ? '-merge' : '')]
	 )
	);
  }
  // Mail if enabled..
  if ($_POST['mail']=='yes') {
    // Everything in the post array..
	foreach ($_POST AS $key => $value) {
      if (!is_array($value)) {
        $MSMAIL->addTag('{'.strtoupper($key).'}', $MSBB->cleaner($value));
      }  
    }
	// Tags..
	$MSMAIL->addTag('{SIGNATURE}', ($MSTEAM->emailSigs=='yes' && $MSTEAM->signature ? $MSTEAM->signature : ''));
    $MSMAIL->addTag('{SUBJECT_OLD}', $ret[2]);
    $MSMAIL->addTag('{ATTACHMENTS}', (!empty($attString) ? implode(mswDefineNewline(),$attString) : 'N/A'));
    $MSMAIL->addTag('{NAME}', $PORTAL->name);
	$MSMAIL->addTag('{MERGED_TICKET}', ($ret[0]=='yes' ? mswTicketNumber($_GET['id']) : ''));
    $MSMAIL->addTag('{TICKET}', mswTicketNumber($TICKET->id));
    $MSMAIL->addTag('{SUBJECT}', $TICKET->subject);
    $MSMAIL->addTag('{DEPT}', $MSYS->department($TICKET->department,$msg_script30));
    $MSMAIL->addTag('{PRIORITY}', $MSYS->levels($TICKET->priority));
    $MSMAIL->addTag('{STATUS}', $MSYS->status($TICKET->ticketStatus));
    $MSMAIL->addTag('{USER}', ($MSTEAM->nameFrom ? $MSTEAM->nameFrom : $MSTEAM->name));
	$MSMAIL->addTag('{CUSTOM}', $MSCFMAN->email($ret[1],$ret[3]));
    $MSMAIL->addTag('{ID}',$TICKET->id);
    
    // Pass ticket number as custom mail header..
    $MSMAIL->xheaders['X-TicketNo'] = mswTicketNumber($TICKET->id);
    // If this ticket was opened by imap, the return address should be the imap address..
    if ($TICKET->source=='imap') {
      $IDEPT = mswGetTableData('imap','im_dept',$TICKET->department,'','`im_email`');
      if (isset($IDEPT->im_email) && $IDEPT->im_email) {
        $replyToAddr = $IDEPT->im_email;
      }
    }
	// What mail templates are we using..
	switch ($isDispute) {
      case 'yes':
	  if ($PORTAL->language && file_exists(LANG_BASE_PATH.$PORTAL->language.'/mail-templates/admin-dispute-reply.txt')) {
	    $mailT  = LANG_BASE_PATH.$PORTAL->language.'/mail-templates/admin-dispute-reply.txt';
		$pLang  = $PORTAL->language;
	  } else {
	    $mailT  = LANG_PATH.'admin-dispute-reply.txt';
	  }
	  break;
	  default:
	  if ($PORTAL->language && file_exists(LANG_BASE_PATH.$PORTAL->language.'/mail-templates/admin-ticket-reply'.($ret[0]=='yes' ? '-merged' : '').'.txt')) {
	    $mailT  = LANG_BASE_PATH.$PORTAL->language.'/mail-templates/admin-ticket-reply'.($ret[0]=='yes' ? '-merged' : '').'.txt';
		$pLang  = $PORTAL->language;
	  } else {
	    $mailT  = LANG_PATH.'admin-ticket-reply'.($ret[0]=='yes' ? '-merged' : '').'.txt';
	  }
	  break;
	}  
	// Send email to original ticket creator..
	$MSMAIL->sendMSMail(
	 array(
	  'from_email' => ($MSTEAM->emailFrom ? $MSTEAM->emailFrom : $MSTEAM->email),
	  'from_name'  => ($MSTEAM->nameFrom ? $MSTEAM->nameFrom : $MSTEAM->name),
	  'to_email'   => $PORTAL->email,
	  'to_name'    => $PORTAL->name,
	  'subject'    => str_replace(
	   array('{website}','{ticket}'),
	   array($SETTINGS->website,mswTicketNumber($TICKET->id)),
	   $emailSubjects['admin-reply']
	  ),
	  'replyto'    => array(
	   'name'      => $SETTINGS->website,
	   'email'     => ($replyToAddr ? $replyToAddr : ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email))
	  ),
	  'template'   => $mailT,
	  'language'   => (isset($pLang) ? $pLang : $SETTINGS->language),
	  'alive'      => 'yes'
	 )
	);
	// If this is a dispute, notify other users in dispute..
	if ($isDispute=='yes' && $SETTINGS->disputes=='yes') {
	  $q  = mysql_query("SELECT `name`,`email` FROM `".DB_PREFIX."disputes`
	        LEFT JOIN `".DB_PREFIX."portal`
			ON `".DB_PREFIX."disputes`.`visitorID` = `".DB_PREFIX."portal`.`id`
            WHERE `".DB_PREFIX."disputes`.`ticketID` = '{$TICKET->id}'
			GROUP BY `email`
			ORDER BY `name`
			") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	  while ($D_USR = mysql_fetch_object($q)) {
	    $pLang = '';
		// Check which templates to use based on language..
	    if ($D_USR->language && file_exists(LANG_BASE_PATH.$D_USR->language.'/mail-templates/admin-dispute-reply.txt')) {
	      $mailT  = LANG_BASE_PATH.$D_USR->language.'/mail-templates/admin-dispute-reply.txt';
		  $pLang  = $D_USR->language;
	    } else {
	      $mailT  = LANG_PATH.'admin-dispute-reply.txt';
	    }
		$MSMAIL->sendMSMail(
	     array(
	      'from_email' => ($MSTEAM->emailFrom ? $MSTEAM->emailFrom : $MSTEAM->email),
	      'from_name'  => ($MSTEAM->nameFrom ? $MSTEAM->nameFrom : $MSTEAM->name),
	      'to_email'   => $D_USR->email,
	      'to_name'    => $D_USR->name,
	      'subject'    => str_replace(
	       array('{website}','{ticket}'),
	       array($SETTINGS->website,mswTicketNumber($ID)),
	       $emailSubjects['admin-reply']
	      ),
	      'replyto'    => array(
	       'name'      => $SETTINGS->website,
	       'email'     => ($replyToAddr ? $replyToAddr : ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email))
	      ),
	      'template'   => $mailT,
		  'language'   => ($pLang ? $pLang : $SETTINGS->language),
	      'alive'      => 'yes'
	     )
	    );
	  }
	}
  }
  if ($ret[0]=='no') {
    $OK          = true;
  } else {
    $metaReload  = '<meta http-equiv="refresh" content="'.MERGE_RELOAD_TIME.';url=index.php?p=view-ticket&amp;id='.ltrim($_POST['mergeid'],'0').'">';
    $OK2         = true;
  }
}

?>
