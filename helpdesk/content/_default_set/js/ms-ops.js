//===================================================
//
// Script: Maian Support
// Written by: David Ian Bennett
// E-Mail: support@maianscriptworld.co.uk
// Website: http://www.maianscriptworld.co.uk
// JS Ops
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

function deptLoader(dept) {
  if (jQuery('select[name="dept"]').val()=='0') {
    return false;
  }
  jQuery(document).ready(function() {
    if (jQuery('#dep_label img')) {
	  jQuery('#dep_label').append('<img style="margin-left:50px" src="content/'+msTemplatePath+'/images/indicator.gif">');
	}
	jQuery.ajax({
      url: 'index.php',
      data: 'ajax=dept&dp='+dept,
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

function ms_vote(sel) {
  msOverLaySpinner();
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'v='+jQuery('input[name="id"]').val()+'&vote='+sel,
      dataType: 'json',
      success: function (data) {
	    msHideOverLaySpinner();
	    jQuery('#vote').html(data['response']);
      }
    });
  });
  return false;
}

function ms_FormHandler(action,tabarea) {
  jQuery(document).ready(function() {
   msOverLaySpinner();
   jQuery.ajax({
    type: 'POST',
    url: 'index.php?ajax='+action,
    data: jQuery("div[class='content'] > form").serialize(),
    cache: false,
    dataType: 'json',
    success: function (data) {
	  msHideOverLaySpinner();
	  switch (data['status']) {
	    case 'ok':
		jQuery('span[class="eMsg"]').remove();
		switch (data['field']) {
		  case 'redirect':
		  window.location = data['msg'];
		  break;
		  case 'suspended':
		  window.location = 'index.php';
		  break;
		  case 'msg':
		  jQuery('#form').before(data['msg']);
		  // Additional actions..
		  switch (action) {
		    case 'newpass':
			newPass('no');
			jQuery('input[name="pass"]').focus();
			break;
			case 'create':
			var clearfields = ['name','email','email2','recaptcha_response_field'];
			for (var i=0; i<clearfields.length; i++) {
			  jQuery('input[name="'+clearfields[i]+'"]').val('');
			}
			break;
		  }
		  break;
		}
		break;
		case 'err':
		switch (action) {
		  case 'create':
		  var flds = data['field'].split('|');
		  if (jQuery('#cusErrMsg_'+flds[1])) {
		    jQuery('#cusErrMsg_'+flds[1]).remove();
		  }
		  msErrDisplayMechanism(data['field']);
		  break;
		  default:
		  var flds = data['field'].split('|');
		  if (jQuery('#cusErrMsg_'+flds[1])) {
		    jQuery('#cusErrMsg_'+flds[1]).remove();
		  }
		  // Switch tab..
		  if (data['tab']!='' && tabarea) {
		    jQuery('#'+tabarea+' a[href="#'+data['tab']+'"]').tab('show');
		  }
		  jQuery('input[name="'+data['field']+'"]').after(msErrFlag(data['field'],data['msg']));
		  break;
		}  
		break;
	  }
    }
   }); 
  });  
  return false;
}

function ms_textPreview(field,area) {
  if (jQuery('textarea[name="'+field+'"]').val()=='') {
    jQuery('textarea[name="'+field+'"]').focus();
    return false;
  }
  jQuery(document).ready(function() {
    jQuery.post('index.php?ajax=previewMsg', { 
      msg: jQuery('textarea[name="'+field+'"]').val() 
    }, 
    function(data) {
      jQuery('#'+area).html(data['msg']);
	  jQuery('textarea[name="'+field+'"]').hide();
	  jQuery('#prev').hide();
	  jQuery('#clse').show();
	  jQuery('#'+area).fadeIn(500);
    },'json'); 
  });  
  return false
}

function ms_closePreview(field,area) {
  jQuery('#'+area).html('').hide();
  jQuery('#clse').hide();
  jQuery('textarea[name="'+field+'"]').show();
  jQuery('#prev').show()
}