<?php if (!defined('INC')) { die('You do not have permission to view this file!!'); } ?>
<div id="wrapper">

 <div id="left">
 
   <h1>STEP<span>&lt; <?php echo $cmd; ?> &gt;</span></h1>
   
   <p class="progress"><span style="width:<?php echo $perc_width; ?>%">&nbsp;</span></p>
   
   <p class="percent"><?php echo $progress; ?>%</p>
 
 </div>
 
 <div id="right">
 
   <p style="line-height:20px">Thank you for trying out <?php echo SCRIPT_NAME; ?>, I hope you like it and enjoy using it.<br><br>
   This installation system will guide you through the install procedure.<br><br>
   To begin, please confirm your database connection information as set in the '<b>control/connect.php</b>' file.
   
   <span class="head">Connection Details</span>
   
   <span class="info"><span class="right"><?php echo DB_HOST; ?></span>Database Host:</span>
   <span class="info"><span class="right"><?php echo DB_NAME; ?></span>Database Name:</span>
   <span class="info"><span class="right"><?php echo DB_USER; ?></span>Database User:</span>
   <span class="info"><span class="right"><?php echo DB_PASS; ?></span>Database Pass:</span>
   <span class="info"><span class="right"><?php echo DB_PREFIX; ?></span>Database Table Prefix:</span>
   
   </p>
   
   <p class="nav">
    <?php
    $e = 0;
    if (DEV_STATUS=='OFF') {
      if (SECRET_KEY=='secret-key-name123' || COOKIE_NAME=='ms-cookie123') {
        ++$e;
      }
    }
    if ($e==0) {
    ?>
    <span><input id="test" onclick="connectionTest()" class="button_con_test" type="button" value="Test Connection" title="Test Connection" /></span>
    <input onclick="window.location='?s=2'" class="button_next" type="button" value="Next &raquo;" title="Next" />
    <?php
    } else {
    ?>
    <span style="color:red;display:block;padding-bottom:50px;width:100%;text-align:center;font-size:14px">[SECURITY ALERT]<br><br><b>SECRET_KEY</b> and <b>COOKIE_NAME</b> MUST be renamed in the connection file.<br><br>Update and <a href="index.php">refresh</a> page.</span>
    <?php
    }
    ?>
   </p>
 
 </div>
 
 <br class="clear" />

</div>