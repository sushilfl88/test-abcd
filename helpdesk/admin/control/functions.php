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

function helpPageLoader($page) {
  switch ($page) {
    case 'view-dispute':
	if (isset($_GET['disputeUsers'])) {
	  return 'view-dispute-users';
	}
	break;
  }
  return $page;
}

function mswUserPageAccess($t) {
  $a = explode('|',$t->pageAccess);
  if ($t->addpages) {
    $b = explode(',',$t->addpages);
	return array_merge($a,$b);
  }
  return $a;
}

function mswDeptPerms($user,$dept,$arr) {
  return ($user=='1' || in_array($dept,$arr) ? 'ok' : 'fail');
}
  
function mswSQLDepartmentFilter($code,$query='AND') {
  return ($code ? $query.' '.$code : '');
}

function userAccessPages($id) {
  $p = array();
  $q = mysql_query("SELECT `page` FROM `".DB_PREFIX."usersaccess`
       WHERE `userID`  = '{$id}' 
       AND `type`      = 'pages'
       ORDER BY `page`
       ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  while ($AP = mysql_fetch_object($q)) {
    $p[] = $AP->page;
  }
  if (!empty($p)) {
    mysql_query("UPDATE `".DB_PREFIX."users` SET
    `pageAccess`  = '".implode('|',$p)."'
    WHERE `id`    = '{$id}'
	") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	return implode('|',$p);
  }
  return '';
}

function mswDeptFilterAccess($MSTEAM,$userDeptAccess,$table) {
  $f = '';
  if ($MSTEAM->id!='1') {
    switch ($MSTEAM->assigned) {
      // Can view assigned tickets ONLY..
      case 'yes':
      switch ($table) {
        case 'department':
        $f  = '`id` > 0 AND `manual_assign` = \'yes\'';
        break;
        case 'tickets':
        $f  = 'FIND_IN_SET(\''.$MSTEAM->id.'\',`assignedto`) > 0';
        break;
      }
      break;
      // Can view tickets by department..
      case 'no':
      switch ($table) {
        case 'department':
        if (!empty($userDeptAccess)) {
          $f  = '`id` IN('.implode(',',$userDeptAccess).')';
        } else {
          $f  = '`id` = \'0\'';
        }
        break;
        case 'tickets':
        if (!empty($userDeptAccess)) {
          $f  = '(`department` IN('.implode(',',$userDeptAccess).') OR FIND_IN_SET(\''.$MSTEAM->id.'\',`assignedto`) > 0)';
        } else {
          $f  = '`department` = \'0\'';
        }
        break;
      }
      break;
    }  
  }
  return $f;    
}

function mswCallBackUrls($cmd) {
  if (isset($_GET['attachment'])) {
    $cmd = 'view-ticket';
  }
  if (isset($_GET['response'])) {
    $cmd = 'view-ticket';
  }
  if (isset($_GET['fattachment'])) {
    $cmd = 'attachman';
  }
  if (isset($_GET['p']) && $_GET['p']=='cp') {
    $cmd = 'team-profile';
  }
  if (isset($_GET['ajax'])) {
    $cmd = 'ajax-handler';
  }
  return $cmd;
}

// Field display information..
function mswFieldDisplayInformation($loc) {
  global $msg_customfields40,$msg_customfields41,$msg_customfields42;
  $chop = explode(',',$loc);
  $dis  = array();
  if (in_array('ticket',$chop)) {
    $dis[] = $msg_customfields40;
  }
  if (in_array('reply',$chop)) {
    $dis[] = $msg_customfields41;
  }
  if (in_array('admin',$chop)) {
    $dis[] = $msg_customfields42;
  }
  return implode(', ', $dis);
}

// Clear settings footers..
function mswClearSettingsFooters() {
  mysql_query("UPDATE `".DB_PREFIX."settings` SET
  `adminFooter`   = '',
  `publicFooter`  = ''
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
}

// Log in checker..
function mswIsLoggedIn($t) {
  if ((isset($_SESSION[md5(SECRET_KEY).'_ms_mail']) && 
     isset($_SESSION[md5(SECRET_KEY).'_ms_key']) && 
     mswIsValidEmail($_SESSION[md5(SECRET_KEY).'_ms_mail'])
    ) || (
     isset($_COOKIE[md5(SECRET_KEY).'_msc_mail']) && 
     isset($_COOKIE[md5(SECRET_KEY).'_msc_key']) && 
     mswIsValidEmail($_COOKIE[md5(SECRET_KEY).'_msc_mail'])
    )
   ) {
    if (!isset($t->name)) {
      header("Location: index.php?p=login");
      exit;
    }
  } else {
    header("Location: index.php?p=login");
    exit;
  }
}

// Cleans CSV..adds quotes if data contains delimiter..
function mswCleanCSV($data,$del) {
  if (strpos($data,$del)!==FALSE) {
    return '"'.mswCleanData($data).'"';
  } else {
    return mswCleanData($data);
  }
}

// Get page access for user..
function mswGetUserPageAccess($id) {
  $q = mysql_query("SELECT `pageAccess`,`addpages` FROM `".DB_PREFIX."users` WHERE `id` = '{$id}'") 
       or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  $U = mysql_fetch_object($q);
  $pages = explode('|',$U->pageAccess);
  // Additional page rules..
  if ($U->addpages) {
    $add = array_map('trim',explode(',',$U->addpages));
	return array_merge($add,$pages);
  }
  return $pages;
}

// Get department access for user..
function mswGetDepartmentAccess($id) {
  $dept  = array();
  $q     = mysql_query("SELECT `deptID` FROM `".DB_PREFIX."userdepts` WHERE `userID` = '{$id}'") 
           or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  while ($row = mysql_fetch_object($q)) {
    $dept[] = $row->deptID;
  }
  // Are there any tickets assigned to this user NOT in the department array..?
  // If there are, add department to allowed array..
  $q2 = mysql_query("SELECT `department` FROM `".DB_PREFIX."tickets` 
        WHERE `department` NOT IN(".implode(',',(!empty($dept) ? $dept : array('0'))).") 
        AND FIND_IN_SET('{$id}',`assignedto`) > 0 
        GROUP BY `department`
        ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  while ($DP = mysql_fetch_object($q2)) {
    $dept[] = $DP->department;
  }
  if (!empty($dept)) {
    sort($dept);
  }
  return $dept;
}

// Standard response department..
function mswSrCat($depts) {
  $dep = array();
  $q   = mysql_query("SELECT `name` FROM `".DB_PREFIX."departments` 
         WHERE `id` IN({$depts}) 
         ORDER BY `name`
	     ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  while ($DP = mysql_fetch_object($q)) {
    $dept[] = mswCleanData($DP->name);
  }
  return (!empty($dept) ? implode(', ', $dept) : '');
}

// FAQ Cat..
function mswFaqCategories($id,$action='show') {
  $cat   = array();
  $catID = array();
  $q     = mysql_query("SELECT `".DB_PREFIX."categories`.`name`,`".DB_PREFIX."categories`.`id` AS `catID` FROM `".DB_PREFIX."categories`
           LEFT JOIN `".DB_PREFIX."faqassign`
	       ON `".DB_PREFIX."faqassign`.`itemID`    = `".DB_PREFIX."categories`.`id`
           WHERE `".DB_PREFIX."faqassign`.`desc`   = 'category'
	       AND `".DB_PREFIX."faqassign`.`question` = '{$id}'
           ORDER BY `".DB_PREFIX."categories`.`name`
	       ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  while ($CT = mysql_fetch_object($q)) {
    $cat[]   = mswCleanData($CT->name);
	$catID[] = $CT->catID;
  }
  // We just want IDs if action is get..
  if ($action=='get') {
    return $catID;
  }
  return (!empty($cat) ? implode(', ', $cat) : '');
}

// Display box if action is done..
function mswActionCompleted($text) {
  return '
  <div class="container-fluid" onclick="jQuery(this).remove()">
    <div class="row-fluid" style="margin:0;padding:0">
     <div class="alert alert-success" style="margin-bottom:10px">
      <button type="button" class="close" data-dismiss="alert" style="padding-top:3px">&times;</button>
      <i class="icon-ok"></i> '.$text.'
     </div>
	</div>
  </div>	
  ';
}

// Display box if action is failed..
function mswActionCompletedFail($text) {
  return '
  <div class="container-fluid" onclick="jQuery(this).remove()">
    <div class="row-fluid" style="margin:0;padding:0">
     <div class="alert alert-error" style="margin-bottom:10px">
      <button type="button" class="close" data-dismiss="alert" style="padding-top:3px">&times;</button>
      <i class="icon-warning-sign"></i> '.$text.'
     </div>
	</div>
  </div>	
  ';
}

?>