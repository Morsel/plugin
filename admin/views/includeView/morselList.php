
<style type="text/css">
/*for upper scroll over morsel list table*/
/*	@media screen and (max-width: 820px) {
	   .wrapper1, .wrapper2{ width: 100%; border: none 0px RED; overflow-x: scroll; overflow-y:hidden;}
       .wrapper1{ height: 20px; }
       .div1 { width:980px; height: 20px; }
	   .div2 { width:980px; background-color: #88FF88; overflow: auto; }
}*/
#the-list code { font-family: "Open Sans",sans-serif !important; }    
</style>

<div class="clearMorsel"></div>
<div class="wrapper1">
    <div class="div1"></div>
</div>
<div class="wrapper2">
    <div class="div2">
      <table class="widefat morselPostListTable">
		<thead>
			<tr>
				<th class='manage-column'>Title</th>
				<th class='manage-column'>Image</th>
				<th class='manage-column'>Description</th>
				<th class='manage-column'>Published Date</th>
		  		<th class='manage-column'>Keyword</th>
		  		<th class='manage-column'>Topic</th>
		  		<th class='manage-column'>Actions</th>
		  	</tr>
		</thead>
		<tfoot>
			<tr>
				<th class='manage-column'>Title</th>
				<th class='manage-column'>Image</th>
				<th class='manage-column'>Description</th>
				<th class='manage-column'>Published Date</th>
			  	<th class='manage-column'>Keyword</th>
		  		<th class='manage-column'>Topic</th>
			  	<th class='manage-column'>Actions</th>
		  	</tr>
		</tfoot>

		<tbody id="the-list">
		 <?php foreach ($jsonPost->data as $row) { ?>
		    <tr id="morsel_post-<?php echo $row->id;?>" class="post-<?php echo $k;?> type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0">
			    <td>
				    <strong>
					    <? $morsel_url = add_query_arg( array('morselid' => $row->id), get_permalink($morsel_page_id));?>
					    <a href="<?php echo $morsel_url?>" target="_blank">
					        <?php if($row->is_submit) { ?>
					            <font class="unPublished">
					               <?php echo $row->title;?>
					               <span>&nbsp;(UNPUBLISHED)</span>
					            </font>
					        <? } else { echo $row->title; } ?>    
					    </a>
					</strong>
				</td>
	            <td>
	              <?php if($row->primary_item_photos->_320x320 != ''){?>
	                <a href="<?php echo $row->primary_item_photos->_320x320;?>" target="_blank" >
	                	<img src="<?php echo $row->primary_item_photos->_320x320;?>" height="100" width="100">
	                </a>
	              <?php } else if($row->photos->_800x600 != '') { ?>
	              	<a href="<?php echo $row->photos->_800x600;?>" target="_blank" >
	                	<img src="<?php echo $row->photos->_800x600;?>" height="100" width="100">
	                </a>
	              <?php } else { echo "No Image Found";} ?>
	            </td>
				<td>
				  <?php echo substr($row->summary,0,150); echo (strlen($row->summary) > 150 ? "..." :"");?>
				</td>
				<td>
				    <?php if(!$row->is_submit) { ?>
				    	<abbr title="<?php echo date("m/d/Y", strtotime($row->published_at));?>"><?php echo date("m/d/Y", strtotime($row->published_at));?></abbr>
				    <br />PUBLISHED
				    <?php } else if($row->local_schedual_date){
				        echo "Scheduled at <span class='schedualDate'>".date('m/d/Y  h:i:s a', strtotime($row->local_schedual_date))."<span><br>";?> 
                        <a morsel-id="<?php echo $row->id ?>" morsel-schedualdate="<?php echo $row->schedual_date;?>" class="all_unpublish_morsel_scheduled button">Reschedule</a> 
				    <? } else { echo "-";} ?>
				</td>
				<td>
				   <?php foreach ($row->morsel_keywords as $tag_keyword){?>
						<code>
						   <?php echo $tag_keyword->name;?>
						   <a href="javascript:void(0);" id="keyword-<?php echo $tag_keyword->id ?><?php echo $row->id ?>" class="dashicons dashicons-no mrsl-remove-keyword" onclick="removeKeyword(<?php echo $row->id ?>,<?php echo $tag_keyword->id ?>); return false;"></a>
						</code>
					<?php } ?>
				</td>
				<td>
				   <?php foreach ($row->morsel_topics as $tag_topic){?>
						<code>
						   <?php echo $tag_topic->name;?>
						   <a href="javascript:void(0);" id="topic-<?php echo $tag_topic->id.$row->id;?>" class="dashicons dashicons-no mrsl-remove-keyword" onclick="removeTopic(<?php echo $row->id ?>,<?php echo $tag_topic->id ?>); return false;"></a>
						</code>
					<?php } ?>
				</td>
				<td>
				    <img src="<?=MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif" id="smallAjaxLoader<?php echo $row->id;?>" class="displayNone editAboveImage"/>
				    <a href="javascript:void(0);" onclick="editMorsel(<?php echo $row->id;?>)" class="button actionButtonMorsel">Edit</a>
				   		<?php add_thickbox(); ?>
						<a morsel-id = "<?php echo $row->id ?>" class="all_morsel_keyowrd_id button actionButtonMorsel" id="keywordButton<?php echo $row->id ?>"><?=(count($row->morsel_keywords)>0)?"Update":"Pick";?> Keyword</a>
					<?php if($row->is_submit || count($row->morsel_topics) == 0) { ?>
				   		<?php add_thickbox(); ?>
						<a morsel-id = "<?php echo $row->id ?>" class="all_morsel_TopicId button actionButtonMorsel">Pick Topics</a>
					<?php } else { ?>
						<?php add_thickbox(); ?>
						<a morsel-id = "<?php echo $row->id ?>" class="all_morsel_TopicId button actionButtonMorsel">Update Topics</a>
					<?php } ?>
					<?php if($row->is_submit) { ?>
						<a morsel-id = "<?php echo $row->id ?>" class="all_unpublish_morsel_id button actionButtonMorsel">Publish Morsel</a>
						<? if($row->schedual_date == ""){?>
							<a morsel-id = "<?php echo $row->id ?>" morsel-schedualdate="<?php echo $row->schedual_date;?>" class="all_unpublish_morsel_scheduled button actionButtonMorsel">Schedule</a>
					    <? }
					} ?>
				</td>
			</tr>
		  <?php } ?>
		</tbody>
	</table>
    </div>
