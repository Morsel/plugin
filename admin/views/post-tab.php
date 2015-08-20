<?php
   if(isset(get_option( 'morsel_settings')['morsel_keywords']))
   {
   	   $old_option = get_option( 'morsel_settings');

	   $old_option['morsel_keywords'] = str_replace("'","",$old_option['morsel_keywords']);
	  
	   update_option("morsel_settings",$old_option);
   
   }
   $options = get_option( 'morsel_settings');

   $api_key = $options['userid'] . ':' .$options['key'];
      
   $jsonurl = MORSEL_API_URL."users/".$options['userid']."/morsels.json?api_key=$api_key&count=".MORSEL_API_COUNT."&submit=true";
   $json = json_decode(file_get_contents($jsonurl));
  
   if(get_option( 'morsel_post_settings')){
   		$morsel_post_settings = get_option( 'morsel_post_settings');	   		
   } else {
   		$morsel_post_settings = array();
   }
   
   if(array_key_exists('posts_id', $morsel_post_settings))
   	$post_selected = $morsel_post_settings['posts_id'];
   else
   	$post_selected = array();
?>

<?php 
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
?>

<?php if(count($json->data)>0){?>   
 <form method="post" action="options.php" id="morsel-form">
      <?php settings_fields( 'morsel_post_settings' ); ?>
      <?php do_settings_sections( 'morsel_post_settings' ); ?>   
      <p>
      	<h3>Selected posts will not be display on your site.</h3>
      </p>
 


	
	<table class="wp-list-table widefat posts fixed">
	<thead>
	<tr>
		<th scope='col' id='cb' class='manage-column column-cb check-column'  style="">
		  <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
		  <input id="cb-select-all-1" type="checkbox" />
		</th>
		<th scope='col' id='title' class='manage-column column-title sortable desc'  style="">
		  <span>Title</span>
		</th>
		<th scope='col' id='author' class='manage-column column-author'  style="">Image</th>
		<th scope='col' id='categories' class='manage-column column-categories'  style="">
		Description</th>
		<th scope='col' id='date' class='manage-column column-date sortable asc'  style="">
  		     <span>Date</span>
  		</th>
  		<th scope='col' id='action' class='manage-column column-action' > 
  			<span>Actions</span>
  		</th>
  	</tr>
	</thead>

	<tfoot>
	<tr>
		<th scope='col'  class='manage-column column-cb check-column'  style="">
		  <label class="screen-reader-text" for="cb-select-all-2">Selecsdt All</label>
		  <input id="cb-select-all-2" type="checkbox" />
		</th>
		<th scope='col'  class='manage-column column-title sortable desc' style="">
		  <span>Title</span>
		</th>
		<th scope='col' class='manage-column column-author' style="">Image</th>
		<th scope='col' class='manage-column column-categories' style="">Description</th>
		<th scope='col' class='manage-column column-date sortable asc' style="">
		  <span>Date</span>
		</th>
		<th scope='col' id='action' class='manage-column column-action' > 
  			<span>Actions</span>
  		</th>
	</tr>
	</tfoot>
   
	<tbody id="the-list">

	 <?php foreach ($json->data as $row) {     ?>
         
        

	    <tr id="morsel_post-<?php echo $row->id;?>" class="post-<?php echo $k;?> type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0">
		    <th scope="row" class="check-column">
				<input id="cb-select-"<?php echo $k;?> type="checkbox" name="morsel_post_settings[posts_id][]" value="<?php echo $row->id?>"  
				<?php echo (in_array($row->id, $post_selected))?"checked":""?> />
				<!-- <input <?php echo (in_array($row->id, $post_selected))?"disabled":""?> type="hidden" name="morsel_post_settings[data][]" value='<?php echo json_encode($tmpData);?>'> -->
				
			</th>
			<td class="post-title page-title column-title">
			    <strong>
			    <?php if($row->is_submit) { ?>
			    <a style="color:red;" href="<?php echo $row->url?>" target="_blank"><?php echo $row->title?><b style="font-size:15px;">&nbsp;(UNPUBLISHED)</b></a>
			    <?php }else{ ?>
			    <a href="<?php echo $row->url?>" target="_blank"><?php echo $row->title?>
			    <?php } ?></a>
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
			    <abbr title="<?php echo date("m/d/Y", strtotime($row->created_at));?>"><?php echo date("m/d/Y", strtotime($row->created_at));?></abbr>
			    <br />PUBLISHED
			    <?php } else { echo "NULL";} ?>
			   
			</td>
			<td>	
			   <?php if($row->is_submit) { ?>
			   		<?php add_thickbox(); ?>
					<a style=" margin-bottom: 5px;" morsel-id = "<?php echo $row->id ?>" class="all_morsel_keyowrd_id button">Pick Keywords</a>
					<br>
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
	<?php  $morsel_keywords = json_decode(get_option("morsel_settings")['morsel_keywords']);
   	 ?>
	 <select id = "select_keyword_id" multiple="" class="widefat">
	 <option value="blank">Select keyword for morsel :</option>
	 </select>
	 <br><br>
	 <a id = "morsel_keyword_button" class="button button-primary "> Pick </a>
	 &nbsp;&nbsp;
	 <a id = "morsel_keyword_close" class="button">Close</a>
	
	 </form>

</div>
<div class="clear"><br></div>
<div>
<?php submit_button("Save","primary","Select",null,array( 'id' => 'morsel_post_selection' ) ); ?>
<input type="button" value="Load more!" class="button" id="admin_loadmore" name="load" style="margin-left:20px"></div>
</form>

<?php } else { ?>
  <p><h3>Oops! You don't have any post on your site.</h3></p>
<?php } ?>

<script type="text/javascript">

		function get_morsel(morsel_id)
		{
			   

		   jQuery.ajax({
					url:"<?php echo MORSEL_API_URL?>"+"morsels/"+morsel_id,
					type:"GET",
					data:{
		    				api_key:"<?php echo $api_key ?>"
		  			},
					success: function(response) {
					  //console.log("reloader",response);
					  if(!response.data.photos)
					  {
					   	setTimeout(function(){
         						get_morsel(morsel_id);
    							},1000);
    				  }
    				  else
    				  {
    				  	window.location.reload(true);
    				  } 
											
					},error:function(){
					
					},complete:function(){
											
					}
		        });
		}
    jQuery('#morsel_keyword_close').click(function(){
		jQuery( "#TB_closeWindowButton" ).trigger( "click" )
	});


	jQuery('.all_unpublish_morsel_id').click(function(){
		
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

						    //console.log('current response',response);
						    if(response.data =="NOT"){
						    	alert("Please add morsel keyword or host detail first");
						    	all_unpublish_morsel_id.addClass('button').text('Publish Morsel');
						    }
						    else{
						     get_morsel(morsel_id);
						    }
							
					},error:function(){
							console.log("Some issue to add keywords to morsel");
					},complete:function(){
											
					}
		        });
			});
        
	   jQuery('.all_morsel_keyowrd_id').click(function(){
		
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

							var all_keywords =JSON.parse('<?php echo get_option("morsel_settings")["morsel_keywords"]; ?>');
							if(response.data=="empty" || response.data=="blank"){

        						alert('Please add keyword list first!');
        				
							}
							else{

							var saved_keywords = response.data;
							 //console.log('keyword response',response);
							 // console.log('keyword all keyword',all_keywords);
							jQuery('#select_keyword_id option[value!="blank"]').remove();
                            var html = '';
							  								
  								jQuery.each(all_keywords, function( all_keywords_index,all_keyword){
  								
                                    if(jQuery.inArray(all_keyword.id, saved_keywords) !== -1){
  										
  										html = '<option selected="selected" value="'+all_keyword.id+'">'+all_keyword.name+'</option>';	;
  										
  									}
  									else
  									{
  									html = '<option  value="'+all_keyword.id+'">'+all_keyword.name+'</option>'
  									
  									}
  								
  									jQuery('#select_keyword_id').append(html);
  							
								});
				
						
							var url = "#TB_inline?width=500&height=200&inlineId=modal-window-id";
        					tb_show("Add Morsel Keywords", url);
        					}
        					
					},error:function(){
							console.log("Some issue to add keywords to morsel");
					},complete:function(){
											
					}
		        });
			});

	 	jQuery('#morsel_keyword_button').click(function(){
	         
	        if(jQuery('#select_keyword_id').val() =="blank")
	        {
	        	alert('Please select keywords first');
	        	return;
	        }
	        var selected_keywords = jQuery('#select_keyword_id').val();
	        selected_keywords = selected_keywords.splice( !jQuery.inArray('blank', selected_keywords));
	        
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
					alert("Morsels keyword updated successfully");
					tb_remove();	
				 	
				} else {
					alert("Wrong credential"); 
				  	return false;     
				}

			   },
			   error:function(response){
			   	alert("You have entered wrong Username or Password!");
			   	
			   },complete:function(){
			   	jQuery('#morsel_keyword_button').val('please wait!');
			   }
			});
	 });

</script>
