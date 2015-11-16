<?php
/**
 * Represents the view for the administration dashboard.
 *
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
// error_reporting(-1);
// ini_set('display_errors', '1');
?>

<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<div id="tab-container" class='tab-container'>
		<ul class='etabs'>
			<li class='tab'><a href="#tabs1-settings">Settings</a></li>
			<li class='tab'><a href="#associated_user">Associated User</a></li>
			<li class='tab'><a href="#tabs1-js">Morsel</a></li>
			<li class='tab'><a href="#morsel_keywords_panel">Manage Keywords</a></li>
			<li class='tab'><a href="#morselTopic">Morsel Topic</a></li>
			<li class='tab'><a href="#tabs1-shortcode">Display</a></li>
			<!-- <li class='tab'><a href="#tabs1-morsel_advanced_tab">Advanced</a></li> -->
            <li class='tab'><a href="#sliderTab">Morsel Slider</a></li>
		</ul>
	    <div class='panel-container'>
    		<div id="tabs1-settings">
         	<?php 
		     	$options =array('apikey'=>'','email'=>'','password'=>'','page'=>10); 	     	
		     	if(get_option('morsel_settings')){
		     	  $options = array_merge($options,get_option('morsel_settings'));	 
		     	  $morselSettings = get_option('morsel_settings');    	
		 	    }
		 	    // $morselSettings["userid"];
		 	?>
				<form method="post" action="options.php" id="morsel-form">
					<?php settings_fields( 'morsel_settings' ); ?>
					<?php do_settings_sections( 'morsel_settings' ); ?>
					<input type="hidden" name="morsel_settings[userid]" id="morsel-userid" value="<?php echo $options['userid'] ?>"/>
					<input type="hidden" name="morsel_settings[key]" id="morsel-key" value="<?php echo $options['key'] ?>"/>
					<input type="hidden" name="morsel_settings[morsel_keywords]" id="morsel-keywords" value=""/>
					<input type="hidden" name="morsel_settings[morsel_associated_user]" id="morsel_associated_user" value=""/>
					<input type="hidden" name="morsel_settings[morsel_advanced_tab]" id="morsel_advanced_tab" value=""/>
					<table class="form-table">
						<tr valign="top">
							<td scope="row">UserName:</td>
							<td><input type="text" name="morsel_settings[email]" id="morsel_username" value="<?php echo $options['email'] ?>"/></td>
						</tr>
						<tr valign="top">
							<td scope="row">Password:</td>
							<td><input type="password" name="morsel_settings[password]" id="morsel_password"/></td>
						</tr>
						<tr valign="top">
							<td scope="row">&nbsp;</td>
							<td><?php submit_button("Connect","primary","save",null,array( 'id' => 'morsel_submit' ) ); ?></td>
						</tr>    		
					</table>
				</form>	

			  <!-- Host Details Form -->
			  <h2>Host Details </h2>
			  <?php 
				//  delete_option('morsel_host_details');
				// //set morsel host details info if they exists	
			 //   	if(get_option('morsel_host_details')){

				// 	$options = array_merge($options,get_option('morsel_host_details'));     	
				// } else {
					
					//set morsel host details info if they exists from API
					$ms_options = get_option( 'morsel_settings');
					if(isset($ms_options['userid']) && $ms_options['userid'] > 0) {
					    $api_key = $ms_options['userid'] . ':' .$ms_options['key'];      
				        $jsonurl = MORSEL_API_USER_URL."/me.json?api_key=".$api_key;    
				        $json = get_json($jsonurl);					           
					        if(isset($json->data->profile)){	
		                    	$hostCompany = $json->data->profile->company_name;
					      		$options = array_merge($options,array(
					      					'profile_id'=>$json->data->profile->id,
					      					// 'host_address'=>$json->data->profile->address,
					      					'host_logo_path'=>$json->data->profile->host_logo,
					      					'host_url'=>$json->data->profile->host_url,
					      					'host_company'=>$json->data->profile->company_name,
					      					'host_street'=>$json->data->profile->street_address,
					      					'host_city'=>$json->data->profile->city,
					      					'host_state'=>$json->data->profile->state,
					      					'host_zip'=>$json->data->profile->zip
					      				));
						    } else {
						      	$options = array_merge($options, array('profile_id'=>'','host_logo_path'=>'','host_url'=>'','host_company'=>'','host_street'=>'','host_city'=>'','host_zip'=>'','host_state'=>'')); 
						    }      	
				    } else {
				       $options = array_merge($options, array('profile_id'=>'','host_logo_path'=>'','host_url'=>'','host_company'=>'','host_street'=>'','host_city'=>'','host_zip'=>'','host_state'=>'')); 
				    }
				// } 
			?>
			<form method="post" action="options.php" id="morsel-host-details-form" >
			 	<?php settings_fields('morsel_host_details'); ?>
			  	<?php do_settings_sections( 'morsel_host_details' ); ?>          	
			   	<table class="form-table">
			  		<tr valign="top">
			  			<input type="hidden" style="width:50%" name="morsel_host_details[profile_id]" id="profile_id" value="<?php echo $options['profile_id'] ?>"/>
			  			<td scope="row">Host Url:</td>
						<td><input type="text" style="width:50%" name="morsel_host_details[host_url]" id="host_url" value="<?php echo $options['host_url'] ?>" disabled/></td>
			  		</tr>
			  		<tr valign="top">
			  			<td scope="row">Host Logo:</td>
			  			<td>
			  				<input type="text" style="width:50%" name="morsel_host_details[host_logo_path]" id="host_logo_path" value="<?php echo $options['host_logo_path']; ?>" /> 
			  				<input type="button" id="upload_image_button" value="Upload Logo" />
			  			</td>
			  		</tr>
			  		<tr valign="top">
			  			<td scope="row" style="vertical-align:top;">Host Address:</td>
			  			<td><!-- <textarea column="50" style="width:50%" rows="5" name="morsel_host_details[host_address]" id="host_address"/><?php echo $options['host_address']; ?></textarea> -->
			  			<input type="text" style="width:50%" name="morsel_host_details[host_company]" id="host_company" value="<?php echo $options['host_company'] ?>" placeholder="Company Name" />
			  			<input type="text" style="width:50%; margin-top:5px" name="morsel_host_details[host_street]" id="host_street" value="<?php echo $options['host_street'] ?>" placeholder="Street Address" />
			  			<input type="text" style="width:50%; margin-top:5px" name="morsel_host_details[host_city]" id="host_city" value="<?php echo $options['host_city'] ?>" placeholder="City" />
			  			<input type="text" style="width:50%; margin-top:5px" name="morsel_host_details[host_state]" id="host_state" value="<?php echo $options['host_state'] ?>" placeholder="State"/>
			  			<input type="text" style="width:50%; margin-top:5px" name="morsel_host_details[host_zip]" id="host_zip" value="<?php echo $options['host_zip'] ?>" placeholder="Zip"/>
			  			</td>
			  		</tr>
					<tr valign="top">
			  			<td scope="row">&nbsp;</td>
						<td>
						<?php if(isset($ms_options['userid']) && $ms_options['userid'] > 0) {?>
						<?php submit_button("Save","primary","save",null,array( 'id' => 'morsel_host_submit' ) ); ?>
			  		    <? } else { ?>
	                    Please Enter username And Password first
			  		    <? } ?>
			  		    </td>
			  		</tr>    		
				</table>
			</form>
<script type="text/javascript">
	(function($){
		$("#morsel_host_submit_button").click(function(event){
			event.preventDefault();
			$("#morsel-host-details-form").valid();
		});
		$("#morsel-host-details-form").validate({
			  rules: {
			   "morsel_host_details[host_company]": {
			      required: true,
			      number: false		      
			    },
			    "morsel_host_details[host_street]": {
			      required: true,
			      number: false		      
			    },
			    "morsel_host_details[host_city]": {
			      required: true,
			      number: false		      
			    },
			    "morsel_host_details[host_state]": {
			      required: true,
			      number: false		      
			    },
			    "morsel_host_details[host_zip]": {
			      required: true,
			      number: false		      
			    }		    
			  },
			  messages: {
			    "morsel_host_details[host_company]": {
			      required: "Please enter Company Name."
			    },
			    "morsel_host_details[host_street]": {
			      required: "Please enter Street Name."
			    },
			    "morsel_host_details[host_city]": {
			      required: "Please enter City Name."
			    },
			    "morsel_host_details[host_state]": {
			      required: "Please enter State Name."
			    },
			    "morsel_host_details[host_zip]": {
			      required: "Please enter Zip Code."
			    }
			  }
		});		
	}(jQuery))
</script>
		  <!-- Host Details Form End -->	
			
	</div>
   	<div id="associated_user">        
        <?php include_once("associated-user-tab.php");?>
	</div>
    <div id="tabs1-js">
        <?php include_once("post-tab.php");?>
	</div>
	<div id="morselTopic" style="display:none">        
        <?php include_once("morselTopic.php");?>
	</div>	
	<div id="morsel_keywords_panel">	
	    <?php include_once("morsel-keywords-tab.php");?>
	</div>
	<div id="tabs1-shortcode">        
        <?php include_once("shortcode-tab.php");?>	    
    </div>
	<div id="tabs1-morsel_advanced_tab"  style="display:none;">        
        <?php include_once("advanced.php");?>	    
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
<script type="text/javascript" src="<?php echo MORSEL_PLUGIN_ADMIN_ASSEST;?>editor/scripts/widgEditor.js"></script>

</div>
