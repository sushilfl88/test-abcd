<?php if (!defined('INC')) { die('You do not have permission to view this file!!'); } ?>
<div id="wrapper">

 <div id="left">
 
   <h1>STEP<span>&lt; <?php echo $cmd; ?> &gt;</span></h1>
   
   <p class="progress"><span style="width:<?php echo $perc_width; ?>%">&nbsp;</span></p>
   
   <p class="percent"><?php echo $progress; ?>%</p>
 
 </div>
 
 <div id="right">
 
   <p style="line-height:20px">Looking good so far. The next check we need to do is for some required modules/functions. Some of them must be installed to perform certain operations, but the system will not fail if these
   are not available as the required operations will either have legacy code or just be disabled.
   
   <span class="head">Module Check</span>
   
   <span class="info"><span class="right"><img src="templates/images/<?php echo (function_exists('curl_init') ? 'ok' : 'error'); ?>.png" alt="" title="" /></span>CURL <span class="italic">(Version check and Cron Jobs Overrides)</span>:</span>
   <span class="info"><span class="right"><img src="templates/images/<?php echo (function_exists('imap_open') ? 'ok' : 'error'); ?>.png" alt="" title="" /></span>IMAP <span class="italic">(For opening tickets via email)</span>:</span>
   <span class="info"><span class="right"><img src="templates/images/<?php echo (function_exists('simplexml_load_string') ? 'ok' : 'error'); ?>.png" alt="" title="" /></span>SIMPLE XML <span class="italic">(For XML version of API)</span>:</span>
   <span class="info"><span class="right"><img src="templates/images/<?php echo (function_exists('json_encode') ? 'ok' : 'error'); ?>.png" alt="" title="" /></span>JSON <span class="italic">(For JSON version of API &amp; Ajax responses)</span>:</span>
   
   </p>
   
   <?php
   if (function_exists('json_encode')) {
   ?>
   <p class="nav">
    <span><input onclick="window.location='?s=2'" class="button_prev" type="button" value="&laquo; Prev" title="Previous" /></span>
    <input onclick="window.location='?s=4'" class="button_next" type="button" value="Next &raquo;" title="Next" />
   </p>
   <?php
   } else {
   ?>
   <span style="color:red;display:block;padding-bottom:50px;width:100%;text-align:center;font-size:14px">[ ERROR ] <a href="http://www.php.net/json_encode" onclick="window.open(this);return false">JSON functions</a> MUST be installed. Enable on server before attempting install.</span>
    <?php
   }
   ?>
 
 </div>
 
 <br class="clear" />

</div>