</div>

<script type="text/javascript">
    jQuery(function(){
    jQuery(".wrapper1").scroll(function(){
        jQuery(".wrapper2")
            .scrollLeft(jQuery(".wrapper1").scrollLeft());
    });
    jQuery(".wrapper2").scroll(function(){
        jQuery(".wrapper1")
            .scrollLeft(jQuery(".wrapper2").scrollLeft());
    });
});
</script>



<div class="morselPostListTableDiv" style="border:solid; display:none;">
	<table class="widefat morselPostListTable">
		<thead>
			<tr>
				<th class='manage-column'>Title</th>
				<th class='manage-column'>Image</th>
				<th class='manage-column'>Description</th>
				<th class='manage-column'>Published Date</th>
		  		<th class='manage-column'>Keyword</th>
		  		<th class='manage-column'>Topic</th>
		  		<th class='manage-column'>Actions</th>
		  	</tr>
		</thead>
		<tfoot>
			<tr>
				<th class='manage-column'>Title</th>
				<th class='manage-column'>Image</th>
				<th class='manage-column'>Description</th>
				<th class='manage-column'>Published Date</th>
			  	<th class='manage-column'>Keyword</th>
		  		<th class='manage-column'>Topic</th>
			  	<th class='manage-column'>Actions</th>
		  	</tr>
		</tfoot>

		<tbody id="the-list">
		 <?php foreach ($jsonPost->data as $row) { ?>
		    <tr id="morsel_post-<?php echo $row->id;?>" class="post-<?php echo $k;?> type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0">
			    <td>
				    <strong>
					    <? $morsel_url = add_query_arg( array('morselid' => $row->id), get_permalink($morsel_page_id));?>
					    <a href="<?php echo $morsel_url?>" target="_blank">
					        <?php if($row->is_submit) { ?>
					            <font class="unPublished">
					               <?php echo $row->title;?>
					               <span>&nbsp;(UNPUBLISHED)</span>
					            </font>
					        <? } else { echo $row->title; } ?>    
					    </a>
					</strong>
				</td>
	            <td>
	              <?php if($row->primary_item_photos->_320x320 != ''){?>
	                <a href="<?php echo $row->primary_item_photos->_320x320;?>" target="_blank" >
	                	<img src="<?php echo $row->primary_item_photos->_320x320;?>" height="100" width="100">
	                </a>
	              <?php } else if($row->photos->_800x600 != '') { ?>
	              	<a href="<?php echo $row->photos->_800x600;?>" target="_blank" >
	                	<img src="<?php echo $row->photos->_800x600;?>" height="100" width="100">
	                </a>
	              <?php } else { echo "No Image Found";} ?>
	            </td>
				<td>
				  <?php echo substr($row->summary,0,150); echo (strlen($row->summary) > 150 ? "..." :"");?>
				</td>
				<td>
				    <?php if(!$row->is_submit) { ?>
				    	<abbr title="<?php echo date("m/d/Y", strtotime($row->published_at));?>"><?php echo date("m/d/Y", strtotime($row->published_at));?></abbr>
				    <br />PUBLISHED
				    <?php } else if($row->local_schedual_date){
				        echo "Scheduled at <span class='schedualDate'>".date('d-m-Y H:i', strtotime($row->local_schedual_date))."<span><br>";?> 
                        <a morsel-id="<?php echo $row->id ?>" morsel-schedualdate="<?php echo $row->schedual_date;?>" class="all_unpublish_morsel_scheduled button">Reschedule</a> 
				    <? } else { echo "-";} ?>
				</td>
				<td>
				   <?php foreach ($row->morsel_keywords as $tag_keyword){?>
						<code>
						   <?php echo $tag_keyword->name;?>
						   <a href="javascript:void(0);" id="keyword-<?php echo $tag_keyword->id ?><?php echo $row->id ?>" class="dashicons dashicons-no mrsl-remove-keyword" onclick="removeKeyword(<?php echo $row->id ?>,<?php echo $tag_keyword->id ?>); return false;"></a>
						</code>
					<?php } ?>
				</td>
				<td>
				   <?php foreach ($row->morsel_topics as $tag_topic){?>
						<code>
						   <?php echo $tag_topic->name;?>
						   <a href="javascript:void(0);" id="topic-<?php echo $tag_topic->id.$row->id;?>" class="dashicons dashicons-no mrsl-remove-keyword" onclick="removeKeyword(<?php echo $row->id ?>,<?php echo $tag_topic->id ?>); return false;"></a>
						</code>
					<?php } ?>
				</td>
				<td>
				    <img src="<?=MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif" id="smallAjaxLoader<?php echo $row->id;?>" class="displayNone editAboveImage"/>
				    <a href="javascript:void(0);" onclick="editMorsel(<?php echo $row->id;?>)" class="button actionButtonMorsel">Edit</a>
				   		<?php add_thickbox(); ?>
						<a morsel-id = "<?php echo $row->id ?>" class="all_morsel_keyowrd_id button actionButtonMorsel" id="keywordButton<?php echo $row->id ?>"><?=(count($row->morsel_keywords)>0)?"Update":"Pick";?> Keyword</a>
					<?php if($row->is_submit || count($row->morsel_topics) == 0) { ?>
				   		<?php add_thickbox(); ?>
						<a morsel-id = "<?php echo $row->id ?>" class="all_morsel_TopicId button actionButtonMorsel">Pick Topics</a>
					<?php } else { ?>
						<?php add_thickbox(); ?>
						<a morsel-id = "<?php echo $row->id ?>" class="all_morsel_TopicId button actionButtonMorsel">Update Topics</a>
					<?php } ?>
					<?php if($row->is_submit) { ?>
						<a morsel-id = "<?php echo $row->id ?>" class="all_unpublish_morsel_id button actionButtonMorsel">Publish Morsel</a>
						<? if($row->schedual_date == ""){?>
							<a morsel-id = "<?php echo $row->id ?>" morsel-schedualdate="<?php echo $row->schedual_date;?>" class="all_unpublish_morsel_scheduled button actionButtonMorsel">Schedule</a>
					    <? }
					} ?>
				</td>
			</tr>
		  <?php } ?>
		</tbody>
	</table>
