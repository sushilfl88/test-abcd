<?php if (!defined('PARENT')) { exit; } ?>
<div class="content">
  <script type="text/javascript">
  //<![CDATA[
  jQuery(document).ready(function() {
    setTimeout(function() {
     ms_versionCheck();
    },3000);
  });
  //]]>
  </script>
  <div class="header">
    
	<h1 class="page-title"><?php echo $msg_versioncheck; ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_versioncheck; ?></li>
  </ul>
  
  <div class="container-fluid">
    
	<div class="row-fluid">
	
	  <div class="versionSpinner" id="vc-area">
	   <div class="inner">
	    <img src="templates/images/vc-spinner.gif" alt="" title="">
	    <span><?php echo $msg_versioncheck2; ?></span>
	   </div>
	  </div>
	  
	  <?php
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
	
	</div>
  
  </div>

</div>

