<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: class.fieldmanager.php
  Description: Class File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

class fieldManager {

// Create select/drop down menu..
public function buildSelect($text,$id,$options,$tabIndex,$value='') {
  $html    = '<option value="nothing-selected">- - - - - - -</option>';
  $select  = explode(mswDefineNewline(),$options);
  foreach ($select AS $o) {
    $html .= '<option value="'.mswSpecialChars($o).'"'.mswSelectedItem($value,$o).'>'.mswCleanData($o).'</option>'.mswDefineNewline();
  }
  return mswDefineNewline().'<label class="textHead">'.$text.'</label>'.mswDefineNewline().'<div class="dataArea"><select name="customField['.$id.']" tabindex="'.$tabIndex.'" class="span2">'.$html.'</select></div>'.mswDefineNewline();
}

// Create checkbox..
public function buildCheckBox($text,$id,$options,$values='') {
  $html   = '';
  $v      = array();
  $boxes  = explode(mswDefineNewline(),$options);
  if ($values) {
    $v = explode('#####',$values);
  }
  foreach ($boxes AS $cb) {
    $html .= '<label class="checkbox"><input type="checkbox" name="customField['.$id.'][]" value="'.mswSpecialChars($cb).'"'.(in_array($cb,$v) ? ' checked="checked"' : '').'> '.$cb.'</label>'.mswDefineNewline();
  }
  return ($html ? mswDefineNewline().'<input type="hidden" name="hiddenBoxes[]" value="'.$id.'"><label class="textHead">'.$text.'</label><div class="dataArea">'.$html.'</div>' : '');
}

// Create input box..
public function buildInputBox($text,$id,$tabIndex,$value='') {
  return mswDefineNewline().'<label class="textHead">'.$text.'</label>'.mswDefineNewline().'<div class="dataArea"><input tabindex="'.$tabIndex.'" class="input-xlarge" type="text" name="customField['.$id.']" value="'.mswSpecialChars($value).'"></div>'.mswDefineNewline();
}

// Create textarea..
public function buildTextArea($text,$id,$tabIndex,$value='') {
  return mswDefineNewline().'<label class="textHead">'.$text.'</label>'.mswDefineNewline().'<div class="dataArea"><textarea tabindex="'.$tabIndex.'" rows="5" cols="40" name="customField['.$id.']">'.mswSpecialChars($value).'</textarea></div>'.mswDefineNewline();
}

}

?>
