//===================================================
//
// Script: Maian Support
// Written by: David Ian Bennett
// E-Mail: support@maianscriptworld.co.uk
// Website: http://www.maianscriptworld.co.uk
// Javascript Functions
//
//==================================================

function ms_checkDataExists(page,field,id) {
  var string = jQuery('input[name="'+field+'"]').val();
  jQuery('input[name="'+field+'"]').css('background-image','none');
  // Remove error div..
  if (jQuery('#errMsg')) {
    jQuery('#errMsg').remove();
  }
  jQuery(document).ready(function() {
   jQuery.post('index.php?p='+page, { 
     checkEntered: jQuery('input[name="'+field+'"]').val(),
	 currID: id
   }, 
   function(data) {
     if (data['response']=='exists') {
	   jQuery('input[name="'+field+'"]').css('background','url(templates/images/invalid.png) no-repeat 98% 50%');
	   jQuery('button[type="submit"]').prop('disabled',true);
	   // Remove error div..
       if (jQuery('#errMsg')) {
         jQuery('#errMsg').remove();
       }
	   // Show message..
	   if (data['message']) {
	     jQuery('input[name="'+field+'"]').after('<span id="errMsg">('+data['message']+')</span>');
	   }
	 } else {
	   // Remove error div..
       if (jQuery('#errMsg')) {
         jQuery('#errMsg').remove();
       }
	   jQuery('input[name="'+field+'"]').css('background','url(templates/images/accept.png) no-repeat 98% 50%'); 
	   jQuery('button[type="submit"]').prop('disabled',false);
	 }
   },'json'); 
  });  
  return false;
}

function ms_checkCount(area,button,spanid) {
  var cnt = 0;
  switch (area) {
    case 'assign':
	jQuery('.checkboxArea input[type="checkbox"]').each(function() {
      if (jQuery(this).prop('checked')) {
        ++cnt;
	  }
    });
	break;
	default:
	jQuery('.'+area+' td input[type="checkbox"]').each(function() {
      if (jQuery(this).prop('checked')) {
        ++cnt;
	  }
    });
	break;
  }	
  // Enable/disable button..
  if (cnt>0) {
    jQuery('#'+button).prop('disabled',false);
  } else {
    jQuery('#'+button).prop('disabled',true);
  }
  // Append count to button if applicable..
  if (spanid && jQuery('#'+spanid)) {
    jQuery('#'+spanid).html('('+cnt+')');
  }
}

function ms_enableDisable(obj,page,id) {
  // Current state..
  var curState = jQuery(obj).attr('class');
  jQuery(document).ready(function() {
   jQuery.ajax({
    url: 'index.php',
    data: 'p='+page+'&id='+id+'&changeState='+curState,
    dataType: 'json',
    success: function (data) {
	  switch (curState) {
	    case 'icon-flag':
		jQuery(obj).attr('class','icon-flag-alt');
		break;
		case 'icon-flag-alt':
		jQuery(obj).attr('class','icon-flag');
		break;
	  }
    }
   });
  });
  return false;
}

function msKeyCodeEvent(e) {
  var unicode = (e.keyCode ? e.keyCode : e.charCode);
  return unicode;
}

function sysLoginEvent() {
  var message = '';
  if (jQuery('#user').val()=='' || jQuery('#pass').val()=='') {
    if (jQuery('#user').val()=='') {
	  jQuery('#user').focus();
	} else {
	  jQuery('#user').focus();
	}
  } else {
    jQuery('#form').submit();
  }
}

function ms_chevronUpDown(id) {
  var status = jQuery('#'+id).attr('class');
  switch (status) {
    case 'icon-chevron-up':
	jQuery('#'+id).attr('class','icon-chevron-down');
	break;
	case 'icon-chevron-down':
	jQuery('#'+id).attr('class','icon-chevron-up');
	break;
  }
}

function getKeyCode(e) {
  var unicode = (e.keyCode ? e.keyCode : e.charCode);
  return unicode;
}

function ms_addTags(tags,type,text,box) {
  switch (type) {
    // Bold, italic & underline..
    case 'bold':
    case 'italic':
    case 'underline':
    ms_insertAtCursor(box,tags);
    break;
    // Other..
    case 'url':
    case 'img':
    case 'email':
    case 'youtube':
    case 'vimeo':
    alertify.prompt(text,function (e,str) {
     if (str!='' && str!='http://' && str!=null && str!=' ') {
       ms_insertAtCursor(box,'['+type+']'+str+'[/'+type+']');
     }
	},(type=='img' || type=='url' ? 'http://' : (type=='email' ? 'email@example.com' : '')));  
    break;
  }
}

