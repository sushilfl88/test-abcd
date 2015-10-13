<?php if (!defined('PARENT')) { exit; } ?>
<!DOCTYPE html>
<html lang="<?php echo (isset($html_lang) ? $html_lang : 'en'); ?>" dir="<?php echo $lang_dir; ?>">
  <head>
    <meta charset="<?php echo $msg_charset; ?>">
    <title><?php echo ($title ? mswSpecialChars($title).': ' : '').mswCleanData($msg_login); ?></title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Stylesheets -->
    <link rel="stylesheet" href="templates/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="templates/css/theme.css" type="text/css">
	<link rel="stylesheet" href="templates/css/ms.css" type="text/css">
    <link rel="stylesheet" href="templates/css/font-awesome.css">

	<!-- JS -->
	<script src="templates/js/jquery.js" type="text/javascript"></script>
	<script src="templates/js/ms-global.js" type="text/javascript"></script>

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

  <div class="navbar">
    <div class="navbar-inner">
      <ul class="nav pull-right"></ul>
      <a class="brand" href="index.php"><span class="first"><?php echo ($title ? $title.': ' : '').$msg_login; ?></span></a>
    </div>
  </div>

  <form method="post" id="form" action="?p=<?php echo $_GET['p']; ?>">
  <div class="row-fluid">
    <div class="dialog">
      <div class="block">
        <p class="block-heading"><?php echo $msg_login9; ?></p>
          <div class="block-body">
              <label><?php echo $msg_login8; ?></label>
              <input type="text" id="user" name="user" class="span12" value="" onkeyup="jQuery('#e_user').hide('slow')" onkeypress="if(getKeyCode(event)==13){sysLoginEvent()}">
			  <?php
			  // Show user error if applicable..
			  if (isset($U_ERROR)) {
			  ?>
			  <div class="alert alert-error" style="margin-top:0" id="e_user">
               <a class="close" data-dismiss="alert">×</a> <?php echo $msg_login6; ?>
			  </div>
              <?php
			  }
			  ?>
			  <label><?php echo $msg_login2; ?></label>
              <input type="password" id="pass" name="pass" class="span12" value="" onkeyup="jQuery('#e_pass').hide('slow')" onkeypress="if(getKeyCode(event)==13){sysLoginEvent()}">
              <?php
			  // Show password error if applicable..
			  if (isset($P_ERROR)) {
			  ?>
			  <div class="alert alert-error" style="margin-top:0" id="e_pass">
               <a class="close" data-dismiss="alert">×</a> <?php echo $msg_login4; ?>
			  </div>
              <?php
			  }
			  ?>
			  <a href="#" onclick="sysLoginEvent();return false" class="btn btn-primary pull-right" title="<?php echo mswSpecialChars($msg_login5); ?>"><?php echo mswCleanData($msg_login5); ?></a>
			  <?php
			  // Is cookie set?
              if (COOKIE_NAME) {
              ?>
              <label class="remember-me"><input type="checkbox" name="cookie" value="1"> <?php echo $msg_login3; ?></label>
			  <?php
			  }
			  ?>
			  <input type="hidden" name="process" value="1">
              <div class="clearfix"></div>
          </div>
      </div>
    </div>
  </div>
  </form>

  <!-- Twitter Bootstrap -->
  <script type="text/javascript">
  //<![CDATA[
  jQuery(document).ready(function() {
    if (jQuery('#user').val()=='') {
	  jQuery('#user').focus();
	} else {
	  jQuery('#pass').focus();
	}
  });
  //]]>
  </script>
  <script src="templates/js/plugins/jquery.bootstrap.js"></script>

</body>
</html>


