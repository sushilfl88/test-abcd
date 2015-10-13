<?php if (!defined('PARENT')) { exit; } ?>      
	  <div class="row-fluid">
       <div class="block" style="padding:0;margin:0;margin-top:10px">
	    <p class="block-heading">
         <span class="block-icon pull-right"><i onclick="ms_showHideDateRange('show')" class="icon-calendar" style="cursor:pointer" title="<?php echo mswSpecialChars($msg_home56); ?>"></i></span>
         <?php
		 if ($SETTINGS->disputes=='yes') {
		 ?>
		 <i class="icon-bar-chart"></i> <?php echo $msg_home54; ?> <span style="margin-left:20px"><?php echo $msg_home61; ?> <span class="badge" style="font-size:11px;padding:2px;line-height:10px;background:<?php echo $g_config['color1']; ?>">&nbsp;</span>&nbsp;&nbsp;&nbsp;<?php echo $msg_home62; ?> <span class="badge" style="font-size:11px;padding:2px;line-height:10px;background:<?php echo $g_config['color2']; ?>">&nbsp;</span></span>
         <?php
		 } else {
		 ?>
		 <i class="icon-bar-chart"></i> <?php echo $msg_home63; ?></span>
         <?php
		 }
		 ?>
		</p>
		<div class="block-body" id="range" style="display:none">
		  <?php echo $msg_home55; ?> <input type="text" class="input-small" name="from" id="from" value="<?php echo mswSpecialChars($from); ?>"> -
		  <input type="text" class="input-small" name="to" id="to" value="<?php echo mswSpecialChars($to); ?>"><br>
		  <?php echo $msg_home59; ?> <input type="text" class="input-small" name="def" value="<?php echo $g_config['default']; ?>" maxlength="3" style="width:30px"> <?php echo $msg_home60; ?>
          <div class="btn-toolbar" style="margin:10px 0 0 50px;padding-top:0">
	       <button class="btn btn-primary" type="button" onclick="ms_changeDateRange()"><i class="icon-refresh"></i> <?php echo $msg_home57; ?></button>
		   <button class="btn btn-link" type="button" onclick="ms_showHideDateRange('cancel')"><i class="icon-remove"></i> <?php echo $msg_levels11; ?></button>
          </div>
		</div>
        <div class="block-body" id="graph">
		 <div class="chartWrapper">
		  <?php
		  if ($g_tick || $g_disp) {
		  ?>
	      <div id="chart">
	      <script type="text/javascript">
	      //<![CDATA[
          jQuery(document).ready(function(){
		   var line  = [];
		   var line2 = [];
		   <?php
		   if ($g_tick) {
		   ?>
	   	   line = [<?php echo $g_tick; ?>];
		   <?php
		   }
		   if ($SETTINGS->disputes=='yes' && $g_disp) {
		   ?>
		   line2 = [<?php echo $g_disp; ?>];
		   <?php
		   }
		   ?>
           var plot = jQuery.jqplot('chart',[line,line2], {
            seriesColors: ['<?php echo $g_config['color1']; ?>','<?php echo $g_config['color2']; ?>'],
		    grid: {
             drawGridLines: true,
             gridLineColor: '<?php echo $g_config['gline']; ?>',
             background: '<?php echo $g_config['bg']; ?>',
             borderColor: '<?php echo $g_config['border']; ?>',
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
		  <?php
		  } else {
		  ?>
		  <p class="nothing_to_see smalltxt"><?php echo $msg_home58; ?></p>
		  <?php
		  }
		  ?>
	     </div>
        </div>
       </div>
	  </div>