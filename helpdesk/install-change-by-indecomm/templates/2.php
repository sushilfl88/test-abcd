<?php if (!defined('INC')) { die('You do not have permission to view this file!!'); } ?>
<div id="wrapper">

 <div id="left">
 
   <h1>STEP<span>&lt; <?php echo $cmd; ?> &gt;</span></h1>
   
   <p class="progress"><span style="width:<?php echo $perc_width; ?>%">&nbsp;</span></p>
   
   <p class="percent"><?php echo $progress; ?>%</p>
 
 </div>
 
 <div id="right">
 
   <p style="line-height:20px">Thank you. Ok, next lets check some directory permissions. Read/write permissions are required on the directories shown below. If you see a cross,
   update <a href="http://en.wikipedia.org/wiki/File_system_permissions" onclick="window.open(this);return false">permissions</a> via FTP or your web control panel.
   
   <span class="head">Permissions</span>
   <?php
   
   $e = 0;
   
   $dirs = array(
   'admin/export/',
   'backups/',
   'logs/',
   'content/attachments/',
   'content/attachments-faq/'
   );
   
   foreach ($dirs AS $d) {
   $perms = (is_dir(REL_PATH.$d) && is_writeable(REL_PATH.$d) ? 'yes' : 'no'); 
   if ($perms=='no') {
     ++$e;
   }
   ?>
   <span class="info"><span class="right"><img src="templates/images/<?php echo ($perms=='yes' ? 'ok' : 'error'); ?>.png" alt="" title="" /></span><?php echo $d;?></span>
   <?php
   }
   ?>
   
   </p>
   
   <?php
   if ($e==0) {
   ?>
   <p class="nav">
    <span><input onclick="window.location='?s=1'" class="button_prev" type="button" value="&laquo; Prev" title="Previous" /></span>
    <input onclick="window.location='?s=3'" class="button_next" type="button" value="Next &raquo;" title="Next" />
   </p>
   <?php
   } else {
   ?>
   <p class="warning_msg">Please fix the <b><?php echo ($e==1 ? '1 error' : $e.' errors'); ?></b> above and then refresh page. If you aren`t sure about permissions, click <a href="http://www.google.co.uk/search?hl=en&amp;q=ftp+permissions" onclick="window.open(this);return false">here</a>.</p>
   <?php
   }
   ?>
 
 </div>
 
 <br class="clear" />

</div>
