<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: user.php
  Description: Installer File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  msw403();
}

$data = array();

//=========================
// INSTALL USER
//=========================
  
$q = mysql_query("INSERT INTO `".DB_PREFIX."users` (
`id`, `ts`, `name`, `email`, `accpass`, `signature`, `notify`, `pageAccess`, `emailSigs`, `notePadEnable`, `delPriv`, `helplink`
) VALUES (
1, UNIX_TIMESTAMP(UTC_TIMESTAMP), '".mswSafeImportString($_POST['user'])."', '".mswSafeImportString($_POST['email'])."', 
'".md5(SECRET_KEY.$_POST['pass'])."', '', 'yes', '', 'no', 'yes', 'yes', 'yes'
)");
  
if (!$q) {
  $data[]  = DB_PREFIX.'users';
  mswlogDBError(DB_PREFIX.'users',mysql_error(),mysql_errno(),__LINE__,__FILE__,'Insert');
}

?>
