<?php if (!defined('INC')) { die('You do not have permission to view this file!!'); } ?>
<div id="wrapper">

 <div id="left">
 
   <h1 style="margin-bottom:30px">COMPLETED</h1>
   
   <p class="progress"><span style="width:<?php echo $perc_width; ?>%">&nbsp;</span></p>
   
   <p class="percent"><?php echo $progress; ?>%</p>
 
 </div>
 
 <div id="right">
 
   <p>All done! The installer ran with no issues and <?php echo SCRIPT_NAME; ?> is ready to go.<br><br>
   
   Lets look at fixing a major security issue first:<br><br>
   
   <b style="color:red;font-size:14px;text-transform:uppercase">DELETE or rename the 'install' folder in your helpdesk directory NOW!!</b><br><br>
   
   Once you have completed this task, here are a few things worth considering:<br><br>
   
   <b>1</b>: Read the rest of the instructions on the <a href="../docs/install_2.html" onclick="window.open(this);return false">installation</a> docs page thoroughly.<br><br>
   <b>2</b>: Please read the <a href="../docs/index.html" onclick="window.open(this);return false">docs</a> carefully where applicable. Click the "Help" link in admin to load help pages.<br><br>
   <b>4</b>: If you have issues, see the '<a href="../docs/support.html" onclick="window.open(this);return false">Support Options</a>'. As with any new software, please be patient with it.<br><br>
   <b>5</b>: If available, check the <a href="http://www.<?php echo SCRIPT_URL; ?>/video-tutorials.html" onclick="window.open(this);return false">video tutorials</a> on the <a href="http://www.<?php echo SCRIPT_URL; ?>" onclick="window.open(this);return false"><?php echo SCRIPT_NAME; ?></a> website for assistance.<br><br>
   <b>6</b>: The helpdesk template files are <b>NOT</b> encoded. Check out the <a href="../docs/install_5.html" onclick="window.open(this);return false">template integration</a> section in the docs.<br><br>
   <b>7</b>: If you like this software, a one time payment for the <a href="http://www.<?php echo SCRIPT_URL; ?>/purchase.html" onclick="window.open(this);return false">commercial version</a> offers many benefits.<br><br>
   
   I really hope you like <?php echo SCRIPT_NAME; ?> and thank you very much for trying it out.
   
   <?php
   // Show details..
   if (isset($_SESSION['helpdeskPost']['email'],$_SESSION['helpdeskPost']['pass'])) {
   ?>
   <span class="viewlogin">
   <b>ADMINISTRATION LOGIN</b>:<br><br>
   
   Login E-Mail: <?php echo $_SESSION['helpdeskPost']['email']; ?><br>
   Login Password: <?php echo $_SESSION['helpdeskPost']['pass']; ?>
   </span>
   <?php
   }
   ?>
   </p>
   
   <p class="nav">
    <span><input onclick="window.location='../index.php'" class="button_view_helpdesk" type="button" value="&laquo; View Help Desk" title="View Help Desk"></span>
    <input onclick="window.location='../admin/index.php'" class="button_view_admin" type="button" value="View Administration &raquo;" title="View Administration" />
   </p>
 
 </div>
 
 <br class="clear" />

</div>
