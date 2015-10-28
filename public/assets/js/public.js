(function($) {
  "use strict";

  $(function() {
    $('[morsel-url]').click(function() {
      window.location.href = jQuery(this).attr('morsel-url');
    })
    jQuery(document).ready(function() {
      var topOfOthDiv = jQuery(".load-more-wrap").offset().top;
      jQuery(window).scroll(function() {
        if (((jQuery(window).scrollTop() + 450) > (topOfOthDiv - 100))) { //scrolled past the other div?
          loadMorsel();
        }
      });
    });
    var morselNoMore;
    function loadMorsel() {
      var morsePageCount = 1;
      var count = '20';

      if ($(this).attr("morsel-count")) {
        count = $(this).attr("morsel-count");
      }

      if ($('#ajaxLoaderFront:visible').length == 0) {
        if (morselNoMore != true) {
          jQuery("#ajaxLoaderFront").css("display", "block");
          $.ajax({
            url: "index.php?pagename=morsel_ajax&page_id=" + parseInt(++morsePageCount) + "&morsel-count=" + count,
            success: function(data) {
              if (data.trim().length > 1)
                $("#morsel-posts-row").append(data);
              else {
                morsePageCount--;
                morselNoMore = true;
                alert("No more morsel.")
              }

              $('[morsel-url]').click(function() {
                window.location.href = jQuery(this).attr('morsel-url');
              })
            },
            error: function() {
              morsePageCount--;
            },
            complete: function() {
              console.log("morselView load");
              jQuery("#ajaxLoaderFront").css("display", "none");
              // load.html('View more morsels');
            }
          });
        }
      }

    }

    // $('#load-morsel').click(function() {
    //   var load = $(this);
    //   load.html('Fetching... Please wait.');
    //   var count = '20';

    //   if ($(this).attr("morsel-count")) {
    //     count = $(this).attr("morsel-count");
    //   }

    //   $.ajax({
    //     url: "index.php?pagename=morsel_ajax&page_id=" + parseInt(++morsePageCount) + "&morsel-count=" + count,
    //     success: function(data) {
    //       if (data.trim().length > 1)
    //         $("#morsel-posts-row").append(data);
    //       else {
    //         morsePageCount--;
    //         alert("No more morsel.")
    //       }

    //       $('[morsel-url]').click(function() {
    //         window.location.href = jQuery(this).attr('morsel-url');
    //       })
    //     },
    //     error: function() {
    //       morsePageCount--;
    //     },
    //     complete: function() {
    //       load.html('View more morsels');
    //     }
    //   });
    // })

    //open popup modal
    var $info = $("#morsel-login-content");
    $info.dialog({
      'dialogClass': 'wp-dialog',
      'modal': true,
      'autoOpen': false,
      'closeOnEscape': true,
      'width': 600
    });
    $("#open-morsel-login").click(function(event) {
      event.preventDefault();
      $info.dialog('open');
    });

    /*function shareThis(){
			var url, shareText, s = scope.morsel,
                            twitterUsername = s.creator.twitter_username ? "@" + s.creator.twitter_username : s.creator.first_name + " " + s.creator.last_name,
                            mediaUrl = "http://media.eatmorsel.com/morsels/" + s.id,
                            facebookUrl = s.mrsl && s.mrsl.facebook_media_mrsl ? s.mrsl.facebook_media_mrsl : mediaUrl,
                            twitterUrl = s.mrsl && s.mrsl.twitter_media_mrsl ? s.mrsl.twitter_media_mrsl : mediaUrl,
                            linkedinUrl = s.mrsl && s.mrsl.linkedin_media_mrsl ? s.mrsl.linkedin_media_mrsl : mediaUrl,
                            pinterestUrl = s.mrsl && s.mrsl.pinterest_media_mrsl ? s.mrsl.pinterest_media_mrsl : mediaUrl,
                            googleplusUrl = s.mrsl && s.mrsl.googleplus_media_mrsl ? s.mrsl.googleplus_media_mrsl : mediaUrl,
                            clipboardUrl = s.mrsl && s.mrsl.clipboard_media_mrsl ? s.mrsl.clipboard_media_mrsl : mediaUrl;
                        if ("facebook" === socialType) url = "https://www.facebook.com/sharer/sharer.php?u=" + facebookUrl;
                        else if ("twitter" === socialType) shareText = encodeURIComponent('"' + s.title + '" from ' + twitterUsername + " on @eatmorsel " + twitterUrl), url = "https://twitter.com/home?status=" + shareText;
                        else if ("linkedin" === socialType) url = "https://www.linkedin.com/shareArticle?mini=true&url=" + linkedinUrl;
                        else if ("pinterest" === socialType) shareText = encodeURIComponent('"' + s.title + '" from ' + s.creator.first_name + " " + s.creator.last_name + " on Morsel"), url = "https://pinterest.com/pin/create/button/?url=" + pinterestUrl + "&media=" + encodeURIComponent(getMediaImage()) + "&description=" + shareText;
                        else if ("googleplus" === socialType) url = "https://plus.google.com/share?url=" + googleplusUrl;
                        else if ("clipboard" === socialType) return void window.prompt("Copy the following link to share:", clipboardUrl);
                        window.open(url)
		}*/
  });
}(jQuery));
