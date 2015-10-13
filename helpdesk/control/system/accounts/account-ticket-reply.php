<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: account-ticket-reply.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || !defined('MS_PERMISSIONS') || !defined('T_PERMS')) {
  $HEADERS->err403();
}

// Load mail params
include(PATH.'control/mail-data.php');

$sCount = 0;

//====================
// Mandatory fields..
//====================

if (isset($T->id) && $T->assignedto!='waiting') {
  if ($_POST['comments']=='') {
    array_push($eFields,'textarea|comments|err1');
  }
  // Attachments..
  if ($SETTINGS->attachment=='yes' && !empty($_FILES['attachment']['tmp_name'])) {
    // Check limit..
    if (LICENCE_VER=='locked' && count($_FILES['attachment']['tmp_name'])>RESTR_ATTACH) {
      $countOfBoxes = RESTR_ATTACH;
    }
    for ($i=0; $i<(isset($countOfBoxes) ? $countOfBoxes : count($_FILES['attachment']['tmp_name'])); $i++) {
      $fname  = $_FILES['attachment']['name'][$i];
      $ftemp  = $_FILES['attachment']['tmp_name'][$i];
      $fsize  = $_FILES['attachment']['size'][$i];
	  $fmime  = $_FILES['attachment']['type'][$i];
      if ($fname && $ftemp && $fsize>0) {
        if (!$MSTICKET->size($fsize)) {
          array_push($eFields,'input|attach|err2');
        } else {
          if (!$MSTICKET->type($fname)) {
            array_push($eFields,'input|attach|err2');
          } else {
		    $ticketAttachments[$i]['ext']  = (strpos($fname,'.')!==false ? strrchr(strtolower($fname),'.') : '');
            $ticketAttachments[$i]['temp'] = $ftemp;
            $ticketAttachments[$i]['size'] = $fsize;
            $ticketAttachments[$i]['name'] = $fname;
			$ticketAttachments[$i]['type'] = $fmime;
		  }
		}  
      }
    }
    // If error, clear all attachment temp files..
    if (in_array('attach|input',$eFields)) {
      for ($i=0; $i<count($_FILES['attachment']['tmp_name']); $i++) {
        @unlink($_FILES['attachment']['tmp_name'][$i]);
      }
      $ticketAttachments = array();
    }
  }
  // Check required custom fields..
  $customCheckFields = $MSFIELDS->check('reply',$T->department);
  if (!empty($customCheckFields)) {
    $eFields = array_merge($eFields,$customCheckFields);
  }
  // All ok?
  if (empty($eFields)) {
    // Add reply..
	$replyID = $MSTICKET->reply(
	 array(
	  'ticket'       => $T->id,
	  'visitor'      => $LI_ACC->id,
	  'quoteBody'    => '',
	  'comments'     => $_POST['comments'],
	  'repType'      => 'visitor',
	  'ip'           => mswIPAddresses(),
	  'disID'        => (isset($PRIV->id) ? $LI_ACC->id : '0')
	 )
	);
	// Proceed if ok..
	if ($replyID>0) {
	  // Add attachments..
      if ($SETTINGS->attachment=='yes' && !empty($ticketAttachments)) {
		for ($i=0; $i<count($ticketAttachments); $i++) {
          $a_name  = $ticketAttachments[$i]['name'];
          $a_temp  = $ticketAttachments[$i]['temp'];
          $a_size  = $ticketAttachments[$i]['size'];
          $a_mime  = $ticketAttachments[$i]['type'];
          if ($a_name && $a_temp && $a_size>0) {
			$atID = $MSTICKET->addAttachment(
			 array(
			  'temp'  => $a_temp,
			  'name'  => $a_name,
			  'size'  => $a_size,
			  'mime'  => $a_mime,
			  'tID'   => $T->id,
			  'rID'   => $replyID,
			  'dept'  => $T->department,
			  'incr'  => $i
			 )
			);
			$attString[] = $SETTINGS->scriptpath.'/?attachment='.$atID;
		  }
        }
	  }  
	  // History log..
	  $MSTICKET->historyLog(
	   $T->id,
	   str_replace(
		array('{visitor}','{id}'),
		array(mswSpecialChars($LI_ACC->name),$replyID),
		$msg_ticket_history['vis-reply-add']
	   )
	  );  
	  // Dispute ticket or standard operations..
	  switch ($T->isDisputed) {
	    case 'no':
		// Was ticket closed..
		if (isset($_POST['close'])) {
		  $closeRrows = $MSTICKET->openclose($T->id,'close');
          // History if affected rows..
          if ($closeRrows>0) {
            $MSTICKET->historyLog(
	         $T->id,
	         str_replace('{user}',mswSpecialChars($LI_ACC->name),$msg_ticket_history['vis-ticket-close'])
	        );
			// Should we switch emails off?
			if ($SETTINGS->closenotify=='yes') {
			  define('EMAILS_OFF', 1);
			}
		  }	
		}
	    break;
	    default:
	    break;
	  }
	  // Mail tags..
	  if (!defined('EMAILS_OFF')) {
	    $MSMAIL->addTag('{ACC_NAME}', $LI_ACC->name);
        $MSMAIL->addTag('{TICKET}', mswTicketNumber($T->id));
        $MSMAIL->addTag('{SUBJECT}', $T->subject);
        $MSMAIL->addTag('{COMMENTS}', $MSBB->cleaner($_POST['comments']));
        $MSMAIL->addTag('{DEPT}', $MSYS->department($T->department,$msg_script30));
        $MSMAIL->addTag('{PRIORITY}', $MSYS->levels($T->priority));
        $MSMAIL->addTag('{STATUS}', (isset($closeRrows) && $closeRrows>0 ? $msg_showticket24 : $msg_showticket23));
        $MSMAIL->addTag('{ATTACHMENTS}', (!empty($attString) ? implode(mswDefineNewline(),$attString) : 'N/A'));
        $MSMAIL->addTag('{CUSTOM}', $MSFIELDS->email($T->id,$replyID));
        $MSMAIL->addTag('{ID}', $T->id);
	    // Send message to support staff..
	    if ($T->assignedto && $T->assignedto!='waiting') {
		  $sqlClause = 'WHERE `userID` IN('.$T->assignedto.') AND `notify` = \'yes\'';
	    } else {
		  $sqlClause = 'WHERE `deptID` = \''.$T->department.'\' AND `userID` != \'1\' AND `notify` = \'yes\'';
	    }
		$qU = mysql_query("SELECT `".DB_PREFIX."users`.`name` AS `teamName`,`email`,`email2` FROM `".DB_PREFIX."userdepts`
              LEFT JOIN `".DB_PREFIX."departments`
              ON `".DB_PREFIX."userdepts`.`deptID`  = `".DB_PREFIX."departments`.`id`
              LEFT JOIN `".DB_PREFIX."users`
              ON `".DB_PREFIX."userdepts`.`userID`  = `".DB_PREFIX."users`.`id`
              $sqlClause
              GROUP BY `email`
			  ORDER BY `".DB_PREFIX."users`.`name`
              ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
        while ($STAFF = mysql_fetch_object($qU)) {
		  $MSMAIL->addTag('{NAME}', $STAFF->teamName);
		  $MSMAIL->sendMSMail(
	       array(
	        'from_email' => $SETTINGS->email,
		    'from_name'  => $SETTINGS->website,
		    'to_email'   => $STAFF->email,
		    'to_name'    => $STAFF->teamName,
		    'subject'    => str_replace(
		     array('{website}','{ticket}'),
		     array($SETTINGS->website,mswTicketNumber($T->id)),
		     $emailSubjects['reply-notify']
		    ),
		    'replyto'    => array(
	         'name'      => $SETTINGS->website,
	         'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email) 
	        ),
		    'template'   => PATH.'content/language/'.$SETTINGS->language.'/mail-templates/ticket-reply.txt',
		    'language'   => $SETTINGS->language,
		    'alive'      => 'yes',
			'add-emails' => $STAFF->email2
	       )
	      );
		}
		// Now send to global user if ticket assign is off..
        if ($T->assignedto=='') {
		  $GLOBAL = mswGetTableData('users','id',1,'AND `notify` = \'yes\'','`name`,`email`,`email2`');
          if (isset($GLOBAL->name)) {
		    $MSMAIL->addTag('{NAME}', $GLOBAL->name);
		    $MSMAIL->sendMSMail(
	         array(
	          'from_email' => $SETTINGS->email,
		      'from_name'  => $SETTINGS->website,
		      'to_email'   => $GLOBAL->email,
		      'to_name'    => $GLOBAL->name,
		      'subject'    => str_replace(
		       array('{website}','{ticket}'),
		       array($SETTINGS->website,mswTicketNumber($T->id)),
		       $emailSubjects['reply-notify']
		      ),
		      'replyto'    => array(
	           'name'      => $SETTINGS->website,
	           'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email) 
	          ),
		      'template'   => PATH.'content/language/'.$SETTINGS->language.'/mail-templates/ticket-reply.txt',
		      'language'   => $SETTINGS->language,
		      'alive'      => 'yes',
			  'add-emails' => $GLOBAL->email2
	         )
	        );
		  }
	    }
	  }
	  // If this ticket is a dispute, send notification to relevant users..
	  if ($T->isDisputed=='yes') {
	    // Check if this ticket was originally opened by imap..
		// If it was, set the reply-to address as the imap address..
		// This is so any replies sent go back to the ticket..
		if ($T->source=='imap') {
		  $IMD = mswGetTableData('imap','im_dept',$T->department);
          if (isset($IMD->im_email) && $IMD->im_email) {
            $replyToAddr = $IMD->im_email;
          }
		}
	    // Get all users in this dispute..
		$ticketDisputeUsers = $MSTICKET->disputeUsers($T->id);
	    // Add original ticket starter to the mix..
		array_push($ticketDisputeUsers,$T->visitorID);
		// Send, but skip person currently logged in..
	    if (!empty($ticketDisputeUsers)) {
	      $qDU  = mysql_query("SELECT `name`,`email`,`language` FROM `".DB_PREFIX."portal`
                  WHERE `id` IN(".implode(',',$ticketDisputeUsers).")
				  AND `id`   != '{$LI_ACC->id}'
				  GROUP BY `email`
                  ORDER BY `name`
                  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
          while ($D_USR  = mysql_fetch_object($qDU)) {
		    $pLang = '';
			$temp  = PATH.'content/language/'.$SETTINGS->language.'/mail-templates/dispute-reply.txt';
			// Get correct language file..
		    if (isset($D_USR->language) && file_exists(PATH.'content/language/'.$D_USR->language.'/mail-templates/dispute-reply.txt')) {
		      $pLang  = $D_USR->language;
			  $temp   = PATH.'content/language/'.$D_USR->language.'/mail-templates/dispute-reply.txt';
		    }
			$MSMAIL->addTag('{USER}', $LI_ACC->name);
			$MSMAIL->addTag('{NAME}', $D_USR->name);
			$MSMAIL->sendMSMail(
	         array(
	          'from_email' => $SETTINGS->email,
		      'from_name'  => $SETTINGS->website,
		      'to_email'   => $D_USR->email,
		      'to_name'    => $D_USR->name,
		      'subject'    => str_replace(
		       array('{website}','{ticket}'),
		       array($SETTINGS->website,mswTicketNumber($T->id)),
		       $emailSubjects['dispute-notify']
		      ),
		      'replyto'    => array(
	           'name'      => $SETTINGS->website,
	           'email'     => ($replyToAddr ? $replyToAddr : ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email))
	          ),
		      'template'   => $temp,
		      'language'   => ($pLang ? $pLang : $SETTINGS->language),
		      'alive'      => 'yes'
	         )
	        );
		  }
		}
	  }
	  // Finish with message..
      $ticketSystemMsg = $msg_showticket8;
    }
  }
}

?>