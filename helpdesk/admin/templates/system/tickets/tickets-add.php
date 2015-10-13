<?php if (!defined('PARENT')) { exit; } 
$countOfCusFields  = mswRowCount('cusfields WHERE `enField` = \'yes\'');
$countOfOtherUsers = mswRowCount('users WHERE `id` > 0');
$dept              = array();
?>
<div class="content">
  <script type="text/javascript">
  //<![CDATA[
  function ms_uncheckAssigned(area) {
    switch (area) {
	  case 'box':
	  if (jQuery('.well input[name="waiting"]:checkbox').val()) {
	    jQuery('.well input[name="waiting"]:checkbox').prop('checked',false);
	  }
	  break;
	  case 'wait':
	  alert('===ff==');
      jQuery('#assignIDSet input[type="checkbox"]:checkbox').not('.creater').prop('checked',false);
	  jQuery('input[name="assignMail"]:checkbox').prop('checked',false);
	  break;
	}  
  }
  function addTicketCusFields(dept) {
    jQuery(document).ready(function() {
     jQuery.ajax({
      url: 'index.php',
      data: 'ajax=add-cus-field&dept='+dept,
      dataType: 'json',
      success: function (data) {
	    if (data['fields']) {
		  if (jQuery('#cusFieldsTab').css('display')=='none') {
		    jQuery('#cusFieldsTab').show();
		  }
		  jQuery('#customFieldsArea').html(data['fields']);
	    } else {
		  if (jQuery('#cusFieldsTab').css('display')!='none') {
		    jQuery('#cusFieldsTab').hide();
		  }
	      jQuery('#customFieldsArea').html(data['fields']);
	    }
      }
     });
    });
    return false;
  }
  //]]>
  </script>
  <div class="header">
    
	<h1 class="page-title"><?php echo $msg_open; ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader41; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_open; ?></li>
  </ul>
  
  <?php
  // Added or failed..
  if (isset($OK)) {
    switch ($OK) {
	  case 'ok':
      echo mswActionCompleted(str_replace('{id}',$ID,$msg_add8));
	  break;
	  case 'fail':
	  echo mswActionCompletedFail($msg_add11);
	  break;
	}  
  }
  
  // Are there any departments?
  $q_dept = mysql_query("SELECT `id`,`name` FROM `".DB_PREFIX."departments` ".mswSQLDepartmentFilter($mswDeptFilterAccess,'WHERE')." ORDER BY `name`") 
            or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  if (mysql_num_rows($q_dept)>0) {
  ?>

  <form method="post" action="?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" enctype="multipart/form-data" id="formfield">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  <ul class="nav nav-tabs" id="tabAreaAdd">
       <li class="active"><a href="#one" data-toggle="tab" onclick="jQuery('#prev').show();return false;"><i class="icon-file-text-alt"></i> <?php echo $msg_add; ?></a></li>
       <li><a href="#two" data-toggle="tab" onclick="jQuery('#prev').hide();return false"><i class="icon-user"></i> <?php echo $msg_add5; ?></a></li>
	   <?php
	   if ($countOfCusFields>0) {
	   ?>
       <li id="cusFieldsTab"><a href="#three" data-toggle="tab" onclick="jQuery('#prev').hide();return false"><i class="icon-list-alt"></i> <?php echo $msg_add2; ?></a></li>
	   <?php
	   }
	   if ($SETTINGS->attachment=='yes') {
	   ?>
	   <li><a href="#four" data-toggle="tab" onclick="jQuery('#prev').hide();return false"><i class="icon-paper-clip"></i> <?php echo $msg_add3; ?></a></li>
	   <?php
	   }
	   if ($countOfOtherUsers>0) {
	   ?>
	   <li><a href="#five" data-toggle="tab" onclick="jQuery('#prev').hide();return false"><i class="icon-signin"></i> <?php echo $msg_add4; ?></a></li>
	   <?php
	   }
	   ?>
	  </ul>

	  <div id="myTabContent" class="tab-content">
	   <div class="tab-pane active in" id="one">
	    <div class="well">
		
		  <label><?php echo $msg_newticket15; ?></label>
          <input type="text" class="input-xxlarge" name="subject" tabindex="<?php echo (++$tabIndex); ?>" value="">
		  
		  <label><?php echo $msg_newticket6; ?></label>
		  <select name="dept" tabindex="<?php echo (++$tabIndex); ?>" onchange="addTicketCusFields(this.value)">
		  <?php
          while ($DEPT = mysql_fetch_object($q_dept)) {
		  $dept[] = $DEPT->id;
          ?>
          <option value="<?php echo $DEPT->id; ?>"><?php echo mswCleanData($DEPT->name); ?></option>
          <?php
          }
          ?>
		  </select>
		  
		  <label><?php echo $msg_newticket8; ?></label>
		  <select name="priority" tabindex="<?php echo (++$tabIndex); ?>">
		  <?php
		  if (!empty($ticketLevelSel)) {
          foreach ($ticketLevelSel AS $k => $v) {
          ?>
          <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
          <?php
          }
		  }
          ?>
		  </select>
		  
		  <div class="addCommsWrapper">
		  <?php
		  // BBCode..
		  include(PATH.'templates/system/bbcode-buttons.php');
		  ?>
		  <textarea name="comments" rows="15" cols="40" id="comments" tabindex="<?php echo (++$tabIndex); ?>"></textarea>
		  <?php
		  // Preview area..do not remove empty div
		  ?>
		  <div id="previewArea" class="previewArea prevTickets" onclick="ms_closePreview('comments','previewArea')"></div>
		  </div>
		  
		  <label class="checkbox">
           <input type="checkbox" name="closed" value="yes"> <?php echo $msg_add13; ?>
          </label>
		
		</div>
	   </div>
	   <div class="tab-pane fade" id="two">
	    <div class="well">
		 
		 <label><?php echo $msg_viewticket2; ?></label>
         <div class="input-append" id="acc">
		  <input type="text" class="input-xlarge" name="name" tabindex="<?php echo (++$tabIndex); ?>" value="" onkeyup="if(jQuery('#acc-search')){jQuery('#acc-search').hide()}">
		  <span class="add-on"><a href="#" onclick="searchAccounts('<?php echo str_replace("'","\'",$msg_add7); ?>','name','acc',0);return false" title="<?php echo mswSpecialChars($msg_add6); ?>"><i class="icon-search"></i> </a></span>
         </div>
		 
		 <label><?php echo $msg_viewticket3; ?></label>
         <div class="input-append" id="acc2">
		  <input type="text" class="input-xlarge" name="email" tabindex="<?php echo (++$tabIndex); ?>" value="">
		  <span class="add-on"><a href="#" onclick="searchAccounts('<?php echo str_replace("'","\'",$msg_add7); ?>','email','acc2',0);return false" title="<?php echo mswSpecialChars($msg_add6); ?>"><i class="icon-search"></i> </a></span>
         </div>
		  
		 <label class="checkbox">
          <input type="checkbox" name="accMail" value="yes" checked="checked"> <?php echo $msg_viewticket18; ?>
         </label>
		 
		</div>
	   </div>
	   <?php
	   if ($countOfCusFields>0) {
	   ?>
	   <div class="tab-pane fade" id="three">
	    <div class="well">
		 
		<div class="customFields collapse in" id="customFieldsArea">
        <?php
		// Custom fields..
		$qF = mysql_query("SELECT * FROM `".DB_PREFIX."cusfields`
              WHERE FIND_IN_SET('ticket',`fieldLoc`)   > 0
              AND `enField`                            = 'yes'
			  AND FIND_IN_SET('{$dept[0]}',`departments`) > 0
              ORDER BY `orderBy`
              ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
        if (mysql_num_rows($qF)>0) {
         while ($FIELDS = mysql_fetch_object($qF)) {
          switch ($FIELDS->fieldType) {
            case 'textarea':
            echo $MSFM->buildTextArea(mswCleanData($FIELDS->fieldInstructions),$FIELDS->id,(++$tabIndex));
            break;
            case 'input':
            echo $MSFM->buildInputBox(mswCleanData($FIELDS->fieldInstructions),$FIELDS->id,(++$tabIndex));
            break;
            case 'select':
            echo $MSFM->buildSelect(mswCleanData($FIELDS->fieldInstructions),$FIELDS->id,$FIELDS->fieldOptions,(++$tabIndex));
            break;
            case 'checkbox':
            echo $MSFM->buildCheckBox(mswCleanData($FIELDS->fieldInstructions),$FIELDS->id,$FIELDS->fieldOptions);
            break;
          }
         }
        }
	    ?>
		</div>
		
		</div>
	   </div>
	   <?php
	   }
	   if ($SETTINGS->attachment=='yes') {
	   ?>
	   <div class="tab-pane fade" id="four">
	    <div class="well">
		 
		 <label><?php echo $msg_viewticket78; ?></label>
		 <div>
          <span class="attachBox"><input type="file" class="input-small" name="attachment[]"></span>
		 </div>
         <?php
         if (LICENCE_VER=='unlocked') {
         ?>
         <p class="attachlinks">
          <button class="btn" type="button" title="<?php echo mswSpecialChars($msg_newticket37); ?>" onclick="ms_attachBox('add','<?php echo ADMIN_ATTACH_BOX_OVERRIDE; ?>')">+</button>
          <button class="btn" type="button" title="<?php echo mswSpecialChars($msg_newticket38); ?>" onclick="ms_attachBox('remove')">-</button>
         </p>
         <?php
         }
         ?>
		
		</div>
	   </div>
	   <?php
	   }
	   if ($countOfOtherUsers>0) {
	   ?>
	   <div class="tab-pane fade" id="five">
	    <div class="well">
		 
		 <div id="assignIDSet" style="margin-bottom:20px">
		 <?php

         $q_users      = mysql_query("SELECT * FROM `".DB_PREFIX."users` ORDER BY `name`") 
                         or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
         while ($USERS = mysql_fetch_object($q_users)) {
         	$checked='';
         	$toggleHideShow="style=''";
         	$class='';
         	if($MSTEAM->id == $USERS->id){
         		$checked='checked';
         		$toggleHideShow="style='display:none;'";
         		 $class="class='creater'";
         	}
         ?>
	     <label class="checkbox" <?php echo $toggleHideShow; ?>>
          <input type="checkbox" <?php echo $checked; ?> <?php echo $class;?> name="assigned[]" value="<?php echo $USERS->id; ?>" onclick="if(this.checked){ms_uncheckAssigned('box')}"> <?php echo mswCleanData($USERS->name); ?>
         </label>
	     <?php
         } 
         ?>
		 </div>
		 
		 <!-- <label class="checkbox">
          <input type="checkbox" name="waiting" value="yes" onclick="if(this.checked){ms_uncheckAssigned('wait')}"> <?php echo $msg_add10; ?>
         </label> -->
		 
		 <label class="checkbox">
          <input type="checkbox" name="assignMail" value="yes" checked="checked"> <?php echo $msg_viewticket18.' '.$msg_add12; ?>
         </label>
		
		</div>
	   </div>
	   <?php
	   }
	   ?>
	  </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
       <input type="hidden" name="process" value="1">
       <button class="btn btn-primary" type="button" onclick="addTicket()"><i class="icon-plus"></i> <?php echo $msg_open; ?></button>
       <button class="btn" type="button" onclick="ms_textPreview('view-ticket','comments','previewArea')" id="prev"><i class="icon-search"></i> <?php echo mswCleanData($msg_viewticket55); ?></button>
	   <button class="btn" type="button" onclick="ms_closePreview('comments','previewArea')" style="display:none" id="clse"><i class="icon-remove"></i> <?php echo mswCleanData($msg_viewticket101); ?></button>
      </div>
	  <?php
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>
	<?php
	} else {
	?>
	<div class="container-fluid" style="margin-top:20px">
    
	  <div class="row-fluid">
	  <div class="alert alert-error">
       <i class="icon-warning-sign"></i> <?php echo $msg_add9; ?>
      </div>
	  <?php
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
	  </div>
	
	</div>  
	<?php
	}
	?>
  
  </div>
  </form>

</div>