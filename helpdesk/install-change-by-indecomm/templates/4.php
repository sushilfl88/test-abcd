<?php if (!defined('INC')) { die('You do not have permission to view this file!!'); } ?>
<div id="wrapper">
 <script type="text/javascript">
 //<![CDATA[
 function warningMSG() {
   var confirmSub = confirm('CONFIRM INSTALLATION\n\nPlease confirm you want to clean install this software?\n\nClick "OK" to proceed..');
   if (confirmSub) { 
     return true;
   } else {
     return false;
   }
 }
 //]]>
 </script>
 <div id="left">
 
   <h1>STEP<span>&lt; <?php echo $cmd; ?> &gt;</span></h1>
   
   <p class="progress"><span style="width:<?php echo $perc_width; ?>%">&nbsp;</span></p>
   
   <p class="percent"><?php echo $progress; ?>%</p>
 
 </div>
 
 <div id="right">
 
   <form method="post" action="?s=4" id="form" onsubmit="return warningMSG()">
   <p style="line-height:20px">Before the installer adds the database tables and information, please specify your MySQL database version and preferred character set for MySQL operations. Setting an incorrect character set can cause issues with foreign characters. If you aren`t sure of this, leave the
   settings as they are.
   
   <span class="head">MySQL</span>
   
   <span class="info"><span class="right"><input type="radio" name="mysql_version" value="MySQL4" <?php echo ((int)$mysqlVer<5 ? ' checked="checked"' : ''); ?> />MySQL4&nbsp;&nbsp;&nbsp;<input type="radio" name="mysql_version" value="MySQL5" <?php echo ((int)$mysqlVer>=5 ? ' checked="checked"' : ''); ?> /> MySQL5</span>Version:</span>
   <span class="info" style="text-align:right">
    <span style="float:left">Character Set:</span>
    <select name="charset">
     <?php 
     foreach ($cSets AS $set) {
     ?>
     <option value="<?php echo $set; ?>"<?php echo mswSelectedItem($set,$defaultSet); ?>><?php echo $set; ?></option>
     <?php 
     } 
     ?>
    </select>
   </span>
   
   </p>
   
   <p class="nav">
    <span><input onclick="window.location='?s=3'" class="button_prev" type="button" value="&laquo; Prev" title="Previous" /></span>
    <input type="hidden" name="tables" value="yes" />
    <input class="button_next_tables" type="submit" value="Install Tables &raquo;" title="Install Tables" />
   </p>
   </form>
   
 </div>
 
 <br class="clear" />

</div>
