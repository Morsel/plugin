<?php 
	 delete_option('morsel_host_details');
	//set morsel host details info if they exists	
   	if(get_option('morsel_host_details')){

		$options = array_merge($options,get_option('morsel_host_details'));     	
	} else {
		
		//set morsel host details info if they exists from API
		$ms_options = get_option( 'morsel_settings');
		if(isset($ms_options['userid']) && $ms_options['userid'] > 0) {
		  $api_key = $ms_options['userid'] . ':' .$ms_options['key'];      
	      $jsonurl = MORSEL_API_USER_URL."/me.json?api_key=".$api_key;    
	      $json = get_json($jsonurl);	      
	      if(isset($json->data->profile)){	      	
	      	$options = array_merge($options,array(
	      					'profile_id'=>$json->data->profile->id,
	      					'host_address'=>$json->data->profile->address,
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
	} 
?>
<form method="post" action="options.php" id="morsel-host-details-form">
 	<?php settings_fields('morsel_host_details'); ?>
  	<?php do_settings_sections( 'morsel_host_details' ); ?>          	
   	<table class="form-table">
  		<tr valign="top">
  			<input type="hidden" style="width:50%" name="morsel_host_details[profile_id]" id="profile_id" value="<?php echo $options['profile_id'] ?>"/>
  			<td scope="row">Host Url:</td>
			<td>
				<input type="text" style="width:50%" name="morsel_host_details[host_url]" id="host_url" value="<?php echo $options['host_url'] ?>" disabled/>
			</td>
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
  			<input type="text" style="width:50%" name="morsel_host_details[host_company]" id="host_company" value="<?php echo $options['host_company'] ?>" placeholder="Company Name"  required/>
  			<input type="text" style="width:50%; margin-top:5px" name="morsel_host_details[host_street]" id="host_street" value="<?php echo $options['host_street'] ?>" placeholder="Street Address" required />
  			<input type="text" style="width:50%; margin-top:5px" name="morsel_host_details[host_city]" id="host_city" value="<?php echo $options['host_city'] ?>" placeholder="City" required />
  			<input type="text" style="width:50%; margin-top:5px" name="morsel_host_details[host_state]" id="host_state" value="<?php echo $options['host_state'] ?>" placeholder="State"  required/>
  			<input type="text" style="width:50%; margin-top:5px" name="morsel_host_details[host_zip]" id="host_zip" value="<?php echo $options['host_zip'] ?>" placeholder="Zip" required/>
  			</td>
  		</tr>
		<tr valign="top">
  			<td scope="row">&nbsp;</td>
			<td><?php submit_button("Save","primary","save",null,array( 'id' => 'morsel_host_submit' ) ); ?></td>
  		</tr>    		
	</table>
</form>