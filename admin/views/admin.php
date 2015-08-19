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
 */
?>

<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<div id="tab-container" class='tab-container'>
	 <ul class='etabs'>
	   <li class='tab'><a href="#tabs1-settings">Settings</a></li>
	   <li class='tab'><a href="#tabs1-js">Post</a></li>
	   <li class='tab'><a href="#morsel_keywords_panel">Manage Keywords</a></li>
	   <li class='tab'><a href="#host_details">Host Details</a></li>
	   <li class='tab'><a href="#tabs1-shortcode">Short Code</a></li>
	 </ul>
	 <div class='panel-container'>
		<div id="tabs1-settings">

     	<?php 
	     	$options =array('apikey'=>'','email'=>'','password'=>'','page'=>10); 	     	
	     	if(get_option('morsel_settings'))
	     	$options = array_merge($options,get_option('morsel_settings'));	     	
     	?>
         <form method="post" action="options.php" id="morsel-form">
         
          <?php settings_fields( 'morsel_settings' ); ?>
          <?php do_settings_sections( 'morsel_settings' ); ?>
          	<input type="hidden" name="morsel_settings[userid]" id="morsel-userid" value="<?php echo $options['userid'] ?>"/>
            <input type="hidden" name="morsel_settings[key]" id="morsel-key" value="<?php echo $options['key'] ?>"/>
            <input type="hidden" name="morsel_settings[morsel_keywords]" id="morsel-keywords" value=""/>
               <table class="form-table">
		      		<tr valign="top">
		      			<td scope="row">UserName:</td>
	      				<td>
	      					<input type="text" name="morsel_settings[email]" id="morsel_username" value="<?php echo $options['email'] ?>"/>
	      				</td>
		      		</tr>
		      		<tr valign="top">
		      			<td scope="row">Password:</td>
		      			<td><input type="password" name="morsel_settings[password]" id="morsel_password"/></td>
		      		</tr>

		    		<tr valign="top">
		      			<td scope="row">&nbsp;</td>
	      				<td>
	      					<?php submit_button("Connect","primary","save",null,array( 'id' => 'morsel_submit' ) ); ?>	      					
	      				</td>
		      		</tr>    		
    		</table>
		  </form>		
			
	 </div>

	 <div id="tabs1-js">
	 	<?php if($options['key']){?>
          <?php include_once("post-tab.php");?>
	    <?php } else {?>
           Sorry, You have to authenticate first with any of Wordpress Login. Thankyou. 
	    <?php } ?> 
	 </div>
	 <div id="host_details">        
        <?php if($options['key']){?>
          <?php include_once("host-details-tab.php");?>
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
	 <div id="tabs1-shortcode">        
        <?php include_once("shortcode-tab.php");?>	    

	 </div>
</div>
</div>

<script type="text/javascript">
    jQuery(document).ready( function() {
      jQuery('#tab-container').easytabs();
    });
</script>
<script>
function getKeywords(userid,auth_key){
	console.log(userid+" "+auth_key)
	jQuery.ajax({
		url:  "<?php echo MORSEL_API_URL_TEST;?>"+"keywords/show_morsel_keyword",
		type: "POST",
		data: {
				keyword:{user_id:421},
				api_key:"421:vCw4-xiAtHYjzwAxZUUz"
			},
		success: function(response) {
			console.log(response.data);
			jQuery("#morsel-keywords").val(JSON.stringify(response.data));
			jQuery("#morsel-form").submit();
		},error:function(){
			Alert('Error in getting morsel keywords of user');
		},complete:function(){
			console.log('Getting morsel keywords is complete');
		}
    });
    return true;
}

window.onload =function(){

	

	jQuery( "#morsel_submit" ).click(function(e) {
		console.log("morsel_submit_called");
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
				 	//get morsel keywords of user
				 	getKeywords(response.data.id,response.data.auth_token);				 	
				 	//jQuery( "#morsel-form" ).submit();
				} else {
					alert("Wrong credential"); 
				  	return false;     
				}

			   },
			   error:function(response){
			   	alert("You have entered wrong Username or Password!");
			   	
			   },complete:function(){
			   	jQuery('#morsel_submit').val('Connect');
			   }
			});
		}); 
	
}
(function($){
		var morsePageCount = 1;	
		$('#admin_loadmore').click(function(){
			var load = $(this);
			load.val('Fetching... Please wait.');
			
			$.ajax({
				url:  "<?php echo site_url()?>"+"/index.php?pagename=morsel_ajax_admin&page_id=" + parseInt(++morsePageCount),          
				success: function(data) {
					if(data.trim().length>1)						                      
				    	$( "#the-list" ).append( data );
					else{
						morsePageCount--;
						alert("No more morsel.")
					}

				},error:function(){
					morsePageCount--;
				},complete:function(){load.val('Load more!');}
	        });	
		});

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
			
			jQuery('#morsel_host_submit').val('Please wait!'); 

			if(jQuery("#profile_id").val() == ""){
				var userData =  { 
									api_key:"<?php echo get_option('morsel_settings')['userid'].':'.get_option('morsel_settings')['key']; ?>",
									user:{
										profile_attributes:{
											host_url: jQuery("#host_url").val(),
											host_logo: jQuery("#host_logo_path").val(),
											address : jQuery("#host_address").val()
										}
									}
								};	
				console.log(userData);
			} else {
				var userData =  { 
									api_key:"<?php echo get_option('morsel_settings')['userid'].':'.get_option('morsel_settings')['key']; ?>",
									user:{
										profile_attributes:{
											id:jQuery("#profile_id").val(),
											host_url: jQuery("#host_url").val(),
											host_logo: jQuery("#host_logo_path").val(),
											address : jQuery("#host_address").val()
										}
									}
								};	
			}			
			console.log("Userdata : ",userData);
			jQuery.ajax({
				url: "<?php echo MORSEL_API_USER_URL.get_option('morsel_settings')['userid'].'.json';?>",
				data: userData,
				type:'PUT',
				success: function(response){	
				jQuery('#morsel_host_submit').val('Save');				
					console.log("Success Response : ",response);
					if(response.meta.status == 200){	
						jQuery("#profile_id").val(response.data.profile.id); 	
					 	jQuery("#morsel-host-details-form").submit();
					} else {
						alert("Opps something has gone wrong!"); 
						return false;     
					}
				},
			   	error:function(response){
			   		console.log("Error Response : ",response);
			   		alert("Opps something has gone wrong!"); 
			   	},complete:function(){
			   		jQuery('#morsel_host_submit').val('Connecting');
			   	}
			});
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
		        console.log(uploaded_image);
		        var image_url = uploaded_image.toJSON().url;
		        // Let's assign the url value to the input field
		        $('#host_logo_path').val(image_url);
		    });
		});

	}(jQuery))
		

</script>


</div>
