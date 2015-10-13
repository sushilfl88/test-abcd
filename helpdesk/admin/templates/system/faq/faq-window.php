<?php if(!defined('PARENT')) { exit; }
$_GET['view'] = (int)$_GET['view'];
$KB           = mswGetTableData('faq','id',$_GET['view']);
if (!isset($KB->id)) {
  exit;
}
if ($SETTINGS->enableVotes=='yes') {
  $yes  = ($KB->kviews>0 ? @number_format($KB->kuseful/$KB->kviews*100,2) : 0);
  $no   = ($KB->kviews>0 ? @number_format($KB->knotuseful/$KB->kviews*100,2) : 0);
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

	<p class="block-heading"><?php echo mswSpecialChars($KB->question); ?></p>

	<div class="block-body">
     <?php
	 echo $MSPARSER->mswTxtParsingEngine($KB->answer);
	 ?>
	</div>

	<div class="block-body">
     <?php
	 $assignedCats = mswFaqCategories($KB->id);
     echo ($assignedCats ? '('.$assignedCats.')' : '<span class="unassigned"><i class="icon-warning-sign"></i> '.$msg_kbase63.'</span>');
	 ?><br>
     <?php echo $msg_response18.': '.$MSDT->mswDateTimeDisplay($KB->ts,$SETTINGS->dateformat).' / '.$MSDT->mswDateTimeDisplay($KB->ts,$SETTINGS->timeformat); ?>
	</div>

	<?php
	if ($SETTINGS->enableVotes=='yes') {
	?>
	<div class="block-body windowVoteArea">
     <p><?php echo str_replace(array('{count}','{helpful}','{nothelpful}'),array($KB->kviews,$yes,$no),$msg_kbase18); ?></p>
	</div>
	<?php
	}
	?>

  </div>

  <!-- Twitter Bootstrap -->
  <script src="templates/js/plugins/jquery.bootstrap.js"></script>

  </body>
</html>
