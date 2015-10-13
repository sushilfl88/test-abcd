<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: ticket-ajax.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  $HEADERS->err403(true);
}

// Access..
if (!in_array($cmd,$userAccess) && $MSTEAM->id!='1') {
  $HEADERS->err403(true);
}

// Delete attachments
if (isset($_GET['delAttach']) && isset($_GET['t']) && isset($_GET['r'])) {
  $acount    = 0;
  $text      = str_replace('{count}','0',$msg_viewticket41);
  $ids       = array();
  if (!empty($_POST['attachments'])) {
    $ids   = $MSTICKET->deleteAttachments();
	$acount = mswRowCount('attachments WHERE `ticketID` = \''.(int)$_GET['t'].'\' AND `replyID` = \''.(int)$_GET['r'].'\'');
	if ($acount>0) {
	  $text = str_replace('{count}',$acount,'<a id="link'.$_GET['t'].'_'.$_GET['r'].'" href="#" onclick="jQuery(\'#attachments_'.$_GET['t'].'_'.$_GET['r'].'\').slideDown(\'slow\');return false">'.$msg_viewticket41.'</a>');
	}
  }
  echo $JSON->encode(
   array(
    'count' => $acount,
	'ids'   => (!empty($ids) ? implode(',',$ids) : 'none'),
	'text'  => '<i class="icon-paper-clip"></i> '.$text
   )
  ); 
  exit;
}
  
// We stop here..
exit;

?>
