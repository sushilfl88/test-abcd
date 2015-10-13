<?php if (!defined('INC')) { die('You do not have permission to view this file!!'); } ?>
<div id="wrapper">
 <script type="text/javascript">
 //<![CDATA[
 jQuery(document).ready(function() {
   jQuery('#email').focus();
 });
 //]]>
 </script>
 
 <div id="left">
 
   <h1>STEP<span>&lt; <?php echo $cmd; ?> &gt;</span></h1>
   
   <p class="progress"><span style="width:<?php echo $perc_width; ?>%">&nbsp;</span></p>
   
   <p class="percent"><?php echo $progress; ?>%</p>
 
 </div>
 
 <div id="right">
 
   <form method="post" action="?s=6" id="form" onsubmit="return checkFormAdmin()">
   <p style="line-height:20px">Information successfully updated.<br><br>
   Now you need to create a main administrator for your helpdesk system. The administrator always has access to all areas of the admin area. Enter username
   and email address. These can be updated later if you prefer:
   
   <span class="head">Create Administrative User</span>
   
   <span class="info" style="text-align:right">
    <span style="float:left">Username/Alias:</span>
    <input type="text" name="user" id="user" class="box" maxlength="100" value="admin" onkeyup="jQuery(this).removeClass('errorbox').addClass('box')" />
   </span>
   
   <span class="info" style="text-align:right">
    <span style="float:left">E-Mail Address:</span>
    <input type="text" name="email" id="email" class="box" value="<?php echo (isset($_SESSION['helpdeskPost']['email']) ? $_SESSION['helpdeskPost']['email'] : ''); ?>" maxlength="250" onkeyup="jQuery(this).removeClass('errorbox').addClass('box')" />
   </span>
   
   <span class="info" style="text-align:right">
    <span style="float:left">Re-enter E-Mail Address:</span>
    <input type="text" name="email2" id="email2" class="box" value="<?php echo (isset($_SESSION['helpdeskPost']['email']) ? $_SESSION['helpdeskPost']['email'] : ''); ?>" maxlength="250" onkeyup="jQuery(this).removeClass('errorbox').addClass('box')" />
   </span>
   
   <span class="info" style="text-align:right">
    <span style="float:left">Password:</span>
    <input type="password" name="pass" id="pass" class="box" maxlength="32" onkeyup="jQuery(this).removeClass('errorbox').addClass('box');jQuery('#pass_2').val(this.value)" />
    <input style="display:none" type="text" name="pass" id="pass_2" class="box" maxlength="32" onkeyup="jQuery(this).removeClass('errorbox').addClass('box');jQuery('#pass').val(this.value)" />
   </span>
   
   <span class="info" style="text-align:right">
    <span style="float:left">Re-enter Password:</span>
    <input type="password" name="pass2" id="pass2" class="box" maxlength="32" onkeyup="jQuery(this).removeClass('errorbox').addClass('box');jQuery('#pass_3').val(this.value)" />
    <input style="display:none" type="text" name="pass2" id="pass_3" class="box" maxlength="32" onkeyup="jQuery(this).removeClass('errorbox').addClass('box');jQuery('#pass2').val(this.value)" />
   </span>
   
   </p>
   
   <p class="nav">
    <span style="float:left" id="showhidepass"><a class="showpass" href="#" onclick="showHidePass('show');return false" title="Click to Make Passwords Visible in Boxes">Show Passwords</a></span>
    <input type="hidden" name="data" value="yes" />
    <input class="button_complete" type="submit" value="Complete Installation &raquo;" title="Complete Installation" />
   </p>
   </form>
 
 </div>
 
 <br class="clear" />

</div>
