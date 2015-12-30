<?php
if(isset($hostCompany) && $hostCompany != ""){
?>
<link rel="stylesheet" type="text/css" href=<?php echo MORSEL_PLUGIN_ADMIN_ASSEST.'editor/css/widgEditor.css'?>>
<?php
   if(isset($morselSettings['morsel_keywords'])) {
   	  $old_option = get_option('morsel_settings');
	    $old_option['morsel_keywords'] = str_replace("'","",$old_option['morsel_keywords']);
	    update_option("morsel_settings",$old_option);
   }
   $options = get_option('morsel_settings');
   $api_key = $options['userid'] . ':' .$options['key'];
   $jsonurl = MORSEL_API_URL."users/".$options['userid']."/morsels.json?api_key=$api_key&count=".MORSEL_API_COUNT."&submit=true";
   // $jsonurl = MORSEL_API_URL."users/".$options['userid']."/morsels.json?api_key=$api_key&count=1&submit=true";
   $jsonPost = json_decode(file_get_contents($jsonurl));

    if(count($jsonPost->data)==0){
       $jsonPost = json_decode(wp_remote_fopen($jsonurl));
    }

   $morsel_page_id = get_option( 'morsel_plugin_page_id');

    if(get_option( 'morsel_post_settings')){
   	  $morsel_post_settings = get_option( 'morsel_post_settings');
    } else {
   	  $morsel_post_settings = array();
    }

    if(array_key_exists('posts_id', $morsel_post_settings))
   	  $post_selected = $morsel_post_settings['posts_id'];
    else
   	  $post_selected = array();


// get all updated keyword on post tab
	if(isset($_POST["keyword"]["name"])){
		if($_POST["keyword_id"] != ""){
			$new_settings = get_option("morsel_settings");
	    	$allKeywords = json_decode($new_settings['morsel_keywords']);
	    	foreach($allKeywords as $kwd){
	    		if($kwd->id == $_POST["keyword_id"]){
	    			$kwd->name = $_POST["keyword"]["name"];
	    		}
	    	}
	    	$new_settings['morsel_keywords'] = json_encode($allKeywords);
	    	update_option("morsel_settings",$new_settings);
	    	if(isset($options["morsel_keywords"])) {
	    	 	$options["morsel_keywords"] = $new_settings['morsel_keywords'];
	    	}
		} else {
			$new_keyword = stripslashes($_POST["updated_keywords"]);
		    $new_settings = get_option("morsel_settings");
	    	$new_settings['morsel_keywords'] = ($new_keyword);
	    	update_option("morsel_settings",$new_settings);
	    	if(isset($options["morsel_keywords"])) {
	    	 	$options["morsel_keywords"] = ($new_keyword);
	    	}
		}
	}

//save preview Text
if(isset($_POST["morsel_settings_Preview"])){
	$new_settings = $_POST["morsel_settings_Preview"];
  update_option("morsel_settings_Preview",$new_settings);
}


if(count($jsonPost->data)>0){?>
<!-- scroll css-->
<link rel="stylesheet" type="text/css" href=<?php echo MORSEL_PLUGIN_ADMIN_ASSEST.'infinite-scroll/scroll.css'?>>
<!-- scroll css-->

<span class="postTab postJsonData" id="morselTableList">
<!-- edit Morsel Start -->
<span class="editMorsels" style="display:none;">
	<div  style="background: none repeat scroll 0 0 #fff;padding: 10px;">
	  <h3>Edit Morsel</h3>
		<div style="width:100%">
			<table class="form-table MorselEdit">
		  		<tr valign="top">
		  			<td width="100%">
							<p>
								<b>Title:</b>
							  <input type="hidden" style="width:50%" name="morselId" id="morselId" value=""/>
							  <input type="text" style="width:50%" name="morselTitle" id="morselTitle" value=""/>
							  <a id="post_title_savebtn" class="button button-primary morselSave" onclick="saveMorsel('morselTitle','title',this)">Save</a>
							  <img style="display:none;" class="smallAjaxLoader" src="<?=MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif">
					    </p>
					    <p>
						    <div class="addItemClass">
						    	<div style="float: left; width: 100px; margin: 10px 0 0 0;"><b>Items:</b></div>
						    	<div style="float:right;">
						    	   <!-- <img src="<?=MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif" id="smallAjaxLoaderAddItem" style="display:none;"/>
						    	   <a style=" margin-bottom: 5px;" class="button" onclick="addItemMorsel();">Add Item</a> -->
						    	</div>
					        <table class="wp-list-table widefat posts addItemTable">
										<thead>
											<tr>
												<th style="" class="manage-column column-date" scope="col">Item Image</th>
												<th style="" class="manage-column item-description sortable desc" scope="col">Item Description</th>
												<th style="" class="manage-column column-title sortable asc" scope="col">Action</th>
										  </tr>
										</thead>
										<tbody id = "items-body"></tbody>
									</table>
	              </div>
					    </p>
					    <p>
						    <div style="float:left;">
		              <input id="" class="button button-primary" type="button" value="Save" name="morsel_update_form" onclick="morsel_back_key()">&nbsp;&nbsp;
				  			  <input id="" class="button button-primary" type="button" value="Cancel" name="morsel_update_form" onclick="morsel_back_key()">
			  		    </div>
				  		  <div style="float:right;">
							   <img src="<?=MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif" id="smallAjaxLoaderAddItem" style="display:none;"/>
							   <a style=" margin-bottom: 5px;" class="button" onclick="addItemMorsel();">Add Item</a>
								</div>
					    </p>
					</td>
		  		</tr>
		  	</table>
		</div>
	</div>
</span>
<!-- edit Morsel End -->
    <div style="float:left; width:80%">
    <form method="post" action="" id="morsel-form-preview-text">
      <?php settings_fields( 'morsel_settings_Preview' ); ?>
          <?php do_settings_sections( 'morsel_settings_Preview' );
            $morselHostDetails = get_option('morsel_host_details');
          ?>
    <input type="hidden" style="width:50%" name="morsel_host_details[profile_id]" id="profile_id_Text" value="<?php echo $morselHostDetails['profile_id'] ?>"/>
	    <table class="form-table" style="margin:0;">
	  		<tr valign="top">
	  			<td scope="row">Preview Text:</td>
				<td>
					<input style="width:200px;" type="text" name="morsel_settings_Preview" id="preview_text" value="<?php echo (get_option('morsel_settings_Preview'))? get_option('morsel_settings_Preview') : 'You have subscribed for Morsel.' ?>"/>
				    <?php submit_button("Save","primary","save",null,array( 'id' => 'morsel_preview_Text_submit' ) ); ?>
			  	</td>
			</tr>
	    </table>
    </form>
    </div>
    <form method="post" action="" id="morsel-loadNew">
        <div style="float:right; padding:15px 0px;"><input id="getNewMorsel" class="button" type="submit" name="loadNewMorsel" value="Get New Morsels"></div>
    </form>

	<table class="wp-list-table widefat posts fixed">
		<thead>
			<tr>
				<th scope='col' id='title' class='manage-column column-title sortable desc' style="padding-left: 10px;">Title</th>
				<th scope='col' id='author' class='manage-column column-author'>Image</th>
				<th scope='col' id='categories' class='manage-column column-categories'>Description</th>
				<th scope='col' id='date' class='manage-column column-date sortable asc'>Published Date</th>
		  		<th scope='col' id='action' class='manage-column column-current-keyowrd'>Current Keyword</th>
		  		<th scope='col' id='action' class='manage-column column-action'>Actions</th>
		  	</tr>
		</thead>
		<tfoot>
			<tr>
				<th scope='col' id='title' class='manage-column column-title sortable desc' style="padding-left: 10px;">Title</th>
				<th scope='col' id='author' class='manage-column column-author'>Image</th>
				<th scope='col' id='categories' class='manage-column column-categories'>Description</th>
				<th scope='col' id='date' class='manage-column column-date sortable asc'>Published Date</th>
			  	<th scope='col' id='action' class='manage-column column-current-keyowrd'>Current Keyword</th>
			  	<th scope='col' id='action' class='manage-column column-action'>Actions</th>
		  	</tr>
		</tfoot>

		<tbody id="the-list">

		 <?php foreach ($jsonPost->data as $row) { ?>
		    <tr id="morsel_post-<?php echo $row->id;?>" class="post-<?php echo $k;?> type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0">
			    <td class="post-title page-title column-title">
				    <strong>
					    <? $morsel_url = add_query_arg( array('morselid' => $row->id), get_permalink($morsel_page_id));?>
					    <?php if($row->is_submit) { ?>
					      <a style="color:red;" href="<?php echo $morsel_url?>" target="_blank"><?php echo $row->title?><b style="font-size:15px;">&nbsp;(UNPUBLISHED)</b></a>
					    <?php }else{ ?>
					      <a href="<?php echo $morsel_url?>" target="_blank"><?php echo $row->title?></a>
					    <?php } ?>
				    </strong>
				</td>
	            <td class="author column-author">
	              <?php if($row->photos->_800x600 != ''){?>
	                <a href="<?php echo $row->photos->_800x600;?>" target="_blank" >
	                <img src="<?php echo $row->photos->_800x600;?>" height="100" width="100">
	                </a>
	              <?php } else if($row->primary_item_photos->_320x320 != '') { ?>
	              	  <a href="<?php echo $row->primary_item_photos->_320x320;?>" target="_blank" >
	                <img src="<?php echo $row->primary_item_photos->_320x320;?>" height="100" width="100">
	                </a>
	              <?php } else { echo "No Image Found";} ?>
	            </td>
				<td class="categories column-categories">
				  <?php echo substr($row->summary,0,150); echo (strlen($row->summary) > 150 ? "..." :"");?>
				</td>
				<td class="date column-date">
				    <?php if(!$row->is_submit) { ?>
				    <abbr title="<?php echo date("m/d/Y", strtotime($row->published_at));?>"><?php echo date("m/d/Y", strtotime($row->published_at));?></abbr>
				    <br />PUBLISHED
				    <?php } else if($row->schedual_date){
				        echo "Schedualed at ".date('d-m-Y H:i', strtotime($row->schedual_date));?> 
                        <br><a style=" margin-bottom: 5px;" morsel-id = "<?php echo $row->id ?>" morsel-schedualdate="<?php echo $row->schedual_date;?>" class="all_unpublish_morsel_scheduled button">ReSchedule</a> 
				    <? }
				     else { echo "NULL";} ?>
				</td>
				<td class="code-keyword categories column-categories">
				   <?php foreach ($row->morsel_keywords as $tag_keyword){?>
						<code style = "line-height: 2;"><?php echo $tag_keyword->name ?><a href="#" id="keyword-<?php echo $tag_keyword->id ?>" class="dashicons dashicons-no mrsl-remove-keyword" onclick="removeKeyword(<?php echo $row->id ?>,<?php echo $tag_keyword->id ?>); return false;"></a></code><br>
					<?php } ?>
				</td>
				<td>
				<p><img src="<?=MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif" id="smallAjaxLoader<?php echo $row->id;?>" style="display:none;"/><a href="javascript:void(0);" onclick="editMorsel(<?php echo $row->id;?>)" class="button">Edit</a></p>
				    <?php if($row->is_submit || count($row->morsel_keywords) == 0) { ?>
				   		<?php add_thickbox(); ?>
						<a style=" margin-bottom: 5px;" morsel-id = "<?php echo $row->id ?>" class="all_morsel_keyowrd_id button">Pick Keyword</a>
					<?php } else { ?>
						<?php add_thickbox(); ?>
						<a style=" margin-bottom: 5px;" morsel-id = "<?php echo $row->id ?>" class="all_morsel_keyowrd_id button">Update Keyword</a>
					<?php } ?>
					<?php if($row->is_submit || count($row->morsel_topics) == 0) { ?>
				   		<?php add_thickbox(); ?>
						<a style=" margin-bottom: 5px;" morsel-id = "<?php echo $row->id ?>" class="all_morsel_TopicId button">Pick Topics</a>
					<?php } else { ?>
						<?php add_thickbox(); ?>
						<a style=" margin-bottom: 5px;" morsel-id = "<?php echo $row->id ?>" class="all_morsel_TopicId button">Update Topics</a>
					<?php } ?>
					<?php if($row->is_submit) { ?>
						<a style=" margin-bottom: 5px;" morsel-id = "<?php echo $row->id ?>" class="all_unpublish_morsel_id button">Publish Morsel</a>
						<? if($row->schedual_date == ""){?>
							<a style=" margin-bottom: 5px;" morsel-id = "<?php echo $row->id ?>" morsel-schedualdate="<?php echo $row->schedual_date;?>" class="all_unpublish_morsel_scheduled button">Scheduled</a>
					    <? }
					} ?>
				</td>
			</tr>
		  <?php } ?>
		</tbody>
	</table>

    <div id="modal-window-id" style="display:none;">
	    <form method="post" action="" id="add_morsel_keyword">
		    <input id ="eatmorsel_id" type = "hidden" value="">
		    <span><b>Create a new keyword for your Morsel account: </b></span>
		    <br><br>
		<?php $morsel_keywords = json_decode($morselSettings['morsel_keywords']);?>
			<select id = "select_keyword_id" class="widefat">
			    <option value="blank">Select keyword for morsel :</option>
			</select>
		    <br><br>
		    <a id="morsel_keyword_button" class="button button-primary"> Pick </a>&nbsp;&nbsp;
		    <a class="morselClosePopup button">Close</a>
		</form>
    </div>

    <div id="modal-window-TopicId" style="display:none;">
	    <form method="post" action="" id="add_morsel_Topic">
		    <input id ="eatmorsel_id" type = "hidden" value="">
		    <span><b>Create a new Topic for your Morsel account: </b></span>
		    <br><br>
		<?php $morsel_keywords = json_decode($morselSettings['morsel_keywords']);?>
			<select id = "select_TopicId" multiple class="widefat">
			    <option value="blank">Select Topic for morsel :</option>
			</select>
		    <br><br>
		    <a id = "morsel_topic_button" class="button button-primary "> Pick </a>&nbsp;&nbsp;
		    <a class="morselClosePopup button">Close</a>
		</form>
    </div>
    <div class="clear"><br></div>
    <div>
        <div id="no-more">No More Morsels</div>
	    <div id="ajaxLoader" >
	        <span><img src="<?php echo MORSEL_PLUGIN_IMG_PATH;?>ajax-loader.gif"></span>
	    </div>
	</div>
<!-- </form> -->

<?php } else { ?>
  <p><h3>Oops! You don't have any morsel on your site.</h3></p>
<?php } ?>


<script type="text/javascript">
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
						jQuery("#keyword-"+keyword_id).parent().hide()
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

    jQuery('.morselClosePopup').click(function(){
		jQuery( "#TB_closeWindowButton" ).trigger( "click" )
	});

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
					        stringhtml += "<code style='line-height: 2;'>"+name+"<a href='#' id='keyword-"+selected_keywords+"' class='dashicons dashicons-no mrsl-remove-keyword' onclick='removeKeyword("+morsel_id+","+selected_keywords+"); return false;'></a></code><br>";
		    		    }
				    })
					jQuery("#morsel_post-"+morsel_id+" .code-keyword").html(stringhtml);
	                alert("Morsels keyword updated successfully");
					tb_remove();
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
						jQuery('#select_TopicId option[value!="blank"]').remove();
                    	var html = '';
					  		jQuery.each(allTopics, function( allTopics_index,allTopics){
							var selected;
							if(jQuery.inArray(allTopics.id, saveTopics) !== -1){
                                selected = "selected";
							}
							html = '<option '+selected+' value="'+allTopics.id+'">'+allTopics.name+'</option>';
							jQuery('#select_TopicId').append(html);
						});
					    var url = "#TB_inline?width=500&height=200&inlineId=modal-window-TopicId";
					    tb_show("Add Morsel Topic", url);
					}
				},error:function(){
					console.log("Some issue to add Topic to morsel");
				},complete:function(){}
		        });
	});
 	jQuery('#morsel_topic_button').click(function(){
        if(jQuery('#select_TopicId').val() =="blank") {
        	alert('Please select Topic first');
        	return;
        }

        var morsel_id = jQuery('#eatmorsel_id').val();

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
					// var stringhtml = "";
					// jQuery('#select_TopicId option:selected').each(function(){
					//     if(jQuery(this).attr('selected') == 'selected') {
					//         var name = jQuery(this).text();
					//         stringhtml += "<code style='line-height: 2;'>"+name+"</code><br>"
		   //  		    }
				 //    })
					//jQuery("#morsel_post-"+morsel_id+" .code-keyword").html(stringhtml);
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
	/*Topic End*/


	/*save host details function*/
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
</script>
</span>
<style type="text/css">
	 .widgContainer{border: 1px solid; display: none;}
</style>
<script type="text/javascript">
    var morselGlobal;
	function editMorsel(morselid){
      console.log("editMorsel---------------",morselid);
      morselGlobal = morselid;
      jQuery("#morselId").val(morselid);
      jQuery("#smallAjaxLoader"+morselid).css("display","block");
      jQuery.ajax({
				url:"<?php echo MORSEL_API_URL;?>"+"morsels/"+morselid,
				type:"GET",
				data:{
	    			api_key:"<?php echo $api_key;?>"
	  			},
				success: function(response) {
					console.log("morsel_response------------",response);
					var data = response.data;
					var items = data.items;
					//title
					jQuery("#morselTitle").val(data.title);
					jQuery("#morsel_description_text").val(data.summary);
					jQuery("#items-body").html("");
					for(var i in items){
		                var html = '<tr class="itemMorsel'+items[i].id+'"><td class="column-date">';
		                if(items[i].photos != null && items[i].photos._100x100 != undefined && items[i].photos._100x100 != null && items[i].photos._100x100 != ''){
  		                  html += '<img id="item-thumb-'+items[i].id+'" onClick="uploadMorselItemImage('+items[i].id+')" src = "'+items[i].photos._100x100+'"/>';
		                } else {
		                	//noImageMorsel.png
		                  html += '<img id="item-thumb-'+items[i].id+'" onClick="uploadMorselItemImage('+items[i].id+')" src = "<?=MORSEL_PLUGIN_IMG_PATH;?>noImageMorsel.png" width="100"/>';
		                }
		                  html += '<input type="file" id="imageUpload'+items[i].id+'" style="display:none;"><img src="<?=MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif" id="smallAjaxLoaderItemImage'+items[i].id+'" style="display:none;"/>';
		                  // var des = items[i].description;
		                  var des = (items[i].description == null)?'Null':items[i].description;
		                  var desText = (items[i].description == null)?'':items[i].description;

		                  html += '</td><td class="item-description"><form action="submit.php" id="form'+items[i].id+'" onsubmit="saveItemDes('+items[i].id+');return false;"><span id="span'+items[i].id+'">'+des+'</span><textarea id="textareaItem'+items[i].id+'" name="nameTextareaItem'+items[i].id+'" class="widgEditor nothing editor" style="width:100%; display:none;">'+desText+'</textarea></form></td><td class="column-title"><a class="button" id="saveButton'+items[i].id+'" style="display:none;" onClick = "formSubmitItem('+items[i].id+')">Save</a><a class="button" onClick = "editMorselItem('+items[i].id+')"  id="editButton'+items[i].id+'">Edit Item</a>&nbsp;&nbsp;<a onClick = "deleteMorselItem('+items[i].id+')" class ="button button-primary" >Delete Item</a></td></form></tr>';
		                jQuery("#items-body").append(html);
		                //textareaItem3339WidgContainer
		                ////<a class="button" id="saveButton'+items[i].id+'" style="display:none;" onClick = "saveItemDes('+items[i].id+')">Save</a>
		            }
					jQuery("#smallAjaxLoader"+morselid).css("display","none");
					jQuery(".editMorsels").css("display","block");
				},error:function(){},
				complete:function(){
					widgInit();
		        }
	    });
	}
function formSubmitItem(formID){
   jQuery("#form"+formID).submit();
}

var acceptedExt = ["jpg","JPG","png","PNG","jpeg","JPEG","gif","GIF"];

function uploadMorselItemImage(itemID){
	//alert("image Item");
    jQuery("#imageUpload"+itemID).click();
    jQuery("#imageUpload"+itemID).change(function(){
    	 var fileObject = {};
    	 fileObject = document.getElementById("imageUpload"+itemID).files[0];
    	 var fileName = fileObject.name,
    	     ext = fileName.split(".")[fileName.split(".").length - 1];

    	if(jQuery.inArray(ext, acceptedExt) >= 0){
     	  jQuery("#smallAjaxLoaderItemImage"+itemID).css("display","block");
	      //submit the form here
	      //event.preventDefault();
			  var fd = new FormData();
			  //fd.append("user[email]",jQuery( "#mrsl_user_email" ).val());
			  if (fileObject) {
			    fd.append("item[photo]", fileObject);
			  }
			  jQuery.ajax({
			  	url:"<?php echo MORSEL_API_URL;?>"+"items/"+itemID+".json?api_key=<?php echo $api_key;?>&prepare_presigned_upload=true",
			    data: fd,
			    type: 'PUT',
			    contentType: false,
			    cache: false,
			    processData: false,
			    beforeSend: function(xhr) {},
			    complete: function() {},
			    success: function(response) {
			      console.log('test response', response);
			      setTimeout(function() {
			      	//called the click of save button for perticular item
			      	//jQuery("#saveButton"+itemID).click();
			      	checkItemPhoto(itemID);
			      	//editMorsel(morselGlobal);
			      }, 8000);
			    },
			    error: function(response) {},
			    complete: function(){
			    	/*setTimeout(function() {
			    		jQuery("#smallAjaxLoaderItemImage"+itemID).css("display","none");
			        },7000);*/
			    }
			  });
	    } else {
	    	alert("Please upload valid image, image extension must be jpg, JPG, png, PNG, jpeg,JPEG, gif, GIF");
	    	return false;
	    }
	});

	// Regular function with arguments
  function checkItemPhoto(itemID) {
    //call after 2 second
    setTimeout(function() {
      jQuery.ajax({
        url : "<?php echo MORSEL_API_ITEMS_URL;?>"+itemID+'.json',
        type:"GET",
        async: false,
        data:{
          api_key : "<?php echo $api_key;?>",
        },
        success: function(response) {
          console.log("checkItemPhoto function Item Get------------",response);
          if(response.meta.status == "200" && response.meta.message == "OK"){
            if(response.data.photos == null || response.data.photos._100x100 == undefined ||response.data.photos._100x100 == ''){
              checkItemPhoto(itemID);
            } else {
              console.log("Get item photo");
              jQuery("#item-thumb-"+itemID).attr("src",response.data.photos._100x100);
              jQuery("#smallAjaxLoaderItemImage"+itemID).css("display","none");
            }
          }
        },error:function(){console.log("Error occure in checkItemPhoto function");},
        complete:function(){}
      });
    },2000);
  }
}
</script>
<script>
	// function addItemMorsel(){
	// 	var html = "<tr><td class='column-date'><img width='50' src = 'https://www.eatmorsel.com/assets/images/utility/placeholders/morsel-placeholder_640x640.jpg' /></td><td class='item-description'>Describe items here...</td><td class='column-title'><a class='button' width='100' >Edit Item</a>&nbsp;&nbsp;<a class ='button button-primary'>Delete Item</a></td></tr>";
	// 	jQuery("#item-body").append(html);
	// }
	function morsel_back_key(){
	  jQuery(".postJsonData").css("display","block");
	  jQuery(".editMorsels").css("display","none");
	}
	jQuery(".add_ItemMorsel").click(function(event) {
		//event.preventDefault();
		jQuery(this).parents('.form-table').remove();
    });
	jQuery("#post_title").click(function(event){
		jQuery('#post_title_text').val(jQuery('#post_title').text());
		jQuery('#post_title_div').show();
		jQuery('#post_title').hide();
	});
	jQuery("#post_summary").click(function(event){
		jQuery('#post_description_text').val(jQuery('#post_summary').text());
		jQuery('#post_description_div').show();
		jQuery('#post_summary').hide();
	});
	jQuery("#post_title_cancelbtn").click(function(event){
		jQuery('#post_title_div').hide();
		jQuery('#post_title').show();
	});
	jQuery("#post_description_cancelbtn").click(function(event){
		jQuery('#post_description_div').hide();
		jQuery('#post_summary').show();
	});
</script>
<style type="text/css">

.form-table span {
display: inline-block;
width: 100%;
}
.morsel-title-hide{
	display: none;
}
.morsel-title-show{
	display: block;
}
#post_description_div > textarea{
	width: 300px;
	height:200px;
	overflow: hidden;
}
.item-description{
	width: 60%;
}
.MorselEdit td{
  vertical-align: top;
}
.morselSave{
  margin-top: 10px !important;
}
.addItemTable th{
	padding: 10px !important
}
</style>
<script>
	function saveMorsel(fieldId,fieldName,element){
		console.log("this element",element);
   	if(fieldName == "summary"){
      data = {
    		"api_key" : "<?php echo $api_key;?>",
    		"summary" : jQuery("#"+fieldId).val()
  		}
   	} else {
      data = {
				"api_key" : "<?php echo $api_key;?>",
				"morsel" : { "title" : jQuery("#"+fieldId).val() }
			}
   	}
    jQuery.ajax({
			url:"<?php echo MORSEL_API_URL;?>"+"morsels/"+morselGlobal,
			type:"PUT",
			data:data,
			beforeSend: function(response) {
				jQuery("#"+element.id).next("img.smallAjaxLoader").show();
			},
			success: function(response) {
				console.log("morsel_response add------------",response);
			},error:function(){
				jQuery("#"+element.id).next("img.smallAjaxLoader").hide();
			},complete:function(){
				jQuery("#"+element.id).next("img.smallAjaxLoader").hide();
			}
    });
	}
	function addItemMorsel(){
		jQuery("#smallAjaxLoaderAddItem").css("display","inline-block");
	    jQuery.ajax({
			url:"<?php echo MORSEL_API_URL;?>"+"items.json",
			type:"POST",
			data:{ api_key : "<?php echo $api_key;?>" , "item":{"morsel_id": morselGlobal} },
			success: function(response) {
				editMorsel(morselGlobal);
			},error:function(){},
			complete:function(){
				jQuery("#smallAjaxLoaderAddItem").css("display","none");
			}
        });
	}
	function saveItemDes(itemID){
		jQuery("#smallAjaxLoaderAddItem").css("display","inline-block");
    	jQuery.ajax({
			url:"<?php echo MORSEL_API_URL;?>items/"+itemID+".json",
			type:"PUT",
			data:{ api_key : "<?php echo $api_key;?>" , item : {"description": jQuery("#textareaItem"+itemID).val() } },
			success: function(response) {
				console.log("morsel_response Item Get------------",response);
				editMorsel(morselGlobal);
				jQuery("#smallAjaxLoaderAddItem").css("display","none");
			},error:function(){},
			complete:function(){}
        });
	}
	function deleteMorselItem(itemID){
		jQuery("#smallAjaxLoaderAddItem").css("display","inline-block");
        jQuery.ajax({
			url:"<?php echo MORSEL_API_URL;?>items/"+itemID+".json",
			type:"DELETE",
			data:{ api_key : "<?php echo $api_key?>" },
			success: function(response) {
				console.log("morsel_response Item Get------------",response);
				jQuery( ".itemMorsel"+itemID).remove();
				jQuery("#smallAjaxLoaderAddItem").css("display","none");
			},error:function(){},
			complete:function(){}
        });
	}
	function editMorselItem(itemID){
		jQuery("#span"+itemID).css("display","none");
		jQuery("#textareaItem"+itemID+"WidgContainer").css("display","block");
		jQuery("#editButton"+itemID).css("display","none");
		jQuery("#saveButton"+itemID).css("display","inline-block");
	}
	function morsel_back_key(){
	    jQuery(".postJsonData").css("display","block");
        jQuery(".editMorsels").css("display","none");//ajaxLoaderSmall.gif
	}
	jQuery(".add_ItemMorsel").click(function(event) {
        jQuery(this).parents('.form-table').remove();
    });
</script>
<script type="text/javascript">
	var morselNoMore;
	var morsePageCount = 1;
	jQuery(window).scroll(function() {

		if(jQuery('#tabs1-js').css('display') != 'none'){

		    if(morselNoMore != true){
		    	if (jQuery(window).scrollTop() + jQuery(window).height() == jQuery(document).height()) {

				    jQuery('#no-more').hide();

				    jQuery("#ajaxLoader").css("display", "block");
				    jQuery.ajax({
				        url: "<?php echo site_url()?>" + "/index.php?pagename=morsel_ajax_admin&page_id=" + parseInt(++morsePageCount),
				        success: function(data) {
							if (data.trim().length > 1) {
								jQuery('#the-list tr:last').after(data);
							} else {
								morsePageCount--;
								morselNoMore = true;
								jQuery('#no-more').show();
							}
				        }, error: function() {
					        morsePageCount--;
					    }, complete: function() {
					        jQuery("#ajaxLoader").css("display", "none");
					    }
				    });
			    }
		    }
		}
	});
</script>
<!-- datetimepicker -->
    <link href="<?php echo MORSEL_PLUGIN_ADMIN_ASSEST.'datetimepicker/bootstrap/css/bootstrap.min.css'?>" rel="stylesheet" media="screen">
    <link href="<?php echo MORSEL_PLUGIN_ADMIN_ASSEST.'datetimepicker/bootstrap/css/bootstrap-datetimepicker.min.css'?>" rel="stylesheet" media="screen">
	<div id="modal-datetimepicker-id" style="display:none;">
	    <form action="" class="form-horizontal">
	        <input id="schedualMorsel" value="" type="hidden">
	        <fieldset>
     	        <legend>Select Date & Time</legend>
	            <div class="controls input-append date form_datetime" data-date="2015-09-16T05:25:07Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="scheduleDataMorsel">
	                <input size="16" type="text" value="" readonly style="width:240px;">
	                <span class="add-on"><i class="icon-th"></i></span>
	            </div>
					<input type="hidden" id="scheduleDataMorsel" value="" />
				<br/>
	            <br>
	            <?php
                    //echo $d = date( 'Y-m-d H:i:s', current_time( 'timestamp', 1 ) );
                    //$d1 = new DateTime('2008-08-03 14:52');
					//$d2 = new DateTime('2008-08-03 14:53');
					//var_dump($d1 == $d2);
					//var_dump($d1 > $d2);
					//var_dump($d1 < $d2);

                ?>
	            <a id="morsel_schedule" class="button button-primary"> Schedule </a>&nbsp;&nbsp;
     		</fieldset>
	    </form>
	</div>
<script>
 	jQuery('.all_unpublish_morsel_scheduled').click(function(){
        var all_morsel_keyowrd_id = jQuery(this);
        // all_morsel_keyowrd_id.text('Please wait!');
		var morsel_id = jQuery(this).attr("morsel-id");
	    jQuery('#schedualMorsel').val(morsel_id);
	    // alert("keyword");
                    var url = "#TB_inline?width=500&height=200&inlineId=modal-datetimepicker-id";
	                tb_show("Schedule", url);
            
	});
	jQuery("#morsel_schedule").click(function(){
		//alert("save Schedule");
		if(jQuery("#scheduleDataMorsel").val() == ""){
			alert("Please Select Data & Time");
			return;
		}
		jQuery.ajax({
			url:"<?php echo MORSEL_API_URL?>"+"morsels/"+jQuery('#schedualMorsel').val(),
			type:"PUT",
			data:{
	   			morsel:{
	   				schedual_date:jQuery("#scheduleDataMorsel").val(),
	   			},
	   			api_key:"<?php echo $api_key ?>"
			},
			success: function(response) {
              console.log("response--schduling---------",response.data.schedual_date);
               // morsel-schedualdate
               // jQuery(this).attr("morsel-schedualdate",response.data.schedual_date);
              window.location.reload(true);
            },error:function(){
				alert("Please add Keyword first.");
			},complete:function(){}   
	    });
	})
</script>
<script type="text/javascript" src="<?php echo MORSEL_PLUGIN_ADMIN_ASSEST.'datetimepicker/bootstrap/js/bootstrap-datetimepicker.js'?>" charset="UTF-8"></script>
<script type="text/javascript">
    jQuery('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1,
        zIndex: 999999999,
        startDate: new Date() 
    });
</script>
<!-- datetimepicker -->
<? } else { ?>
Please Enter Host Details First.
<? } ?>
