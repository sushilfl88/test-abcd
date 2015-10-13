<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: ic-handler.php
  Description: ionCube Custom Error Handler
  
  This file is the custom error handler for ionCube. It should not be changed. 
  
  Changing this file could hinder your attempts at getting the script up and running successfully.

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

function ioncube_event_handler($err_code,$params) {
  switch($err_code) {
    case '1':
    $e_msg = 'One or more files are corrupt. This happens when files don`t get transferred via FTP correctly or an encoded file is edited. Try re-uploading the software files again, making sure they get transferred in ASCII mode, NOT binary.';
    break;
    case '6':
    $e_msg = 'This software requires a &quot;licence.lic&quot; file. This should be in the root of your software installation and should not be edited or renamed in any way.';
    break;
    case '7':
    $e_msg = 'The &quot;licence.lic&quot; file is corrupt. This happens when the file doesn`t get transferred via FTP correctly or an edit attempt is made. Try re-uploading the &quot;licence.lic&quot; file again, making sure it gets transferred in ASCII mode, NOT binary.<br><br>If the error persists, contact script support or post on <a href="http://www.maianscriptworld.co.uk/forums/" onclick="window.open(this);return false">support forum</a>.';
    break;
    case '9':
    $e_msg = 'A property marked as \'enforced\' in the licence file was NOT matched to a property contained in the software. Try re-uploading the &quot;licence.lic&quot; file again.';
    break;
    case '10':
    $e_msg = 'The header block of the licence file has been altered and loading is terminated.';
    break;
    case '11':
    $e_msg = 'The &quot;licence.lic&quot; file within this installation cannot run on this server.<br><br>Licence file is encoded for a different domain.';
    break;
    case 'PK':
    $e_msg = 'The &quot;licence.lic&quot; file within this installation contains an invalid product key.<br><br>This may be due to entering the key incorrectly on licence creation:<br><br>If you require help, please contact me.<br><span style="font-weight:normal"><a href="mailto:support@maianscriptworld.co.uk">support@maianscriptworld.co.uk</a></span>';
    break;
    case 'NONE':
    $e_msg = 'Ioncube support is not enabled on server. Contact your host for assistance or see the script docs.';
    break;
    case 'EXP':
    $e_msg = 'This beta version has expired. All beta versions are valid for 1 month only.<br><br>If you are an active beta tester, please contact me for a new licence file.<br><span style="font-weight:normal"><a href="mailto:support@maianscriptworld.co.uk?subject=Beta%20Licence">support@maianscriptworld.co.uk</a></span><br><br>Remember that beta versions should NOT be used in a production environment.';
    break;
    default:
    $e_msg = '';
    if (!empty($params) && in_array($err_code,range(1,14))) {
      foreach ($params AS $k => $v) {
        $e_msg .= '['.$k.'] - '.$v.'<br>';
      }
      $e_msg .= '<br>Please contact script support or post on the <a href="http://www.maianscriptworld.co.uk/forums/" onclick="window.open(this);return false">support forum</a>.';
    } else {
      $e_msg = 'ionCube Error Not Recognised.<br><br>Please contact script support or post on <a href="http://www.maianscriptworld.co.uk/forums/" onclick="window.open(this);return false">support forum</a>.';
    }
    break;
  }
  $doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml">';
  $footer  = '<p class="footer">ionCube is a registered trademark of <a href="http://www.ioncube.com" onclick="window.open(this);return false" title="ionCube Ltd">ionCube Ltd</a></p>';
  $help    = 'If the above message wasn`t helpful, you should first see if a solution is in the software documentation (<b>docs</b>). If that doesn`t help, please post on the <a href="http://www.maianscriptworld.co.uk/forums/" onclick="window.open(this);return false">support forums</a> at Maian Script World for FREE support.<br><br>If you have paid for a commercial licence, please send me a message via the <a href="https://www.maiangateway.com" onclick="window.open(this);return false">Licence Centre</a>, thank you.<br><br>For ionCube &amp; ionCube loader help, try the <a href="http://forum.ioncube.com/" onclick="window.open(this);return false">ionCube forums</a>.<br><br>I apologise for any inconvenience.<br><br>David Ian Bennett (Lead Developer - MSWorld)';
  echo $doctype.'<head><title>[Maian Script World] ionCube Error</title><style type="text/css">body{background:url(http://www.maianscriptworld.co.uk/templates/images/ioncube/bg.jpg);font:15px arial;color:#5e3b36}a{color:#34312c}a:hover{text-decoration:none}p{margin:0;padding:0}.footer{font:11px arial;color:#34312c;width:780px;margin:0 auto;text-align:right;padding:10px 0 0 0}.footer a{color:#34312c}#wrapper{width:850px;margin:0 auto;margin-top:50px;background:#fff;border:15px solid #efe3d7;-webkit-border-radius: 5px 5px 5px 5px;-khtml-border-radius: 5px 5px 5px 5px;-moz-border-radius: 5px 5px 5px 5px;border-radius: 5px 5px 5px 5px}#wrapper .head {padding:10px}.head span{float:right;color:#e44d32;font:bold 26px arial;display:block;padding:5px 0 0 0}.msg{padding:15px;border-top:1px solid #efe3d7}.msg .error{display:block;background:#f0e7d6;margin:20px 0 20px 0;line-height:22px;padding:15px;font-weight:bold;border:1px solid #e7d2b3}</style></head><body><div id="wrapper"><p class="head"><span>ERROR ('.($err_code ? $err_code : 'N/A').')</span><img src="http://www.maianscriptworld.co.uk/templates/images/ioncube/ioncube.png" alt="ionCube" title="ionCube"></p><p class="msg">The following encoding error has occured while running this software:<span class="error">'.$e_msg.'</span>'.$help.'</p></div>'.$footer.'</body></html>';
  exit;
}

?>