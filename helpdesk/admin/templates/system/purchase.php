<?php if(!defined('PARENT')) { exit; }
// Check product key exists.. 
if ($SETTINGS->prodKey=='' || strlen($SETTINGS->prodKey)!=60) {
  $productKey = mswProdKeyGen();
  mysql_query("UPDATE `".DB_PREFIX."settings` SET
  `prodKey` = '{$productKey}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  $SETTINGS->prodKey = $productKey;
}
// Update encoder version if not already..
if ($SETTINGS->encoderVersion=='XX' && function_exists('ioncube_loader_version')) {
  mysql_query("UPDATE `".DB_PREFIX."settings` SET
  `encoderVersion` = '".ioncube_loader_version()."'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
}
?>
<div class="content">
        
  <div class="header">
    
	<h1 class="page-title"><?php echo $msg_adheader9; ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li class="active"><?php echo $msg_adheader9; ?></li>
  </ul>

  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  <div class="well">
	   If you would like show your support for this software and enjoy the benefits of the commercial version of <?php echo SCRIPT_NAME; ?>, please consider purchasing a licence. Thank you.<br><br>
        <span class="badge badge-info">1</span> - Please visit the <a href="http://www.<?php echo SCRIPT_URL; ?>" title="<?php echo SCRIPT_NAME; ?>" onclick="window.open(this);return false"><?php echo SCRIPT_NAME; ?> Website</a> and use the &#039;<span class="highlighter">Purchase</span>&#039; option.<br><br>
        <span class="badge badge-info">2</span> - Once payment has been completed you will be redirected to the <a href="https://www.maiangateway.com/login.html" onclick="window.open(this);return false">Maian Script World Licence Centre</a>.<br><br>
        <span class="badge badge-info">3</span> - Generate your &#039;<span class="highlighter">licence.lic</span>&#039; licence file using the onscreen instructions. To generate a licence file you will need the unique <span class="highlighter">60 character product key</span> shown below.<br><br>
        <span class="badge badge-info">4</span> - Upload the &#039;<span class="highlighter">licence.lic</span>&#039; file into your support installation folder and replace the default one.<br><br>
        <span class="badge badge-info">5</span> - Select &#039;<span class="highlighter">Settings > Other Options > Edit Footers</span>&#039; from the left menu. (This is hidden in the free version).
	  </div>
	  <div class="block">
	   <p class="block-heading">PRODUCT KEY</p>
	   <div class="block-body">
	    <?php echo strtoupper($SETTINGS->prodKey); ?>
	   </div>
	  </div>
	  <div class="well" style="margin-top:20px">
	   Besides unlocking ALL the free restrictions, the full version has the following benefits:<br><br>
       &#043; ALL Future upgrades FREE of Charge<br>
       &#043; Notifications of new version releases<br>
       &#043; All features unlocked and unlimited<br>
       &#043; Copyright removal included in price<br>
	   &#043; Free 12 months priority support<br>
       &#043; No links in email footers<br>
       &#043; One off payment, no subscriptions<br><br>
	   Check out the <a href="http://www.<?php echo SCRIPT_URL; ?>/features.html" title="Feature Matrix" onclick="window.open(this);return false">feature comparison matrix</a>. If you have any questions, please <a href="http://www.maianscriptworld.co.uk/contact/" onclick="window.open(this);return false">contact me</a>.
	  </div>
	  <?php
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>
  
  </div>

</div>