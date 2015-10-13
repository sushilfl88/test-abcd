<!DOCTYPE html>
<html lang="{lang}" dir="<?php echo $lang_dir; ?>">
  <head>
    <meta charset="{charset}">
    <title>404</title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Global CSS -->
    <link rel="stylesheet" href="templates/css/bootstrap.css" type="text/css">
	<link rel="stylesheet" href="templates/css/bootstrap-responsive.css" type="text/css">
    <link rel="stylesheet" href="templates/css/theme.css" type="text/css">
    <link rel="stylesheet" href="templates/css/font-awesome.css">
	<script src="templates/js/jquery.js" type="text/javascript"></script>
	<link rel="stylesheet" href="templates/css/ms.css" type="text/css">

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="templates/js/html5.js"></script>
    <![endif]-->

    <link rel="SHORTCUT ICON" href="favicon.ico">
  </head>

  <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
  <!--[if IE 7 ]> <body class="ie ie7 http-error"> <![endif]-->
  <!--[if IE 8 ]> <body class="ie ie8 http-error"> <![endif]-->
  <!--[if IE 9 ]> <body class="ie ie9 http-error"> <![endif]-->
  <!--[if (gt IE 9)|!(IE)]><!-->
  <body class="http-error">
  <!--<![endif]-->

  <div class="row-fluid">
    <div class="http-error">
        <h1>{oops}</h1>
        <p class="info">{error}</p>
        <p><i class="icon-home"></i></p>
        <p><a href="index.php">{back}</a></p>
    </div>
  </div>

  </body>
</html>


