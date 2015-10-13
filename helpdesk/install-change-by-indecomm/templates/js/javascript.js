//============================================
// MAIAN SUPPORT
// General Javascript Functions
// Written by David Ian Bennett
// http://www.maianscriptworld.co.uk
//============================================

function upgradeRoutines(stage) {
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'upgrade.php',
      data: 'upgrade=1&action='+stage,
      dataType: 'html',
      success: function (data) {
        if (data=='done') {
          window.location='upgrade.php?completed=yes';
        } else {
          if (stage=='start') {
            jQuery('#op_start').removeClass('running').addClass('done');
            jQuery('#op_start').html('Completed');
            jQuery('#op_1').removeClass('pleasewait').addClass('running');
            jQuery('#op_1').html('Running..');
          } else {
            jQuery('#op_'+stage).removeClass('running').addClass('done');
            jQuery('#op_'+stage).html('Completed');
            jQuery('#op_'+data).removeClass('pleasewait').addClass('running');
            jQuery('#op_'+data).html('Running..');
          }
          upgradeRoutines(data);
        }
      }
    });
  });
  return false;
}

function showHidePass(action) {
  switch (action) {
    case 'show':
    jQuery('#showhidepass').html('<a class="hidepass" href="#" onclick="showHidePass(\'hide\');return false" title="Click to Make Passwords Hidden in Boxes">Hide Passwords</a>');
    jQuery('#pass').hide();
    jQuery('#pass2').hide();
    jQuery('#pass_2').show();
    jQuery('#pass_3').show();
    break;
    case 'hide':
    jQuery('#showhidepass').html('<a class="showpass" href="#" onclick="showHidePass(\'show\');return false" title="Click to Make Passwords Visible in Boxes">Show Passwords</a>');
    jQuery('#pass_2').hide();
    jQuery('#pass_3').hide();
    jQuery('#pass').show();
    jQuery('#pass2').show();
    break;
  }
}

function checkFormAdmin() {
  var message = '';
  if (jQuery('#user').val()=='') {
    jQuery('#user').addClass('errorbox');
    message = 'Please enter username..\n';
  }
  if (jQuery('#email').val()=='') {
    jQuery('#email').addClass('errorbox');
    message += 'Please enter email address..\n';
  } else {
    if (jQuery('#email').val()!=jQuery('#email2').val()) {
      jQuery('#email2').addClass('errorbox');
      message += 'E-mail addresses do not match, try again..\n';
    }
  }
  if (jQuery('#pass').val()=='') {
    jQuery('#pass').addClass('errorbox');
    message += 'Please enter password..\n';
  } else {
    if (jQuery('#pass').val()!=jQuery('#pass2').val()) {
      jQuery('#pass2').addClass('errorbox');
      message += 'Passwords do not match, try again..\n';
    }
  }
  if (message) {
    alert(message);
    return false;
  }
}

function checkForm() {
  var message = '';
  if (jQuery('#website').val()=='') {
    jQuery('#website').addClass('errorbox');
    message = 'Please enter help desk name..\n';
  }
  if (jQuery('#email').val()=='') {
    jQuery('#email').addClass('errorbox');
    message += 'Please enter help desk "from" email address..\n';
  }
  if (message) {
    alert(message);
    return false;
  }
}

function connectionTest() {
  jQuery('#test').val('Please wait..');
  jQuery('#test').attr('disabled','disabled');
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'connectionTest=yes',
      dataType: 'html',
      success: function (data) {
        alert(data);
        jQuery('#test').val('Test Connection');
        jQuery('#test').removeAttr('disabled','');
      },
      complete: function () {
      },
      error: function(xml,status,error) {
      }
    });
  });   
}
