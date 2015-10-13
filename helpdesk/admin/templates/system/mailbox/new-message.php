<?php if (!defined('PARENT')) { exit; } ?>
<div class="content">
        
  <div class="header">
    
	<button class="btn search-bar-button" type="button" onclick="mswToggle('b1','b2','keys')"><i class="icon-search" id="search-icon-button"></i></button>
	<h1 class="page-title"><?php echo $msg_adheader61; ?> (<?php echo $msg_mailbox4; ?>)</h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader61; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_mailbox4; ?></li>
  </ul>
  
  <?php
  // Added..
  if (isset($OK)) {
    echo mswActionCompleted($msg_mailbox9);
  }
  ?>

  <form method="post" action="index.php?p=<?php echo $_GET['p']; ?>&amp;new=1" onsubmit="return ms_fieldCheck('subject,message,staffmailboxes','none')">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  <?php
	  // Search..
	  include(PATH.'templates/system/bootstrap/search-box.php');
	  // Mailbox menu..
	  include(PATH.'templates/system/mailbox/mailbox-nav.php');
	  // Other Users..
	  $q = mysql_query("SELECT `id`,`name`
           FROM `".DB_PREFIX."users`
		   WHERE `id` != '{$MSTEAM->id}'
           ORDER BY `name`
		   ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	  ?>
      <div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="one">
		 <div class="well">
		  <?php
		  if (mysql_num_rows($q)>0) {
		  ?>
		  <label><?php echo $msg_mailbox10; ?></label>
          <input type="text" class="input-xxlarge" maxlength="250" tabindex="<?php echo (++$tabIndex); ?>" name="subject">
		 
		  <label><?php echo $msg_mailbox7; ?></label>
          <textarea name="message" rows="5" cols="20" tabindex="<?php echo (++$tabIndex); ?>"></textarea>
		  
		  <label><?php echo $msg_mailbox11; ?></label>
		  <div class="mailStaff">
		  <?php
		  $q = mysql_query("SELECT `id`,`name`
               FROM `".DB_PREFIX."users`
			   WHERE `id` != '{$MSTEAM->id}'
               ORDER BY `name`
			   ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
		  while ($STAFF = mysql_fetch_object($q)) {
		  ?>
		  <label class="checkbox">
           <input type="checkbox" name="staff[]" value="<?php echo $STAFF->id; ?>" onclick="if(this.checked){clearMailBoxStaffErr()}"> <?php echo mswSpecialChars($STAFF->name); ?>
          </label>
		  <?php
		  }
		  } else {
		  ?>
		  <p class="nothing_to_see"><?php echo $msg_home64; ?></p>
		  <?php
		  }
		  ?>
		  </div>
		 </div>
		</div>
	  </div>
	  <?php
	  if (mysql_num_rows($q)>0) {
	  ?>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <input type="hidden" name="compose" value="yes">
       <button class="btn btn-primary" type="submit"><i class="icon-envelope"></i> <?php echo $msg_mailbox8; ?></button>
      </div>
	  <?php
	  }
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>

  </div>
  </form>
	
</div>