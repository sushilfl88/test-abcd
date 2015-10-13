<?php if (!defined('PARENT')) { die('You do not have permission to view this file!!'); } 
$box = (defined('BB_BOX') ? BB_BOX : 'comments');
?>
<span class="bb_buttons">
  <button class="btn" type="button" onclick="ms_addTags('[b]..[/b]','bold','','<?php echo $box; ?>')"><i class="icon-bold"></i></button>
  <button class="btn" type="button" onclick="ms_addTags('[i]..[/i]','italic','','<?php echo $box; ?>')"><i class="icon-italic"></i></button>
  <button class="btn" type="button" onclick="ms_addTags('[u]..[/u]','underline','','<?php echo $box; ?>')"><i class="icon-underline"></i></button>
  <button class="btn" type="button" onclick="ms_addTags('-','url','<?php echo mswSpecialChars(str_replace("'","\'",$bb_code_buttons[6])); ?>','<?php echo $box; ?>')" title="<?php echo mswSpecialChars($bb_code_buttons[2]); ?>"><i class="icon-link"></i></button>
  <button class="btn" type="button" onclick="ms_addTags('-','email','<?php echo mswSpecialChars(str_replace("'","\'",$bb_code_buttons[5])); ?>','<?php echo $box; ?>')" title="<?php echo mswSpecialChars($bb_code_buttons[1]); ?>"><i class="icon-envelope-alt"></i></button>
  <button class="btn" type="button" onclick="ms_addTags('-','img','<?php echo mswSpecialChars(str_replace("'","\'",$bb_code_buttons[4])); ?>','<?php echo $box; ?>')" title="<?php echo mswSpecialChars($bb_code_buttons[0]); ?>"><i class="icon-picture"></i></button>
  <button class="btn" type="button" onclick="ms_addTags('-','youtube','<?php echo mswSpecialChars(str_replace("'","\'",$bb_code_buttons[7])); ?>','<?php echo $box; ?>')" title="YouTube"><i class="icon-youtube"></i></button>
  <button class="btn" type="button" onclick="ms_addTags('-','vimeo','<?php echo mswSpecialChars(str_replace("'","\'",$bb_code_buttons[8])); ?>','<?php echo $box; ?>')" title="Vimeo"><i class="icon-play"></i></button>
  <button class="btn" type="button" onclick="window.open('index.php?p=bbCode','_blank')"><i class="icon-question"></i></button>
  <?php
  // Load standard responses button?
  if (defined('STANDARD_RESPONSES')) {
  ?>
  <button class="btn btn-info pull-right" type="button" onclick="jQuery('#standardResponses').slideDown('slow')" title="<?php echo mswSpecialChars($msg_viewticket12); ?>"><i class="icon-quote-left"></i>..<i class="icon-quote-right"></i></button>
  <?php
  }
  ?>
  <br class="clearfix">
</span>