// With thanks to Scott Klarr
// http://www.scottklarr.com
function ms_insertAtCursor(field,text) {
  var txtarea   = document.getElementById(field); 
  var scrollPos = txtarea.scrollTop; 
  var strPos    = 0; 
  var br        = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? 'ff' : (document.selection ? 'ie' : false));
  if (br=='ie') { 
    txtarea.focus(); 
    var range = document.selection.createRange(); 
    range.moveStart ('character', -txtarea.value.length); 
    strPos    = range.text.length; 
  }
  if (br=='ff') {
    strPos      = txtarea.selectionStart; 
  }
  var front     = (txtarea.value).substring(0,strPos); 
  var back      = (txtarea.value).substring(strPos,txtarea.value.length); 
  txtarea.value = front+text+back; 
  strPos        = strPos+text.length; 
  if (br=='ie') { 
    txtarea.focus(); 
    var range = document.selection.createRange(); 
    range.moveStart('character', -txtarea.value.length); 
    range.moveStart('character', strPos); 
    range.moveEnd('character', 0); 
    range.select();
  }
  if (br=='ff') { 
    txtarea.selectionStart = strPos; 
    txtarea.selectionEnd   = strPos; 
    txtarea.focus(); 
  } 
  txtarea.scrollTop = scrollPos;
}

function closeAttachments(id) {
  jQuery('#h2A').css({'background-image':'none','border-top':'none'});
  jQuery('#signal').html('[+]');
  jQuery('#h2A').attr('onclick','showFAQAttachments('+id+')');
  jQuery('#attach-display').slideUp('slow',function(){jQuery('#attach-display').html('')});
}

function showFAQAttachments(id) {
  jQuery('#h2A').css('background','#f6f6f6 url(templates/images/indicator.gif) no-repeat 95% 50%');
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'p=faq&loadAttachments='+id,
      dataType: 'html',
      success: function (data) {
        jQuery('#h2A').css({'background-image':'none','border-top':'1px dashed #d7d7d7'});
        jQuery('#signal').html('[-]');
        jQuery('#h2A').attr('onclick','closeAttachments('+id+')');
        jQuery('#attach-display').html(data);
        jQuery('#attach-display').slideDown('slow');
      }
    });
  });
  return false;
}

function ms_autoComplete(box) {
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'p=portal&autoComplete='+box,
      dataType: 'html',
      success: function (data) {
        if (data!='none') {
          jQuery('#'+box).autocomplete({
		        source: data.split(',')
	        });
        }
      }
    });
  });
  return false;   
}

function ms_insertMailBox(value,field,select) {
  jQuery('input[name="'+field+'"]').val(value);
  jQuery('input[name="'+field+'"]').show();
  jQuery('select[class="'+select+'"]').hide();
  jQuery('#'+field+'_area').show();
  jQuery('#'+field).show();
}

function ms_folderCheck() {
  if (jQuery('input[name="im_host"]').val()=='') {
    jQuery('input[name="im_host"]').focus();
	return false;
  } else if (jQuery('input[name="im_user"]').val()=='') {
    jQuery('input[name="im_user"]').focus();
    return false;
  } else if (jQuery('input[name="im_pass"]').val()=='') {
    jQuery('input[name="im_pass"]').focus();
    return false;
  } else if (jQuery('input[name="im_port"]').val()=='') {
    jQuery('input[name="im_port"]').focus();
    return false;
  } else {
    return true;
  }
}

function ms_enPostPriv(id,user) {
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'p=view-ticket&id='+id+'&ppriv='+user,
      dataType: 'html',
      success: function (data) {
        var split = data.split('#####');
        switch (split[0]) {
          case 'yes':
          if (user>0) {
            jQuery('#ou_'+user).removeClass('user_no').addClass('user_yes');
          } else {
            jQuery('#oru').removeClass('user_no').addClass('user_yes');
          }
          break;
          case 'no':
          if (user>0) {
            jQuery('#ou_'+user).removeClass('user_yes').addClass('user_no');
          } else {
            jQuery('#oru').removeClass('user_yes').addClass('user_no');
          }
          break;
        }
        alert(split[1]);
      }
    });
  });
  return false;
}

function ms_generateAPIKey() {
  jQuery('#apiKey').css('background','url(templates/images/indicator.gif) no-repeat 98% 50%');
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'p=settings&genKey=yes',
      dataType: 'html',
      success: function (data) {
        jQuery('#apiKey').css('background-image','none');
        jQuery('#apiKey').val(data);
      }
    });
  });
  return false;
}

