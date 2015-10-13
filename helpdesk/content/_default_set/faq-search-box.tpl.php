<?php if (!defined('PARENT')) { exit; } ?>
	<form method="get" action="index.php" style="margin:0;padding:0">
	<p style="margin:0;padding:0"><input type="hidden" name="p" value="faq-search"></p>
	<div class="btn-toolbar" id="sbox" style="margin-top:<?php echo (defined('SEARCH_MARGIN') ? SEARCH_MARGIN : '0'); ?>;display:none">
     <div class="input-append">
      <input type="text" class="input-large" name="q" value="<?php echo (isset($_GET['q']) ? urldecode(mswSpecialChars($_GET['q'])) : ''); ?>">
      <button class="btn btn-info" type="submit"><i class="icon-search"></i></button>
     </div>
    </div>
	</form>