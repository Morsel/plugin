<style type="text/css">
	.settings td { padding: 0;} 
	.settings tr>td:first-child{
		width: 126px;
	}
	.settingsHost td { padding: 0; vertical-align: top;} 
	.settingsHost tr>td:first-child{
		width: 126px;
		padding: 7px 0 0 0;
	}
	.settingsHost input[type="text"] {
	  width:50%;
	}
	.settingsHost .buttonLogo {
	   margin: 0;	
	}
	.otherSettingsMorsel td { padding: 7px; vertical-align: top;}
	.etabs {display: block;}
	.mobileMenu { display: none;}
	@media screen and (max-width: 420px) {
	    .etabs{ display: none;}
	    .mobileMenu{display: block !important;}
    }
    input[type="text"],input[type="password"] { margin-bottom: 10px; }
</style>
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
		    <li class='tab'><a href="#sliderTab">Slider</a></li>
		</ul>
		<ul class="mobileMenu"> 
		    <select class="mobileMenuMorsel">
		    	<option id="tabs1-settings_li" value="#tabs1-settings">Settings</option>
		    	<option id="associated_user_li" value="#associated_user">Associated User</option>
		    	<option id="tabs1-js_li" value="#tabs1-js">Morsel</option>
		    	<option id="morsel_keywords_panel_li" value="#morsel_keywords_panel">Manage Keywords</option>
		    	<option id="morselTopic_li" value="#morselTopic">Morsel Topic</option>
		    	<option id="tabs1-shortcode_li" value="#tabs1-shortcode">Display</option>
		    	<option id="sliderTab_li" value="#sliderTab">Slider</option>
		    </select>
		</ul>    
	    <div class='panel-container'>
    		<div id="tabs1-settings">
         	<?php 
		     	$options =array('apikey'=>'','email'=>'','password'=>'','page'=>10); 	     	
		     	if(get_option('morsel_settings')){
		     	  $options = array_merge($options,get_option('morsel_settings'));	 
		     	  $morselSettings = get_option('morsel_settings');    	
		 	    }
		 	?>
				<form method="post" action="options.php" id="morsel-form">
					<?php settings_fields( 'morsel_settings' ); ?>
					<?php do_settings_sections( 'morsel_settings' ); ?>
					<input type="hidden" name="morsel_settings[userid]" id="morsel-userid" value="<?php echo $options['userid'] ?>"/>
					<input type="hidden" name="morsel_settings[key]" id="morsel-key" value="<?php echo $options['key'] ?>"/>
					<input type="hidden" name="morsel_settings[morsel_keywords]" id="morsel-keywords" value=""/>
					<input type="hidden" name="morsel_settings[morsel_associated_user]" id="morsel_associated_user" value=""/>
					<input type="hidden" name="morsel_settings[morsel_advanced_tab]" id="morsel_advanced_tab" value=""/>
					<table class="form-table settings">
						<tr valign="top">
							<td scope="row">Username:</td>
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
			?>
			<form method="post" action="options.php" id="morsel-host-details-form" >
			 	<?php settings_fields('morsel_host_details'); ?>
			  	<?php do_settings_sections( 'morsel_host_details' ); ?>          	
			  	<input type="hidden" style="width:50%" name="morsel_host_details[profile_id]" id="profile_id" value="<?php echo $options['profile_id'] ?>"/>
			   	<table class="form-table settingsHost">
			  		<tr>
			  			<td scope="row">Host Url:</td>
						<td><input type="text" name="morsel_host_details[host_url]" id="host_url" value="<?php echo $options['host_url'] ?>" disabled/></td>
			  		</tr>
			  		<tr>
			  			<td scope="row">Host Logo:</td>
			  			<td>
			  				<input type="text" name="morsel_host_details[host_logo_path]" id="host_logo_path" value="<?php echo $options['host_logo_path']; ?>" /> 
			  				<input type="button" id="upload_image_button" value="Upload Logo" class="button button-primary buttonLogo" />
			  			</td>
			  		</tr>
			  		<tr>
			  			<td scope="row">Host Address:</td>
			  			<td>
				  			<input type="text" name="morsel_host_details[host_company]" id="host_company" value="<?php echo $options['host_company'] ?>" placeholder="Company Name" />
				  			<input type="text" name="morsel_host_details[host_street]" id="host_street" value="<?php echo $options['host_street'] ?>" placeholder="Street Address" />
				  			<input type="text" name="morsel_host_details[host_city]" id="host_city" value="<?php echo $options['host_city'] ?>" placeholder="City" />
				  			<input type="text" name="morsel_host_details[host_state]" id="host_state" value="<?php echo $options['host_state'] ?>" placeholder="State"/>
				  			<input type="text" name="morsel_host_details[host_zip]" id="host_zip" value="<?php echo $options['host_zip'] ?>" placeholder="Zip"/>
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
			<!-- Other Settings Form -->
		<h2>Other Settings</h2>
		<!-- Show morsel login on morsel details page yes or no-->
		<?php
			if(get_option('morsel_other_settings')){
				$options = array_merge($options,get_option('morsel_other_settings'));
 	        }
 	        settings_fields( 'morsel_other_settings' );
			do_settings_sections( 'morsel_other_settings' );
		?>
		<form method="post" action="options.php" id="morsel-show-login-btn-form" >
		<table class="form-table otherSettingsMorsel">
	   	    <tr>
	   	    	<td colspan="2">You Can use morsel login on your wordpress default login by just add "open-morsel-login" class to your login/Signup anchor tag. <br><b> &lt;a class="open-morsel-login" href="#"&gt;SignUp/LogIn&lt;/a&gt;</b></td>
	   	    </tr>
	  		<tr valign="top">
	  			<td colspan="2" scope="row">
	  			    <input type="checkbox" name="morsel_other_settings[hide_login_btn]" id="hide_login_btn" value="1" <?php checked( $options['hide_login_btn'], 1 ); ?> />&nbsp; Don't show morsel login button on a morsel detail page.
	  			</td>
	  		</tr>
			<tr>
			    <td><?php submit_button("Save","primary","save",null,array( 'id' => 'morsel_show_login_submit' ) ); ?></td>
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

jQuery( document ).ready(function() {
    console.log( "ready!",window.location.href.split("#")[1]);
    setTimeout(function(){ 
    	// console.log("----------------",jQuery(".etabs li.active a").attr("href"));
    	var mobile = jQuery(".etabs li.active a").attr("href")+"_li";
    	jQuery(mobile).attr("selected", "selected");
    }, 1000);
});
jQuery(".mobileMenuMorsel").change(function(){
    window.location.href = window.location.href.split("#")[0]+jQuery(this).find('option:selected').val();
})
</script>
		  <!-- Host Details Form End -->	