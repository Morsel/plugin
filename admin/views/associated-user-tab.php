<?php  if(isset($hostCompany) && $hostCompany != ""){	?>
<script type="text/javascript">
	jQuery( document ).ready(function() {
		getAssociatedData("<?php echo $options['userid'] ?>","<?php echo $options['userid'].':'.$options['key'] ?>");
	});

	function getAssociatedData(userid,auth_key){
		jQuery("#ajaxLoaderPostForAssociatedUser").css("display","none");
		jQuery.ajax({
			url:  "<?php echo MORSEL_API_URL;?>"+"users/"+userid+"/association_requests",
			type: "GET",
			data: {
				api_key:auth_key
			},
			success: function(response) {
				if(response.data!=""){
                   console.log("response--------------------",response.data);
                   jQuery("#morsel_associated_user_list").html("");
                   var data = response.data;         

					/*create option for shortcode tab*/ 
		  	     	var sel = jQuery('#morsel_shortcode_user');
					// jQuery(data).each(function() {
					//     sel.append(jQuery("<option>").attr('value',this.associated_user["id"]).text(this.associated_user["fullname"]));
					// });	

		            for(var k in data){
		                var html = '<tr id="delete'+data[k].associated_user["id"]+'" class="type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0 deleteUser">';
		            	html +='<td class="post-title page-title column-title"><strong>'+data[k].associated_user["id"]+'</strong></td>';            
						html +='<td class="categories column-categories">'+data[k].associated_user["username"]+'</td>';
		                html +='<td class="date column-date">'+data[k].associated_user["email"]+'</td>';
						html +='<td class="edit-btn column-categories">';
                        if(data[k].is_approved == "true"){
						    html +='<div id="circle-green"><div></td>';
						    sel.append(jQuery("<option>").attr('value',data[k].associated_user["id"]).text(data[k].associated_user["fullname"]));
						} else {
						    html +='<div id="circle-red"><div></td>';
			            }
			            html +='<td><a href="javascript:void(0);" onclick="deleteUser('+data[k].associated_user["id"]+')">Delete</td>';
						html +='</tr>';
		                jQuery("#morsel_associated_user_list").append(html);
		            }
		        } else {            	
				    var html = '<tr><td></td><td><b>NO RESULT FOUND</b></td><td></td><td></td></tr>';
				    jQuery("#morsel_associated_user_list").prepend(html);
		        }
			}, error:function(){
				jQuery("#ajaxLoaderPostForAssociatedUser").css("display","none");
			},
			 complete:function(){
				console.log('Getting associated user is complete');
				jQuery("#ajaxLoaderPostForAssociatedUser").css("display","none");
			}
		});
    return true;
    }
</script>
<form method="post"> 	         	
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
			<td><input type="button" class="button button-primary" name="save" id="associated-user-submit" value="Add User" onclick="addAssociatedUser()"></td>
  		</tr>
	</table>
</form>
<div id="ajaxLoaderPostForAssociatedUser" style="display:none; text-align:center;">
    <span><img src="<?php echo MORSEL_PLUGIN_IMG_PATH;?>ajax-loader.gif"></span>
</div>
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

	<tbody id="morsel_associated_user_list"></tbody>
 </table>

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
					  	// jQuery( "#delete"+userId ).remove();
					    getAssociatedData("<?php echo $options['userid'] ?>","<?php echo $options['userid'].':'.$options['key'] ?>");
					},error:function(error){						
					  console.log("error response------------------",error);
					},complete:function(){}
	        	});	
	}
	function addAssociatedUser(){
        if(jQuery("#associated_user_name").val() != ""){
			var associated_username = jQuery("#associated_user_name").val();
			jQuery("#associated-user-submit").val('Please wait!');
			var user_id = jQuery("#admin-user-userid").val();
				jQuery.ajax({
				   url:  "<?php echo MORSEL_API_URL;?>"+"users/"+user_id+"/create_association_request",
					type: "POST",
					data: {
	    				association_request_params:{name_or_email:associated_username},
	    			    api_key:jQuery("#admin-user-key").val()
	  				},	  				
					success: function(response) {
						if(response.data != "Invalid user"){
				            getAssociatedData("<?php echo $options['userid'] ?>","<?php echo $options['userid'].':'.$options['key'] ?>");
					        jQuery("#associated-user-submit").val('Add User');				
							jQuery('#associated_user_name').val('');
						} else {
							alert("Opps You entered wrong username/email!"); 
							jQuery("#associated-user-submit").val('Add User');
						}
					},error:function(){
						jQuery("#associated-user-submit").val('Add User');
						console.log('Error in add morsel Request');
					},complete:function(){
						console.log('Add morsel Request is complete');
					}
	        	});	
			} else {
				alert("Please fill username or email!");
				jQuery("#associated_user_name").focus()
			}
	}
</script> 
<? } else { ?>
Please Enter Host Details First.
<? } ?>