<?php if (!defined('PARENT')) { exit; } 
$moveToFolders           = array();
$moveToFolders['inbox']  = $msg_mailbox;
$moveToFolders['outbox'] = $msg_mailbox2;
$moveToFolders['bin']    = $msg_mailbox3;
if (!isset($keys)) {
  $keys = '';
}
?>
<ul class="nav nav-tabs">
  <?php
  if (isset($_GET['msg'])) {
  ?>
  <li class="active"><a href="?p=mailbox&amp;msg=<?php echo (int)$_GET['msg']; ?>" class="mailBoxMsg"><i class="icon-search"></i> <?php echo $msg_mailbox7; ?></a></li>
  <?php
  }
  if ($keys) {
  ?>
  <li class="active"><a href="?p=mailbox&amp;keys=<?php echo urlencode(mswSpecialChars($keys)); ?>" class="mailBoxMsg"><i class="icon-search"></i> <?php echo $msg_mailbox32; ?></a></li>
  <?php
  }
  ?>
  <li<?php echo (!isset($_GET['f']) && !isset($_GET['msg']) && !isset($_GET['new']) && $keys=='' ? ' class="active"' : ''); ?>><a href="?p=mailbox"><i class="icon-inbox"></i> <?php echo $msg_mailbox; ?></a></li>
  <li<?php echo (isset($_GET['f']) && $_GET['f']=='outbox' ? ' class="active"' : ''); ?>><a href="?p=mailbox&amp;f=outbox"><i class="icon-signin"></i> <?php echo $msg_mailbox2; ?></a></li>
  <li<?php echo (isset($_GET['f']) && $_GET['f']=='bin' ? ' class="active"' : ''); ?>><a href="?p=mailbox&amp;f=bin"><i class="icon-trash"></i> <?php echo $msg_mailbox3; ?></a></li>
  <?php
  // Are additional folders allowed?
  if ($MSTEAM->mailFolders>0) {
  ?>
  <li class="dropdown">
   <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-folder-open"></i> <?php echo $msg_mailbox5; ?><b class="caret"></b></a>
   <ul class="dropdown-menu">
    <li><a href="?p=mailbox&amp;folders=1"><i class="icon-plus"></i> <?php echo $msg_mailbox6; ?></a></li>
	<?php
	$qF = mysql_query("SELECT `id`,`folder`
          FROM `".DB_PREFIX."mailfolders`
		  WHERE `staffID` = '{$MSTEAM->id}'
          ORDER BY `folder`
		  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	if (mysql_num_rows($qF)>0) {
	  $moveToFolders['-'] = '- - - - - - -';
	}
    while ($FOLDERS = mysql_fetch_object($qF)) {
	$moveToFolders[$FOLDERS->id] = mswCleanData($FOLDERS->folder)
	?>
	<li><a href="?p=mailbox&amp;f=<?php echo $FOLDERS->id; ?>"><i class="icon-folder-close-alt"></i> <?php echo mswCleanData($FOLDERS->folder); ?></a></li>
	<?php
	}
	?>
   </ul>
  </li>
  <?php
  }
  ?>
  <li<?php echo (isset($_GET['new']) ? ' class="active"' : ''); ?>><a href="?p=mailbox&amp;new=1"><i class="icon-pencil"></i> <?php echo $msg_mailbox4; ?></a></li>
</ul>