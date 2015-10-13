<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: class.headers.php
  Description: Class File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

class htmlHeaders  {

public function err403($admin=false,$text='') {
  //var_dump(debug_backtrace());
  global $msg_charset,$html_lang,$msg_home11,$msg_script52,$msg_script53,$msg_script54,$SETTINGS;
  header('HTTP/1.0 403 Forbidden');
  header('Content-type: text/html; charset='.$msg_charset);
  if ($admin) {
    $f = array('{charset}','{lang}','{back}','{error}','{oops}');
	$r = array($msg_charset,$html_lang,$msg_script53,($text ? $text : $msg_home11),$msg_script52);
    echo (file_exists(PATH.'templates/system/headers/403.php') ? str_replace($f,$r,file_get_contents(PATH.'templates/system/headers/403.php')) : '403: Forbidden');
  } else {
    if (!class_exists('Savant3')) {
	  include(PATH.'control/lib/Savant3.php');
	}
	$tpl  = new Savant3();
	$tpl->assign('LANG', $html_lang);
    $tpl->assign('CHARSET', $msg_charset);
    $tpl->assign('SETTINGS', $SETTINGS);
	$tpl->assign('TXT',array($msg_script52,$msg_home11,$msg_script54));
    $tpl->display('content/'.(defined(MS_TEMPLATE_SET) ? MS_TEMPLATE_SET : '_default_set').'/headers/403.tpl.php');
  }
  exit;
}

public function err404($admin=false,$text='') {
  global $msg_charset,$html_lang,$msg_script6,$msg_script52,$msg_script54,$SETTINGS;
  header('HTTP/1.0 404 Not Found');
  header('Content-type: text/html; charset='.$msg_charset);
  if ($admin) {
    $f = array('{charset}','{lang}','{back}','{error}','{oops}');
	$r = array($msg_charset,$html_lang,$msg_script54,($text ? $text : $msg_script6),$msg_script52);
	echo (file_exists(PATH.'templates/system/headers/404.php') ? str_replace($f,$r,file_get_contents(PATH.'templates/system/headers/404.php')) : '404: Page Not Found');
  } else {
    if (!class_exists('Savant3')) {
	  include(PATH.'control/lib/Savant3.php');
	}
	$tpl  = new Savant3();
	$tpl->assign('LANG', $html_lang);
    $tpl->assign('CHARSET', $msg_charset);
    $tpl->assign('SETTINGS', $SETTINGS);
	$tpl->assign('TXT',array($msg_script52,$msg_script6,$msg_script54));
    $tpl->display('content/'.(defined(MS_TEMPLATE_SET) ? MS_TEMPLATE_SET : '_default_set').'/headers/404.tpl.php');
  }
  exit;
}

}
 
?>