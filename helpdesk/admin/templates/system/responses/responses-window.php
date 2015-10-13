<?php if(!defined('PARENT')) { exit; }
$_GET['view']  = (int)$_GET['view'];
$SR            = mswGetTableData('responses','id',$_GET['view']);
if (!isset($SR->id)) {
  exit;
}
?>
<!DOCTYPE html>
<html lang="<?php echo (isset($html_lang) ? $html_lang : 'en'); ?>" dir="<?php echo $lang_dir; ?>">
  <head>
    <meta charset="<?php echo $msg_charset; ?>">
    <title></title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="templates/css/bootstrap.css" type="text/css">
	<link rel="stylesheet" href="templates/css/bootstrap-responsive.css" type="text/css">
    <link rel="stylesheet" href="templates/css/theme.css" type="text/css">
    <link rel="stylesheet" href="templates/css/font-awesome.css">
	<script src="templates/js/jquery.js" type="text/javascript"></script>
	<link rel="stylesheet" href="templates/css/ms.css" type="text/css">
    <script src="templates/js/ms-global.js" type="text/javascript"></script>
	<script src="templates/js/ms-ops.js" type="text/javascript"></script>
	<link rel="stylesheet" href="templates/css/bbcode.css"  type="text/css">
	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="templates/js/html5.js"></script>
    <![endif]-->

    <link rel="SHORTCUT ICON" href="favicon.ico">
  </head>

  <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
  <!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
  <!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
  <!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
  <!--[if (gt IE 9)|!(IE)]><!-->
  <body class="">
  <!--<![endif]-->

  <div class="block">

	<p class="block-heading"><?php echo mswSpecialChars($SR->title); ?></p>

	<div class="block-body">
     <?php
	 echo $MSPARSER->mswTxtParsingEngine($SR->answer);
	 ?>
	</div>

	<div class="block-body">
     (<?php echo mswSrCat($SR->departments); ?>)<br>
     <?php echo $msg_response18.': '.$MSDT->mswDateTimeDisplay($SR->ts,$SETTINGS->dateformat).' / '.$MSDT->mswDateTimeDisplay($SR->ts,$SETTINGS->timeformat); ?>
    </div>

  </div>

  <!-- Twitter Bootstrap -->
  <script src="templates/js/plugins/jquery.bootstrap.js"></script>

  </body>
</html>
