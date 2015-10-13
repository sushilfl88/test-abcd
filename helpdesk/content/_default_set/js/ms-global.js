//===================================================
//
// Script: Maian Support
// Written by: David Ian Bennett
// E-Mail: support@maianscriptworld.co.uk
// Website: http://www.maianscriptworld.co.uk
// JS Functions
//
//==================================================

// Overlay spinner..
function msOverLaySpinner() {
  if (jQuery('#updateSpinner')) {
    jQuery('#updateSpinner').show();
  }
}

// Hide overlay spinner..
function msHideOverLaySpinner() {
  if (jQuery('#updateSpinner')) {
    jQuery('#updateSpinner').remove();
  }
}

// Show/hide boxes for new password option..
function newPass(type) {
  switch (type) {
    case 'yes':
	jQuery('#passArea').hide();
	jQuery('#login').hide();
    jQuery('#pass').show();
	jQuery('#cancel').show();
	if (jQuery('input[name="email"]').val()=='') {
	  jQuery('input[name="email"]').focus();
	}
	break;
	case 'no':
    jQuery('#login').show();
    jQuery('#pass').hide();
	jQuery('#cancel').hide();
	jQuery('#passArea').show();
	break;
  }
}

// BB Code Tag Handling
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

// Field check..
function ms_fieldCheck(field,tab,action) {
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
	}
	// Additional..
	switch (action) {
	  case 'profile':
	  if (jQuery('input[name="email"]').val()!='') {
	    if (jQuery('input[name="email2"]').val()=='') {
		  if (tab) {
	        jQuery('#'+tab+' a[href="#two"]').tab('show');
		  }
		  jQuery('input[name="email2"]').focus();
		  return false;
		}
	  }
	  if (jQuery('input[name="email2"]').val()!='') {
	    if (jQuery('input[name="email"]').val()=='') {
		  if (tab) {
	        jQuery('#'+tab+' a[href="#two"]').tab('show');
		  }
		  jQuery('input[name="email"]').focus();
		  return false;
		}
	  }
	  if (jQuery('input[name="curpass"]').val()!='') {
	    if (jQuery('input[name="newpass"]').val()=='') {
		  if (tab) {
	        jQuery('#'+tab+' a[href="#three"]').tab('show');
		  }
		  jQuery('input[name="newpass"]').focus();
		  return false;
		}
		if (jQuery('input[name="newpass2"]').val()=='') {
		  if (tab) {
	        jQuery('#'+tab+' a[href="#three"]').tab('show');
		  }
		  jQuery('input[name="newpass2"]').focus();
		  return false;
		}
		if (jQuery('input[name="newpass"]').val()!=jQuery('input[name="newpass2"]').val()) {
		  if (tab) {
	        jQuery('#'+tab+' a[href="#three"]').tab('show');
		  }
		  jQuery('input[name="newpass2"]').focus();
		  return false;
		}
	  }
	  break;
	}
  } else {
    for (var i=0; i<fields.length; i++) {
	  if (jQuery('input[name="'+fields[i]+'"]')) {
	    if (jQuery('input[name="'+fields[i]+'"]').val()=='') {
          if (tab) {
	        jQuery('#'+tab+' a:first').tab('show');
		  }
		  jQuery('input[name="'+fields[i]+'"]').focus();
		  return false;
        }
	  }
	  if (jQuery('textarea[name="'+fields[i]+'"]')) {
	    if (jQuery('textarea[name="'+fields[i]+'"]').val()=='') {
          if (tab) {
	        jQuery('#'+tab+' a:first').tab('show');
		  }
		  jQuery('textarea[name="'+fields[i]+'"]').focus();
	      return false;
        }
	  }
	}
  }
  if (action) {
    ms_FormHandler(action,tab); 
  } else {
    return true;
  }
  return false;
}

// Update password..
function ms_updatePass() {
  if (jQuery('#upass').val()=='') {
    jQuery('#upass').focus();
    return false;
  }
  jQuery(document).ready(function() {
    jQuery.post('index.php?p=portal', { 
      pass: jQuery('#upass').val() 
    }, 
    function(data) {
      var string = data.split('#####');
      if (string[0]=='error') {
        jQuery('#upass').after('<span class="error" id="eError">'+string[1]+'</span>').show('slow');
      } else {
        alert(string[1]);
        jQuery('#upass').val('');
        jQuery('#passArea').hide('slow');
        jQuery('#mainDisplay').show('slow');
      }
    }); 
  }); 
  return false; 
}

