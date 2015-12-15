function timeAgo(selector) {

    var templates = {
        prefix: "",
        suffix: " ago",
        seconds: "less than a minute",
        minute: "about a minute",
        minutes: "%d minutes",
        hour: "about an hour",
        hours: "about %d hours",
        day: "a day",
        days: "%d days",
        month: "about a month",
        months: "%d months",
        year: "about a year",
        years: "%d years"
    };
    var template = function(t, n) {
        return templates[t] && templates[t].replace(/%d/i, Math.abs(Math.round(n)));
    };

    var timer = function(time) {
        if (!time)
            return;
        // /time = new Date(time)

        time = new Date(time * 1000 || time);

        var now = new Date();
        var seconds = ((now.getTime() - time) * .001) >> 0;
        var minutes = seconds / 60;
        var hours = minutes / 60;
        var days = hours / 24;
        var years = days / 365;

        return templates.prefix + (
                seconds < 45 && template('seconds', seconds) ||
                seconds < 90 && template('minute', 1) ||
                minutes < 45 && template('minutes', minutes) ||
                minutes < 90 && template('hour', 1) ||
                hours < 24 && template('hours', hours) ||
                hours < 42 && template('day', 1) ||
                days < 30 && template('days', days) ||
                days < 45 && template('month', 1) ||
                days < 365 && template('months', days / 30.41) ||
                years < 1.5 && template('year', 1) ||
                template('years', years)
                ) + templates.suffix;
    };

    var elements = document.getElementsByClassName('time-ago');

    for (var i in elements) {
        var $this = elements[i];
        if (typeof $this === 'object') {
            $this.innerHTML = timer($this.getAttribute('title') || $this.getAttribute('datetime'));
        }
    }
   // update time every minute
   //setTimeout(timeAgo, 60000);
}

function creatCommentList(commentData,morselSite,avatar_image) {
  var html = '';
  if(jQuery.isArray(commentData)){

    commentData.reverse().forEach(function(entry) {
      html += '<li class="view-more-list">\
                <div class="user-info">';
      if(entry.creator.photos){
       html +=     '<a class="profile-pic-link profile-pic-s" title="'+entry.creator.username+'" href="'+morselSite+entry.creator.username+'"><img class="img-circle" src="'+entry.creator.photos._72x72+'"></a>';
      } else {
        html +=     '<a class="profile-pic-link profile-pic-s" title="'+entry.creator.username+'" href="'+morselSite+entry.creator.username+'"><img class="img-circle" src="'+avatar_image+'"></a>';
      }
      html +=     '<div class="user-body user-extra-info">\
                    <h5 class="user-info-main">\
                      <a class="overflow-ellipsis" href="'+morselSite+entry.creator.username+'">'+entry.creator.first_name+' '+entry.creator.last_name+'</a>\
                    </h5>\
                    <span class="time-ago" title="'+entry.created_at+'"></span>\
                    <p class="user-list-text">'+entry.description+'</p>\
                  </div>\
                </div>\
              </li>';
      });

  } else {

    html = '<li class="view-more-list">\
              <div class="user-info">';
    if(commentData.creator.photos){
      html += '<a class="profile-pic-link profile-pic-s" title="'+commentData.creator.username+'" href="'+morselSite+commentData.creator.username+'"><img class="img-circle" src="'+commentData.creator.photos._72x72+'"></a>';
    } else {
      html += '<a class="profile-pic-link profile-pic-s" title="'+commentData.creator.username+'" href="'+morselSite+commentData.creator.username+'"><img class="img-circle" src="'+avatar_image+'"></a>';
    }
      html +=   '<div class="user-body user-extra-info">\
                  <h5 class="user-info-main">\
                    <a class="overflow-ellipsis" href="'+morselSite+commentData.creator.username+'">'+commentData.creator.first_name+' '+commentData.creator.last_name+'</a>\
                  </h5>\
                  <span class="time-ago" title="'+commentData.created_at+'"></span>\
                  <p class="user-list-text">'+commentData.description+'</p>\
                </div>\
              </div>\
            </li>';
  }
  return html;
}

var waitingDialog = (function ($) {

    // Creating modal dialog's DOM
  var $dialog = $(
    '<div id="morselWaringModal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
    '<div class="modal-dialog modal-m">' +
    '<div class="modal-content">' +
      '<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3 style="margin:0;"></h3></div>' +
      '<div class="modal-body">' +
        '<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>' +
      '</div>' +
    '</div></div></div>');

  return {
    /**
     * Opens our dialog
     * @param message Custom message
     * @param options Custom options:
     *          options.dialogSize - bootstrap postfix for dialog size, e.g. "sm", "m";
     *          options.progressType - bootstrap postfix for progress bar type, e.g. "success", "warning".
     */
    show: function (message, options) {
      //add modal to bootstrap-iso class div because we use bootstrap with parent .bootstrap-iso class
      $('div.bootstrap-iso').append($dialog);
      // Assigning defaults
      var settings = $.extend({
        dialogSize: 'm',
        progressType: ''
      }, options);
      if (typeof message === 'undefined') {
        message = 'Loading';
      }
      if (typeof options === 'undefined') {
        options = {};
      }
      // Configuring dialog
      $dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
      $dialog.find('.progress-bar').attr('class', 'progress-bar');
      if (settings.progressType) {
        $dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
      }
      $dialog.find('h3').text(message);
      // Opening dialog
      $dialog.modal();
    },
    /**
     * Closes dialog
     */
    hide: function () {
      $dialog.modal('hide');
    }
  }

})(jQuery);

