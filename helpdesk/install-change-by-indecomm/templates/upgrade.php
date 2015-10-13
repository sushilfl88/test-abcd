<?php if (!defined('INC')) { die('You do not have permission to view this file!!'); } ?>
<div id="wrapper">

 <div id="left">
 
   <h1>VERSION</h1>
   
   <p class="percent" style="padding:0">Latest: <?php echo SCRIPT_VERSION; ?></p>
   <p class="percent" style="margin:0">Installed: <?php echo $SETTINGS->softwareVersion; ?></p>
 
 </div>
 
 <div id="right">
 
   <?php
   if (isset($_GET['upgrade'])) {
   ?>
   <p>Upgrading..this may take several minutes.....<b>DO NOT REFRESH SCREEN</b>..
   
   <span class="head"><?php echo count($ops); ?> Upgrade Operations</span>
   <?php
   for ($i=0; $i<count($ops); $i++) {
   if ($i<1) {
   ?>
   <span class="info"><span class="running" id="op_start">Running..</span><?php echo $ops[$i]; ?></span>
   <?php
   } else {
   ?>
   <span class="info"><span class="pleasewait" id="op_<?php echo $i; ?>">Please wait..</span><?php echo $ops[$i]; ?></span>
   <?php
   }
   }
   
   ?>
   </p>
   <?php
   } else {
   if (SCRIPT_VERSION>$SETTINGS->softwareVersion) {
   ?>
   
   <p>This upgrade routine will update your database to the latest build. Click button below to proceed:
   
   <span class="head">Database to Upgrade</span>
   
   <span class="info"><span class="right"><?php echo DB_NAME; ?></span>Database Name:</span>
   
   </p>
   
   <p class="nav">
    <input onclick="window.location='upgrade.php?upgrade=1'" class="button_upgrade" type="button" value="Upgrade &raquo;" title="Upgrade">
   </p>
   <?php
   } else {
   ?>
   <p style="color:#555;font-size:14px;border-bottom:1px dashed #d7d7d7;background:url(templates/images/ok.png) no-repeat 99% 50%">YOUR INSTALLATION APPEARS TO BE UP TO DATE.</p>
   <p style="font-style:italic;padding:20px 10px 20px 10px">If you need to re-run this upgrade, do the following:<br><br>
   <b>1</b>: Log into your database and access your <?php echo SCRIPT_NAME; ?> '<?php echo DB_PREFIX; ?>settings' database table.<br><br>
   <b>2</b>: Change the 'softwareVersion' value to the previous version or a value lower than <?php echo SCRIPT_VERSION; ?><br><br>
   <b>3</b>: Refresh this page and follow the upgrade instructions.
   </p>
   <?php
   }
   }
   ?>
 
 </div>
 
 <br class="clear">

</div>
