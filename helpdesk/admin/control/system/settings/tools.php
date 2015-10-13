<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  This File: tools.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  $HEADERS->err403(true);
}

// Access..
if (!in_array($cmd,$userAccess) && $MSTEAM->id!='1') {
  $HEADERS->err403(true);
}

// Batch enable/disable fields..
$batchEnDisFields = array(
 'users'     => $msg_tools30,
 'portal'    => $msg_tools31,
 'fields'    => $msg_tools32,
 'responses' => $msg_tools33,
 'imap'      => $msg_tools34,
 'faq-cat'   => $msg_tools35,
 'faq-que'   => $msg_tools36
);

// Load mail params
include(REL_PATH.'control/mail-data.php');

// Account classes..
include(REL_PATH.'control/classes/class.accounts.php');
include_once(PATH.'control/classes/class.accounts.php');
$MSACCNT            = new accountSystem();
$MSACC              = new accounts();
$MSACC->settings    = $SETTINGS;
$MSACCNT->settings  = $SETTINGS;

// Batch enable/disable..
if (isset($_POST['enable-disable'])) {
  if (!empty($_POST['tbls'])) {
    $MSSET->batchEnableDisable($batchEnDisFields);
    switch ($_POST['endis-option']) {
	  case 'enable':
	  $txt = $msg_tools37;
	  break;
	  case 'disable':
	  $txt = $msg_tools38;
	  break;
	}
	$OK5 = true;
  } else {
    header("Location: index.php?p=tools");
	exit;
  }
}

