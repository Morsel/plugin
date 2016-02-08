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
				if(response.data != ""){
                   console.log("response--------------------",response.data);
                   jQuery("#morsel_associated_user_list").html("");
                   var data = response.data;         
					/*create option for shortcode tab*/ 
		  	     	var sel = jQuery('#morsel_shortcode_user');
		            for(var k in data){
		                var html = '<tr id="delete'+data[k].associated_user["id"]+'" class="type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0 deleteUser">';
		            	html +='<td class="post-title page-title column-title associatedUserListTD"><strong>'+data[k].associated_user["id"]+'</strong></td>';            
						html +='<td class="categories column-categories associatedUserListTD">'+data[k].associated_user["username"]+'</td>';
		                html +='<td class="date column-date associatedUserListTD">'+data[k].associated_user["email"]+'</td>';
						html +='<td class="edit-btn column-categories associatedUserListTD">';
                        if(data[k].is_approved == "true"){
						    html +='<img width="15" src="<?=MORSEL_PLUGIN_IMG_PATH?>green-circle.png"/></td>';
						    sel.append(jQuery("<option>").attr('value',data[k].associated_user["id"]).text(data[k].associated_user["fullname"]));
						} else {
						    html +='<img width="15" src="<?=MORSEL_PLUGIN_IMG_PATH?>red-circle.png"/></td>';
			            }
			            html +='<td class="associatedUserListTD"><a href="javascript:void(0);" onclick="deleteUser('+data[k].associated_user["id"]+')">Delete</td>';
						html +='</tr>';
		                jQuery("#morsel_associated_user_list").append(html);
		            }
		        } else {            	
				    var html = '<tr><td class="noResult" colspan="5">NO RESULT FOUND</td></tr>';
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

<style type="text/css">
/*    .noResult{ text-align: center; font-weight: bold;}
	.associatedUserDiv { width: 100%; overflow: auto;}
	.associatedUserList tr:hover { background: #FFFFFF;}
	.associatedUserListTD { text-align: center !important;}
	.associatedAdd td { padding: 0;}
	.associatedAdd td input[type="text"] { margin: 0; width: 300px;}
	.associatedAdd td input[type="button"] { margin: 10px 0 ;}
	.associatedAdd .textTd { width: 285px; margin-bottom:8px;}
	#morsel_associated_user_list {display: "table-cell" !important;}
	#ajaxLoaderPostForAssociatedUser {display:none; text-align:center;}
*/
</style>

<form method="post"> 	         	
   	<table class="form-table associatedAdd">
  		<tr>  			
  			<td class="textTd">Request To Morsel User (Username/Email) :</td>
			<td>
				<input type="hidden" name="api_key" id="admin-user-key" value="<?php echo $options['userid'].':'.$options['key'] ?>"/>
				<input type="hidden" name="admin[user_id]" id="admin-user-userid" value="<?php echo $options['userid'] ?>"/>
				<input type="text" name="associated[name]" id="associated_user_name" value=""/>
			</td>
  		</tr>  		
		<tr>
  			<td>&nbsp;</td>
			<td><input type="button" class="button button-primary" name="save" id="associated-user-submit" value="Add User" onclick="addAssociatedUser()"></td>
  		</tr>
	</table>
</form>
<div id="ajaxLoaderPostForAssociatedUser">
    <span><img src="<?php echo MORSEL_PLUGIN_IMG_PATH;?>ajax-loader.gif"></span>
</div>
<div class="associatedUserDiv">
 <table class="widefat associatedUserList">
	<thead>
		<tr>
			<th class='manage-column column-categories associatedUserListTD'><span>User ID</span></th>
			<th class='manage-column column-title sortable desc associatedUserListTD'><span>User Name</span></th>
			<th class='manage-column column-date sortable asc associatedUserListTD'><span>Email</span></th>
			<th class='manage-column column-date sortable asc associatedUserListTD'>Accepted</th>
			<th class='manage-column column-date sortable asc associatedUserListTD'>Action</th>
	  	</tr>
	</thead>
	<tfoot>
		<tr>			
			<th class='manage-column column-categories associatedUserListTD'>User ID</th>
			<th class='manage-column column-title sortable desc associatedUserListTD'><span>User Name</span></th>
			<th class='manage-column column-date sortable asc associatedUserListTD'><span>Email</span></th>
			<th class='manage-column column-date sortable asc associatedUserListTD'>Accepted</th>
			<th class='manage-column column-date sortable asc associatedUserListTD'>Action</th>	
		</tr>
	</tfoot>
	<tbody id="morsel_associated_user_list"></tbody>
 </table>
</div> 

<script type="text/javascript">
	function deleteUser(userId){
		var user_id = jQuery("#admin-user-userid").val();
		jQuery.ajax({
				   url:  "<?php echo MORSEL_API_URL;?>"+"users/"+user_id+"/delete_association_request.json",
					type: "DELETE",
					data: {
	    				association_request_params:{associated_user_id:userId},
	    			    api_key:jQuery("#admin-user-key").val()
	  				},	  				
					success: function(response) {
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