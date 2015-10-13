//===================================================
//
// Script: Maian Support
// Written by: David Ian Bennett
// E-Mail: support@maianscriptworld.co.uk
// Website: http://www.maianscriptworld.co.uk
// System Ops
//
// Incorporating jQuery functions
// Copyright (c) John Resig
// http://jquery.com/
//
//==================================================

function addTicket()  {
  jQuery(document).ready(function() {
   if (jQuery('input[name="subject"]').val()=='') {
     jQuery('input[name="subject"]').focus();
	 return false;
   }
   if (jQuery('textarea[name="comments"]').val()=='') {
     jQuery('textarea[name="comments"]').focus();
	 return false;
   }
   if (jQuery('input[name="name"]').val()=='') {
     jQuery('#tabAreaAdd a[href="#two"]').tab('show');
     jQuery('input[name="name"]').focus();
	 return false;
   }
   if (jQuery('input[name="email"]').val()=='') {
     jQuery('#tabAreaAdd a[href="#two"]').tab('show');
     jQuery('input[name="email"]').focus();
	 return false;
   }
   if (jQuery('#navbar')) {
     jQuery('html,body').animate({
      scrollTop: jQuery('#navbar').offset().top
     },750, 'swing', function(){
	   msOverLaySpinner();
	   setTimeout(function() {
        jQuery('#formfield').submit();
       }, 1500);
	 });
   } else {
     setTimeout(function() {
       jQuery('#formfield').submit();
     }, 1500);
   }
  }); 
}

function addReply()  {
  jQuery(document).ready(function() {
   if (jQuery('textarea[name="comments"]').val()=='') {
     jQuery('textarea[name="comments"]').focus();
	 return false;
   }
   if (jQuery('#navbar')) {
     jQuery('html,body').animate({
      scrollTop: jQuery('#navbar').offset().top
     },750, 'swing', function(){
	   msOverLaySpinner();
	   setTimeout(function() {
        jQuery('#formfield').submit();
       }, 1500);
	 });
   } else {
     setTimeout(function() {
       jQuery('#formfield').submit();
     }, 1500);
   }
  });
}

function deptLoader(dept,posted) {
  if (jQuery('select[name="dept"]').val()=='0') {
    return false;
  }
  jQuery(document).ready(function() {
    if (jQuery('#dep_label img')) {
	  jQuery('#dep_label').append('<img style="margin-left:50px" src="content/'+msTemplatePath+'/images/indicator.gif">');
	}
	jQuery.ajax({
      url: 'index.php',
      data: 'ajax=dept&dp='+dept+'&pst='+posted,
      dataType: 'json',
      success: function (data) {
        // Subject..
        if (data['subject']) {
          if (jQuery('input[name="subject"]').val()=='') {
		    jQuery('input[name="subject"]').val(data['subject']);
			msErrClear('subject');
		  }
        }
        // Comments..
        if (data['comments']) {
          if (jQuery('textarea[name="comments"]').val()=='') {
		    jQuery('textarea[name="comments"]').val(data['comments']);
			msErrClear('comments');
		  }
        }
        // Custom fields..
        if (data['fields']) {
          jQuery('#customFieldsArea').html(data['fields']);
		  jQuery('#customFieldsArea').fadeIn(500);
        } else {
		  jQuery('#customFieldsArea').hide();
		}
		if (jQuery('#dep_label img')) {
		  jQuery('#dep_label img').remove();
		}
      }
    });
  });
  return false;   
}

function ms_showImapFolders(field,select) {
  jQuery('input[name="'+field+'"]').css('background','url(templates/images/indicator.gif) no-repeat 99% 50%');
  jQuery(document).ready(function() {
    jQuery.post('index.php?p=imap&showImapFolders=yes', { 
      host: jQuery('input[name="im_host"]').val(),
      user: jQuery('input[name="im_user"]').val(),
      pass: jQuery('input[name="im_pass"]').val(),
      port: jQuery('input[name="im_port"]').val(),
      flags: jQuery('input[name="im_flags"]').val()
    }, 
    function(data) {
	  jQuery('input[name="'+field+'"]').css('background-image','none');
      switch(data['action']) {
	    case 'ok':
		jQuery('select[class="'+select+'"]').html(data['html']);
		jQuery('input[name="'+field+'"]').hide();
		jQuery('#'+field+'_area').hide();
		jQuery('#'+field).hide();
		jQuery('select[class="'+select+'"]').show();
		break;
		default:
		alertify.set({delay:10000});
		alertify.error(data['msg']);
		break;
	  }
    },'json'); 
  });  
  return false
}

