<?php 
	 if(isset(get_option( 'morsel_settings')['morsel_keywords']))
   {
   	   $old_option = get_option( 'morsel_settings');

	   $old_option['morsel_keywords'] = str_replace("'","",$old_option['morsel_keywords']);
	  
	   update_option("morsel_settings",$old_option);
   
   }
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
<form method="post" action="" id="morsel-host-keywords-form"> 	         	
   	<table class="form-table">
  		<tr valign="top">  			
  			<td scope="row">Keyword Name:</td>
			<td>
				<input type="hidden" name="api_key" id="kwd-morsel-key" value="<?php echo $options['userid'].':'.$options['key'] ?>"/>
				<input type="hidden" name="keyword[user_id]" id="kwd-morsel-userid" value="<?php echo $options['userid'] ?>"/>
				<input type="hidden" name="updated_keywords" id="updated_keywords" value=""/>
				<input type="hidden" name="keyword_id" id="keyword_id" value=""/>
				<input type="text" style="width:50%" name="keyword[name]" id="keyword_name" value=""/>
			</td>
  		</tr>  		
		<tr valign="top">
  			<td scope="row">&nbsp;</td>
			<td><?php submit_button("Save","primary","morsel-keywords-form",null,array('id'=>'morsel-keywords-submit')); ?></td>
  		</tr>
	</table>
</form>
<table class="wp-list-table widefat posts">
	<thead>
		<tr>
			<th scope='col' id='keyword-id' class='manage-column column-categories'  style=""><span>Keyword ID</span></th>
			<th scope='col' id='keyword-name' class='manage-column column-title sortable desc'  style=""><span>Keyword Name</span></th>
			Description</th>
			<th scope='col' id='date' class='manage-column column-date sortable asc'  style=""><span>Date</span></th>
			<th scope='col' class='manage-column column-date sortable asc' style="">Actions</th>
	  	</tr>
	</thead>
	<tfoot>
		<tr>			
			<th scope='col' id='keyword-id' class='manage-column column-categories'  style="">Keyword ID</th>
			<th scope='col' id='keyword-name' class='manage-column column-title sortable desc'  style=""><span>Keyword Name</span></th>
			<th scope='col' class='manage-column column-date sortable asc' style=""><span>Date</span></th>
			<th scope='col' class='manage-column column-date sortable asc' style="">Actions</th>	
		</tr>
	</tfoot>

	<tbody id="morsel-keyword-list">
		<?php if($options["morsel_keywords"]) {

				foreach(json_decode($options["morsel_keywords"]) as $row){ ?>
		<tr id="morsel_keyword-<?php echo $row->id;?>" class="post-<?php echo $k;?> type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0">		    
			<td class="post-title page-title column-title">
			    <strong><?php echo $row->id?></strong>
			</td>            
			<td class="categories column-categories" id="keyword-name-<?php echo $row->id;?>"><?php echo $row->name;?></td>
			<td class="date column-date">
			    <abbr title="<?php echo date("d/m/Y", strtotime($row->created_at));?>"><?php echo date("m/d/Y", strtotime($row->created_at));?></abbr><br />Created
			</td>
			<td class="edit-btn column-categories">
			  <button class="edit-keyword-btn" id="<?php echo $row->id;?>">Edit</button>
			</td>
		</tr>
		<?php }
		}?>
	</tbody>
</table>
<div class="clear"><br></div>

<script type="text/javascript">
	(function($){
		$("#morsel-keywords-submit").click(function(event){
			event.preventDefault();
			if($("#keyword_name").val() != ""){

				var keywords_name = $("#keyword_name").val();
				
				var regex = /[^\w\s]/gi;

				if(regex.test(keywords_name) == true) {
				    alert('Your keyword string contains illegal characters.');
				    return;
				}
				
				$("#morsel-keywords-submit").val('Please wait!');
				
				if($("#keyword_id").val() != ""){ //for edit keyword
					
					$.ajax({
						url:  "<?php echo MORSEL_API_URL;?>"+"keywords/edit_morsel_keyword",
						type: "POST",
						data: {
		    				keyword:{
		    					id:$("#keyword_id").val(),
		    					name:keywords_name
		    					},
		    				api_key:$("#kwd-morsel-key").val()
		  				},
						success: function(response) {
							//console.log("Response in edit keywords : ",response.data);
							$("#morsel-host-keywords-form").submit();
							
							
						},error:function(){
							console.log('Error in edit morsel keywords');
						},complete:function(){
							$("#morsel-keywords-submit").val('Connect');
							console.log('Edit morsel keywords is complete');
						}
		        	});
				} else { //for add keyword

					$.ajax({
						url:  "<?php echo MORSEL_API_URL;?>"+"keywords/add_morsel_keyword",
						type: "POST",
						data: {
		    				keyword:{user_id:$("#kwd-morsel-userid").val(),
		    				name:keywords_name
		    			    },
		    				api_key:$("#kwd-morsel-key").val()
		  				},	  				
						success: function(response) {

		  					//console.log("Response in add keywords : ",response.data);
		
							var keywords =  JSON.parse('<?php echo get_option("morsel_settings")["morsel_keywords"]?>');
							//console.log("current keywords : ",keywords);
							if(keywords=="blank") {
								keywords = [];
							   
					    	}
					    	//console.log("current new keywords : ",keywords);
					    	keywords.push(response.data);
							//console.log("new current keywords : ",keywords);
							$("#updated_keywords").val(JSON.stringify(keywords));
							$("#morsel-host-keywords-form").submit();
							
							$('#keyword_name').val('');
						},error:function(){
							console.log('Error in add morsel keywords');
						},complete:function(){
							$("#morsel-keywords-submit").val('Connect');
							console.log('Add morsel keywords is complete');
						}
		        	});	
				}

							
			} else {
				alert("Please fill the keyword text");
				$("#keyword_name").focus()
			}
		});

		$(".edit-keyword-btn").click(function(){
			var keyword_id = $(this).attr("id");
			$("#keyword_id").val(keyword_id);
			var keyword_name = $("#keyword-name-"+keyword_id).html();
			//console.log("keyword name for update : ",keyword_name);
			$("#keyword_name").val(keyword_name);			
			$("#keyword_name").focus();
		});
	}(jQuery));
</script>
