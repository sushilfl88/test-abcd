<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: menu.php
  Description: Nav Menu

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

//============================================
// UNCOMMENT TO AUTO COLLAPSE ALL ON LOAD
//============================================

/*for ($i=1; $i<11; $i++) {
  $men  = 'menu'.$i.'_collapse';
  $$men = 'yes';
}
*/

//=================
// TICKETS
//=================

$adminPageSlots                       = 0;
$adminPages                           = array();
$adminPages[$adminPageSlots]['title'] = $msg_adheader41;
if (in_array('assign',$userAccess) || in_array('open',$userAccess) || in_array('close',$userAccess) || in_array('disputes',$userAccess) || in_array('cdisputes',$userAccess) ||
    in_array('search',$userAccess) || in_array('add',$userAccess) || in_array('spam',$userAccess) || $MSTEAM->id=='1') {
?>
<a href="#tickets-menu" class="nav-header<?php echo (isset($menu1_collapse) ? '' : ' collapsed'); ?>" data-toggle="collapse" title="<?php echo mswSpecialChars($adminPages[$adminPageSlots]['title']); ?>"><i class="fam-clock-edit"></i> <?php echo mswCleanData($adminPages[$adminPageSlots]['title']); ?> <i class="icon-chevron-up"></i></a>
<ul id="tickets-menu" class="nav nav-list collapse<?php echo (isset($menu1_collapse) ? ' in' : ''); ?>">
<?php

// Add new ticket..
if (in_array('add',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=add" title="<?php echo mswSpecialChars($msg_open); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_open); ?></a></li>
<?php
$adminPages[$adminPageSlots]['add'] = $msg_open;
}

// Assign tickets..
if (in_array('assign',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=assign" title="<?php echo mswSpecialChars($msg_adheader32); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader32); ?></a></li>
<?php
$adminPages[$adminPageSlots]['assign'] = $msg_adheader32;
}

// Open tickets..
if (in_array('open',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=open" title="<?php echo mswSpecialChars($msg_adheader5); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader5); ?></a></li>
<?php
$adminPages[$adminPageSlots]['open'] = $msg_adheader5;
}

// Closed tickets..
if (in_array('close',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=close" title="<?php echo mswSpecialChars($msg_adheader6); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader6); ?></a></li>
<?php
$adminPages[$adminPageSlots]['close'] = $msg_adheader6;
}

// Open disputes..
if ($SETTINGS->disputes=='yes') {
if (in_array('disputes',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=disputes" title="<?php echo mswSpecialChars($msg_adheader28); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader28); ?></a></li>
<?php
$adminPages[$adminPageSlots]['disputes'] = $msg_adheader28;
}

// Closed disputes..
if (in_array('cdisputes',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=cdisputes" title="<?php echo mswSpecialChars($msg_adheader29); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader29); ?></a></li>
<?php
$adminPages[$adminPageSlots]['cdisputes'] = $msg_adheader29;
}
}

// Spam tickets..
if (mswRowCount('imap WHERE `im_piping` = \'yes\' AND `im_spam` = \'yes\'')>0 && (in_array('spam',$userAccess) || $MSTEAM->id=='1')) {
?>
<li><a href="?p=spam" title="<?php echo mswSpecialChars($msg_adheader63); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader63); ?></a></li>
<?php
$adminPages[$adminPageSlots]['spam'] = $msg_adheader63;
}

// Search tickets..
if (in_array('search',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=search" title="<?php echo mswSpecialChars($msg_adheader7); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader7); ?></a></li>
<?php
$adminPages[$adminPageSlots]['search'] = $msg_adheader7;
}

// IMS Portal..
if (in_array('submit',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=submit" title="<?php echo mswSpecialChars($msg_adheader65); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader65); ?></a></li>
<?php
$adminPages[$adminPageSlots]['submit'] = $msg_adheader65;
}

?>
</ul>
<?php

}

//====================
// SUPPORT TEAM
//====================

++$adminPageSlots;
$adminPages[$adminPageSlots]['title'] = $msg_adheader4;

