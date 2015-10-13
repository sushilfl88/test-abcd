<?php if (!defined('PARENT')) { exit; } 
if (isset($_GET['edit'])) {
  $_GET['edit'] = (int)$_GET['edit'];
  $EDIT         = mswGetTableData('faqattach','id',$_GET['edit']);
  checkIsValid($EDIT);
}
?>
<div class="content">
  <script type="text/javascript">
  //<![CDATA[
  function ms_faqBox(type,max) {
    switch (type) {
      case 'add':
      var n = jQuery('#tabArea li').length;
      if (n<max) {
	    var nextTab = parseInt(n+1);
	    jQuery('div[class="tab-content"]').append('<div class="tab-pane fade" id="tab'+nextTab+'">'+jQuery('div[class="tab-content"] div').html()+'</div>');
		jQuery('div[id="tab'+nextTab+'"] input').val('');
		jQuery('#tabArea li').last().after('<li><a href="#tab'+nextTab+'" data-toggle="tab"><i class="icon-paperclip"></i> '+nextTab+'</a></li>');
		jQuery('#mc_countVal').html('('+nextTab+')');
		jQuery('#tabArea a[href="#tab'+nextTab+'"]').tab('show');
      }
      break;
      case 'remove':
      var n = jQuery('#tabArea li').length;
      if (n>1) {
	    var lastTab = parseInt(n-1);
        jQuery('#tabArea li').last().remove();
		jQuery('div[class="tab-pane fade"]').last().remove();
		jQuery('#mc_countVal').html('('+parseInt(n-1)+')');
		jQuery('#tabArea a[href="#tab'+lastTab+'"]').tab('show');
      }
      break;
    }
  }
  //]]>
  </script>
  <div class="header">
    
	<h1 class="page-title"><?php echo (isset($EDIT->id) ? $msg_attachments12 : $msg_attachments2); ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader17; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo (isset($EDIT->id) ? $msg_attachments12 : $msg_attachments2); ?></li>
  </ul>
  
  <?php
  // Added..
  if (isset($OK1)) {
    echo mswActionCompleted(str_replace('{count}',$total,$msg_attachments10));
  }
  // Updated..
  if (isset($OK2)) {
    echo mswActionCompleted($msg_attachments13);
  }
  ?>

  <form method="post" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('name[]')" enctype="multipart/form-data">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  <ul class="nav nav-tabs" id="tabArea">
       <li class="active"><a href="#tab1" data-toggle="tab"><i class="icon-paperclip"></i> <?php echo $msg_kbase60; ?></a></li>
	  </ul>
	  <div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="tab1">
		 <div class="well" style="margin-bottom:10px">
		  
		  <label><?php echo $msg_attachments3; ?></label>
          <input type="text" class="input-xlarge" name="name[]" value="<?php echo (isset($EDIT->name) ? mswSpecialChars($EDIT->name) : ''); ?>">
          
		  <label><?php echo $msg_attachments4; ?></label>
          <input type="text" class="input-xlarge" name="remote[]" value="<?php echo (isset($EDIT->remote) ? mswSpecialChars($EDIT->remote) : ''); ?>">
      
		  <label><?php echo $msg_attachments5; ?></label>
          <input type="file" class="input-xlarge" name="file[]">
          
		 </div>
		</div>
	  </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <input type="hidden" name="<?php echo (isset($EDIT->id) ? 'update' : 'process'); ?>" value="1">
       <input type="hidden" name="opath" value="<?php echo (isset($EDIT->path) ? $EDIT->path : ''); ?>">
       <input type="hidden" name="osize" value="<?php echo (isset($EDIT->size) ? $EDIT->size : ''); ?>">
       <?php
	   if (isset($EDIT->id)) {
	   ?>
	   <button class="btn btn-primary" type="submit"><i<?php echo (isset($EDIT->id) ? ' class="icon-ok"' : ' class="icon-plus"'); ?>></i> <?php echo mswCleanData((isset($EDIT->id) ? $msg_attachments12 : $msg_attachments2)); ?></button>
       <button class="btn btn-link" type="button" onclick="ms_windowLoc('?p=attachman')"><i class="icon-remove"></i> <?php echo mswCleanData($msg_levels11); ?></button>
       <?php
	   } else {
	   ?>
	   <div class="row-fluid">
	    <div class="pull-left">
		 <button class="btn btn-primary" type="submit"><i class="icon-plus"></i> <?php echo mswCleanData((isset($EDIT->id) ? $msg_attachments12 : $msg_attachments2)); ?> <span id="mc_countVal">(1)</span></button>
        </div>
		<div class="pull-right">
		 <button class="btn" type="button" title="<?php echo mswSpecialChars($msg_script46); ?>" onclick="ms_faqBox('add','<?php echo (LICENCE_VER=='locked' ? RESTR_ATTACH : '9999999'); ?>')" style="margin-right:5px">+</button>
         <button class="btn" type="button" title="<?php echo mswSpecialChars($msg_script47); ?>" onclick="ms_faqBox('remove',0)">-</button>
		</div>
		<span class="clearfix"></span>
	   </div>
	   <?php
	   }
	   ?>
	  </div>
	  <?php
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>

  </div>
  </form>

</div>