function showDialog(page){
  //close main popup
  jQuery("#morsel-login-content").dialog("close");

  var jQuerydialog = jQuery('<div></div>')
           .html('<iframe style="border: 0px; " src="' + page + '" width="100%" height="100%"></iframe>')
           .dialog({
               autoOpen: false,
               modal: true,
               width: "90%",
               height : 500,
               title: "Connect to social",
               open: function(event, ui) {
                jQuery(this).closest('.ui-dialog').find('.ui-dialog-titlebar-close').hide();
               },
               buttons: [
                {
                  text: "Close",
                  click: function() {
                    jQuery( this ).dialog( "close" );
                  }
                }
              ]
           });
  jQuerydialog.dialog('open');
}

function likesCountText(isIncrease) {

  var isIncrease = isIncrease;
  var text = jQuery("#like-count").text();
  text = text.trim();
  var finalValue = '';

  if(isIncrease){
    if(text == ''){
      finalValue = '1 like';
    } else {
      val = parseInt(text.substr(0,text.indexOf(' ') + 1));
      val = val + 1;
      finalValue = (val > 1) ? val+' '+'likes' : val+' '+'like';
    }
  } else {
    if(text == '1 like'){
      finalValue = '';
    } else {
      val = parseInt(text.substr(0,text.indexOf(' ') + 1));
      val = val - 1;
      finalValue = (val > 1) ? val+' '+'likes' : val+' '+'like';
    }
  }

  jQuery("#like-count").text(finalValue );
}

