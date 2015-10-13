<?php
// Base path..
$basePath = $this->SETTINGS->scriptpath.'/content/'.MS_TEMPLATE_SET.'/';
?>
<!DOCTYPE html>
<html lang="<?php echo $this->LANG; ?>">
  
  <head>
    
	<meta charset="<?php echo $this->CHARSET; ?>">
    <title>403</title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
	<link rel="stylesheet" type="text/css" href="<?php echo $basePath; ?>css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $basePath; ?>css/bootstrap-responsive.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $basePath; ?>css/theme.css">
    <link rel="stylesheet" href="<?php echo $basePath; ?>css/font-awesome.css">

    <script src="<?php echo $basePath; ?>js/jquery.js" type="text/javascript"></script>
    <link rel="stylesheet" href="<?php echo $basePath; ?>css/ms.css" type="text/css">
	<?php
	// HTML5 shim - IE6-8 support of HTML5 elements
	?>
	<!--[if lt IE 9]>
    <script src="<?php echo $basePath; ?>js/html5.js"></script>
    <![endif]-->

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
        <h1><?php echo $this->TXT[0]; ?></h1>
        <p class="info"><?php echo $this->TXT[1]; ?></p>
        <p><i class="icon-home"></i></p>
        <p><a href="<?php echo $this->SETTINGS->scriptpath; ?>/index.php"><?php echo $this->TXT[2]; ?></a></p>
    </div>
  </div>
    
  </body>
</html>


