<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: class.bootstrap.php
  Description: Class File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

class msBootStrap {

// Drop down button..
public function button($text,$links=array(),$area='admin') {
  $html = '';
  switch ($area) {
    case 'admin':
	$button = file_get_contents(PATH.'templates/system/bootstrap/drop-down-button.htm');
	$link   = file_get_contents(PATH.'templates/system/bootstrap/drop-down-button-li.htm');
	break;
	default:
	$button = file_get_contents(PATH.'content/'.MS_TEMPLATE_SET.'/html/bootstrap/drop-down-button.htm');
	$link   = file_get_contents(PATH.'content/'.MS_TEMPLATE_SET.'/html/bootstrap/drop-down-button-li.htm');
	break;
  }
  foreach ($links AS $l => $v) {
    $html .= str_replace(array('{link}','{text}'),array($v['link'],$v['name']),$link);
  }
  return str_replace(array('{text}','{links}'),array($text,trim($html)),$button);
}

}

?>