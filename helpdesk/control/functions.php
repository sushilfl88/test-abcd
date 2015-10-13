<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  This File: functions.php
  Description: Functions

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

// Database connection..
function mswDBConnector() {
  $connect = @mysql_connect(trim(DB_HOST),trim(DB_USER),trim(DB_PASS));
  if (!$connect) {
	  die((ENABLE_MYSQL_ERRORS ? 'Code: '.mysql_errno().' Error: '.mysql_error() : MYSQL_DEFAULT_ERROR));
  }
  if ($connect && !mysql_select_db(trim(DB_NAME),$connect)) {
    die((ENABLE_MYSQL_ERRORS ? 'Code: '.mysql_errno().' Error: '.mysql_error() : MYSQL_DEFAULT_ERROR));
  }
  if ($connect) {
    // Character set..
    if (defined('DB_CHAR_SET') && DB_CHAR_SET) {
      if (strtolower(DB_CHAR_SET)=='utf-8') {
        $change = 'utf8';
      }
      @mysql_query("SET CHARACTER SET '".(isset($change) ? $change : DB_CHAR_SET)."'");
      @mysql_query("SET NAMES '".(isset($change) ? $change : DB_CHAR_SET)."'");
    }
    // Locale..
    if (defined('DB_LOCALE')) {
      if (DB_CHAR_SET && DB_LOCALE) {
        @mysql_query("SET `lc_time_names` = '".DB_LOCALE."'");
      }
    }
  }
}

// Clean characters..
function mswCleanFile($file) {
  return preg_replace("/[&'#]/","_",$file);
}

// Fixes settings fields if manual schema was run..
function mswManSchemaFix($s) {
  if ($s->email=='' && $s->scriptpath=='' && $s->attachpath=='' && $s->attachhref=='') {
    $hdeskPath = 'http://www.example.com/helpdesk';
    if (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['PHP_SELF'])) {
      $hdeskPath = 'http'.(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 's' : '').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,-10);
    }
    $hdeskPathAtt = $hdeskPath.'/content/attachments';
    $hdeskPathFaq = $hdeskPath.'/content/attachments-faq';
	$attachPath   = mswSafeImportString(PATH.'content/attachments');
	$attFaqPath   = mswSafeImportString(PATH.'content/attachments-faq');
	$apiKey       = strtoupper(substr(md5(uniqid(rand(),1)),3,10).'-'.substr(md5(uniqid(rand(),1)),3,8));
    mysql_query("UPDATE `".DB_PREFIX."settings` SET
    `website`             = 'My Help Desk',
    `email`               = 'email@example.com',
    `scriptpath`          = '{$hdeskPath}',
    `attachpath`          = '{$attachPath}',
	`attachhref`          = '{$hdeskPathAtt}',
	`attachpathfaq`       = '{$attFaqPath}',
	`attachhreffaq`       = '{$hdeskPathFaq}',
    `adminFooter`         = 'To add your own footer code, click &quot;Settings &amp; Tools > Other Options > Edit Footers&quot;',
    `publicFooter`        = 'To add your own footer code, click &quot;Settings &amp; Tools > Other Options > Edit Footers&quot;',
    `prodKey`             = '".mswProdKeyGen()."',
    `encoderVersion`      = '".(function_exists('ioncube_loader_version') ? ioncube_loader_version() : 'XX')."',
    `softwareVersion`     = '".SCRIPT_VERSION."',
	`apiKey`              = '{$apiKey}'
    LIMIT 1
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    // Insert user..
    if (mswRowCount('users')==0) {
      mysql_query("INSERT INTO `".DB_PREFIX."users` (
      `id`, `ts`, `name`, `email`, `accpass`, `signature`, `notify`, `pageAccess`, `emailSigs`, `notePadEnable`, `delPriv`,
      `nameFrom`, `emailFrom`, `assigned`, `timezone`
      ) VALUES (
      1, UNIX_TIMESTAMP(UTC_TIMESTAMP), 'admin', 'admin@example.com', '".md5(SECRET_KEY.'admin')."', '', 'yes', '', 'no', 'yes', 'yes',
      '', '', 'no', 'Europe/London'
      )");
    } else {
	  mysql_query("UPDATE `".DB_PREFIX."users` SET
	  `accpass`  = '".md5(SECRET_KEY.'admin')."'
	  WHERE `id` = '1'
	  ");
	}
    // Page reload..
    header("Location: index.php");
    exit;
  }
}

