<?php
if(isset($hostCompany) && $hostCompany != ""){
?>
<?php
   if(isset($morselSettings['morsel_keywords'])) {
   	   $old_option = get_option('morsel_settings');
	   $old_option['morsel_keywords'] = str_replace("'","",$old_option['morsel_keywords']);
	   update_option("morsel_settings",$old_option);
   }
   $options = get_option('morsel_settings');
   $api_key = $options['userid'] . ':' .$options['key'];
   $jsonurl = MORSEL_API_URL."users/".$options['userid']."/morsels.json?api_key=$api_key&count=".MORSEL_API_COUNT."&submit=true";
   $json = json_decode(file_get_contents($jsonurl));

    if(count($json->data)==0){
       $json = json_decode(wp_remote_fopen($jsonurl));
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


if(count($json->data)>0){?>  
<!-- scroll css-->
<link rel="stylesheet" type="text/css" href=<?php echo MORSEL_PLUGIN_ADMIN_ASSEST.'infinite-scroll/scroll.css'?>>
<!-- scroll css-->

<span class="postTab postJsonData" id="morselTableList">  
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

		 <?php foreach ($json->data as $row) {?>
	      
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
				    <?php } else { echo "NULL";} ?>			   
				</td>
				<td class="code-keyword categories column-categories">
				   <?php foreach ($row->morsel_keywords as $tag_keyword){?>
						<code style = "line-height: 2;"><?php echo $tag_keyword->name ?></code><br>
					<?php } ?>
				</td>
				<td>
				    <?php if($row->is_submit || count($row->morsel_keywords) == 0) { ?>
				   		<?php add_thickbox(); ?>
						<a style=" margin-bottom: 5px;" morsel-id = "<?php echo $row->id ?>" class="all_morsel_keyowrd_id button">Pick Keywords</a>
					    <?php } ?>
						<br>
					 <?php if($row->is_submit) { ?>
						<a morsel-id = "<?php echo $row->id ?>" class="all_unpublish_morsel_id button">Publish Morsel</a>
					<?php } ?>
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
			<select id = "select_keyword_id" multiple class="widefat">
			    <option value="blank">Select keyword for morsel :</option>
			</select>
		    <br><br>
		    <a id = "morsel_keyword_button" class="button button-primary "> Pick </a>&nbsp;&nbsp;
		    <a id = "morsel_keyword_close" class="button">Close</a>		
		</form>
    </div>
    <div class="clear"><br></div>
    <div>
        <div id='no-more'>No More Morsels</div>
	    <div id="ajaxLoader" >
	        <span><img src="<?php echo MORSEL_PLUGIN_IMG_PATH;?>ajax-loader.gif"></span>
	    </div>
	</div>
<!-- </form> -->

<?php } else { ?>
  <p><h3>Oops! You don't have any morsel on your site.</h3></p>
<?php } ?>



<script type="text/javascript">
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

    jQuery('#morsel_keyword_close').click(function(){
		jQuery( "#TB_closeWindowButton" ).trigger( "click" )
	});

    jQuery('#the-list ').on('click', '.all_unpublish_morsel_id', function() {
		var all_unpublish_morsel_id = jQuery(this); 
        all_unpublish_morsel_id.removeClass('button').text('Your morsel is publishing...');
		var morsel_id = jQuery(this).attr("morsel-id");    
		    jQuery.ajax({
				url:"<?php echo MORSEL_API_URL?>"+"morsels/"+morsel_id+"/check_then_publish",
				type:"POST",
				data:{
	   				userId:<?php echo $options['userid']; ?>,
	   				api_key:"<?php echo $api_key ?>"
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
        selected_keywords = selected_keywords.splice( !jQuery.inArray('blank', selected_keywords));
	      
        var morsel_id = jQuery('#eatmorsel_id').val();
        
 		jQuery.ajax({
			url: "<?php echo MORSEL_API_URL.'morsels/update_keyword.json';?>",
		    type:'post',
		    data: {
				morsel:{morsel_keyword_ids:selected_keywords},
				morsel_id:jQuery('#eatmorsel_id').val(),
				user_id:<?php echo $options['userid']; ?>,
				api_key:"<?php echo $api_key ?>"
			},
			success: function(response){			
				if(response.meta.status == 200){
					var stringhtml = "";
					jQuery('#select_keyword_id option:selected').each(function(){
					    if(jQuery(this).attr('selected') == 'selected') {
					        var name = jQuery(this).text();
					        stringhtml += "<code style='line-height: 2;'>"+name+"</code><br>"
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
			   	alert("You have entered wrong Username or Password!");
			}, complete:function(){
			   	jQuery('#morsel_keyword_button').val('please wait!');
			}
		});
	});

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
<script type="text/javascript">
	function editMorsel(morselid,morselTitle,morselSummary,morselDescription,morselImage){
      jQuery("#morselId").val(morselid);
      jQuery("#post_title").val(morselTitle);
      jQuery("#post_summary").val(morselSummary);
      jQuery("#post_description").val(morselDescription);//morsel_image
      jQuery("#morsel_image").attr("src", morselImage);
      jQuery(".postJsonData").css("display","none");
      jQuery(".editMorsels").css("display","table-row");
	}
</script>
<style type="text/css">
	.editMorselsInnerDiv{
		background: none repeat scroll 0 0 #fff;
		padding: 10px;
	}
	.editMorselLeftDiv{
		float:left; 
		width:50%;
	}
</style>
<span class="editMorsels" style="display:none;">
	<div class="editMorselsInnerDiv">
	    <h3>Edit Morsel</h3>
		<div class="editMorselLeftDiv">
			<table class="form-table">
		  		<tr valign="top">  			
		  			<td scope="row">Title:</td>
					<td>
						<input type="text" style="width:50%" name="morselId" id="morselId" value=""/>
						<input type="text" style="width:50%" name="post_title" id="post_title" value=""/>
					</td>
		  		</tr>  	
		  		<tr valign="top">  			
		  			<td scope="row">Summary:</td>
					<td>
						<input type="text" style="width:50%" name="summary" id="post_summary" value=""/>
					</td>
		  		</tr>
		  		<tr valign="top">  			
		  			<td scope="row">Description:</td>
					<td>
						<textarea style="width:50%" name="post_description" id="post_description"></textarea>
					</td>
		  		</tr>  	
		  		<tr valign="top">  			
		  			<td scope="row">Image:</td>
					<td>
					<img width="100"  id="morsel_image"/><br>
						<input type="text" style="width:50%" name="post_image" id="post_image" value=""/>
					</td>
		  		</tr>  		
				<tr valign="top">
		  			<td scope="row">&nbsp;</td>
					<td><input id="morsel_update_key" class="button button-primary" type="button" value="Save" name="morsel_update_form">&nbsp;&nbsp;<input id="" class="button button-primary" type="button" value="Back To Morsel" name="morsel_update_form" onclick="morsel_back_key()"></td>
		  		</tr>
	        </table>
		</div>
		<div class="addItemClass editMorselLeftDiv">
			<h3>
			    <span>Add Item </span>
			    <span id="add_ItemMorsel" onclick="addItemMorsel()">
			        <img src='<?=MORSEL_PLUGIN_IMG_PATH."/plus.png"?>' width="30" />
			    </span>
			</h3>
			<table class="form-table">
		  		<tr class="add_item">  			
		  			<td scope="row" style="vertical-align:top !important;"> 
		  			    <p>Image</p>
						<input type="text" style="width:50%" name="addItem[image]" id="" value=""/>
					</td>
					<td scope="row" style="vertical-align:top !important;">
					    <p>Description</p>
					    <textarea style="width:100%" name="addItem[description]" id=""></textarea>
					</td>
		  		</tr>  		
				<tr>
		  			<td scope="row"><!-- <img class="add_ItemMorsel" src='<?=MORSEL_PLUGIN_IMG_PATH."/minus.png"?>' width="30" /> --></td>
					<td><input id="edit-morsel-item-btn" class="button button-primary" type="button" value="Save" name="morsel-keywords-form"></td>
		  		</tr>
	        </table>	        
		</div>
	</div>
</span>
<script>
	function addItemMorsel(){
		var k = "<table class='form-table'><tr valign='top' class='add_item'><td scope='row' style='vertical-align:top !important;'><p>Image</p><input type='text' style='width:50%' name='addItem[image]'/></td><td scope='row' style='vertical-align:top !important;'><p>Description</p><textarea style='width:100%' name='addItem[description]'></textarea></td></tr><tr valign='top'><td scope='row'><img class='add_ItemMorsel' src='<?=MORSEL_PLUGIN_IMG_PATH?>/minus.png' width='30' /></td><td><input class='button button-primary' type='button' value='Save' name='morsel-keywords-form'></td></tr></table>";
    	jQuery(".addItemClass").append(k);
	}
	function morsel_back_key(){
	    jQuery(".postJsonData").css("display","block");
        jQuery(".editMorsels").css("display","none");
	}
	jQuery(".add_ItemMorsel").click(function(event) {
        jQuery(this).parents('.form-table').remove();
    });
</script>
<script type="text/javascript">
	var morselNoMore;
	jQuery(window).scroll(function() {
		if(jQuery('#tabs1-js').css('display') != 'none'){
		    if(morselNoMore != true){
		    	if (jQuery(window).scrollTop() + jQuery(window).height() == jQuery(document).height()) {
				    jQuery('#no-more').hide();
				    var morsePageCount = 1;
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
<? } else { ?>
Please Enter Host Details First.
<? } ?>