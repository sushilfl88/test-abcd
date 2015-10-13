<?php if (!defined('PARENT')) { exit; } ?>
<div class="content">
  <script type="text/javascript">
  //<![CDATA[
  jQuery(document).ready(function() {
    if (typeof name!=undefined && jQuery('input[name="name"]').val()=='') {
      jQuery('input[name="name"]').focus();
    } else {
      if (typeof subject!=undefined && jQuery('input[name="subject"]').val()=='') {
        jQuery('input[name="subject"]').focus();
      }
    }
	<?php
	// If form is refreshed, show custom fields area if they exist..
	// Also, make any errors visible..
	// Must NOT be removed..
	if (isset($_POST['process']) && $this->CFIELDS) {
	?>
	jQuery('.customFieldsCreate').show();
	<?php
	}
	// Have we got any errors..
	if (count($this->EFIELDS)>0) {
	?>
	msErrDisplayMechanism('<?php echo implode(',',$this->EFIELDS); ?>');
	<?php
	}
	?>
  });
  //]]>
  </script>

  <div class="header">
    
	<h1 class="page-title"><?php echo $this->TXT[0]; ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
  
  <ul class="breadcrumb">
    <li class="active"><?php echo $this->TXT[1]; ?></li>
  </ul>
  
  <?php
  // Error message..
  if (count($this->EFIELDS)>0 && $this->SYSTEM_MESSAGE) {
    echo mswActionMessageWarning($this->SYSTEM_MESSAGE);
  }
  ?>
  
  <form method="post" action="?p=open" enctype="multipart/form-data" id="formfield">
  <div class="container-fluid">
    
	<div class="row-fluid">
	 <div class="block">
	   <div class="block-body">
	     
	     <?php
		 // If the person is logged in, we already have name and email, so we can hide these fields..
		 if ($this->LOGGED_IN=='no') {
		 ?>
		 <label><?php echo $this->TXT[2]; ?></label>
         <input type="text" class="input-xxlarge" name="name" tabindex="1" maxlength="250" value="<?php echo $this->POST['name']; ?>" onkeyup="msErrClear('name')">
         
		 <label><?php echo $this->TXT[3]; ?></label>
         <input type="text" class="input-xxlarge" name="email" tabindex="2" maxlength="250" value="<?php echo $this->POST['email']; ?>" onkeyup="msErrClear('email')">
         <?php
		 }
		 ?>
		 
		 <label><?php echo $this->TXT[4]; ?></label>
         <input type="text" class="input-xxlarge" name="subject" tabindex="3" maxlength="250" value="<?php echo $this->POST['subject']; ?>" onkeyup="msErrClear('subject')">
         
		 <label id="dep_label"><?php echo $this->TXT[5]; ?></label>
         <select name="dept" tabindex="4" onchange="msErrClear('dept');deptLoader(this.value,'no')">
         <option value="0">- - - -</option>
         <?php 
		 // DEPARTMENTS
		 // html/ticket-department.htm
		 echo $this->DEPARTMENTS; 
		 ?>
         </select>
         
		 <label><?php echo $this->TXT[6]; ?></label>
         <select name="priority" tabindex="5" onchange="msErrClear('priority')">
         <option value="0">- - - -</option>
		 <?php
         foreach ($this->PRIORITY_LEVELS AS $k => $v) {
         ?>
         <option value="<?php echo $k; ?>"<?php echo mswSelectedItem($this->POST['priority'],$k); ?>><?php echo $v; ?></option>
         <?php
         }
         ?>
         </select>
         
		 <div class="customFieldsCreate" id="customFieldsArea">
		 <?php 
         // CUSTOM FIELDS
         // html/custom-fields/*
		 // CFIELDS displays custom fields only on page refresh, so when errors may be present on form submission..
		 echo $this->CFIELDS;
         ?>
		 </div>
         
		 <div class="row-fluid">
		 
		  <div class="span8">
		  
		  <label><?php echo $this->TXT[7]; ?></label>
		  <?php
		  // BBCODE
		  if ($this->SETTINGS->enableBBCode=='yes') {
		   include(PATH.'content/'.MS_TEMPLATE_SET.'/bb-code.tpl.php');
		  }
		  ?>
          <textarea rows="12" cols="40" name="comments" tabindex="50" id="comments" onkeyup="msErrClear('comments')"><?php echo $this->POST['comments']; ?></textarea>
          
		  <?php
		  // PREVIEW DIV - DO NOT REMOVE
		  ?>
		  <div id="previewArea" class="previewArea prevTickets" onclick="ms_closePreview('comments','previewArea')"></div>
		  
		  <?php 
          // SPAM PREVENTION IF RECAPTCHA ENABLED
          // html/recaptcha.htm
          echo $this->RECAPTCHA; 
          ?>
		  
		  </div>
		  
		  <div class="span4">
		  <?php
		  // ENTRY ATTACHMENTS
		  if ($this->SETTINGS->attachment=='yes') {
		  ?>
		  <label><?php echo $this->TXT[8]; ?></label>
		  <div style="line-height:35px">
		   <div class="attachBox">
		   <?php
		   // Is there a max file size restriction..
		   if ($this->SETTINGS->maxsize>0) {
		   ?>
		   <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $this->SETTINGS->maxsize; ?>">
		   <?php
		   }
		   ?>
           <input type="file" class="input-small" name="attachment[]" onclick="msErrClear('attach')">
		   </div>
		  </div>
          <p class="attachlinks">
           <button class="btn" type="button" title="<?php echo mswSpecialChars($this->TXT[9]); ?>" onclick="ms_attachBox('add','<?php echo $this->SETTINGS->attachboxes; ?>')">+</button>
           <button class="btn" type="button" title="<?php echo mswSpecialChars($this->TXT[10]); ?>" onclick="ms_attachBox('remove')">-</button>
          </p>
		  <?php
		  // ATTACHMENT RESTRICTION
		  // html/ticket-attachment-restrictions.htm
		  if ($this->TXT[11]) {
		  ?>
		  <p class="attachRestrictions">
		  <?php echo $this->TXT[11]; ?>
		  </p>
          <?php
		  }
		 
		  }
		  ?>
		  </div>
		  
		 </div>
       
	   </div>
	 </div>  
	 
	 <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	  <input type="hidden" name="process" value="yes">
	  <button class="btn btn-primary" type="button" tabindex="51" onclick="addTicket()"><i class="icon-plus"></i> <?php echo $this->TXT[12]; ?></button>
	  <button class="btn" type="button" onclick="ms_textPreview('comments','previewArea')" id="prev"><i class="icon-search"></i> <?php echo $this->TXT[13]; ?></button>
	  <button class="btn" type="button" onclick="ms_closePreview('comments','previewArea')" style="display:none" id="clse"><i class="icon-remove"></i> <?php echo $this->TXT[14]; ?></button>
     </div>
	 <?php
	 // Footer..
	 include(PATH.'content/'.MS_TEMPLATE_SET.'/footer-right.tpl.php');
	 ?>
	</div>
	<?php
	// Hidden elements that hold text for errors..
	?>
    <p id="err1" style="display:none"><?php echo $this->TXT[17]; ?></p>
	<p id="err2" style="display:none"><?php echo $this->TXT[18]; ?></p>
	<p id="err3" style="display:none"><?php echo $this->TXT[20]; ?></p>
  </div>
  </form>

</div>