if (in_array('team',$userAccess) || in_array('teamman',$userAccess) || $MSTEAM->id=='1') {
?>
<a href="#users-menu" class="nav-header<?php echo (isset($menu2_collapse) ? '' : ' collapsed'); ?>" data-toggle="collapse" title="<?php echo mswSpecialChars($adminPages[$adminPageSlots]['title']); ?>"><i class="fam-user"></i> <?php echo mswCleanData($adminPages[$adminPageSlots]['title']); ?> <i class="icon-chevron-up"></i></a>
<ul id="users-menu" class="nav nav-list collapse<?php echo (isset($menu2_collapse) ? ' in' : ''); ?>">
<?php

// Add user..
if (in_array('team',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=team" title="<?php echo mswSpecialChars($msg_adheader57); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader57); ?></a></li>
<?php
$adminPages[$adminPageSlots]['team'] = $msg_adheader57;
}

// Manage users..
if (in_array('teamman',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=teamman" title="<?php echo mswSpecialChars($msg_adheader58); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader58); ?></a></li>
<?php
$adminPages[$adminPageSlots]['teamman'] = $msg_adheader58;
}

?>
</ul>
<?php

}

//====================
// ACCOUNTS
//====================

++$adminPageSlots;
$adminPages[$adminPageSlots]['title'] = $msg_adheader38;

if (in_array('accounts',$userAccess) || in_array('accountman',$userAccess) ||
    in_array('accountsearch',$userAccess) || in_array('acc-import',$userAccess) ||
	$MSTEAM->id=='1') {
?>
<a href="#accounts-menu" class="nav-header<?php echo (isset($menu3_collapse) ? '' : ' collapsed'); ?>" data-toggle="collapse" title="<?php echo mswSpecialChars($adminPages[$adminPageSlots]['title']); ?>"><i class="fam-mouse"></i> <?php echo mswSpecialChars($adminPages[$adminPageSlots]['title']); ?> <i class="icon-chevron-up"></i></a>
<ul id="accounts-menu" class="nav nav-list collapse<?php echo (isset($menu3_collapse) ? ' in' : ''); ?>">
<?php

// Add account..
if (in_array('accounts',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=accounts" title="<?php echo mswSpecialChars($msg_adheader39); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader39); ?></a></li>
<?php
$adminPages[$adminPageSlots]['accounts'] = $msg_adheader39;
}

// Manage accounts..
if (in_array('accountman',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=accountman" title="<?php echo mswSpecialChars($msg_adheader40); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader40); ?></a></li>
<?php
$adminPages[$adminPageSlots]['accountman'] = $msg_adheader40;
}

// Search accounts..
if (in_array('accountsearch',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=accountsearch" title="<?php echo mswSpecialChars($msg_adheader56); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader56); ?></a></li>
<?php
$adminPages[$adminPageSlots]['accountsearch'] = $msg_adheader56;
}

// Import accounts..
if (in_array('acc-import',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=acc-import" title="<?php echo mswSpecialChars($msg_adheader59); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader59); ?></a></li>
<?php
$adminPages[$adminPageSlots]['acc-import'] = $msg_adheader59;
}

?>
</ul>
<?php

}

//====================
// DEPARTMENTS
//====================

++$adminPageSlots;
$adminPages[$adminPageSlots]['title'] = $msg_adheader3;

if (in_array('dept',$userAccess) || in_array('deptman',$userAccess) || $MSTEAM->id=='1') {
?>
<a href="#dept-menu" class="nav-header<?php echo (isset($menu10_collapse) ? '' : ' collapsed'); ?>" data-toggle="collapse" title="<?php echo mswSpecialChars($adminPages[$adminPageSlots]['title']); ?>"><i class="fam-page-white-add"></i> <?php echo mswCleanData($adminPages[$adminPageSlots]['title']); ?> <i class="icon-chevron-up"></i></a>
<ul id="dept-menu" class="nav nav-list collapse<?php echo (isset($menu10_collapse) ? ' in' : ''); ?>">
<?php

// Add department..
if (in_array('dept',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=dept" title="<?php echo mswSpecialChars($msg_dept2); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_dept2); ?></a></li>
<?php
$adminPages[$adminPageSlots]['dept'] = $msg_dept2;
}

// Manage departments..
if (in_array('deptman',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=deptman" title="<?php echo mswSpecialChars($msg_dept9); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_dept9); ?></a></li>
<?php
$adminPages[$adminPageSlots]['deptman'] = $msg_dept9;
}

?>
</ul>
<?php

}

