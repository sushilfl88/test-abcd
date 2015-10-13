<?php if (!defined('PARENT')) { exit; } ?>
<div class="content">
  <?php
  // Show form errors..
  // Must NOT be removed..
  if (isset($_POST['process']) && count($this->EFIELDS)>0) {
  ?>
  <script type="text/javascript">
  //<![CDATA[
  jQuery(document).ready(function() {
    msErrDisplayMechanism('<?php echo implode(',',$this->EFIELDS); ?>');
  });
  //]]>
  </script>
  <?php
  }
  ?>
  <div class="header">
    
	<?php
	// If ticket status isn`t closed, we can show the reply and close links..
	// If the ticket is awaiting operator assignment, don`t show..
	if ($this->TICKET->ticketStatus=='open' && $this->TICKET->assignedto!='waiting') {
	?>
	<i class="icon-pencil"></i> <a href="#" onclick="scrollToArea('replyArea');return false" title="<?php echo mswSpecialChars($this->TXT[7]); ?>"><?php echo $this->TXT[7]; ?></a>&nbsp;&nbsp;&nbsp;
    <i class="icon-off"></i> <a href="?t=<?php echo $_GET['t']; ?>&amp;cl=yes" title="<?php echo mswSpecialChars($this->TXT[23]); ?>"><?php echo $this->TXT[23]; ?></a>
    <?php
	}
	// If ticket can be re-opened, show open link..
	if ($this->TICKET->ticketStatus=='close') {
	?>
	<i class="icon-unlock"></i> <a class="open" href="?t=<?php echo $_GET['t']; ?>&amp;lk=yes" title="<?php echo mswSpecialChars($this->TXT[11]); ?>"><?php echo $this->TXT[11]; ?></a>
    <?php
	}
	?>
	<h1 class="page-title"><?php echo $this->TXT[0]; ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
  
  <ul class="breadcrumb">
    <li><a href="index.php"><?php echo $this->TXT[2]; ?></a> <span class="divider">/</span></li>
	<li><a href="index.php?p=history"><?php echo $this->TXT[1]; ?></a> <span class="divider">/</span></li>
    <li class="active"><?php echo $this->TXT[3]; ?></li>
  </ul>
  
  <?php
  // SYSTEM MESSAGE
  // html/action-message.htm
  // html/action-message-warning.htm
  if (count($this->EFIELDS)>0 && $this->SYSTEM_MESSAGE) {
    echo mswActionMessageWarning($this->SYSTEM_MESSAGE);
  } else {
    if ($this->SYSTEM_MESSAGE) {
      echo mswActionMessage($this->SYSTEM_MESSAGE);
	}
  }
  ?>
  
  <form method="post" action="?t=<?php echo $_GET['t']; ?>" enctype="multipart/form-data" id="formfield">
  <div class="container-fluid">
    
	<div class="row-fluid">
	 
	 <div class="block mainticket">
	   <p class="block-heading"><?php echo strtoupper(mswSpecialChars($this->TICKET->subject)); ?> <span class="label label-info"><?php echo mswSpecialChars($this->USER_DATA->name); ?> &#8226; <?php echo $this->TXT[4]; ?> &#8226; <?php echo $this->TXT[5].' &#8226; '.$this->TXT[6]; ?></span></p>
	   <div class="block-body">
	    <i class="icon-quote-left"></i>
	    <?php
		// MESSAGE..
	    echo $this->COMMENTS;
	    ?>
		<i class="icon-quote-right"></i>
		<?php
	    // CUSTOM FIELDS
	    // html/ticket-custom-fields.htm
		// html/custom-fields/*
		if ($this->CUSTOM_FIELD_DATA) {
		?>
		<div class="customFieldsTicket">
		<?php
	    echo $this->CUSTOM_FIELD_DATA;
		?>
		</div>
		<?php
		}
	    ?>
		
	    <div class="ticketInfoBox">
	     <div class="info">
	     	
		 <?php 
		 
		 // ATTACHMENTS
		 // html/ticket-attachment.htm
		 if ($this->ATTACHMENTS) {
		 ?>
		 <p class="pull-left attachments">
		 <?php
		 echo $this->ATTACHMENTS; 
		 ?>
		 </p>
		 <?php
		 }
		 
		 // Department name..
		 echo $this->TXT[8]; ?> &#8226; 
		 <?php 
		 // Email of person who opened ticket..
		 echo mswCleanData($this->USER_DATA->email); ?> &#8226; 
		 <?php 
		 // IP address(es)..
		 echo ($this->TICKET->ipAddresses ? mswCleanData($this->TICKET->ipAddresses) : 'N/A'); 
		 ?>
		 </div>
		 <span class="clearfix"></span>
	    </div>
	   </div>
	 </div>
	 <?php
	 
	 // TICKET REPLIES
	 // html/ticket-reply.htm
	 // html/ticket-message.htm
	 // html/ticket-attachment.htm
	 // html/ticket-custom-fields.htm
	 if ($this->TICKET->assignedto!='waiting') {
	   echo $this->TICKET_REPLIES;
	 }
	 
	 // REPLY AREA
	 if ($this->TICKET->ticketStatus=='open' && $this->TICKET->assignedto!='waiting') {
	 ?>
	 
	 <div class="block" id="replyArea">
	   
	   <p class="block-heading"><?php echo $this->TXT[7]; ?></p>
	    
		<div class="block-body">
	   
	    <?php
	    // CUSTOM FIELDS
		// html/custom-fields/*
	    if ($this->ENTRY_CUSTOM_FIELDS) {
	    ?>
	    <div class="customFields" id="customFieldsArea">
        <?php
	    echo $this->ENTRY_CUSTOM_FIELDS;
        ?>
        </div>
	    <?php
	    }
	    ?>
	   
	    <div class="row-fluid<?php echo ($this->ENTRY_CUSTOM_FIELDS ? ' replyArea' : ''); ?>">
	    
	     <div class="span8">
		  <?php
		  // BBCODE
		  if ($this->SETTINGS->enableBBCode=='yes') {
		   include(PATH.'content/'.MS_TEMPLATE_SET.'/bb-code.tpl.php');
		  }
		  ?>
		  <textarea name="comments" rows="15" cols="40" tabindex="50" id="comments" onkeyup="msErrClear('comments')"><?php echo $this->POST['comments']; ?></textarea>
		  <?php
		  // PREVIEW DIV - DO NOT REMOVE
		  ?>
		  <div id="previewArea" class="previewArea prevTickets" onclick="ms_closePreview('comments','previewArea')"></div>
		  
		  <label class="checkbox">
		   <input type="checkbox" name="close" value="1"> <?php echo $this->TXT[14]; ?>
		  </label>
		 </div>
		
		 <div class="span4">
		 <?php
		 // ENTRY ATTACHMENTS
		 if ($this->SETTINGS->attachment=='yes') {
		 ?>
		 <label><?php echo $this->TXT[15]; ?></label>
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
          <button class="btn" type="button" id="addbox" title="<?php echo mswSpecialChars($this->TXT[16]); ?>" onclick="ms_attachBox('add','<?php echo $this->SETTINGS->attachboxes; ?>')">+</button>
          <button class="btn" type="button" title="<?php echo mswSpecialChars($this->TXT[17]); ?>" onclick="ms_attachBox('remove')">-</button>
         </p>
		 <?php
		 // ATTACHMENT RESTRICTION
		 // html/ticket-attachment-restrictions.htm
		 if ($this->TXT[18]) {
		 ?>
		 <p class="attachRestrictions">
		 <?php echo $this->TXT[18]; ?>
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
	  <button class="btn btn-primary" type="button" onclick="addReply()"><i class="icon-plus"></i> <?php echo $this->TXT[7]; ?></button>
	  <button class="btn" type="button" onclick="ms_textPreview('comments','previewArea')" id="prev"><i class="icon-search"></i> <?php echo $this->TXT[12]; ?></button>
	  <button class="btn" type="button" onclick="ms_closePreview('comments','previewArea')" style="display:none" id="clse"><i class="icon-remove"></i> <?php echo $this->TXT[13]; ?></button>
     </div>
	 
	 <?php
	 // Hidden elements that hold text for errors..
	 ?>
     <p id="err1" style="display:none"><?php echo $this->TXT[22]; ?></p>
	 <p id="err2" style="display:none"><?php echo $this->TXT[24]; ?></p>
	 <?php
	 
	 // Ticket not open..
	 } else {
	   ?>
	   <div class="ticketMessage">
	   <?php
	   if ($this->TICKET->assignedto=='waiting') {
	     $this->TICKET->ticketStatus = 'waiting';
	   }
	   // Show message based on closed status..
	   switch ($this->TICKET->ticketStatus) {
	     // Just closed, can be re-opened..
	     case 'close':
		 ?>
		 <p><?php echo $this->TXT[9]; ?></p>
		 <?php
		 break;
		 // Closed and locked, cannot be re-opened..
		 case 'closed':
		 ?>
		 <p><?php echo $this->TXT[10]; ?></p>
		 <?php
		 break;
		 // Waiting operator assignment..
		 case 'waiting':
		 ?>
		 <p><?php echo $this->TXT[20]; ?></p>
		 <?php
		 break;
	   }
	   ?>
	   </div>
	   <?php
	 }
	 
	 // Footer..
	 include(PATH.'content/'.MS_TEMPLATE_SET.'/footer-right.tpl.php');
	 ?>
	</div>
  
  </div>
  </form>

</div>