function selectDisputeAccount(val,tab,tabID) {
  if (val) {
    var html = val.split('######');
	jQuery('#'+tab+' input[name="name['+tabID+']"]').val(html[0]);
    jQuery('#'+tab+' input[name="email['+tabID+']"]').val(html[1]);
    jQuery('#acc-search-'+tab).remove();
	jQuery('#closebutton_'+tab).remove();
  }
  return false;
}

function searchDisputeAccount(field,tab,ticket,tabID) {
  if (jQuery('#'+tab+' input[name="'+field+'['+tabID+']"]').val()=='') {
    jQuery('#'+tab+' input[name="'+field+'['+tabID+']"]').focus();
	return false;
  }
  jQuery('#'+tab+' input[name="'+field+'['+tabID+']"]').css('background','url(templates/images/indicator.gif) no-repeat 98% 50%');
  jQuery(document).ready(function() {
   jQuery.ajax({
    url: 'index.php',
    data: 'ajax=dispute-users&term='+jQuery('#'+tab+' input[name="'+field+'['+tabID+']"]').val()+'&field='+field+'&id='+ticket,
    dataType: 'json',
    success: function (data) {
	  jQuery('#'+tab+' input[name="'+field+'['+tabID+']"]').css('background-image','none');
	  if (jQuery('#acc-search-'+tab)) {
	    jQuery('#acc-search-'+tab).remove();
	  }
	  if (jQuery('#closebutton_'+tab)) {
	    jQuery('#closebutton_'+tab).remove();
	  }
	  if (data.length>0) {
	    var html = '<select onclick="selectDisputeAccount(this.value,\''+tab+'\',\''+tabID+'\')" id="acc-search-'+tab+'"><option value="">- - - - -</option>';
	    for (var i=0; i<data.length; i++) {
	      html += '<option value="'+data[i]['name']+'######'+data[i]['email']+'">'+data[i]['name']+'</option>';
	    }
	    html += '</select> <i class="icon-remove" id="closebutton_'+tab+'" style="cursor:pointer" onclick="jQuery(\'#acc-search-'+tab+'\').remove();jQuery(this).remove()"></i>';
		switch (field) {
		  case 'name':
		  jQuery('#'+tab+' div[class="input-append"]:first').after('&nbsp;&nbsp;'+html);
		  break;
		  case 'email':
		  jQuery('#'+tab+' div[class="input-append"]:last').after('&nbsp;&nbsp;'+html);
		  break;
		}
	  } else {
	    alertify.error(data['text']);
	  }
    }
   });
  });
  return false;
}

function selectAccount(val,id) {
  if (val) {
    var html = val.split('######');
	if (id>0) {
	  jQuery('input[name="dest_email"]').val(html[1]);
	} else {
      jQuery('input[name="name"]').val(html[0]);
      jQuery('input[name="email"]').val(html[1]);
	}
    jQuery('#acc-search').remove();
	jQuery('#closebutton').remove();
  }
  return false;
}

function searchAccounts(text,field,area,id) {
  // Are we moving tickets?
  if (id>0) {
    var field = 'dest_email';
  }
  if (jQuery('input[name="'+field+'"]').val()=='') {
    jQuery('input[name="'+field+'"]').focus();
	return false;
  } 
  jQuery('input[name="'+field+'"]').css('background','url(templates/images/indicator.gif) no-repeat 98% 50%');
  jQuery(document).ready(function() {
   jQuery.ajax({
    url: 'index.php',
    data: 'ajax=account-search&term='+jQuery('input[name="'+field+'"]').val()+'&field='+field+'&id='+id,
    dataType: 'json',
    success: function (data) {
	  jQuery('input[name="'+field+'"]').css('background-image','none');
	  if (jQuery('#acc-search')) {
	    jQuery('#acc-search').remove();
	  }
	  if (jQuery('#closebutton')) {
	    jQuery('#closebutton').remove();
	  }
	  if (data.length>0) {
	    var html = '<select onclick="selectAccount(this.value,'+id+')" id="acc-search"><option value="">- - - - -</option>';
	    for (var i=0; i<data.length; i++) {
	      html += '<option value="'+data[i]['name']+'######'+data[i]['email']+'">'+data[i]['name']+'</option>';
	    }
	    html += '</select> <i class="icon-remove" id="closebutton" style="cursor:pointer" onclick="jQuery(\'#acc-search\').remove();jQuery(this).remove()"></i>';
	    jQuery('#'+area).after('&nbsp;&nbsp;'+html);
	  } else {
	    alertify.error(text);
	  }
    }
   });
  });
  return false;
}

