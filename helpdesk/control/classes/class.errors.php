<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk
  
  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: class.errors.php
  Description: Class File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

// Set error handler preferences..
define('ERR_HANDLER_PATH', substr(dirname(__file__),0,strpos(dirname(__file__),'control')-1).'/'); // DO NOT change!!
define('ERR_HANDLER_LOG_FOLDER', 'logs'); // Name of logs folder..
define('ERR_HANDLER_ENABLED', 1); // Enable custom error handler?
define('ERR_HANDLER_DISPLAY', 1); // Display a message on screen?
define('ERR_APPEND_RAND_STRING', 1); // Adds random string to file name for security. Prevents someone attempting browser access.
define('MASK_FILE_PATH', 0); // Hide file path if error occurs..
define('FILE_ERR_LOG_TXT', 'error_log.txt'); // File name of error log
define('FILE_FATAL_ERR_LOG_TXT', 'fatal_error_log.txt'); // File name of fatal error log
  
class msErrs  {

public function generalErr($error) {
  msErrs::log('error_log',$error);
}

public function mailErr($error) {
  msErrs::log('mail_error_log',$error);
}

public function fatalErr($error) {
  msErrs::log('fatal_error_log',$error);
}

public function log($type,$error) {
  if (is_dir(ERR_HANDLER_PATH.ERR_HANDLER_LOG_FOLDER)) {
    file_put_contents(ERR_HANDLER_PATH.ERR_HANDLER_LOG_FOLDER.'/'.msErrs::raStr().$type.'.txt',trim($error).PHP_EOL.'- - - - - - - - - - - - - - - - - - -'.PHP_EOL, FILE_APPEND);
  }
}

public function raStr() {
  return (ERR_APPEND_RAND_STRING ? '' : '');
}

}

// Initiate the class..
$MSEH = new msErrs();

if (ERR_HANDLER_ENABLED) {
  // Switch off display errors..
  @ini_set('display_errors', 0);
  // Set error reporting level..
  error_reporting(E_ALL);
}

function msFatalErr() {
  global $MSEH;
  $error = error_get_last();
  if ($error['type']==E_ERROR) {
    $string  = '[Error Code: '.$error['type'].'] '.$error['message'].PHP_EOL;
	$string .= '[Date/Time: '.date('j F Y @ H:iA').']'.PHP_EOL;
    $string .= '[Fatal error on line '.$error['line'].' in file '.$error['file'].']';
	if (ERR_HANDLER_DISPLAY) {
      echo '<div style="background:#ff9999"><p style="padding:10px;color:#fff">A fatal error has occured. For more details please view "'.ERR_HANDLER_LOG_FOLDER.'/'.FILE_FATAL_ERR_LOG_TXT.'".</div>';
	}
	$MSEH->fatalErr($string);
  }
}

function msErrorhandler($errno, $errstr, $errfile, $errline) {
  global $MSEH;
  if (!(error_reporting() & $errno)) {
    return;
  }
  switch ($errno) {
    case E_USER_ERROR:
	$string  = '[Error Code: '.$errno.'] '.$errstr.PHP_EOL;
	$string .= '[Date/Time: '.date('j F Y @ H:iA').']'.PHP_EOL;
    $string .= '[Error on line '.$errline.' in file '.$errfile.']';
	if (ERR_HANDLER_DISPLAY) {
      echo '<div style="background:#ff9999"><p style="padding:10px;color:#fff">A fatal error has occured. For more details please view "'.ERR_HANDLER_LOG_FOLDER.'/'.FILE_FATAL_ERR_LOG_TXT.'".</div>';
	}
	$MSEH->fatalErr($string);
	exit;
    break;

    case E_USER_WARNING:
    $string  = '[Error Code: '.$errno.'] '.$errstr;
	$string .= '[Date/Time: '.date('j F Y @ H:iA').']'.PHP_EOL;
	$string .= '[Error on line '.$errline.' in file '.$errfile.']';
	if (ERR_HANDLER_DISPLAY) {
	  echo '<div style="background:#ff9999"><p style="padding:10px;color:#fff">An error has occured. For more details please view "'.ERR_HANDLER_LOG_FOLDER.'/'.FILE_ERR_LOG_TXT.'".</div>';
	}
	$MSEH->generalErr($string);
	break;

    case E_USER_NOTICE:
    $string  = '[Error Code: '.$errno.'] '.$errstr.PHP_EOL;
	$string .= '[Date/Time: '.date('j F Y @ H:iA').']'.PHP_EOL;
	$string .= '[Error on line '.$errline.' in file '.$errfile.']';
	if (ERR_HANDLER_DISPLAY) {
	  echo '<div style="background:#ff9999"><p style="padding:10px;color:#fff">An error has occured. For more details please view "'.ERR_HANDLER_LOG_FOLDER.'/'.FILE_ERR_LOG_TXT.'".</div>';
	}
	$MSEH->generalErr($string);
	break;

    default:
    $string  = '[Error Code: '.$errno.'] '.$errstr.PHP_EOL;
	$string .= '[Date/Time: '.date('j F Y @ H:iA').']'.PHP_EOL;
	$string .= '[Error on line '.$errline.' in file '.$errfile.']';
	if (ERR_HANDLER_DISPLAY) {
	  echo '<div style="background:#ff9999"><p style="padding:10px;color:#fff">An error has occured. For more details please view "'.ERR_HANDLER_LOG_FOLDER.'/'.FILE_ERR_LOG_TXT.'".</div>';
	}
	$MSEH->generalErr($string);
	break;
  }
  return true;	
}
 
?>