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

	<h2>
		<?php echo esc_html( get_admin_page_title() ); ?>
	</h2>


<div id="tab-container" class='tab-container'>
 <ul class='etabs'>
   <li class='tab'><a href="#tabs1-settings">Settings</a></li>
   <li class='tab'><a href="#tabs1-js">Post</a></li>
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
</div>
</div>

<script type="text/javascript">
    jQuery(document).ready( function() {
      jQuery('#tab-container').easytabs();
    });
</script>
<script>
window.onload =function(){

	jQuery( "#morsel_submit" ).click(function(e) {
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
				 	jQuery( "#morsel-form" ).submit();   

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
		})
	}(jQuery))
		

</script>


</div>