//====================
// CUSTOM FIELDS
//====================

++$adminPageSlots;
$adminPages[$adminPageSlots]['title'] = $msg_adheader26;

if (in_array('fieldsman',$userAccess) || in_array('fields',$userAccess) || $MSTEAM->id=='1') {
?>
<a href="#fields-menu" class="nav-header<?php echo (isset($menu5_collapse) ? '' : ' collapsed'); ?>" data-toggle="collapse" title="<?php echo mswSpecialChars($adminPages[$adminPageSlots]['title']); ?>"><i class="fam-application-view-list"></i> <?php echo mswSpecialChars($adminPages[$adminPageSlots]['title']); ?> <i class="icon-chevron-up"></i></a>
<ul id="fields-menu" class="nav nav-list collapse<?php echo (isset($menu5_collapse) ? ' in' : ''); ?>">
<?php

// Add custom field..
if (in_array('fields',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=fields" title="<?php echo mswSpecialChars($msg_customfields2); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_customfields2); ?></a></li>
<?php
$adminPages[$adminPageSlots]['fields'] = $msg_customfields2;
}

// Manage custom fields..
if (in_array('fieldsman',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=fieldsman" title="<?php echo mswSpecialChars($msg_adheader43); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader43); ?></a></li>
<?php
$adminPages[$adminPageSlots]['fieldsman'] = $msg_adheader43;
}

?>
</ul>
<?php

}

//======================
// STANDARD RESPONSES
//======================

++$adminPageSlots;
$adminPages[$adminPageSlots]['title'] = $msg_adheader13;

if (in_array('responseman',$userAccess) || in_array('standard-responses',$userAccess) || 
    in_array('standard-responses-import',$userAccess) || $MSTEAM->id=='1') {
?>
<a href="#response-menu" class="nav-header<?php echo (isset($menu8_collapse) ? '' : ' collapsed'); ?>" data-toggle="collapse" title="<?php echo mswSpecialChars($adminPages[$adminPageSlots]['title']); ?>"><i class="fam-comments"></i> <?php echo mswCleanData($adminPages[$adminPageSlots]['title']); ?> <i class="icon-chevron-up"></i></a>
<ul id="response-menu" class="nav nav-list collapse<?php echo (isset($menu8_collapse) ? ' in' : ''); ?>">
<?php

// Standard responses..
if (in_array('standard-responses',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=standard-responses" title="<?php echo mswSpecialChars($msg_adheader53); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader53); ?></a></li>
<?php
$adminPages[$adminPageSlots]['standard-responses'] = $msg_adheader53;
}

// Manage responses..
if (in_array('responseman',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=responseman" title="<?php echo mswSpecialChars($msg_adheader54); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader54); ?></a></li>
<?php
$adminPages[$adminPageSlots]['responseman'] = $msg_adheader54;
}

// Import responses..
if (in_array('standard-responses-import',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=standard-responses-import" title="<?php echo mswSpecialChars($msg_adheader60); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader60); ?></a></li>
<?php
$adminPages[$adminPageSlots]['standard-responses-import'] = $msg_adheader60;
}

?>
</ul>
<?php

}

//==================
// PRIORITY LEVELS
//==================

++$adminPageSlots;
$adminPages[$adminPageSlots]['title'] = $msg_adheader52;

