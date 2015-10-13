<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: account-global.php
  Description: System File
  
  Data here is applicable to view ticket/dispute and create ticket screens

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || !defined('MS_PERMISSIONS')) {
  $HEADERS->err403();
}

// JS/CSS..
$ms_js_css_loader['bbcode']   = 'yes';
$ms_js_css_loader['alertify'] = 'yes';

// Attachment types seperator..
$ATTACH_TYPES_SEPERATOR       = '&nbsp;,&nbsp;';

// Attachment restrictions..
$attachRestrictions = '';
if ($SETTINGS->filetypes) {
  $attachRestrictions = str_replace(
   array('{text}','{info}'),
   array(
    $msg_newticket34,
	str_replace('|',$ATTACH_TYPES_SEPERATOR,str_replace('.','',$SETTINGS->filetypes))
   ),
   file_get_contents(PATH.'content/'.MS_TEMPLATE_SET.'/html/ticket-attachment-restrictions.htm')
  );
}
if ($SETTINGS->maxsize>0 || $SETTINGS->attachboxes>0) {
  $attachRestrictions .= str_replace(
   array('{text}','{info}'),
   array(
    $msg_newticket35,
	($SETTINGS->attachboxes>0 ? $SETTINGS->attachboxes : $msg_public_ticket2).' / '.($SETTINGS->maxsize>0 ? mswFileSizeConversion($SETTINGS->maxsize) : $msg_public_ticket2)
   ),
   file_get_contents(PATH.'content/'.MS_TEMPLATE_SET.'/html/ticket-attachment-restrictions.htm')
  );
}

// System messages..
if (isset($_GET['msg'])) {
  switch ($_GET['msg']) {
    case 'added':
	$ticketSystemMsg = $msg_public_ticket12;
	break;
	case 'replied':
	$ticketSystemMsg = $msg_showticket7;
	break;
  }
}

?>
