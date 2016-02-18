<?php
add_action('wp_enqueue_scripts', 'sliderFrontendScripts');
function sliderFrontendScripts() {
  if(!is_admin()){
    wp_enqueue_style('shs-front',plugins_url('admin/slider/css/shs-front.css',__FILE__));
    // wp_enqueue_script('shs-front-script',plugins_url('admin/slider/js/jquery-1.9.1.min.js',__FILE__));
    wp_enqueue_script('shs-front-script',plugins_url('admin/slider/js/jssor.slider.mini.js',__FILE__));
  }
}

function morseldisplayslider($atts)
{ 
  ob_start();

  //print_r($atts);
  $atts = shortcode_atts( array("slider" => "1") , $atts, 'morseldisplayslider' );
  $shs_settings=get_option('morselSliderSettings');
  $slider_duration=$shs_settings['pause_time'];
  $width= ($shs_settings['width'] != "")?$shs_settings['width']:'100%';
  $autoplay=$shs_settings['show_navigation'];
  $slug = get_post(get_option("morsel_plugin_page_id"))->post_name;
  ?>
  <style type="text/css">
   .sliderPTag { left: 0;
    margin: auto;
    padding: 10px;
    right: 0;
    text-align: center;
    top: 68%;
    font-size: 28px;
    position: absolute;
    z-index: 999;
    color: #ffffff;
    font-weight: 700px;
    text-shadow: 1px 1px 1px rgb(0, 0, 0);
    text-transform: capitalize;
  }
.img-circular{
  width: 72px;
  height: 72px;
  display: block;
  border-radius: 50%;
  margin: 0 auto; 
}
  </style>
  <div style="width:<?=$width?>;  margin:0 auto;">
    <div id="jssor_1" style="position: relative; margin: 0 auto; top: 0px; left: 0px; width: 1200px; height: 800px; overflow: hidden; visibility: hidden;">
        <!-- Loading Screen -->

        <div data-u="loading" style="position: absolute; top: 0px; left: 0px;">
            <div style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
            <div class="sliderAboveDiv"></div>
        </div>
        <div data-u="slides" style="cursor: default; position: relative; top: 0px; left: 0px; width: 1200px; height: 800px; overflow: hidden;">
        <?php $contents = get_option('shs_slider_contents');
          //print_r($contents);
          for($S=0;$S<count($contents);$S++){
            if($atts['slider'] == $contents[$S]["name"]){
               $sliderContent = $contents[$S]["slider"];
               break;
            }
          }
          //print_r($sliderContent);
          foreach ($sliderContent as $key => $value) { 
            $imageArray = explode("@@$@@", $value);
            //print_r($imageArray);
            ?>
            <div data-p="225.00" style="display: none;">
              <div class="sliderPTag"><?=$imageArray[1]?>
                <? if($imageArray[2] != ''){?>
                  <p class="img-circular" style="background-image: url(https://morsel-staging.s3.amazonaws.com/<?=$imageArray[2]?>);"></p>
                <? } ?>
              </div>
              <a class="morsel-slider-img" target="blank" href="<?php echo site_url(),"/",$slug,"/?morselid=",$key;?>">
              <img data-u="image" src="https://morsel-staging.s3.amazonaws.com/<?=$imageArray[0]?>" />
              </a>
            </div>
            <? } ?>
        </div>
        <!-- Bullet Navigator -->
        <div data-u="navigator" class="jssorb05" style="bottom:16px;right:6px;" data-autocenter="1">
            <!-- bullet navigator item prototype -->
            <div data-u="prototype" style="width:16px;height:16px;"></div>
        </div>
        <!-- Arrow Navigator -->
        <span data-u="arrowleft" class="jssora22l" style="top:123px;left:12px;width:40px;height:58px;" data-autocenter="2"></span>
        <span data-u="arrowright" class="jssora22r" style="top:123px;right:12px;width:40px;height:58px;" data-autocenter="2"></span>
    </div>
  </div>


    <!-- use jssor.slider.debug.js instead for release -->
    <script>
        jQuery(document).ready(function ($) {

            var jssor_1_options = {
              $AutoPlay: "<?=$autoplay;?>",
              $SlideDuration: <?=$slider_duration;?>,
              $SlideEasing: $Jease$.$OutQuint,
              $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
              },
              $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$
              }
            };

            var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

            //responsive code begin
            //you can remove responsive code if you don't want the slider scales while window resizes
            function ScaleSlider() {
                var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
                if (refSize) {
                    refSize = Math.min(refSize, 1920);
                    jssor_1_slider.$ScaleWidth(refSize);
                }
                else {
                    window.setTimeout(ScaleSlider, 30);
                }
            }
            ScaleSlider();
            $(window).bind("load", ScaleSlider);
            $(window).bind("resize", ScaleSlider);
            $(window).bind("orientationchange", ScaleSlider);
            //responsive code end
        });
    </script>

<? 
return ob_get_clean();
} //function end
  add_shortcode('morseldisplayslider', 'morseldisplayslider');

