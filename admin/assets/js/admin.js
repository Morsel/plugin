(function ( $ ) {
	"use strict";

	$(function () {
		$('tbody .check-column>input[type=checkbox]').click(function(){			
		    if($(this).is(':checked'))
		      $(this).siblings("[name='morsel_post_settings[data][]']").attr('disabled', 'disabled');
		    else
		      $(this).siblings("[name='morsel_post_settings[data][]']").removeAttr('disabled');
		  })
         $('#cb-select-all-1, #cb-select-all-2').click(function(){
         	if($(this).is(':checked'))
		      $("[name='morsel_post_settings[data][]']").attr('disabled', 'disabled');
		    else
		      $("[name='morsel_post_settings[data][]']").removeAttr('disabled');
         })

		// Place your administration-specific JavaScript here

	});

}(jQuery));