// Update email.
function ms_updateEmail() {
  if (jQuery('#uemail').val()=='') {
    jQuery('#uemail').focus();
    return false;
  }
  jQuery(document).ready(function() {
    jQuery.post('index.php?p=portal', { 
      portemail: jQuery('#uemail').val() 
    }, 
    function(data) {
      var string = data.split('#####');
      if (string[0]=='error') {
        jQuery('#uemail').after('<span class="error" id="eError">'+string[1]+'</span>').show('slow');
      } else {
        alert(string[1]);
        jQuery('#logged_in_email').html(jQuery('#uemail').val());
        jQuery('#uemail').val('');
        jQuery('#emailArea').hide('slow');
        jQuery('#mainDisplay').show('slow');
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
	  jQuery('.attachBox input[type="file"]').last().val('');
	  var howmany = jQuery('.attachBox').length;
	  if (howmany==max) {
	    jQuery('#addbox').hide();
	  }
    } else {
	}
    break;
    case 'remove':
    var n = jQuery('.attachBox').length;
    if (n>1) {
      jQuery('.attachBox').last().remove();
    }
	// Was the add box hidden? If so, show it..
	if (jQuery('#addbox').css('display')=='none') {
	  jQuery('#addbox').show();
	}
    break;
  }
}

// Scroll to reply..
function scrollToArea(divArea) {
  jQuery('html,body').animate({
    scrollTop: jQuery('#'+divArea).offset().top
  }, 2000);
}

// Toggle for new password..
function toggleBoxes(status) {
  switch (status) {
    case 'on':
    jQuery('#viewTickets').hide();
    jQuery('#pbox').hide();
    jQuery('#newPass').show('slow');
    jQuery('#email').focus();
    break;
    case 'off':
    jQuery('#newPass').hide();
    jQuery('#viewTickets').show('slow');
    jQuery('#eError').hide();
    jQuery('#pError').hide();
    jQuery('#pbox').show('slow');
    break;
  }
}

function msErrFlag(id,text) {
  if (jQuery('#cusErrMsg_'+id)) {
    jQuery('#cusErrMsg_'+id).remove();
  }
  return '<span class="eFormErr" id="cusErrMsg_'+id+'"><i class="icon-arrow-up"></i> '+text+'</span>';
}

function msErrDisplayMechanism(data) {
  var fields = data.split(',');
  if (fields.length=='1') {
    var f   = fields[0].split('|');
	var txt = jQuery('#'+f[2]).html();
	if (f[0]=='cus') {
	  jQuery('#cus_fld_'+f[1]).append(msErrFlag(f[1],txt));
	} else {
	  switch (f[1]) {
		case 'attach':
		jQuery('p[class="attachlinks"]').append('<br><br>'+msErrFlag(f[1],txt));
		break;
		case 'recaptcha_response_field':
		jQuery('#recaptcha_wrapper').after(msErrFlag(f[1],txt));
		// Issue refresh and clear field..
		jQuery('input[name="recaptcha_response_field"]').val('');
		Recaptcha.reload();
		break;
		default:
		jQuery(f[0]+'[name="'+f[1]+'"]').after(msErrFlag(f[1],txt));
		break;
      }
	}
  } else {
    for (var i=0; i<fields.length; i++) {
	  var f   = fields[i].split('|');
	  var txt = jQuery('#'+f[2]).html();
	  if (f[0]=='cus') {
	    jQuery('#cus_fld_'+f[1]).append(msErrFlag(f[1],txt));
	  } else {
	    switch (f[1]) {
		  case 'attach':
		  jQuery('p[class="attachlinks"]').append('<br><br>'+msErrFlag(f[1],txt));
		  break;
		  case 'recaptcha_response_field':
		  jQuery('#recaptcha_wrapper').after(msErrFlag(f[1],txt));
		  // Issue refresh and clear field..
		  jQuery('input[name="recaptcha_response_field"]').val('');
		  Recaptcha.reload();
		  break;
		  default:
		  jQuery(f[0]+'[name="'+f[1]+'"]').after(msErrFlag(f[1],txt));
		  break;
		}
	  }
    }
  }
}

function msErrClear(id,type) {
  var fields = id.split(',');
  var action = (type=='hide' || type==undefined ? 'hide' : 'slide');
  if (fields.length=='1') {
    if (id=='attach') {
	  jQuery('#cusErrMsg_'+id).hide();
	} else {
	  if (jQuery('#cusErrMsg_'+id)) {
	    if (action=='hide') {
	      jQuery('#cusErrMsg_'+id).remove();
	    } else {
          jQuery('#cusErrMsg_'+id).slideUp();
	    }
      }
	}
  } else {
    for (var i=0; i<fields.length; i++) {
	  if (jQuery('#cusErrMsg_'+fields[i])) {
        if (action=='hide') {
	      jQuery('#cusErrMsg_'+fields[i]).remove();
		} else {
		  jQuery('#cusErrMsg_'+fields[i]).slideUp();
		}
      }
	}
  }
}

function selectAllCustomBoxes(id,state) {
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

// Toggle search..
function mswToggleSearch() {
  if (jQuery('#sbox').css('display')=='none') {
    jQuery('#sbox').show();
	jQuery('#search-icon-button').attr('class','icon-remove');
	jQuery('div[class="well"]').css('margin-top','10px');
	if (jQuery('#sbox input').val()=='') {
	  jQuery('#sbox input').focus();
	}
  } else {
    jQuery('#sbox').slideUp();
	jQuery('#search-icon-button').attr('class','icon-search');
	jQuery('div[class="well"]').css('margin-top','15px');
  }
}

// Check search..
function checkSearch(field) {
  if (jQuery('input[name="'+field+'"]').val()=='') {
    jQuery('input[name="'+field+'"]').focus();
	return false;
  }
  return true;
}

// Add to faves..
function addBookmark(article,page) {
  if (window.sidebar) {
    window.sidebar.addPanel(article,page,"");
  } else if( document.all ) {
    window.external.AddFavorite(page,article);
  } else if( window.opera && window.print ) {
    alert('Your browser doesn`t support this feature, sorry. Use the standard bookmarking option in your browser.');
  } else {
    alert('Your browser doesn`t support this feature, sorry. Use the standard bookmarking option in your browser.');
  }
}

function clearNewPass2() {
  if (jQuery('input[name="newpass2"]').val()!='') {
    jQuery('input[name="newpass2"]').val('')
  }
  if (jQuery('#cusErrMsg_newpass')) {
    jQuery('#cusErrMsg_newpass').hide();
  }
}