function checkUserEmail(txt) {
  if (!mswIsValidEmailAddress(jQuery('#email').val())) {
    alert(txt);
    return false;
  }
}

function mswIsValidEmailAddress(addy) {
  var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
  return pattern.test(addy);
}


function ms_removeDisputeUser(id) {
  if (id==2) {
    jQuery('#name_2').hide('slow').html('');
    jQuery('#on_1').show();
  } else {
    jQuery('#name_'+id).hide('slow').html('');
    jQuery('#on_'+parseInt(id-1)).show();
    jQuery('#off_'+parseInt(id-1)).show();
  }
  return false;
}

function ms_addDisputeUser(id) {
  jQuery('#on_'+id).hide();
  jQuery('#off_'+id).hide();
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'p=view-ticket&addDisputeBox='+id,
      dataType: 'html',
      success: function (data) {
        jQuery('#name_'+id).after(data).show('slow');
      }
    });
  });
  return false;   
}

function ms_textPreview(url,field,area) {
  if (jQuery('#'+field).val()=='') {
    jQuery('#'+field).focus();
    return false;
  }
  jQuery('#'+field).css('background','url(templates/images/loading.gif) no-repeat 50% 50%');
  jQuery(document).ready(function() {
    jQuery.post('index.php?p='+url+'&previewMsg=yes', { 
      msg : jQuery('#'+field).val() 
    }, 
    function(data) {
      jQuery('#'+field).css('background-image','none');
      jQuery('#'+area).html(data);
	  jQuery('#'+field).hide();
	  jQuery('#prev').hide();
	  jQuery('#clse').show();
	  jQuery('#'+area).fadeIn(500);
    },'html'); 
  });  
  return false
}

function ms_closePreview(field,area) {
  jQuery('#'+area).html('').hide();
  jQuery('#clse').hide();
  jQuery('#'+field).show();
  jQuery('#prev').show()
}

// Read xml..
function xmlTag(xml,tag) {
  return jQuery(xml).find(tag).text();
}

// Auto pass..
function ms_passGenerator(label,field) {
  jQuery('input[name="'+field+'"]').css('background','url(templates/images/indicator.gif) no-repeat 95% 50%');
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'ajax=passgen',
      dataType: 'json',
      success: function (data) {
	    jQuery('input[name="'+field+'"]').css('background-image','none');
		jQuery('#'+label+' span').remove();
	    jQuery('#'+label).append('<span style="padding-left:20px" class="highlightPass">'+data['pass']+'</span>');
		jQuery('input[name="'+field+'"]').val(data['pass']);
      }
    });
  });
  return false;   
}

// Add/remove attachment boxes..
function ms_attachBox(type,max) {
  switch (type) {
    case 'add':
    var n = jQuery('.attachBox').length;
    if (n<max) {
      jQuery('.attachBox').last().after(jQuery('.attachBox').last().clone());
	  jQuery('.attachBox input').last().val('');
    }
    break;
    case 'remove':
    var n = jQuery('.attachBox').length;
    if (n>1) {
      jQuery('.attachBox').last().remove();
    }
    break;
  }
}

// Clone area
function ms_cloneArea(op,max,div,fields) {
  var html  = jQuery(div).last().html();
  var n     = jQuery(div).length;
  var cldv  = div.substring(1);
  switch (op) {
    case 'add':
	if (max>0 && parseInt(n)+parseInt(1)>max) {
	  alertify.set({delay:10000});
      alertify.error('Free Version Restriction');
	  return false;
	}
	jQuery(div).last().after('<div class="'+cldv+'">'+html+'</div>');
	if (fields) {
	  var f = fields.split(',');
	  for (var i=0; i<f.length; i++) {
	    jQuery('input[name="'+f[i]+'"]').last().val('');
	  }
	}
	break;
	case 'remove':
	if (n>1) {
	  jQuery(div).last().remove();
	}
	break;
  }
}

function addResponseBox(txt,txt2,txt3) {
  if (jQuery('textarea[name="comments"]').val()=='') {
    jQuery('textarea[name="comments"]').focus();
    return false;
  }
  if (jQuery('select[name="response"]').val()=='yes') {
    alertify.prompt(txt,function (e,str) {
	  if (str!=null && str!='') {
        jQuery('input[name="response_title"]').val(str);
		addReply(txt3);
      }
	},txt2);
	return false;
  }
  addReply(txt3);
}

// Check questions..
function checkBoxes(checked,field) {
  if (checked) {
    jQuery(field+' input:checkbox').prop('checked',true);
  } else {
    jQuery(field+' input:checkbox').prop('checked',false);
  }
}