// Action message..
function mswActionMessage($text) {
  return str_replace('{text}',$text,file_get_contents(PATH.'content/'.MS_TEMPLATE_SET.'/html/action-message.htm'));
}

// Action message..
function mswActionMessageWarning($text) {
  return str_replace('{text}',$text,file_get_contents(PATH.'content/'.MS_TEMPLATE_SET.'/html/action-message-warning.htm'));
}

// DB Schema..
function mswDBSchemaArray() {
  $tbl = array();
  if (strlen(DB_PREFIX)>0) {
    $q = mysql_query("SHOW TABLES WHERE SUBSTRING(`Tables_in_".DB_NAME."`,1,".strlen(DB_PREFIX).") = '".DB_PREFIX."'")
         or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  } else {
    $q = mysql_query("SHOW TABLES")
         or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  }
  while ($TABLES = mysql_fetch_object($q)) {
    $field = 'Tables_in_'.strtolower(DB_NAME);
    $tbl[] = $TABLES->{$field};
  }
  return $tbl;
}

// Generates 60 character product key..
function mswProdKeyGen() {
  $_SERVER['HTTP_HOST']    = (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : uniqid(rand(),1));
  $_SERVER['REMOTE_ADDR']  = (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : uniqid(rand(),1));
  if (function_exists('sha1')) {
    $c1       = sha1($_SERVER['HTTP_HOST'].date('YmdHis').$_SERVER['REMOTE_ADDR'].time());
    $c2       = sha1(uniqid(rand(),1).time());
    $prodKey  = substr($c1.$c2,0,60);
  } elseif (function_exists('md5')) {
    $c1       = md5($_SERVER['HTTP_POST'].date('YmdHis').$_SERVER['REMOTE_ADDR'].time());
    $c2       = md5(uniqid(rand(),1),time());
    $prodKey  = substr($c1.$c2,0,60);
  } else {
    $c1       = str_replace('.','',uniqid(rand(),1));
    $c2       = str_replace('.','',uniqid(rand(),1));
    $c3       = str_replace('.','',uniqid(rand(),1));
    $prodKey  = substr($c1.$c2.$c3,0,60);
  }
  return strtoupper($prodKey);
}

// Login credentials..
function mswIsUserLoggedIn() {
  return (isset($_SESSION[md5(SECRET_KEY).'_msw_support']) &&
    mswIsValidEmail($_SESSION[md5(SECRET_KEY).'_msw_support']) &&
    mswRowCount('portal WHERE `email` = \''.$_SESSION[md5(SECRET_KEY).'_msw_support'].'\' AND `verified` = \'yes\'')>0 ?
    $_SESSION[md5(SECRET_KEY).'_msw_support'] :
    'guest'
  );
}

// Check valid email..
function mswIsValidEmail($em) {
  if (function_exists('filter_var') && !filter_var($em,FILTER_VALIDATE_EMAIL)) {
    return false;
  } else {
    if (!preg_match("/^[_.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z.-]+.)+[a-zA-Z]{2,6}$/i", $em)) {
      return false;
    }
  }
  return true;
}

// New line to break..
function mswNL2BR($text) {
  // Second param added in 5.3.0, else its not available..
  if (version_compare(phpversion(),'5.3.0','<')) {
    return str_replace(mswDefineNewline(),'<br>',$text);
  }
  return nl2br($text,false);
}

// Detect SSL..
function mswDetectSSLConnection() {
  return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'yes' : 'no');
}

// Variable sanitation..
function mswDigit($no) {
  return (int)$no;
}

