<div id="modal-window-id" class="displayNone">
	    <form method="post" action="" id="add_morsel_keyword">
		    <input id ="eatmorsel_id" type = "hidden" value="">
		    <span><b>Create a new keyword for your Morsel account: </b></span>
		    <br><br>
		<?php $morsel_keywords = json_decode($morselSettings['morsel_keywords']);?>
			<select id = "select_keyword_id" class="widefat" style="width:auto;">
			    <option value="blank">Select keyword for morsel :</option>
			</select>
		    <br><br>
		    <a id="morsel_keyword_button" class="button button-primary"> Pick </a>&nbsp;&nbsp;
		    <a class="morselClosePopup button">Close</a>
		</form>
    </div>    

<script type="text/javascript">
	    /*For Keyword*/
	jQuery('#the-list ').on('click', '.all_morsel_keyowrd_id', function() {
		var all_morsel_keyowrd_id = jQuery(this);
        all_morsel_keyowrd_id.text('Please wait!');
		var morsel_id = jQuery(this).attr("morsel-id");
	    jQuery('#eatmorsel_id').val(morsel_id);

		    jQuery.ajax({
				url:"<?php echo MORSEL_API_URL?>"+"keywords/selected_morsel_keyword",
				type:"POST",
				data:{
	    			keyword:{
	    				morsel_id:morsel_id,
	    				user_id:<?php echo $options['userid']; ?>
	    			},
	    			api_key:"<?php echo $api_key ?>"
	  			},
				success: function(response) {
                    all_morsel_keyowrd_id.text('Pick Keywords');
					if(response.data=="empty" || response.data=="blank"){
            			alert('Please add keyword list first!');
					} else {
						var all_keywords =JSON.parse(jQuery('#post_keyword_id').val());
						var saved_keywords = response.data;

						jQuery('#select_keyword_id option[value!="blank"]').remove();
                    	var html = '';
					  		jQuery.each(all_keywords, function( all_keywords_index,all_keyword){

							if(jQuery.inArray(all_keyword.id, saved_keywords) !== -1){
								html = '<option selected="selected" value="'+all_keyword.id+'">'+all_keyword.name+'</option>';	;
							} else {
								html = '<option  value="'+all_keyword.id+'">'+all_keyword.name+'</option>'
							}
							jQuery('#select_keyword_id').append(html);
						});
					    var url = "#TB_inline?width=500&height=200&inlineId=modal-window-id";
					    tb_show("Add Morsel Keywords", url);
					}
				},error:function(){
					console.log("Some issue to add keywords to morsel");
				},complete:function(){}
		        });
	});
 	jQuery('#morsel_keyword_button').click(function(){
        if(jQuery('#select_keyword_id').val() =="blank") {
        	alert('Please select keywords first');
        	return;
        }
        var selected_keywords = jQuery('#select_keyword_id').val();
        // selected_keywords = selected_keywords.splice( !jQuery.inArray('blank', selected_keywords));
        var morsel_id = jQuery(this).parent("form").children("input#eatmorsel_id").val();
        console.log("morselId : ",morsel_id);

 		jQuery.ajax({
			url: "<?php echo MORSEL_API_URL.'morsels/update_keyword.json';?>",
		    type:'post',
		    data: {
				morsel:{morsel_keyword_ids:[selected_keywords]},
				morsel_id:morsel_id,
				user_id:"<?php echo $options['userid']; ?>",
				api_key:"<?php echo $api_key ?>"
			},
			success: function(response){
				if(response.meta.status == 200){
					var stringhtml = "";
					jQuery('#select_keyword_id option:selected').each(function(){
					    if(jQuery(this).attr('selected') == 'selected') {
					        var name = jQuery(this).text();
					        stringhtml += "<code style='line-height: 2;'>"+name+"<a href='#' id='keyword-"+selected_keywords+morsel_id+"' class='dashicons dashicons-no mrsl-remove-keyword' onclick='removeKeyword("+morsel_id+","+selected_keywords+"); return false;'></a></code><br>";
		    		    }
				    })
				    console.log("---------------stringhtml-------------",stringhtml);
					// jQuery("#morsel_post-"+morsel_id+" .code-keyword").html(stringhtml);
					jQuery("#morsel_post-"+morsel_id).find('td:eq(4)').html(stringhtml);
	                alert("Morsels keyword updated successfully");
					tb_remove();
					jQuery("#keywordButton"+morsel_id).html("Update keyword");
				} else {
					alert("Wrong credential");
				  return false;
				}
	    }, error:function(response){
			 	alert("Opps some issue occured in adding keyword to morsel, please try again.");
			}, complete:function(){
			 	jQuery('#morsel_keyword_button').val('please wait!');
			}
		});
	});
    /*Keyword End*/
</script>    