<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: account-ticket-create.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || !defined('MS_PERMISSIONS') || !defined('T_PERMS')) {
  $HEADERS->err403();
}

if ($SETTINGS->enCapLogin=='yes' && MS_PERMISSIONS!='guest' && isset($LI_ACC->name)) {
  $SETTINGS->recaptchaPublicKey  = '';
  $SETTINGS->recaptchaPrivateKey = '';
}

// Load mail params
include(PATH.'control/mail-data.php');

$sCount = 0;

//====================
// Mandatory fields..
//====================

$fields = array('subject','dept','priority','comments');
if (!isset($LI_ACC->name)) {
  array_push($fields,'name','email');
}
if ($SETTINGS->recaptchaPublicKey && $SETTINGS->recaptchaPrivateKey) {
  array_push($fields,'recaptcha_response_field');
}

if (!empty($fields)) {
  // If any mandatory fields are missing, its forbidden as they shouldn`t be missing..
  foreach ($fields AS $f) {
    if (!isset($_POST[$f])) {
	  $HEADERS->err403();
	} else {
	  // If its not missing, is it blank..
	  if ($_POST[$f]!='' && $_POST[$f]!='0') {
	    ++$sCount;
	  }
	}
  }
  // Are all fields blank? If so, just refresh, pointless carrying on..
  // This can trigger from curious people just hitting the open ticket button without doing anything..
  if ($sCount==0) {
    header("Location: index.php?p=open");
	exit;
  }
  // Ok, so lets see whats invalid..
  if (isset($_POST['name']) && $_POST['name']=='') {
    array_push($eFields,'input|name|err1');
  }
  if (isset($_POST['email']) && !mswIsValidEmail($_POST['email'])) {
    array_push($eFields,'input|email|err1');
  }
  if ((int)$_POST['dept']=='0') {
    array_push($eFields,'select|dept|err1');
  }
  if ($_POST['subject']=='') {
    array_push($eFields,'input|subject|err1');
  }
  if ($_POST['comments']=='') {
    array_push($eFields,'textarea|comments|err1');
  }
  if (!in_array($_POST['priority'],$levelPrKeys)) {
    array_push($eFields,'select|priority|err1');
  }
  if ($SETTINGS->recaptchaPublicKey && $SETTINGS->recaptchaPrivateKey && isset($_POST['recaptcha_response_field'])) {
    $RECAPTCHA = recaptcha_check_answer(
	 $SETTINGS->recaptchaPrivateKey,
	 $_SERVER['REMOTE_ADDR'],
	 $_POST['recaptcha_challenge_field'],
	 $_POST['recaptcha_response_field']
	);
    if (!$RECAPTCHA->is_valid) {
      array_push($eFields,'input|recaptcha_response_field|err3');
    }
  } else {
    // If javascript isn`t enabled, fail..
    if ($SETTINGS->recaptchaPublicKey && $SETTINGS->recaptchaPrivateKey && isset($_POST['recaptcha_response_field_fail'])) {
      array_push($eFields,'input|recaptcha_response_field|err3');
    }
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
  $customCheckFields = $MSFIELDS->check('ticket',(int)$_POST['dept']);
  if (!empty($customCheckFields)) {
    $eFields = array_merge($eFields,$customCheckFields);
  }
  // All ok?
  if (empty($eFields)) {
    $deptID = (int)$_POST['dept'];
    // Department preferences..
    $DP = mswGetTableData('departments','id',$deptID,'','`manual_assign`');
	// If not logged in, lets see if this account exists..
	if (!isset($LI_ACC->id)) {
	  $LI_ACC = mswGetTableData('portal','email',mswSafeImportString($_POST['email']));
	}
	// Is person logged in or does person already have account?
	if (isset($LI_ACC->name)) {
	  $name   = $LI_ACC->name;
	  $email  = $LI_ACC->email;
	  $pass   = '';
	  $userID = $LI_ACC->id;
	} else {
	  define('NEW_ACC_CREATION',1);
	  $name   = $_POST['name'];
	  $email  = $_POST['email'];
	  $pass   = $MSACC->ms_generate();
	  $mailT  = PATH.'content/language/'.$SETTINGS->language.'/mail-templates/new-account.txt';
	  // Create account..
	  $userID = $MSACC->add(
	   array(
	    'name'     => $name,
	    'email'    => $email,
	    'pass'     => $pass,
	    'enabled'  => 'yes',
		'verified' => 'yes',
	    'timezone' => $SETTINGS->timezone,
	    'ip'       => mswIPAddresses(),
	    'notes'    => '',
		'language' => $SETTINGS->language
	   )
	  );
	  // Send email about new account..
	  $MSMAIL->addTag('{ACC_NAME}', $name);
	  $MSMAIL->addTag('{ACC_EMAIL}', $email);
	  $MSMAIL->addTag('{PASS}', $pass);
	  $MSMAIL->addTag('{LOGIN_URL}', $SETTINGS->scriptpath);
	  $MSMAIL->sendMSMail(
	   array(
	    'from_email' => $SETTINGS->email,
		'from_name'  => $SETTINGS->website,
		'to_email'   => $email,
		'to_name'    => $name,
		'subject'    => str_replace(
		 array('{website}'),
		 array($SETTINGS->website),
		 $emailSubjects['new-account']
		),
		'replyto'    => array(
	     'name'      => $SETTINGS->website,
	     'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email) 
	    ),
		'template'   => $mailT,
		'language'   => $SETTINGS->language,
		'alive'      => 'yes'
	   )
	  );
	}
	// Add ticket to database..
	if ($userID>0) {
	  $ID = $MSTICKET->add(
	   array(
	    'dept'         => $deptID,
	    'assigned'     => ($DP->manual_assign=='yes' ? 'waiting' : ''),
	    'visitor'      => $userID,
	    'subject'      => $_POST['subject'],
	    'quoteBody'    => '',
	    'comments'     => $_POST['comments'],
	    'priority'     => $_POST['priority'],
	    'replyStatus'  => 'start',
	    'ticketStatus' => 'open',
	    'ip'           => mswIPAddresses(),
	    'notes'        => '',
	    'disputed'     => 'no'
	   )
	  );
	  // Proceed if ticket added ok..
	  if ($ID>0) {
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
				'tID'   => $ID,
				'rID'   => 0,
				'dept'  => $deptID,
				'incr'  => $i
			   )
			  );
			  $attString[] = $SETTINGS->scriptpath.'/?attachment='.$atID;
			}
          }
		}
	    // Mail tags..
		$MSMAIL->addTag('{ACC_NAME}', $name);
		$MSMAIL->addTag('{ACC_EMAIL}', $email);
		$MSMAIL->addTag('{SUBJECT}', $MSBB->cleaner($_POST['subject']));
		$MSMAIL->addTag('{TICKET}', mswTicketNumber($ID));
        $MSMAIL->addTag('{DEPT}', $MSYS->department($deptID,$msg_script30));
        $MSMAIL->addTag('{PRIORITY}', $MSYS->levels($_POST['priority']));
        $MSMAIL->addTag('{COMMENTS}', $MSBB->cleaner($_POST['comments']));
		$MSMAIL->addTag('{ATTACHMENTS}', (!empty($attString) ? implode(mswDefineNewline(),$attString) : 'N/A'));
        $MSMAIL->addTag('{CUSTOM}', $MSFIELDS->email($ID,0));
        $MSMAIL->addTag('{ID}', $ID);
		// Send message to support staff if manual assign is off for department..
        // This doesn`t include the global user..
        if ($DP->manual_assign=='no') {
		  $qU = mysql_query("SELECT `".DB_PREFIX."users`.`name` AS `teamName`,`email`,`email2` FROM `".DB_PREFIX."userdepts`
                LEFT JOIN `".DB_PREFIX."departments`
                ON `".DB_PREFIX."userdepts`.`deptID`  = `".DB_PREFIX."departments`.`id`
                LEFT JOIN `".DB_PREFIX."users`
                ON `".DB_PREFIX."userdepts`.`userID`  = `".DB_PREFIX."users`.`id`
                WHERE `deptID`  = '{$deptID}'
                AND `userID`   != '1'
                AND `notify`    = 'yes'
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
		       array($SETTINGS->website,mswTicketNumber($ID)),
		       $emailSubjects['new-ticket']
		      ),
		      'replyto'    => array(
	           'name'      => $SETTINGS->website,
	           'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email) 
	          ),
		      'template'   => PATH.'content/language/'.$SETTINGS->language.'/mail-templates/new-ticket-staff.txt',
		      'language'   => $SETTINGS->language,
		      'alive'      => 'yes',
			  'add-emails' => $STAFF->email2
	         )
	        );
		  }
		}
		// Now send to global user..
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
		     array($SETTINGS->website,mswTicketNumber($ID)),
		     $emailSubjects['new-ticket']
		    ),
		    'replyto'    => array(
	         'name'      => $SETTINGS->website,
	         'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email) 
	        ),
		    'template'   => PATH.'content/language/'.$SETTINGS->language.'/mail-templates/new-ticket-admin.txt',
		    'language'   => $SETTINGS->language,
		    'alive'      => 'yes',
			'add-emails' => $GLOBAL->email2
	       )
	      );
		}
		// Send auto responder to person who opened ticket..
		if (!defined('NEW_ACC_CREATION') && file_exists(LANG_PATH.'mail-templates/new-ticket-visitor.txt')) {
		  $mailT  = LANG_PATH.'mail-templates/new-ticket-visitor.txt';
		  $pLang  = $LI_ACC->language;
		} else {
		  $mailT  = PATH.'content/language/'.$SETTINGS->language.'/mail-templates/new-ticket-visitor.txt';
		}
		$MSMAIL->addTag('{NAME}', $name);
		$MSMAIL->sendMSMail(
	     array(
	      'from_email' => $SETTINGS->email,
		  'from_name'  => $SETTINGS->website,
		  'to_email'   => $email,
		  'to_name'    => $name,
		  'subject'    => str_replace(
		   array('{website}','{ticket}'),
		   array($SETTINGS->website,mswTicketNumber($ID)),
		   $emailSubjects['new-ticket-vis']
		  ),
		  'replyto'    => array(
	       'name'      => $SETTINGS->website,
	       'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email) 
	      ),
		  'template'   => $mailT,
		  'language'   => (isset($pLang) ? $pLang : $SETTINGS->language)
	     )
	    );
		// Write history log..
		$MSTICKET->historyLog(
		 $ID,
		 str_replace(
		  array('{visitor}'),
		  array($name),
		  $msg_ticket_history['new-ticket-visitor']
		 )
		);
		// All done..show relevant message..
		$title = $msg_main2;
        include(PATH.'control/header.php');
        $tpl  = new Savant3();
        $tpl->assign('TXT',
         array(
          $msg_public_ticket4,
	      $msg_newticket13,
	      str_replace(array('{ticket}','{ticket_long}'),array($ID,mswTicketNumber($ID)),$msg_public_ticket5),
	      $msg_public_ticket6
         )
        );
        $tpl->assign('ADD_TXT', ($pass ? str_replace(array('{email}','{pass}'),array($email,$pass),$msg_public_ticket7) : ''));
        $tpl->assign('ID', $ID);
  
        // Global vars..
        include(PATH.'control/lib/global.php');
  
        $tpl->display('content/'.MS_TEMPLATE_SET.'/ticket-create-message.tpl.php');
        include(PATH.'control/footer.php');
        exit;
	  }	
    }		
  }
}

?>