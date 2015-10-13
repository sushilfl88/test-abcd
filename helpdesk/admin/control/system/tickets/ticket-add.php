<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  This File: ticket-add.php
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

// Call the relevant classes..
include_once(REL_PATH.'control/classes/class.tickets.php');
include_once(PATH.'control/classes/class.accounts.php');
include_once(REL_PATH.'control/classes/class.fields.php');
include_once(REL_PATH.'control/classes/class.accounts.php');
$MSACC                 = new accounts();
$MSPORTAL              = new accountSystem();
$MSPTICKETS            = new tickets();
$MSCFMAN               = new customFieldManager();
$MSACC->settings       = $SETTINGS;
$MSPTICKETS->settings  = $SETTINGS;
$MSPTICKETS->datetime  = $MSDT;
$MSPORTAL->settings    = $SETTINGS;

// Add ticket..
if (isset($_POST['process'])) {
  $OK = 'fail';
  if ($_POST['subject'] && $_POST['comments'] && $_POST['name'] && mswIsValidEmail($_POST['email'])) {
    // Check if account exists for email address..
	$PORTAL = mswGetTableData('portal','email',mswSafeImportString($_POST['email']));
	// Check language..
	if (isset($_PORTAL->id) && $PORTAL->language && file_exists(LANG_BASE_PATH.$PORTAL->language.'/mail-templates/admin-add-ticket.txt')) {
	  $mailT  = LANG_BASE_PATH.$PORTAL->language.'/mail-templates/admin-add-ticket.txt';
	  $pLang  = $PORTAL->language;
	} else {
	  $mailT  = LANG_PATH.'admin-add-ticket.txt';
	}
	$pass   = '';
	// If portal account doesn`t exist, we need to create it..
	if (!isset($PORTAL->id)) {
	  $pass   = $MSPORTAL->ms_generate();
	  $mailT  = LANG_PATH.'admin-add-ticket-new.txt';
	  $userID = $MSACC->add(
	   array(
	    'name'     => $_POST['name'],
	    'email'    => $_POST['email'],
	    'userPass' => $pass,
	    'enabled'  => 'yes',
	    'timezone' => '',
	    'ip'       => '',
	    'notes'    => '',
		'language' => $SETTINGS->language
	   )
	  );
	}
	// Add ticket to database..
	if ((isset($userID) && $userID>0) || isset($PORTAL->id)) {
	  $ID     = $MSPTICKETS->add(
	   array(
	    'dept'         => (int)$_POST['dept'],
	    'assigned'     => (isset($_POST['waiting']) ? 'waiting' : (!empty($_POST['assigned']) ? implode(',',$_POST['assigned']) : '')),
	    'visitor'      => (isset($userID) ? $userID : $PORTAL->id),
	    'subject'      => $_POST['subject'],
	    'quoteBody'    => '',
	    'comments'     => $_POST['comments'],
	    'priority'     => $_POST['priority'],
	    'replyStatus'  => (isset($_POST['closed']) ? 'admin' : 'visitor'),
	    'ticketStatus' => (isset($_POST['closed']) ? 'close' : 'open'),
	    'ip'           => '',
	    'notes'        => '',
	    'disputed'     => 'no'
	   )
	  );
	  // Add attachments, history, send emails..
	  if ($ID>0) {
	    // Attachments..
		$attString = array();
		if (!empty($_FILES['attachment']['tmp_name'])) {
		  for ($i=0; $i<count($_FILES['attachment']['tmp_name']); $i++) {
		    $a_name = $_FILES['attachment']['name'][$i];
			$a_temp = $_FILES['attachment']['tmp_name'][$i];
			$a_size = $_FILES['attachment']['size'][$i];
			$a_mime = $_FILES['attachment']['type'][$i];
			if ($a_name && $a_temp && $a_size>0) {
			  $atID = $MSPTICKETS->addAttachment(
			   array(
			    'temp'  => $a_temp,
				'name'  => $a_name,
				'size'  => $a_size,
				'mime'  => $a_mime,
				'tID'   => $ID,
				'rID'   => 0,
				'dept'  => $_POST['dept'],
				'incr'  => $i
			   )
			  );
			  $attString[] = $SETTINGS->scriptpath.'/?attachment='.$atID;
			}
		  }
		}
		// Log..
		$MSTICKET->historyLog(
		 $ID,
		 str_replace(
		  array('{user}'),
		  array($MSTEAM->name),
		  $msg_ticket_history['new-ticket-admin']
		 )
		);
		// Everything in the post array..
	    foreach ($_POST AS $key => $value) {
          if (!is_array($value)) {
            $MSMAIL->addTag('{'.strtoupper($key).'}', $MSBB->cleaner($value));
          }
        }
		// Send notification to visitor if enabled..
		if (isset($_POST['accMail']) && !isset($_POST['closed'])) {
		  // Tags..
		  $MSMAIL->addTag('{NAME}', $_POST['name']);
          $MSMAIL->addTag('{TITLE}', $_POST['subject']);
		  $MSMAIL->addTag('{COMMENTS}', $MSBB->cleaner($_POST['comments']));
          $MSMAIL->addTag('{EMAIL}', $_POST['email']);
          $MSMAIL->addTag('{PASSWORD}', $pass);
          $MSMAIL->addTag('{ID}', $ID);
		  $MSMAIL->sendMSMail(
	       array(
	        'from_email' => ($MSTEAM->emailFrom ? $MSTEAM->emailFrom : $MSTEAM->email),
	        'from_name'  => ($MSTEAM->nameFrom ? $MSTEAM->nameFrom : $MSTEAM->name),
		    'to_email'   => $_POST['email'],
		    'to_name'    => $_POST['name'],
		    'subject'    => str_replace(
		     array('{website}','{ticket}'),
		     array($SETTINGS->website,mswTicketNumber($ID)),
		     $emailSubjects['new-ticket']
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
		// Send notification to support staff..
		// If ticket is waiting assignment, no emails are sent..
		if (isset($_POST['assignMail']) && !isset($_POST['waiting']) && !isset($_POST['closed'])) {
		  // Are we notifying staff who are assigned to this ticket?
		  $userList = array();
		  if (!empty($_POST['assigned'])) {
		    $as = implode(',',$_POST['assigned']);
		    $q  = mysql_query("SELECT `id`,`name`,`email`,`email2` FROM `".DB_PREFIX."users`
                  WHERE `id`    IN({$as})
				  AND `id`  NOT IN (1,{$MSTEAM->id})
                  AND `notify`   = 'yes'
                  ORDER BY `id`
                  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
		    while ($USR = mysql_fetch_object($q)) {
			  $userList[$USR->id] = array($USR->name,$USR->email,$USR->email2);
			}
			$mailT  = LANG_PATH.'admin-ticket-assign.txt';
		  } else {
		    $q = mysql_query("SELECT `".DB_PREFIX."users`.`id` AS `usrID`,`name`,`email`,`email2` FROM `".DB_PREFIX."userdepts`
                 LEFT JOIN `".DB_PREFIX."users`
                 ON `".DB_PREFIX."userdepts`.`userID`  = `".DB_PREFIX."users`.`id`
                 WHERE `deptID`                        = '{$_POST['dept']}'
                 AND `".DB_PREFIX."users`.`id`    NOT IN (1,{$MSTEAM->id})
				 AND `notify`                          = 'yes'
                 GROUP BY `".DB_PREFIX."userdepts`.`userID`
				 ORDER BY `".DB_PREFIX."userdepts`.`userID`
                 ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
		    while ($USR = mysql_fetch_object($q)) {
			  $userList[$USR->usrID] = array($USR->name,$USR->email,$USR->email2);
			}
			$mailT  = LANG_PATH.'admin-add-ticket-staff-notify.txt';
		  }
		  // Tags..
		  $MSMAIL->addTag('{TITLE}', $_POST['subject']);
          $MSMAIL->addTag('{TICKETS}', str_replace(
		   array('{id}','{subject}'),
		   array(
		    mswTicketNumber($ID),
		    $_POST['subject']
		   ),
		   $msg_assign7
		  ));
          $MSMAIL->addTag('{TEAM_NAME}', $MSTEAM->name);
		  $MSMAIL->addTag('{ASSIGNEE}', $MSTEAM->name);
		  $MSMAIL->addTag('{TICKET}', mswTicketNumber($ID));
		  $MSMAIL->addTag('{ACC_NAME}', $_POST['name']);
		  $MSMAIL->addTag('{ACC_EMAIL}', $_POST['email']);
		  $MSMAIL->addTag('{SUBJECT}', $_POST['subject']);
		  $MSMAIL->addTag('{DEPT}', $MSYS->department($_POST['dept'],$msg_script30));
		  $MSMAIL->addTag('{PRIORITY}', $MSYS->levels($_POST['priority']));
		  $MSMAIL->addTag('{COMMENTS}', $MSBB->cleaner($_POST['comments']));
		  $MSMAIL->addTag('{CUSTOM}', $MSCFMAN->email($ID,0));
		  $MSMAIL->addTag('{ATTACHMENTS}', (!empty($attString) ? implode(mswDefineNewline(),$attString) : 'N/A'));
		  $MSMAIL->addTag('{ID}', $ID);
		  // Anyone to send a message to..
		  if (!empty($userList)) {
		    foreach ($userList AS $k => $v) {
			  $teamID  = $k;
			  $name    = $v[0];
			  $email   = $v[1];
			  $email2  = $v[2];
			  $MSMAIL->addTag('{NAME}', $name);
              $MSMAIL->sendMSMail(
	           array(
	            'from_email' => ($MSTEAM->emailFrom ? $MSTEAM->emailFrom : $MSTEAM->email),
	            'from_name'  => ($MSTEAM->nameFrom ? $MSTEAM->nameFrom : $MSTEAM->name),
		        'to_email'   => $email,
		        'to_name'    => $name,
		        'subject'    => str_replace(
		         array('{website}','{ticket}'),
		         array($SETTINGS->website,mswTicketNumber($ID)),
		         $emailSubjects['new-ticket-team']
		        ),
				'replyto'    => array(
	             'name'      => $SETTINGS->website,
	             'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email)
	            ),
		        'template'   => $mailT,
				'language'   => $SETTINGS->language,
				'alive'      => 'yes',
				'add-emails' => $email2
	           )
	          );
			}
		  }
		  // Send mail to global user if applicable and if the global user isn`t the one adding the ticket..
		  // Applies to department level filtering only, not assigned..
		  if (empty($_POST['assigned']) && $MSTEAM->id>1) {
		    $GLOBAL = mswGetTableData('users','id','1');
			if (isset($GLOBAL->id) && $GLOBAL->notify=='yes') {
			  $MSMAIL->addTag('{NAME}', $GLOBAL->name);
              $MSMAIL->sendMSMail(
	           array(
	            'from_email' => ($MSTEAM->emailFrom ? $MSTEAM->emailFrom : $MSTEAM->email),
	            'from_name'  => ($MSTEAM->nameFrom ? $MSTEAM->nameFrom : $MSTEAM->name),
		        'to_email'   => $GLOBAL->email,
		        'to_name'    => $GLOBAL->name,
		        'subject'    => str_replace(
		         array('{website}','{ticket}'),
		         array($SETTINGS->website,mswTicketNumber($ID)),
		         $emailSubjects['new-ticket-team']
		        ),
				'replyto'    => array(
	             'name'      => $SETTINGS->website,
	             'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email)
	            ),
		        'template'   => $mailT,
				'language'   => $SETTINGS->language,
				'add-emails' => $GLOBAL->email2
	           )
	          );
			}
		  }
		}
		// Log for closed..
		if (isset($_POST['closed'])) {
		  $MSTICKET->historyLog(
		   $ID,
		   str_replace(
		    array('{user}'),
		    array($MSTEAM->name),
		    $msg_ticket_history['new-ticket-admin-close']
		   )
		  );
		}
	    $OK = 'ok';
      }
	}
  }
}

$title          = $msg_open;
$loadJQAPI      = true;
$loadBBCSS      = true;
$loadJQAlertify = true;

include(PATH.'templates/header.php');
include(PATH.'templates/system/tickets/tickets-add.php');
include(PATH.'templates/footer.php');

?>