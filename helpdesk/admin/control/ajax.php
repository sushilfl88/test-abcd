<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: ajax.php
  Description: System File

  Ajax Ops
  
  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || !isset($_GET['ajax'])) {
  $HEADERS->err403(true);
}

// Load classes not loaded by main system..
include(REL_PATH.'control/classes/class.accounts.php');
include(PATH.'control/classes/class.accounts.php');
$MSACC           = new accountSystem();
$MSACC->settings = $SETTINGS;
$MSPTL           = new accounts();
$MSPTL->settings = $SETTINGS;

// Parse based on directive..
switch ($_GET['ajax']) {

  //=========================
  // Ticket delete ops..
  //=========================
  
  case 'delete':
  if (USER_DEL_PRIV=='yes') {
    switch ($_GET['type']) {
      // Ticket reply..
      case 'reply':
	  $ID  = (int)$_GET['id'];
      $RP  = mswGetTableData('replies','id',$ID);
	  $TK  = mswGetTableData('tickets','id',$RP->ticketID);
	  switch ($RP->replyType) {
	    case 'admin':
		$NME  = mswGetTableData('users','id',$RP->replyUser);
	    break;
		case 'visitor':
		$NME  = mswGetTableData('portal','id',$RP->replyUser);
	    break;
	  }
	  if (isset($TK->id)) {
        $rows = $MSTICKET->deleteReply($RP,$TK,$ID);
	    // History log..
        if ($rows>0) {
          $MSTICKET->historyLog(
	       $TK->id,
	       str_replace(
	        array('{user}','{id}','{poster}'),
	        array($MSTEAM->name,$ID,(isset($NME->name) ? $NME->name : 'N/A')),
	        $msg_ticket_history['reply-delete']
	       )
	      );
        }
	  }
	  break;
    }
    $json = array('msg' => 'ok');
  }
  break;
  
  //===========================
  // Update ticket notes..
  //===========================
  
  case 'ticket-notes':
  $ID   = (isset($_GET['ticketNotes']) ? (int)$_GET['ticketNotes'] : '0');
  if (isset($_POST['notes']) && $ID>0) {
    $rows = $MSTICKET->updateTicketNotes($ID);
    // History log..
    if ($rows>0) {
      $MSTICKET->historyLog(
	   $ID,
	   str_replace(
	    array('{user}'),
	    array($MSTEAM->name),
	    $msg_ticket_history['ticket-notes-edit']
	   )
	  );
    }
  }
  $json = array('ok');
  break;
  
  //================================================
  // Assign staff to tickets via ticket screen..
  //================================================
  
  case 'assign-staff':
  $u   = array();
  $ID  = (isset($_GET['ticketAssigned']) ? (int)$_GET['ticketAssigned'] : '0');
  if (!empty($_POST['assigned']) && $ID>0) {
    for ($i=0; $i<count($_POST['assigned']); $i++) {
      $u[] = $_POST['assigned'][$i]['value'];
    }
  }
  if (!empty($u)) {
    $MSTICKET->ticketUserAssign($ID,implode(',',$u),
     str_replace(
      array('{users}','{admin}'),
	  array($MSTICKET->assignedTeam(implode(',',$u)),$MSTEAM->name),
	  $msg_ticket_history['assign-update']
     )
    );
  }
  $json = array('ok'); 
  break;
  
  //===========================
  // Password generator..
  //===========================
  
  case 'passgen':
  $pass = $MSACC->ms_generate();
  $json = array('pass' => $pass);
  break;
  
  //========================
  // Account search..
  //========================
  
  case 'account-search':
  $json = $MSPTL->search();
  break;
  
  //=============================
  // Dispute account search..
  //=============================
  
  case 'dispute-users':
  $searched = $MSTICKET->searchDisputeUsers();
  if (empty($searched)) {
    $json = array('text' => $msg_viewticket117);
  } else {
    $json = $searched;
  }
  break;
  
  //==========================
  // Delete history entry..
  //==========================
  
  case 'delete-history':
  $json = array('ok');
  if (USER_DEL_PRIV=='yes') {
    $MSTICKET->deleteTicketHistory();
    $json = array(
     'resp'  => 'ok',
     'text'  => $msg_viewticket111
    );
  }
  break;
  
  //===========================
  // Load standard response..
  //===========================
  
  case 'response':
  $json = array('ok');
  if (isset($_GET['getResponse'])) {
    $SR   = mswGetTableData('responses','id',(int)$_GET['getResponse']);
    $json = array('response' => (isset($SR->answer) ? mswCleanData($SR->answer) : '&nbsp;'));
  }
  break;
  
  //===========================
  // Add ticket custom fields
  //===========================
  
  case 'add-cus-field':
  $fields = '';
  $dept   = (int)$_GET['dept'];
  $area   = (!isset($_GET['area']) ? 'ticket' : (in_array($_GET['area'],array('ticket','reply','admin')) ? $_GET['area'] : 'ticket'));
  // Custom fields..
  $qF = mysql_query("SELECT * FROM `".DB_PREFIX."cusfields`
        WHERE FIND_IN_SET('{$area}',`fieldLoc`)  > 0
        AND `enField`                            = 'yes'
		AND FIND_IN_SET('{$dept}',`departments`) > 0
        ORDER BY `orderBy`
        ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  if (mysql_num_rows($qF)>0) {
    while ($FIELDS = mysql_fetch_object($qF)) {
      switch ($FIELDS->fieldType) {
        case 'textarea':
        $fields .= $MSFM->buildTextArea(mswCleanData($FIELDS->fieldInstructions),$FIELDS->id,(++$tabIndex));
        break;
        case 'input':
        $fields .= $MSFM->buildInputBox(mswCleanData($FIELDS->fieldInstructions),$FIELDS->id,(++$tabIndex));
        break;
        case 'select':
        $fields .= $MSFM->buildSelect(mswCleanData($FIELDS->fieldInstructions),$FIELDS->id,$FIELDS->fieldOptions,(++$tabIndex));
        break;
        case 'checkbox':
        $fields .= $MSFM->buildCheckBox(mswCleanData($FIELDS->fieldInstructions),$FIELDS->id,$FIELDS->fieldOptions);
        break;
      }
    }
  }
  $json   = array('fields' => $fields);
  break;
  
  //=======================
  // Delete attachments
  //=======================
  
  case 'del-attach':
  $json = array('count' => 0, 'ids' => 'err', 'text' => '');
  if (isset($_GET['t']) && isset($_GET['r'])) {
    $acount  = 0;
    $text    = str_replace('{count}','0',$msg_viewticket41);
    $ids     = array();
	$rID     = (int)$_GET['r'];
	$tID     = (int)$_GET['t'];
    if (!empty($_POST['attachments'])) {
      $ids   = $MSTICKET->deleteAttachments();
	  $acount = mswRowCount('attachments WHERE `ticketID` = \''.$tID.'\' AND `replyID` = \''.$rID.'\'');
	  if ($acount>0) {
	    $text = str_replace('{count}',$acount,'<a id="link'.$tID.'_'.$rID.'" href="#" onclick="jQuery(\'#attachments_'.$tID.'_'.$rID.'\').slideDown(\'slow\');return false">'.$msg_viewticket41.'</a>');
	  }
    }
    $json = array(
     'count' => $acount,
	 'ids'   => (!empty($ids) ? implode(',',$ids) : 'none'),
	 'text'  => '<i class="icon-paper-clip"></i> '.$text
    ); 
  }
  break;
  
  //======================
  // Mail Test
  //======================
  
  case 'mailtest':
  include(REL_PATH.'control/mail-data.php');
  $cnt    = 0;
  $others = '';
  if (isset($_POST['emails'])) {
    $list = array_map('trim',explode(',',$_POST['emails']));
	if (!empty($list)) {
	  $cnt   = count($list);
	  $first = $list[0];
	  unset($list[0]);
	  if (!empty($list)) {
	    $others = implode(',',$list);
	  }
	  // Send test..
	  $MSMAIL->sendMSMail(
	   array(
	    'from_email' => $SETTINGS->email,
		'from_name'  => $SETTINGS->website,
		'to_email'   => $first,
		'to_name'    => $SETTINGS->website,
		'subject'    => str_replace(
		 array('{website}'),
		 array($SETTINGS->website),
		 $emailSubjects['test-message']
		),
		'replyto'    => array(
	     'name'      => $SETTINGS->website,
	     'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email) 
	    ),
		'template'   => str_replace('{website}',$SETTINGS->website,$msg_script_action10),
		'language'   => $SETTINGS->language,
		'alive'      => ($others ? 'yes' : 'no'),
		'add-emails' => $others
	   )
	  );
	}
  }
  $json = array(
   'msg' => str_replace('{count}',$cnt,$msg_script_action9)
  ); 
  break;
  
  //==================
  // Auto Path
  //==================
  
  case 'autopath':
  switch ($_GET['type']) {
    case 'http':
	$svr  = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	$path = 'http://'.substr($svr,0,strpos($svr,$SETTINGS->afolder)).'content/'.$msg_settings128;
	break;
	default:
	$spt  = PATH;
	$path = substr($spt,0,strpos($spt,$SETTINGS->afolder)).'content'.(strpos($spt,':')!==false ? '\\' : '/').$msg_settings128;
	break;
  }
  $json = array(
   'path' => $path
  );
  break;

}

// We stop here and parse json response..
echo $JSON->encode($json);
exit;

?>
