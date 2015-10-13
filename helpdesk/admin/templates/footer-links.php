<?php if (!defined('PARENT')) { exit; } ?>
      <?php
	  // Please don`t remove the footer unless you have purchased a licence..
	  // http://www.maiansupport.com/purchase.html
	  if (LICENCE_VER=='unlocked' && $SETTINGS->adminFooter) {
	  echo mswCleanData($SETTINGS->adminFooter);
	  } else {
	  ?>
	  <footer>
	    <hr>
		  <p class="pull-right"><a href="http://www.indecomm.net" title="Indecomm Script World" onclick="window.open(this);return false">&copy; 2005 - <?php echo date('Y'); ?> Indecomm Global Services</a></p>
		  <p>Powered by: <a href="http://www.indecomm.net" onclick="window.open(this);return false" title="Indecomm Support">Indecomm Global Services</a></p>
	  </footer>
	  <?php
	  }
	  ?>