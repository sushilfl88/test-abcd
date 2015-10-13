<?php if(!defined('PARENT')) { exit; } 
if (file_exists(PATH.'templates/header-custom.php')) {
  include_once(PATH.'templates/header-custom.php');
} else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
}
?>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=<?php echo $msg_charset; ?>">
<title><?php echo mswCleanData($msg_viewticket55); ?></title>
<link href="stylesheet.css" rel="stylesheet" type="text/css">
</head>

<body>

<div id="bodyOverride">

<div id="windowWrapper">
<?php
echo (isset($_SESSION['previewBoxText']) && $_SESSION['previewBoxText'] ?
$MSPARSER->mswTxtParsingEngine($_SESSION['previewBoxText']) :
$msg_script24);
?>
</div>  

</div>

</body>
</html>
