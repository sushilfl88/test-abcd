<?php if (!defined('PARENT')) { exit; } 
// ADD OR REMOVE QUICK LINKS
// To add a different quick links file for each user append -ID to the end of a new file.
// The file is a copy of this file with the team ID appended (See admin for ID numbers).
// Example: quick-links-16.php (would load ONLY for team member 16)
?>

<i class="icon-caret-right"></i> <a href="?p=settings">Helpdesk Settings</a><br>
<i class="icon-caret-right"></i> <a href="http://www.<?php echo SCRIPT_URL; ?>" onclick="window.open(this);return false"><?php echo SCRIPT_NAME; ?> Website</a><br>
<i class="icon-caret-right"></i> <a href="http://www.maianscriptworld.co.uk" onclick="window.open(this);return false">MSWorld Website</a><br>
<i class="icon-caret-right"></i> <a href="../index.php" onclick="window.open(this);return false">Your Helpdesk</a><br>
<i class="icon-caret-right"></i> <a href="../docs/" onclick="window.open(this);return false">Your Helpdesk Docs</a><br>
<span class="quickText">To Add/Remove see:<br>admin/templates/system/home/quick-links.php</span>