if (in_array('levels',$userAccess) || $MSTEAM->id=='1') {	
?>
<a href="#levels-menu" class="nav-header<?php echo (isset($menu7_collapse) ? '' : ' collapsed'); ?>" data-toggle="collapse" title="<?php echo mswSpecialChars($adminPages[$adminPageSlots]['title']); ?>"><i class="fam-flag-blue"></i> <?php echo mswCleanData($adminPages[$adminPageSlots]['title']); ?> <i class="icon-chevron-up"></i></a>
<ul id="levels-menu" class="nav nav-list collapse<?php echo (isset($menu7_collapse) ? ' in' : ''); ?>">

<?php

// Add priority level..
if (in_array('levels',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=levels" title="<?php echo mswSpecialChars($msg_adheader50); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader50); ?></a></li>
<?php
$adminPages[$adminPageSlots]['levels'] = $msg_adheader50;
}

// Manage priority levels..
if (in_array('levelsman',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=levelsman" title="<?php echo mswSpecialChars($msg_adheader51); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader51); ?></a></li>
<?php
$adminPages[$adminPageSlots]['levelsman'] = $msg_adheader51;
}

?>
</ul>
<?php

}

//====================
// IMAP
//====================

++$adminPageSlots;
$adminPages[$adminPageSlots]['title'] = $msg_adheader24;

if (in_array('imap',$userAccess) || in_array('imapman',$userAccess) || in_array('imapfilter',$userAccess) || $MSTEAM->id=='1') {
?>
<a href="#imap-menu" class="nav-header<?php echo (isset($menu4_collapse) ? '' : ' collapsed'); ?>" data-toggle="collapse" title="<?php echo mswSpecialChars($adminPages[$adminPageSlots]['title']); ?>"><i class="fam-email"></i> <?php echo mswCleanData($adminPages[$adminPageSlots]['title']); ?> <i class="icon-chevron-up"></i></a>
<ul id="imap-menu" class="nav nav-list collapse<?php echo (isset($menu4_collapse) ? ' in' : ''); ?>">
<?php

// Add imap account..
if (in_array('imap',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=imap" title="<?php echo mswSpecialChars($msg_adheader39); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader39); ?></a></li>
<?php
$adminPages[$adminPageSlots]['imap'] = $msg_adheader39;
}

// Manage imap accounts..
if (in_array('imapman',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=imapman" title="<?php echo mswSpecialChars($msg_adheader40); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader40); ?></a></li>
<?php
$adminPages[$adminPageSlots]['imapman'] = $msg_adheader40;
}

// Imap spam filter..
if (in_array('imapfilter',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=imapfilter" title="<?php echo mswSpecialChars($msg_adheader62); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader62); ?></a></li>
<?php
$adminPages[$adminPageSlots]['imapfilter'] = $msg_adheader62;
}

?>
</ul>
<?php

}

//==============
// SETTINGS
//==============

++$adminPageSlots;
$adminPages[$adminPageSlots]['title'] = $msg_adheader37;

if ((in_array('tools',$userAccess) && USER_DEL_PRIV=='yes') || in_array('portal',$userAccess) ||
    in_array('log',$userAccess) || in_array('reports',$userAccess) || in_array('settings',$userAccess) || $MSTEAM->id=='1') {	
?>
<a href="#settings-menu" class="nav-header<?php echo (isset($menu9_collapse) ? '' : ' collapsed'); ?>" data-toggle="collapse" title="<?php echo mswSpecialChars($adminPages[$adminPageSlots]['title']); ?>"><i class="fam-cog"></i> <?php echo mswCleanData($adminPages[$adminPageSlots]['title']); ?> <i class="icon-chevron-up"></i></a>
<ul id="settings-menu" class="nav nav-list collapse<?php echo (isset($menu9_collapse) ? ' in' : ''); ?>">

<?php

// Settings..
if (in_array('settings',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=settings" title="<?php echo mswSpecialChars($msg_adheader2); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader2); ?></a></li>
<?php
$adminPages[$adminPageSlots]['settings'] = $msg_adheader2;
}

// Tools..
if (in_array('tools',$userAccess) || $MSTEAM->id=='1') {
if (USER_DEL_PRIV=='yes') {
?>
<li><a href="?p=tools" title="<?php echo mswSpecialChars($msg_adheader15); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader15); ?></a></li>
<?php
$adminPages[$adminPageSlots]['tools'] = $msg_adheader15;
}
}

// Reports..
if (in_array('reports',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=reports" title="<?php echo mswSpecialChars($msg_adheader34); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader34); ?></a></li>
<?php
$adminPages[$adminPageSlots]['reports'] = $msg_adheader34;
}

// Entry log..
if (in_array('log',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=log" title="<?php echo mswSpecialChars($msg_adheader20); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader20); ?></a></li>
<?php
$adminPages[$adminPageSlots]['log'] = $msg_adheader20;
}

// Database backup..
if (in_array('backup',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=backup" title="<?php echo mswSpecialChars($msg_adheader30); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader30); ?></a></li>
<?php
$adminPages[$adminPageSlots]['backup'] = $msg_adheader30;
}

?>
</ul>
<?php

}

