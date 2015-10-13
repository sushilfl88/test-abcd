<?php if (!defined('INC')) { die('You do not have permission to view this file!!'); } ?>
<div id="wrapper">
 <script type="text/javascript">
 //<![CDATA[
 jQuery(document).ready(function() {
   jQuery('#website').focus();
 });
 //]]>
 </script>
 
 <div id="left">
 
   <h1>STEP<span>&lt; <?php echo $cmd; ?> &gt;</span></h1>
   
   <p class="progress"><span style="width:<?php echo $perc_width; ?>%">&nbsp;</span></p>
   
   <p class="percent"><?php echo $progress; ?>%</p>
 
 </div>
 
 <div id="right">
 
   <form method="post" action="?s=5" id="form" onsubmit="return checkForm()">
   <p>Tables successfully created, so all looking good at the moment.<br><br>
   Now enter your new helpdesk name and 'from' email address for tickets. These can be changed later: 
   
   <span class="head">Information</span>
   
   <span class="info" style="text-align:right">
    <span style="float:left">Helpdesk Name:</span>
    <input type="text" name="website" id="website" class="box" maxlength="150" onkeyup="jQuery(this).removeClass('errorbox').addClass('box')" />
   </span>
   
   <span class="info" style="text-align:right">
    <span style="float:left">E-Mail Address:</span>
    <input type="text" name="email" id="email" class="box" maxlength="250" onkeyup="jQuery(this).removeClass('errorbox').addClass('box')" />
   </span>
   
   <span class="info" style="text-align:right">
    <span style="float:left">Default System Timezone:</span>
    <select name="timezone">
    <?php
    // TIMEZONES..
    foreach ($timezones AS $k => $v) {
    ?>
    <option value="<?php echo $k; ?>"<?php echo mswSelectedItem($k,'Europe/London'); ?>><?php echo $v; ?></option>
    <?php
    }
    ?>
    </select>
   </span>
   
   </p>
   
   <p class="nav">
    <span><input onclick="window.location='?s=4'" class="button_prev" type="button" value="&laquo; Prev" title="Previous" /></span>
    <input type="hidden" name="hdeskInfo" value="yes" />
    <input class="button_next_update" type="submit" value="Update &amp; Continue &raquo;" title="Update &amp; Continue" />
   </p>
   </form>
   
 </div>
 
 <br class="clear" />

</div>
