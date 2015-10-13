<?php if (!defined('INC')) { die('You do not have permission to view this file!!'); } ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo $title; ?></title>
<link href="stylesheet.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="templates/js/jquery.js"></script>
<script type="text/javascript" src="templates/js/javascript.js"></script>
<link rel="SHORTCUT ICON" href="favicon.ico">
<?php
if (isset($upgradeOperations)) {
?>
<script type="text/javascript">
//<![CDATA[
upgradeRoutines('start');
//]]>
</script>
<?php
}
?>
</head>

<body>

<div id="logo">

  <p><a href="<?php echo (defined('UPGRADE_ROUTINE') ? 'upgrade.php' : 'index.php'); ?>"><img src="templates/images/logo.png" alt="<?php echo SCRIPT_NAME; ?>: Installation System" title="<?php echo SCRIPT_NAME; ?>: Installation System"></a></p>
  
</div>