// Catch MySQL errors..
function mswMysqlErrMsg($code,$error,$file,$line) {
  global $msg_script61,$SETTINGS;
  if (ENABLE_MYSQL_ERRORS) {
    echo '<p style="color:red;border:2px solid red;padding:10px;font-size:12px;line-height:20px;background:#f2f2f2 url('.(isset($SETTINGS->scriptpath) ? $SETTINGS->scriptpath.'/' : '').'content/images/alert.png) no-repeat 98% 50%">';
    echo '<b style="color:black">MYSQL DATABASE ERROR:</b><br>';
    echo '<b>'.(isset($msg_script61[0]) ? $msg_script61[0] : 'Code').'</b>: '.$code.'<br>';
    echo '<b>'.(isset($msg_script61[1]) ? $msg_script61[1] : 'Error').'</b>: '.$error.'<br>';
    echo '<b>'.(isset($msg_script61[2]) ? $msg_script61[2] : 'File').'</b>: '.$line.'<br>';
    echo '<b>'.(isset($msg_script61[3]) ? $msg_script61[3] : 'Line').'</b>: '.$file.'<br>';
    echo '</p>';
  } else {
    echo MYSQL_DEFAULT_ERROR;
  }
  exit;
}

// Reverses ticket number and removes zeros..
function mswReverseTicketNumber($num) {
  return ltrim($num,'0');
}

// File size..
function mswFileSizeConversion($size,$precision=2) {
  if ($size>0) {
    $base     = log($size)/log(1024);
    $suffixes = array('Bytes','KB','MB','GB','TB');
    return round(pow(1024,$base-floor($base)),$precision).$suffixes[floor($base)];
  } else {
    return '0Bytes';
  }
}

// Check valid query..
function checkIsValid($data) {
  if (!isset($data->id)) {
    exit;
  }
}

// Digit check..
function mswCheckDigit($id,$admin=false) {
  if ((int)$id==0) {
    global $HEADERS;
	if (class_exists('htmlHeaders')) {
      $HEADERS->err404($admin);
	} else {
      header('HTTP/1.0 404 Not Found');
      header('Content-type: text/plain; charset=utf-8');
      echo '<h1>404</h1>';
	}
    exit;
  }
}

// Calculate ticket number based on digits..
function mswTicketNumber($num) {
  global $SETTINGS;
  $zeros = '';
  if ($SETTINGS->minTickDigits>0 && $SETTINGS->minTickDigits>strlen($num)) {
    for ($i=0; $i<$SETTINGS->minTickDigits-strlen($num); $i++) {
      $zeros .= 0;
    }
  }
  return $zeros.$num;
}

// Yes/No..
function ms_YesNo($flag) {
  global $msg_script4,$msg_script5;
  return ($flag=='yes' ? $msg_script4 : $msg_script5);
}

// Gets data based on param criteria..
function mswGetTableData($table,$row,$val,$and='',$params='*') {
  $q = mysql_query("SELECT $params FROM `".DB_PREFIX.$table."`
       WHERE `".$row."`  = '{$val}'
       $and
       LIMIT 1
       ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  return mysql_fetch_object($q);
}

// Gets row count..
function mswRowCount($table,$where='',$format=true) {
  $q = mysql_query("SELECT count(*) AS `r_count` FROM ".DB_PREFIX.$table.$where)
       or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  $r = mysql_fetch_object($q);
  if ($format) {
    return @number_format($r->r_count);
  } else {
    return $r->r_count;
  }
}

// Clean data..
function mswCleanData($data) {
  if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
    $sybase  = strtolower(@ini_get('magic_quotes_sybase'));
    if (empty($sybase) || $sybase=='off') {
      // Fixes issue of new line chars not parsing between single quotes..
      $data = str_replace('\n','\\\n',$data);
      return stripslashes($data);
    }
  }
  return trim($data);
}

// Gets visitor IP address..
function mswIPAddresses() {
  $ip = array();
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip[] = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'],',')!==FALSE) {
      $split = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
      foreach ($split AS $value) {
        $ip[] = $value;
      }
    } else {
      $ip[] = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
  } else {
    $ip[] = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
  }
  return (!empty($ip) ? implode(', ',$ip) : '');
}

// Define newline..
function mswDefineNewline() {
  if (defined('PHP_EOL')) {
    return PHP_EOL;
  }
  $unewline = "\r\n";
  if (strstr(strtolower($_SERVER["HTTP_USER_AGENT"]), 'win')) {
    $unewline = "\r\n";
  } else if (strstr(strtolower($_SERVER["HTTP_USER_AGENT"]), 'mac')) {
    $unewline = "\r";
  } else {
    $unewline = PHP_EOL;
  }
  return $unewline;
}

