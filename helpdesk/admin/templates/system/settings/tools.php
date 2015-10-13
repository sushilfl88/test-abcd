<?php if (!defined('PARENT')) { exit; } 
$resetMsg = @file_get_contents(REL_PATH.'content/language/'.$SETTINGS->language.'/mail-templates/pass-reset.txt');
?>
<div class="content">
  <script type="text/javascript">
  //<![CDATA[
  function ms_resetPurgeFields(type) {
    if (type=='blanks') {
	  jQuery('#purgeButtonArea').hide();
	  jQuery('#passButtonArea').hide();
	} else {
      jQuery('input[name="purge-type"]').val(type);
	  ms_divHideShow('purgeButton','passButton');
	  ms_divHideShow('purgeButtonArea','passButtonArea');
	  jQuery('input[name="purge-type"]').val(type);
	  jQuery('#purgeButton').prop('disabled',true);
	  jQuery('#passButton').prop('disabled',true);
	  switch (type) {
	    case 'reset':
	    ms_divHideShow('passButton','purgeButton');
	    ms_divHideShow('passButtonArea','purgeButtonArea');
	    break;
	  }
	}
  }
  function ms_enableButtons(area,button) {
    var boxes = 0;
	switch (area) {
	  case 'purge1':
	  var days = parseInt(jQuery('input[name="days1"]').val());
	  jQuery('#boxes_'+area+' input[type="checkbox"]').each(function() {
	    if (jQuery(this).prop('checked')) {
          ++boxes;
	    }
	  });
	  if (days>0 && boxes>0) {
	    jQuery('#'+button).prop('disabled',false); 
	  } else {
	    jQuery('#'+button).prop('disabled',true);
	  }
	  break;
	  case 'purge2':
	  var days = parseInt(jQuery('input[name="days2"]').val());
	  jQuery('#boxes_'+area+' input[type="checkbox"]').each(function() {
	    if (jQuery(this).prop('checked')) {
          ++boxes;
	    }
	  });
	  if (days>0 && boxes>0) {
	    jQuery('#'+button).prop('disabled',false); 
	  } else {
	    jQuery('#'+button).prop('disabled',true);
	  }
	  break;
	  case 'purge3':
	  var days = parseInt(jQuery('input[name="days3"]').val());
	  if (days>0) {
	    jQuery('#'+button).prop('disabled',false); 
	  } else {
	    jQuery('#'+button).prop('disabled',true);
	  }
	  break;
	  case 'reset':
	  if (jQuery('input[name="visitors"]:checked').prop('checked') ||
	      jQuery('input[name="team"]:checked').prop('checked')) {
	    jQuery('#'+button).prop('disabled',false);
      } else {
	    jQuery('#'+button).prop('disabled',true);
	  }
	  break;
	}
  }
  function ms_selectMailTag(val) {
    if (val) {
	  ms_insertAtCursor('message',val);
	  return true;
	}
	return false;
  }
  //]]>
  </script>
  <div class="header">
    
	<h1 class="page-title"><?php echo $msg_adheader15; ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader37; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_adheader15; ?></li>
  </ul>
  
  <?php
  // Delete tickets..
  if (isset($OK1)) {
    echo mswActionCompleted(str_replace(array('{count1}','{count2}','{count3}'),array($counts[0],$counts[1],$counts[2]),$msg_tools8));
  }
  // Delete attachments..
  if (isset($OK2)) {
    echo mswActionCompleted(str_replace('{count}',$count,$msg_tools9));
  }
  // Password reset..
  if (isset($OK3)) {
    echo mswActionCompleted(str_replace(array('{count}','{count2}'),array(@number_format($cnt[0]),@number_format($cnt[1])),$msg_tools18));
  }
  // Delete accounts..
  if (isset($OK4)) {
    echo mswActionCompleted(str_replace('{count}',$count,$msg_tools25));
  }
  // Enable/disable..
  if (isset($OK5)) {
    echo mswActionCompleted($txt);
  }
  ?>

  <form method="post" id="form" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('none','none')">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  <ul class="nav nav-tabs">
	   <li class="dropdown active">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-eraser"></i> <?php echo $msg_tools13; ?><b class="caret"></b></a>
        <ul class="dropdown-menu">
		 <li><a href="#one" data-toggle="tab" onclick="ms_resetPurgeFields('purge1')"><i class="icon-ban-circle"></i> <?php echo $msg_tools2; ?></a></li>
		 <li><a href="#two" data-toggle="tab" onclick="ms_resetPurgeFields('purge2')"><i class="icon-ban-circle"></i> <?php echo $msg_tools6; ?></a></li>
		 <li><a href="#five" data-toggle="tab" onclick="ms_resetPurgeFields('purge3')"><i class="icon-ban-circle"></i> <?php echo $msg_tools26; ?></a></li>
		</ul>
	   </li>
	   <li><a href="#four" data-toggle="tab" onclick="ms_resetPurgeFields('blanks')"><i class="icon-flag"></i> <?php echo $msg_tools24; ?></a></li>
	   <?php
	   if ($MSTEAM->id=='1') {
	   ?>
	   <li><a href="#three" data-toggle="tab" onclick="ms_resetPurgeFields('reset')"><i class="icon-lock"></i> <?php echo $msg_tools12; ?></a></li>
	   <?php
	   }
	   ?>
	  </ul>

	  <div id="myTabContent" class="tab-content">
	   <div class="tab-pane active in" id="one">
	    <div class="well" style="margin-bottom:10px">
		 
		 <label><?php echo $msg_tools3; ?></label>
         <input class="input-small" type="text" name="days1" tabindex="<?php echo (++$tabIndex); ?>" value="" onkeyup="ms_enableButtons('purge1','purgeButton')">
         
		 <div style="margin-top:10px" id="boxes_purge1">
		 <?php
         $q_dept = mysql_query("SELECT * FROM `".DB_PREFIX."departments` ".mswSQLDepartmentFilter($mswDeptFilterAccess,'WHERE')." ORDER BY `name`") 
                   or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
         while ($DEPT = mysql_fetch_object($q_dept)) {
         ?>
		 <label class="checkbox">
          <input type="checkbox" name="dept1[]" value="<?php echo $DEPT->id; ?>" onclick="ms_enableButtons('purge1','purgeButton')" checked="checked"> <?php echo mswCleanData($DEPT->name); ?>
         </label>
		 <?php
         }
         ?>
		 </div>
         
		 <label class="checkbox"><br>
          <input type="checkbox" name="clear" value="yes"> <?php echo $msg_tools5; ?>
		 </label>
		 
	    </div>
	   </div>
	   <div class="tab-pane face" id="five">
	    <div class="well" style="margin-bottom:10px">
		 
		 <label><?php echo $msg_tools3; ?></label>
         <input class="input-small" type="text" name="days3" tabindex="<?php echo (++$tabIndex); ?>" value="" onkeyup="ms_enableButtons('purge3','purgeButton')">
         
		 <label class="checkbox"><br>
          <input type="checkbox" name="mail" value="yes"> <?php echo $msg_tools27; ?>
		 </label>
		 
		</div>
	   </div>
	   <div class="tab-pane fade" id="two">
	    <div class="well" style="margin-bottom:10px">
		 
		 <label><?php echo $msg_tools3; ?></label>
         <input class="input-small" type="text" name="days2" tabindex="<?php echo (++$tabIndex); ?>" value="" onkeyup="ms_enableButtons('purge2','purgeButton')">
         
		 <div style="margin-top:10px" id="boxes_purge2">
		 <?php
         $q_dept = mysql_query("SELECT * FROM `".DB_PREFIX."departments` ".mswSQLDepartmentFilter($mswDeptFilterAccess,'WHERE')." ORDER BY `name`") 
                   or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
         while ($DEPT = mysql_fetch_object($q_dept)) {
         ?>
		 <label class="checkbox">
         <input type="checkbox" name="dept2[]" value="<?php echo $DEPT->id; ?>" onclick="ms_enableButtons('purge2','purgeButton')" checked="checked"> <?php echo mswCleanData($DEPT->name); ?>
         </label>
		 <?php
         }
         ?>
		 </div>
		 
	    </div>
	   </div>
	   <?php
	   if ($MSTEAM->id=='1') {
	   ?>
	   <div class="tab-pane fade" id="three">
	    <div class="well" style="margin-bottom:10px">
		 
		 <label class="checkbox">
          <input type="checkbox" name="visitors" value="yes" onclick="ms_enableButtons('reset','passButton')"> <?php echo $msg_tools15; ?>
		 </label>
		 
		 <label class="checkbox">
          <input type="checkbox" name="team" value="yes" onclick="ms_enableButtons('reset','passButton')"> <?php echo $msg_tools16; ?>
		 </label>
		 
		 <label class="checkbox">
          <input type="checkbox" name="disabled" value="yes"> <?php echo $msg_tools19; ?>
		 </label>
		 
		 <label class="checkbox">
          <input type="checkbox" name="sendmail" value="yes" checked="checked" onclick="if(this.checked){jQuery('#msgArea').show()}else{jQuery('#msgArea').hide()}"> <?php echo $msg_tools21; ?>
		 </label>
		 
		 <div id="msgArea">
		 <label><br><?php echo $msg_tools17; ?></label>
         <textarea name="message" id="message" rows="5" cols="20" tabindex="<?php echo (++$tabIndex); ?>"><?php echo mswSpecialChars($resetMsg); ?></textarea><br>
		 <select class="span2" onchange="ms_selectMailTag(this.value)">
		  <option value=""><?php echo $msg_tools22; ?></option>
		  <?php
		  foreach ($msg_tools23 AS $k => $v) {
		  ?>
		  <option value="<?php echo $k; ?>"><?php echo $k.' = '.$v; ?></option>
		  <?php
		  }
		  ?>
		 </select>
		 <span style="display:block;font-size:11px">
		 <?php echo $msg_tools20; ?>: content/language/<?php echo $SETTINGS->language; ?>/mail-templates/pass-reset.txt
		 </span>
		 </div>
		 
	    </div>
	   </div>
	   <?php
	   }
	   ?>
	   <div class="tab-pane fade" id="four">
	    <div class="well" style="margin-bottom:10px">
		 
		 <?php
		 foreach ($batchEnDisFields AS $k => $v) {
		 ?>
		 <label class="checkbox">
		  <input type="checkbox" name="tbls[]" value="<?php echo $k; ?>"> <?php echo $v; ?>
		 </label>
		 <?php
		 }
		 ?>
		 
		 <br>
		 <select name="endis-option" onchange="if(this.value!='0'){ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','enable-disable');return false;}">
		  <option value="0">- - - - - - -</option>
		  <option value="enable"><?php echo $msg_tools28; ?></option>
		  <option value="disable"><?php echo $msg_tools29; ?></option>
		 </select>
		 
	    </div>
	   </div>
	  </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0" id="purgeButtonArea">
	   <button class="btn btn-danger" onclick="ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','purge');return false;" id="purgeButton" disabled="disabled"><i class="icon-eraser" style="padding-right:0;margin-right:0"></i> <?php echo mswCleanData($msg_tools4); ?></button>
	  </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0;display:none" id="passButtonArea">
	   <button class="btn btn-danger" onclick="ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','reset');return false;" id="passButton"  disabled="disabled" style="display:none"><i class="icon-lock"></i> <?php echo mswCleanData($msg_tools14); ?></button>
      </div>
	  <?php
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>
    
	<input type="hidden" name="purge-type" value="purge1">
  </div>
  </form>

</div>