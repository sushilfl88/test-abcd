<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: access.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  $HEADERS->err403(true);
}

if ($cmd=='logout') {
  @session_unset();
  @session_destroy();
  unset($_SESSION[md5(SECRET_KEY).'_ms_mail'],$_SESSION[md5(SECRET_KEY).'_ms_key']);
  if (isset($_SESSION['autoPurgeRan'])) {
    unset($_SESSION['autoPurgeRan']);
  }
  if (isset($_COOKIE[md5(SECRET_KEY).'_msc_mail'])) {
    @setcookie(md5(SECRET_KEY).'_msc_mail', '');
    @setcookie(md5(SECRET_KEY).'_msc_key', '');
    unset($_COOKIE[md5(SECRET_KEY).'_msc_mail'],$_COOKIE[md5(SECRET_KEY).'_msc_key']);
  }

  header("Location: index.php?p=login");
  exit;
}

if (isset($_POST['process'])) {
  if ($_POST['user'] && $_POST['pass']) {
    if (!mswIsValidEmail($_POST['user'])) {
      $U_ERROR = $msg_login6;
    } else {
      $USER = mswGetTableData('users','email',mswSafeImportString($_POST['user']),'AND `accpass` = \''.md5(SECRET_KEY.$_POST['pass']).'\'');
      if (isset($USER->email)) {
	    // Update page access..
		if ($USER->id>0) {
		  $upa              = userAccessPages($USER->id);
		  $USER->pageAccess = $upa;
		}
        // Add entry log..
        if ($USER->enableLog=='yes') {
          $MSUSERS->log($USER);
        }
        // Set session..
        $_SESSION[md5(SECRET_KEY).'_ms_mail'] = $USER->email;
        $_SESSION[md5(SECRET_KEY).'_ms_key']  = $USER->accpass;
        // Set cookie..
        if (isset($_POST['cookie']) && COOKIE_NAME) {
          if ((COOKIE_SSL && mswDetectSSLConnection()=='yes') || !COOKIE_SSL) {
            @setcookie(md5(SECRET_KEY).'_msc_mail', $USER->email, time()+60*60*24*COOKIE_EXPIRY_DAYS);
            @setcookie(md5(SECRET_KEY).'_msc_key', $USER->accpass, time()+60*60*24*COOKIE_EXPIRY_DAYS);
          }
        }
        if (isset($_SESSION[md5(SECRET_KEY).'thisTicket'])) {
          $thisTicket = mswReverseTicketNumber($_SESSION[md5(SECRET_KEY).'thisTicket']);
          $SUPTICK    = mswGetTableData('tickets','id',$thisTicket);
          unset($_SESSION[md5(SECRET_KEY).'thisTicket']);
          $userAccess = explode('|',$USER->pageAccess);
          if ($SUPTICK->assignedto=='waiting' && (in_array('assign',$userAccess) || $USER->id==1)) {
            header("Location: index.php?p=assign");
          } elseif ($SUPTICK->assignedto=='waiting' && !in_array('assign',$userAccess)) {
            header("Location: index.php");
          } else {
            header("Location: index.php?p=view-".(isset($SUPTICK->isDisputed) && $SUPTICK->isDisputed=='yes' ? 'dispute' : 'ticket')."&id=".$thisTicket);
          }
        } else {
		  // Do we have any unread messages?
		  // If yes, do we redirect to mailbox?
		  if ($USER->mailbox=='yes' && $USER->mailScreen=='yes') {
		    if (mswRowCount('mailassoc WHERE `staffID` = \''.$USER->id.'\' AND `folder` = \'inbox\' AND `status` = \'unread\'')>0) {
			  header("Location: index.php?p=mailbox");
			  exit;
			}
		  }
          header("Location: index.php");
        }
        exit;
      } else {
        $P_ERROR = $msg_login4;
      }
    }
  } else {
    header("Location: index.php?p=login");
    exit;
  }
}
  
// Are we already logged in via cookie..
if (isset($MSTEAM->name)) {
  header("Location: index.php");
  exit;
}

include(PATH.'templates/system/login.php');

?>
