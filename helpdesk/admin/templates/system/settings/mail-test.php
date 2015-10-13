<?php if (!defined('PARENT')) { exit; } ?>
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

	<p class="block-heading"><?php echo strtoupper($msg_settings117); ?></p>

	<div class="notesPopup">

	 <input name="emails" type="text" style="width:95%">

	 <div class="btn-toolbar" style="margin-top:0;padding-top:0;text-align:center">
      <button class="btn btn-primary" type="button" onclick="ms_mailTest()"><i class="icon-envelope"></i> <?php echo $msg_settings118; ?></button>
	 </div>

	</div>

  </div>

  <!-- Twitter Bootstrap -->
  <script src="templates/js/plugins/jquery.bootstrap.js"></script>
  <script type="text/javascript">
  //<![CDATA[
  function ms_mailTest() {
    if (jQuery('input[name="emails"]').val()=='') {
	  jQuery('input[name="emails"]').focus();
	  return false;
	}
    jQuery('input[name="emails"]').css('background','url(templates/images/indicator.gif) no-repeat 98% 50%');
    jQuery(document).ready(function() {
     jQuery.post('index.php?ajax=mailtest', {
       emails: jQuery('input[name="emails"]').val()
     },
     function(data) {
	   jQuery('input[name="emails"]').css('background-image','none');
       alert(data['msg']);
     },'json');
    });
    return false;
  }
  //]]>
  </script>

  </body>
</html>