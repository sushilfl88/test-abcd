<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: class.fields.php
  Description: Class File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

class customFieldManager {

public $parser;

// Mysql..
public function insert($ticketID,$fieldID,$replyID,$data) {
  mysql_query("INSERT INTO `".DB_PREFIX."ticketfields` (
  `ticketID`,`fieldID`,`replyID`,`fieldData`
  ) VALUES (
  '{$ticketID}','{$fieldID}','{$replyID}','".mswSafeImportString($data)."'
  )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
}

// Display..
public function display($ticketID,$replyID=0) {
  $html     = '';
  $qT       = mysql_query("SELECT * FROM `".DB_PREFIX."ticketfields`
              LEFT JOIN `".DB_PREFIX."cusfields`
              ON `".DB_PREFIX."cusfields`.`id` = `".DB_PREFIX."ticketfields`.`fieldID`
              WHERE `ticketID`  = '{$ticketID}'
              AND `replyID`     = '{$replyID}'
			  AND `enField`     = 'yes'
              ORDER BY `".DB_PREFIX."cusfields`.`id`
              ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  while ($TS = mysql_fetch_object($qT)) {
    if ($TS->fieldData!='nothing-selected' && $TS->fieldData!='') {
      if ($TS->repeatPref=='no' && strpos($TS->fieldLoc,'admin')!==false) {
      } else {
        switch ($TS->fieldType) {
          case 'textarea':
          case 'input':
          case 'select':
          $html .= str_replace(
		   array('{head}','{data}'),
           array(
		    mswCleanData($TS->fieldInstructions),
            $this->parser->mswTxtParsingEngine($TS->fieldData)
           ),
           file_get_contents(PATH.'content/'.MS_TEMPLATE_SET.'/html/ticket-custom-fields.htm')
          );
          break;
          case 'checkbox':
          $html .= str_replace(
		   array('{head}','{data}'),
           array(
		    mswCleanData($TS->fieldInstructions),
            str_replace('#####','<br>',mswCleanData($TS->fieldData))
           ),
           file_get_contents(PATH.'content/'.MS_TEMPLATE_SET.'/html/ticket-custom-fields.htm')
          );
          break;
        }
      }
    }
  }
  return ($html ? trim($html) : '');
}

// Return data for emails..
public function email($ticketID,$replyID=0) {
  $text  = '';
  $qF    = mysql_query("SELECT * FROM `".DB_PREFIX."cusfields`
           LEFT JOIN `".DB_PREFIX."ticketfields`
           ON `".DB_PREFIX."cusfields`.`id` = `".DB_PREFIX."ticketfields`.`fieldID`
           WHERE `ticketID`  = '{$ticketID}'
           AND `replyID`     = '{$replyID}'
           AND `enField`     = 'yes'
           ORDER BY `".DB_PREFIX."cusfields`.`orderBy`
           ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  if (mysql_num_rows($qF)>0) {
  while ($FIELDS = mysql_fetch_object($qF)) {
    switch ($FIELDS->fieldType) {
      case 'checkbox':
      $text .= mswCleanData($FIELDS->fieldInstructions).mswDefineNewline();
      $text .= str_replace('#####',mswDefineNewline(),mswCleanData($FIELDS->fieldData)).mswDefineNewline().mswDefineNewline();
      break;
      default:
      $text .= mswCleanData($FIELDS->fieldInstructions).mswDefineNewline();
      $text .= mswCleanData($FIELDS->fieldData).mswDefineNewline().mswDefineNewline();
      break;
    }
  }
  }
  return ($text ? trim($text) : 'N/A');
}

// Insert and return data..
public function data($area,$ticketID,$replyID=0,$dept) {
  $text  = '';
  $qF    = mysql_query("SELECT * FROM `".DB_PREFIX."cusfields`
           WHERE FIND_IN_SET('{$area}',`fieldLoc`) > 0
           AND `enField`  = 'yes'
           AND FIND_IN_SET('{$dept}',`departments`) > 0
           ORDER BY `orderBy`
           ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  if (mysql_num_rows($qF)>0) {
  while ($FIELDS = mysql_fetch_object($qF)) {
    switch ($FIELDS->fieldType) {
      case 'textarea':
      case 'input':
      if ($_POST['customField'][$FIELDS->id]!='') {
        $text .= mswCleanData($FIELDS->fieldInstructions).mswDefineNewline();
        $text .= $_POST['customField'][$FIELDS->id].mswDefineNewline().mswDefineNewline();
        //customFieldManager::insert($ticketID,$FIELDS->id,$replyID,mswCleanData($_POST['customField'][$FIELDS->id]));
      }
      break;
      case 'select':
      if ($_POST['customField'][$FIELDS->id]!='nothing-selected') {
        $text .= mswCleanData($FIELDS->fieldInstructions).mswDefineNewline();
        $text .= $_POST['customField'][$FIELDS->id].mswDefineNewline().mswDefineNewline();
        //customFieldManager::insert($ticketID,$FIELDS->id,$replyID,mswCleanData($_POST['customField'][$FIELDS->id]));
      }
      break;
      case 'checkbox':
      if (!empty($_POST['customField'][$FIELDS->id])) {
        $text .= mswCleanData($FIELDS->fieldInstructions).mswDefineNewline();
        foreach ($_POST['customField'][$FIELDS->id] AS $k => $v) {
          $text .= $v.mswDefineNewline();
        }
        $text .= mswDefineNewline();
        //customFieldManager::insert($ticketID,$FIELDS->id,$replyID,mswCleanData(implode('#####',$_POST['customField'][$FIELDS->id])));
      }
      break;
    }
  }
  }
  return ($text ? trim($text) : 'N/A');
}

// Check required fields..
public function check($area,$dept) {
  $e   = array();
  $qF  = mysql_query("SELECT * FROM `".DB_PREFIX."cusfields`
         WHERE FIND_IN_SET('{$area}',`fieldLoc`) > 0
         AND `fieldReq`  = 'yes'
         AND `enField`   = 'yes'
         AND FIND_IN_SET('{$dept}',`departments`) > 0
         ORDER BY `orderBy`
         ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  if (mysql_num_rows($qF)>0) {
  while ($FIELDS = mysql_fetch_object($qF)) {
    switch ($FIELDS->fieldType) {
      case 'textarea':
      case 'input':
      if (isset($_POST['customField'][$FIELDS->id]) && $_POST['customField'][$FIELDS->id]=='') {
        $e[] = 'cus|'.$FIELDS->id.'|err1';
      }
      break;
      case 'select':
      if (isset($_POST['customField'][$FIELDS->id]) && $_POST['customField'][$FIELDS->id]=='nothing-selected') {
        $e[] = 'cus|'.$FIELDS->id.'|err1';
      }
      break;
      case 'checkbox':
      if (empty($_POST['customField'][$FIELDS->id])) {
        $e[] = 'cus|'.$FIELDS->id.'|err1';
      }
      break;
    }
  }
  }
  return $e;
}

// Render new fields..
public function build($area,$dept) {
  $html = '';
  $tab  = 6;
  $qF   = mysql_query("SELECT * FROM `".DB_PREFIX."cusfields`
          WHERE FIND_IN_SET('{$area}',`fieldLoc`) > 0
          AND `enField`  = 'yes'
          AND FIND_IN_SET('{$dept}',`departments`) > 0
          ORDER BY `orderBy`
          ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  if (mysql_num_rows($qF)>0) {
  while ($F = mysql_fetch_object($qF)) {
    switch ($F->fieldType) {
      case 'textarea':
      $html .= customFieldManager::textarea(mswCleanData($F->fieldInstructions),$F->id,++$tab,$F->fieldReq);
      break;
      case 'input':
      $html .= customFieldManager::box(mswCleanData($F->fieldInstructions),$F->id,++$tab,$F->fieldReq);
      break;
      case 'select':
      $html .= customFieldManager::select(mswCleanData($F->fieldInstructions),$F->id,$F->fieldOptions,++$tab,$F->fieldReq);
      break;
      case 'checkbox':
      $html .= customFieldManager::checkbox(mswCleanData($F->fieldInstructions),$F->id,$F->fieldOptions,$F->fieldReq);
      break;
    }
  }
  }
  return ($html ? trim($html) : '');
}

// Create select/drop down menu..
public function select($text,$id,$options,$tab,$req) {
  $html     = '';
  $wrapper  = file_get_contents(PATH.'content/'.MS_TEMPLATE_SET.'/html/custom-fields/select.htm');
  $select   = explode(mswDefineNewline(),$options);
  foreach ($select AS $o) {
    $html .= str_replace(
	 array('{value}','{selected}','{text}'),
     array(
	  mswCleanData($o),
	  (isset($_POST['customField'][$id]) ? mswSelectedItem($_POST['customField'][$id],$o) : ''),
      mswCleanData($o)
	 ),
	 file_get_contents(PATH.'content/'.MS_TEMPLATE_SET.'/html/custom-fields/select-option.htm')
    );
  }
  return str_replace(
   array('{id}','{options}','{label}','{tab}'),
   array(
    $id,
    trim($html),
    mswCleanData($text),
    $tab
   ),
   $wrapper
  );
}

// Create checkbox..
public function checkbox($text,$id,$options,$req) {
  global $msg_viewticket71;
  $wrapper  = file_get_contents(PATH.'content/'.MS_TEMPLATE_SET.'/html/custom-fields/checkbox-wrapper.htm');
  $html     = '';
  $v        = array();
  $boxes    = explode(mswDefineNewline(),$options);
  if (isset($_POST['customField'][$id]) && !empty($_POST['customField'][$id])) {
    $v = $_POST['customField'][$id];
  }
  foreach ($boxes AS $cb) {
    $html .= str_replace(
	 array('{value}','{checked}','{id}'),
     array(
	  mswCleanData($cb),
	  (in_array($cb,$v) ? ' checked="checked"' : ''),
      $id
	 ),
	 file_get_contents(PATH.'content/'.MS_TEMPLATE_SET.'/html/custom-fields/checkbox.htm')
    );
  }
  return str_replace(
   array('{label}','{text}','{checkboxes}','{id}'),
   array(
    mswCleanData($text),
	$msg_viewticket71,
	trim($html),
	$id
   ),
   $wrapper
  );
}

// Create input box..
public function box($text,$id,$tab,$req) {
  return str_replace(
   array('{label}','{value}','{id}','{tab}'),
   array(
    mswCleanData($text),
	(isset($_POST['customField'][$id]) ? mswSpecialChars($_POST['customField'][$id]) : ''),
    $id,
	$tab
   ),
   file_get_contents(PATH.'content/'.MS_TEMPLATE_SET.'/html/custom-fields/input-box.htm')
  );
}

// Create textarea..
public function textarea($text,$id,$tab,$req) {
  return str_replace(
   array('{label}','{value}','{id}','{tab}'),
   array(
    mswCleanData($text),
	(isset($_POST['customField'][$id]) ? mswSpecialChars($_POST['customField'][$id]) : ''),
    $id,
	$tab
   ),
   file_get_contents(PATH.'content/'.MS_TEMPLATE_SET.'/html/custom-fields/textarea.htm')
  );
}

}

?>