<table class="form-table">
	<tr>
	    <td>
	    	<form method="post" action="" id="morsel-loadNew">
                <div class="loadNewButton">
                    <input id="getNewMorsel" class="button" type="submit" name="loadNewMorsel" value="Get New Morsels">
                </div>
			</form>
	    </td>
	</tr>
</table>
<script type="text/javascript">
	    /*morsel preview text*/
	jQuery( "#morsel_preview_Text_submit" ).click(function(e) {
		e.preventDefault();
		jQuery('#morsel_preview_Text_submit').val('Please wait!');
       	var userData = {
				api_key:"<?php echo $morselSettings['userid'].':'.$morselSettings['key']; ?>",
				user: {
					profile_attributes:{
						id:jQuery("#profile_id_Text").val(),
						preview_text: jQuery("#preview_text").val()
					}
				}
			};
		jQuery.ajax({
			url: "<?php echo MORSEL_API_USER_URL.$morselSettings['userid'].'.json';?>",
			data: userData,
			type:'PUT',
			success: function(response){
		    	jQuery('#morsel_preview_Text_submit').val('Save');
				if(response.meta.status == 200){
					jQuery("#profile_id_Text").val(response.data.profile.id);
				 	jQuery("#morsel-form-preview-text").submit();
				} else {
					alert("Opps something has gone wrong!");
					return false;
				}
			}, error:function(response){
		   		console.log("Error Response : ",response);
		   		alert("Opps something has gone wrong!");
		   	}, complete:function(){
		   		jQuery('#morsel_preview_Text_submit').val('Connecting');
		   	}
		});
	});	
	/*morsel preview text End*/
</script>		    
