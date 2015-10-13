<?php if (!defined('PARENT')) { exit; } 
$_GET['id'] = (int)$_GET['id'];
$USER       = mswGetTableData('users','id',$_GET['id']);
checkIsValid($USER);
// For graphs..
$dateRange           = '-6 months';
$colors              = array('#c8c8cb','#65718a');
$from                = (isset($_GET['from']) && $MSDT->mswDatePickerFormat($_GET['from'])!='0000-00-00' ? $_GET['from'] : $MSDT->mswConvertMySQLDate(date('Y-m-d',strtotime($dateRange,$MSDT->mswTimeStamp()))));
$to                  = (isset($_GET['to']) && $MSDT->mswDatePickerFormat($_GET['to'])!='0000-00-00' ? $_GET['to'] : $MSDT->mswConvertMySQLDate(date('Y-m-d',$MSDT->mswTimeStamp())));
include(PATH.'control/classes/class.graphs.php');
$MSGRAPH             = new graphs();
$MSGRAPH->settings   = $SETTINGS;
$MSGRAPH->datetime   = $MSDT;
$MSGRAPH->range      = array($from,$to);
$buildGraph          = $MSGRAPH->graph('responses');
?>
<div class="content">
  <script type="text/javascript">
  //<![CDATA[
  <?php
  include(PATH.'templates/date-pickers.php');
  ?>
  function searchToggle() {
    jQuery('#b1').toggle();
	if (jQuery('#b1').css('display')!='none') {
	  jQuery('input[name="q"]').focus();
      jQuery('#search-icon-button').attr('class','icon-remove');
	} else {
	  jQuery('#search-icon-button').attr('class','icon-calendar');
	}
  }
  function searchLog() {
    var from = jQuery('input[name="from"]').val();
	var to   = jQuery('input[name="to"]').val();
	if (from=='' || to=='') {
	  if (to=='') {
	    jQuery('input[name="to"]').focus();
	  } else {
	    jQuery('input[name="from"]').focus();
	  }
	  return false;
	}
	jQuery('#form').submit();
  }
  //]]>
  </script>  
  <div class="header">
  
    <button class="btn search-bar-button" type="button" onclick="searchToggle()"><i class="icon-calendar" id="search-icon-button"></i></button>
	<h1 class="page-title"><?php echo $msg_user86; ?></h1>
	 
	<span class="clearfix"></span>
	
  </div>
  
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader4; ?> <span class="divider">/</span></li>
    <li><?php echo $msg_user86; ?> <span class="divider">/</span></li>
	<li class="active"><?php echo mswSpecialChars($U->name); ?></li>
  </ul>

  <form method="get" id="form" action="index.php?p=<?php echo $_GET['p']; ?>" onsubmit="return ms_fieldCheck('none','none')">
  <div class="container-fluid" style="margin-top:20px">
    
    <div class="btn-toolbar" id="b1" style="margin-top:0;padding-top:0;display:none">
     <input type="hidden" name="p" value="graph"><input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
     <input type="text" placeholder="<?php echo mswSpecialChars($msg_reports2); ?>" class="input-small" id="from" name="from" value="<?php echo mswSpecialChars($from); ?>" style="margin-right:1px">
     <div class="input-append">
      <input placeholder="<?php echo mswSpecialChars($msg_reports3); ?>" type="text" class="input-small" id="to" name="to" value="<?php echo mswSpecialChars($to); ?>">
      <button type="button" class="btn btn-info" onclick="searchLog()"><i class="icon-search"></i></button>
     </div>
    </div>
  
	<div class="row-fluid">
	  <div class="span5">
	    <i class="icon-calendar" onclick="searchToggle()" style="cursor:pointer"></i> <?php echo $from; ?> - <?php echo $to; ?>
	  </div>
	  <div class="span7 alignmentRight">
	   <i class="icon-circle" style="color:<?php echo $colors[0]; ?>"></i> <?php echo $msg_user92; ?>&nbsp;&nbsp;&nbsp;<i class="icon-circle" style="color:<?php echo $colors[1]; ?>"></i> <?php echo $msg_user93; ?>
	  </div>
	  <span class="clearfix"></span>
	</div>
	
	<?php
	if (!empty($buildGraph[0]) || !empty($buildGraph[1])) {
	?>
	<div class="row-fluid">
	  <div class="chartWrapper">
	  <div id="chart">
	  <script type="text/javascript">
	  //<![CDATA[
      jQuery(document).ready(function(){
	   <?php
	   if (!empty($buildGraph[0])) {
	   ?>
	   var line = [<?php echo implode(',',$buildGraph[0]); ?>];
       <?php
	   $plot = '[line]';
	   $clrs = "'".$colors[0]."'";
	   }
	   if (!empty($buildGraph[1])) {
	   ?>
	   var line2 = [<?php echo implode(',',$buildGraph[1]); ?>];
       <?php
	   $plot = '[line2]';
	   $clrs = "'".$colors[1]."'";
	   }
	   if (!empty($buildGraph[0]) && !empty($buildGraph[1])) {
	     $plot = '[line,line2]';
		 $clrs = "'".$colors[0]."','".$colors[1]."'";
	   }
	   ?>
       var plot = jQuery.jqplot('chart',<?php echo $plot; ?>, {
        seriesColors: [<?php echo $clrs; ?>],
		grid: {
         drawGridLines: true,
         gridLineColor: '#ddd',
         background: '#fdfdfd',
         borderColor: '#ddd',
		 borderWidth: '0.5',
		 shadow: false
		},
		axes: {
         xaxis: {
          renderer: jQuery.jqplot.DateAxisRenderer,
          labelRenderer: jQuery.jqplot.CanvasAxisLabelRenderer,
          tickRenderer: jQuery.jqplot.CanvasAxisTickRenderer,
          tickOptions: {
           angle: 90
          }
         },
         yaxis: {
          min: 0,
          labelRenderer: jQuery.jqplot.CanvasAxisLabelRenderer,
		  tickOptions: {
           formatString: '%d'
          },
		  tickInterval: 1
         }
        }
       });
	  });
      //]]>
      </script>
	  </div>
	  </div>
	  <div class="btn-toolbar" style="margin-top:25px;padding-top:0">
	    <button class="btn btn-primary" type="button" onclick="jQuery('#chart').jqplotSaveImage()"><i class="icon-save"></i> <?php echo $msg_user88; ?></button>
		<button class="btn btn-link" type="button" onclick="ms_windowLoc('?p=teamman')"><i class="icon-remove"></i> <?php echo $msg_levels11; ?></button>
      </div>
	  <?php
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>
    <?php
	} else {
	?><br>
	<table class="table table-striped table-hover">
     <tr class="warning nothing_to_see">
	  <td><?php echo $msg_user94; ?></td>
	 </tr> 
	</table>
	<div class="btn-toolbar" style="margin-top:25px;padding-top:0">
	  <button class="btn btn-link" type="button" onclick="ms_windowLoc('?p=teamman')"><i class="icon-remove"></i> <?php echo $msg_levels11; ?></button>
    </div>
    <?php
	}
	?>
  </div>
  </form>

</div>