function deleteOps(type,id,message) {
  alertify.confirm(message, function (e) {
   if (e) {
    jQuery(document).ready(function() {
     switch (type) {
	   case 'reply':
	   jQuery('#reply-'+id+' .block-body').css('background','url(templates/images/loading.gif) no-repeat 50% 50%');
	   break;
	 }
	 jQuery.ajax({
      url: 'index.php',
      data: 'ajax=delete&id='+id+'&type='+type,
      dataType: 'json',
      success: function (data) {
		switch (type) {
		  case 'reply':
		  jQuery('#reply-'+id+' .block-body').css('background-image','none');
	      break;
		}
		if (data['msg']=='ok') {
		  jQuery('#'+type+'-'+id).slideUp();
		}
      }
     });
    });
    return true;
   } else {
    return false;
   }
  });
}

function ms_deleteAttachments(ticket,reply,txt) {
  // Was anything check. Close div if nothing was checked..
  if (!jQuery('input[name="att'+ticket+'_'+reply+'[]"]:checked').val()) {
    jQuery('#attachments_'+ticket+'_'+reply).slideUp();
    return false;
  }
  alertify.confirm(txt, function (e) {
   if (e) { 
    jQuery(document).ready(function() {
     jQuery('#but_ar_'+ticket+'_'+reply).css('background','url(templates/images/indicator.gif) no-repeat 95% 50%');
     jQuery.post('index.php?ajax=del-attach&t='+ticket+'&r='+reply, { 
      attachments: jQuery('input[name="att'+ticket+'_'+reply+'[]"]').serializeArray()
     }, 
     function(data) {
	   jQuery('#but_ar_'+ticket+'_'+reply).css('background-image','none');
	   if (data['ids']!='err') {
	     if (data['ids']!='none') {
	       // If count is 0, no attachments are left, so close attachments area..
           // If count is greater than 1, just close deleted attachments..
	       if (data['count']==0) {
		     jQuery('#attachments_'+ticket+'_'+reply).slideUp('slow',function(){jQuery('#attachments_'+ticket+'_'+reply).remove()});
	       } else {
	         var ids = data['ids'].split(',');
		     for (var i=0; i<ids.length; i++) {
		       jQuery('#attrow'+ids[i]).hide('slow');
		     }
	       }
	       // Update attachments text..
	       jQuery('#link'+ticket+'_'+reply).html(data['text']);
	     } else {
	       jQuery('#attachments_'+ticket+'_'+reply).slideUp();
	     }
	   }
     },'json'); 
    });
   } else {
    return false;
   }
  }); 
}

function ms_updateTicketNotes(ticket) {
  jQuery('#notes').css('background','url(templates/images/loading.gif) no-repeat 50% 50%');
  jQuery(document).ready(function() {
   jQuery.post('index.php?ajax=ticket-notes&ticketNotes='+ticket, { 
    notes: jQuery('#notes').val() 
   }, 
   function(data) {
     jQuery('#notes').css('background-image','none');
   },'json'); 
  });  
  return false;
}

function ms_updateAssignedUsers(ticket) {
  jQuery('#userAssignArea .block-body').css('background','url(templates/images/loading.gif) no-repeat 50% 50%');
  jQuery(document).ready(function() {
   jQuery.post('index.php?ajax=assign-staff&ticketAssigned='+ticket, { 
    assigned: jQuery('input[name="assigned[]"]').serializeArray()
   }, 
   function(data) {
     jQuery('#userAssignArea .block-body').css('background-image','none');
   },'json'); 
  });  
  return false;
}

function getStandardResponse() {
  jQuery('#comments').css('background','url(templates/images/loading.gif) no-repeat 50% 50%');
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'ajax=response&getResponse='+jQuery('#response').val(),
      dataType: 'json',
      success: function (data) {
        jQuery('#comments').css('background-image','none');
        jQuery('#comments').val(data['response']);
      }
    });
  });
  return false;
}

function mswRemoveHistory(id,ticket) {
  jQuery('#historyArea').css('background','url(templates/images/loading.gif) no-repeat 95% 5%');
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'ajax=delete-history&id='+id+'&t='+ticket,
      dataType: 'json',
      success: function (data) {
        jQuery('#historyArea').css('background-image','none');
		var count = parseInt(jQuery('#hiscount').html());
		// Single entry..
		if (id>0) {
		  jQuery('#history_entry_'+id).slideUp();
		  jQuery('#hiscount').html(parseInt(count-1));
		  if (count==1) {
		    jQuery('#historyArea').html('<p class="nodata">'+data['text']+'</p>');
		    jQuery('#historyArea p').hide();
			setTimeout(function() {
              jQuery('#historyArea .nodata').slideDown();
		    }, 500);
		  }
		}
		// All entries..
		if (ticket>0) {
		  jQuery('#historyArea').html('<p class="nodata">'+data['text']+'</p>');
		  jQuery('#historyArea p').hide();
		  jQuery('#hiscount').html('0');
		  jQuery('#hisblockhead a').remove();
		  setTimeout(function() {
            jQuery('#historyArea .nodata').slideDown();
		  }, 500);
		}
      }
    });
  });
  return false;
}