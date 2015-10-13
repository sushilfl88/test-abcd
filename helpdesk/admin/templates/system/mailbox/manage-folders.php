<?php if (!defined('PARENT')) { exit; } ?>
<div class="content">
  <script type="text/javascript">
  //<![CDATA[
  function ms_folderBox(type,max) {
    switch (type) {
      case 'add':
      var n  = jQuery('.folder').length;
	  var nb = jQuery('.folder input[name="new[]"]').length;
      if (n<max) {
        jQuery('.folder').last().after(jQuery('.folder').last().clone());
		jQuery('.folder').last().attr('id','folder_newbox_'+parseInt(nb+1));
		jQuery('.folder .add-on a').last().attr('onclick','ms_folderBox(\'remove\',\'folder_newbox_'+parseInt(nb+1)+'\');return false');
	    jQuery('.folder input').last().attr('name','new[]');
		jQuery('.folder input').last().val('');
		var n = jQuery('.folder').length;
		// If limit reached, hide add button..
		if (n==max) {
		  jQuery('#addButton').hide();
		}
      }
      break;
      case 'remove':
      jQuery('#'+max).remove();
	  var n = jQuery('.folder').length;
	  // If no boxes, shown default box on initial load..
	  if (n==0) {
	    var html = '<div class="folder" id="folder_newbox_1">';
		html += '<div class="input-append">';
		html += '<input type="text" class="input-large" maxlength="50" tabindex="1" name="new[]" value="">';
	    html += '<span class="add-on"><a href="#" onclick="ms_folderBox(\'remove\',\'folder_newbox_1\');return false"><i class="icon-trash"></i></a></span>';
		html += '</div>';
		html += '</div>';
		jQuery('div[class="well"] label').after(html);
		jQuery('div[class="well"] input').focus();
	  }
	  if (jQuery('#addButton').css('display')=='none') {
	    jQuery('#addButton').show();
	  }
      break;
    }
  }
  //]]>
  </script>
  <div class="header">
    
	<button class="btn search-bar-button" type="button" onclick="mswToggle('b1','b2','keys')"><i class="icon-search" id="search-icon-button"></i></button>
	<h1 class="page-title"><?php echo $msg_adheader61; ?> (<?php echo $msg_mailbox6; ?>)</h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader61; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_mailbox6; ?></li>
  </ul>
  
  <?php
  // Added..
  if (isset($OK)) {
    echo mswActionCompleted(str_replace('{count}',$delCount,$msg_mailbox14));
  }
  ?>

  <form method="post" action="index.php?p=<?php echo $_GET['p']; ?>&amp;folders=1" id="form">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  <?php
	  // Search..
	  include(PATH.'templates/system/bootstrap/search-box.php');
	  // Mailbox menu..
	  include(PATH.'templates/system/mailbox/mailbox-nav.php');
	  ?>
      <div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="one">
		 <div class="well">
		  
		 <label><?php echo $msg_mailbox13.($MSTEAM->mailFolders>0 ? ' (<b>'.$msg_mailbox15.': '.$MSTEAM->mailFolders.'</b>)' : ''); ?></label>
		  <?php
	      $qF = mysql_query("SELECT `id`,`folder`
                FROM `".DB_PREFIX."mailfolders`
			    WHERE `staffID` = '{$MSTEAM->id}'
                ORDER BY `folder`
			    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
		  $foundRows = mysql_num_rows($qF);
		  if ($foundRows>0) {		
          while ($FOLDERS = mysql_fetch_object($qF)) {
		  // Hide add button on page load if folder limit is reached..
		  if ($MSTEAM->mailFolders>0 && $MSTEAM->mailFolders==$foundRows) {
		    define('HIDE_ADD_BUTTON', 1);
		  }
		  ?>
		  <div class="folder" id="folder_box_<?php echo $FOLDERS->id; ?>">
		   <div class="input-append">
		    <input type="text" class="input-large" maxlength="50" tabindex="<?php echo (++$tabIndex); ?>" name="folder[<?php echo $FOLDERS->id; ?>]" value="<?php echo mswSpecialChars($FOLDERS->folder); ?>">
			<span class="add-on"><a href="#" onclick="ms_folderBox('remove','folder_box_<?php echo $FOLDERS->id; ?>');return false" title="<?php echo mswSpecialChars($msg_script47); ?>"><i class="icon-trash"></i></a></span>
		   </div>
		  </div>
		  <?php
		  }
		  } else {
		  ?>
		  <div class="folder" id="folder_newbox_1">
		   <div class="input-append">
		    <input type="text" class="input-large" maxlength="50" tabindex="<?php echo (++$tabIndex); ?>" name="new[]" value="">
			<span class="add-on"><a href="#" onclick="ms_folderBox('remove','folder_newbox_1');return false" title="<?php echo mswSpecialChars($msg_script47); ?>"><i class="icon-trash"></i></a></span>
		   </div>
		  </div>
		  <?php
		  }
		  ?>
		 </div>
		</div>
	  </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <div class="pull-left">
	    <button class="btn btn-primary" type="submit" onclick="ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','update-folders');return false;"><i class="icon-ok"></i> <?php echo $msg_mailbox12; ?></button>
	   </div>
	   <div class="pull-right">
		 <button class="btn" type="button" title="<?php echo mswSpecialChars($msg_script46); ?>" onclick="ms_folderBox('add','<?php echo ($MSTEAM->mailFolders>0 ? $MSTEAM->mailFolders : '9999999'); ?>')" id="addButton" style="margin-right:5px<?php echo (defined('HIDE_ADD_BUTTON') ? ';display:none' : ''); ?>">+</button>
       </div>
	   <span class="clearfix"></span>
      </div>
	  <?php
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>

  </div>
  </form>
	
</div>