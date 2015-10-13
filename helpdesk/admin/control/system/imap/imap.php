<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: imap.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  $HEADERS->err403(true);
}

// Access..
if (!in_array($cmd,$userAccess) && $MSTEAM->id!='1') {
  $HEADERS->err403(true);
}

// Class..
include_once(PATH.'control/classes/class.imap.php');
$MSIMAP  = new imap();

// Show imap folders..
if (isset($_GET['showImapFolders'])) {
  $html   = '';
  $msg    = '';
  $action = 'err';
  $_POST  = array_map('mswCleanData',$_POST);
  if (function_exists('imap_open')) {
    $mbox = @imap_open(
	 '{'.($_POST['host'] ? $_POST['host'] : 'xx').':'.($_POST['port'] ? $_POST['port'] : '1').'/imap'.
     ($_POST['flags'] ? $_POST['flags'] : '').
     '}',$_POST['user'],$_POST['pass']
    );
    if ($mbox) {
      $list = @imap_list($mbox,'{'.$_POST['host'].'}','*');
      if (is_array($list)) {
        sort($list);
		$html = '<option value="0">'.$msg_imap26.'</option>';
        foreach ($list AS $box) {
          $box   = str_replace('{'.$_POST['host'].'}','',imap_utf7_decode($box));
          $html .= '<option value="'.$box.'">'.$box.'</option>';
        }
		$action = 'ok';
      } else {
	    $msg = $msg_script_action2;
      }
      @imap_close($mbox);
      @imap_errors();
      @imap_alerts();
    } else {
	  // Mask errors to prevent callback failure..
	  @imap_errors();
      @imap_alerts();
	  $msg = $msg_script_action2;
    }
  } else {
    $msg = $msg_script_action3;
  }
  echo $JSON->encode(
   array(
    'action' => $action,
	'msg'    => $msg,
	'html'   => trim($html)
   )
  ); 
  exit;
}

// Add..
if (isset($_POST['process'])) {
  $MSIMAP->addImapAccount();
  $OK1 = true;
}
  
// Update..
if (isset($_POST['update'])) {
  $MSIMAP->editImapAccount();
  $OK2 = true;
} 
  
$title          = (isset($_GET['edit']) ? $msg_imap25 : $msg_adheader39);
$loadJQAlertify = true;
  
include(PATH.'templates/header.php');
include(PATH.'templates/system/imap/imap.php');
include(PATH.'templates/footer.php');

?>
