<?php if (!defined('PARENT')) { exit; }
$g_config  =  array(
 'default' => $MSTEAM->defDays,
 'color1'  => '#c8c8cb',
 'color2'  => '#5f6d88',
 'bg'      => '#fdfdfd',
 'gline'   => '#dddddd',
 'border'  => '#dddddd'
);
include(PATH.'control/classes/class.graphs.php');
$tz               = ($MSTEAM->timezone ? $MSTEAM->timezone : $SETTINGS->timezone);
$from             = (isset($_GET['f']) && $_GET['f'] && $MSDT->mswDatePickerFormat($_GET['f'])!='0000-00-00' ? $_GET['f'] : $MSDT->mswConvertMySQLDate(date('Y-m-d',strtotime('-'.$g_config['default'].' days',$MSDT->mswTimeStamp()))));
$to               = (isset($_GET['t']) && $_GET['t'] && $MSDT->mswDatePickerFormat($_GET['t'])!='0000-00-00' ? $_GET['t'] : $MSDT->mswConvertMySQLDate(date('Y-m-d',$MSDT->mswTimeStamp())));
$graph            = new graphs();
$graph->settings  = $SETTINGS;
$graph->datetime  = $MSDT;
$graph->team      = $MSTEAM;
$data             = $graph->home($from,$to,$ticketFilterAccess);
$g_tick           = (isset($data[0]) && $data[0]!='none' ? implode(',',$data[0]) : '');
$g_disp           = (isset($data[1]) && $data[1]!='none' ? implode(',',$data[1]) : '');
?>
<div class="content">
  <script type="text/javascript">
  //<![CDATA[
  <?php
  include(PATH.'templates/date-pickers.php');
  ?>
  function ms_changeDateRange() {
    var fm = jQuery('input[name="from"]').val();
	var to = jQuery('input[name="to"]').val();
	var dd = jQuery('input[name="def"]').val();
	if (dd=='') {
	  if (fm=='') {
	    jQuery('input[name="from"]').focus();
	    return false;
	  }
	  if (to=='') {
	    jQuery('input[name="to"]').focus();
	    return false;
	  }
	}
	ms_windowLoc('?f='+fm+'&t='+to+'&dd='+dd);
  }
  function ms_showHideDateRange(act) {
    switch (act) {
	  case 'show':
	  jQuery('#range').show();
	  jQuery('#graph').hide();
	  break;
	  default:
	  jQuery('#range').hide();
	  jQuery('#graph').show();
	  break;
	}
  }
  //]]>
  </script>
  <div class="header">
    
	<h1 class="page-title"><?php echo $msg_adheader11; ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $MSDT->mswDateTimeDisplay(strtotime(date('Y-m-d',$MSDT->mswUTC())),$SETTINGS->dateformat,$tz); ?></li>
  </ul>
  
  <div class="container-fluid">
    
	<div class="row-fluid">
	  <?php
	  switch ($MSTEAM->id) {
	    case 1:
		include(PATH.'templates/system/home/admin.php');
		break;
		default:
		include(PATH.'templates/system/home/users.php');
		break;
	  }
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>
  
  </div>

</div>

