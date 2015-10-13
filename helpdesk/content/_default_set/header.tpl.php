<?php if (!defined('PARENT')) { exit; }
$basePath = $this->SETTINGS->scriptpath.'/content/'.MS_TEMPLATE_SET.'/';
?>
<!DOCTYPE html>
<html lang="<?php echo $this->LANG; ?>" dir="<?php echo $this->DIR; ?>">

  <head>

	<meta charset="<?php echo $this->CHARSET; ?>">
    <title><?php echo $this->TITLE; ?></title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" type="text/css" href="<?php echo $basePath; ?>css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $basePath; ?>css/bootstrap-responsive.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $basePath; ?>css/theme.css">
    <link rel="stylesheet" href="<?php echo $basePath; ?>css/font-awesome.css">

    <script src="<?php echo $basePath; ?>js/jquery.js" type="text/javascript"></script>
    <link rel="stylesheet" href="<?php echo $basePath; ?>css/ms.css" type="text/css">
	<script type="text/javascript">var msTemplatePath = '<?php echo MS_TEMPLATE_SET; ?>';</script>
	<script src="<?php echo $basePath; ?>js/ms-global.js" type="text/javascript"></script>
	<script src="<?php echo $basePath; ?>js/ms-ops.js" type="text/javascript"></script>
    <?php
	// Javascript/CSS page specific..
	echo $this->JS_CSS_BLOCK;
	// HTML5 shiv - IE6-8 support of HTML5 elements
	?>
	<!--[if lt IE 9]>
    <script src="<?php echo $basePath; ?>js/html5.js"></script>
    <![endif]-->

  </head>

  <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
  <!--[if IE 7 ]> <body class="ie ie7"> <![endif]-->
  <!--[if IE 8 ]> <body class="ie ie8"> <![endif]-->
  <!--[if IE 9 ]> <body class="ie ie9"> <![endif]-->
  <!--[if (gt IE 9)|!(IE)]><!-->
  <body>
  <!--<![endif]-->

    <div class="navbar" id="navbar">
        <div class="navbar-inner">
		        <?php
				// If visitor is logged in, show links on top bar..
				if ($this->LOGGED_IN=='yes') {
				?>
                <ul class="nav pull-right">
                   <li>
					 <a href="index.php" class="visible-phone visible-tablet visible-desktop" role="button" title="<?php echo mswSpecialChars($this->TXT[7]); ?>">
					 <i class="icon-dashboard"></i> <?php echo $this->TXT[2]; ?>
					 </a>
				   </li>
                   <li id="fat-menu" class="dropdown">
                     <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                     <i class="icon-user"></i> <?php echo $this->TXT[6]; ?>
                     <i class="icon-caret-down"></i>
                     </a>
                     <ul class="dropdown-menu">
                       <li class="divider visible-phone"></li>
                       <li><a href="?lo=1" title="<?php echo mswSpecialChars($this->TXT[5]); ?>"><?php echo $this->TXT[5]; ?></a></li>
                     </ul>
                   </li>
                </ul>
				<?php
				}
				?>
				<a class="brand" href="index.php"><span class="first"><?php echo $this->TOP_BAR_TITLE; ?></span></a>
        </div>
    </div>

    <div class="sidebar-nav">

        <?php
		// Navigation menu..
		include(PATH.'content/'.MS_TEMPLATE_SET.'/nav-menu.tpl.php');
		?>

	</div>
