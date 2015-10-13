<?php if (!defined('PARENT')) { exit; } ?>
<!DOCTYPE html>
<html lang="<?php echo (isset($html_lang) ? $html_lang : 'en'); ?>" dir="<?php echo $lang_dir; ?>">
  <head>
    <meta charset="<?php echo $msg_charset; ?>">
    <title><?php echo ($title ? mswSpecialChars($title).': ' : '').$msg_script.' - '.mswCleanData($msg_adheader).(LICENCE_VER!='unlocked' ? ' (Free Version)' : '').(mswCheckBetaVersion()=='yes' ? ' - BETA VERSION' : ''); ?></title>
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
  <!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
  <!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
  <!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
  <!--[if (gt IE 9)|!(IE)]><!-->
  <body class="">
  <!--<![endif]-->

  <div class="container-fluid" style="margin-top:20px">

    <div class="row-fluid">

	  <div class="well">

	    <div class="block">

		  <p class="block-heading"><?php echo strtoupper($msg_bbcode16); ?></p>
		  <div class="block-body">
		   <b>[b]</b> <?php echo $msg_bbcode3; ?> <b>[/b]</b> = <b><?php echo $msg_bbcode3; ?></b>
		  </div>
		  <div class="block-body">
		   <b>[u]</b> <?php echo $msg_bbcode4; ?> <b>[/u]</b> = <span style="text-decoration:underline"><?php echo $msg_bbcode4; ?></span>
		  </div>
		  <div class="block-body">
           <b>[i]</b> <?php echo $msg_bbcode5; ?> <b>[/i]</b> = <span style="font-style:italic"><?php echo $msg_bbcode5; ?></span>
          </div>
		  <div class="block-body">
		  <b>[s]</b> <?php echo $msg_bbcode6; ?> <b>[/s]</b> = <span style="text-decoration:line-through"><?php echo $msg_bbcode6; ?></span>
		  </div>
		  <div class="block-body">
		   <b>[del]</b> <?php echo $msg_bbcode7; ?> <b>[/del]</b> = <span style="text-decoration:line-through;color:red"><?php echo $msg_bbcode7; ?></span>
		  </div>
		  <div class="block-body">
		   <b>[ins]</b> <?php echo $msg_bbcode8; ?> <b>[/ins]</b> = <span style="background:yellow"><?php echo $msg_bbcode8; ?></span>
		  </div>
		  <div class="block-body">
		   <b>[em]</b> <?php echo $msg_bbcode9; ?> <b>[/em]</b> = <span style="font-style:italic;font-weight:bold"><?php echo $msg_bbcode9; ?></span>
		  </div>
		  <div class="block-body">
		   <b>[color=#FF0000]</b> <?php echo $msg_bbcode10; ?><b> [/color]</b> = <span style="color:red"><?php echo $msg_bbcode10; ?></span>
		  </div>
		  <div class="block-body">
		   <b>[color=blue]</b> <?php echo $msg_bbcode11; ?> <b>[/color]</b> = <span style="color:blue"><?php echo $msg_bbcode11; ?></span>
		  </div>
		  <div class="block-body">
		   <b>[h1]</b> <?php echo $msg_bbcode12; ?> <b>[/h1]</b> = <span style="font-weight:bold;font-size:22px"><?php echo $msg_bbcode12; ?></span>
		  </div>
		  <div class="block-body">
		   <b>[h2]</b> <?php echo $msg_bbcode13; ?> <b>[/h2]</b> = <span style="font-weight:bold;font-size:20px"><?php echo $msg_bbcode13; ?></span>
		  </div>
		  <div class="block-body">
		   <b>[h3]</b> <?php echo $msg_bbcode14; ?> <b>[/h3]</b> = <span style="font-weight:bold;font-size:18px"><?php echo $msg_bbcode14; ?></span>
		  </div>
		  <div class="block-body">
		   <b>[h4]</b> <?php echo $msg_bbcode15; ?> <b>[/h4]</b> = <span style="font-weight:bold;font-size:16px"><?php echo $msg_bbcode15; ?></span>
		  </div>
       </div>

	   <div class="block">

		  <p class="block-heading"><?php echo strtoupper($msg_bbcode17); ?></p>
		  <div class="block-body">
		   <b>[url=http://www.example.com]</b> <?php echo $msg_bbcode32; ?> <b>[/url]</b> = <a href="http://www.example.com"><?php echo $msg_bbcode32; ?></a>
		  </div>
		  <div class="block-body">
		   <b>[url]</b> http://www.example.com <b>[/url]</b> = <a href="http://www.example.com">http://www.example.com</a>
		  </div>
		  <div class="block-body">
		   <b>[urlnew=http://www.example.com]</b> <?php echo $msg_bbcode32; ?> <b>[/urlnew]</b> = <a href="http://www.example.com"><?php echo $msg_bbcode32; ?></a> (<?php echo $msg_bbcode28; ?>)
		  </div>
		  <div class="block-body">
		   <b>[urlnew]</b> http://www.example.com <b>[/urlnew]</b> = <a href="http://www.example.com">http://www.example.com</a> (<?php echo $msg_bbcode28; ?>)
		  </div>
		  <div class="block-body">
		   <b>[email]</b> email@example.com <b>[/email]</b> = <a href="mailto:email@example.com">email@example.com</a>
		  </div>
		  <div class="block-body">
		   <b>[email=email@example.com]</b> <?php echo $msg_bbcode26; ?> <b>[/email]</b> = <a href="mailto:email@example.com"><?php echo $msg_bbcode26; ?></a>
		  </div>
		  <div class="block-body">
		   <b>[img]</b> http://www.example.com/images/logo.png <b>[/img]</b> = <?php echo $msg_bbcode31; ?>
		  </div>

	   </div>

	   <div class="block">

		  <p class="block-heading"><?php echo strtoupper($msg_bbcode28); ?></p>
		  <div class="block-body">
		   <b>[youtube]</b><?php echo $msg_bbcode30; ?><b>[/youtube]</b> = <?php echo $msg_bbcode29; ?>
		  </div>
		  <div class="block-body">
		   <b>[vimeo]</b><?php echo $msg_bbcode30; ?><b>[/vimeo]</b> = <?php echo $msg_bbcode29; ?>
		  </div>

	   </div>

	   <div class="block">

		  <p class="block-heading"><?php echo strtoupper($msg_bbcode18); ?></p>
		  <div class="block-body">
		   <div class="row-fluid">
		    <div class="span5"><b>[list]</b><br><b>&nbsp;[*]</b> <?php echo $msg_bbcode20; ?> 1 <b>[/*]<br>&nbsp;[*]</b> <?php echo $msg_bbcode20; ?> 2 <b>[/*]<br>&nbsp;[*]</b> <?php echo $msg_bbcode20; ?> 3 <b>[/*]<br>[/list]</b></div>
			<div class="span1">=</div>
			<div class="span5"><ul style="list-style-type:disc"><li><?php echo $msg_bbcode20; ?> 1</li><li><?php echo $msg_bbcode20; ?> 2</li><li><?php echo $msg_bbcode20; ?> 3</li></ul></div>
		   </div>
		  </div>
		  <div class="block-body">
		   <div class="row-fluid">
		    <div class="span5"><b>[list=n]</b><br><b>&nbsp;[*]</b> <?php echo $msg_bbcode21; ?> 1 <b>[/*]<br>&nbsp;[*]</b> <?php echo $msg_bbcode21; ?> 2 <b>[/*]<br>&nbsp;[*]</b> <?php echo $msg_bbcode21; ?> 3 <b>[/*]<br>[/list]</b></div>
			<div class="span1">=</div>
			<div class="span5"><ul style="list-style-type:decimal"><li><?php echo $msg_bbcode21; ?> 1</li><li><?php echo $msg_bbcode21; ?> 2</li><li><?php echo $msg_bbcode21; ?> 3</li></ul></div>
		   </div>
		  </div>
		  <div class="block-body">
		   <div class="row-fluid">
		    <div class="span5"><b>[list=a]</b><br><b>&nbsp;[*]</b> <?php echo $msg_bbcode22; ?> 1 <b>[/*]<br>&nbsp;[*]</b> <?php echo $msg_bbcode22; ?> 2 <b>[/*]<br>&nbsp;[*]</b> <?php echo $msg_bbcode22; ?> 3 <b>[/*]<br>[/list]</b></div>
			<div class="span1">=</div>
			<div class="span5"><ul style="list-style-type:lower-alpha"><li><?php echo $msg_bbcode22; ?> 1</li><li><?php echo $msg_bbcode22; ?> 2</li><li><?php echo $msg_bbcode22; ?> 3</li></ul></div>
		   </div>
		  </div>

	   </div>

	   <div class="block">

		  <p class="block-heading"><?php echo strtoupper($msg_bbcode19); ?></p>
		  <div class="block-body">
		   <b>[b][u]</b><?php echo $msg_bbcode23; ?> <b>[/u][/b]</b> = <span style="text-decoration:underline;font-weight:bold"><?php echo $msg_bbcode23; ?></span>
		  </div>
		  <div class="block-body">
		   <b>[color=blue][b][u]</b> <?php echo $msg_bbcode24; ?> <b>[/u][/b][/color]</b> = <span style="text-decoration:underline;font-weight:bold;color:blue"><?php echo $msg_bbcode24; ?></span>
		  </div>

		</div>

	  </div>

    </div>

  </div>

  </body>
</html>