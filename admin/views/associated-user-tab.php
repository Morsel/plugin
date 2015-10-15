<?php 
 if(isset($hostCompany) && $hostCompany != ""){			
			$api_key = $options['userid'] . ':' .$options['key'];
		  
		  	$jsonurl = MORSEL_API_URL."users/".$options['userid']."/association_requests.json?api_key=$api_key";
		    $json = json_decode(file_get_contents($jsonurl));
		    
		    if($json->data){
       		$new_associated = json_encode($json->data);
		    $new_settings = get_option("morsel_settings"); 
		    $new_settings['morsel_associated_user'] = ($new_associated);
	    	update_option("morsel_settings",$new_settings);
	    	
	    	
	    	}
	    	else
	    	{
	    		$new_associated = "";
			    $new_settings = get_option("morsel_settings"); 
			    $new_settings['morsel_associated_user'] = ($new_associated);
		    	update_option("morsel_settings",$new_settings);
		    	
	    	}
	
?>
<form method="post" action="" id="associated-host-user-form"> 	         	
   	<table class="form-table">
  		<tr valign="top">  			
  			<td scope="row" style="width:30%">Request To Morsel User (Username/Email) :</td>
			<td>
				<input type="hidden" name="api_key" id="admin-user-key" value="<?php echo $options['userid'].':'.$options['key'] ?>"/>
				<input type="hidden" name="admin[user_id]" id="admin-user-userid" value="<?php echo $options['userid'] ?>"/>
							
				<input type="text" style="width:50%" name="associated[name]" id="associated_user_name" value=""/>
			</td>
  		</tr>  		
		<tr valign="top">
  			<td scope="row">&nbsp;</td>
			<td><?php submit_button("Request","primary","associated-user-form",null,array('id'=>'associated-user-submit')); ?></td>
  		</tr>
	</table>
</form>
 <table class="wp-list-table widefat posts">
	<thead>
		<tr>
			<th scope='col' id='keyword-id' class='manage-column column-categories'  style=""><span>User ID</span></th>
			<th scope='col' id='keyword-name' class='manage-column column-title sortable desc'  style=""><span>User Name</span></th>
			<th scope='col' id='date' class='manage-column column-date sortable asc'  style=""><span>Email</span></th>
			<th scope='col' class='manage-column column-date sortable asc' style="">Accepted</th>
			<th scope='col' class='manage-column column-date sortable asc' style="">Action</th>
	  	</tr>
	</thead>
	<tfoot>
		<tr>			
			<th scope='col' id='keyword-id' class='manage-column column-categories'  style="">User ID</th>
			<th scope='col' id='keyword-name' class='manage-column column-title sortable desc'  style=""><span>User Name</span></th>
			<th scope='col' class='manage-column column-date sortable asc' style=""><span>Email</span></th>
			<th scope='col' class='manage-column column-date sortable asc' style="">Accepted</th>
			<th scope='col' class='manage-column column-date sortable asc' style="">Action</th>	
		</tr>
	</tfoot>

	<tbody id="morsel-keyword-list">
		<?php if(get_option( 'morsel_settings')["morsel_associated_user"]!="") {
			 
		   foreach(json_decode(get_option( 'morsel_settings')["morsel_associated_user"]) as $row){ ?>
				<tr id="delete<?=$row->associated_user->id;?>" class="type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0 deleteUser">		    
					<td class="post-title page-title column-title">
					    <strong><?php echo $row->associated_user->id?></strong>
					</td>            
					<td class="categories column-categories"><?php echo $row->associated_user->username;?></td>
					<td class="date column-date">
						<?php echo $row->associated_user->email;?>
					</td>
					<td class="edit-btn column-categories">
					   <?php
					  
					    if($row->is_approved!="false") { ?>

					   		<div id="circle-green"><div>

					   <?php 	}else{ ?>
					   			<div id="circle-red"><div>
					   <?php } ?>

					</td>
					<td><a href="javascript:void(0);" onclick="deleteUser('<?=$row->associated_user->id;?>')">Delete</td>
				</tr>
		<?php }
		}else{

			?>
			<tr>
				<td></td>
				<td><b>NO RESULT FOUND</b></td>
				<td></td>
				<td></td>
			</tr>

		<?php } ?>
	</tbody>
 </table>
 <input id= "previous" type="hidden" value = ""/>
 <div class="clear"><br></div>
<script type="text/javascript">
		function deleteUser(userId){
			// alert("user_id"+userId);
			var user_id = jQuery("#admin-user-userid").val();
			jQuery.ajax({
					   url:  "<?php echo MORSEL_API_URL;?>"+"users/"+user_id+"/delete_association_request.json",
						type: "DELETE",
						data: {
		    				association_request_params:{associated_user_id:userId},
		    			    api_key:jQuery("#admin-user-key").val()
		  				},	  				
						success: function(response) {
						  console.log("response------------------",response);
						  	jQuery( "#delete"+userId ).remove();
						},error:function(error){						
						  console.log("error response------------------",error);
						},complete:function(){
						  if(jQuery("#morsel-keyword-list .deleteUser").length == 0){
						  	jQuery( "#morsel-keyword-list" ).append( "<tr><td></td><td><b>NO RESULT FOUND</b></td><td></td><td></td></tr>" )
                          } 
						  // window.location.href=window.location.href;
						}
		        	});	
		}
	(function($){		
		$("#associated-user-submit").click(function(event){
			event.preventDefault();
			if($("#associated_user_name").val() != ""){

				var associated_username = $("#associated_user_name").val();
				
				$("#associated-user-submit").val('Please wait!');
					
					var user_id = $("#admin-user-userid").val();
					$.ajax({
					   url:  "<?php echo MORSEL_API_URL;?>"+"users/"+user_id+"/create_association_request",
						type: "POST",
						data: {
		    				association_request_params:{name_or_email:associated_username},
		    			    api_key:$("#admin-user-key").val()
		  				},	  				
						success: function(response) {
							//jQuery.grep(associated_username, function(e){ return e.id == response.data.id ; }).length > 0
							if(response.data != "Invalid user")
							{
					                <?php 
									if(get_option("morsel_settings")["morsel_associated_user"]!=""  )
									{
								 	?>
								 		associated_username = JSON.parse('<?php echo get_option("morsel_settings")["morsel_associated_user"]?>');
									<?php 
									} 
									?>
								if ($('#previous').val()== response.data.id)
								{
									alert("You have already sent Request to this user");
									$('#associated_user_name').val('');
									$("#associated-user-submit").val('Request');
								}
								else
								{	
									//$("#associated-host-user-form").submit();
									var requests = response.data;
								    var html = '<tr id=delete'+requests.associated_user.id+' class="type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0 deleteUser">';		    
									html+='<td class="post-title page-title column-title">';
					    			html+='<strong>'+requests.associated_user.id+'</strong>';
									html+='</td>';            
									html+='<td class="categories column-categories">'+requests.associated_user.username+'</td>';
									html+='<td class="date column-date">'+requests.associated_user.email+'</td>';
							    	html+='<td class="edit-btn column-categories">';
		   					   			if(requests.is_approved!="false"){
		   					   				html+='<div id="circle-green"></div></td>';
		   					   			}else{
		   					   				html+='<div id="circle-red"></div></td>';
		   					   			}
		   					   		html+='<td ><a href="javascript:void(0);" onclick="deleteUser('+requests.associated_user.id+')">Delete</td></tr>';		
		   					   		$('#morsel-keyword-list tr:last').after(html);
		   					    	var	is_no_record_tr = $('#morsel-keyword-list tr:first').text().trim();

		   					    	if(is_no_record_tr=="NO RESULT FOUND")
		   					    	{
		   					    		$('#morsel-keyword-list tr:first').remove();

		   					    	}
		   					    	$('#previous').val(response.data.id);
						        }
								$("#associated-user-submit").val('Request');												
								$('#associated_user_name').val('');
							}
							else
							{
								alert("Opps You entered wrong username/email!"); 
								$("#associated-user-submit").val('Request');
							}
						},error:function(){
							$("#associated-user-submit").val('Request');
							console.log('Error in add morsel Request');
						},complete:function(){
							console.log('Add morsel Request is complete');
						}
		        	});	
			} else {
				alert("Please fill username or email!");
				$("#associated_user_name").focus()
			}
		});		
	}(jQuery));
</script>
<? } else { ?>
Please Enter Host Details First.
<? } ?>