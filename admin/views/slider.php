<?php
if(isset($hostCompany) && $hostCompany != ""){
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
    .sliderSelection {
      width: 204px;
    }
    .sliderValue{ float: right;}
    @media screen and (max-width: 400px) {
      .saveSlides{
        float: none !important;
        margin: 0 !important;
      }
    }
    .sliderField {
      width : 200px;
      float: left;
    }
    /*css for slider table*/
    .sliderTd1 { width: 130px;}
    .sliderTd2 { width: 150px;}
    .sliderTd3 { width: 50px;}
</style>
<?php
    if(get_option("morselSliderSettings") == ""){
    //add slider option by Default
      $morselSliderSettings['pause_time']=7000;
    $morselSliderSettings['trans_time']=1000;
    $morselSliderSettings['width']="250px";
    $morselSliderSettings['height']="200px";
    $morselSliderSettings['direction']="Up";
    $morselSliderSettings['pause_on_hover']="Yes";
    $morselSliderSettings['show_navigation']="true";
    add_option("morselSliderSettings", $morselSliderSettings);
    }


  echo '<div class="wrap">';
  echo '<div class="icon32" id="icon-options-general"><br></div><h2>Morsel Image Slider</h2>';
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

  echo "<div class='shs_admin_wrapper'><h5 style='text-align:center' class='shs_shortinfo'>Use Shortcode <br> <span style='font-size:14px;font-weight: bold;'>[morseldisplayslider]</span></h5></div>";
  echo '<div id="poststuff" style="position:relative;">
      <div class="postbox shs_admin_wrapper">
      <div class="handlediv" title="Click to toggle"><br/></div>
      <h3 class="hndle"><span>General Settings</span></h3>
      <div class="inside" style="padding: 15px;margin: 0;">';
    echo "<form name='settings' method='post'>";
    echo "<table>";
      ?>
      <tr>
          <td>
              <div class="sliderField"><?php _e('Width: ','shs'); ?></div>
              <div class="sliderValue">
                 <span>You can add width in "px" or in "%" like 200px or 100%</span><br>
                 <input placeholder="200px Or 100%" type='text' name='width' value='<?php echo $width; ?>' />
                </div>
          </td>
      </tr>
      <tr>
          <td>
              <div class="sliderField"><?php _e('Slider Duration: ','shs'); ?></div>
                    <div class="sliderValue">
                        <span>You can set a interval duration between slides (in ms).</span><br>
                  <input placeholder="7000 milliseconds" type='text' name='pause_time' value='<?php echo $pause_time; ?>' />
              </div>
          </td>
      </tr>
      <tr>
        <td><div class="sliderField"><?php _e('AutoPlay: ','shs'); ?></div>
          <select name="show_navigation" class="sliderSelection">
            <option <?php //sliderCheckForSelected($autoplay,"true");
            if($autoplay == "true"){echo "selected='selected'";}
             ?> ><?php _e('true','shs'); ?></option>
            <option <?php //sliderCheckForSelected($autoplay,"false");
            if($autoplay == "false"){echo "selected='selected'";}
            ?> ><?php _e('false','shs'); ?></option>
          </select>
        </td>
      </tr>
      <tr><td style="font-weight:normal;"><input type='submit' name='jsetsub'  class='button-primary' value='SAVE SETTINGS' /></td></tr>
      <?php
      echo "</table>";
      echo "</form>";
      echo '</div></div></div>';
  echo '</div>'; // .wrap

     //function for selcted checkbox
    /*function sliderCheckForSelected($option,$check){
    if($option==$check){
      echo "selected='selected'";
    }
    }*/
?>
<div class="sliderDiv">
<a href="javascript:void(0);" onclick="editSliderById()">Add New</a><img src="<?=MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif" class="loaderImageSlider" id="loaderImageSliderAdd" style="display:none;"/>
 <table class="widefat sliderDivList">
  <thead>
    <tr>
      <th class='manage-column column-categories'><span>Slider</span></th>
      <th class='manage-column column-title sortable desc'><span>Shortcode</span></th>
      <th class='manage-column column-date sortable asc'>Action</th>
      </tr>
  </thead>
  <tbody id="sliderListTbody">

  </tbody>
  <tfoot>
    <tr>
      <th class='manage-column column-categories'><span>Slider</span></th>
      <th class='manage-column column-title sortable desc '><span>Shortcode</span></th>
      <th class='manage-column column-date sortable asc '>Action</th>
    </tr>
  </tfoot>
 </table>
</div>
<div id="addSliderDiv" style="display:none;">
      <div style="position:relative;">
    <form name="qord" method="post" id="slideFormSubmit">
      <div class="postbox shs_admin_wrapper">
        <div class="handlediv" title="Click to toggle"><br/></div>
        <h3 class="hndle" style="font-size:12px; margin:0; padding:6px;"><span><?php _e('Select morsels slides by checkbox to display in your slider','shs'); ?></span></h3>
        <div style="clear:both; text-align:center; padding:10px 0 0 0px;"><input type="button" name="joptsv" class="button-primary saveSlides" value="SAVE SLIDES" onclick="saveMorselSlider()" /></div>
        <div class="inside" style="padding: 15px;margin: 0;">
         <div id="joptions"></div>
          </div>
        </div>
      </form>
    </div>
</div>
<script type="text/javascript">
jQuery( document ).ready(function() {
  getSliderList();
});
var sliderID;
function editSliderById(sliderId){
  // jQuery('#joptions').html("");
  if(sliderId == undefined){
    sliderID = "";
    jQuery("#loaderImageSliderAdd").css('display','block');
  } else {
  jQuery("#loaderImageSlider"+sliderId).css('display','block');
    sliderID = sliderId
  }
  jQuery.ajax({
    url: "<?php echo site_url()?>" + "/index.php?pagename=morsel_ajax_admin_slider&sliderId="+sliderID,
    beforeSend: function(data){
      jQuery('#joptions').html("");
    },
    success: function(data) {
    if (data.trim().length > 1) {
        jQuery('#joptions').append(data);
    }
  },
    complete: function() {
        var url = "#TB_inline?width=500&height=400&inlineId=addSliderDiv";
      tb_show("Slider", url);
      jQuery(".loaderImageSlider").css('display','none');
    }
  });
}

function saveMorselSlider(){
  if (jQuery('#sliderNameSlider').val() == "") {
      alert("Please enter slider name.");
      return;
    }
  if (jQuery('.sliderCheckbox:checked').length == 0) {
      alert("Select atleast 1 slide.");
      return;
    }
    jQuery.ajax({
      url: "<?php echo site_url()?>" + "/index.php?pagename=sliderSave&sliderID="+sliderID,
      data:jQuery("#slideFormSubmit input").serialize(),//only input
      success: function(data) {
      getSliderList();
            tb_remove();
      //location.reload();
    }
  });
}

function getSliderList(){
  jQuery("#sliderListTbody").html("");
  jQuery.ajax({
      url: "<?php echo site_url()?>" + "/index.php?pagename=getSliderListing",
      success: function(html) {
      jQuery("#sliderListTbody").append(html);
    }
  });
}

function deleteSliderById(sliderID){
    jQuery.ajax({
      url: "<?php echo site_url()?>" + "/index.php?pagename=sliderDelete&sliderID="+sliderID,
      success: function(data) {
      //location.reload();
      getSliderList();
    }
  });
}


</script>

<? } else { ?>
Please Enter Host Details First.
<? } ?>
