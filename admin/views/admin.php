<?php

/**
 * Represents the view for the administration dashboard.
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Morsel
 * @author    Nishant <nishant.n@cisinlabs.com>
 * @license   GPL-2.0+
 * @link      eatmorsel
 * @copyright 2014 Nishant
 * 
 */
?>


    <?php include_once("settingsMorsel.php");?>
			
	</div>
   	<div id="associated_user">        
        <?php if($options['key']){?>
          <?php include_once("associated-user-tab.php");?>
	    <?php } else {?>
           Sorry, You have to authenticate first with any of Wordpress Login. Thankyou. 
	    <?php } ?>
	</div>
    <div id="tabs1-js">
	 	<?php if($options['key']){?>
          <?php include_once("post-tab.php");?>
	    <?php } else {?>
           Sorry, You have to authenticate first with any of Wordpress Login. Thankyou. 
	    <?php } ?> 
	</div>
	<div id="host_details" style="display:none">        
        <?php if($options['key']){?>
            <?php //include_once("host-details-tab.php");?>
	    <?php } else {?>
           Sorry, You have to authenticate first with any of Wordpress Login. Thankyou. 
	    <?php } ?>
	</div>	
	<div id="morsel_keywords_panel">	
	    <?php if($options['key']){?>
           <?php include_once("morsel-keywords-tab.php");?>
	    <?php } else {?>
           Sorry, You have to authenticate first with any of Wordpress Login. Thankyou. 
	    <?php } ?>
	</div>
	<div id="morselTopic" style="display:none">
           <?php include_once("morselTopic.php");?>
    </div>
	<div id="tabs1-shortcode">        
        <?php include_once("shortcode-tab.php");?>	    
    </div>
	<div id="tabs1-morsel_advanced_tab">        
        <?php //include_once("advanced.php");?>	    
    </div>
    <div id="sliderTab">
        <?php include_once("slider.php");?>
    </div>
	</div>
</div>
</div>

<script type="text/javascript">
    jQuery(document).ready( function() {
      jQuery('#tab-container').easytabs();
    });
</script>
<script>
function addprofile(userid,auth_key){
	jQuery.ajax({
		url: "<?php echo MORSEL_API_USER_URL?>"+ userid+"/create_profile.json",
		data: {
			api_key: auth_key				 
		},
		type:'POST',
		success: function(response){	
			if(response.meta.status == 200){	
				jQuery("#profile_id").val(response.data.id); 	
				jQuery( "#morsel-form" ).submit();
			} else {
				alert("Opps something has gone wrong!"); 
				return false;     
			}
		},
	   	error:function(response){
	   		console.log("Error Response : ",response);
	   		alert("Opps something has gone wrong!"); 
	   	},complete:function(){}
	});
}

function host_url () {
	base_url = window.location.protocol+'//'+window.location.hostname;
	jQuery( "#host_url" ).val(base_url);
}

