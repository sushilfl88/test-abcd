<?php if (!defined('PARENT')) { exit; } 
$tempSets = ($SETTINGS->langSets ? unserialize($SETTINGS->langSets) : array());
$defLogs  = ($SETTINGS->defKeepLogs ? unserialize($SETTINGS->defKeepLogs) : array());
$apiHndls = ($SETTINGS->apiHandlers ? explode(',',$SETTINGS->apiHandlers) : array());
include_once(PATH.'control/recaptcha.php');
?>
<div class="content">
  <script type="text/javascript">
  //<![CDATA[
  <?php
  include(PATH.'templates/date-pickers.php');
  ?>
  jQuery(document).ready(function() {
    jQuery('.nyroModal').nyroModal();
  });
  function autoPath(type,box) {
    jQuery(document).ready(function() {
     jQuery('input[name="'+box+'"]').css('background','url(templates/images/indicator.gif) no-repeat 99% 50%');
	 jQuery.ajax({
      url: 'index.php',
      data: 'ajax=autopath&type='+type,
      dataType: 'json',
      success: function (data) {
	    jQuery('input[name="'+box+'"]').css('background-image','none');
		jQuery('input[name="'+box+'"]').val(data['path']);
      }
     });
    });
    return false;
  }
  //]]>
  </script>
  <div class="header">
    
	<h1 class="page-title"><?php echo $msg_adheader2; ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader37; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_adheader2; ?></li>
  </ul>

  <?php
  // Updated..
  if (isset($OK)) {
    echo mswActionCompleted($msg_settings8);
  }
  ?>
  
  <form method="post" action="index.php?p=<?php echo $_GET['p']; ?>" onsubmit="return ms_fieldCheck('none','none')">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  <ul class="nav nav-tabs">
	   <li class="dropdown active">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-cogs"></i> <?php echo $msg_settings22; ?><b class="caret"></b></a>
        <ul class="dropdown-menu">
		 <li><a href="#one_a" data-toggle="tab"><i class="icon-cog"></i> <?php echo $msg_settings86; ?></a></li>
		 <li><a href="#one_g" data-toggle="tab"><i class="icon-text-width"></i> <?php echo $msg_settings105; ?></a></li>
		 <li><a href="#one_f" data-toggle="tab"><i class="icon-user"></i> <?php echo $msg_settings92; ?></a></li>
		 <li><a href="#one_b" data-toggle="tab"><i class="icon-calendar"></i> <?php echo $msg_settings87; ?></a></li>
		 <li><a href="#one_c" data-toggle="tab"><i class="icon-edit"></i> <?php echo $msg_settings91; ?></a></li>
		 <li><a href="#one_d" data-toggle="tab"><i class="icon-bullhorn"></i> <?php echo $msg_settings88; ?></a></li>
		 <li><a href="#one_h" data-toggle="tab"><i class="icon-envelope"></i> <?php echo $msg_settings119; ?></a></li>
		 <li><a href="#one_e" data-toggle="tab"><i class="icon-puzzle-piece"></i> <?php echo $msg_settings89; ?></a></li>
		</ul>
	   </li>	
       <li><a href="#two" data-toggle="tab"><i class="icon-time"></i> <?php echo $msg_settings10; ?></a></li>
       <li><a href="#five" data-toggle="tab"><i class="icon-paper-clip"></i> <?php echo $msg_settings23; ?></a></li>
	   <li><a href="#six" data-toggle="tab"><i class="icon-info-sign"></i> <?php echo $msg_settings29; ?></a></li>
	   <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-wrench"></i> <?php echo $msg_settings85; ?><b class="caret"></b></a>
        <ul class="dropdown-menu">
         <li><a href="#seven" data-toggle="tab"><i class="icon-envelope-alt"></i> <?php echo $msg_settings24; ?></a></li>
	     <li><a href="#three" data-toggle="tab"><i class="icon-off"></i> <?php echo $msg_settings83; ?></a></li>
		 <li><a href="#four" data-toggle="tab"><i class="icon-barcode"></i> <?php echo $msg_settings62; ?></a></li>
		 <li><a href="#nine" data-toggle="tab"><i class="icon-signin"></i> <?php echo $msg_settings111; ?></a></li>
	     <?php
         if (LICENCE_VER=='unlocked') {
         ?>
	     <li><a href="#eight" data-toggle="tab"><i class="icon-file-text-alt"></i> <?php echo $msg_settings56; ?></a></li>
	     <?php
	     }
	     ?>
        </ul>
       </li>
      </ul>
      <div id="myTabContent" class="tab-content" style="margin-bottom:10px">
       <div class="tab-pane active in" id="one_a">
	    <div class="well" style="margin-bottom:0">
	     
		 <label><?php echo $msg_settings9; ?></label>
         <input type="text" class="input-xxlarge" maxlength="150" name="website" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo mswSpecialChars($SETTINGS->website); ?>">
         
		 <label><?php echo $msg_settings20; ?></label>
         <input type="text" class="input-xxlarge" maxlength="250" name="scriptpath" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo mswCleanData($SETTINGS->scriptpath); ?>">
          
		 <label><?php echo $msg_settings84; ?></label>
		 <select name="language" tabindex="<?php echo (++$tabIndex); ?>">
         <?php
         $showlang = opendir(REL_PATH.'content/language');
         while (false!==($read=readdir($showlang))) {
          if (is_dir(REL_PATH.'content/language/'.$read) && !in_array($read,array('.','..'))) {
          ?>
          <option<?php echo mswSelectedItem($read,$SETTINGS->language); ?>><?php echo $read; ?></option>
          <?php
          }
         }
         closedir($showlang);
         ?>
         </select>
		 
		 <label><?php echo $msg_settings30; ?></label>
         <input type="text" class="input-xlarge" maxlength="50" tabindex="<?php echo (++$tabIndex); ?>" name="afolder" value="<?php echo $SETTINGS->afolder; ?>">
		 
		</div>
       </div>
	   <div class="tab-pane fade" id="one_g">
	    <div class="well" style="margin-bottom:0">
	     
		 <?php
         $showlang = opendir(REL_PATH.'content/language');
         while (false!==($read=readdir($showlang))) {
          if (is_dir(REL_PATH.'content/language/'.$read) && !in_array($read,array('.','..'))) {
          ?>
		  <label>
          <?php 
		  echo ucfirst(strtolower($read)); 
		  ?></label>
		  <select name="templateSet[<?php echo $read; ?>]" tabindex="<?php echo (++$tabIndex); ?>">
          <?php
          $showsets = opendir(REL_PATH.'content');
          while (false!==($rd=readdir($showsets))) {
           if (is_dir(REL_PATH.'content/'.$rd) && !in_array($rd,array('.','..')) && substr($rd,0,1)=='_') {
           ?>
           <option<?php echo (isset($tempSets[$read]) ? mswSelectedItem($tempSets[$read],$rd) : ''); ?> value="<?php echo $rd; ?>"><?php echo $rd; ?></option>
           <?php
           }
          }
          closedir($showsets);
          ?>
          </select><br>
		  <?php
          }
         }
         closedir($showlang);
         ?>
         
		</div>
       </div>
	   <div class="tab-pane fade" id="one_b">
	    <div class="well" style="margin-bottom:0">
	     
		 <label class="checkbox">
		  <input type="checkbox" name="autoCloseMail" value="yes"<?php echo ($SETTINGS->autoCloseMail=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings75; ?>
         </label>
		 
		 <label><br><?php echo $msg_settings13; ?></label>
         <input type="text" class="input-small" name="autoClose" maxlength="5" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo $SETTINGS->autoClose; ?>"> <?php echo $msg_settings14; ?>
		 
		 <p style="text-align:right">
		   <a href="../close-tickets.php" class="nyroModal"><i class="icon-cog"></i> <?php echo $msg_user105; ?></a>
		 </p>
		 
		</div>
       </div>
	   <div class="tab-pane fade" id="one_c">
        <div class="well" style="margin-bottom:0">
		
		 <label class="checkbox">
		  <input type="checkbox" name="createPref" value="yes"<?php echo ($SETTINGS->createPref=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings90; ?>
		 </label>
		 
		 <label class="checkbox">
		  <input type="checkbox" name="ticketHistory" value="yes"<?php echo (isset($SETTINGS->ticketHistory) && $SETTINGS->ticketHistory=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings101; ?>
		 </label>
		 
		 <label class="checkbox">
		  <input type="checkbox" name="closenotify" value="yes"<?php echo (isset($SETTINGS->closenotify) && $SETTINGS->closenotify=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings103; ?>
		 </label>
		 
		 <label><br><?php echo $msg_settings21; ?></label>
         <input type="text" class="input-xxlarge" maxlength="250" name="email" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo mswSpecialChars($SETTINGS->email); ?>">
		 
		 <label><?php echo $msg_settings104; ?></label>
         <input type="text" class="input-xxlarge" maxlength="250" name="replyto" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo mswSpecialChars($SETTINGS->replyto); ?>">
		 
		 <label><?php echo $msg_settings114; ?></label>
         <input type="text" class="input-small" maxlength="2" name="minTickDigits" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo mswSpecialChars($SETTINGS->minTickDigits); ?>">

		</div>
       </div>
	   <div class="tab-pane fade" id="one_d">
	    <div class="well" style="margin-bottom:0">
	     
		 <label class="checkbox">
		  <input type="checkbox" name="disputes" value="yes"<?php echo ($SETTINGS->disputes=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings81; ?>
		 </label>
		 
		 <label class="checkbox">
		  <input type="checkbox" name="disputeAdminStop" value="yes"<?php echo ($SETTINGS->disputeAdminStop=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings129; ?>
		 </label>
		 
		</div>
       </div>
	   <div class="tab-pane fade" id="one_e">
	    <div class="well" style="margin-bottom:0">
	     
		 <label class="checkbox">
		  <input type="checkbox" name="apiLog" value="yes"<?php echo ($SETTINGS->apiLog=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings124; ?>
		 </label>
		 
		 <label class="checkbox">
		  <input type="checkbox" name="apiHandlers[]" value="json"<?php echo (in_array('json',$apiHndls) ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings125; ?>
		 </label>
		 
		 <label class="checkbox">
		  <input type="checkbox" name="apiHandlers[]" value="xml"<?php echo (in_array('xml',$apiHndls) ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings126; ?>
		 </label>
		 
		 <label><br><?php echo $msg_settings59; ?></label>
		 <div class="input-append">
         <input type="text" class="input-xlarge" maxlength="100" id="apiKey" name="apiKey" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo $SETTINGS->apiKey; ?>">
		 <span class="add-on"><a href="#" onclick="ms_generateAPIKey();return false"><i class="icon-key"></i></a></span>
         </div>
		
		</div>
       </div>
	   <div class="tab-pane fade" id="one_f">
        <div class="well" style="margin-bottom:0">
		
		 <label class="checkbox">
		  <input type="checkbox" name="createAcc" value="yes"<?php echo ($SETTINGS->createAcc=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings93; ?>
		 </label>
		 
		 <label class="checkbox">
		  <input type="checkbox" name="enableBBCode" value="yes"<?php echo ($SETTINGS->enableBBCode=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings58; ?>
		 </label>
		 
		 <label class="checkbox">
		  <input type="checkbox" name="accProfNotify" value="yes"<?php echo ($SETTINGS->accProfNotify=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings106; ?>
		 </label>
		 
		 <label class="checkbox">
		  <input type="checkbox" name="newAccNotify" value="yes"<?php echo ($SETTINGS->newAccNotify=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings108; ?>
		 </label>
		 
		 <label class="checkbox">
		  <input type="checkbox" name="enableLog" value="yes"<?php echo ($SETTINGS->enableLog=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings110; ?>
		 </label>
		 
		 <label><br><?php echo $msg_settings99; ?> / <?php echo $msg_settings100; ?></label>
         <input type="text" class="input-small" name="loginLimit" maxlength="5" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo $SETTINGS->loginLimit; ?>"> / 
		 <input type="text" class="input-small" name="banTime" maxlength="5" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo $SETTINGS->banTime; ?>">
		 
		 <label><?php echo $msg_settings107; ?></label>
         <input type="text" class="input-small" name="minPassValue" maxlength="3" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo $SETTINGS->minPassValue; ?>">
		 
		</div>
       </div>
       <div class="tab-pane fade" id="two">
	    <div class="well" style="margin-bottom:0">
		 
		 <label><?php echo $msg_settings2; ?></label>
         <input type="text" class="input-small" name="dateformat" maxlength="20" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo $SETTINGS->dateformat; ?>"> /
         <input type="text" class="input-small" name="timeformat" maxlength="15" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo $SETTINGS->timeformat; ?>">
         
		 <label><?php echo $msg_settings12; ?></label>
         <select name="timezone" tabindex="<?php echo (++$tabIndex); ?>">
         <?php
         // TIMEZONES..
         foreach ($timezones AS $k => $v) {
         ?>
         <option value="<?php echo $k; ?>"<?php echo mswSelectedItem($SETTINGS->timezone,$k); ?>><?php echo $v; ?></option>
         <?php
         }
         ?>
         </select>
		 
		 <label><?php echo $msg_settings64; ?></label>
		 <select name="weekStart" tabindex="<?php echo ++$tabIndex; ?>">
		 <option value="sun"<?php echo mswSelectedItem($SETTINGS->weekStart,'sun'); ?>><?php echo $msg_settings65; ?></option>
		 <option value="mon"<?php echo mswSelectedItem($SETTINGS->weekStart,'mon'); ?>><?php echo $msg_settings66; ?></option>
		 </select>
         
		 <label><?php echo $msg_settings69; ?></label>
         <select name="jsDateFormat" tabindex="<?php echo ++$tabIndex; ?>">
         <?php
         foreach (array('DD-MM-YYYY','DD/MM/YYYY','YYYY-MM-DD','YYYY/MM/DD','MM-DD-YYYY','MM/DD/YYYY') AS $jsf) {
         ?>
         <option value="<?php echo $jsf; ?>"<?php echo mswSelectedItem($SETTINGS->jsDateFormat,$jsf); ?>><?php echo $jsf; ?></option>
         <?php
         }
         ?>
         </select>
		
		</div>
       </div>
       <div class="tab-pane fade" id="three">
	    <div class="well" style="margin-bottom:0">
		  
		 <label class="checkbox">
          <input type="checkbox" name="sysstatus" value="yes"<?php echo ($SETTINGS->sysstatus=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings70; ?>
         </label>
		 
		 <label><br><?php echo $msg_settings82; ?></label>
         <textarea name="offlineReason" rows="5" cols="20" tabindex="<?php echo (++$tabIndex); ?>"><?php echo mswSpecialChars($SETTINGS->offlineReason); ?></textarea>
		 
		 <label><?php echo $msg_settings73; ?></label>
         <input type="text" class="input-small" maxlength="5" tabindex="<?php echo (++$tabIndex); ?>" id="from" name="autoenable" value="<?php echo ($SETTINGS->autoenable!='0000-00-00' ? $MSDT->mswConvertMySQLDate($SETTINGS->autoenable) : ''); ?>">
        
		</div>
       </div>
	   <div class="tab-pane fade" id="four">
	    <div class="well" style="margin-bottom:0">
		 
		 <label class="checkbox">
		  <input type="checkbox" name="enCapLogin" value="yes"<?php echo ($SETTINGS->enCapLogin=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings67; ?>
         </label>
		
		 <label><br><?php echo $msg_settings60; ?></label>
         <input type="text" class="input-xlarge" maxlength="250" name="recaptchaPublicKey" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo mswSpecialChars($SETTINGS->recaptchaPublicKey); ?>">
         
		 <label><?php echo $msg_settings61; ?></label>
		 <input type="text" class="input-xlarge" maxlength="250" name="recaptchaPrivateKey" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo mswSpecialChars($SETTINGS->recaptchaPrivateKey); ?>">
         
		 <label><?php echo $msg_settings109; ?></label>
		 <select name="recaptchaLang" class="span2">
		 <?php
		 foreach ($gRC_Lang AS $rclk => $rclv) {
		 ?>
         <option value="<?php echo $rclk; ?>"<?php echo mswSelectedItem($SETTINGS->recaptchaLang,$rclk); ?>><?php echo $rclv; ?></option>
         <?php
		 }
		 ?>
		 </select> /
		 <select name="recaptchaTheme" class="span2">
		 <?php
		 foreach ($gRC_Themes AS $rctk => $rctv) {
		 ?>
         <option value="<?php echo $rctk; ?>"<?php echo mswSelectedItem($SETTINGS->recaptchaTheme,$rctk); ?>><?php echo $rctv; ?></option>
         <?php
		 }
		 ?>
		 </select>
		 
		</div>
       </div>
	   <div class="tab-pane fade" id="five">
	    <div class="well" style="margin-bottom:0">
		 
		 <label class="checkbox">
          <input type="checkbox" name="attachment" value="yes"<?php echo ($SETTINGS->attachment=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings3; ?>
         </label>
		 
		 <label class="checkbox">
		  <input type="checkbox" name="rename" value="yes"<?php echo ($SETTINGS->rename=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings76; ?>
         </label>
		  
		 <label><br><?php echo $msg_settings4; ?></label>
         <input type="text" class="input-xlarge" name="filetypes" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo $SETTINGS->filetypes; ?>">
         
		 <label><?php echo $msg_settings5; ?> (<?php echo $msg_script19; ?>)</label>
         <div class="input-append">
		  <input type="text" class="input-xlarge" id="max" tabindex="<?php echo (++$tabIndex); ?>" maxlength="15" name="maxsize" value="<?php echo $SETTINGS->maxsize; ?>">
		  <div class="btn-group">
           <button class="btn dropdown-toggle" data-toggle="dropdown">
           <?php echo $msg_settings95; ?>
           <span class="caret"></span>
           </button>
           <div class="dropdown-menu page-dropdowns" style="padding:5px">
		    <?php
			$mb = (1024*1024);
			$gb = ((1024*1024)*1024);
			$l  = 0;
			$sizes    =  array(
			  (1*$mb).'|1MB',
			  (2*$mb).'|2MB',
			  (3*$mb).'|3MB',
			  (4*$mb).'|4MB',
			  (5*$mb).'|5MB',
			  (10*$mb).'|10MB',
			  (15*$mb).'|15MB',
			  (20*$mb).'|20MB',
			  (50*$mb).'|50MB',
			  (100*$mb).'|100MB',
			  (1*$gb).'|1GB',
			  (2*$gb).'|2GB',
			  (5*$gb).'|5GB',
			  'x|'.$msg_settings96
			);
			foreach ($sizes AS $sk) {
			++$l;
			if (in_array($l,array(6,10,14))) {
			  echo '<br>';
			}
			$chop = explode('|',$sk);
			?>
			<span onclick="jQuery('#max').val('<?php echo ($chop[0]=='x' ? '' : $chop[0]); ?>')" title="<?php echo $chop[1]; ?>"><?php echo $chop[1]; ?></span><?php echo ($chop[0]!='x' ? ', ' : '').PHP_EOL; ?>
			<?php
			}
			?>
		   </div>
          </div>
         </div>
		 
		 <label><?php echo $msg_settings25; ?></label>
         <input type="text" class="input-small" maxlength="3" name="attachboxes" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo $SETTINGS->attachboxes; ?>">
         
		 <label><?php echo $msg_settings27; ?></label>
         <div class="input-append">
		  <input type="text" class="input-xxlarge" name="attachpath" maxlength="250" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo mswCleanData($SETTINGS->attachpath); ?>">
		  <span class="add-on"><a href="#" onclick="autoPath('server','attachpath');return false"><i class="icon-refresh" title="<?php echo mswSpecialChars($msg_settings127); ?>"></i></a></span>
         </div>
		 
		 <label><?php echo $msg_settings94; ?></label>
		 <div class="input-append">
          <input type="text" class="input-xxlarge" name="attachhref" maxlength="250" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo mswCleanData($SETTINGS->attachhref); ?>">
          <span class="add-on"><a href="#" onclick="autoPath('http','attachhref');return false"><i class="icon-refresh" title="<?php echo mswSpecialChars($msg_settings127); ?>"></i></a></span>
         </div>
		 
		</div>
       </div>
	   <div class="tab-pane fade" id="six">
	    <div class="well" style="margin-bottom:0">
		 
		 <label class="checkbox">
          <input type="checkbox" name="kbase" value="yes"<?php echo ($SETTINGS->kbase=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings; ?>
         </label>
		 
		 <label class="checkbox">
		  <input type="checkbox" name="enableVotes" value="yes"<?php echo ($SETTINGS->enableVotes=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings57; ?>
         </label>
		 
		 <label class="checkbox">
		  <input type="checkbox" name="multiplevotes" value="yes"<?php echo ($SETTINGS->multiplevotes=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings32; ?>
         </label>
		 
		 <label class="checkbox">
		  <input type="checkbox" name="renamefaq" value="yes"<?php echo ($SETTINGS->renamefaq=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings76; ?>
         </label>
		 
		 <label><br><?php echo $msg_settings33; ?></label>
         <input type="text" class="input-small" maxlength="5" tabindex="<?php echo (++$tabIndex); ?>" name="popquestions" value="<?php echo $SETTINGS->popquestions; ?>">
         
		 <label><?php echo $msg_settings34; ?></label>
         <input type="text" class="input-small" maxlength="5" tabindex="<?php echo (++$tabIndex); ?>" name="cookiedays" value="<?php echo $SETTINGS->cookiedays; ?>">
         
		 <label><?php echo $msg_settings68; ?></label>
         <input type="text" class="input-small" maxlength="3" tabindex="<?php echo (++$tabIndex); ?>" name="quePerPage" value="<?php echo $SETTINGS->quePerPage; ?>">
		 
		 <label><?php echo $msg_settings98; ?></label>
         <div class="input-append">
		  <input type="text" class="input-xxlarge" name="attachpathfaq" maxlength="250" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo mswCleanData($SETTINGS->attachpathfaq); ?>">
		  <span class="add-on"><a href="#" onclick="autoPath('server','attachpathfaq');return false"><i class="icon-refresh" title="<?php echo mswSpecialChars($msg_settings127); ?>"></i></a></span>
         </div>
		 
		 <label><?php echo $msg_settings97; ?></label>
         <div class="input-append">
		  <input type="text" class="input-xxlarge" name="attachhreffaq" maxlength="250" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo mswCleanData($SETTINGS->attachhreffaq); ?>">
          <span class="add-on"><a href="#" onclick="autoPath('http','attachhreffaq');return false"><i class="icon-refresh" title="<?php echo mswSpecialChars($msg_settings127); ?>"></i></a></span>
         </div>
		 
		</div>
       </div>
	   <div class="tab-pane fade" id="seven">
	    <div class="well" style="margin-bottom:0">
		 
		 <label class="checkbox">
		  <input type="checkbox" name="enableMail" value="yes"<?php echo ($SETTINGS->enableMail=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings115; ?>
		 </label>
		 
		 <label class="checkbox">
		  <input type="checkbox" name="smtp_debug" value="yes"<?php echo ($SETTINGS->smtp_debug=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings15; ?>
		 </label>
		
		 <label><br><?php echo $msg_settings16; ?></label>
		 <div class="input-append">
          <input type="text" class="input-xlarge" name="smtp_host" maxlength="100" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo $SETTINGS->smtp_host; ?>">
          <span class="add-on"><a href="?p=settings&amp;mailTest=yes" class="nyroModal"><i class="icon-circle-arrow-right" title="<?php echo mswSpecialChars($msg_settings116); ?>"></i></a></span>
         </div>
		 
		 <label><?php echo $msg_settings17; ?></label>
         <input type="text" class="input-xlarge" name="smtp_user" maxlength="100" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo $SETTINGS->smtp_user; ?>">
         
		 <label><?php echo $msg_settings18; ?></label>
         <input type="password" class="input-xlarge" name="smtp_pass" maxlength="100" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo mswSpecialChars($SETTINGS->smtp_pass); ?>">
         
		 <label><?php echo $msg_settings19; ?></label>
         <input type="text" class="input-small" name="smtp_port" maxlength="4" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo $SETTINGS->smtp_port; ?>"> / 
		 <select name="smtp_security" tabindex="<?php echo ++$tabIndex; ?>" class="span1">
		  <option value=""<?php echo mswSelectedItem($SETTINGS->smtp_security,''); ?>><?php echo $msg_settings78; ?></option>
		  <option value="tls"<?php echo mswSelectedItem($SETTINGS->smtp_security,'tls'); ?>><?php echo $msg_settings79; ?></option>
		  <option value="ssl"<?php echo mswSelectedItem($SETTINGS->smtp_security,'ssl'); ?>><?php echo $msg_settings80; ?></option>
		 </select>
        
		</div>
       </div>
	   <div class="tab-pane fade" id="nine">
	    <div class="well" style="margin-bottom:0">
		 
		 <label><?php echo $msg_settings112; ?></label>
         <input type="text" class="input-small" maxlength="5" name="defKeepLogs[user]" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($defLogs['user']) ? $defLogs['user'] : '0'); ?>">
         
		 <label><?php echo $msg_settings113; ?></label>
         <input type="text" class="input-small" maxlength="5" name="defKeepLogs[acc]" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($defLogs['acc']) ? $defLogs['acc'] : '0'); ?>">
          
        </div>
       </div>
	   <div class="tab-pane fade" id="one_h">
	    <div class="well" style="margin-bottom:0">
		 
		 <label class="checkbox">
		  <input type="checkbox" name="imap_debug" value="yes"<?php echo ($SETTINGS->imap_debug=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_settings120; ?>
		 </label>
		 
		 <label><br><?php echo $msg_settings121; ?></label>
         <input type="text" class="input-small" maxlength="10" name="imap_param" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo $SETTINGS->imap_param; ?>">
         
		 <label><?php echo $msg_settings122; ?></label>
         <input type="text" class="input-small" maxlength="3" name="imap_memory" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo $SETTINGS->imap_memory; ?>"> M
         
		 <label><?php echo $msg_settings123; ?></label>
         <input type="text" class="input-small" maxlength="3" name="imap_timeout" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo $SETTINGS->imap_timeout; ?>">
          
        </div>
       </div>
	   <?php
       if (LICENCE_VER=='unlocked') {
       ?>
	   <div class="tab-pane fade" id="eight">
	    <div class="well" style="margin-bottom:0">
		 
		 <label><?php echo $msg_settings54; ?></label>
         <textarea name="adminFooter" rows="5" cols="20" tabindex="<?php echo (++$tabIndex); ?>"><?php echo mswSpecialChars($SETTINGS->adminFooter); ?></textarea>
         
		 <label><?php echo $msg_settings55; ?></label>
         <textarea name="publicFooter" rows="5" cols="20" tabindex="<?php echo (++$tabIndex); ?>"><?php echo mswSpecialChars($SETTINGS->publicFooter); ?></textarea>
        
		</div>
       </div>
	   <?php
	   }
	   ?>
      </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <input type="hidden" name="process" value="1">
       <button class="btn btn-primary" type="submit"><i class="icon-ok"></i> <?php echo mswCleanData($msg_settings7); ?></button>
      </div>
	  <?php
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>
  
  </div>
  </form>

</div>