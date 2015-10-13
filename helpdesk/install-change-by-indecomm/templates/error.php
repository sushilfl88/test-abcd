<?php if (!defined('INC')) { die('You do not have permission to view this file!!'); } ?>
<div id="wrapper">

 <div id="left">
 
   <h1 style="margin-bottom:100px"><?php echo $type; ?></h1>
   
 </div>
 
 <div id="right_error">
 
   <?php
   switch ($code) {
     case 'old':
     ?>
     <p style="line-height:20px">The PHP version you have installed on your server is too old and this software cannot run.<br><br>PHP v4.3 or higher is required.<br><br>
     <b>Your Version:</b> <?php echo phpVersion(); ?><br><br>
     Please <a href="http://php.net/downloads.php" onclick="window.open(this);return false">upgrade</a> your installation.
     </p>
     <?php
     break;
     case 'sdata':
     case 'tables':
     ?>
     <p style="line-height:20px">An error occured during the install process and the installer has terminated. This may be due to server issues or in some cases, bugs within MySQL.<br><br>
     The following options are now available:<br><br>
     <b>1</b>: <a href="index.php">Re-run</a> the installer again.<br><br>
     <b>2</b>: Run the database setup manually via the instructions on the <a href="../docs/install_2.html" onclick="window.open(this);return false">installation</a> documentation page.<br><br>
     <b style="text-decoration:underline;font-size:15px">SEND ERROR REPORT</b><br><br>
     I would be grateful if you would send me an error report so that I am aware of this issue and can improve the installer. This is optional, but if you would like to send me the report,
     please do the following:<br><br>
     <b>1</b>: Make sure the "logs" directory in the system is writeable.<br><br>
     <b>2</b>: <a href="index.php">Re-run</a> the installer again to re-produce the error.<br><br>
     <b>2</b>: <a href="mailto:support@maianscriptworld.co.uk?subject=Maian%20Support%20Error%20Report">E-mail</a> me a copy of the '<b>logs/install-error-report.txt</b>' file created.<br><br>
     Thank you very very much and sorry for the inconvenience.<br><br><br>
     </p>
     <?php
     break;
   }
   ?>
 
 </div>
 
 <br class="clear" />

</div>
