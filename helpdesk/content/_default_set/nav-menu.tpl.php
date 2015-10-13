<?php if (!defined('PARENT')) { exit; } ?>

                <div class="nav-header" data-target="#dashboard-menu"><i class="fam-add"></i><a href="?p=open" title="<?php echo mswSpecialChars($this->TXT[1]); ?>" rel="nofollow"><?php echo $this->TXT[1]; ?></a></div>
                <ul id="dashboard-menu" class="nav nav-list">
				  <?php
				  // If visitor is logged in, show account menu..
				  if ($this->LOGGED_IN=='yes') {
				  ?>
				  <li><a href="?p=profile" title="<?php echo mswSpecialChars($this->TXT[9]); ?>"><?php echo $this->TXT[9]; ?></a></li>
                  <li><a href="?p=history" title="<?php echo mswSpecialChars($this->TXT[3]); ?>"<?php echo ($this->SETTINGS->disputes=='no' ? ' class="mswborder"' : ''); ?>><?php echo $this->TXT[3]; ?></a></li>
                  <?php
				  // Is the dispute system enabled?
				  if ($this->SETTINGS->disputes=='yes') {
				  ?>
				  <li><a href="?p=disputes" class="mswborder" title="<?php echo mswSpecialChars($this->TXT[10]); ?>"><?php echo $this->TXT[10]; ?></a></li>
                  <?php
				  }
				  } else {
				  // Is the ability to open account enabled?
				  if ($this->SETTINGS->createAcc=='yes') {
				  ?>
				  <li><a href="?p=create" title="<?php echo mswSpecialChars($this->TXT[8]); ?>" rel="nofollow"><?php echo $this->TXT[8]; ?></a></li>
				  <?php
				  }
				  ?>
				  <li><a href="?p=login" class="mswborder" title="<?php echo mswSpecialChars($this->TXT[4]); ?>" rel="nofollow"><?php echo $this->TXT[4]; ?></a></li>
				  <?php
				  }
				  ?>
                </ul>
                <?php
				
				// Show FAQ?
				if ($this->SETTINGS->kbase=='yes' && $this->FAQ_LINKS) {
				?>
				<div class="nav-header" data-target="#faq-menu"><i class="fam-folder-magnify"></i><?php echo $this->TXT[0]; ?></div>
                <ul id="faq-menu" class="nav nav-list">
                  <?php
				  // html/faq-menu-link.htm
				  echo $this->FAQ_LINKS;
				  ?>
                </ul>
				<?php
				}
				?>