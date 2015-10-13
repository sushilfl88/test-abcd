<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: reset.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  $HEADERS->err403(true);
}

// Permissions..
if (!defined('PASS_RESET')) {
  $HEADERS->err403(true,'This page cannot be accessed.<br>Refer to the <a href="../docs/reset.html" onclick="window.open(this);return false">documentation</a> on how to access the reset page');
}

// Update..
if (isset($_POST['process'])) {
  $ret = $MSUSERS->reset();
  if (isset($_POST['email']) && !empty($ret)) {
    // Load mail params
    include(REL_PATH.'control/mail-data.php');
	for ($i=0; $i<count($ret); $i++) {
	  $q       = mysql_query("SELECT `id`,`name`,`email`,`email2` FROM `".DB_PREFIX."users`
                 WHERE `id` = '{$ret[$i]['id']}'
				 ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
      while ($USERS = mysql_fetch_object($q)) {
        $MSMAIL->addTag('{NAME}', $USERS->name);
        $MSMAIL->addTag('{EMAIL}', $USERS->email);
        $MSMAIL->addTag('{PASS}', $ret[$i]['pass']);
        // Send mail..
	    $MSMAIL->sendMSMail(
	     array(
	      'from_email' => $SETTINGS->email,
		  'from_name'  => $SETTINGS->website,
		  'to_email'   => $USERS->email,
		  'to_name'    => $USERS->name,
		  'subject'    => str_replace(
		   array('{website}','{user}'),
		   array(
		    $SETTINGS->website,
		    $USERS->name
		   ),
		   $emailSubjects['reset']
		  ),
		  'replyto'    => array(
	       'name'      => $SETTINGS->website,
	       'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email) 
	      ),
		  'template'   => LANG_PATH.'admin-pass-reset.txt',
		  'language'   => $SETTINGS->language,
		  'alive'      => 'yes',
		  'add-emails' => $USERS->email2
	     )
	    );
	  }
	}
  }
  $OK = true;
}

$title          = $msg_adheader36;
$loadJQAlertify = true;

if (file_exists(PATH.'templates/reset.php')) {
  define('RESET_LOADER', 1);
  include(PATH.'templates/reset.php');
} else {
  $HEADERS->err403(true,'Reset template file is missing. Did you rename it?<br>Refer to the <a href="../docs/reset.html" onclick="window.open(this);return false">documentation</a>.');
}

?>
