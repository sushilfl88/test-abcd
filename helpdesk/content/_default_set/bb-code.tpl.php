<?php if (!defined('PARENT')) { exit; } ?>
<div class="bb_buttons">
  <button class="btn" type="button" onclick="ms_addTags('[b]..[/b]','bold','','comments')"><i class="icon-bold"></i></button>
  <button class="btn" type="button" onclick="ms_addTags('[i]..[/i]','italic','','comments')"><i class="icon-italic"></i></button>
  <button class="btn" type="button" onclick="ms_addTags('[u]..[/u]','underline','','comments')"><i class="icon-underline"></i></button>
  <button class="btn" type="button" title="<?php echo mswSpecialChars($this->TXT[19][2]); ?>" onclick="ms_addTags('-','url','<?php echo mswSpecialChars(str_replace("'","\'",$this->TXT[19][6])); ?>','comments')"><i class="icon-link"></i></button>
  <button class="btn" type="button" title="<?php echo mswSpecialChars($this->TXT[19][1]); ?>" onclick="ms_addTags('-','email','<?php echo mswSpecialChars(str_replace("'","\'",$this->TXT[19][5])); ?>','comments')"><i class="icon-envelope-alt"></i></button>
  <button class="btn" type="button" title="<?php echo mswSpecialChars($this->TXT[19][0]); ?>" onclick="ms_addTags('-','img','<?php echo mswSpecialChars(str_replace("'","\'",$this->TXT[19][4])); ?>','comments')"><i class="icon-picture"></i></button>
  <button class="btn" type="button" title="YouTube" onclick="ms_addTags('-','youtube','<?php echo mswSpecialChars(str_replace("'","\'",$this->TXT[19][7])); ?>','comments')"><i class="icon-youtube"></i></button>
  <button class="btn" type="button" title="Vimeo" onclick="ms_addTags('-','vimeo','<?php echo mswSpecialChars(str_replace("'","\'",$this->TXT[19][8])); ?>','comments')"><i class="icon-play"></i></button>
  <button class="btn" type="button" title="?" onclick="window.open('index.php?bbcode=show','_blank')"><i class="icon-question"></i></button>
</div>