// Append url parameter..
function mswUrlApp($var,$ampersand=true) {
  return (isset($_GET[$var]) ? ($ampersand ? '&amp;' : '').$var.'='.mswCleanData($_GET[$var]) : '');
}

// Return selected option..
function mswSelectedItem($var,$compare,$get=false) {
  if ($get) {
    return (isset($_GET[$var]) && $_GET[$var]==$compare ? ' selected="selected"' : '');
  } else {
    return ($var==$compare ? ' selected="selected"' : '');
  }
}

// Check encoding..
function mswUTF8($in, $encoding) {
	$encoding = strtoupper($encoding);
  switch($encoding) {
		case 'UTF-8':
			return $in;
      break;
		case 'ISO-8859-1':
			return utf8_encode($in);
			break;
		default:
			return iconv($encoding, 'UTF-8', $in);
			break;
	}
}

// Return checked option based on array..
function mswCheckedArrItem($arr,$value) {
  return (in_array($value,$arr) ? ' checked="checked"' : '');
}

// Parse url for query string params..
function mswQueryParams($skip=array(),$start='no',$escape='yes') {
  $s = '';
  if (!empty($_GET)) {
    foreach ($_GET AS $gK => $gV) {
	  // Check for array elements in query string..
      if (is_array($gV)) {
        foreach ($gV AS $gKA => $gVA) {
	      if (!in_array($gK,$skip)) {
	        $s .= ($escape=='yes' ? '&amp;' : '&').$gK.'[]='.urlencode(mswCleanData($gVA));
		  }
        }
      } else {
	    if (!in_array($gK,$skip)) {
	      $s .= ($escape=='yes' ? '&amp;' : '&').$gK.'='.urlencode(mswCleanData($gV));
	    }
      }
    }
  }
  return ($start=='yes' ? substr($s,5) : $s);
}

// Convert bad multibyte chars..
function mswStripMultibyteChars($str) {
  $result = '';
  $length = strlen($str);
  for ($i = 0; $i < $length; $i++){
    $ord = ord($str[$i]);
    if ($ord >= 240 && $ord <= 244) {
      $result .= '?';
      $i += 3;
    } else {
      $result .= $str[$i];
    }
  }
  return $result;
}

// Safe mysql import..none array..
function mswSafeImportString($data) {
  if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
    $sybase  = strtolower(@ini_get('magic_quotes_sybase'));
    if (empty($sybase) || $sybase=='off') {
      $data  = stripslashes($data);
    } else {
      $data  = mswRemoveDoubleApostrophes($data);
    }
  }
  // Strip bad multibyte characters and replace with ?.
  if (DB_CHAR_SET=='utf8') {
    $q   = @mysql_query("SELECT VERSION() AS `v`");
    $VS  = @mysql_fetch_object($q);
	if (isset($VS->v) && $VS->v<='5.5.3') {
      $data = mswStripMultibyteChars($data);
	}
  }
  // Fix microsoft word smart quotes..
  $data  = mswConvertSmartQuotes($data);
  return mysql_real_escape_string($data);
}

// Special char..
function mswSpecialChars($data,$entities=true) {
  if (!$entities) {
    return mswCleanData($data);
  }
  return htmlspecialchars(mswCleanData($data));
}

// Fix Microsoft Word smart quotes..
function mswConvertSmartQuotes($string) {
  return $string;
  //$search   = array(chr(145),chr(146),chr(147),chr(148),chr(151));
  //$replace  = array("'","'",'"','"','-');
  //return str_replace($search,$replace,$string);
}

// Remove double apostrophes via magic quotes setting..
function mswRemoveDoubleApostrophes($data) {
  return str_replace("''","'",$data);
}

// Recursive way of handling multi dimensional arrays..
function mswMultiDimensionalArrayMap($func,$arr) {
  $newArr = array();
  if (!empty($arr)) {
    foreach($arr AS $key => $value) {
      $newArr[$key] = (is_array($value) ? mswMultiDimensionalArrayMap($func,$value) : $func($value));
    }
  }
  return $newArr;
}

// Global filtering on post and get inputs using callback mechanism..
$_GET   = mswMultiDimensionalArrayMap('htmlspecialchars',$_GET);
$_POST  = mswMultiDimensionalArrayMap('trim',$_POST);

// Database connection..
mswDBConnector();

?>