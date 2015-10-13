<?php if (!defined('PARENT')) { exit; }
	  // Please don`t remove the footer unless you have purchased a licence..
	  // http://www.maiansupport.com/purchase.html
	  if (LICENCE_VER=='unlocked' && $this->SETTINGS->publicFooter) {
	  echo mswCleanData($this->SETTINGS->publicFooter);
	  } else {
	  ?>
	  <footer>
	   <hr>
		<p class="pull-right"><a href="http://www.maianscriptworld.co.uk" title="Maian Script World" onclick="window.open(this);return false">&copy; 2005 - <?php echo date('Y'); ?> David Ian Bennett &amp; Maian Script World</a></p>
		<p>Powered by: <a href="http://www.maiansupport.com" onclick="window.open(this);return false" title="Maian Support">Maian Support</a></p>
	  </footer>
	  <?php
	  }
	  ?>