window.onload =function(){
	host_url();
	jQuery( "#morsel_submit" ).click(function(e) {
		///console.log("morsel_submit_called");
		e.preventDefault();
		if(jQuery( "#morsel_username" ).val() == ""){
			 alert("Please Fill UserName");
			 return false;
		}
		if(jQuery( "#morsel_password" ).val() == ""){
			 alert("Please Fill UserName");
			 return false;
		}
  
		jQuery('#morsel_submit').val('Please wait!');
		jQuery.ajax({
			url: "<?php echo MORSEL_API_URL.'users/sign_in.json';?>",
			data: 'user[email]='+jQuery( "#morsel_username" ).val()+'&user[password]='+jQuery( "#morsel_password" ).val(),
			type:'post',
			success: function(response){				
				if(response.meta.status == 200){
				 	jQuery( "#morsel-userid" ).val(response.data.id);
				 	jQuery( "#morsel-key" ).val(response.data.auth_token);
				 	var auth_key = response.data.id+":"+response.data.auth_token;
				 	addprofile(response.data.id,auth_key);	
				} else {
					alert("Wrong credential"); 
				  	return false;     
				}
   		    }, error:function(response){
			   	alert("You have entered wrong Username or Password!");
			}, complete:function(){
			   	jQuery('#morsel_submit').val('Connect');
			}
		});
	}); 
}
(function($){
		/*save host details function*/
		jQuery( "#morsel_host_submit" ).click(function(e) {
			e.preventDefault();
			if(jQuery("#host_url").val() == ""){
				alert("Please Fill Host URl");
				return false;
			}
			if(jQuery("#host_logo_path").val() == ""){
				alert("Please Fill Absoulte Host Logo Path");
				return false;
			}
	  		if(jQuery("#host_address").val() == ""){
				alert("Please Fill Addess Of The Host Site Organisation/Person");
				return false;
			}
			if($("#morsel-host-details-form").valid()){
			   jQuery('#morsel_host_submit').val('Please wait!'); 
            if(jQuery("#profile_id").val() == ""){
				var userData =  { 
						api_key:"<?php echo $morselSettings['userid'].':'.$morselSettings['key']; ?>",
						user:{
							profile_attributes:{
								host_url: jQuery("#host_url").val(),
								host_logo: jQuery("#host_logo_path").val(),
								company_name : jQuery("#host_company").val(),
								street_address : jQuery("#host_street").val(),
								city : jQuery("#host_city").val(),
								state : jQuery("#host_state").val(),
								zip : jQuery("#host_zip").val()
							}
						}
					};	
			} else {
				var userData =  { 
						api_key:"<?php echo $morselSettings['userid'].':'.$morselSettings['key']; ?>",
						user:{
							profile_attributes:{
					    		id:jQuery("#profile_id").val(),
								host_url: jQuery("#host_url").val(),
								host_logo: jQuery("#host_logo_path").val(),
								company_name : jQuery("#host_company").val(),
								street_address : jQuery("#host_street").val(),
								city : jQuery("#host_city").val(),
								state : jQuery("#host_state").val(),
								zip : jQuery("#host_zip").val()
							}
						}
				};	
			}			
			// console.log("Userdata : ",userData);
			jQuery.ajax({
				url: "<?php echo MORSEL_API_USER_URL.$morselSettings['userid'].'.json';?>",
				data: userData,
				type:'PUT',
				success: function(response){	
				jQuery('#morsel_host_submit').val('Save');				
					//console.log("Success Response : ",response);
					if(response.meta.status == 200){	
						jQuery("#profile_id").val(response.data.profile.id); 	
					 	jQuery("#morsel-host-details-form").submit();
					} else {
						alert("Opps something has gone wrong!"); 
						return false;     
					}
				}, error:function(response){
			   		console.log("Error Response : ",response);
			   		alert("Opps something has gone wrong!"); 
			   	}, complete:function(){
			   		jQuery('#morsel_host_submit').val('Connecting');
			   	}
			});
		}
	}); 
		
	$('#upload_image_button').click(function(e) {
	    e.preventDefault();
	    var image = wp.media({ 
	        title: 'Upload Image',
	        // mutiple: true if you want to upload multiple files at once
	        multiple: false
	    }).open()
	    .on('select', function(e){
	        // This will return the selected image from the Media Uploader, the result is an object
	        var uploaded_image = image.state().get('selection').first();
	        // We convert uploaded_image to a JSON object to make accessing it easier
	        // Output to the console uploaded_image
	        //console.log(uploaded_image);
	        var image_url = uploaded_image.toJSON().url;
	        // Let's assign the url value to the input field
	        $('#host_logo_path').val(image_url);
	    });
	});
}(jQuery))
</script>

</div>
<script src="http://cdn.ckeditor.com/4.5.5/standard-all/ckeditor.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.4/adapters/jquery.js"></script> 