// Departments/pages..
function selectAllBoxes(which,status) {
  switch (which) {
    case 'dept':
    switch (status) {
      case 'on':
      jQuery("#deptboxes input:checkbox").each(function() {
        jQuery(this).prop('checked',true);
      });
      break;
      case 'off':
      jQuery("#deptboxes input:checkbox").each(function() {
        jQuery(this).prop('checked',false);
      });
      break;
    }
    break;
    case 'pages':
    switch (status) {
      case 'on':
      jQuery("#pageboxes input:checkbox").each(function() {
        jQuery(this).prop('checked',true);
      });
      break;
      case 'off':
      jQuery("#pageboxes input:checkbox").each(function() {
        jQuery(this).prop('checked',false);
      });
      break;
    }
    break;
  }
}

// Search..
function mswDoSearch(url) {
  if (jQuery('input[name="keys"]').val()=='') {
    jQuery('input[name="keys"]').focus();
	return false;
  }
  window.location='?p='+url+'&keys='+jQuery('input[name="keys"]').val();
}

// Check/uncheck array of checkboxes..
function selectAll(which,status) {
  jQuery("#"+which+" input:checkbox").each(function() {
    jQuery(this).prop('checked',(status=='on' ? true : false));
  });
}

// Uncheck box..
function ms_uncheck(box) {
  jQuery('#'+box).prop('checked',false);
}

// Uncheck range..
function ms_checkRange(action,chkclass) {
  jQuery('.'+chkclass+' input[type="checkbox"]').each(function(){
    jQuery(this).prop('checked',(action ? true : false));
  });
}

// Jump to area and wait, then show something..
function mswJumpWait(divarea,showarea) {
  ms_scrollToArea(divarea);
  setTimeout(function() {
   jQuery('#'+showarea).slideDown();
  }, 2000);
}

// Scroll to..
function ms_scrollToArea(divArea) {
  jQuery('html, body').animate({
    scrollTop: jQuery('#'+divArea).offset().top
  }, 2000);
}

// Select custom boxes..
function ms_selectAllCustomBoxes(id,state) {
  switch (state) {
    case 'on':
    jQuery("#"+id+" input:checkbox").each(function() {
      jQuery(this).attr('checked', 'checked');
    });
    break;
    case 'off':
    jQuery("#"+id+" input:checkbox").each(function() {
      jQuery(this).removeAttr('checked');
    });
    break;
  }
}

// Confirm message..
function ms_confirmActionMessage(obj,txt) {
  var url = jQuery(obj).attr('href');
  alertify.confirm(txt, function (e) {
   if (e) {
    window.location = url;
   } else {
    return false;
   }
  });
}

// Overlay spinner..
function msOverLaySpinner() {
  if (jQuery('#updateSpinner')) {
    jQuery('#updateSpinner').show();
  }
}

// Confirm message..
function ms_confirmButtonAction(form,txt,field,value) {
  if (field=='export-search') {
    jQuery('#'+form).append('<input type="hidden" name="'+field+'" value="'+(value ? value : 'yes')+'">');
    jQuery('#'+form).submit();
  } else {
    alertify.confirm(txt, function (e) {
     if (e) {
      // Append delete trigger and execute..
      jQuery('#'+form).append('<input type="hidden" name="'+field+'" value="'+(value ? value : 'yes')+'">');
      jQuery('#'+form).submit();
	  // Load spinner..
	  msOverLaySpinner();
     } else {
      return false;
     }
    });
  }
}