</div>   
<script>    
    jQuery('#the-list').on('click', '.all_unpublish_morsel_id', function() {
		var all_unpublish_morsel_id = jQuery(this);
        all_unpublish_morsel_id.removeClass('button').text('Your morsel is publishing...');
		var morsel_id = jQuery(this).attr("morsel-id");
		    jQuery.ajax({
				url:"<?php echo MORSEL_API_URL?>"+"morsels/"+morsel_id+"/check_then_publish",
				type:"POST",
				data:{
	   				userId:<?php echo $options['userid']; ?>,
	   				api_key:"<?php echo $api_key ?>",
	   				post_to_facebook : true,
	   				post_to_twitter : true
	  			},
				success: function(response) {
				    if(response.data =="NOT"){
				    	alert("Please add morsel keyword");
				    	all_unpublish_morsel_id.addClass('button').text('Publish Morsel');
				    }
				    else{
				        get_morsel(morsel_id);
				    }
				},error:function(){
					console.log("Some issue to add keywords to morsel");
				},complete:function(){}
	        });
	});
	/**
	 * Remove keyword function from a morsel
	 */
	function removeKeyword(morsel_id,keyword_id){
		jQuery.ajax({
				url:"<?php echo MORSEL_API_URL?>"+"morsels/remove_morsel_keywords.json",
				type:"DELETE",
				data:{
	    			morsel:{morsel_keyword_ids:[keyword_id] },
						morsel_id:morsel_id,
	    			api_key:"<?php echo $api_key ?>"
	  		},
				success: function(response) {
					if(response.meta.status == "200" && response.meta.message == "OK"){
						jQuery("#keyword-"+keyword_id+morsel_id).parent().hide();
						jQuery("#keywordButton"+morsel_id).html("Pick keyword");
                    }
				},error:function(){
					alert("Opps some thing wrong happen!");
				},
				complete:function(){}
	    });
		return false;
	}

	function removeTopic(morsel_id,topic_id){
		jQuery.ajax({
				url:"<?php echo MORSEL_API_URL?>"+"morsels/remove_morsel_topics.json",
				type:"DELETE",
				data:{
	    			morsel:{morsel_topic_ids:[topic_id] },
					morsel_id:morsel_id,
	    			api_key:"<?php echo $api_key ?>"
	  		    },
				success: function(response) {
					if(response.meta.status == "200" && response.meta.message == "OK"){
						jQuery("#topic-"+topic_id+morsel_id).parent().hide();
                    }
				},error:function(){
					alert("Opps some thing wrong happen!");
				},
				complete:function(){}
	    });
		return false;
	}

	function get_morsel(morsel_id){
	    jQuery.ajax({
				url:"<?php echo MORSEL_API_URL?>"+"morsels/"+morsel_id,
				type:"GET",
				data:{
	    				api_key:"<?php echo $api_key ?>"
	  			},
				success: function(response) {
				  if(!response.data.photos) {
				   	setTimeout(function(){
     					get_morsel(morsel_id);
					},1000);
				  } else {
				  	window.location.reload(true);
				  }
				},error:function(){},
				complete:function(){}
	    });
	}
	jQuery('.all_unpublish_morsel_scheduled').click(function(){
        var all_morsel_keyowrd_id = jQuery(this);
		var morsel_id = jQuery(this).attr("morsel-id");
	    jQuery('#schedualMorsel').val(morsel_id);
        var url = "#TB_inline?width=500&height=200&inlineId=modal-datetimepicker-id";
	    tb_show("Schedule", url);
	});
</script>
