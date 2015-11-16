<?
    if(get_option("morselSliderSettings") == ""){
		//add slider option by Default
	   	$morselSliderSettings['pause_time']=7000;
		$morselSliderSettings['trans_time']=1000;
		$morselSliderSettings['width']="250px";
		$morselSliderSettings['height']="200px";
		$morselSliderSettings['direction']="Up";
		$morselSliderSettings['pause_on_hover']="Yes";
		$morselSliderSettings['show_navigation']="Yes";
		add_option("morselSliderSettings", $morselSliderSettings);
    }


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

	$morselSliderSettings=get_option('morselSliderSettings');
	$pause_time=$morselSliderSettings['pause_time'];
	$width=$morselSliderSettings['width'];
	// $height=$morselSliderSettings['height'];

	$autoplay=$morselSliderSettings['show_navigation'];

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
			<!-- <tr><td><?php _e('Height','shs'); ?></td><td><input type='text' name='height' value='<?php echo $height; ?>' /> <?php _e('eg:200px','shs'); ?></td></tr>
			 -->
			<tr>
				<td><?php _e('AutoPlay','shs'); ?></td>
				<td>
					<select name="show_navigation">
						<option <?php sliderCheckForSelected($autoplay,"true"); ?> ><?php _e('true','shs'); ?></option>
						<option <?php sliderCheckForSelected($autoplay,"false"); ?> ><?php _e('false','shs'); ?></option>
					</select>
				</td>
			</tr>
			<tr><td><?php _e('Slider Duration','shs'); ?></td><td><input type='text' name='pause_time' value='<?php echo $pause_time; ?>' /> <?php _e('eg:7000','shs'); ?></td></tr>
			<tr><td colspan="2" style="font-weight:normal;"><input type='submit' name='jsetsub'  class='button-primary' value='SAVE SETTINGS' /></td></tr>
			<?php
			echo "</table>";
			echo "</form>";
			echo '</div></div></div>';

	?>
	<?php
	echo '</div>'; // .wrap
function sliderCheckForSelected($option,$check){
	if($option==$check){
		echo "selected='selected'";
	}
}

?>
<style type="text/css">
	.shs_shortinfo{
    background: none repeat scroll 0 0 #DBEFFE;
    border: 1px solid #98B9D0;
    color: #333333;
    font-size: 12px;
    font-weight: normal;
    line-height: 22px;
    padding: 10px;
}
</style>
    <?php
	if(isset($_POST['joptsv'])){

	  $contents=$_POST['cnt'];
	  update_option('shs_slider_contents',$contents);
	?>
      <div class="updated" style="width:686px;"><p><strong><font color="green"><?php _e('Slides Saved','shs'); ?></font></strong></p></div>
    <?php
	}
    $contents = get_option('shs_slider_contents');
    //print_r($contents);

	?>
	<div id="poststuff" style="position:relative;">
		<form name="qord" method="post">
			<div class="postbox shs_admin_wrapper">
				<div class="handlediv" title="Click to toggle"><br/></div>
				<h3 class="hndle"><span><?php _e('Select Morsel as Slides for slider','shs'); ?></span><input type="submit" name="joptsv" class="button-primary" value="SAVE SLIDES" style="float:right;margin:-4px" /></h3>
         <div class="inside" style="padding: 15px;margin: 0;">
          <table class="wp-list-table widefat posts fixed">
						<thead>
							<tr>
								<th scope='col' class='manage-column column-title sortable desc' style="padding-left: 10px;">Morsel Name</th>
								<th scope='col' class='manage-column column-author'>Image</th>
								<th scope='col' class='manage-column column-categories'><input type="checkbox" name="main" class="checkAllCheckbox" value="main" style="margin:0px"></th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th scope='col' class='manage-column column-title sortable desc' style="padding-left: 10px;">Morsel Name</th>
								<th scope='col' class='manage-column column-author'>Image</th>
								<th scope='col' class='manage-column column-categories'><input type="checkbox" name="main" class="checkAllCheckbox" value="main"  style="margin:0px"></th>
							</tr>
						</tfoot>
					    <tbody id="joptions">
					    <?php

					    foreach ($jsonPost->data as $row) {
					    	if(!$row->is_submit){
					     ?>

					    	<tr>
					    		<td><a href="<?php echo $morsel_url?>" target="_blank"><?php echo $row->title?></a></td>
					    		<td>
					    			<?php
					    			$imageUrl = "";

                                     if($row->primary_item_photos->_992x992 != ''){
                                      $imageUrl = str_replace("_992x992_", "", $row->primary_item_photos->_992x992);
                                      $imageUrlAdmin = $row->primary_item_photos->_100x100;
							      ?>
							      <a href="<?php echo $imageUrl;?>" target="_blank" >
	                				<img src="<?php echo $imageUrlAdmin;?>" height="100" width="100">
	                			  <a>
					    		</td>

					    		<td>
					    			<input type="checkbox" <? if(array_key_exists($row->id, $contents)){?>checked<? } ?> name="cnt[<?=$row->id;?>]" value="<?=$imageUrl?>">
					    		</td>
					    		<? } else { ?>
					    		</td>
					    		<td></td>
					    		<? } ?>

					    	</tr>

					    <? } } ?>
					    </tbody>
				  </table>

				  <div id="no-moreSlider" style="display:none;">No More Morsels</div>
					  <div id="ajaxLoaderSlider"  style="display:none;">
					        <span><img src="<?php echo MORSEL_PLUGIN_IMG_PATH;?>ajax-loader.gif"></span>
					  </div>
						<!-- <input type="submit" name="joptsv" class="button-primary" style="margin-left: 13px;" value="SAVE SLIDES" /> -->
				</div>
			</div>
		</div>
	</form>
</div>


<script type="text/javascript">
	// var noMoreMorsel;
	// jQuery(window).scroll(function() {
	// 	if(jQuery('#joptions').css('display') != 'none'){
	// 	    if(noMoreMorsel != true){
	// 	    	if (jQuery(window).scrollTop() + jQuery(window).height() == jQuery(document).height()) {
	// 			    jQuery('#no-moreSlider').hide();
	// 			    var morsePageCount = 1;
	// 			    jQuery("#ajaxLoaderSlider").css("display", "block");
	// 			    jQuery.ajax({
	// 			        url: "<?php echo site_url()?>" + "/index.php?pagename=morsel_ajax_admin&view=slider&page_id=" + parseInt(++morsePageCount),
	// 			        success: function(data) {
	// 						if (data.trim().length > 1) {
	// 							jQuery('#joptions li:last').after(data);
	// 						} else {
	// 							morsePageCount--;
	// 							noMoreMorsel = true;
	// 							jQuery('#no-moreSlider').show();
	// 						}
	// 			        }, error: function() {
	// 				        morsePageCount--;
	// 				    }, complete: function() {
	// 				        jQuery("#ajaxLoaderSlider").css("display", "none");
	// 				    }
	// 			    });
	// 		    }
	// 	    }
	// 	}
	// });

//for check and uncheck
jQuery(".checkAllCheckbox").change(function () {
    jQuery("input:checkbox").prop('checked', jQuery(this).prop("checked"));
});

jQuery(".checkBoxSlider").change(function(){
	if(jQuery(this).attr('checked')) {
	    alert("checked");
	} else {
	    alert("unchecked");
	}
})
</script>