//==================================
// FREQUENTLY ASKED QUESTIONS
//==================================

++$adminPageSlots;
$adminPages[$adminPageSlots]['title'] = $msg_adheader17;

if (in_array('faq-cat',$userAccess) || in_array('faq',$userAccess) || in_array('attach',$userAccess) || 
    in_array('attachman',$userAccess) || in_array('faqman',$userAccess) || in_array('faq-catman',$userAccess) || 
	in_array('faq-import',$userAccess) || $MSTEAM->id=='1') {
?>
<a href="#faq-menu" class="nav-header<?php echo (isset($menu6_collapse) ? '' : ' collapsed'); ?>" data-toggle="collapse" title="<?php echo mswSpecialChars($adminPages[$adminPageSlots]['title']); ?>"><i class="fam-book"></i> <?php echo mswCleanData($adminPages[$adminPageSlots]['title']); ?> <i class="icon-chevron-up"></i></a>
<ul id="faq-menu" class="nav nav-list collapse<?php echo (isset($menu6_collapse) ? ' in' : ''); ?>">
<?php

// Add FAQ category..
if (in_array('faq-cat',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=faq-cat" title="<?php echo mswSpecialChars($msg_adheader44); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader44); ?></a></li>
<?php
$adminPages[$adminPageSlots]['faq-cat'] = $msg_adheader44;
}

// Manage FAQ categories..
if (in_array('faq-catman',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=faq-catman" title="<?php echo mswSpecialChars($msg_adheader45); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader45); ?></a></li>
<?php
$adminPages[$adminPageSlots]['faq-catman'] = $msg_adheader45;
}

// Add FAQ question..
if (in_array('faq',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=faq" title="<?php echo str_replace('&amp;amp;','&amp;',mswSpecialChars($msg_adheader46)); ?>"><i class="fam-bullet-blue"></i><?php echo str_replace('&amp;amp;','&amp;',mswCleanData($msg_adheader46)); ?></a></li>
<?php
$adminPages[$adminPageSlots]['faq'] = $msg_adheader46;
}

// Manage FAQ questions..
if (in_array('faqman',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=faqman" title="<?php echo str_replace('&amp;amp;','&amp;',mswSpecialChars($msg_adheader47)); ?>"><i class="fam-bullet-blue"></i><?php echo str_replace('&amp;amp;','&amp;',mswCleanData($msg_adheader47)); ?></a></li>
<?php
$adminPages[$adminPageSlots]['faqman'] = $msg_adheader47;
}

// Import FAQ questions..
if (in_array('faq-import',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=faq-import" title="<?php echo mswSpecialChars($msg_adheader55); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader55); ?></a></li>
<?php
$adminPages[$adminPageSlots]['faq-import'] = $msg_adheader55;
}

// Add attachments..
if (in_array('attach',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=attachments" title="<?php echo mswSpecialChars($msg_adheader48); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader48); ?></a></li>
<?php
$adminPages[$adminPageSlots]['attach'] = $msg_adheader48;
}

// Manage attachments..
if (in_array('attachman',$userAccess) || $MSTEAM->id=='1') {
?>
<li><a href="?p=attachman" title="<?php echo mswSpecialChars($msg_adheader49); ?>"><i class="fam-bullet-blue"></i><?php echo mswCleanData($msg_adheader49); ?></a></li>
<?php
$adminPages[$adminPageSlots]['attachman'] = $msg_adheader49;
}

?>
</ul>
<?php

}

?>