function commentsCountText(itemId,isIncrease) {

  var isIncrease = isIncrease;
  var itemId = itemId;
  var text = jQuery("#comment-count-"+itemId).text();
  text = text.trim();
  var finalValue = '';

  if(isIncrease){
    if(text == 'Add comment'){
      finalValue = '1 comment';
    } else {
      val = parseInt(text.substr(0,text.indexOf(' ') + 1));
      val = val + 1;
      finalValue = (val > 1) ? val+' '+'comments' : val+' '+'comment';
    }
    if(jQuery("#comment-count-"+itemId).prev('i').hasClass("common-comment-empty")){
      jQuery("#comment-count-"+itemId).prev('i').attr('class',"common-comment-filled");
    }
  } else {
    if(text == '1 comment'){
      finalValue = '';
    } else {
      val = parseInt(text.substr(0,text.indexOf(' ') + 1));
      val = val - 1;
      finalValue = (val > 1) ? val+' '+'comments' : val+' '+'comment';
    }
  }
  jQuery("#comment-count-"+itemId).text(finalValue );
}

  jQuery(function ($) {

    //check for comment textarea blank
    jQuery("#comment-text").keypress(function() {
        if(jQuery("#comment-text").val() != ''){
          jQuery("#add-comment-btn").prop("disabled",false);
        } else {
          jQuery("#add-comment-btn").prop("disabled",true);
        }
    });

    //add modal-open class to body
    jQuery(window).on('shown.bs.modal', function(){
        if(!jQuery("body").hasClass('modal-open')){
          jQuery("body").addClass('modal-open')
        }
    });

    //FOR HIDDING remove modal-open class to body
    jQuery(window).on('hidden.bs.modal', function(){
        if(jQuery("body").hasClass('modal-open')){
          jQuery("body").removeClass('modal-open')
        }
    });

    //focus on share functionality
    jQuery("#share-morsel-focus").click(function(event){
      event.preventDefault();
      //jQuery('#share-morsel')[0].scrollIntoView(true);
      $('html, body').animate({ scrollTop: $("#share-morsel").offset().top }, 500);
    });

    //file upload image
    jQuery("#mrsl_user_photo").change(function () {
        jQuery(".image-preview").html("");
        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.png)$/;
        if (regex.test(jQuery(this).val().toLowerCase())) {
            if (jQuery.browser.msie && parseFloat(jQuery.browser.version) <= 9.0) {
                jQuery(".image-preview").show();
                jQuery(".image-preview")[0].filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = jQuery(this).val();
            }
            else {
                if (typeof (FileReader) != "undefined") {
                    jQuery(".image-preview").show();
                    jQuery(".image-preview").append("<img class='img-circle' />");
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        jQuery(".image-preview img").attr("src", e.target.result);
                    }
                    reader.readAsDataURL(jQuery(this)[0].files[0]);
                } else {
                    alert("This browser does not support FileReader.");
                }
            }
        } else {
            alert("Please upload a valid image file.");
        }
    });


    /*show hide of signup and login panel*/
    jQuery("#show-mrsl-login-btn").click(function(event){

      jQuery("#mrsl-signup-section").toggle();
      jQuery("#mrsl-login-section").toggle();

      if(jQuery("#show-mrsl-login-btn").text() =='Login'){
        jQuery("#show-mrsl-login-btn").text('Signup');
      } else {
        jQuery("#show-mrsl-login-btn").text('Login');
      }

    });

    // Setup signup span for tooltip
    jQuery("#mrsl-signup-submit-btn-span").mouseover(function(event){

      if((jQuery('#mrsl_user_first_name').val() == "")
          || (jQuery('#mrsl_user_last_name').val() == "")
          || (jQuery('#mrsl_user_email').val() == "")
          || (jQuery('#mrsl_user_password').val() == "")
          || (jQuery('#varification').val() == "")
          ){
        jQuery("#mrsl-signup-submit-btn-span").attr("data-original-title","Please complete all required fields");
        jQuery("#mrsl-signup-submit-btn-span").attr("data-toggle","tooltip");
        jQuery("#mrsl-signup-submit-btn").prop("disabled",true);
      } else if(jQuery("#mrsl-signup-form div").children("div.form-group").hasClass("has-error")){
        jQuery("#mrsl-signup-submit-btn-span").attr("data-original-title","Please correct errors in indicated fields");
        jQuery("#mrsl-signup-submit-btn-span").attr("data-toggle","tooltip");
        jQuery("#mrsl-signup-submit-btn").prop("disabled",true);
      } else {
        jQuery("#mrsl-signup-submit-btn-span").attr("data-toggle","");
        jQuery("#mrsl-signup-submit-btn").prop("disabled",false);
      }
    });

    // Setup form validation on the signup-form element
    jQuery("#mrsl-signup-form").validate({

        // Specify the validation rules
        rules: {
            "user[first_name]" : "required",
            "user[last_name]" : "required",
            "user[username]" : "required",
            "user[email]" : {
              required : true,
              email:true
            },
            "user[password]": "required",
            verification: {
              equalTo: "#mrsl_user_password"
            }
        },

        // Specify the validation error messages
        messages: {
            "user[first_name]" : " First Name is required",
            "user[last_name]" : "Last Name is required",
            "user[username]" : "Username is required",
            "user[email]" : {
              required : "Email is required",
              email: "Email is invalid"
            },
            "user[password]": "Password is required",
            verification: {
              equalTo: "Passwords don't match"
            }
        },

        highlight: function(element) {
            jQuery(element).parent('div').addClass("has-error");
        },

        unhighlight: function(element) {
            jQuery(element).parent('div').removeClass("has-error");
        },

        submitHandler: function(form) {
            form.submit();
        },

        onfocusout: function(element) {
          this.element(element);
        }
    });


    /*end signup section*/


    jQuery('body').tooltip({
      selector: '[data-toggle="tooltip"]'
    });

    jQuery('a.open-site-link').click(function(event){   //bind handlers
      event.preventDefault();
      jQuery("#morselLoginModal").modal('hide');

      var src = jQuery(this).attr('data-src');
      var height = jQuery(this).attr('data-height') || 300;
      var width = jQuery(this).attr('data-width') || 400;

      jQuery("#forgetPasswordModal iframe").attr({'src':src,
                        'height': height,
                        'width': width});
    });

    // Setup form validation on the #register-form element
   jQuery("#mrsl-submit-btn-span").mouseover(function(event){
      if((jQuery('#mrsl-login').val() == "") || (jQuery('#mrsl-password').val() == "") ){
        jQuery("#mrsl-submit-btn-span").attr("data-toggle","tooltip");
        jQuery("#mrsl-submit-btn").prop("disabled",true);
      } else {
        jQuery("#mrsl-submit-btn-span").attr("data-toggle","");
        jQuery("#mrsl-submit-btn").prop("disabled",false);
      }
   });

   //flip signup account
   jQuery("#morsel-front-login-form div.have-an-account").children("a").click(function(event){
      event.preventDefault();
      jQuery("#show-mrsl-login-btn").trigger("click");
   });

   // Setup form validation on the login-form element
    jQuery("#morsel-front-login-form").validate({

        // Specify the validation rules
        rules: {
            "user[login]" : "required",
            "user[password]": "required"
        },

        // Specify the validation error messages
        messages: {
            "user[login]": "Email or Username is required",
            "user[password]": "Password is required"
        },

        highlight: function(element) {
            jQuery(element).parent('div').addClass("has-error");
        },

        unhighlight: function(element) {
            jQuery(element).parent('div').removeClass("has-error");
        },

        submitHandler: function(form) {
            form.submit();
        },

        onfocusout: function(element) {
          this.element(element);
        }
    });
});
