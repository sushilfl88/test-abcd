<?php

//=========================
// LOAD BBCODE CSS
//=========================

if (isset($loadBBCSS)) {
?>
<link rel="stylesheet" href="templates/css/bbcode.css"  type="text/css">
<?php
}

//================================
// LOAD JQUERY ALERTIFY PLUGIN
//================================

if (isset($loadJQAlertify)) {
?>
<script src="templates/js/plugins/jquery.alertify.js" type="text/javascript"></script>
<link href="templates/css/alertify.core.css" rel="stylesheet" type="text/css">
<link href="templates/css/alertify.theme.css" rel="stylesheet" type="text/css">
<?php
}

//================================
// LOAD JQUERY NYRO MODAL PLUGIN
//================================

if (isset($loadJQNyroModal)) {
?>
<script src="templates/js/plugins/jquery.nyroModal.js" type="text/javascript"></script>
<link href="templates/css/nyroModal.css" rel="stylesheet" type="text/css">
<?php
}

//=========================
// LOAD JQUERY UI
//=========================

if (isset($loadJQAPI)) {
?>
<script type="text/javascript" src="templates/js/jquery-ui.js"></script>
<link href="templates/css/jquery-ui.css" rel="stylesheet" type="text/css">
<?php
}

//=========================
// LOAD JQPLOT LOADER
//=========================

if (isset($loadJQPlot)) {
?>
<script type="text/javascript" src="templates/js/jqplot/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="templates/js/jqplot/jqplot.logAxisRenderer.min.js"></script>
<script type="text/javascript" src="templates/js/jqplot/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="templates/js/jqplot/jqplot.canvasAxisLabelRenderer.min.js"></script>
<script type="text/javascript" src="templates/js/jqplot/jqplot.canvasAxisTickRenderer.min.js"></script>
<script type="text/javascript" src="templates/js/jqplot/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="templates/js/jqplot/jqplot.categoryAxisRenderer.min.js"></script>
<script type="text/javascript" src="templates/js/jqplot/jqplot.barRenderer.min.js"></script>
<script type="text/javascript" src="templates/js/jqplot/jqplot.highlighter.min.js"></script>
<link rel="stylesheet" type="text/css" href="templates/css/jqplot.css">
<?php
}

?>