// (function($) {
//   "use strict";

//   $(function() {
//     jQuery(document).ready(function() {
//       var topOfOthDiv = jQuery(".load-more-wrap").offset().top;
//       jQuery(window).scroll(function() {
//         if (((jQuery(window).scrollTop() + 450) > (topOfOthDiv - 100))) { //scrolled past the other div?
//           loadMorsel();
//         }
//       });
//     });
//     var morselNoMore;
//     function loadMorsel() {
//       var morsePageCount = 1;
//       var count = '20';

//       if ($(this).attr("morsel-count")) {
//         count = $(this).attr("morsel-count");
//       }

//       if ($('#ajaxLoaderFront:visible').length == 0) {
//         if (morselNoMore != true) {
//           jQuery("#ajaxLoaderFront").css("display", "block");
//           $.ajax({
//             url: "index.php?pagename=morsel_ajax&page_id=" + parseInt(++morsePageCount) + "&morsel-count=" + count,
//             success: function(data) {
//               if (data.trim().length > 1)
//                 $("#morsel-posts-row").append(data);
//               else {
//                 morsePageCount--;
//                 morselNoMore = true;
//                 alert("No more morsel.")
//               }

//               $('[morsel-url]').click(function() {
//                 window.location.href = jQuery(this).attr('morsel-url');
//               })
//             },
//             error: function() {
//               morsePageCount--;
//             },
//             complete: function() {
//               console.log("morselView load");
//               jQuery("#ajaxLoaderFront").css("display", "none");
//               // load.html('View more morsels');
//             }
//           });
//         }
//       }

//     }
//   });
// }(jQuery));
