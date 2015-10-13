<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  This File: class.system.php
  Description: Class File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

class msSystem {

public $settings;

public function languages() {
  $lang = array();
  $d    = opendir(PATH.'content/language');
  while (false!==($r=readdir($d))) {
    if (is_dir(PATH.'content/language/'.$r) && !in_array($r,array('.','..'))) {
      $lang[] = $r;
    }
  }
  closedir($d);
  return $lang;
}

public function token() {
  $t = substr(md5(uniqid(rand(),1)),3,30);
  return md5($t.SECRET_KEY);
}

// Assign ticket status based on value..
public function status($tstatus) {
  global $msg_viewticket14,$msg_viewticket15,$msg_viewticket16;
  switch ($tstatus) {
    case 'open':
    return $msg_viewticket14;
    break;
    case 'close':
    return $msg_viewticket15;
    break;
    case 'closed':
    return $msg_viewticket16;
    break;
  }
}

public function department($id,$msg,$object=false) {
  $DEPT = mswGetTableData('departments','id',$id);
  if ($object) {
    return $DEPT;
  }
  return (isset($DEPT->name) ? mswCleanData($DEPT->name) : $msg);
}

public function recaptcha() {
  global $msg_newticket26;
  $api  = RECAPTCHA_API_SERVER;
  // Is this a secure server..
  if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {
    $api = RECAPTCHA_API_SECURE_SERVER;
  }
  return str_replace(
   array('{text}','{public_key}','{api_url}','{theme}','{lang}'),
   array(
	$msg_newticket26,
	$this->settings->recaptchaPublicKey,
    $api,
	($this->settings->recaptchaTheme ? $this->settings->recaptchaTheme : 'white'),
	($this->settings->recaptchaLang ? $this->settings->recaptchaLang : 'en')
   ),
   file_get_contents(PATH.'content/'.MS_TEMPLATE_SET.'/html/recaptcha.htm')
  );
}

public function ticketDepartments($dept='',$arr=false) {
  $html = '';
  $arrD = array();
  $q_dept = mysql_query("SELECT `id`,`name` FROM `".DB_PREFIX."departments`
            WHERE `showDept` = 'yes'
            ORDER BY `orderBy`
            ")
            or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  if (mysql_num_rows($q_dept)>0) {
    while ($DEPT = mysql_fetch_object($q_dept)) {
	  $html .= str_replace(
	   array(
	    '{value}','{selected}','{text}'
	   ),
	   array(
	    $DEPT->id,
		mswSelectedItem($dept,$DEPT->id),
		mswCleanData($DEPT->name)
	   ),
	   file_get_contents(PATH.'content/'.MS_TEMPLATE_SET.'/html/ticket-department.htm')
	  );
	  $arrD[$DEPT->id] = mswCleanData($DEPT->name);
    }
  }
  return ($arr ? $arrD : $html);
}

public function levels($level,$arr=false,$keys=false,$filter=false) {
  $level  = strtolower($level);
  $levels = array();
  $q      = mysql_query("SELECT * FROM `".DB_PREFIX."levels`
            ".($filter ? 'WHERE `display` = \'yes\'' : '')."
            ORDER BY `orderBy`
            ")
            or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  while ($L = mysql_fetch_object($q)) {
    $levels[($L->marker ? $L->marker : $L->id)] = mswCleanData($L->name);
  }
  if ($keys) {
    return array_keys($levels);
  } else {
    if ($arr) {
      return $levels;
    } else {
      return (isset($levels[$level]) ? $levels[$level] : $levels['low']);
    }
  }
}

public function callback($cmd) {
  // FAQ..
  if (isset($_GET['a']) || isset($_GET['c']) || isset($_GET['q']) || isset($_GET['v'])) {
    $cmd        = (isset($_GET['a']) ? 'que' : (isset($_GET['q']) ? 'search' : 'faq'));
    $_GET['p']  = (isset($_GET['a']) ? 'que' : (isset($_GET['q']) ? 'search' : 'faq'));
  }
  // Verification..
  if (isset($_GET['va'])) {
    $cmd = 'create';
  }
  // Ajax..
  if (isset($_GET['ajax'])) {
    $cmd = 'ajax';
  }
  // Logout..
  if (isset($_GET['lo'])) {
    $cmd = 'login';
  }
  // View ticket..
  if (isset($_GET['t']) || isset($_GET['attachment'])) {
    $cmd = 'ticket';
  }
  // View dispute..
  if (isset($_GET['d']) || isset($_GET['qd'])) {
    $cmd = 'dispute';
  }
  // Search..
  if (isset($_GET['qt'])) {
    $cmd = 'history';
  }
  // Search Disputes..
  if (isset($_GET['qd'])) {
    $cmd = 'disputes';
  }
  // FAQ attachment..
  if (isset($_GET['fattachment'])) {
    $cmd = 'faq';
  }
  // Imap..
  if (isset($_GET[$this->settings->imap_param])) {
    $cmd = $this->settings->imap_param;
  }
  // BB code..
  if (isset($_GET['bbcode'])) {
    $cmd = 'home';
  }
  // API..
  if (isset($_GET['api']) || isset($_GET['xml'])) {
    $cmd = 'api';
  }
  return $cmd;
}

public function jsCSSBlockLoader($ms_js_css_loader=array(),$folder) {
  $html = '';
  $base = $folder.'/content/'.MS_TEMPLATE_SET.'/';
  if (array_key_exists('bbcode',$ms_js_css_loader)) {
    $html .= '<link rel="stylesheet" href="'.$base.'css/bbcode.css" type="text/css">'.mswDefineNewline();
  }
  if (array_key_exists('alertify',$ms_js_css_loader)) {
    $html .= '<script src="'.$base.'js/plugins/jquery.alertify.js" type="text/javascript"></script>'.mswDefineNewline();
    $html .= '<link href="'.$base.'css/alertify.core.css" rel="stylesheet" type="text/css">'.mswDefineNewline();
    $html .= '<link href="'.$base.'css/alertify.theme.css" rel="stylesheet" type="text/css">'.mswDefineNewline();
  }
  if (array_key_exists('nyro',$ms_js_css_loader)) {
    $html .= '<script src="'.$base.'js/plugins/jquery.nyroModal.js" type="text/javascript"></script>'.mswDefineNewline();
    $html .= '<link href="'.$base.'css/nyroModal.css" rel="stylesheet" type="text/css">'.mswDefineNewline();
  }
  if (array_key_exists('jquery-ui',$ms_js_css_loader)) {
    $html .= '<script type="text/javascript" src="'.$base.'js/jquery-ui.js"></script>'.mswDefineNewline();
    $html .= '<link href="'.$base.'css/jquery-ui.css" rel="stylesheet" type="text/css">'.mswDefineNewline();
  }
  return trim($html);
}

}

?>