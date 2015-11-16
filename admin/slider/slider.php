<?
	if(isset($_REQUEST['page']))
	{
	    wp_enqueue_script('jquery');
	    if($_REQUEST['page']=="morselSlider")
		{
		  wp_enqueue_script('jquery-ui-sortable');
		  wp_enqueue_script('shs-admin-script',plugins_url('js/slider.js',__FILE__), array('jquery'),'',1);
		  wp_enqueue_style('shs-admin-style',plugins_url('css/slider.css',__FILE__), false, '1.0.0' );
		}
	}

	//add slider option by Default
   	$morselSliderSettings['pause_time']=7000;
	$morselSliderSettings['trans_time']=1000;
	$morselSliderSettings['width']="250px";
	$morselSliderSettings['height']="200px";
	$morselSliderSettings['direction']="Up";
	$morselSliderSettings['pause_on_hover']="Yes";
	$morselSliderSettings['show_navigation']="Yes";
	add_option("morselSliderSettings", $morselSliderSettings);


function sliderMorsel() {
	echo '<div class="wrap">';
	echo '<div class="icon32" id="icon-options-general"><br></div><h2>Morsel Image Slider </h2>';
	if(isset($_POST['jsetsub']))
	{
		$pause_time=$_POST['pause_time'];
		$trans_time=$_POST['trans_time'];
		$width=$_POST['width'];
		$height=$_POST['height'];
		$direction=$_POST['direction'];
		$pause_hover=$_POST['pause_on_hover'];
		$show_navigation=$_POST['show_navigation'];
		
		$morselSliderSettings['pause_time']=$pause_time;
		$morselSliderSettings['trans_time']=$trans_time;
		$morselSliderSettings['width']=$width;
		$morselSliderSettings['height']=$height;
		$morselSliderSettings['direction']=$direction;
		$morselSliderSettings['pause_on_hover']=$pause_hover;
		$morselSliderSettings['show_navigation']=$show_navigation;
		update_option('morselSliderSettings',$morselSliderSettings);
		?>
		<div class="updated" style="width:686px;"><p><strong><font color="green"><?php _e('Setting Saved' ); ?></font></strong></p></div>
		<?php
	}
	?>
	<div class="shs_banner_wrapper">
		<!-- WP-Banner Starts Here -->
	
		<!-- WP-Banner Ends Here -->
	</div>
	<?php
	$morselSliderSettings=get_option('morselSliderSettings');
	$pause_time=$morselSliderSettings['pause_time'];
	$trans_time=$morselSliderSettings['trans_time'];
	$width=$morselSliderSettings['width'];
	$height=$morselSliderSettings['height'];
	$direction=$morselSliderSettings['direction'];
	$pause_hover=$morselSliderSettings['pause_on_hover'];
	$show_navigation=$morselSliderSettings['show_navigation'];
	echo "<div class='shs_admin_wrapper'><h5 style='text-align:center' class='shs_shortinfo'>Use Shortcode <br> <span style='font-size:14px;font-weight: bold;'>[morselDisplaySlider]</span></h5></div>";
	echo '<div id="poststuff" style="position:relative;">
		  <div class="postbox shs_admin_wrapper">
		  <div class="handlediv" title="Click to toggle"><br/></div>
		  <h3 class="hndle"><span>General Settings</span></h3>
		  <div class="inside" style="padding: 15px;margin: 0;">';
			echo "<form name='settings' method='post'>";
			echo "<table>";
			?>
			<tr><td><?php _e('Width','shs'); ?></td><td><input type='text' name='width' value='<?php echo $width; ?>' /> <?php _e('eg:200px','shs'); ?></td></tr>
			<tr><td><?php _e('Height','shs'); ?></td><td><input type='text' name='height' value='<?php echo $height; ?>' /> <?php _e('eg:200px','shs'); ?></td></tr>
			
			<tr>
				<td><?php _e('Show Navigation','shs'); ?></td>
				<td>
					<select name="show_navigation">
						<option <?php sliderCheckForSelected($show_navigation,"Yes"); ?> ><?php _e('Yes','shs'); ?></option>
						<option <?php sliderCheckForSelected($show_navigation,"No"); ?> ><?php _e('No','shs'); ?></option>
					</select>
				</td>
			</tr>
			
			<tr>
				<td><?php _e('Pause on Hover','shs'); ?></td>
				<td>
					<select name="pause_on_hover">
						<option <?php sliderCheckForSelected($pause_hover,"Yes"); ?> ><?php _e('Yes','shs'); ?></option>
						<option <?php sliderCheckForSelected($pause_hover,"No"); ?> ><?php _e('No','shs'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php _e('Animation Type','shs'); ?></td>
				<td>
					<select name="direction">
						<option value="Right" <?php sliderCheckForSelected($direction,"Right"); ?> ><?php _e('Horizontal Slide','shs'); ?></option>
						<option value="Down" <?php sliderCheckForSelected($direction,"Down"); ?> ><?php _e('Vertical Slide','shs'); ?></option>
					</select>
				</td>
			</tr>
			<tr><td><?php _e('Pause time','shs'); ?></td><td><input type='text' name='pause_time' value='<?php echo $pause_time; ?>' /> <?php _e('eg:7000','shs'); ?></td></tr>
			<tr><td><?php _e('Transition time','shs'); ?></td><td><input type='text' name='trans_time' value='<?php echo $trans_time; ?>' /> <?php _e('eg:1000','shs'); ?></td></tr>
			<tr><td colspan="2" style="font-weight:normal;"><input type='submit' name='jsetsub'  class='button-primary' value='SAVE SETTINGS' /></td></tr>
			<?php
			echo "</table>";
			echo "</form>";
			echo '</div></div></div>';
			
	?>
    <?php
	if(isset($_POST['joptsv']))
	{
	$contents=$_POST['cnt'];
	update_option('shs_slider_contents',$contents);
	?>
    <div class="updated" style="width:686px;"><p><strong><font color="green"><?php _e('Slides Saved','shs'); ?></font></strong></p></div>
    <?php
	}
	?>
    <style>#joptions{ list-style-type: none; margin: 0; padding: 0; }</style>
	
	<div id="poststuff" style="position:relative;display:none;">
		<div class="postbox shs_admin_wrapper">
			<div class="handlediv" title="Click to toggle"><br/></div>
			<h3 class="hndle"><span><?php _e('Add Slider Contents Below (Drap up-down to re-order)','shs'); ?></span></h3>
			<div class="inside" style="padding: 15px;margin: 0;">
				<div>
					<h5>
					<?php _e('More Than two slides Recommended.','shs'); ?>
					</h5>
					<form name="qord" method="post">
						<ul id="joptions">
							<?php
							$contents=get_option('shs_slider_contents');
							if($contents)
							{   $k = 0;
								foreach($contents as $content)
								{   $k++;
									if($content)
									{
									$content=stripslashes($content);
								?>
								<li style="clear:both; height:auto; height:110px;">
								   <div style="width:70%; float:left;">
								     <input name="cnt[]" rows="3" style="width:50%;" class="imageUploadAdd" id="uploadImageSlides<?=$k?>" value="<?php echo $content; ?>">
								     <input type="button" value="Upload" onclick='addSlidesImageNew("uploadImageSlides<?=$k?>")'><input type="button" class="shs_del" title="Delete" value="" onClick="shs_delete_field(this);"  /><input type="button" class="shs_add" title="Add New" value="" onClick="shs_add_to_field(this);"  />
								   </div>
								   <div style="width:20%; float:right;"><img src="<?php echo $content; ?>" id="uploadImageSrc<?=$k?>" width="100" style="max-height:95px;"></div>
								</li>
								<?php
									} // if($content)
								}// 	foreach($contents as $content)
							} // if($contents)
							?>
							<li><input name="cnt[]" rows="3" style="width:70%;" class="imageUploadAdd" id="addSlidesImageNew1" ><input type="button" value="Upload" onclick="addSlidesImageNew('addSlidesImageNew1')"><input type="button" class="shs_del" title="Delete" value="" onClick="shs_delete_field(this);"  /><input type="button" class="shs_add" title="Add New" value=""  onClick="shs_add_to_field(this);"  /></li>
						</ul>
						<input type="submit" name="joptsv" class="button-primary" style="margin-left: 13px;" value="SAVE SLIDES" />
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<?php
	echo '</div>'; // .wrap
}
sliderMorsel();

function sliderCheckForSelected($option,$check){
	if($option==$check){
		echo "selected='selected'";
	}
}

// function shs_slider_cont_count()
// {
// global $wpdb;
// $number=0;
// $contents=get_option('shs_slider_contents');
// 	if($contents)
// 	{
// 		foreach($contents as $content)
// 		{
// 			if($content)
// 			{
// 			$number++;
// 			} 
// 		}
// 	}
// return $number;
// }


?>