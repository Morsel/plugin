<?php

/**
 * 
 * Nishant widget class
 *
 * @since 2.8.0
 */
class morsel_widget extends WP_Widget {
 
 
    /** constructor -- name this the same as the class above */
    function morsel_widget() {
        parent::WP_Widget(false, $name = 'Morsel');	
    }
 
    /** @see WP_Widget::widget -- do not rename this */
    function widget($args, $instance) {	

        extract( $args );
        // print_r($instance);
        $title 		= apply_filters('widget_title', $instance['title']);
        $view    = apply_filters('widget_view', $instance['view']);
        $options = get_option( 'morsel_settings2');
        
          $api_key = $options['userid'] . ':' .$options['key'];
              
          $jsonurl = MORSEL_API_URL."$api_key&count=".MORSEL_API_COUNT;
          $json = json_decode(file_get_contents($jsonurl)); //getting whole data
          //print_r($json);
          $count_morsel = count($json->data);

          $morsel_post_settings_sht = get_option( 'morsel_post_settings');//gettind excluding id
          $morsel_page_id = get_option( 'morsel_plugin_page_id'); //gettting discription page id
            
            if(array_key_exists('posts_id', $morsel_post_settings_sht)){
                $morsel_post_sht =  $morsel_post_settings_sht['posts_id'];
                //print_r($morsel_post_sht);
                $count_morsel_posts = count($morsel_post_sht);
                if($count_morsel==$count_morsel_posts){
                    $count_morsel = 0;
                }
            }
        ?>
        <?php if($count_morsel>0){?>
        <link rel="stylesheet" type="text/css" href=<?php echo MORSEL_PLUGIN_WIDGET_ASSEST.'css/nano.css'?>>
        
        <script type="text/javascript" src=<?php echo MORSEL_PLUGIN_WIDGET_ASSEST.'js/jquery.nanoscroller.min.js'?>></script>
        <script type="text/javascript" src=<?php echo MORSEL_PLUGIN_WIDGET_ASSEST.'js/settings.js'?>></script>
        
          <?php echo $before_widget; ?>
            <?php if ( $title )
              echo $before_title . $title . $after_title; ?>
  				    
              <div class="morsel nano" id="morsel-wrapper" >
               <div class="nano-content">
                <ul>
                 <?php foreach ($json->data as $row_sht) {
                  if(!in_array($row_sht->id,$morsel_post_sht)){ 
                      $morsel_url = add_query_arg( array('morselid' => $row_sht->id), get_permalink($morsel_page_id));
                  ?>

                   <li morsel-url="<?php echo $morsel_url;?>">
                     <div class="post-img">
                      <?php if($row_sht->photos->_800x600){?>
                       <img src="<?php echo $row_sht->photos->_800x600;?>" >
                      <?php } else { ?>
                       <img src="<?php echo MORSEL_PLUGIN_IMG_PATH.'no_image.png'?>">
                      <?php } ?>
                     </div>
                     <div class="post">
                        <h3><?php echo $row_sht->title;?></h3>
                        <p><?php echo $row_sht->summary;?></p>
                     </div>    
                    </li>
                 <?php } //if end
                 } //foreach end?> 
                </ul>
               </div>
              </div>
              <?php echo $after_widget; ?>
        <?php
      }//end count_post if
    }
 
    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {		
		   $instance = $old_instance;
		   $instance['title'] = strip_tags($new_instance['title']);
		   $instance['view'] = strip_tags($new_instance['view']);
		return $instance;
    }
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {	
 
          $title = esc_attr($instance['title']);
          $view	= esc_attr($instance['view']);
	      ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('view'); ?>"><?php _e('View:'); ?></label> 
          <input <?php echo ($view == 'grid')?"checked":"";?> class="widefat" id="grid_view" name="<?php echo $this->get_field_name('view'); ?>" type="radio" value="grid" />
            <img height="100" width="80" class="widget_image" title="Grid View" alt="Grid View" src="<?php echo MORSEL_PLUGIN_IMG_PATH."morsel_view1.png"?>">
          <!-- <input <?php echo ($view != 'grid')?"checked":"";?> class="widefat" id="non_grid_view" name="<?php echo $this->get_field_name('view'); ?>" type="radio" value="non_grid" /><img class="widget_image" alt="Simple View" title="Simple View" height="100" width="80" src="<?php echo MORSEL_PLUGIN_IMG_PATH."morsel_view2.png"?>"> -->
        </p>
		    <?php 
    }
 
 
} // end class morsel_widget
add_action('widgets_init', create_function('', 'return register_widget("morsel_widget");'));
?>
