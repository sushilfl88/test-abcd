<?php if (!defined('PARENT')) { exit; }
if (!isset($SUPTICK->id)) { exit; }
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
  <!--[if IE 7 ]> <body class="ie ie7"> <![endif]-->
  <!--[if IE 8 ]> <body class="ie ie8"> <![endif]-->
  <!--[if IE 9 ]> <body class="ie ie9"> <![endif]-->
  <!--[if (gt IE 9)|!(IE)]><!-->
  <body class="">
  <!--<![endif]-->

  <div class="block">

	<p class="block-heading"><?php echo strtoupper($msg_viewticket99); ?> (#<?php echo mswTicketNumber($SUPTICK->id); ?>)</p>

	<div class="notesPopup">

	 <textarea name="notes" rows="8" cols="40" id="notes"><?php echo mswSpecialChars($SUPTICK->ticketNotes); ?></textarea>

	 <div class="btn-toolbar" style="margin-top:0;padding-top:0;text-align:center">
      <button class="btn btn-primary" type="button" onclick="ms_updateTicketNotes('<?php echo $SUPTICK->id; ?>')"><i class="icon-ok"></i> <?php echo $msg_viewticket99; ?></button>
	 </div>

	</div>

  </div>

  <!-- Twitter Bootstrap -->
  <script src="templates/js/plugins/jquery.bootstrap.js"></script>

  </body>
</html>