// Field check..
function ms_fieldCheck(field,tab) {
  if (field!='none') {
    var fields = field.split(',');
    if (fields.length=='1') {
      if (jQuery('input[name="'+field+'"]')) {
	    if (jQuery('input[name="'+field+'"]').val()=='') {
	      if (tab) {
	        jQuery('#'+tab+' a:first').tab('show');
		  }
		  jQuery('input[name="'+field+'"]').focus();
	      return false;
        }
	    if (jQuery('textarea[name="'+field+'"]').val()=='') {
          if (tab) {
	        jQuery('#'+tab+' a:first').tab('show');
		  }
		  jQuery('textarea[name="'+field+'"]').focus();
	      return false;
        }
		if (jQuery('select[name="'+field+'"]').val()=='0') {
          if (tab) {
	        jQuery('#'+tab+' a:first').tab('show');
		  }
		  jQuery('select[name="'+field+'"]').focus();
	      return false;
        }
	  }
    } else {
      for (var i=0; i<fields.length; i++) {
	    if (jQuery('input[name="'+fields[i]+'"]')) {
	      if (jQuery('input[name="'+fields[i]+'"]').val()=='') {
            if (tab) {
	          switch (tab) {
			    case 'tabAreaAdd':
			    switch (fields[i]) {
			      case 'subject':
				  jQuery('#'+tab+' a:first').tab('show');
				  break;
				  case 'comments':
				  jQuery('#'+tab+' a:first').tab('show');
				  break;
				  case 'name':
				  jQuery('#'+tab+' a[href="#two"]').tab('show');
				  break;
				  case 'email':
				  jQuery('#'+tab+' a[href="#two"]').tab('show');
				  break;
			    }
			    break;
			    default:
			    jQuery('#'+tab+' a:first').tab('show');
			    break;
			  }
		    }
		    jQuery('input[name="'+fields[i]+'"]').focus();
		    return false;
          }
	    }
	    if (jQuery('textarea[name="'+fields[i]+'"]')) {
	      if (jQuery('textarea[name="'+fields[i]+'"]').val()=='') {
            if (tab) {
		      switch (tab) {
			    case 'tabAreaAdd':
			    break;
			    default:
			    jQuery('#'+tab+' a:first').tab('show');
			    break;
			  }
		    }
		    jQuery('textarea[name="'+fields[i]+'"]').focus();
	        return false;
          }
	    }
		if (jQuery('select[name="'+fields[i]+'"]')) {
	      if (jQuery('select[name="'+fields[i]+'"]').val()=='0') {
            if (tab) {
		      switch (tab) {
			    case 'tabAreaAdd':
			    break;
			    default:
			    jQuery('#'+tab+' a:first').tab('show');
			    break;
			  }
		    }
		    jQuery('select[name="'+fields[i]+'"]').focus();
	        return false;
          }
	    }
		if (fields[i]=='staffmailboxes') {
		  chkcnt = 0;
		  jQuery('.mailStaff input[name="staff[]"]:checked').each(function(){
		    ++chkcnt;
          });
		  if (chkcnt==0) {
		    jQuery('.mailStaff').removeClass('mailStaff').addClass('mailStaff_Err');
		    return false;
		  }
		}
	  }
    }
  }
  // Load spinner if set..
  msOverLaySpinner();
  return true;
}

// Clear mailbox staff error..
function clearMailBoxStaffErr() {
  if (jQuery('.mailStaff_Err')) {
    jQuery('.mailStaff_Err').removeClass('mailStaff_Err').addClass('mailStaff');
  }
}

// Confirm action and execute..
function confirmMessageExecute(txt,action,values) {
  alertify.confirm(txt, function (e) {
   if (e) {
    switch (action) {
	  case 'history':
	  var chop = values.split('##');
	  mswRemoveHistory(chop[0],chop[1]);
	  break;
	}
   } else {
    return false;
   }
  });
}

// Confirm message..
function confirmMessage(txt) {
  alertify.confirm(txt, function (e) {
   if (e) {
    return true;
   } else {
    return false;
   }
  });
}

// Toggle..
function mswToggle(div1,div2,field,area) {
  if (jQuery('#'+div2).css('display')=='none') {
    if (jQuery('#'+div1)) {
	  jQuery('#'+div1).hide();
	}
	jQuery('#'+div2).show();
	if (jQuery('input[name="'+field+'"]').val()=='') {
	  jQuery('input[name="'+field+'"]').focus();
	}
	switch (field) {
	  case 'keys':
	  jQuery('#search-icon-button').attr('class','icon-remove');
	  if (area=='mailbox') {
	    jQuery('div[class="container-fluid"]').css('margin-top','0');
	  }
	  break;
	}
  } else {
    if (jQuery('#'+div1)) {
      jQuery('#'+div1).show();
	}
	jQuery('#'+div2).hide();
	switch (field) {
	  case 'keys':
	  jQuery('#search-icon-button').attr('class','icon-search');
	  if (area=='mailbox') {
	    jQuery('div[class="container-fluid"]').css('margin-top','20px');
	  }
	  break;
	}
  }
}

// Version check..
function ms_versionCheck() {
  jQuery(document).ready(function(){
   jQuery.ajax({
    url:'index.php',
	data: 'p=vc&vck=yes',
	dataType:'json',
	success: function (data) {
	  jQuery('#vc-area').html(data['html']);
	}
   });
  });
  return false;
}

// Check digit input..
function ms_digitInput(input) {
  return '';
}

// ID show/hide..
function ms_divHideShow(show,hide) {
  jQuery('#'+show).show();
  jQuery('#'+hide).hide();
}

// Window location..
function ms_windowLoc(url) {
  switch(url) {
    case 'backwards':
	window.history.back();
	break;
	default:
	window.location = url;
	break;
  }
}