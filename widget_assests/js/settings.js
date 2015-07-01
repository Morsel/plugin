jQuery(function(){

  jQuery('#morsel-wrapper,#morsel-wrapper-thumbnail').nanoScroller({
    preventPageScrolling: true
  });
  jQuery('[morsel-url]').click(function(){
  	var url = jQuery(this).attr('morsel-url');
    window.location.href=url;
  })
  

 /* $("#main").find('.description').load("readme.html", function(){
    $(".nano").nanoScroller();
    $("#main").find("img").load(function() {
        $(".nano").nanoScroller();
    });
  });*/


});

