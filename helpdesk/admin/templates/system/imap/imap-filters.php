<?php if (!defined('PARENT')) { exit; } 
$B8_CFG = mswGetTableData('imap_b8','id','1');
?>
<div class="content">
  <script type="text/javascript">
  //<![CDATA[
  function showResetDays(check) {
    jQuery('input[name="reset_days"]').prop('disabled',(!check ? true : false));
  }
  //]]>
  </script>
  <div class="header">
    
	<h1 class="page-title"><?php echo $msg_adheader62; ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader24; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_adheader62; ?></li>
  </ul>
  
  <?php
  // Update..
  if (isset($OK)) {
    echo mswActionCompleted($msg_imap60);
  }
  ?>

  <form method="post" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('none','none')">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  <ul class="nav nav-tabs">
       <li class="active"><a href="#one" data-toggle="tab"><i class="icon-food"></i> <?php echo $msg_imap43; ?></a></li>
       <li><a href="#two" data-toggle="tab"><i class="icon-bullseye"></i> <?php echo $msg_imap44; ?></a></li>
	   <li><a href="#three" data-toggle="tab"><i class="icon-bolt"></i> <?php echo $msg_imap45; ?></a></li>
	   <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-beaker"></i> <?php echo $msg_imap64; ?><b class="caret"></b></a>
        <ul class="dropdown-menu">
         <li><a href="#five" data-toggle="tab"><i class="icon-plus"></i> <?php echo $msg_imap65; ?></a></li>
         <li><a href="#four" data-toggle="tab"><i class="icon-rotate-left"></i> <?php echo $msg_imap61; ?></a></li>
         <li><a href="#six" data-toggle="tab"><i class="icon-remove"></i> <?php echo $msg_spam6; ?></a></li>
        </ul>
       </li>
	  </ul>
      <div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="one">
		 <div class="well">
		 
		  <label class="checkbox">
           <input type="checkbox" name="learning" value="yes"<?php echo (isset($B8_CFG->learning) && $B8_CFG->learning=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_imap50; ?>
          </label>
		  
		  <label><br><?php echo $msg_imap46; ?></label>
          <input type="text" class="input-small" maxlength="5" name="tokens" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($B8_CFG->tokens) ? mswSpecialChars($B8_CFG->tokens) : '15'); ?>">
          
		  <label><?php echo $msg_imap47; ?></label>
          <input type="text" class="input-small" maxlength="5" name="min_dev" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($B8_CFG->min_dev) ? mswSpecialChars($B8_CFG->min_dev) : '0.5'); ?>">
          
		  <label><?php echo $msg_imap48; ?></label>
          <input type="text" class="input-small" maxlength="5" name="x_constant" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($B8_CFG->x_constant) ? mswSpecialChars($B8_CFG->x_constant) : '0.5'); ?>">
          
		  <label><?php echo $msg_imap49; ?></label>
          <input type="text" class="input-small" maxlength="5" name="s_constant" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($B8_CFG->s_constant) ? mswSpecialChars($B8_CFG->s_constant) : '0.3'); ?>">
          
		 </div>
		</div>
		<div class="tab-pane fade" id="two">
		 <div class="well">
		 
		  <label class="checkbox">
           <input type="checkbox" name="num_parse" value="yes"<?php echo (isset($B8_CFG->num_parse) && $B8_CFG->num_parse=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_imap53; ?>
          </label>
		  
		  <label class="checkbox">
           <input type="checkbox" name="uri_parse" value="yes"<?php echo (isset($B8_CFG->uri_parse) && $B8_CFG->uri_parse=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_imap54; ?>
          </label>
		  
		  <label class="checkbox">
           <input type="checkbox" name="html_parse" value="yes"<?php echo (isset($B8_CFG->html_parse) && $B8_CFG->html_parse=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_imap55; ?>
          </label>
		  
		  <label><br><?php echo $msg_imap51; ?></label>
          <input type="text" class="input-small" maxlength="5" name="min_size" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($B8_CFG->min_size) ? mswSpecialChars($B8_CFG->min_size) : '3'); ?>">
          
		  <label><?php echo $msg_imap52; ?></label>
          <input type="text" class="input-small" maxlength="5" name="max_size" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($B8_CFG->max_size) ? mswSpecialChars($B8_CFG->max_size) : '30'); ?>">
          
		 </div> 
		</div>
		<div class="tab-pane fade" id="three">
		 <div class="well">
		 
		  <label class="checkbox">
           <input<?php echo (!function_exists('mb_substr') ? ' disabled="disabled" ' : ' '); ?>type="checkbox" name="multibyte" value="yes"<?php echo (isset($B8_CFG->multibyte) && $B8_CFG->multibyte=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_imap56.(!function_exists('mb_substr') ? $msg_imap58 : ''); ?>
          </label>
		  
		  <?php
		  // Show encoding sets..
		  if (function_exists('mb_list_encodings')) {
		  ?>
		  <label><br><?php echo $msg_imap57; ?></label>
		  <select name="encoder">
		   <?php
		   foreach (mb_list_encodings() AS $enc) {
		   ?>
		   <option value="<?php echo $enc; ?>"<?php echo (isset($B8_CFG->encoder) && $B8_CFG->encoder==$enc ? ' selected="selected"' : ''); ?>><?php echo $enc; ?></option>
		   <?php
		   }
		   ?>
		  </select>
		  <?php
		  }
		  ?>
		 
		 </div> 
		</div>
		<div class="tab-pane fade" id="four">
		 <div class="well">
		 
		  <label class="checkbox">
           <input type="checkbox" name="reset" value="yes" onclick="showResetDays(this.checked)"> <?php echo $msg_imap62; ?>
          </label>
		  
		  <label><br><?php echo $msg_imap69; ?></label>
          <input type="text" class="input-small" maxlength="3" name="reset_days" tabindex="<?php echo (++$tabIndex); ?>" value="" disabled="disabled">
          
		 </div> 
		</div>
		<div class="tab-pane fade" id="five">
		 <div class="well">
		 
		  <label><?php echo $msg_imap66; ?></label>
          <textarea name="add-to" rows="5" cols="20" tabindex="<?php echo (++$tabIndex); ?>"></textarea>
        
		  <select name="classify">
		   <option value="spam" selected="selected"><?php echo $msg_imap67; ?></option>
		   <option value="ham"><?php echo $msg_imap68; ?></option>
		  </select>
		  
		 </div> 
		</div>
		<div class="tab-pane fade" id="six">
		 <div class="well">
		 
		  <label><?php echo $msg_spam7; ?></label>
          <textarea name="skipFilters" rows="5" cols="20" tabindex="<?php echo (++$tabIndex); ?>"><?php echo (isset($B8_CFG->skipFilters) ? mswSpecialChars($B8_CFG->skipFilters) : ''); ?></textarea>
        
		 </div> 
		</div>
	  </div>
	  <div class="pull-right">
	   <p style="font-size:11px;font-style:italic;text-align:right"><?php echo $msg_script57; ?>: <a href="../docs/imapfilter2.html" onclick="window.open(this);return false">b8</a>. <?php echo $msg_script58; ?> Tobias Leupold.</p>
	  </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <input type="hidden" name="process" value="1">
       <button class="btn btn-primary" type="submit"><i class="icon-ok"></i> <?php echo $msg_imap59; ?></button>
      </div>
	  <?php
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>

  </div>
  </form>
	
</div>