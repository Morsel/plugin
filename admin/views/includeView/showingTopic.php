    <script src="<?php echo MORSEL_PLUGIN_ADMIN_ASSEST.'multiselect/jquery.sumoselect.js'?>"></script>
    <link href="<?php echo MORSEL_PLUGIN_ADMIN_ASSEST.'multiselect/sumoselect.css'?>" rel="stylesheet" />

    <script type="text/javascript">
        jQuery(document).ready(function () {
            //window.testSelAll = jQuery('.testSelAll').SumoSelect({okCancelInMulti:true, selectAll:true });
        });
    </script>
    <div id="modal-window-TopicId" class="displayNone">
	    <form method="post" action="" id="add_morsel_Topic">
		    <input id ="eatmorsel_id" type = "hidden" value="">
		    <br>
		<?php $morsel_keywords = json_decode($morselSettings['morsel_keywords']);?>
			<!--<select id = "select_TopicId" multiple="multiple" placeholder class="widefat" style="width:auto;">
			    <option value="blank"></option>
			</select>-->
      <div class="morsel-topic-select-wrapper">
  			<div class="selectBoxForTopic"></div>
        <div class="mrsl-action-btn">
  		    <a id = "morsel_topic_button" class="button button-primary ">Save</a>&nbsp;&nbsp;
  		    <a class="morselClosePopup button">Close</a>
        </div>
      </div>
		</form>
    </div>

<script type="text/javascript">
		/*Topic Start*/
	jQuery('#the-list ').on('click', '.all_morsel_TopicId', function() {
		var all_morsel_TopicId = jQuery(this);
        all_morsel_TopicId.text('Please wait!');
		var morsel_id = jQuery(this).attr("morsel-id");
	    jQuery('#eatmorsel_id').val(morsel_id);

		    jQuery.ajax({
				url:"<?php echo MORSEL_API_URL?>"+"topics/selected_morsel_topic",
				type:"POST",
				data:{
	    			topic:{
	    				morsel_id:morsel_id,
	    				user_id:<?php echo $options['userid']; ?>
	    			},
	    			api_key:"<?php echo $api_key ?>"
	  			},
				success: function(response) {

                    all_morsel_TopicId.text('Pick Topic');
					if(response.data=="empty" || response.data=="blank"){
            			alert('Please add Topic list first!');
					} else {
						var allTopics =JSON.parse(jQuery('#post_topic_id').val());
						var saveTopics = response.data;
						jQuery('.selectBoxForTopic').html("");
                    	var html = '<select id="select_TopicId" multiple="multiple" placeholder="Select topic for your morsels" class="testSelAll">';
				  		jQuery.each(allTopics, function( allTopics_index,allTopics){
 							var selected;
							if(jQuery.inArray(allTopics.id, saveTopics) !== -1){
                                selected = "selected";
							}
							html += '<option '+selected+' value="'+allTopics.id+'">'+allTopics.name+'</option>';
						});
				  		html += "</select>";

						jQuery('.selectBoxForTopic').append(html);
					    var url = "#TB_inline?width=400&height=400&inlineId=modal-window-TopicId";
					    tb_show("Add Morsel Topic", url);
					    jQuery('.testSelAll').SumoSelect({okCancelInMulti:true, selectAll:true });
					}
				},error:function(){
					console.log("Some issue to add Topic to morsel");
				},complete:function(){}
		        });
	});
 	jQuery('#morsel_topic_button').click(function(){
        if(jQuery('#select_TopicId').val() == "" || jQuery('#select_TopicId').val() == null ) {
        	alert('Please select Topic first');
        	return;
        }
        var morsel_id = jQuery('#eatmorsel_id').val();
        console.log("Topic----------------------",jQuery('#select_TopicId').val());
        jQuery.ajax({
			url: "<?php echo MORSEL_API_URL.'morsels/update_topic.json';?>",
		    type:'post',
		    data: {
				morsel:{morsel_topic_ids:jQuery('#select_TopicId').val()},
				morsel_id:jQuery('#eatmorsel_id').val(),
				user_id:<?php echo $options['userid']; ?>,
				api_key:"<?php echo $api_key ?>"
			},
			success: function(response){
				if(response.meta.status == 200){
					getMorselTopic(morsel_id);
					alert("Morsel topic updated successfully");
					tb_remove();
				} else {
					alert("Wrong credential");
				  	return false;
				}
	        }, error:function(response){
			   	alert("You have entered wrong Username or Password!");
			}, complete:function(){
			   	jQuery('#morsel_topic_button').val('please wait!');
			}
		});
	});

	function getMorselTopic(morsel_id){
		console.log("getMorselTopic--------",morsel_id);
	    jQuery.ajax({
		url:"<?php echo MORSEL_API_URL?>"+"topics/selected_morsel_topic",
		type:"POST",
		data:{
			topic:{
				morsel_id:morsel_id,
				user_id:<?php echo $options['userid']; ?>
			},
			api_key:"<?php echo $api_key ?>"
			},
		success: function(response) {
            console.log("response----------------",response);
            var allTopics =JSON.parse(jQuery('#post_topic_id').val());
            var stringhtml = '';
		  		jQuery.each(allTopics, function( allTopics_index,allTopics){
					var selected;
					if(jQuery.inArray(allTopics.id, response.data) !== -1){
                        stringhtml += "<code style='line-height: 2;'>"+allTopics.name+"<a href='#' id='topic-"+allTopics.id+morsel_id+"' class='dashicons dashicons-no mrsl-remove-keyword' onclick='removeTopic("+morsel_id+","+allTopics.id+"); return false;'></a></code>";
		    		}
				});
				jQuery("#morsel_post-"+morsel_id).find('td:eq(5)').html(stringhtml);
				jQuery("#morsel_post-"+morsel_id).find(".all_morsel_TopicId").text('Update Topics');

    	},error:function(){
			console.log("Some issue to add Topic to morsel");
		},complete:function(){}
        });
	}
	/*Topic End*/
</script>