// Purge ops..
if (isset($_POST['purge-type'])) {
  @ini_set('memory_limit', '50M');
  @set_time_limit(0);
  switch ($_POST['purge-type']) {
    // Purge accounts..
	case 'purge3':
	if (isset($_POST['days3']) && (int)$_POST['days3']>0 && USER_DEL_PRIV=='yes') {
     $data  = $MSACC->purgeAccounts();
	 $count = count($data);
	 if ($count>0 && isset($_POST['mail'])) {
	   foreach ($data AS $k => $v) {
	     $pLang  = $SETTINGS->language;
		 $mailT  = LANG_BASE_PATH.$SETTINGS->language.'/mail-templates/account-deleted.txt';
		 if ($v['lang'] && file_exists(LANG_BASE_PATH.$v['lang'].'/mail-templates/account-deleted.txt')) {
		   $mailT  = LANG_BASE_PATH.$v['lang'].'/mail-templates/account-deleted.txt';
	       $pLang  = $v['lang'];
	     }
	     $MSMAIL->addTag('{NAME}', $v['name']);
         $MSMAIL->sendMSMail(
	      array(
	       'from_email' => $SETTINGS->email,
		   'from_name'  => $SETTINGS->website,
		   'to_email'   => $v['email'],
		   'to_name'    => $v['name'],
		   'subject'    => str_replace(
		    array('{website}'),
		    array($SETTINGS->website),
		    $emailSubjects['acc-deletion']
		   ),
		   'replyto'    => array(
	        'name'      => $SETTINGS->website,
	        'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email)
	       ),
		   'template'   => $mailT,
		   'language'   => $pLang,
		   'alive'      => 'yes'
	      )
	     );
	   }
	 }
     $OK4 = true;
    }
	break;
    // Purge tickets..
    case 'purge1':
	if (isset($_POST['days1']) && (int)$_POST['days1']>0 && !empty($_POST['dept1']) && USER_DEL_PRIV=='yes') {
     $counts  = $MSTICKET->purgeTickets();
     $OK1      = true;
    }
    break;
	// Purge attachments..
    case 'purge2':
	if (isset($_POST['days2']) && (int)$_POST['days2']>0 && !empty($_POST['dept2']) && USER_DEL_PRIV=='yes') {
      $count  = $MSTICKET->purgeAttachments();
      $OK2    = true;
    }
	break;
	// Reset passwords..can only be actioned by global admin..
    case 'reset':
	if ($MSTEAM->id=='1') {
	  $cnt = array(0,0);
	  // Account visitors..
	  if (isset($_POST['visitors'])) {
	    $qA = mysql_query("SELECT `name`,`email`,`language` FROM `".DB_PREFIX."portal`
	          ".(!isset($_POST['disabled']) ? 'WHERE `enabled` = \'yes\'' : '')."
			  GROUP BY `email`
			  ORDER BY `name`
			  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
        while ($ACC = mysql_fetch_object($qA)) {
		  $pLang   = '';
		  if ($ACC->language && file_exists(LANG_BASE_PATH.$ACC->language.'/mail-templates/html-wrapper.html')) {
	        $pLang  = $ACC->language;
	      }
		  // New password..
		  $newPass = $MSACCNT->ms_password($ACC->email,$MSACCNT->ms_generate());
		  // Send email..
		  if (isset($_POST['sendmail'])) {
		    $MSMAIL->addTag('{NAME}', $ACC->name);
            $MSMAIL->addTag('{EMAIL}', $ACC->email);
            $MSMAIL->addTag('{PASS}', $newPass);
            $MSMAIL->addTag('{LOGIN_URL}', $SETTINGS->scriptpath);
		    $MSMAIL->sendMSMail(
	         array(
	          'from_email' => $SETTINGS->email,
		      'from_name'  => $SETTINGS->website,
		      'to_email'   => $ACC->email,
		      'to_name'    => $ACC->name,
		      'subject'    => str_replace(
		       array('{website}'),
		       array($SETTINGS->website),
		       $emailSubjects['reset']
		      ),
			  'replyto'    => array(
	           'name'      => $SETTINGS->website,
	           'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email)
	          ),
		      'template'   => $_POST['message'],
			  'language'   => ($pLang ? $pLang : $SETTINGS->language),
		      'alive'      => 'yes'
	         )
	        );
		  }
	    }
	    $cnt[0] = mysql_num_rows($qA);
      }
	  // Support team..
	  if (isset($_POST['team'])) {
	    $qU = mysql_query("SELECT `id`,`name`,`email` FROM `".DB_PREFIX."users`
	          WHERE `id` > 1
	          ".(!isset($_POST['disabled']) ? 'AND `enabled` = \'yes\'' : '')."
			  GROUP BY `email`
			  ORDER BY `name`
			  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
        while ($USR = mysql_fetch_object($qU)) {
	      // New password..
		  $newPass = $MSUSERS->password($USR->id,$MSACCNT->ms_generate());
		  // Send email..
		  if (isset($_POST['sendmail'])) {
		    $MSMAIL->addTag('{NAME}', $USR->name);
            $MSMAIL->addTag('{EMAIL}', $USR->email);
            $MSMAIL->addTag('{PASS}', $newPass);
            $MSMAIL->addTag('{LOGIN_URL}', $SETTINGS->scriptpath.'/'.$SETTINGS->afolder);
		    $MSMAIL->sendMSMail(
	         array(
	          'from_email' => $SETTINGS->email,
		      'from_name'  => $SETTINGS->website,
		      'to_email'   => $USR->email,
		      'to_name'    => $USR->name,
		      'subject'    => str_replace(
		       array('{website}'),
		       array($SETTINGS->website),
		       $emailSubjects['reset']
		      ),
			  'replyto'    => array(
	           'name'      => $SETTINGS->website,
	           'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email)
	          ),
		      'template'   => $_POST['message'],
			  'language'   => $SETTINGS->language,
		      'alive'      => 'yes'
	         )
	        );
		  }
	    }
	    $cnt[1] = mysql_num_rows($qU);
      }
	  $OK3 = true;
	}
	break;
  }
}

$title          = $msg_adheader18;
$loadJQAlertify = true;

include(PATH.'templates/header.php');
include(PATH.'templates/system/settings/tools.php');
include(PATH.'templates/footer.php');

?>
